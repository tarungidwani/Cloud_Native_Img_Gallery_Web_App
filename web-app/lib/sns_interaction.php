<?php
    require 'config_reader.php';
    include_once dirname(__DIR__) . '/aws_sdk/aws-autoloader.php';

    define("SNS_CONFIG", dirname(__DIR__) . '/config/sns_connection');

    /* Get the Topic ARN
     * based on name of
     * topic specified
     * in config file
     */
    function get_topic_arn($topic_name, $region)
    {
        $sns_client = new Aws\Sns\SnsClient([
            'version' => 'latest',
            'region'  => "$region"
        ]);

        try
        {
            /* Ended up using create topic as listTopics
             * does allow looking up a topic by name
             * Create topic will return the topic arn
             * if topic with said name already exists
             */
            $sns_info = $sns_client->createTopic([
                'Name' => $topic_name
            ]);
            return $sns_info['TopicArn'];
        }
        catch (\Aws\Sns\Exception\SnsException $sns_exception)
        {
            echo "Topic $topic_name does not exist, please create required topic by running the create-app-env.sh script\n";
            exit(1);
        }
    }

    