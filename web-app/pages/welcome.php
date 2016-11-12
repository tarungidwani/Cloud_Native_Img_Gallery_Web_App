<?php

    session_start();
    $features = ["<li><a href=\"gallery.php\">Gallery</a></li>",
                 "<li><a href=\"upload.php\">Upload</a></li>"];

    if($_SESSION['client_token'] == null)
        header('location: ../index.php');

    if($_SESSION['is_admin'])
        $features[] = "<li><a href=\"admin.php\">Admin</a></li>";
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>
<ul>
    <?php
    foreach($features as $feature)
        print "$feature";
    ?>
</ul>
</body>
</html>
