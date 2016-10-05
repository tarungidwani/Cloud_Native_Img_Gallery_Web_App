#!/bin/bash

apache_file_location=/var/www/html

sudo apt-get install -yq git &> /dev/null
sudo apt-get install -yq apache2 &> /dev/null
sudo service apache2 start
sudo rm -rf $apache_file_location/*
sudo git clone -q https://github.com/tarungidwani/boostrap-website.git $apache_file_location \
&> /dev/null
