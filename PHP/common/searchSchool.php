<?php
require './common/db-connect.php'; 

$searchText = $_GET['text'];

$sql = "SELECT school_id, school_name FROM schools WHERE school_name LIKE :text LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':text', '%' . $searchText . '%', PDO::PARAM_STR);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>