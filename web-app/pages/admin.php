<?php
    require 'init.php';
    require 'menu.php';

    if(!$_SESSION['is_admin'])
        header('Location: welcome.php');
?>

