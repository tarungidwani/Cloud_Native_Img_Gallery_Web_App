#!/bin/bash

# Variables for creating launch configuration
instance_ami="ami-06b94666"
instance_type="t2.micro"
launch_configuration_name="bootstrap-website-lc"
instance_user_data="file://installenv.sh"
security_group_name="bootstrap-website-sec-group"
key_name="tarun-itmo-444-access-key"

# Creates a new launch configuration with the name bootstrap-website-lc
aws autoscaling create-launch-configuration --image-id "$instance_ami" --instance-type "$instance_type" \
--launch-configuration-name "$launch_configuration_name" --user-data "$instance_user_data" \
--security-groups "$security_group_name" --key-name "$key_name" 2>> log.txt













