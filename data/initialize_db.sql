/* Creates a database */
SET @db_name = "gallery";
SET @create_db = CONCAT('CREATE DATABASE ', @db_name);
	
PREPARE create_db_stmt from @create_db;
EXECUTE create_db_stmt;
DEALLOCATE PREPARE create_db_stmt;

