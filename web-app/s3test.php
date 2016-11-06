<?php

    require 'lib/config_reader.php';
    require 'lib/s3_interaction.php';

    define('PATH_TO_S3_CONNECTION_INFO_FILE' , 'config/s3_connection');

    /* Displays an image on the
     * screen using a S3 bucket
     * as the src
    */
    function display_image($file_url)
    {
        echo "<h2>URL to img in AWS Raw S3 bucket: $file_url</h2>";
        echo "<img src='$file_url' alt='T-REX with a switch and Microsoft/IIT flag with grabbers in its hands' width='90%' height='90%'>";
    }


