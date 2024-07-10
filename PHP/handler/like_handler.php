<?php
require 'db-connect.php';

session_start();
$user_id = $_SESSION['user_id']; // ログイン中のユーザーID
$liked_user_id = $_POST['liked_user_id']; // いいねされたユーザーのID

// いいねの存在チェック
$stmt = $pdo->prepare("SELECT * FROM likes WHERE user_id = :user_id AND liked_user_id = :liked_user_id");
$stmt->execute(['user_id' => $user_id, 'liked_user_id' => $liked_user_id]);
$like = $stmt->fetch();

if ($like) {
    // いいねが存在する場合は削除
    $stmt = $pdo->prepare("DELETE FROM likes WHERE like_id = :like_id");
    $stmt->execute(['like_id' => $like['like_id']]);
} else {
    // いいねが存在しない場合は追加
    $stmt = $pdo->prepare("INSERT INTO likes (user_id, liked_user_id, liked_at) VALUES (:user_id, :liked_user_id, NOW())");
    $stmt->execute(['user_id' => $user_id, 'liked_user_id' => $liked_user_id]);
}

echo json_encode(['success' => true]);
?>