<?php
require 'common/db-connect.php';

$pdo = new PDO($connect, USER, PASS);
$chat_id = $_POST['chat_id'];

$messages = $pdo->prepare('SELECT * FROM messages WHERE chat_id = ? ORDER BY message_at');
$messages->execute([$chat_id]);

$result = $messages->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($result);
?>
