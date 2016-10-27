#!/bin/bash

# Name: Tarun Gidwani
# Script name: create-app-env.sh
# Purpose: Setups the backend env required
#          by the application
#					 -> RDS

log_file_name="app-env-log.txt"

# Values needed to create 
# a DB instance in RDS
db_engine="mariadb"
db_instance_class="db.t2.micro"
db_storage_type="gp2"
db_allocated_storage_amount="20"
db_instance_identifier="web-app-db"
db_master_username="root"
db_master_user_password="TestMikeTest!"
availability_zone="us-west-2b"
security_group_id="sg-7a9cff03"
db_name="webapp"

# Creates a MariaDB instance within RDS
# with the following properties
aws rds create-db-instance --engine "$db_engine" --db-instance-class "$db_instance_class" --storage-type "$db_storage_type" \
                           --allocated-storage "$db_allocated_storage_amount" --db-instance-identifier "$db_instance_identifier" \
                           --master-username "$db_master_username"  --master-user-password "$db_master_user_password" \
                           --availability-zone "$availability_zone" --vpc-security-group-ids "$security_group_id" --db-name "$db_name" \
> /dev/null 2>> "$log_file_name"

# Waits for specified db instance to
# become available
aws rds wait db-instance-available --db-instance-identifier "$db_instance_identifier"


