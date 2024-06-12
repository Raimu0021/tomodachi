<?php
require 'common/header.php';
require 'common/db-connect.php';

$user_id = 1;
$pdo = new PDO($connect, USER, PASS);
$eror_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $chat_id = $_POST['chat_id'];
    $message = $_POST['message'];
    $sender_id = $_POST['sender_id'];
    

    if (!empty($message)) {
        $stmt = $pdo->prepare('INSERT INTO messages (chat_id, sender_id, message, message_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$chat_id, $sender_id, $message]);
    }else{
        $eror_message = "メッセージを入力してください";
    }
}

$chat_id = $_POST['chat_id'];
$messages = $pdo->prepare('SELECT * FROM messages WHERE chat_id = ? ORDER BY message_at');
$messages->execute([$chat_id]);
?>

<link rel="stylesheet" href="../CSS/message.css">

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
        var messagesContainer = document.querySelector('.messages');
        messagesContainer.innerHTML = ''; // 既存のメッセージをクリア
        data.forEach(function(message) {
            var messageDiv = document.createElement('div');
            messageDiv.className = 'message_text';
            messageDiv.id = message.sender_id == <?php echo $user_id; ?> ? 'send' : 'receive';
            messageDiv.textContent = message.message;
            messagesContainer.appendChild(messageDiv);
        });
    })
    .catch(error => console.error('Error:', error));
}

// HTMLが完全に読み込まれた後にメッセージを取得
document.addEventListener('DOMContentLoaded', fetchMessages);

// 1秒ごとにメッセージを更新
setInterval(fetchMessages, 1000);
</script>


<div class="contents">
    <div class="messages">
        <!-- JavaScriptによって動的にメッセージが挿入されます -->
    </div>
    
    <form action="" method="post">
        <input type="hidden" name="chat_id" value="<?php echo htmlspecialchars($chat_id, ENT_QUOTES, 'UTF-8'); ?>">
        <select name="sender_id">
            <?php $participant = $pdo->prepare('SELECT * FROM participants WHERE chat_id=?');
            $participant->execute([$chat_id]);
            foreach ($participant as $sender):?>
            <option value="<?php echo $sender['user_id'];?>"><?php echo $sender['user_id'];?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="message">
        <button type="submit" name="send">送信</button>
        <p><?php echo $eror_message;?></p>
    </form>

    <a href="chat_list.php">もどる</a>
</div>



<?php require 'common/footer.php';?>