#!/bin/bash

host="$1"
db_username="$2"
db_password="$3"

db_name="gallery"
table_name="user_logins"

# Hashed passwords of the users
password_1=($(printf "%s" 'LetMeInTarun'      | md5sum))
password_2=($(printf "%s" 'LetMeInJeremy'     | md5sum))
password_3=($(printf "%s" 'LetMeInController' | md5sum))

table_name_1="jobs"
table_name_2="feature_status"

if [ $# != 3 ]
then
	printf "\n**\nPlease provide the following parameters:\n"
	printf " 1. URL to DB instance endpoint\n 2. User name of DB instance\n 3. Password of DB instance\n**\n\n"
	exit 1;
fi

mysql -h "$host" -u "$db_username" -p"$db_password" 2>> db_initialize.txt << EOF 

	/* Drops a DB if it exists */
	DROP DATABASE IF EXISTS $db_name;

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
	('controller'           , "$password_3");
	
	/* Creates a table */
	CREATE TABLE IF NOT EXISTS $table_name_1
	(
		id INT PRIMARY KEY AUTO_INCREMENT,
		user_login_id INT NOT NULL,
		phone_number VARCHAR(20),
		s3_raw_url VARCHAR(2083) NOT NULL,
		s3_finished_url VARCHAR(2083),
		status ENUM('0','1') NOT NULL,
		reciept VARCHAR(32) NOT NULL,
		FOREIGN KEY (user_login_id)
				REFERENCES $table_name(id)
				ON DELETE CASCADE
	);

	/* Creates a table */
	CREATE TABLE IF NOT EXISTS $table_name_2
	(
		feature VARCHAR(50) PRIMARY KEY,
		status INT NOT NULL
	);

	/* Inserts status for feature */
	INSERT INTO $table_name_2 (feature, status)
	VALUES
	('upload', 1);

EOF
