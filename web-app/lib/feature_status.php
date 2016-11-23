<?php

    require 'generate_sql_queries.php';
    require 'db_interaction.php';

    /* Gets the status of the
     * specified feature that
     * is stored in a DB table
     * for persistence
     */
    function get_feature_status($feature_name)
    {
        $db_connection_info = setup_db_info();
        $db_name = $db_connection_info['db_name'];
        $table_name = $db_connection_info['table_name_features'];
        $query = create_select_by_feature_name($db_name, $table_name, $feature_name);

        $err_msg = "Failed to get feature status information in table $table_name in db $db_name";
        $feature_status_info = execute_query($db_connection_info, $query, $err_msg)[0];

        return $feature_status_info;
    }