<?php
session_start();
include 'common/db-connect.php';

$errors = []; // エラーメッセージを格納する配列

if (isset($_SESSION['noLogin'])) {
    $errors['noLogin'] = $_SESSION['noLogin'];
    SESSION_destroy();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_type'])) {
    $formType = $_POST['form_type'];
    $email = $_POST['email'];
    $password = $_POST['password'] ?? $_POST['pass']; // パスワードフィールドの名前が異なるため修正
    $user_name = $_POST['username'] ?? ''; // 新規登録の場合のユーザー名

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
        if ($formType == 'signin') {
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
                    $_SESSION['user_id'] = $user['user_id'];
                    $sql = "UPDATE users SET online_flg = 1 WHERE user_id = :user_id";
                    $online = $conn->prepare($sql);
                    $online->bindValue(':user_id', $user['user_id'], PDO::PARAM_STR);
                    $online->execute();
                    $_SESSION['loggedin'] = true;

                    header('Location: home.php');
                    exit;
                }
            } catch (PDOException $e) {
                $errors['db'] = 'エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            }
        } elseif ($formType == 'signup') {
            try {
                // メールアドレスの重複チェック
                $sql = "SELECT * FROM users WHERE email = :email";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':email', $email, PDO::PARAM_STR);
                $stmt->execute();
                $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existingUser) {
                    $errors['email'] = 'このメールアドレスは既に登録されています。';
                } else {
                    // パスワードをハッシュ化
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO users (user_name, email, password, profile_image) VALUES (:user_name, :email, :password, :profile_image)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(':user_name', $user_name, PDO::PARAM_STR);
                    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
                    $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
                    $stmt->bindValue(':profile_image', '../img/default-avatar.png', PDO::PARAM_STR);
                    $stmt->execute();

                    $_SESSION['user_id'] = $conn->lastInsertId();
                    $_SESSION['loggedin'] = true;
                    header('Location: home.php');
                    exit;
                }
            } catch (PDOException $e) {
                $errors['db'] = 'エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            }
        }
    }
}
//ログアウト処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
    $user_id = $_SESSION["user_id"];
    $stmt = $conn->prepare("UPDATE users SET online_flg=0 WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    SESSION_destroy();
    $errors['logout'] = 'ログアウトしました';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/login.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const signInBtn = document.getElementById("signIn");
            const signUpBtn = document.getElementById("signUp");
            const container = document.querySelector(".container");

            signInBtn.addEventListener("click", () => {
                container.classList.remove("right-panel-active");
            });

            signUpBtn.addEventListener("click", () => {
                container.classList.add("right-panel-active");
            });
        });
    </script>
    <title>ログイン</title>
</head>
<body>
<div class="container right-panel-active">
    <!-- Sign Up -->
    <div class="container__form container--signup">
        <form action="" method="POST" class="form" id="form1">
            <h2 class="form__title">新規登録</h2>
            <input type="text" placeholder="User" name="username" class="input" />
            <input type="email" placeholder="Email" name="email" class="input" />
            <input type="password" placeholder="Password" name="password" class="input" />
            <input type="hidden" name="form_type" value="signup">
            <button type="submit" class="btn">新規登録</button>
        </form>
    </div>

    <!-- Sign In -->
    <div class="container__form container--signin">
        <form action="" method="POST" class="form">
            <h2 class="form__title">サインイン</h2>
            <input type="email" placeholder="Email" name="email" class="input" />
            <input type="password" placeholder="Password" name="pass" class="input" />
            <input type="hidden" name="form_type" value="signin">
            <button type="submit" class="btn">サインイン</button>
        </form>
    </div>

    <!-- Overlay -->
    <div class="container__overlay">
        <div class="overlay">
            <div class="overlay__panel overlay--left">
                <button class="btn" id="signIn">サインイン</button>
            </div>
            <div class="overlay__panel overlay--right">
                <button class="btn" id="signUp">新規登録</button>
            </div>
        </div>
    </div>
</div>

<!-- エラーメッセージ表示 -->
<div class="flexbox">
    <div class="content">
        <?php foreach ($errors as $error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
