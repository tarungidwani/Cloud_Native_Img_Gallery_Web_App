<?php

    function create_db_query($db_name)
    {
        return "CREATE DATABASE $db_name";
    }

    function create_students_table_query($db_name, $table_name)
    {
        return <<<SQL
  CREATE TABLE $db_name.$table_name
  (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255),
    Age INT(3)
  );
SQL;
    }