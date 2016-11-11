<?php

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