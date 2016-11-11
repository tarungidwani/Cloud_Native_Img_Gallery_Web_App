<?php

    function create_select_all_records_query($db_name, $table_name)
    {
        return "SELECT * FROM $db_name.$table_name ORDER BY ID";
    }