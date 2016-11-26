<?php
    require dirname(__DIR__) . '/lib/db_interaction.php';
    require 'process_img.php';
    require dirname(__DIR__) . '/lib/config_reader.php';
    require dirname(__DIR__) . '/lib/s3_interaction.php';

    define("S3CONFIGPATH", dirname(__DIR__) . '/config/s3_connection');

    function get_job_to_process($reciept)
    {
        $db_connection_info = setup_db_info();
        $db_name = $db_connection_info['db_name'];
        $table_name = $db_connection_info['table_name_jobs'];

        $query_to_execute = "SELECT * from $db_name.$table_name where reciept = '$reciept'";
        $err_msg = "Failed to retrieve jobs from $table_name in DB: $db_name";
        $job_to_process = execute_query($db_connection_info, $query_to_execute, $err_msg);

        return $job_to_process[0];
    }

    function process_job($job_to_process)
    {
        $err_msg = "Failed to read S3 config file";
        $s3_connection_info = read_info_from_config_file(constant("S3CONFIGPATH"), $err_msg);
        $raw_img_url = $job_to_process['s3_raw_url'];
        $finished_bucket_name = $s3_connection_info['finished_bucket_name'];
        $finished_img_path = process_img($raw_img_url);
        $region = $s3_connection_info['region'];

        $finished_img_url = submit_file_to_s3($finished_bucket_name, $finished_img_path, $region);

    }
