<?php
require __DIR__ . '/db-connect.php'; 

$searchText = isset($_GET['text']) ? $_GET['text'] : '';

$sql = "SELECT school_id, school_name FROM schools WHERE school_name LIKE :text LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':text', '%' . $searchText . '%', PDO::PARAM_STR);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC); // この行を変更

echo json_encode($results);
?>