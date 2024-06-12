<?php
require 'common/db-connect.php';

$chat_id = $_POST['chat_id'];

$messages = $conn->prepare('SELECT * FROM messages WHERE chat_id = ? ORDER BY message_at');
$messages->execute([$chat_id]);

$result = $messages->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($result);
?>
