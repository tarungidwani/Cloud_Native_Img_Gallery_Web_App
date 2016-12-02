#!/bin/bash

apache_file_location=/var/www/html

aws_sdk_tmp_location=/tmp/aws.zip
aws_sdk_location=$apache_file_location/aws_sdk
aws_sdk_url=http://docs.aws.amazon.com/aws-sdk-php/v3/download/aws.zip

repo_url=git@github.com:illinoistech-itm/tgidwani.git
repo_tmp_location=/tmp/repo
repo_files_locaiton=$repo_tmp_location/web-app/*

# Installs the mysql
# cmdline client
run-one-until-success sudo apt-get -y install mysql-client

# Installs and starts
# the apache2 web server
run-one-until-success sudo apt-get -y install apache2
sudo service apache2 start

# Installs all tools needed
# to install and setup php
run-one-until-success sudo apt-get -y install zip
run-one-until-success sudo apt-get -y install unzip
run-one-until-success sudo apt-get -y install curl

# Installs php and all the
# required modules
run-one-until-success sudo apt-get -y install php
run-one-until-success sudo apt-get -y install libapache2-mod-php
run-one-until-success sudo apt-get -y install php-xml
run-one-until-success sudo apt-get -y install php-curl
run-one-until-success sudo apt-get -y install php-mysql

# Installs and setups AWS SDK
# for PHP
sudo rm -rf "$apache_file_location"/*
sudo wget -O "$aws_sdk_tmp_location" "$aws_sdk_url"
sudo unzip "$aws_sdk_tmp_location" -d "$aws_sdk_location"

# Install and setup web-app
run-one-until-success sudo apt-get -y install git
sudo git clone "$repo_url" "$repo_tmp_location"
sudo cp -r $repo_files_locaiton "$apache_file_location"

# Restarts apache2 web server
# for the changes to take effect
sudo service apache2 restart

