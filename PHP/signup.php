<?php
session_start();
include 'common/db-connect.php';

$errors = []; // エラーメッセージを格納する配列

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['pass'];

    // メールアドレスのバリデーション
    if (empty($email)) {
        $errors['email'] = 'メールアドレスは必須です。';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = '無効なメールアドレス形式です。';
    }

    // パスワードのバリデーション
    if (empty($password)) {
        $errors['pass'] = 'パスワードは必須です。';
    }

    if (count($errors) === 0) {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $member = $stmt->fetch();
            if ($member) {
                $errors['email'] = '同じメールアドレスが存在します。';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users(email, password) VALUES (:email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':password', $passwordHash);
                $stmt->execute();
                $_SESSION['id'] = $conn->lastInsertId(); // 新規登録したユーザーのIDをセッションに保存
                header('Location: home.php');
                exit;
            }
        } catch (PDOException $e) {
            $errors['db'] = 'データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
</head>
<body>
<link rel="stylesheet" href="../CSS/login.css">
<div class="flexbox">
    <div class="content">
        <?php foreach ($errors as $error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endforeach; ?>
        <div class="logo">
            <img src="../CSS/copuruLogo.jpg" alt="ロゴ" class="logo">
            <h1>coプル</h1>
        </div>
        <p>あなたの出会いをサポートします</p>
        <h3>新規登録</h3>
        <form action="" method="POST">    
            <p>メールアドレス</p>
            <input type="email" name="email">
            <p>パスワード</p>
            <input type="password" name="pass">
            <button type="submit" class="btn">新規登録</button>
        </form>
    </div>
    <p class="box">登録済みですか？
        <a href="login.php"><button class="btn">ログイン</button></a>
    </p>
</div>
</body>
</html>
