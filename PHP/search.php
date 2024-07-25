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

<div class="contant">
<h2>検索結果</h2>

<?php
if (count($results) > 0) {
    echo '<div class="user_cards">';
    foreach ($results as $row) {
        renderCard($row['user_id'], $row['profile_image'], $row['user_name'], $row['date_of_birth'], $row['gender'], $row['school_id'], $user_id);
    }
    echo '</div>';
} else {
    // 検索結果が0件の場合のメッセージ
    echo "<p>検索結果に一致する生徒はいませんでした。</p>";
}
?>
</div>
<style>
    .content {
        height: 100vh;
        padding: 15px 20px;
        overflow: scroll;
        /*IE(Internet Explorer)・Microsoft Edgeへの対応*/
        -ms-overflow-style: none;
        /*Firefoxへの対応*/
        scrollbar-width: none;
    }
    /*Google Chrome、Safariへの対応*/
    .content::-webkit-scrollbar {
        display: none;
    }
    .content .search {
        width: 80%;
        margin: 10px auto 0 auto;
    }
    .user_cards {
        display: flex;
        padding: 10px;
        overflow-x: scroll;
        margin-bottom: 30px;
        /*IE(Internet Explorer)・Microsoft Edgeへの対応*/
        -ms-overflow-style: none;
        /*Firefoxへの対応*/
        scrollbar-width: none;
    }
    /*Google Chrome、Safariへの対応*/
    .user_cards::-webkit-scrollbar {
        display: none;
    }
    .user_card {
        margin-left: 20px;
        width: 200px;
        height: 300px;
        border: 1px solid #dadada;
        border-radius: 5px;
        background-color: white;
        text-align: center;
    }
    .user_card img {
        border-radius: 50%;
        height: 100px;
        width: 100px;
        object-fit: cover;
        margin: 15px auto 0 auto;
    }
    .card-body {
        text-align: right;
        font-weight: 400;
        padding: 20px;
    }
    .card-body .like-btn {
        background: white;
        border: none;
        color: #e62748;
        width: 100%;
        text-align: right;
        cursor: pointer;
    }
    .card-body .user_name {
        font-size: 23px;
        font-weight: 600;
        text-align: center;
    }
    .card-body .school {
        font-size: 13px;
        text-align: center;
    }
</style>
<script src="../js/likeButtonHandler.js"></script>
<?php require 'common/footer.php';?>