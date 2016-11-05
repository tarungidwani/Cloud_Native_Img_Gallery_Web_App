<?php

    function create_db_query($db_name)
    {
        return "CREATE DATABASE IF NOT EXISTS $db_name";
    }

    function create_students_table_query($db_name, $table_name)
    {
        return <<<SQL
    CREATE TABLE IF NOT EXISTS $db_name.$table_name
    (
      ID INT AUTO_INCREMENT PRIMARY KEY,
      Name VARCHAR(255),
      Age INT(3)
    );
SQL;
    }

    function create_insert_student_records_query($db_name, $table_name)
    {
        return <<<SQL
    INSERT INTO $db_name.$table_name (Name, Age)
    VALUES
          ("Dev Shah"         , 19),
          ("Eddie Huang"      , 15),
          ("Lu Xiajun"        , 21),
          ("John Wayne Gacey" , 24),
          ("Bruce Lee"        , 59)
SQL;
}

    function create_select_all_records_query($db_name, $table_name)
    {
        return "SELECT * FROM $db_name.$table_name";
    }