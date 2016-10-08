#!/bin/bash






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
                                     --query "AutoScalingGroups[*].AutoScalingGroupName" 2>> delete-log.txt))
	for auto_scaling_group_name in "${auto_scaling_group_names[@]}"
	do
		local instances_of_asg=$(aws autoscaling describe-auto-scaling-groups --auto-scaling-group $auto_scaling_group_name\
														 --query "AutoScalingGroups[].Instances[].InstanceId" --output "text" 2>> delete-log.txt)

		aws autoscaling update-auto-scaling-group --auto-scaling-group-name $auto_scaling_group_name\
		--min-size $min_size --max-size $max_size --desired-capacity $desired_capacity 2>> delete-log.txt
		aws ec2 wait instance-terminated --instance-ids $instances_of_asg 2>> delete-log.txt
		aws autoscaling describe-scaling-activities --auto-scaling-group-name $auto_scaling_group_name &>> sa.txt
		# Even after waiting for the instances attached to the auto-scaling-group to terminate, we run
		# run into the issue of ScalingActivityInProgress (some cleanup still in progress) and this 
		# prevents the auto-scaling-groups from being deleted. So we have to either sleep for a 
		# arbitrary amount of time or use the --force-delete option
		aws autoscaling delete-auto-scaling-group --auto-scaling-group-name $auto_scaling_group_name \
		--force-delete 2>> delete-log.txt
	done
}

# Destroys all EC2 objects:
# Auto-scaling-groups
function destroy_env
{
	printf "Deleting auto-scaling-groups.........\n"
	delete_all_auto_scaling_groups
	printf "Completed successfully!\n"
}

destroy_env
