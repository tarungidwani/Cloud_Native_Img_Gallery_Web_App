<?php
    require 'init.php';
    require 'menu.php';
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Upload</title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
    <input type="file" accept="image/png,image/jpeg"  name="img_path" style="padding-bottom: 20px;padding-top: 20px;" />
    <br>
    <input type="submit" value="Upload" formaction="uploader.php"/>
    <br>
    <?php
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
