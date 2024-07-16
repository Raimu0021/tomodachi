<?php
// データベース接続のコードを含む
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $liked_user_id = $_POST['liked_user_id'];
    $liking_user_id = $_SESSION['user_id']; // セッションから現在のユーザーIDを取得する

    try {
        // 現在のいいね状態を確認する
        $stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE liked_user_id = :liked_user_id AND liking_user_id = :liking_user_id");
        $stmt->bindParam(':liked_user_id', $liked_user_id);
        $stmt->bindParam(':liking_user_id', $liking_user_id);
        $stmt->execute();
        $isLiked = $stmt->fetchColumn() > 0;

        if ($isLiked) {
            // 既にいいねしている場合は、いいねを削除
            $stmt = $conn->prepare("DELETE FROM likes WHERE liked_user_id = :liked_user_id AND liking_user_id = :liking_user_id");
            $stmt->bindParam(':liked_user_id', $liked_user_id);
            $stmt->bindParam(':liking_user_id', $liking_user_id);
            $stmt->execute();
            echo json_encode(['success' => true, 'liked' => false]);
        } else {
            // まだいいねしていない場合は、いいねを追加
            $stmt = $conn->prepare("INSERT INTO likes (liked_user_id, liking_user_id) VALUES (:liked_user_id, :liking_user_id)");
            $stmt->bindParam(':liked_user_id', $liked_user_id);
            $stmt->bindParam(':liking_user_id', $liking_user_id);
            $stmt->execute();
            echo json_encode(['success' => true, 'liked' => true]);
        }
    } catch (PDOException $e) {
        error_log("データベースクエリエラー: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>