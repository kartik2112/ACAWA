<?php
    session_start();
    if(!isset($_SESSION['userid']) ){
        header("location: login.php");
    }

    unset($_SESSION['userid']);
    unset($_SESSION['Alogin']);

    session_unset();
    session_destroy();
    header("location: login.php");
?>

