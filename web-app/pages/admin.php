<?php
    require 'init.php';
    require 'menu.php';

    if(!$_SESSION['is_admin'])
        header('Location: welcome.php');
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
</head>
<body>
    <h3>Upload Feature</h3>
    <form method="post">
        <input type="radio"  name="upload_feature_status" value=1> Enable
        <input type="radio"  name="upload_feature_status" value=0> Disable
        <input type="submit" name="submit"                value="Save" formaction="admin_features.php">
    </form>
</body>
</html>

