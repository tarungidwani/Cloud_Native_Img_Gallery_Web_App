<?php
    require 'init.php';
    require dirname(__DIR__) . '/lib/feature_status.php';

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

    $value_submitted = $_POST['submit'];

    if($value_submitted == "Save")
        update_upload_feature_status();

