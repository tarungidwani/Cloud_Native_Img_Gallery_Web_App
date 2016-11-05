<?php

    require 'lib/db_creation.php';
    require 'lib/generate_sql_queries.php';

    /*
     * Creates the school DB in RDS instance
     * Creates a table called students in DB school
     * Inserts 5 student records in table students
     */
    function setup_db($db_connection_info)
    {
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

    /* Querys the student table in DB school
     * and retrieves all the student records
     * and returns all of them as part of
     * an array
    */
    function get_student_records($db_connection_info)
    {
        $query_to_execute = create_select_all_records_query($db_connection_info['db_name'],$db_connection_info['table_name']);
        $err_msg = "Failed to read student records from table $db_connection_info[table_name] in DB $db_connection_info[db_name]";
        $student_records_array = execute_query($db_connection_info, $query_to_execute, $err_msg);

        return $student_records_array;
    }

    // Execution of this program begins here
    function main()
    {
        $db_connection_info = read_info_from_db_config_file();
        $db_endpoint = get_db_endpoint($db_connection_info['region'],$db_connection_info['db_identifier']);
        $db_connection_info['db_endpoint'] = $db_endpoint;

        setup_db($db_connection_info);

        $student_records_array = get_student_records($db_connection_info);
    }
    main();

