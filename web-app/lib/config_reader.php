<?php

    /* Reads a config file, parses it,
    * creates and returns an associative
     * array with the with the variable
     * and its value
    */
    function read_info_from_config_file($config_file_path, $err_msg)
    {
        if (file_exists($config_file_path))
            return parse_ini_file($config_file_path);
        else
        {
            echo sprintf($err_msg, $config_file_path);
            exit(1);
        }
    }