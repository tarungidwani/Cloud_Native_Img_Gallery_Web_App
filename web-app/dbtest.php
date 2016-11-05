<?php

    require 'lib/db_creation.php';
    require 'lib/generate_sql_queries.php';

    function setup_db()
    {
        $db_connection_info = read_info_from_db_config_file();
        $db_endpoint = get_db_endpoint($db_connection_info['region'],$db_connection_info['db_identifier']);
        $db_connection_info['db_endpoint'] = $db_endpoint;

        // Create school DB
        $query_to_execute = create_db_query($db_connection_info['db_name']);
        $err_msg = "Failed to create database $db_connection_info[db_name]";
        execute_query($db_connection_info, $query_to_execute, $err_msg);

        // Create students table
        $query_to_execute = create_students_table_query($db_connection_info['db_name'],$db_connection_info['table_name']);
        $err_msg = "Failed to create table $db_connection_info[table_name] in DB $db_connection_info[db_name]";
        execute_query($db_connection_info, $query_to_execute, $err_msg);

        /*
         * Insert 5 student records
         * into students table in DB
         * school
         */
        $query_to_execute = create_insert_student_records_query($db_connection_info['db_name'],$db_connection_info['table_name']);
        $err_msg = "Failed to insert student records in table $db_connection_info[table_name] in DB $db_connection_info[db_name]";
        execute_query($db_connection_info, $query_to_execute, $err_msg);
    }