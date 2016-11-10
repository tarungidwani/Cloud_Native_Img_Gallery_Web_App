#!/bin/bash

host="$1"
db_username="root"
db_password="TestMikeTest!"

db_name="gallery"
table_name="user_logins"

if [ $# != 1 ]
then
	printf "\n**Please provide url to DB instance endpoint**\n\n"
	exit 1;
fi


