<?php
session_start();
require './common/header.php';
require './common/card_component.php';
require './common/db-connect.php'; 
require './common/searchSchool.php';
$loggedInUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$user_id = $_SESSION['user_id'];
$schoolId = $_GET['school_id'];

$sql = "SELECT * FROM users WHERE school_id = :school_id AND is_private = 0";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':school_id', $schoolId, PDO::PARAM_INT);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="row">

<h2>検索結果</h2>

<?php
if (count($results) > 0) {
    foreach ($results as $row) {
        echo '<div class="col-md-3 mb-4">';
            renderCard($row['user_id'], $row['profile_image'], $row['user_name'], $row['date_of_birth'], $row['gender'], $row['school_id'], $user_id);
        echo '</div>';
    }
} else {
    // 検索結果が0件の場合のメッセージ
    echo "<p>検索結果に一致する生徒はいませんでした。</p>";
}
?>
    </div>
</div>

<script src="../js/likeButtonHandler.js"></script>