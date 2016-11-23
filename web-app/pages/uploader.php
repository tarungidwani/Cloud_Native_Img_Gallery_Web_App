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

    function bind_params_to_prepared_stmt($stmt, $s3_raw_url)
    {
        $id = NULL;
        $user_login_id = $_SESSION['user_login_id'];
        $phone_number = '';
        $s3_finished_url = '';
        $status = '0';
        $reciept = md5($s3_raw_url);

        $stmt->bind_param("sisssss", $id ,$user_login_id, $phone_number, $s3_raw_url, $s3_finished_url, $status, $reciept);

        if(!stmt)
        {
            echo "Failed to insert raw img job record (*Bind failed: " . $stmt->error . "*)";
            exit(1);
        }
        return $stmt;
    }

    function record_raw_image_job($db_connection_info, $s3_raw_url)
    {
        $db_endpoint = $db_connection_info['db_endpoint'];
        $db_username = $db_connection_info['db_username'];
        $db_password = $db_connection_info['db_password'];
        $db_name = $db_connection_info['db_name'];
        $mysql_connection = new mysqli($db_endpoint, $db_username, $db_password, $db_name);

        if(!$mysql_connection->connect_errno)
        {
            $insert_raw_img_record_stmt       = setup_prepared_statement($mysql_connection, $db_connection_info['table_name_jobs']);
            $insert_raw_img_record_stmt_bound = bind_params_to_prepared_stmt($insert_raw_img_record_stmt, $s3_raw_url);
            $result = $insert_raw_img_record_stmt_bound->execute();

            if(!$result)
            {
                echo "Failed to insert raw img job record (*Execution failed: " . $insert_raw_img_record_stmt_bound->error . "*)";
                exit(1);
            }
            else
                echo "Job successfully submitted, once complete you will receive a notification at: " . $_SESSION['user_name'];
        }
        else
        {
            echo "Failed to connect to RDS instance: $db_connection_info[db_endpoint]\n";
            $mysql_connection->close();
            exit(1);
        }
    }

    function submit_job()
    {
        $s3_info = read_info_from_config_file(constant("S3_CONFIG_PATH"),"Failed to read S3 config file");
        $raw_img_url = upload_raw_img_to_s3_bucket($s3_info);
    }
    submit_job();
