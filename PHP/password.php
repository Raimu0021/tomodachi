<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// セッションを開始
session_start();

require 'common/header.php';
include 'common/db-connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($newPassword !== $confirmPassword) {
        $message = 'パスワードが一致していません';
    } else {
        // パスワードをハッシュ化
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // ユーザーIDの取得（セッションから取得）
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            // データベースの更新
            $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':id', $userId);

            if ($stmt->execute()) {
                // パスワード変更成功時にlogin.phpにリダイレクト
                header('Location: login.php');
                exit;
            } else {
                $message = 'パスワードの変更中にエラーが発生しました';
            }
        } else {
            $message = 'ユーザーIDが取得できませんでした。再度ログインしてください。';
        }
    }
}
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
        input[type="email"],
        input[type="password"] {
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
