<?php
session_start();
require './common/header.php';
require './common/card_component.php';
require './common/db-connect.php'; 
require './common/searchSchool.php';
$loggedInUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$schoolId = $_GET['school_id'];

$sql = "SELECT profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE school_id = :school_id AND is_private = 0";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':school_id', $schoolId, PDO::PARAM_INT);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>検索結果</h2>

<?php
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    renderCard($row['profile_image'], $row['user_name'], $row['date_of_birth'], $row['gender'], $row['school_id']);
}

?>

