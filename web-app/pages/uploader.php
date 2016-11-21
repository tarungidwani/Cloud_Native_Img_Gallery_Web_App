<?php

    require 'init.php';
    require 'menu.php';
    require "../lib/s3_interaction.php";

    define("S3_CONFIG_PATH", "../config/s3_connection");

    function upload_raw_img_to_s3_bucket($s3_info)
    {
        $raw_bucket_name = $s3_info["raw_bucket_name"];
        $img_path = $_FILES["img_path"]["tmp_name"];
        $region = $s3_info["region"];

        $raw_img_url = submit_file_to_s3($raw_bucket_name, $img_path, $region);

        return $raw_img_url;
    }

    $s3_info = read_info_from_config_file(constant("S3_CONFIG_PATH"),"Failed to read S3 config file");
    $raw_img_url = upload_raw_img_to_s3_bucket($s3_info);
