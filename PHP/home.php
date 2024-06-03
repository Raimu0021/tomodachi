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

        // school_idがnullなら同じ学校の生徒は表示しない
        if($school_id != null){
            if ($stmt->rowCount() > 0) {
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="col-md-3 mb-4">';
                    renderCard($row['profile_image'], $row['user_name'], $row['date_of_birth'], $row['gender'], $row['school_id']);
                    echo '</div>';
                }
            } else {
                echo "<p>同じ学校の生徒は見つかりませんでした</p>";
            }
        }
        

        $conn = null;
        ?>
    </div>
</div>

<?php require './common/footer.php'; ?>