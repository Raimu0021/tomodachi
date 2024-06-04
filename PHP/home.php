<?php
session_start();
require './common/header.php';
require './common/card_component.php';
require './common/db-connect.php'; 
$loggedInUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>

<form action="search.php" method="get" class="mb-4">
    <div class="input-group">
        <input type="text" name="school_name" class="form-control" placeholder="学校名を入力">
        <button class="btn btn-primary" type="submit">検索</button>
    </div>
</form>

<div class="container">
    <div class="row">
    <?php
        $school_id = $loggedInUser ? $loggedInUser['school_id'] : null;
        $sql = "SELECT profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE school_id = :school_id ORDER BY RAND() LIMIT 8";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
        $stmt->execute();

        //　同じ学校の生徒表示
        // school_idがnullなら同じ学校の生徒は表示しない
        if($school_id != null && $stmt->rowCount() > 0){    
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="col-md-3 mb-4">';
                renderCard($row['profile_image'], $row['user_name'], $row['date_of_birth'], $row['gender'], $row['school_id']);
                echo '</div>';
            }
            }elseif ($loggedInUser != null && $school_id == null) {
                echo "<p>学校を登録して生徒を探しましょう！</p>";
            } elseif ($loggedInUser != null && $stmt->rowCount() == 0){
                echo "<p>同じ学校の生徒は見つかりませんでした</p>";
            }
        
        
        //　いいねしたユーザーを表示
        if($loggedInUser != null) {
            // likesテーブルからliked_user_idを取得
            $sql = "SELECT liked_user_id FROM likes WHERE user_id = :user_id LIMIT 8";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $loggedInUser, PDO::PARAM_INT);
            $stmt->execute();
    
            // liked_user_idを使用してusersテーブルからユーザー情報を取得
            while($like = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $sql = "SELECT profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE user_id = :liked_user_id";
                $stmt2 = $conn->prepare($sql);
                $stmt2->bindParam(':liked_user_id', $like['liked_user_id'], PDO::PARAM_INT);
                $stmt2->execute();
    
                // ユーザー情報を使用してカードを表示
                while($user = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="col-md-3 mb-4">';
                    renderCard($user['profile_image'], $user['user_name'], $user['date_of_birth'], $user['gender'], $user['school_id']);
                    echo '</div>';
                }
            }
        }

        
        

        $conn = null;
        ?>
    </div>
</div>

<?php require './common/footer.php'; ?>