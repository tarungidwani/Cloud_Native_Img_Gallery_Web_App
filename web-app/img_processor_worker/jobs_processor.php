<?php
    require dirname(__DIR__) . '/lib/db_interaction.php';

    function get_all_jobs_to_process()
    {
        $db_connection_info = setup_db_info();
        $db_name = $db_connection_info['db_name'];
        $table_name = $db_connection_info['table_name_jobs'];
        $s3_finished_url = '';
        $status = '0';

        $query_to_execute = "SELECT * from $db_name.$table_name where s3_finished_url = '$s3_finished_url' and status = '$status'";
        $err_msg = "Failed to retrieve jobs from $table_name in DB: $db_name";
        $all_jobs_to_process = execute_query($db_connection_info, $query_to_execute, $err_msg);

        return $all_jobs_to_process;
    }
