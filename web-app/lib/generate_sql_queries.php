<?php

    function create_students_table_query($db_name, $table_name)
    {
        return <<<SQL
    CREATE TABLE IF NOT EXISTS $db_name.$table_name
    (
      ID INT AUTO_INCREMENT PRIMARY KEY,
      Name VARCHAR(255) NOT NULL,
      Age INT(3) NOT NULL,
      UNIQUE (Name, Age)
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
    ON DUPLICATE KEY UPDATE Name = VALUES(Name), Age = VALUES(Age);
SQL;
}

    function create_select_all_records_query($db_name, $table_name)
    {
        return "SELECT * FROM $db_name.$table_name ORDER BY ID";
    }