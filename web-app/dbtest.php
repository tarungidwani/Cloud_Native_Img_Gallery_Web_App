<?php

    require 'lib/db_creation.php';
    require 'lib/generate_sql_queries.php';

    function setup_db()
    {
        $db_connection_info = read_info_from_db_config_file();
        $db_endpoint = get_db_endpoint($db_connection_info['region'],$db_connection_info['db_identifier']);
        $db_connection_info['db_endpoint'] = $db_endpoint;

        // Create school DB


        // Create students table

        /*
         * Insert 5 student records
         * into students table in DB
         * school
         */
        


    }