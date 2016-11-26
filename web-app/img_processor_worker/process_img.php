<?php

    define("IITLOGOIMG", dirname(__DIR__) . '/IIT-logo.png');
    define("DESTDIR", '/tmp');

    function download_img($raw_img_url)
    {
        $img_info = pathinfo($raw_img_url);
        $raw_img_name = $img_info['basename'];
        $raw_img_dest_dir = constant("DESTDIR") . "/$raw_img_name";

        $is_copy_successful = copy($raw_img_url, $raw_img_dest_dir);

        return $is_copy_successful;
    }
    