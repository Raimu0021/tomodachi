<?php
header('Content-Type: application/json'); // すべてのレスポンスをJSON形式で返す

include '../common/db-connect.php'; // データベース接続ファイルをインクルード

if (!$conn) {
    echo json_encode(['error' => 'データベース接続に失敗しました。']);
    exit;
}

$requestData = json_decode(file_get_contents('php://input'), true);
$sender_id = $requestData['sender_id'];
$receiver_id = $requestData['receiver_id'];

// 1. データベースに自分と相手のペアが存在するか確認
$stmt = $conn->prepare("SELECT * FROM dates WHERE (sender_id = :sender_id AND receiver_id = :receiver_id) OR (sender_id = :receiver_id AND receiver_id = :sender_id)");
$stmt->execute([':sender_id' => $sender_id, ':receiver_id' => $receiver_id]);
$result = $stmt->fetchAll();

if (count($result) == 0) {
    // ペアが存在しない場合、新規登録
    $insertStmt = $conn->prepare("INSERT INTO dates (sender_id, receiver_id, is_hidden) VALUES (:sender_id, :receiver_id, 0)");
    $insertStmt->execute([':sender_id' => $sender_id, ':receiver_id' => $receiver_id]);
} else {
    
    $row = $result[0];
    if ($row['is_hidden'] == 1) {
        // ペアが存在するがis_hiddenが1の場合、is_hiddenを0に更新
        $updateStmt = $conn->prepare("UPDATE dates SET is_hidden = 0 WHERE date_id = :date_id");
        $updateStmt->execute([':date_id' => $row['date_id']]);
    }else{
        //  is_hidden = 0の場合にそれぞれのユーザーのcurrently_datingを1に更新
        $updateDatingStmt = $conn->prepare("UPDATE users SET currently_dating = 1 WHERE user_id = :sender_id");
        $updateDatingStmt->execute([':sender_id' => $sender_id]);
        $updateDatingStmt = $conn->prepare("UPDATE users SET currently_dating = 1 WHERE user_id = :receiver_id");
        $updateDatingStmt->execute([':receiver_id' => $receiver_id]);
    }
}

// 状態
// ・ペアが存在しない or is_hidden = 1
// デート要請がない

// ・is_hidden = 0
// デート要請がある


// 処理
// ・デート要請がない場合
// is_hidden = 0にする

// ・デート要請がある場合
// それぞれのユーザーのcurrently_datingを1にする


echo json_encode(['message' => 'デートリクエストの処理が完了しました。']);
?>