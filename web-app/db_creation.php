<?php
    require 'aws_sdk/aws-autoloader.php';
    require 'generate_sql_queries.php';

    // Constants
    define('PATH_TO_DB_CONNECTION_INFO_FILE' , 'config/db_connection');

    /* Reads the db connection config file, parses it,
     * creates and returns an associative array with the
     * with the variable and its value
     */
    function read_info_from_db_config_file()
    {
        $db_connection_file_path = constant('PATH_TO_DB_CONNECTION_INFO_FILE');

        if (file_exists($db_connection_file_path))
            return parse_ini_file($db_connection_file_path);
        else
        {
            echo "Failed to connect to RDS instance: file '$db_connection_file_path' does not exist\n";
            return null;
        }
    }

    function execute_create_query($db_connection_info, $query_to_execute, $err_msg)
    {
        $db_endpoint = get_db_endpoint($db_connection_info['region'],$db_connection_info['db_identifier']);
        $mysql_connection = new mysqli($db_endpoint,$db_connection_info['db_username'],$db_connection_info['db_password']);

        if(!$mysql_connection->connect_errno)
        {
            $is_query_successful = $mysql_connection->query($query_to_execute);

            if(!$is_query_successful)
            {
                echo "$err_msg\n";
                $mysql_connection->close();
                exit(1);
            }
        }
        else
        {
            echo "Failed to connect to RDS instance: $db_endpoint\n";
            $mysql_connection->close();
            exit(1);
        }
        $mysql_connection->close();
    }

    function get_db_endpoint($region, $db_identifier)
    {
        $rds_client = new \Aws\Rds\RdsClient([
            'version' => 'latest',
            'region'  => "$region",
            'credentials' => [
                'key' => 'AKIAINSA32Q2CDCUR4OA',
                'secret' => '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'
            ]
        ]);

        try
        {
            $db_info = $rds_client->describeDBInstances([
                'DBInstanceIdentifier' => "$db_identifier"
            ]);
            return $db_info['DBInstances'][0]['Endpoint']['Address'];
        }
        catch (\Aws\Rds\Exception\RdsException $rds_exception)
        {
            echo "RDS instance $db_identifier does not exist, please create required instance by running the create-app-env.sh script\n";
            exit(1);
        }
    }


$db_connection_info = read_info_from_db_config_file();

$query_to_execute = create_db_query($db_connection_info['db_name']);
$err_msg = "Failed to create database $db_connection_info[db_name]";
execute_create_query($db_connection_info, $query_to_execute, $err_msg);

$query_to_execute = create_students_table_query($db_connection_info['db_name'],$db_connection_info['table_name']);
$err_msg = "Failed to create table $db_connection_info[table_name] in DB $db_connection_info[db_name]";
execute_create_query($db_connection_info, $query_to_execute, $err_msg);

$query_to_execute = create_insert_students_records_query($db_connection_info['db_name'],$db_connection_info['table_name']);
$err_msg = "Failed to insert student records in table $db_connection_info[table_name] in DB $db_connection_info[db_name]";
execute_create_query($db_connection_info, $query_to_execute, $err_msg);

echo "hee";