<?php
    require 'lib/authenticate_user.php';

    if (session_status() == PHP_SESSION_NONE)
    {
        session_destroy();
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Login In</title>
</head>
<body>
<div id = "center box" style = "height: 200px; width: 400px; position: fixed; top: 50%; left: 50%; margin-top: -100px; margin-left: -200px;">
    <form method="post">
        Username <input type="text" name="user_name" required/>
        <br><br>
        Password <input type="password" name="password" required/>
        <br><br>
        <input type="submit" name="submit" value="Login"/>
    </form>
</div>
</body>
</html>
