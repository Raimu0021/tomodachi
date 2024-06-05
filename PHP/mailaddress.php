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
$current_email = '';

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    
    // 現在のメールアドレスを取得
<<<<<<< HEAD
<<<<<<< HEAD
    $sql = "SELECT email FROM users WHERE user_id = :id";
=======
    $sql = "SELECT email FROM users WHERE user_id = :id";  // ここで 'user_id' を使用しています
>>>>>>> 28637035f5adcfc8dc2e7667d9b10e9bd5f320c6
=======


    $sql = "SELECT email FROM users WHERE user_id = :id";  // ここで 'user_id' を使用しています

>>>>>>> dd50fc8520227432c6d2ee9e21c259eec44d6b7e
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $current_email = $result['email'];
    } else {
        $message = "ユーザーが見つかりません。";
    }

    $stmt->closeCursor();
} else {
    $message = "セッションが見つかりません。再度ログインしてください。";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = $_POST['new-email'];
    $confirm_email = $_POST['confirm-email'];
    
    if ($new_email === $confirm_email) {
        // 新しいメールアドレスを更新
<<<<<<< HEAD
<<<<<<< HEAD
        $sql = "UPDATE users SET email = :new_email WHERE user_id = :id";
=======
        $sql = "UPDATE users SET email = :new_email WHERE user_id = :id";  // ここで 'user_id' を使用しています
>>>>>>> 28637035f5adcfc8dc2e7667d9b10e9bd5f320c6
=======
        $sql = "UPDATE users SET email = :new_email WHERE user_id = :id";
>>>>>>> dd50fc8520227432c6d2ee9e21c259eec44d6b7e
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':new_email', $new_email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $message = "メールアドレスが更新されました。";
            $current_email = $new_email;
        } else {
            $message = "メールアドレスの更新に失敗しました。";
        }
        
        $stmt->closeCursor();
    } else {
        $message = "新しいメールアドレスが一致しません。";
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
    input[type="email"] {
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
    <h1>メールアドレス変更</h1>
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="" method="POST">
        <div class="form-group">
            <label for="current-email">現在のメールアドレス</label>
            <input type="text" id="current-email" name="current-email" value="<?php echo htmlspecialchars($current_email ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="new-email">新しいメールアドレス</label>
            <input type="email" id="new-email" name="new-email" required>
        </div>
        <div class="form-group">
            <label for="confirm-email">新しいメールアドレス確認</label>
            <input type="email" id="confirm-email" name="confirm-email" required>
        </div>
        <button type="submit">変更する</button>
    </form>
</div>
<?php require 'common/footer.php' ?>
