<?php
    include_once dirname(__DIR__) . '/aws_sdk/aws-autoloader.php';

    /* Uploads a file to the specified
     * AWS S3 bucket
     */
    function submit_file_to_s3($bucket_name, $file_location, $region)
    {
        $s3_client = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region'  => "$region"
        ]);

        try
        {
            $result = $s3_client->putObject([
                'Bucket'     => $bucket_name,
                'Key'        => basename($file_location),
                'SourceFile' => $file_location,
                'ACL'        => 'public-read'
            ]);
            return $result['ObjectURL'];
        }
        catch(\Aws\S3\Exception\S3Exception $s3_exception)
        {
            echo "S3 bucket $bucket_name does not exist, please create required bucket by running the create-app-env.sh script\n";
            echo "Raw bucket name should be raw-tng or modify name in s3_connection config file\n";
            exit(1);
        }
    }

    /* Uploads a file to the specified
     * AWS S3 bucket ACL: private
     */
    function submit_file_to_s3_private($bucket_name, $file_location, $region)
    {
        $s3_client = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region'  => "$region"
        ]);

        try
        {
             $s3_client->putObject([
                'Bucket'     => $bucket_name,
                'Key'        => basename($file_location),
                'SourceFile' => $file_location,
                'ACL'        => 'private'
            ]);
        }
        catch(\Aws\S3\Exception\S3Exception $s3_exception)
        {
            echo "Failed to upload db back up to $bucket_name in S3\n";
            exit(1);
        }
    }

