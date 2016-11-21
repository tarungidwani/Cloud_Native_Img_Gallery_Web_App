<?php

    session_start();

    if($_SESSION['client_token'] == null)
        header('location: ../index.php');