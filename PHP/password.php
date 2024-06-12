<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'common/header.php';
include 'common/db-connect.php';

$message = '';
$current_password = '';

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    
    // 現在のパスワードを取得
    $sql = "SELECT password FROM users WHERE user_id = :id";  // ここで 'user_id' を使用しています

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
    $current_password_input = $_POST['current-password'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];
    
    if (password_verify($current_password_input, $current_password)) {
        if ($new_password === $confirm_password) {
            // 新しいパスワードをハッシュ化して更新
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = :new_password WHERE user_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':new_password', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $message = "パスワードが更新されました。";
            } else {
                $message = "パスワードの更新に失敗しました。";
            }
            
            $stmt->closeCursor();
        } else {
            $message = "新しいパスワードが一致しません。";
        }
    } else {
        $message = "現在のパスワードが正しくありません。";
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
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
        text-align: left;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
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
            <label for="current-password">現在のパスワード</label>
            <input type="password" id="current-password" name="current-password" required>
        </div>
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
