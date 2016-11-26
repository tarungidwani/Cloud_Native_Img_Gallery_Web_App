<?php

    define("IITLOGOIMG", __DIR__ . '/IIT-logo.png');
    define("DESTDIR", '/tmp');

    function download_img($raw_img_url)
    {
        $img_info = pathinfo($raw_img_url);
        $raw_img_name = $img_info['basename'];
        $raw_img_dest = constant("DESTDIR") . "/$raw_img_name";

        $is_copy_successful = copy($raw_img_url, $raw_img_dest);
        if(!$is_copy_successful)
        {
            echo "Failed to download img: $raw_img_name from raw S3 bucket";
            exit(1);
        }
        return $raw_img_dest;
    }
    