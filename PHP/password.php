<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // セッションの開始は出力前に行う

include 'common/db-connect.php';

$message = '';
$current_password = '';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // 現在のパスワードを取得
    $sql = "SELECT password FROM users WHERE user_id = :user_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $current_password = $result['password'];
    } else {
        $message = "ユーザーが見つかりません。";
    }

    $stmt->closeCursor();
} else {
    $message = "セッションが見つかりません。再度ログインしてください。";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password_input = $_POST['current-password'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];
    
    if (password_verify($current_password_input, $current_password)) {
        if ($new_password === $confirm_password) {
            // 新しいパスワードをハッシュ化して更新
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = :new_password WHERE user_id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':new_password', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $message = "パスワードが更新されました。";
            } else {
                $message = "パスワードの更新に失敗しました。";
            }
            
            $stmt->closeCursor();
        } else {
            $message = "新しいパスワードが一致しません。";
        }
    } else {
        $message = "現在のパスワードが正しくありません。";
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/password.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    require 'common/header.php';
    ?>
    <div class="back-link">
            <a href="setting.php" class="btn btn-secondary">戻る</a>
        </div>
    <div class="login-field">
        <div class="login-background">
            <div class="login-title">
                <span>パスワード変更</span>
            </div>
            <div class="login-form">
                <?php if (!empty($message)): ?>
                    <div class="message"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="field">
                        <label for="current-password">現在のパスワード</label>
                        <input type="password" id="current-password" name="current-password" required>
                    </div>
                    <div class="field">
                        <label for="new-password">新しいパスワード</label>
                        <input type="password" id="new-password" name="new-password" required>
                    </div>
                    <div class="field">
                        <label for="confirm-password">新しいパスワード確認</label>
                        <input type="password" id="confirm-password" name="confirm-password" required>
                    </div>
                    <div class="field button-field">
                        <button class="button button-login" type="submit">変更する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <span class="square square-tl"></span>
    <span class="square square-tr"></span>
    <span class="square square-bl"></span>
    <span class="square square-br"></span>
    <span class="star star1"></span>
    <span class="star star2"></span>
</body>

</html>
<?php require 'common/footer.php'; ?>
