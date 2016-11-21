<?php

    require '../lib/feature_status.php';

    function get_enabled_features()
    {
        $features = ["<li><a href=\"gallery.php\">Gallery</a></li>"];
        $is_upload_feature_enabled = is_feature_enabled('upload');

        if($is_upload_feature_enabled)
            $features[] = "<li><a href=\"upload.php\">Upload</a></li>";

        $a = $_SESSION['is_admin'];
        if($_SESSION['is_admin'])
            $features[] = "<li><a href=\"admin.php\">Admin</a></li>";
        return $features;
    }
    $features = get_enabled_features();
?>

<ul>
    <?php
    foreach($features as $feature)
        print "$feature";
    ?>
</ul>
