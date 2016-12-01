<?php
    require 'init.php';
    require 'menu.php';
    require dirname(__DIR__) . '/lib/feature_status.php';
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Upload</title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
    <?php
        $feature_name = 'upload';
        $is_upload_feature_enabled = is_feature_enabled($feature_name);

        if($is_upload_feature_enabled)
        {
            echo "<input type=\"file\" accept=\"image/png,image/jpeg\"  name=\"img_path\" style=\"padding-bottom: 20px;padding-top: 20px;\" />";
            echo "<br>";
            echo "<input type=\"submit\" value=\"Upload\" formaction=\"uploader.php\"/>";
            echo "<br>";
        }
        else
            echo "<h3>Upload feature is currently unavailable due to maintenance, please check back again later</h3>";

        $invalid_img_type_err_msg = $_SESSION['invalid_img_type_err'];
        if( !empty($invalid_img_type_err_msg))
        {
            echo "<h3>$invalid_img_type_err_msg</h3>";
            unset($_SESSION['invalid_img_type_err']);
        }
    ?>
</form>
</body>
</html>
