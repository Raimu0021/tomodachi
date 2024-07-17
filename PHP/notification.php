<div class="noti_title">
    通知一覧
</div>
<?php
// データベース接続オブジェクトが既に作成されている前提
$notification_sql = $conn->prepare('SELECT *,    
    YEAR(notification_at) AS year, 
    MONTH(notification_at) AS month, 
    DAY(notification_at) AS day, 
    HOUR(notification_at) AS hour, 
    MINUTE(notification_at) AS minute, 
    SECOND(notification_at) AS second 
    FROM notification WHERE user_id=? AND is_read = 0 ORDER BY notification_at DESC');
    
$notification_sql->execute([$user_id]);
$notifications = $notification_sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($notifications as $no) {
    $user = $conn->prepare('SELECT * FROM users WHERE user_id = ?');
    $user->execute([$no['sender_id']]);
    $sender = $user->fetch(PDO::FETCH_ASSOC);
    $sender_name = htmlspecialchars($sender['user_name']);

    $notification_content = htmlspecialchars($no['content']);
    $notification_date = htmlspecialchars($no['month']) . "月" . htmlspecialchars($no['day']) . "日";

    if ($no['type'] == 'message') {
?>
        <p class="noti_text"><a href="chat.php"><?php echo $sender_name . ' ' . $notification_content . ' ' . $notification_date; ?></a></p>
<?php
    } else if ($no['type'] == 'like') {
?>
        <p class="noti_text"><?php echo $sender_name . ' ' . $notification_content . ' ' . $notification_date; ?></p>
<?php
    } else {
?>
        <p class="noti_text"><?php echo $sender_name . ' ' . $notification_content . ' ' . $notification_date; ?></p>
<?php
    }
}
?>

<style>
    .noti_title {
        width: 100%;
        height: 5vh;
        border: 1px #8e8e8e solid;
    }
    .noti_text {
        width: 100%;
        height: 7vh;
    }
    .noti_text a {
        text-decoration: none;
    }
</style>
