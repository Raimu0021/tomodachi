<?php
    const SERVER = 'mysql304.phy.lolipop.lan';
    const DBNAME = 'LAA1521164-copuru';
    const USER = 'LAA1521164';
    const PASS = 'asojuku';

    $connect = 'mysql:host='. SERVER .";dbname=".  DBNAME .';charset=utf8';
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>