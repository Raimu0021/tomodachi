<?php
include 'db-connect.php'; // データベース接続ファイルをインクルード

$requestData = json_decode(file_get_contents('php://input'), true);
$userId = $requestData['userId'];
$partnerId = $requestData['partnerId'];

// 1. データベースに自分と相手のペアが存在するか確認
$stmt = $conn->prepare("SELECT * FROM dates WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)");
$stmt->bind_param("iiii", $userId, $partnerId, $partnerId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // ペアが存在しない場合、新規登録
    $insertStmt = $conn->prepare("INSERT INTO dates (sender_id, receiver_id, is_hidden) VALUES (?, ?, 0)");
    $insertStmt->bind_param("ii", $userId, $partnerId);
    $insertStmt->execute();
} else {
    // ペアが存在するがis_hiddenが1の場合、is_hiddenを0に更新
    $row = $result->fetch_assoc();
    if ($row['is_hidden'] == 1) {
        $updateStmt = $conn->prepare("UPDATE dates SET is_hidden = 0 WHERE date_id = ?");
        $updateStmt->bind_param("i", $row['date_id']);
        $updateStmt->execute();
    }
}

// 2. receiver_idに自分のIDがある場合、currently_datingを1に更新
$updateDatingStmt = $conn->prepare("UPDATE users SET currently_dating = 1 WHERE user_id = ?");
$updateDatingStmt->bind_param("i", $userId);
$updateDatingStmt->execute();

echo json_encode(['message' => 'デートリクエストの処理が完了しました。']);
?>