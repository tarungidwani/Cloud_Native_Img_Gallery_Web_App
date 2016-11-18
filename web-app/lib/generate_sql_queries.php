<?php

    function create_select_all_records_query($db_name, $table_name)
    {
        return "SELECT * FROM $db_name.$table_name ORDER BY ID";
    }

    function create_select_all_by_user_email($db_name, $table_name, $value)
    {
        return "SELECT * FROM $db_name.$table_name where user_name = \"$value\"";
    }

    function create_select_by_feature_name($db_name, $table_name, $feature_name)
    {
        return "SELECT * FROM $db_name.$table_name where feature = \"$feature_name\"";
    }
    