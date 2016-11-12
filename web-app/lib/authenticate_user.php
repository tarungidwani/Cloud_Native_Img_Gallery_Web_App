<?php
    require 'config_reader.php';
    require 'generate_sql_queries.php';
    require 'db_interaction.php';

    /* Querys and brigs together
     * all the information needed
     * to connect to the app DB
     * and execute a query
     */
    function setup_db_info()
    {
        $db_connection_file_path = dirname(__DIR__) . '/config/db_connection';
        $err_msg = "Failed to read db_connection file, please try again later";
        $db_connection_info = read_info_from_config_file($db_connection_file_path, $err_msg);

        $db_region = $db_connection_info['region'];
        $db_identifier = $db_connection_info['db_identifier'];
        $db_connection_info['db_endpoint'] = get_db_endpoint($db_region, $db_identifier);

        return $db_connection_info;
    }