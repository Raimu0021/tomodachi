<?php 
require 'common/header.php' ;
require 'common/date_card_component.php';
require './common/db-connect.php'; 
$loggedInUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$hasDateRequest = false;
?>

        
<?php

        //非ログインユーザーの場合、ログインページにリダイレクト
        if ($loggedInUser == null) {
            header('Location: login-logout.php'); 
            exit;
        }
        //ここまで

        // ログインユーザーがデート中の場合、デート評価画面にリダイレクト
        $stmt = $conn->prepare("SELECT currently_dating FROM users WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['currently_dating'] == 1) {
            header('Location: date_evaluation.php');
            exit;
        }
        //ここまで

        // ログインユーザーがデート中でない場合        
        if($loggedInUser != null) {

            // ログインユーザーのIDをセッションから取得
            $loggedInUserId = $_SESSION['user_id'];
        
            // デート申請を送ってきたユーザーを取得
            $dateRequestsSql = "SELECT u.* FROM users u JOIN dates d ON u.user_id = d.sender_id WHERE d.receiver_id = :loggedInUserId AND d.is_hidden = 0 AND u.currently_dating = 0 ORDER BY RAND() LIMIT 8";
            $stmt = $conn->prepare($dateRequestsSql);
            $stmt->execute(['loggedInUserId' => $loggedInUserId]);
            $dateRequestUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // いいねを送ってきたユーザーを取得
            $likesSql = "SELECT u.* FROM users u JOIN likes l ON u.user_id = l.user_id WHERE l.liked_user_id = :loggedInUserId AND u.currently_dating = 0 ORDER BY RAND() LIMIT 8";
            $stmt = $conn->prepare($likesSql);
            $stmt->execute(['loggedInUserId' => $loggedInUserId]);
            $usersWhoLikedLoggedInUser = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            
            $sql = "SELECT liked_user_id FROM likes WHERE user_id = :user_id ORDER BY RAND() LIMIT 8";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $loggedInUser, PDO::PARAM_INT);
            $stmt->execute();
            $userIdsLikedByLoggedInUser = $stmt->fetchAll(PDO::FETCH_ASSOC); // いいねしたユーザーのIDを取得
        
            echo "<h2>いいねした人</h2>";
            // 1. ログインユーザーがいいねしたユーザーの詳細を取得
            foreach($userIdsLikedByLoggedInUser as $like) {
                
                $sql = "SELECT profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE user_id = :liked_user_id AND is_private = 0 AND currently_dating = 0";
                $stmt2 = $conn->prepare($sql);
                $stmt2->bindParam(':liked_user_id', $like['liked_user_id'], PDO::PARAM_INT);
                $stmt2->execute();
                

                
                while($user = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    
                    echo '<div class="col-md-3 mb-4">';
                    renderDateCard($_SESSION['user_id'], $like['liked_user_id'], $user['profile_image'], $user['user_name'], $user['date_of_birth'], $user['gender'], $user['school_id'],$hasDateRequest);
                    echo '</div>';
                }
            }

            // 2. デート申請を送ってきたユーザーを取得　
            echo "<h2>デート申請を送ってきたユーザー</h2>";
            $hasDateRequest = true;
            foreach($dateRequestUsers as $user) {
                echo '<div class="col-md-3 mb-4">';
                renderDateCard($_SESSION['user_id'], $user['user_id'], $user['profile_image'], $user['user_name'], $user['date_of_birth'], $user['gender'], $user['school_id'],$hasDateRequest);
                echo '</div>';
            }
            $hasDateRequest = false;

            // 3. いいねを送ってきたユーザーを取得
            echo "<h2>いいねを送ってきたユーザー</h2>";
            foreach($usersWhoLikedLoggedInUser as $user) {
                echo '<div class="col-md-3 mb-4">';
                renderDateCard($_SESSION['user_id'], $user['user_id'], $user['profile_image'], $user['user_name'], $user['date_of_birth'], $user['gender'], $user['school_id'],$hasDateRequest);
                echo '</div>';
            }
        }

        
        


        // 4. 断る、キャンセルボタンを作成する
        // option 1. デート申請を送ったユーザーを取得（デートボタンの非表示）
        
    ?>
    <script>
    function requestDate(sender_id, receiver_id) {
        // AJAXリクエストをバックエンドに送信
        fetch('handler/date_request_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ sender_id: sender_id, receiver_id: receiver_id }),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    function declineDate(sender_id, receiver_id){
        // AJAXリクエストをバックエンドに送信
        fetch('handler/date_decline_handler.php',{
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ sender_id: sender_id, receiver_id: receiver_id }),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:',data);
        })
        .catch((error) =>{
            console.error('Error:', error);
        });
    }
    </script>

<?php require './common/footer.php'; ?>

