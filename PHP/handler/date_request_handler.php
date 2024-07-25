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
    $insertStmt = $conn->prepare("INSERT INTO dates (sender_id, receiver_id, is_hidden, is_pending) VALUES (:sender_id, :receiver_id, 0, 1)");
    $insertStmt->execute([':sender_id' => $sender_id, ':receiver_id' => $receiver_id]);
} else {
    
    $row = $result[0];
    if ($row['is_hidden'] == 1) {
        // ペアが存在するがis_hiddenが1の場合、is_hiddenを0に更新、is_pending = 1に設定する
        $updateDatingStmt = $conn->prepare("UPDATE dates SET is_hidden = 0, is_pending = 1 WHERE date_id = :date_id");
        $updateDatingStmt->execute([':date_id' => $row['date_id']]);
    }else if($row['is_pending'] == 1){
        //  is_pending = 1の場合にそれぞれのユーザーのcurrently_datingを1に更新
        $updateDatingStmt = $conn->prepare("UPDATE users SET currently_dating = 1 WHERE user_id = :sender_id");
        $updateDatingStmt->execute([':sender_id' => $sender_id]);
        $updateDatingStmt = $conn->prepare("UPDATE users SET currently_dating = 1 WHERE user_id = :receiver_id");
        $updateDatingStmt->execute([':receiver_id' => $receiver_id]);
    }else{
        //　どれにも当てはまらない場合、is_pending = 1に設定する
        $updateDatingStmt = $conn->prepare("UPDATE dates SET is_pending = 1 WHERE date_id = :date_id");
        $updateDatingStmt->execute([':date_id' => $row['date_id']]);
    }
}


echo json_encode(['message' => 'デートリクエストの処理が完了しました。']);
?>