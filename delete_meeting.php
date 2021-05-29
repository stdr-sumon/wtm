<?php
    require_once('database/db.php');
    session_start();
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = pg_query($conn, "select * from delete_meeting('$id');");
        while ($row = pg_fetch_assoc($sql)) {
            // return print_r($row['delete_meeting']);
            $pieces = explode(". ", $row['delete_meeting']);
            $_SESSION['message'] = $pieces[0];
            $_SESSION['flag'] = $pieces[1];
            header("Location: dashboard.php");
        }
    } else {
        $_SESSION['message'] = 'Error';
        $_SESSION['flag'] = 0;
        header("Location: dashboard.php");
    }
    
?>