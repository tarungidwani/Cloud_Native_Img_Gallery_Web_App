<?php
    require 'config_reader.php';
    require 'generate_sql_queries.php';
    require 'db_interaction.php';

    /* Checks to ensure that the user
     * entered username and password
     * match the username and password
     * stored in the user logins DB
     */
    function are_user_credentials_valid($user_entered_username,$user_entered_password)
    {
        $user_credentials = get_user_credentials_from_db();

        if( $user_entered_username == $user_credentials['user_name']  &&
            $user_entered_password == $user_credentials['password'] )
            return true;
        else
            return false;
    }

    /* Querys and brigs together
     * all the information needed
     * to connect to the app DB
     * and execute a query
     */
    function setup_db_info()
    {
        $db_connection_file_path = dirname(__DIR__) . '/config/db_connection';
        $err_msg = "Failed to read db_connection file, please try again later";
        $db_connection_info = read_info_from_config_file($db_connection_file_path, $err_msg);

        $db_region = $db_connection_info['region'];
        $db_identifier = $db_connection_info['db_identifier'];
        $db_connection_info['db_endpoint'] = get_db_endpoint($db_region, $db_identifier);

        return $db_connection_info;
    }

    /* Gets a user's login info
     * from DB based on the
     * specified username
     */
    function get_user_credentials_from_db()
    {
        $db_connection_info = setup_db_info();
        $db_name = $db_connection_info['db_name'];
        $table_name = $db_connection_info['table_name_users'];
        $username = $_SESSION['user_name'];
        $query = create_select_all_by_user_email($db_name, $table_name, $username);

        $err_msg = "Failed to get user login information in table $table_name in db $db_name";
        $user_login_info = execute_query($db_connection_info, $query, $err_msg)[0];

        return $user_login_info;
    }
