#!/bin/bash

# Name: Tarun Gidwani
# Script name: destroy-env.sh
# Purpose: Destroys all EC2 objects in default region

log_file=delete-log.txt

# Deletes all DB instances and the data 
# stored in them (RDS)
function delete_all_db_instances
{
		local db_instance_identifiers=($(aws rds describe-db-instances --query DBInstances[*].DBInstanceIdentifier \
																		 --output text 2>> $log_file))

		for db_instance_identifier in "${db_instance_identifiers[@]}"
		do
			aws rds delete-db-instance --db-instance-identifier "$db_instance_identifier" --skip-final-snapshot > /dev/null 2>> $log_file
			aws rds wait db-instance-deleted --db-instance-identifier "$db_instance_identifier" 2>> $log_file
		done
}

# Deletes all S3 buckets and 
# all the objects in each bucket
function delete_all_s3_buckets
{
	all_s3_buckets=($(aws s3 ls | cut -d' ' -f3))
	
	for s3_bucket in "${all_s3_buckets[@]}"
	do
		aws s3 rm s3://$s3_bucket --recursive > /dev/null 2>> $log_file
		aws s3 rb s3://$s3_bucket > /dev/null 2>> $log_file
	done
}

# Deletes all simple and
# fifo queues in SQS
function delete_all_queues
{
	all_queue_urls=($(aws sqs list-queues --query QueueUrls[*] --output text))

	if [ ${all_queue_urls[0]} == "None" ] && [ ${#all_queue_urls[@]} -eq 1 ]
	then
		return;
	fi
	
	for queue_url in "${all_queue_urls[@]}"
	do
		aws sqs delete-queue --queue-url "$queue_url"
	done
}

# Deletes all auto scaling groups and terminates
# all instances attached to it in the default
# region
function delete_all_auto_scaling_groups 
{
	local min_size=0
	local max_size=0
	local desired_capacity=0
	local query_auto_scaling_group_cmd=""
	local auto_scaling_group_names=($(aws autoscaling describe-auto-scaling-groups --output "text"\
                                     --query "AutoScalingGroups[*].AutoScalingGroupName" 2>> $log_file))
	for auto_scaling_group_name in "${auto_scaling_group_names[@]}"
	do
		local instances_of_asg=$(aws autoscaling describe-auto-scaling-groups --auto-scaling-group $auto_scaling_group_name\
														 --query "AutoScalingGroups[].Instances[].InstanceId" --output "text" 2>> $log_file)

		aws autoscaling update-auto-scaling-group --auto-scaling-group-name $auto_scaling_group_name\
		--min-size $min_size --max-size $max_size --desired-capacity $desired_capacity 2>> $log_file
		aws ec2 wait instance-terminated --instance-ids $instances_of_asg 2>> $log_file
		# Even after waiting for the instances attached to the auto-scaling-group to terminate, we run
		# run into the issue of ScalingActivityInProgress (some cleanup still in progress) and this 
		# prevents the auto-scaling-groups from being deleted. So we have to either sleep for a 
		# arbitrary amount of time or use the --force-delete option
		aws autoscaling delete-auto-scaling-group --auto-scaling-group-name $auto_scaling_group_name \
		--force-delete 2>> $log_file
	done
}

# Deletes all launch configurations
# in the default region
function delete_all_launch_configurations 
{
	local all_launch_configurations=($(aws autoscaling describe-launch-configurations \
																		 --query "LaunchConfigurations[*].LaunchConfigurationName" \
																		 --output text 2>> $log_file))
	for launch_configuration in ${all_launch_configurations[@]}
	do
		aws autoscaling delete-launch-configuration --launch-configuration-name $launch_configuration \
		2>> $log_file
	done
}

# Deletes all classic load balancers
# in the default region
function delete_all_load_balancers 
{
	local all_load_balancers=($(aws elb describe-load-balancers --query "LoadBalancerDescriptions[*].LoadBalancerName" \
													 --output "text" 2>> $log_file))

	for load_balancer in ${all_load_balancers[@]}
	do
		aws elb delete-load-balancer --load-balancer-name $load_balancer 2>> $log_file
	done
}

# Terminates all instances in
# the default region
function terminate_all_instances 
{
	local all_instances=($(aws ec2 describe-instances --query "Reservations[*].Instances[*].InstanceId" --output text 2>> $log_file))
	
	for instance in ${all_instances[@]}
	do
		aws ec2 terminate-instances --instance-id $instance > /dev/null 2>> $log_file
		aws ec2 wait instance-terminated --instance-ids $instance 2>> $log_file
	done
}

# Destroys all EC2 objects:
# RDS DB instances
# Auto-scaling-groups
# Launch configurations
# Classic load balancers
# Instances
function destroy_env
{

	printf "Deleting all DB instances in RDS........\n"
	delete_all_db_instances
	printf "Completed successfully!\n"

	printf "Deleting all buckets in S3........\n"
	delete_all_s3_buckets
	printf "Completed successfully!\n"

	printf "Deleting all queues in SQS........\n"
	delete_all_queues
	printf "Completed successfully!\n"

	printf "Deleting all auto-scaling-groups.........\n"
	delete_all_auto_scaling_groups
	printf "Completed successfully!\n"

	printf "Deleting all launch configurations.........\n"
	delete_all_launch_configurations
	printf "Completed successfully!\n"

	printf "Deleting all load balancers.........\n"
	delete_all_load_balancers
	printf "Completed successfully!\n"

	printf "Terminating all instances.........\n"
	terminate_all_instances
	printf "Completed successfully!\n"
}

destroy_env
