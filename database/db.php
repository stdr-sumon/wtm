<?php
    $host        = "host = 127.0.0.1";
    $port        = "port = 5432";
    $dbname      = "dbname = wtm";
    $credentials = "user = postgres password=123456";

    $conn = pg_connect("$host $port $dbname $credentials");
?>