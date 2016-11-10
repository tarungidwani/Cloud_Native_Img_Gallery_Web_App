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

mysql -h "$host" -u "$db_username" -p"$db_password" << EOF 

	CREATE DATABASE IF NOT EXISTS $db_name;
	USE $db_name;

EOF
