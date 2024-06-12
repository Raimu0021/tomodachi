<?php
$servername = 'mysql304.phy.lolipop.lan';
$dbname = 'LAA1521164-copuru';
$username = 'LAA1521164';
$password = 'asojuku';

$connect = 'mysql:host='. $servername .";dbname=".  $dbname .';charset=utf8';

try {
    $conn = new PDO($connect, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    error_log("Connected successfully");

} catch(PDOException $e) {
    // If connection failed, try to connect to localhost
    $servername = "localhost";
    $username = 'root';
    $password = 'root';
    $connect = 'mysql:host='. $servername .";dbname=".  $dbname .';charset=utf8';
    
    try {
        $conn = new PDO($connect, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        error_log("Connected successfully");

    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>