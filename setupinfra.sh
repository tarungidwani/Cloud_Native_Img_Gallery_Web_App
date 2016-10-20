#!/bin/bash

key_name="bootstrap_website_access"
security_group_name="bootstrap_website_group"
security_group_description="Allows inbound traffic on 22 for ssh to login & allows traffic on port 80 \
for http to access bootstrap website hosted on apache server"
instace_quantity=1
instace_type="t2.micro"
instance_zone="us-west-2b"
instance_ami="ami-06b94666"
instance_user_data="file://installenv.sh"


# Creates a new key-pair named bootstrap_website_access and stores the private key in a file
aws ec2 create-key-pair --key-name "$key_name" --query "KeyMaterial" \
--output text > "$key_name".pem 2> /dev/null

chmod 400 "$key_name".pem

# Creates a new security group named bootstrap_website_group
aws ec2 create-security-group --group-name "$security_group_name" --description "$security_group_description"\
&> /dev/null

# Adds rule to allow inbound traffic from any host on port 22 (ssh)
 aws ec2 authorize-security-group-ingress --group-name "$security_group_name" --protocol tcp --port 22 \
 --cidr 0.0.0.0/0 &> /dev/null

 # Adds rule to allow inbound traffic from any host on port 80 (http)
 aws ec2 authorize-security-group-ingress --group-name "$security_group_name" --protocol tcp --port 80 \
 --cidr 0.0.0.0/0 &> /dev/null

# Starts a new EC2 Ubuntu 16.04 LTS server
aws ec2 run-instances --image-id "$instance_ami" --instance-type "$instace_type" \
--count "$instace_quantity" --security-groups "$security_group_name" \
--key-name "$key_name" --placement "AvailabilityZone=$instance_zone" \
--user-data "$instance_user_data"
