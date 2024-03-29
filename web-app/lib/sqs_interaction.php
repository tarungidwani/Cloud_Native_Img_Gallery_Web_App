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

    /* Sends a message to the
     * specified queue
     */
    function send_message_to_queue($queue_url, $message_body, $region, $queue_name)
    {
        $sqs_client = new Aws\Sqs\SqsClient([
            'version' => 'latest',
            'region'  => "$region"
        ]);

        try
        {
            $sqs_client->sendMessage([
                'QueueUrl' => "$queue_url",
                'MessageBody' => "$message_body"
            ]);
        }
        catch(\Aws\Sqs\Exception\SqsException $sqs_exception)
        {
            echo "Failed to send message to queue: $queue_name, " . $sqs_exception->getMessage() . "\n";
            exit(1);
        }
    }

    /* Reads at most 10 msgs at
     * a time from the specified
     * queue
     */
    function read_messages_from_queue($queue_url, $region, $queue_name)
    {
        $sqs_client = new Aws\Sqs\SqsClient([
            'version' => 'latest',
            'region'  => "$region"
        ]);

        try
        {
            $sqs_msgs = $sqs_client->receiveMessage([
                'QueueUrl' => $queue_url
            ]);
            return $sqs_msgs;
        }
        catch(\Aws\Sqs\Exception\SqsException $sqs_exception)
        {
            echo "Failed to read message to queue: $queue_name, " . $sqs_exception->getMessage() . "\n";
            exit(1);
        }
    }

    /* Deletes a message from
     * the queue with the
     * specified message
     * handle
     */
    function delete_message_from_queue($queue_url, $message_handle, $region, $queue_name)
    {
        $sqs_client = new Aws\Sqs\SqsClient([
            'version' => 'latest',
            'region'  => "$region"
        ]);

        try
        {
            $sqs_client->deleteMessage([
                'QueueUrl' => $queue_url,
                'ReceiptHandle' => $message_handle
            ]);
        }
        catch(\Aws\Sqs\Exception\SqsException $sqs_exception)
        {
            echo "Failed to delete message in queue: $queue_name, " . $sqs_exception->getMessage() . "\n";
            exit(1);
        }
    }
