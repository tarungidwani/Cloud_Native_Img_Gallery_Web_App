<?php
    require dirname(__DIR__) . '/lib/db_interaction.php';
    require 'process_img.php';
    include_once dirname(__DIR__) . '/lib/config_reader.php';
    require dirname(__DIR__) . '/lib/s3_interaction.php';
    require dirname(__DIR__) . '/lib/sqs_interaction.php';

    define("S3CONFIGPATH", dirname(__DIR__) . '/config/s3_connection');
    define("SQSCONFIGPATH", dirname(__DIR__) . '/config/sqs_connection');

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
        $finished_img_url = upload_finished_img_to_s3_bucket($job_to_process);
        $job_to_process['s3_finished_url'] = $finished_img_url;

        update_job_record_in_db($job_to_process);
    }

    function upload_finished_img_to_s3_bucket($job_to_process)
    {
        $err_msg = "Failed to read S3 config file";
        $s3_connection_info = read_info_from_config_file(constant("S3CONFIGPATH"), $err_msg);
        $raw_img_url = $job_to_process['s3_raw_url'];
        $finished_bucket_name = $s3_connection_info['finished_bucket_name'];
        $finished_img_path = process_img($raw_img_url);
        $region = $s3_connection_info['region'];

        $finished_img_url = submit_file_to_s3($finished_bucket_name, $finished_img_path, $region);

        return $finished_img_url;
    }

    function update_job_record_in_db($job_to_process)
    {
        $db_connection_info = setup_db_info();
        $db_name = $db_connection_info['db_name'];
        $table_name = $db_connection_info['table_name_jobs'];
        $finished_img_url = $job_to_process['s3_finished_url'];
        $status = '1';
        $reciept = $job_to_process['reciept'];

        $query_to_execute = "UPDATE $db_name.$table_name SET s3_finished_url = '$finished_img_url', status = '$status' where reciept = '$reciept'";
        $err_msg = "Failed update job record with reciept: $reciept in $table_name in DB: $db_name";
        execute_query($db_connection_info, $query_to_execute, $err_msg);
    }

    function process_all_jobs()
    {
        $err_msg = "Failed to read SQS config file";
        $sqs_connection_info = read_info_from_config_file(constant("SQSCONFIGPATH"),$err_msg);
        $region = $sqs_connection_info['region'];
        $queue_name = $sqs_connection_info['queue_name'];
        $queue_url = get_queue_url($region, $queue_name);

    }
    process_all_jobs();
