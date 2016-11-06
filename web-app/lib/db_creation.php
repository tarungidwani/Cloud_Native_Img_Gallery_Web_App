<?php
//Failed to connect to RDS instance: file '$db_connection_file_path' does not exist\n"
   require dirname(__DIR__) . '/aws_sdk/aws-autoloader.php';

    /* Querys AWS's RDS to get the endpoint of the DB instance
     * whose db-identifer and region are passed to this
     * function as arguments
     * Note: Credentials will be removed, currently added
     *       for testing purposes
    */
    function get_db_endpoint($region, $db_identifier)
    {
        $rds_client = new \Aws\Rds\RdsClient([
            'version' => 'latest',
            'region'  => "$region",
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

    /* Executes any create or insert query based on the
     * db info and query passed to it. If the connection
     * to the DB fails or the execution of the query fails
     * it prints out an error message and exits the program
     * with a failure return code
     */
    function execute_query($db_connection_info, $query_to_execute, $err_msg)
    {
        $mysql_connection = new mysqli($db_connection_info['db_endpoint'],$db_connection_info['db_username'],$db_connection_info['db_password']);

        if(!$mysql_connection->connect_errno)
        {
            $result = $mysql_connection->query($query_to_execute);
            $data = array();

            if($result)
            {
                if(!is_bool($result))
                {
                    while ($row = $result->fetch_assoc())
                        $data[] = $row;
                }
                $mysql_connection->close();
                return $data;
            }
            else
            {
                echo "$err_msg\n";
                $mysql_connection->close();
                exit(1);
            }
        }
        else
        {
            echo "Failed to connect to RDS instance: $db_connection_info[db_endpoint]\n";
            $mysql_connection->close();
            exit(1);
        }
    }
