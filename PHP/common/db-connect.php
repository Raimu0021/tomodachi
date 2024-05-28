<?php
    const SERVER = 'mysql304.phy.lolipop.lan';
    const DBNAME = 'LAA1521164-copuru';
    const USER = 'LAA1521164';
    const PASS = 'asojuku';

    $dsn = 'mysql:host='. SERVER .";dbname=".  DBNAME .';charset=utf8';
    
    try {
        $conn = new PDO($dsn, USER, PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>
