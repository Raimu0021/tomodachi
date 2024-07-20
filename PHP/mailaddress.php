<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // セッションの開始は出力前に行う

include 'common/db-connect.php';

$message = '';
$current_email = '';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // 現在のメールアドレスを取得
    $sql = "SELECT email FROM users WHERE user_id = :user_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $current_email = $result['email'];
    } else {
        $message = "ユーザーが見つかりません。";
    }

    $stmt->closeCursor();
} else {
    $message = "セッションが見つかりません。再度ログインしてください。";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = $_POST['new-email'];
    $confirm_email = $_POST['confirm-email'];
    
    if ($new_email === $confirm_email) {
        // 新しいメールアドレスを更新
        $sql = "UPDATE users SET email = :new_email WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':new_email', $new_email, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $message = "メールアドレスが更新されました。";
            $current_email = $new_email;
        } else {
            $message = "メールアドレスの更新に失敗しました。";
        }
        
        $stmt->closeCursor();
    } else {
        $message = "新しいメールアドレスが一致しません。";
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
                <span>メールアドレス変更</span>
            </div>
            <div class="login-form">
                <?php if (!empty($message)): ?>
                    <div class="message"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="field">
                        <label for="current-email">現在のメールアドレス</label>
                        <input type="text" id="current-email" name="current-email" value="<?php echo htmlspecialchars($current_email ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly>
                    </div>
                    <div class="field">
                        <label for="new-email">新しいメールアドレス</label>
                        <input type="email" id="new-email" name="new-email" required>
                    </div>
                    <div class="field">
                        <label for="confirm-email">新しいメールアドレス確認</label>
                        <input type="email" id="confirm-email" name="confirm-email" required>
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
