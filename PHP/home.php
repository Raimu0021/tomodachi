<?php
session_start();
require './common/header.php';
require './common/card_component.php';
require './common/db-connect.php'; 
require './common/searchSchool.php';
$loggedInUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$user_id = $_SESSION['user_id'];
$sql = "SELECT school_id FROM users WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$school_id = $stmt->fetchColumn();
?>



<!-- 検索欄 -->
<div class="contant">
        <form id="schoolSearchForm" action="search.php" method="get" class="mb-4 search">
            <div class="input-group">
                <input type="text" name="school_name" id="school_name" class="form-control" placeholder="学校名を入力">
            </div>
        </form>
        <div id="school_predictions"></div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../js/search.js"></script>
<!-- 検索欄ここまで-->

<!-- ユーザー表示 -->
    <?php
        $sql = "SELECT * FROM users WHERE school_id = :school_id AND user_id != :user_id AND is_private = 0 ORDER BY RAND() LIMIT 8";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        //　同じ学校の生徒表示
        // school_idがnullなら同じ学校の生徒は表示しない
        if($school_id != null && $stmt->rowCount() > 0){    
            echo "<h2>あなたと同じ学校</h2>";
            echo '<div class="user_cards">';
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                renderCard($row['user_id'], $row['profile_image'], $row['user_name'], $row['date_of_birth'], $row['gender'], $row['school_id'], $user_id);
            }
            echo '</div>';
        }elseif ($loggedInUser != null && $school_id == null) {
            echo "<h2>あなたと同じ学校</h2>";
            echo "<p>学校を登録して生徒を探しましょう！</p>";
        } elseif ($loggedInUser != null && $stmt->rowCount() == 0){
            echo "<h2>あなたと同じ学校</h2>";
            echo "<p>同じ学校の生徒は見つかりませんでした</p>";
        }
    ?>

<!-- ユーザー表示ここまで -->

<!-- いいねユーザー表示 -->
        
    <?php
        if($loggedInUser != null) {

            $sql = "SELECT user_id, profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE user_id = :user_id AND is_private = 0";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $loggedInUser, PDO::PARAM_INT);
            $stmt->execute();

            while($like = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $sql = "SELECT user_id, profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE user_id = :liked_user_id";
                $stmt2 = $conn->prepare($sql);
                $stmt2->bindParam(':liked_user_id', $like['liked_user_id'], PDO::PARAM_INT);
                $stmt2->execute();
                echo "<h2>いいね済み</h2>";
                echo '<div class="user_cards">';
                while($user = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    renderCard($user['user_id'], $user['profile_image'], $user['user_name'], $user['date_of_birth'], $user['gender'], $user['school_id'], $user_id);    
                }
                echo '</div>';
            }
        }
    ?>

<!-- いいねユーザー表示ここまで -->

<!-- ランダムユーザー表示 -->
    <?php
        if($loggedInUser != null){
            $sql = "SELECT user_id, profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE is_private = 0 AND user_id != :user_id ORDER BY RAND() LIMIT 16";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $loggedInUser, PDO::PARAM_INT);
            $stmt->execute();
        }else{
            $sql = "SELECT user_id, profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE is_private = 0 ORDER BY RAND() LIMIT 16";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
        
        
        echo '<h2>ランダム表示</h2>';
        echo '<div class="user_cards">';
        while($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            renderCard($user['user_id'], $user['profile_image'], $user['user_name'], $user['date_of_birth'], $user['gender'], $user['school_id'], $user_id);
        }
        echo '</div>';
    ?>
<!-- ランダムユーザー表示ここまで -->
      
<!-- いいね処理 -->
<script src="../js/likeButtonHandler.js"></script>

    <?php
        $conn = null;
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

<?php require './common/footer.php'; ?>
