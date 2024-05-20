<?php
include 'db-connect.php';

$user_id = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_email = $_POST['new-email'];
    $confirm_email = $_POST['confirm-email'];

    if ($new_email === $confirm_email) {
        $sql = "UPDATE users SET email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_email, $user_id);

        if ($stmt->execute()) {
            $message = "メールアドレスが正常に変更されました。";
        } else {
            $message = "メールアドレスの変更に失敗しました: " . $conn->error;
        }

        $stmt->close();
    } else {
        $message = "新しいメールアドレスが一致しません。";
    }
}
$sql = "SELECT email FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$current_email = "";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_email = $row['email'];
} else {
    $current_email = "メールアドレスが見つかりません。";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メールアドレス変更</title>
    <link rel="stylesheet" href="mailaddress.css">
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
</body>
</html>
