#!/bin/bash

log_file=create-worker.txt

ec2_security_group="$1"
key_name="$2"

instace_quantity=1
instace_type="t2.micro"
instance_zone="us-west-2b"
ami_id="ami-9cb313fc"
instance_user_data="file://install-worker-env.sh"
iam_profile="$3"

worker_instance_id=$(aws ec2 run-instances --image-id "$ami_id"              --instance-type "$instace_type" \
                      --count "$instace_quantity"       --security-groups "$ec2_security_group" \
											--key-name "$key_name"            --placement "AvailabilityZone=$instance_zone" \
											--user-data "$instance_user_data" --iam-instance-profile "Name=$iam_profile" \
										  --query Instances[0].InstanceId   --output "text" 2>> $log_file)

aws ec2 wait instance-running --instance-ids "$worker_instance_id"

