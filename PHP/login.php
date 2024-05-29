<?php
session_start();
include 'common/db-connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['pass'];
    
    try {
        $conn = new PDO($connect, USER, PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $member = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($member && password_verify($password, $member['password'])) {
            $_SESSION['id'] = $member['user_id'];
            $_SESSION['name'] = $member['user_name'];
            $_SESSION['msg'] = 'ログインしました。';
            header('Location: home.php');
            exit;
        } else {
            $_SESSION['msg'] = 'メールアドレスもしくはパスワードが間違っています。';
            header('Location: login.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['msg'] = 'エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        header('Location: login.php');
        exit;
    }
} else {
    if (!isset($_SESSION['msg'])) {
        $_SESSION['msg'] = '';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
<div class="flexbox">
    <div class="content">

        <?php if (isset($_SESSION['msg'])): ?>
            <h1><?php echo htmlspecialchars($_SESSION['msg'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>
        <form action="" method="POST">
            <img src="../CSS/copuruLogo.jpg" alt="ロゴ" class="logo">

            <p>あなたの出会いをサポートします</p>
            <br>
            <p>メールアドレス<br>
                <input type="email" name="email" required></p>
            <p>パスワード<br>
                <input type="password" name="pass" required></p><br>
            <button type="submit" class="btn">ログイン</button>
        </form>
        </div><br>
        <p class="box">今すぐ出会いが欲しいですか？
            <a href="signup.php"><button class="btn">新規登録</button></a>
        </p>
    </div>
</div>
</body>
</html>