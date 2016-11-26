<?php
    require dirname(__DIR__) . '/lib/db_interaction.php';

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
