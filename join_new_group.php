<?php
    require_once('database/db.php');
    
    session_start();
    if (isset($_SESSION['type']) && $_SESSION['type'] != 'student') {
        return;
        session_destroy();
        header("location: login.php");
    }

    if (isset($_GET['stud_id']) && isset($_GET['group_id'])) {
        $stud_id = $_GET['stud_id'];
        $group_id = $_GET['group_id'];
        $meeting_id = $_GET['meet_id'];
        // return print_r($_GET);
        $sql = pg_query($conn, "select * from join_study_group($stud_id, $group_id);");

        while ($row = pg_fetch_assoc($sql)) {
            session_start();
            $pieces = explode(". ", $row['join_study_group']);
            $_SESSION['msg'] = $pieces[0];
            $_SESSION['insertUpdateFlag'] = $pieces[1];
            header("Location: student_meeting.php?id=$meeting_id");
        }
    }

?>