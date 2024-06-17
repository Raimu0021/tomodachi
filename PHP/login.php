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
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $errors['login'] = '入力されたメールアドレスのユーザーは存在しません';
            } elseif (!password_verify($password, $user['password'])) {
                $errors['login'] = 'メールアドレスもしくはパスワードが間違っています。';
            } else {
                $_SESSION['id'] = $user['user_id'];
                header('Location: home.php');
                exit;
            }
        } catch (PDOException $e) {
            $errors['db'] = 'エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }
}
?>

<?php require 'common/header.php'?>
<link rel="stylesheet" href="../CSS/login.css">
<div class="flexbox">
    <div class="content">
        <?php foreach ($errors as $error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endforeach; ?>
        <form action="" method="POST">
            <img src="../CSS/copuruLogo.jpg" alt="ロゴ" class="logo">
            <h1>coプル</h1>
            <p>あなたの出会いをサポートします</p>
            <h3>ログイン<h3>
            <p>メールアドレス<input type="email" name="email" required></p>
            <p>パスワード<input type="password" name="pass" required></p><br>
            <button type="submit" class="btn">ログイン</button>
        </form>
    </div>
    <p class="box">今すぐ出会いが欲しいですか？
        <a href="signup.php"><button class="btn">新規登録</button></a>
    </p>
</div>
<style>
    .sidebar {
        display: none;
    }
</style>
<?php require 'common/footer.php'?>
