<?php
require 'db-connect.php';
require 'common/header.php';

// ユーザーIDはセッションやクッキーから取得することを想定（例：1）
$user_id = 1;

// データベースから通知を取得
$stmt = $pdo->prepare("SELECT notifications.*, users.name, users.icon 
                       FROM notifications 
                       JOIN users ON notifications.sender_id = users.id 
                       WHERE notifications.user_id = :user_id AND notifications.seen = FALSE 
                       ORDER BY notifications.timestamp DESC");
$stmt->execute(['user_id' => $user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h1>通知</h1>
    <?php if (count($notifications) > 0): ?>
        <?php foreach ($notifications as $notification): ?>
            <div class="notification">
                <img src="<?= htmlspecialchars($notification['icon']) ?>" alt="<?= htmlspecialchars($notification['name']) ?>のアイコン">
                <p><strong><?= htmlspecialchars($notification['name']) ?></strong>から新しいチャットがあります</p>
                <small><?= htmlspecialchars($notification['timestamp']) ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>新しい通知はありません。</p>
    <?php endif; ?>
</div>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    .container {
        padding: 20px;
    }

    .notification {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .notification img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .notification p {
        margin: 0;
    }

    .notification small {
        color: #666;
    }
</style>
<?php require 'common/footer.php'; ?>
