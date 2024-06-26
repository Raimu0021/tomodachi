<?php
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $chat_id = $_POST['chat_id'];
    $message = $_POST['message'];
    $sender_id = $_POST['sender_id'];

    if (!empty($message)) {
        $stmt = $conn->prepare('INSERT INTO messages (chat_id, sender_id, message, message_at) VALUES (?, ?, ?, NOW())');

        if ($stmt->execute([$chat_id, $sender_id, $message])) {
            // メッセージが正常に挿入された場合の処理
        } else {
            $error_message = "メッセージの送信に失敗しました";
        }
    } else {
        $error_message = "メッセージを入力してください";
    }
}

$chat_id = $_POST['chat_id'] ?? ''; // POSTが存在しない場合のデフォルト値

$messages = $conn->prepare('SELECT * FROM messages WHERE chat_id = ? ORDER BY message_at');
$messages->execute([$chat_id]);
?>
<script>
function fetchMessages() {
    var chatId = <?php echo json_encode($chat_id); ?>;
    fetch('fetch_messages.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'chat_id=' + chatId
    })
    .then(response => response.json())
    .then(data => {
        var messagesContainer = document.querySelector('.message_area');
        messagesContainer.innerHTML = ''; // 既存のメッセージをクリア

        data.forEach(function(message) {
            var messageWrapperDiv = document.createElement('div');
            messageWrapperDiv.className = message.sender_id == <?php echo $user_id; ?> ? 'send' : 'receive';

            var messageTextDiv = document.createElement('div');
            messageTextDiv.className = 'message_text';
            messageTextDiv.textContent = message.message;

            var messageAtDiv = document.createElement('div');
            messageAtDiv.className = 'message_at';
            messageAtDiv.textContent = `${message.hour}:${message.minute}`;


            messageWrapperDiv.appendChild(messageTextDiv);
            messageWrapperDiv.appendChild(messageAtDiv);
            messagesContainer.appendChild(messageWrapperDiv);
        });
    })
    .catch(error => console.error('Error:', error));
}

// HTMLが完全に読み込まれた後にメッセージを取得
document.addEventListener('DOMContentLoaded', fetchMessages);

// 1秒ごとにメッセージを更新
setInterval(fetchMessages, 1000);

</script>
<div class="chat_name">
    <p><?php echo $chat_name?></p>
    <form action="">
        <button type="submit" name="chat_id" class="close_chat">閉じる</button>
    </form>
</div>
<div class="message_area">
    <!-- JavaScriptによって動的にメッセージが挿入されます -->
</div>

<div class="input_area">
    <form action="" method="post">
        <input type="hidden" name="chat_id" value="<?php echo htmlspecialchars($chat_id, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="sender_id" value="<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="text" name="message" class="input_message">
        <button type="submit" name="send" class="send_message">送信</button>
        <p><?php echo $error_message;?></p>
    </form>
</div>

