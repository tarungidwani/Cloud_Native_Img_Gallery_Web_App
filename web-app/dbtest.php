<?php

    require 'lib/config_reader.php';
    require 'lib/db_creation.php';
    require 'lib/generate_sql_queries.php';

    define('PATH_TO_DB_CONNECTION_INFO_FILE' , 'config/db_connection');

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

    /* Creates a html table, add each student record
     * as a row and prints out the html table
     * in one's browser of choice
     */
    function print_all_student_records($student_records_array)
    {
        $column_headers = array_keys($student_records_array[0]);

        echo '<table border="2"><thead><tr>';
        foreach($column_headers as $column_header) {
            echo '<th>'.$column_header.'</th>';
        }
        echo '</tr></thead><tbody>';

        foreach($student_records_array as $student_record) {
            echo '<tr>';
            foreach($column_headers as $column_header) {
                echo '<td>'.$student_record[$column_header].'</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
    }

    // Execution of this program begins here
    function main()
    {
        $db_config_file_path = constant('PATH_TO_DB_CONNECTION_INFO_FILE');
        $err_msg = "Failed to connect to RDS instance, file $db_config_file_path does not exist";

        $db_connection_info = read_info_from_config_file($db_config_file_path, $err_msg);
        $db_endpoint = get_db_endpoint($db_connection_info['region'],$db_connection_info['db_identifier']);
        $db_connection_info['db_endpoint'] = $db_endpoint;

        setup_db($db_connection_info);

        $student_records_array = get_student_records($db_connection_info);

        print_all_student_records($student_records_array);
    }
    main();


