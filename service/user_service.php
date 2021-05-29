<?php
    require_once "database/user_data_access.php";
    function login_service($email, $pass, $type)
    {
        return login($email, $pass, $type);
    }
?>