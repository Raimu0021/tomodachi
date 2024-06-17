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
<?php require 'common/header.php'?>
<link rel="stylesheet" href="../CSS/signup.css">
<div class="flexbox">
    <div class="content">
        <?php foreach ($errors as $error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endforeach; ?>
        <form action="" method="POST">
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
<?php require 'common/footer.php'?>