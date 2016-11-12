<?php
    require 'lib/authenticate_user.php';

    if (session_status() == PHP_SESSION_NONE)
    {
        session_destroy();
        session_start();
    }

    /* Verifies user entered credentials
     * and redirects to welcome page on
     * success or displays error msg
     * on failure
     */
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $_SESSION['user_name'] = $_POST['user_name'];
        $user_entered_password = md5($_POST['password']);
        $are_credentials_valid = are_user_credentials_valid($_POST['user_name'], $user_entered_password);

        if($are_credentials_valid)
           header('Location: pages/welcome.php');
        else
            print "<h3 style='padding-left: 478px; padding-top: 320px'>Error: Invalid login credentials!</h3>";
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
