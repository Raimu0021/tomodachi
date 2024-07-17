<?php
$error_message = "メッセージを入力";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $chat_id = $_POST['chat_id'];
    $message = $_POST['message'];
    $sender_id = $_POST['sender_id'];

    if (!empty($message)) {
        $stmt = $conn->prepare('INSERT INTO messages (chat_id, sender_id, message, message_at) VALUES (?, ?, ?, NOW())');

        if ($stmt->execute([$chat_id, $sender_id, $message])) {
            $participant_query = $conn->prepare('SELECT * FROM participants WHERE chat_id=? AND user_id != ?');
            $participant_query->execute([$chat_id, $sender_id]);
            foreach($participant_query as $participant){
                $sql = "SELECT * FROM users WHERE user_id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':id', $participant['user_id'], PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if($user['online_flg'] == 0){
                    $in_noti = $conn->prepare('INSERT INTO notification (user_id, sender_id, type, content, notification_at) VALUES (?,?,?,?, NOW())');
                    $in_noti->execute([$participant['user_id'], $sender_id, "message", "新しいメッセージがあります"]);   
                }
            }
        } else {
            $error_message = "*メッセージの送信に失敗しました*";
        }
    } else {
        $error_message = "*メッセージを入力してください*";
    }
}

$chat_id = $_POST['chat_id'] ?? ''; // POSTが存在しない場合のデフォルト値
$chat_name = $_POST['chat_name'];

$messages = $conn->prepare('SELECT * FROM messages WHERE chat_id = ? ORDER BY message_at');
$messages->execute([$chat_id]);
?>
<script>
function fetchMessages(scrollToBottom = false) {
    const chatId = <?php echo json_encode($chat_id); ?>;
    fetch('fetch_messages.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'chat_id=' + encodeURIComponent(chatId)
    })
    .then(response => response.json())
    .then(data => {
        const messagesContainer = document.querySelector('.message_area');
        const shouldScroll = messagesContainer.scrollTop + messagesContainer.clientHeight === messagesContainer.scrollHeight;
        
        messagesContainer.innerHTML = ''; // 既存のメッセージをクリア

        const today = new Date();
        let year = today.getFullYear();
        let month = today.getMonth() + 1;
        let day = today.getDate();
        
        let lastMessageDate = null; // 前のメッセージの日付を保持する変数
        
        data.forEach(function(message) {
            let dateFlag = false;
            const messageYear = message.year;
            const messageMonth = message.month;
            const messageDay = message.day;
            let dateText = "";

            if (month - messageMonth == 0) {
                const dayDifference = day - messageDay;
                switch (dayDifference) {
                    case 0:
                        dateText = "今日";
                        dateFlag = true;
                        break;
                    case 1:
                        dateText = "1日前";
                        dateFlag = true;
                        break;
                    case 2:
                        dateText = "2日前";
                        dateFlag = true;
                        break;
                    default:
                        dateText = messageMonth + "月" + messageDay + "日";
                }
            }

            const messageDate = new Date(messageYear, messageMonth - 1, messageDay);
            if (lastMessageDate === null || messageDate.toDateString() !== lastMessageDate.toDateString()) {
                const dateDiv = document.createElement('div');
                dateDiv.className = 'message_date';
                dateDiv.textContent = dateText;
                messagesContainer.appendChild(dateDiv);
                lastMessageDate = messageDate;
            }

            const messageWrapperDiv = document.createElement('div');
            messageWrapperDiv.className = message.sender_id == <?php echo $user_id; ?> ? 'send' : 'receive';

            const messageTextDiv = document.createElement('div');
            messageTextDiv.className = 'message_text';
            messageTextDiv.textContent = message.message;

            const messageAtDiv = document.createElement('div');
            messageAtDiv.className = 'message_at';
            messageAtDiv.textContent = `${message.hour}:${message.minute}`;

            messagesContainer.appendChild(messageWrapperDiv);

            if (messageWrapperDiv.classList.contains("send")) {
                messageWrapperDiv.appendChild(messageAtDiv);
                messageWrapperDiv.appendChild(messageTextDiv);
            } else {
                messageWrapperDiv.appendChild(messageTextDiv);
                messageWrapperDiv.appendChild(messageAtDiv);
            }
        });

        if (scrollToBottom || shouldScroll) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    })
    .catch(error => console.error('Error:', error));
}

// HTMLが完全に読み込まれた後にメッセージを取得
document.addEventListener('DOMContentLoaded', () => {
    fetchMessages(true); // ページ読み込み時にスクロールを一番下に
});

// setInterval(() => fetchMessages(false), 1000);
</script>

<div class="chat_name">
    <p><?php echo htmlspecialchars($chat_name, ENT_QUOTES, 'UTF-8'); ?></p>
    <form action="">
        <button type="submit" name="chat_id" class="close_chat">閉じる</button>
    </form>
</div>
<div class="message_area">
    <!-- JavaScriptによって動的にメッセージが挿入されます -->
</div>
<form action="" method="post">
    <input type="hidden" name="chat_id" value="<?php echo htmlspecialchars($chat_id, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="sender_id" value="<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="chat_name" value="<?php echo htmlspecialchars($chat_name, ENT_QUOTES, 'UTF-8'); ?>">

    <div class="input-group input_area">
        <input type="text" class="form-control input_message" name="message" maxlength=4000 placeholder="<?php echo $error_message;?>" aria-label="<?php echo $error_message;?>" aria-describedby="button-addon2">
        <button class="btn btn-outline-secondary send_message" name="send" type="submit" id="button-addon2">送信</button>
    </div>
</form>
