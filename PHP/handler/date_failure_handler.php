<?php
//
header('Content-Type: application/json'); // すべてのレスポンスをJSON形式で返す

include '../common/db-connect.php'; // データベース接続ファイルをインクルード

if (!$conn) {
    echo json_encode(['error' => 'データベース接続に失敗しました。']);
    exit;
}

//各ユーザーid、デートid取得処理
$requestData = json_decode(file_get_contents('php://input'), true);
$sender_id = $requestData['sender_id'];
$receiver_id = $requestData['receiver_id'];

$getDateIdStmt = $conn->prepare("SELECT date_id FROM dates WHERE (sender_id = :sender_id AND receiver_id = :receiver_id) OR (sender_id = :receiver_id AND receiver_id = :sender_id)");
$getDateIdStmt->execute([':sender_id' => $sender_id, ':receiver_id' => $receiver_id]);
$date = $getDateIdStmt->fetch(PDO::FETCH_ASSOC);
$date_id = $date['date_id'];
//ここまで


//それぞれのusersのcurrently_datingを0、datesのis_datingを0に変更、date_failure.phpに飛ばす
$updateDatingStmt = $conn->prepare("UPDATE users SET currently_dating = 0 WHERE user_id = :sender_id");
$updateDatingStmt->execute([':sender_id' => $sender_id]);
$updateDatingStmt = $conn->prepare("UPDATE users SET currently_dating = 0 WHERE user_id = :receiver_id");
$updateDatingStmt->execute([':receiver_id' => $receiver_id]);
$updateDatingStmt = $conn->prepare("UPDATE dates SET is_dating = 0 WHERE date_id = :date_id");
$updateDatingStmt->execute([':date_id' => $date_id]);

header("Location: date_failure.php");
exit();
?>