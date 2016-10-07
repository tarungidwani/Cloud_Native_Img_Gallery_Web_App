#!/bin/bash

# Checks if the user specified an AMI ID as an 
# argument to this script

if [ "$1" == "" ]
then
	printf "\n***Please provide an Amazon AMI image ID as an argument to this script***\n\n"
	exit 1
fi

# Variables for creating launch configuration
instance_ami="$1"
instance_type="t2.micro"
launch_configuration_name="bootstrap-website-lc"
instance_user_data="file://installenv.sh"
security_group_name="bootstrap-website-sec-group"
key_name="tarun-itmo-444-access-key"

# Creates a new launch configuration with the name bootstrap-website-lc
aws autoscaling create-launch-configuration --image-id "$instance_ami" --instance-type "$instance_type" \
--launch-configuration-name "$launch_configuration_name" --user-data "$instance_user_data" \
--security-groups "$security_group_name" --key-name "$key_name" 2> log.txt

# Variables for creating autoscaling-group
auto_scaling_group_name="bootstrap-website-asg"
desired_capacity=4
instance_zone="us-west-2b"

# Creates a new auto scaling group with the name bootstrap-website-asg
aws autoscaling create-auto-scaling-group --launch-configuration-name "$launch_configuration_name" \
--auto-scaling-group-name "$auto_scaling_group_name" --desired-capacity "$desired_capacity" \
--min-size "$desired_capacity" --max-size "$desired_capacity" --availability-zones "$instance_zone" 2> log.txt

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
#--auto-scaling-group-names  "bootstrap-website-asg" 
# --query AutoScalingGroups[].Instances[].InstanceId --output "text"`

# Waits for all instances in auto-scaling-group to spawn and become running
# aws ec2 wait instance-running --instance-ids $asg_instance_ids  2> log.txt

# Variables for creating a classic load balancer
load_balancer_name="bootstrap-website-lb"
lb_listener="Protocol=HTTP,LoadBalancerPort=80,InstanceProtocol=HTTP,InstancePort=80"
sec_group_id="sg-c109bbb8"

# Creates a new classic load balancer with the name bootstrap-website-lb
lb_dns_name=$(aws elb create-load-balancer --load-balancer-name "$load_balancer_name" --availability-zones "$instance_zone" \
--listeners "$lb_listener" --security-groups "$sec_group_id" --output "text" 2> log.txt)

# Attaches load balancer: bootstrap-website-lb to autoscaling group: bootstrap-website-asg
aws autoscaling attach-load-balancers --auto-scaling-group-name "$auto_scaling_group_name" \
--load-balancer-names "$load_balancer_name" 2> log.txt

printf "\n"
printf "Load-balancer-url to SAT NextGen bootstrap website: $lb_dns_name\n\n"
printf "**May have to wait 2-5 mins for the following to complete:\n"
printf "\t1. Instances of autoscaling group to become running\n"
printf "\t2. Initialization of these instances with the specified user-data script provided\n"
printf "\t3. Instances of the autoscaling group to become InService in the load balancer\n"

