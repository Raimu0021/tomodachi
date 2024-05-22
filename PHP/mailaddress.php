<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'common/header.php';
include 'common/db-connect.php';

try {
    $conn = new PDO($connect, USER, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$user_id = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_email = $_POST['new-email'];
    $confirm_email = $_POST['confirm-email'];

    if ($new_email === $confirm_email) {
        try {
            $sql = "UPDATE users SET email = :email WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $new_email);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $message = "メールアドレスが正常に変更されました。";
            } else {
                $message = "メールアドレスの変更に失敗しました。";
            }
        } catch (PDOException $e) {
            $message = "メールアドレスの変更に失敗しました: " . $e->getMessage();
        }
    } else {
        $message = "新しいメールアドレスが一致しません。";
    }
}

try {
    $sql = "SELECT email FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_email = $result ? $result['email'] : "メールアドレスが見つかりません。";
} catch (PDOException $e) {
    $current_email = "エラーが発生しました: " . $e->getMessage();
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メールアドレス変更</title>
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
</head>
<body>

    <div class="container">
        <h1>メールアドレス変更</h1>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="current-email">現在のメールアドレス</label>
                <input type="text" id="current-email" name="current-email" value="<?php echo htmlspecialchars($current_email); ?>" readonly>
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
</body>
</html>

