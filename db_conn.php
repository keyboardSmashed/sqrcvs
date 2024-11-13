<?php
    $sname = "localhost";
    $uname = "root";
    $password = "";

    $db_name = "sqrcvs";

    $mysqli = new mysqli($sname, $uname, $password, $db_name);

    if ($mysqli->connect_errno) {
        die("Connection error: ". $mysqli->connect_error);
    }

    return $mysqli;
?>