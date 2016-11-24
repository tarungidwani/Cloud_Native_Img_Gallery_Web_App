#!/bin/bash

web_app_dir=/home/ubuntu/web-app
aws_sdk_tmp_location=/tmp/aws.zip
aws_sdk_location=$web_app_dir/aws_sdk
aws_sdk_url=http://docs.aws.amazon.com/aws-sdk-php/v3/download/aws.zip

repo_url=git@github.com:illinoistech-itm/tgidwani.git
repo_tmp_location=/tmp/repo
repo_files_locaiton=$repo_tmp_location/web-app/*

# Installs all tools needed
# to install and setup php
run-one-until-success sudo apt-get -y install zip
run-one-until-success sudo apt-get -y install unzip
run-one-until-success sudo apt-get -y install curl

# Installs php and all the
# required modules
run-one-until-success sudo apt-get -y install php
run-one-until-success sudo apt-get -y install php-xml
run-one-until-success sudo apt-get -y install php-curl
run-one-until-success sudo apt-get -y install php-mysql

# Installs and setups 
#AWS SDK for PHP
mkdir $web_app_dir
sudo wget -O "$aws_sdk_tmp_location" "$aws_sdk_url"
sudo unzip "$aws_sdk_tmp_location" -d "$aws_sdk_location"

# Install and setup web-app
run-one-until-success sudo apt-get -y install git
sudo git clone "$repo_url" "$repo_tmp_location"
sudo cp -r $repo_files_locaiton "$web_app_dir"

