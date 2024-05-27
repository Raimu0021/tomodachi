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
        
        if ($member && password_verify($password, $member['pass'])) {
            $_SESSION['id'] = $member['id'];
            $_SESSION['name'] = $member['name'];
            $msg = 'ログインしました。';
            $link = '<a href="home.php">ホーム</a>';
        } else {
            $msg = 'メールアドレスもしくはパスワードが間違っています。';
            $link = '<a href="login.php">戻る</a>';
        }
    } catch (PDOException $e) {
        $msg = 'エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        $link = '<a href="login.php">戻る</a>';
    }
} else {
    $msg = '不正なリクエストです。';
    $link = '<a href="login.php">戻る</a>';
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
    <?php if (!empty($message)): ?>
        <h1><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></h1>
        <?php echo $link; ?>
    <?php endif; ?>
        <form action="home.php" method="POST">
        <img src="../CSS/copuruLogo.jpg" alt="ロゴ" class="logo">
            <p>あなたの出会いをサポートします</p>
            <br>
            <p>メールアドレス<br>
                <input type="text" name="email" required></p>
            <p>パスワード<br>
                <input type="password" name="pass" required></p><br>
            <button type="submit" class="btn">ログイン</button>
        </form>
    </div><br>
        <p class="box">今すぐ出会いが欲しいですか？
        <a href="signup.php"><button class="btn">新規登録</button></a></p>
</div>
</body>
</html>