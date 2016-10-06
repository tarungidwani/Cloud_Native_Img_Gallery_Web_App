#!/bin/bash

# Variables for creating launch configuration
instance_ami="ami-06b94666"
instance_type="t2.micro"
launch_configuration_name="bootstrap-website-lc"
instance_user_data="file://installenv.sh"
security_group_name="bootstrap-website-sec-group"
key_name="tarun-itmo-444-access-key"

# Variables for creating autoscaling-group
auto_scaling_group_name="bootstrap-website-asg"
desired_capacity=4
instance_zone="us-west-2b"

# Creates a new launch configuration with the name bootstrap-website-lc
aws autoscaling create-launch-configuration --image-id "$instance_ami" --instance-type "$instance_type" \
--launch-configuration-name "$launch_configuration_name" --user-data "$instance_user_data" \
--security-groups "$security_group_name" --key-name "$key_name" 2>> log.txt

# Creates a new auto scaling group with the name bootstrap-website-asg
aws autoscaling create-auto-scaling-group --launch-configuration-name "$launch_configuration_name" \
--auto-scaling-group-name "$auto_scaling_group_name" --desired-capacity "$desired_capacity" \
--min-size "$desired_capacity" --max-size "$desired_capacity" --availability-zones "$instance_zone"













