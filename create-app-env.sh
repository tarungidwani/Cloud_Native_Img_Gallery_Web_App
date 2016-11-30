#!/bin/bash

# Name: Tarun Gidwani
# Script name: create-app-env.sh
# Purpose: Setups the backend env required
#          by the application
#					 -> RDS
#					 -> S3
#          -> SQS
#          -> SNS

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
security_group_id=$1
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
aws rds wait db-instance-available --db-instance-identifier "$db_instance_identifier" 2>> "$log_file_name"

# Initialize DB with required
# tables and sample data
db_endpoint=$(aws rds describe-db-instances --db-instance-identifier "$db_instance_identifier" --query DBInstances[*].Endpoint.Address \
							--output text 2>> "$log_file_name")
./data/initialize_db.sh "$db_endpoint" "$db_master_username" "$db_master_user_password"

# Values needed to create 
# raw and finished 
# buckets in S3
raw_bucket_name=$2
finish_bucket_name=$3
region="us-west-2"

aws s3 mb s3://$raw_bucket_name    --region $region > /dev/null 2>> "$log_file_name"
aws s3 mb s3://$finish_bucket_name --region $region > /dev/null 2>> "$log_file_name"

# Values needed to create 
# a queue in SQS
queue_name="gallery_img_jobs"
visibility_timeout=600

# Creates a simple queue
# within SQS
gallery_img_jobs_queue_url=$(aws sqs create-queue --queue-name "$queue_name"  --attributes VisibilityTimeout=$visibility_timeout \
                                                  --output text --region $region 2>> "$log_file_name")

# Values needed to
# create a topic in
# SNS
topic_name="gallery_img_job_notifier"
property_name="DisplayName"
display_name="Gallery_Img_Job_Notifier"

# Creates a topic
# in SNS
topic_arn=$(aws sns create-topic --name "$topic_name" --region "$region" --output text 2>> "$log_file_name")
aws sns set-topic-attributes --topic-arn "$topic_arn" --attribute-name "$property_name" --attribute-value "$display_name" 2>> "$log_file_name"

# Values needed to
# subscribe users
# to a topic in SNS
protocol="email"
subscriber_email_addresses_file=./data/subscriber_email_addresses.txt

# Subscribes all
# email addresses
# in the subscriber
# email addresses file
while read subscriber_email_addresses
do
	aws sns subscribe --topic-arn "$topic_arn" --protocol "$protocol" --notification-endpoint "$subscriber_email_addresses" > /dev/null 2>> "$log_file_name"
done < $subscriber_email_addresses_file

