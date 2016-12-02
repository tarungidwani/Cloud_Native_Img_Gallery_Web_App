<?php
    require 'init.php';
    require dirname(__DIR__) . '/lib/feature_status.php';
    include_once dirname(__DIR__) . '/lib/db_interaction.php';

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

    function create_db_back_up()
    {
        $db_connection_info = setup_db_info();
        $db_endpoint = $db_connection_info['db_endpoint'];
        $db_username = $db_connection_info['db_username'];
        $db_password = $db_connection_info['db_password'];
        $db_name = $db_connection_info['db_name'];
        $back_up_file = $db_name . "_back_up.sql";

        $backup_command = "mysqldump --opt -h $db_endpoint -u $db_username -p$db_password $db_name > $back_up_file 2> /dev/null";
        system($backup_command);
    }

    $value_submitted = $_POST['submit'];

    if($value_submitted == "Save")
        update_upload_feature_status();

