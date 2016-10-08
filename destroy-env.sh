#!/bin/bash

# Name: Tarun Gidwani
# Script name: destroy-env.sh
# Purpose: Destroys all EC2 objects in default region

log_file=delete-log.txt

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


# Destroys all EC2 objects:
# Auto-scaling-groups
function destroy_env
{
	printf "Deleting all auto-scaling-groups.........\n"
	delete_all_auto_scaling_groups
	printf "Completed successfully!\n"

	printf "Deleting all launch configurations.........\n"
	delete_all_launch_configurations
	printf "Completed successfully!\n"

	printf "Deleting all load balancers.........\n"
	delete_all_load_balancers
	printf "Completed successfully!\n"
}

destroy_env
