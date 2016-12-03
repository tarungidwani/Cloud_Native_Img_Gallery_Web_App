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
        <?php
            $upload_feature_status_msg = $_SESSION['upload_feature_status_msg'];
            if(!empty($upload_feature_status_msg))
            {
                echo "$upload_feature_status_msg";
                unset($_SESSION['upload_feature_status_msg']);
            }
        ?>

        <h3>DB Backup</h3>
        <input type="submit" name="submit"                value="Back Up" formaction="admin_features.php">
        <?php
            $db_backup_feature_msg = $_SESSION['db_backup_msg'];
            if(!empty($db_backup_feature_msg))
            {
                echo "$db_backup_feature_msg";
                unset($_SESSION['db_backup_msg']);
            }
        ?>

        <h3>DB Restore</h3>
        <input type="submit" name="submit"                value="Restore" formaction="admin_features.php">
        <?php
            $restore_backup_feature_msg = $_SESSION['db_restore_msg'];
            if(!empty($restore_backup_feature_msg))
            {
                echo "$restore_backup_feature_msg";
                unset($_SESSION['db_restore_msg']);
            }
        ?>
    </form>
</body>
</html>

