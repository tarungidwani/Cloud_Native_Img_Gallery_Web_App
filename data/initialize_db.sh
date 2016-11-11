#!/bin/bash

host="$1"
db_username="root"
db_password="TestMikeTest!"

db_name="gallery"
table_name="user_logins"

# Hashed passwords of the users
password_1=$(md5sum <<< 'LetMeInTarun')
password_2=$(md5sum <<< 'LetMeInJeremy')
password_3=$(md5sum <<< 'LetMeInController')

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
		id INT PRIMARY KEY AUTO_INCREMENT,
		user_name VARCHAR(255) NOT NULL,
		password VARCHAR(255) NOT NULL,
		CONSTRAINT UNIQUE (user_name)
	);

	/* Insert user login records */
	INSERT INTO $table_name (user_name, password)
	VALUES 
	('tgidwani@hawk.iit.edu', "$password_1"),
	('hajek@iit.edu'        , "$password_2"),
	('controller'           , "$password_3")

EOF
