#!/bin/bash

ec2_security_group="gallery-web-app-sec-group"
rds_security_group="gallery-web-app-db-sec-group"

# Creates a new security group 
# Adds rule to allow inbound traffic 
# from any host on port 22 (ssh)
# Adds rule to allow inbound traffic 
# from any host on port 80 (http)
function create_ec2_sec_group
{
	
	local ec2_security_group_description="Allows inbound traffic on 22 for ssh to login & allows traffic on port 80"

	printf "\nCreating security group for EC2 instances\n"

	aws ec2 create-security-group --group-name "$ec2_security_group" --description "$ec2_security_group_description" \
	&> /dev/null

	aws ec2 authorize-security-group-ingress --group-name "$ec2_security_group" --protocol tcp --port 22 \
 	--cidr 0.0.0.0/0 &> /dev/null

 	aws ec2 authorize-security-group-ingress --group-name "$ec2_security_group" --protocol tcp --port 80 \
 	--cidr 0.0.0.0/0 &> /dev/null

	printf "Completed Successfully\n"
}

# Creates a new security group
# Adds rule to allow inbound traffic 
# from any host on port 3306 (ssh)
function create_rds_sec_group
{
	local rds_security_group_description="Allows inbound traffic on 3306 default port for MySQL/MariaDB"

	printf "\nCreating security group for RDS (MySQL/MariaDB) instance\n"

	aws ec2 create-security-group --group-name "$rds_security_group" --description "$rds_security_group_description" \
	&> /dev/null

	aws ec2 authorize-security-group-ingress --group-name "$rds_security_group" --protocol tcp --port 3306 \
 	--cidr 0.0.0.0/0 &> /dev/null

	printf "Completed Successfully\n"
}

# Destroy all existing infrastructure
# before begining setup of infra and
# web app
./destroy-env.sh

create_ec2_sec_group

create_rds_sec_group

