<?php

    session_start();
    require 'menu.php';

    if($_SESSION['client_token'] == null)
        header('location: ../index.php');
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>
</body>
</html>
