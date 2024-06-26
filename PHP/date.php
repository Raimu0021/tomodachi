<?php 
require 'common/header.php' ;

require './common/db-connect.php'; 
$loggedInUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>
<!-- いいねユーザー表示 -->
        
<?php
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
                    renderCard($user['profile_image'], $user['user_name'], $user['date_of_birth'], $user['gender'], $user['school_id']);
                    echo '</div>';
                }
            }
        }
    ?>

<?php require './common/footer.php'; ?>

