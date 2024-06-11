<?php
require __DIR__ . '/db-connect.php'; 

$searchText = isset($_GET['text']) ? $_GET['text'] : '';

$sql = "SELECT school_id, school_name FROM schools WHERE school_name LIKE :text LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':text', '%' . $searchText . '%', PDO::PARAM_STR);
$stmt->execute();

$results = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($results);
?>