<?php
    include 'database/db.php';
    function login($user, $pass, $type)
    {
        $data = array();
        if ($type == "admin") {
            $sql = pg_query($GLOBALS['conn'], "select * from admin_login('$user','$pass')");
            while ($row = pg_fetch_assoc($sql)) {
                $data['id'] = $row['fsr_id'];
                $data['name'] = $row['fsr_user'];
                $data['type'] = 'admin';
            }
            return $data;
        } elseif($type == "student") {
            $sql = pg_query($GLOBALS['conn'], "select * from student_login('$user','$pass')");
            while ($row = pg_fetch_assoc($sql)) {
                $data['id'] = $row['stud_id'];
                $data['name'] = $row['stud_user'];
                $data['type'] = 'student';
            }
            return $data;
        }
    }
?>