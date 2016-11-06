<?php

    require 'lib/config_reader.php';
    require 'lib/s3_interaction.php';

    define('PATH_TO_S3_CONNECTION_INFO_FILE' , 'config/s3_connection');
    define('PATH_TO_IMAGE_TO_UPLOAD' , 'imgs/switchonarex.png');

    /* Displays an image on the
     * screen using a S3 bucket
     * as the src
    */
    function display_image($file_url)
    {
        echo "<h2>URL to img in AWS Raw S3 bucket: $file_url</h2>";
        echo "<img src='$file_url' alt='T-REX with a switch and Microsoft/IIT flag with grabbers in its hands' width='90%' height='90%'>";
    }

    /* Execution of this
     * program begins here
     */
    function main()
    {
        $s3_connection_info = read_info_from_config_file(constant('PATH_TO_S3_CONNECTION_INFO_FILE'));
        $bucket_name = $s3_connection_info['raw_bucket_name'];
        $file_path = constant('PATH_TO_IMAGE_TO_UPLOAD');
        $region = $s3_connection_info['region'];

        $file_url = submit_file_to_s3($bucket_name, $file_path, $region);

        display_image($file_url);
    }
    main();


