<?php
session_start();
require './common/header.php';
require './common/card_component.php';
require './common/db-connect.php'; 
require './common/searchSchool.php';
$loggedInUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>

<form action="search.php" method="get" class="mb-4">
    <div class="input-group">
        <input type="text" name="school_name" id="school_name" class="form-control" placeholder="学校名を入力">
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    $('#school_name').on('input', function() {
        let text = $(this).val();
        console.log(text);// 入力したもの
        $.get('./common/searchSchool.php', {text: text}, function(data) {
            let schools = JSON.parse(data);
            console.log(schools);//  API返り値
            let html = '';
            for(let i = 0; i < Object.keys(schools).length; i++) {
                html += '<div><a href="search.php?school_id=' + schools[i].school_id + '">' + schools[i].school_name + '</a></div>';
                console.log(schools.school_name);
            }
            $('#school_predictions').html(html);
        });
    });
});
</script>
<div id="school_predictions">
</div>



<div class="container">
    <div class="row">
    <?php
        $school_id = $loggedInUser ? $loggedInUser['school_id'] : null;
        $sql = "SELECT profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE school_id = :school_id AND is_private = 0 ORDER BY RAND() LIMIT 8";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
        $stmt->execute();

        //　同じ学校の生徒表示
        // school_idがnullなら同じ学校の生徒は表示しない
        if($school_id != null && $stmt->rowCount() > 0){    
            echo "<h2>あなたと同じ学校</h2>";
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="col-md-3 mb-4">';
                renderCard($row['profile_image'], $row['user_name'], $row['date_of_birth'], $row['gender'], $row['school_id']);
                echo '</div>';
            }
        }elseif ($loggedInUser != null && $school_id == null) {
            echo "<h2>あなたと同じ学校</h2>";
            echo "<p>学校を登録して生徒を探しましょう！</p>";
        } elseif ($loggedInUser != null && $stmt->rowCount() == 0){
            echo "<h2>あなたと同じ学校</h2>";
            echo "<p>同じ学校の生徒は見つかりませんでした</p>";
        }
    ?>

        
    <?php
        //　いいねしたユーザーを表示
        if($loggedInUser != null) {

            $sql = "SELECT profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE user_id = :liked_user_id AND is_private = 0";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $loggedInUser, PDO::PARAM_INT);
            $stmt->execute();
    
            while($like = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $sql = "SELECT profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE user_id = :liked_user_id";
                $stmt2 = $conn->prepare($sql);
                $stmt2->bindParam(':liked_user_id', $like['liked_user_id'], PDO::PARAM_INT);
                $stmt2->execute();
                
                while($user = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    echo "<h2>いいね済み</h2>";
                    echo '<div class="col-md-3 mb-4">';
                    renderCard($user['profile_image'], $user['user_name'], $user['date_of_birth'], $user['gender'], $user['school_id']);
                    echo '</div>';
                }
            }
        }
    ?>


    <?php
        // ランダムにユーザー16人を表示
        if($loggedInUser != null){
            $sql = "SELECT profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE is_private = 0 AND user_id != :user_id ORDER BY RAND() LIMIT 16";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $loggedInUser, PDO::PARAM_INT);
            $stmt->execute();
        }else{
            $sql = "SELECT profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE is_private = 0 ORDER BY RAND() LIMIT 16";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
        
        

        while($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="col-md-3 mb-4">';
            renderCard($user['profile_image'], $user['user_name'], $user['date_of_birth'], $user['gender'], $user['school_id']);
            echo '</div>';
        }
    ?>
    
        
    <?php
        $conn = null;
    ?>
    </div>
</div>

<?php require './common/footer.php'; ?>