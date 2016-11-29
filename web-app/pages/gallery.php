<?php
    require 'init.php';
    require 'menu.php';
    include_once dirname(__DIR__) . '/lib/db_interaction.php';

    function get_all_finished_images_of_user()
    {
        $db_connection_info = setup_db_info();
        $db_name = $db_connection_info['db_name'];
        $table_name = $db_connection_info['table_name_jobs'];
        $user_login_id = $_SESSION['user_login_id'];
        $status = '1';

        $query = "SELECT s3_raw_url, s3_finished_url FROM $db_name.$table_name WHERE (status = '$status' AND  s3_finished_url != '') AND user_login_id = $user_login_id";
        $err_msg = "Failed to retrieve finished jobs for user: " . $_SESSION['user_name'];
        $all_finished_image_records = execute_query($db_connection_info, $query, $err_msg);

        return $all_finished_image_records;
    }
?>

