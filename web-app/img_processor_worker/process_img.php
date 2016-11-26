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

    function create_img_based_on_type($raw_img_path)
    {
        $img_type = exif_imagetype($raw_img_path);

        if ($img_type == 2)
            return imagecreatefromjpeg($raw_img_path);
        elseif($img_type == 3)
            return imagecreatefrompng($raw_img_path);
        else
        {
            echo "Failed to recognize type/signature of image, please upload another image";
            exit(1);
        }
    }

    function apply_watermark($raw_img_path)
    {
        ini_set('memory_limit','-1');
        $raw_img = create_img_based_on_type($raw_img_path);
        $stamp = imagecreatefrompng(constant("IITLOGOIMG"));
        $stamp_img_x = imagesx($stamp);
        $stamp_img_y = imagesy($stamp);
        $margin_right  = 10;
        $margin_bottom = 10;
        $is_image_copy_successful = imagecopy($raw_img,$stamp,imagesx($raw_img) - $stamp_img_x -$margin_right, imagesy($raw_img) - $stamp_img_y -$margin_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

        if(!$is_image_copy_successful)
        {
            echo "Failed to apply IIT logo watermark to raw img: $raw_img_path";
            exit(1);
        }
        $finished_img_path = constant("DESTDIR") . '/' . pathinfo($raw_img_path)['filename'] . '_finished.png';
        imagepng($raw_img, $finished_img_path);
        imagedestroy($raw_img);

        return $finished_img_path;
    }

    function process_img($raw_img_url)
    {
        $raw_img_path      = download_img($raw_img_url);
        $finished_img_path = apply_watermark($raw_img_path);

        return $finished_img_path;
    }
    