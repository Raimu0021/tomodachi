<?php 
require 'common/header.php' ;
require 'date_card_component.php';
require './common/db-connect.php'; 
$loggedInUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>

        
<?php

        //非ログインユーザーの場合、ログインページにリダイレクト
        if ($loggedInUser == null) {
            header('Location: login-logout.php'); 
            exit;
        }
        //ここまで

        // ログインユーザーがデート中の場合、デート評価画面にリダイレクト
        $stmt = $pdo->prepare("SELECT currently_dating FROM users WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['currently_dating'] == 1) {
            header('Location: date_evaluation.php');
            exit;
        }
        //ここまで

        // ログインユーザーがデート中でない場合
        // 1. ログインユーザーがいいねしたユーザーを取得
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
                    echo "<h2>いいねした人</h2>";
                    echo '<div class="col-md-3 mb-4">';
                    renderDateCard($user['profile_image'], $user['user_name'], $user['date_of_birth'], $user['gender'], $user['school_id']);
                    echo '</div>';
                }
            }
        }
        
    ?>
    <script>
    function requestDate(userId, partnerId) {
        // AJAXリクエストをバックエンドに送信
        fetch('/path/to/date_request_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ userId: userId, partnerId: partnerId }),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    </script>

<?php require './common/footer.php'; ?>

