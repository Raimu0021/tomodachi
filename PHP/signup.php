<?php
session_start();
include 'common/db-connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    try {
        $dbh = new PDO($connect, USER, PASS);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $member = $stmt->fetch();
        if ($member) {
            $_SESSION['msg'] = '同じメールアドレスが存在します。';
            header('Location: signup.php');
            exit;
        } else {
            $sql = "INSERT INTO users(email, pass) VALUES (:email, :pass)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':pass', $password);
            $stmt->execute();
            $_SESSION['msg'] = '新規登録が完了しました';
            header('Location: login.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['msg'] = 'データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        header('Location: signup.php');
        exit;
    }
} else {
    if (isset($_SESSION['msg'])) {
        $msg = htmlspecialchars($_SESSION['msg'], ENT_QUOTES, 'UTF-8');
        unset($_SESSION['msg']);
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>新規登録</title>
    <link rel="stylesheet" href="../CSS/signup.css">
</head>
<body>
<div class="flexbox">
    <div class="content">
        <?php if (isset($msg)): ?>
            <h1><?php echo $msg; ?></h1>
            <a href="signup.php">戻る</a>
            <?php endif; ?>
            <form action="login.php" method="POST">
            <img src="../CSS/copuruLogo.jpg" alt="ロゴ" class="logo">
                <p>あなたの出会いをサポートします</p>
                <br>
                <p>メールアドレス<br>
                    <input type="email" name="email" required></p>
                <p>パスワード<br>
                    <input type="password" name="pass" required></p><br>
                <button type="submit" class="btn">新規登録</button>
            </form>
    </div><br>
    <p class="box">登録済みですか？
        <a href="login.php"><button class="btn">ログイン</button></a>
    </p>
</div>
</body>
</html>