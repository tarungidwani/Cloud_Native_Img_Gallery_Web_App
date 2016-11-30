#!/bin/bash

# Name: Tarun Gidwani
# Script name: destroy.sh
# Purpose: Creates an autoscaling group with a desired number of 
#          instances and a load balancer attached to it

# Checks if the user specified the 3 
# required arguments to this script
if [ $# -ne 4 ]
then
	printf "\n***\nPlease provide the following 3 arguments in the given order:\n\n"
	printf " 1. AMI ID\n 2. key-name\n 3. security-group\n 4. iam-profile\n"
	printf "***\n\n"
	exit 1
fi

# Variables for creating launch configuration
instance_ami="$1"
key_name="$2"
security_group_name="$3"
iam_profile="$4"
launch_configuration_name="web-app-lc"
# No use for number of
# instances to launch as I am
# not explicitly launching 
# instances
instance_type="t2.micro"
instance_user_data="file://installenv.sh"

# Creates a new launch configuration with the name web-app-lc
aws autoscaling create-launch-configuration --image-id "$instance_ami" --instance-type "$instance_type" \
--launch-configuration-name "$launch_configuration_name" --user-data "$instance_user_data" \
--security-groups "$security_group_name" --key-name "$key_name" --iam-instance-profile "$iam_profile" 2>> log.txt

# Variables for creating autoscaling-group
auto_scaling_group_name="web-app-asg"
desired_capacity=4
instance_zone="us-west-2b"

# Creates a new auto scaling group with the name web-app-asg
aws autoscaling create-auto-scaling-group --launch-configuration-name "$launch_configuration_name" \
--auto-scaling-group-name "$auto_scaling_group_name" --desired-capacity "$desired_capacity" \
--min-size "$desired_capacity" --max-size "$desired_capacity" --availability-zones "$instance_zone" 2>> log.txt

# ** Unable to wait for instances of the auto-scaling-group created 
#    above to become running because after running the create-auto-scaling-group 
#    command it does not return anything and the group is not created immdeiately. 
#    So when I try to run describe-autoscaling-groups and query for the intance-ids 
#    of the above created autoscaling group I get back an empty string. Thus this 
#     prevents me from passing the instance-ids of said auto-scaling group to 
#    the wait instance-running command preventing my script to wait till 
#    all these instances of the autoscaling group become running
# **
# Tried the following but unsuccessful without using the sleep 
# command with an arbitrary time to wait. Using sleep did not 
# seem like the best approach so I decided not to wait for the 
# instances of the autoscaling group to become running.

# asg_instance_ids=`aws autoscaling describe-auto-scaling-groups 
#--auto-scaling-group-names  "web-app-asg" 
# --query AutoScalingGroups[].Instances[].InstanceId --output "text"`

# Waits for all instances in auto-scaling-group to spawn and become running
# aws ec2 wait instance-running --instance-ids $asg_instance_ids  2> log.txt

# Variables for creating a classic load balancer
load_balancer_name="web-app-lb"
lb_listener="Protocol=HTTP,LoadBalancerPort=80,InstanceProtocol=HTTP,InstancePort=80"
sec_group_id=$(aws ec2 describe-security-groups --group-name $security_group_name \
																							  --query SecurityGroups[*].GroupId --output text)

# Creates a new classic load balancer with the name web-app-lb
lb_dns_name=$(aws elb create-load-balancer --load-balancer-name "$load_balancer_name" --availability-zones "$instance_zone" \
--listeners "$lb_listener" --security-groups "$sec_group_id" --output "text" 2>> log.txt)

# Variables for creating and setting 
# a sitcky session cookie policy to 
# a classic load balancer
sticky_session_policy_name="users-web-app-session"
load_balancer_port="80"

# Creates a sticky session cookie (ensures that all requests from the user 
# during the life of the browser session are sent to the same instance) 
aws elb create-lb-cookie-stickiness-policy --load-balancer-name "$load_balancer_name" --policy-name "$sticky_session_policy_name"

# Sets a sticky session cookie policy 
# for the specified classic load balancer
aws elb set-load-balancer-policies-of-listener --load-balancer-name "$load_balancer_name" --load-balancer-port "$load_balancer_port" \
                                               --policy-names "$sticky_session_policy_name"

# Attaches load balancer: web-app-lb to autoscaling group: web-app-asg
aws autoscaling attach-load-balancers --auto-scaling-group-name "$auto_scaling_group_name" \
--load-balancer-names "$load_balancer_name" 2>> log.txt

printf "\n"
printf "Load-balancer-url to web-app: $lb_dns_name\n\n"
printf "**May have to wait 10-25 mins for the following to complete:\n"
printf "\t1. Instances of autoscaling group to become running\n"
printf "\t2. Initialization of these instances with the specified user-data script provided\n"
printf "\t3. Instances of the autoscaling group to become InService in the load balancer\n"

