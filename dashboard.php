<?php
    
    session_start();
    if (!isset($_SESSION['loggedIn'])) {
        header("location: login.php");
    }

    require_once('database/db.php');

    if (isset($_SESSION['type']) && $_SESSION['type'] == 'admin') {
        require_once "admin_dash.php";
    } elseif (isset($_SESSION['type']) && $_SESSION['type'] == 'student') {
        require_once "student_dash.php";
    } else {
        header("location: login.php");
    }

?>