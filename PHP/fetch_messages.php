<?php
require 'common/db-connect.php';

$chat_id = $_POST['chat_id'];

$messages = $conn->prepare(
    'SELECT *, 
    YEAR(message_at) AS year, 
    MONTH(message_at) AS month, 
    DAY(message_at) AS day, 
    HOUR(message_at) AS hour, 
    MINUTE(message_at) AS minute, 
    SECOND(message_at) AS second 
    FROM messages 
    WHERE chat_id = ? 
    ORDER BY message_at'
);
$messages->execute([$chat_id]);

$result = $messages->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($result);
?>
