<?php

    $host = "dbhost.cs.man.ac.uk";
    $db_username = "j53270jm";
    $db_password = "WvyvILzKjl9xRcLd+uHSo2xKRMr8SpugA4c0fwd3MEE";
    $db_name = "2024_comp10120_cm1";

    try{
        $pdo = new PDO("mysql:host=$host;dbname=$db_name", $db_username, $db_password);
    }
    catch(Exception $e){
        die("error" . $e->getMessage());
    }
?>