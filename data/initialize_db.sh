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

mysql -h "$host" -u "$db_username" -p"$db_password" 2>> db_initialize.txt << EOF 
	
	/* Creates a database */
	CREATE DATABASE IF NOT EXISTS $db_name;
	USE $db_name;

	/* Creates a table */
	CREATE TABLE IF NOT EXISTS $table_name
	(
		user_name VARCHAR(255) PRIMARY KEY,
		password VARCHAR(255) NOT NULL
	);

EOF
