<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // セッションの開始は出力前に行う

include 'common/db-connect.php';

$message = '';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $message = "セッションが見つかりません。再度ログインしてください。";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    
    try {
        // ユーザー情報の取得
        $sql = "SELECT password FROM users WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && password_verify($password, $result['password'])) {
            // アカウントの削除
            $sql = "DELETE FROM users WHERE user_id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                // セッションの終了
                session_destroy();
                header("Location: login-logout.php");
                exit();
            } else {
                $message = "アカウントの削除に失敗しました。";
            }
        } else {
            $message = "パスワードが一致しません。";
        }
        
        $stmt->closeCursor();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
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
    <link rel="stylesheet" href="../CSS/mailaddress.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    require 'common/header.php';
    ?>
    <div class="login-field">
        <div class="login-background">
            <div class="login-title">
                <span>アカウント削除</span>
            </div>
            <div class="login-form">
                <?php if (!empty($message)): ?>
                    <div class="message"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="field">
                        <label for="password">パスワード</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="field button-field">
                        <button class="button button-login" type="submit">削除する</button>
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
