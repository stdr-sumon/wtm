<?php
    require_once('database/db.php');
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id = $_POST['meetingId'];
        $is_active = $_POST['is_active'];
        $sql = pg_query($conn, "select * from update_meeting_visibilty($id, '$is_active')");
        $return_arr = array();
        while ($row = pg_fetch_assoc($sql)) {
            $pieces = explode(". ", $row['update_meeting_visibilty']);
            $return_arr[] =  array("msg" => $pieces[0], "flag" => $pieces[1]);
            echo json_encode($return_arr);
        }
    }
    
?>