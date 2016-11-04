<?php
    define('PATH_TO_DB_CONNECTION_INFO_FILE' , 'config/db_connection');

    /* Reads the db connection config file, parses it,
     * creates and returns an associative array with the
     * with the variable and its value
     */
    function read_info_from_db_config_file()
    {
        $db_connection_file_path = constant('PATH_TO_DB_CONNECTION_INFO_FILE');

        if (file_exists($db_connection_file_path))
            return parse_ini_file($db_connection_file_path);
        else
        {
            echo "Failed to connect to RDS instance: file '$db_connection_file_path' does not exist\n";
            exit(1);
        }
    }