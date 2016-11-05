#!/bin/bash

apache_file_location=/var/www/html

# Update all repos and upgrade
# all packages with available
# updates
sudo apt-get update
sudo apt-get -y upgrade
sudo apt-get -y dist-upgrade

# Installs and starts
# the apache2 web server
sudo apt-get -y install apache2
sudo service apache2 start




