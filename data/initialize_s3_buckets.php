<?php
    include_once dirname(__DIR__) . '/web-app/lib/config_reader.php';
    include_once dirname(__DIR__) . '/web-app/lib/s3_interaction.php';
    include_once dirname(__DIR__) . '/web-app/lib/db_interaction.php';

    /* Paths to imgs that will
     * pre-seated in this
     * gallery web app
     */
    define("IMG_1_raw"     , dirname(__DIR__) . '/web-app/imgs/eartrumpet.png');
    define("IMG_1_finished", dirname(__DIR__) . '/web-app/imgs/eartrumpet-bw.png');
    define("IMG_2_raw"     , dirname(__DIR__) . '/web-app/imgs/Knuth.jpg');
    define("IMG_2_finished", dirname(__DIR__) . '/web-app/imgs/Knuth-bw.jpg');
    define("IMG_3_raw"     , dirname(__DIR__) . '/web-app/imgs/mountain.jpg');
    define("IMG_3_finished", dirname(__DIR__) . '/web-app/imgs/mountain-bw.jpg');

    define("S3_CONFIG"     , dirname(__DIR__) . '/web-app/config/s3_connection');

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

    function bind_multiple_params_to_prepared_stmt($stmt, $s3_raw_url, $s3_finished_url)
    {
        $id = NULL;
        $user_login_id = 2;
        $phone_number = '';
        $status = '1';
        $reciept = md5($s3_raw_url);

        $stmt->bind_param("sisssss", $id ,$user_login_id, $phone_number, $s3_raw_url, $s3_finished_url, $status, $reciept);

        if(!$stmt)
        {
            echo "Failed to insert raw img job record (*Bind failed: " . $stmt->error . "*)";
            exit(1);
        }
        return $stmt;
    }

    function record_pre_seated_finished_job($s3_raw_url, $s3_finished_url)
    {
        $db_connection_info = setup_db_info();
        $db_endpoint = $db_connection_info['db_endpoint'];
        $db_username = $db_connection_info['db_username'];
        $db_password = $db_connection_info['db_password'];
        $db_name = $db_connection_info['db_name'];
        $mysql_connection = new mysqli($db_endpoint, $db_username, $db_password, $db_name);

        if(!$mysql_connection->connect_errno)
        {
            $insert_pre_seated_img_record_stmt       = setup_prepared_statement($mysql_connection, $db_connection_info['table_name_jobs']);
            $insert_pre_seated_img_record_stmt_bound = bind_multiple_params_to_prepared_stmt($insert_pre_seated_img_record_stmt, $s3_raw_url, $s3_finished_url);
            $result = $insert_pre_seated_img_record_stmt_bound->execute();

            if(!$result)
            {
                echo "Failed to insert raw img job record (*Execution failed: " . $insert_pre_seated_img_record_stmt_bound->error . "*)";
                exit(1);
            }
        }
        else
        {
            echo "Failed to connect to RDS instance: $db_connection_info[db_endpoint]\n";
            $mysql_connection->close();
            exit(1);
        }
    }

    function pre_seat_imgs_web_app()
    {
        $err_msg = "Failed to read S3 config file";
        $s3_connection_info = read_info_from_config_file(constant("S3_CONFIG"), $err_msg);
        $raw_bucket_name = $s3_connection_info['raw_bucket_name'];
        $finished_bucket_name = $s3_connection_info['finished_bucket_name'];
        $region = $s3_connection_info['region'];

        $img_1_raw_url      = submit_file_to_s3($raw_bucket_name     , constant("IMG_1_raw")     , $region);
        $img_1_finished_url = submit_file_to_s3($finished_bucket_name, constant("IMG_1_finished"), $region);
        record_pre_seated_finished_job($img_1_raw_url, $img_1_finished_url);

        $img_2_raw_url      = submit_file_to_s3($raw_bucket_name     , constant("IMG_2_raw")     , $region);
        $img_2_finished_url = submit_file_to_s3($finished_bucket_name, constant("IMG_2_finished"), $region);
        record_pre_seated_finished_job($img_2_raw_url, $img_2_finished_url);

        $img_3_raw_url      = submit_file_to_s3($raw_bucket_name     , constant("IMG_3_raw")     , $region);
        $img_3_finished_url = submit_file_to_s3($finished_bucket_name, constant("IMG_3_finished"), $region);
        record_pre_seated_finished_job($img_3_raw_url, $img_3_finished_url);
    }

    pre_seat_imgs_web_app();
