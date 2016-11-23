#!/bin/bash

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

