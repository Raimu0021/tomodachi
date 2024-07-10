<?php
session_start();
require './common/db-connect.php';

// ログインしているユーザーのIDを取得
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_password = $_POST['password'];

    try {
        // ユーザー情報の取得
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = :user_id"); // user_idに修正
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $stored_password = $result['password'];

            // パスワードの確認
            if (password_verify($input_password, $stored_password)) {
                // アカウントの削除
                $stmt = $conn->prepare("DELETE FROM users WHERE user_id = :user_id"); // user_idに修正
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                // セッションの終了
                session_destroy();
                header("Location:login-logout.php");
                exit();
            } else {
                $error = "パスワードが一致しません。";
            }
        } else {
            $error = "ユーザー情報の取得に失敗しました。";
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

require './common/header.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アカウント削除</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h1 {
            margin-bottom: 1.5rem;
            color: #333333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 0.5rem;
            color: #555555;
            text-align: left;
        }
        input[type="password"] {
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #dddddd;
            border-radius: 4px;
        }
        button {
            padding: 0.75rem;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>アカウント削除</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">削除</button>
        </form>
    </div>
</body>
</html>
