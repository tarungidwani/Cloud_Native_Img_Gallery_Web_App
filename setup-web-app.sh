#!/bin/bash

function install_dependencies
{
	sudo apt-get -y install mysql-client &> /dev/null 

	# Installs all tools needed
	# to install and setup php
	sudo apt-get -y install zip   &> /dev/null
	sudo apt-get -y install unzip &> /dev/null
	sudo apt-get -y install curl  &> /dev/null

	# Installs php and all the
	# required modules
	sudo apt-get -y install php       &> /dev/null
	sudo apt-get -y install php-xml   &> /dev/null
	sudo apt-get -y install php-curl  &> /dev/null
	sudo apt-get -y install php-mysql &> /dev/null
}

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

# Creates a MariaDB RDS instance
# Creates two S3 buckets: raw and
# finished
function create_app_infra
{
	local rds_sec_group_id=$(aws ec2 describe-security-groups --group-name $rds_security_group \
																													  --query SecurityGroups[*].GroupId --output text)
	local raw_s3_bucket="raw-tng"
	local finished_s3_bucket="finished-tng"

	printf "\nCreating env for app: RDS instance (MariaDB), S3 buckets & Simple Queue\n"
	./create-app-env.sh "$rds_sec_group_id" "$raw_s3_bucket" "$finished_s3_bucket"
	printf "Completed Successfully\n"
}

function create_env
{
	local ami_id="ami-9cb313fc"
	local key_name="$1"
	local iam_profile="$2"

	printf "\n Creating env: LC, ASG, and LB\n"
	./create-env.sh "$ami_id" "$key_name" "$ec2_security_group" "$iam_profile"
	printf "Completed Successfully\n"
}

function create_worker
{
	local security_group="$ec2_security_group"
	local key_name="$1"
	local iam_profile="$2"

	printf "\nCreating worker instance\n"
	./create-worker-instance.sh "$security_group" "$key_name" "$iam_profile"
	printf "Completed Successfully\n"

}

if [ $# -ne 2 ]
then
	printf "\n***\nPlease provide the following 2 arguments in the given order:\n\n"
	printf " 1. Key Name\n 2. IAM Role Name, with PowerUserAccess policy set\n"
	printf "***\n\n"
	exit 1
fi

# Destroy all existing infrastructure
# before begining setup of infra and
# web app
./destroy-env.sh

install_dependencies

create_ec2_sec_group

create_rds_sec_group

create_app_infra

create_env "$1" "$2"

create_worker "$1" "$2"

