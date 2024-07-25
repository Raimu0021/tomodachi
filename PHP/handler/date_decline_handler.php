<?php
// 相手からデート申請が来ている場合のみ呼び出されるため、datesにテーブルが存在している

header('Content-Type: application/json'); // すべてのレスポンスをJSON形式で返す

include '../common/db-connect.php'; // データベース接続ファイルをインクルード

if (!$conn) {
    echo json_encode(['error' => 'データベース接続に失敗しました。']);
    exit;
}

$requestData = json_decode(file_get_contents('php://input'), true);
$sender_id = $requestData['sender_id'];
$receiver_id = $requestData['receiver_id'];

// datesのこのペアのis_pendingを0にする
$stmt = $conn->prepare("SELECT * FROM dates WHERE (sender_id = :sender_id AND receiver_id = :receiver_id) OR (sender_id = :receiver_id AND receiver_id = :sender_id)");
$stmt->execute([':sender_id' => $sender_id, ':receiver_id' => $receiver_id]);
$result = $stmt->fetchAll();

$row = $result[0];
$updateStmt = $conn->prepare("UPDATE dates SET is_pending = 0 WHERE date_id = :date_id");
$updateStmt->execute([':date_id' => $row['date_id']]);

echo json_encode(['message' => 'デートリクエストの処理が完了しました。']);
?>