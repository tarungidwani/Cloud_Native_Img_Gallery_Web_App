#!/bin/bash

apache_file_location=/var/www/html

sudo apt-get install -y git
sudo apt-get install -y apache2
sudo service apache2 start
sudo rm -rf $apache_file_location/*
sudo git clone https://github.com/tarungidwani/boostrap-website.git $apache_file_location
