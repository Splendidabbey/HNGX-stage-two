<?php
    $serverName = "localhost";
    $userName = "root";
    $password = "";
    $database = "rest_api_db";

    $conn = new mysqli($serverName, $userName, $password, $database);
    if ($conn->connect_error){
        die("Connection failed".$conn->connect_error);
    }
?>