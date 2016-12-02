<?php

    require 'generate_sql_queries.php';
    require 'db_interaction.php';

    /* Checks to ensure that the feature
    * is enabled or disabled
    */
    function is_feature_enabled($feature_name)
    {
        $feature_status = get_feature_status($feature_name);

        if($feature_status['status'])
            return true;
        else
            return false;
    }

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

    /* Updates the status
     * of the specified
     * feature stored
     * in DB table
     */
    function set_feature_status($feature_name, $status)
    {
        $db_connection_info = setup_db_info();
        $db_name = $db_connection_info['db_name'];
        $table_name = $db_connection_info['table_name_features'];
        $query = create_update_status_by_feature_name($db_name, $table_name, $feature_name, $status);

        $err_msg = "Failed to update $feature_name feature status information in table $table_name in db $db_name";
        execute_query($db_connection_info, $query, $err_msg);
    }

