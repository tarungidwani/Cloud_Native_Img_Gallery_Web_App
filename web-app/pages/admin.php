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
</body>
</html>

