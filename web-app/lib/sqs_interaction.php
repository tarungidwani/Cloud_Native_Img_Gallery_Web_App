<?php
    include_once dirname(__DIR__) . '/aws_sdk/aws-autoloader.php';

    /* Gets the url of the specified
     * queue based on the queue name
     * and region passed as arguments
     */
    function get_queue_url($region, $queue_name)
    {
        $sqs_client = new Aws\Sqs\SqsClient([
            'version' => 'latest',
            'region'  => "$region"
        ]);

        try
        {
            $sqs_info = $sqs_client->getQueueUrl([
                'QueueName' => "$queue_name"
            ]);
            return $sqs_info["QueueUrl"];
        }
        catch(\Aws\Sqs\Exception\SqsException $sqs_exception)
        {
            echo "Queue:  $queue_name does not exist, please create required queue by running the create-app-env.sh script\n";
            exit(1);
        }
    }
    