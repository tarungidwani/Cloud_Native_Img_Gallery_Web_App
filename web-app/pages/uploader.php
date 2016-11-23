<?php
    require 'init.php';
    require 'menu.php';
    require dirname(__DIR__) . '/lib/s3_interaction.php';
    include_once dirname(__DIR__) . '/lib/db_interaction.php';

    define("S3_CONFIG_PATH", "../config/s3_connection");

    function upload_raw_img_to_s3_bucket($s3_info)
    {
        $raw_bucket_name = $s3_info["raw_bucket_name"];
        $img_path = $_FILES["img_path"]["tmp_name"];
        $region = $s3_info["region"];

        $raw_img_url = submit_file_to_s3($raw_bucket_name, $img_path, $region);

        return $raw_img_url;
    }

    function setup_prepared_statement($mysql_connection, $jobs_table_name)
    {
        $insert_raw_img_record_stmt = $mysql_connection->prepare("INSERT INTO $jobs_table_name VALUES (?,?,?,?,?,?,?)");

        if(!$insert_raw_img_record_stmt)
        {
            echo "Failed to insert raw img job record (*Prepare failed: " . $mysql_connection->error . "*)";
            $mysql_connection->close();
            exit(1);
        }
        return $insert_raw_img_record_stmt;
    }

    function submit_job()
    {
        $s3_info = read_info_from_config_file(constant("S3_CONFIG_PATH"),"Failed to read S3 config file");
        $raw_img_url = upload_raw_img_to_s3_bucket($s3_info);
    }
    submit_job();
