<?php
    require 'init.php';
    require dirname(__DIR__) . '/lib/feature_status.php';
    include_once dirname(__DIR__) . '/lib/db_interaction.php';
    include_once dirname(__DIR__) . '/lib/config_reader.php';
    include_once dirname(__DIR__) . '/lib/s3_interaction.php';

    define("S3_CONFIG", dirname(__DIR__) . '/config/s3_connection');

    function update_upload_feature_status()
    {
        $feature_name = "upload";
        $upload_feature_status = $_POST['upload_feature_status'];

        if($upload_feature_status == null || $upload_feature_status == '')
        {
            $_SESSION['upload_feature_status_msg'] = "Please select one of the radio buttons (Enable or Disable)";
            header('Location: admin.php');
            exit(1);
        }
        set_feature_status($feature_name, $upload_feature_status);
        $status = ($upload_feature_status == '1' ? "enabled" : "disabled");
        $_SESSION['upload_feature_status_msg'] = "Successfully $status $feature_name feature in web-app";
        header('Location: admin.php');
        exit(0);
    }

    function save_db_back_up_to_s3_bucket($back_up_file)
    {
        $err_msg = "Failed to read S3 config file";
        $s3_connection_info = read_info_from_config_file(constant("S3_CONFIG"), $err_msg);
        $bucket_name = $s3_connection_info['db_backup_bucket'];
        $region = $s3_connection_info['region'];

        submit_file_to_s3_private($bucket_name, $back_up_file, $region);
    }

    function create_db_back_up()
    {
        $db_connection_info = setup_db_info();
        $db_endpoint = $db_connection_info['db_endpoint'];
        $db_username = $db_connection_info['db_username'];
        $db_password = $db_connection_info['db_password'];
        $db_name = $db_connection_info['db_name'];
        $back_up_file = "/tmp/" . $db_name . "_back_up.sql";

        $backup_command = "mysqldump --opt -h $db_endpoint -u $db_username -p$db_password $db_name > $back_up_file 2> /dev/null";
        system($backup_command, $backup_command_return_value);
        if($backup_command_return_value != 0)
        {
            $_SESSION['db_backup_msg'] = "Failed to backup DB $db_name, please ensure that DB exists and you have the appropriate permissions to carry out operations";
            header('Location: admin.php');
            exit(1);
        }

        save_db_back_up_to_s3_bucket($back_up_file);

        $is_delete_successful = unlink($back_up_file);
        if(!$is_delete_successful)
        {
            $_SESSION['db_backup_msg'] = "Failed to delete DB $db_name backup from EC2 instance's filesystem";
            header('Location: admin.php');
            exit(1);
        }

        $_SESSION['db_backup_msg'] = "Created DB $db_name backup successfully";
        header('Location: admin.php');
        exit(0);
    }

    function restore_db_from_latest_back_up()
    {
        $err_msg = "Failed to read S3 config file";
        $s3_connection_info = read_info_from_config_file(constant("S3_CONFIG"), $err_msg);
        $bucket_name = $s3_connection_info['db_backup_bucket'];
        $region = $s3_connection_info['region'];

        $db_connection_info = setup_db_info();
        $db_name = $db_connection_info['db_name'];
        $db_endpoint = $db_connection_info['db_endpoint'];
        $db_username = $db_connection_info['db_username'];
        $db_password = $db_connection_info['db_password'];
        $key_name = $db_name . "_back_up.sql";
        $file_location = "/tmp/$key_name";

        $get_db_back_up_file_from_s3_status = get_file_from_s3($bucket_name, $key_name, $file_location, $region);
        if($get_db_back_up_file_from_s3_status != "Success")
        {
            $_SESSION['db_restore_msg'] = $get_db_back_up_file_from_s3_status;
            header('Location: admin.php');
            exit(1);
        }

        $restore_command = "mysql -h $db_endpoint -u $db_username -p$db_password $db_name < $file_location 2> /dev/null";
        system($restore_command, $restore_command_return_value);
        if($restore_command_return_value != 0)
        {
            $_SESSION['db_restore_msg'] = "Failed to restore DB $db_name from DB backup $key_name";
            header('Location: admin.php');
            exit(1);
        }

        $is_delete_successful = unlink($file_location);
        if(!$is_delete_successful)
        {
            $_SESSION['db_restore_msg'] = "Failed to delete DB $db_name backup from EC2 instance's filesystem";
            header('Location: admin.php');
            exit(1);
        }

        $_SESSION['db_restore_msg'] = "Restored DB $db_name from latest backup successfully";
        header('Location: admin.php');
        exit(0);
    }

    $value_submitted = $_POST['submit'];

    if($value_submitted == "Save")
        update_upload_feature_status();
    elseif($value_submitted == "Back Up")
        create_db_back_up();
    elseif($value_submitted == "Restore")
        restore_db_from_latest_back_up();

