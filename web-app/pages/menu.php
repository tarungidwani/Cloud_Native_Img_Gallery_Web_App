<?php
    require 'init.php';

    function get_enabled_features()
    {
        $features = ["<li><a href=\"gallery.php\">Gallery</a></li>", "<li><a href=\"upload.php\">Upload</a></li>"];

        if($_SESSION['is_admin'])
            $features[] = "<li><a href=\"admin.php\">Admin</a></li>";
        return $features;
    }
    $features = get_enabled_features();
?>

<ul>
    <?php
        echo "<h3>Welcome User: " . $_SESSION['user_name'] . "</h3>";
        foreach($features as $feature)
            print "$feature";
    ?>
</ul>
