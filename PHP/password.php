<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'common/header.php';
include 'common/db-connect.php';

try {
    $conn = new PDO($connect, USER, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$message = '';
$current_password = '';

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    
    // 現在のパスワードを取得
    $sql = "SELECT password FROM users WHERE user_id = :id";  // ここで 'user_id' を使用
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $current_password = $result['password'];
    } else {
        $message = "ユーザーが見つかりません。";
    }

    $stmt->closeCursor();
} else {
    $message = "セッションが見つかりません。再度ログインしてください。";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];
    
    if ($new_password === $confirm_password) {
        // 新しいパスワードを更新
        $sql = "UPDATE users SET password = :new_password WHERE user_id = :id";  // ここで 'user_id' を使用
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':new_password', $new_password, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $message = "パスワードが更新されました。";
            $current_password = $new_password;
        } else {
            $message = "パスワードの更新に失敗しました。";
        }
        
        $stmt->closeCursor();
    } else {
        $message = "新しいパスワードが一致しません。";
include 'common/db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email']; // ユーザーのメールアドレスを取得する方法を確立する
    $password = 
    if ($new_password !== $confirm_password) {
        echo "新しいパスワードが一致しません";
    } else {
        try {
            $conn = new PDO($connect, USER, PASS);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($current_password, $row['password'])) {
                    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_sql = "UPDATE users SET password = :new_password WHERE email = :email";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bindParam(':new_password', $new_password_hashed);
                    $update_stmt->bindParam(':email', $email);
                    $update_stmt->execute();
                    echo "パスワードが変更されました";
                } else {
                    echo "現在のパスワードが正しくありません";
                }
            } else {
                echo "ユーザーが見つかりません";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $conn = null;
    }
}

$conn = null;
?>
<style>
    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background-color: #f8f8f8;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
        text-align: center;
    }

    h1 {
        margin-bottom: 20px;
        font-size: 24px;
    }

    .form-group {
        margin-bottom: 15px;
        text-align: left;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="email"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #45a049;
    }

    .message {
        margin-bottom: 15px;
        color: #f00;
    }
</style>

<div class="container">
    <h1>パスワード変更</h1>
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="" method="POST">
        <div class="form-group">
            <label for="new-password">新しいパスワード</label>
            <input type="password" id="new-password" name="new-password" required>
        </div>
        <div class="form-group">
            <label for="confirm-password">新しいパスワード確認</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
        </div>
        <button type="submit">変更する</button>
    </form>
</div>
<?php require 'common/footer.php' ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワード変更</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .logo {
            width: 100px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>パスワード変更</h2>
        <form action="password.php" method="post">
            <input type="hidden" name="email" value="user@example.com"> <!-- ユーザーのメールアドレスを動的に設定してください -->
            <label for="current_password">現在のパスワード:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">新しいパスワード:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">新しいパスワード確認:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">変更する</button>
        </form>
    </div>
</body>
</html>