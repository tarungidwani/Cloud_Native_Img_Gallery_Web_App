<?php
    require dirname(__DIR__) . '/aws_sdk/aws-autoloader.php';

    /* Uploads a file to the specified
     * AWS S3 bucket
     */
    function submit_file_to_s3($bucket_name, $file_location, $region)
    {
        $s3_client = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region'  => "$region",
            'credentials' => [
                'key' => 'AKIAINSA32Q2CDCUR4OA',
                'secret' => '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'
            ]
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