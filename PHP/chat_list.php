<?php 
require 'common/header.php';
require 'common/db-connect.php';

$user_id = 1;

$chat_ids = $conn->prepare('SELECT * FROM participants WHERE user_id=?');
$chat_ids->execute([$user_id]);

?>

<div class="contents">
    <?php
    foreach($chat_ids as $id_row){
        $chat_id = $id_row['chat_id'];

        $join_chat = $conn->prepare('SELECT * FROM chats WHERE chat_id=?');
        $join_chat->execute([$chat_id]);
        $chat = $join_chat->fetch(PDO::FETCH_ASSOC);
        
        $last_message_query = $conn->prepare('SELECT * FROM messages WHERE chat_id=? ORDER BY message_id DESC LIMIT 1');
        $last_message_query->execute([$chat_id]);
        $last_message = $last_message_query->fetch(PDO::FETCH_ASSOC);

        if(!$last_message){
            $last_message_text = "チャットしてみよう！";
        } else {
            $last_message_text = $last_message['message'];
        }
    ?>
        <form action="message.php" method="post">
            <input type="hidden" name="chat_id" value="<?php echo htmlspecialchars($chat['chat_id'], ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit">
                <p><?php echo htmlspecialchars($chat['chat_id'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><?php echo htmlspecialchars($last_message_text, ENT_QUOTES, 'UTF-8'); ?></p>
            </button>
        </form>
    <?php
    }
    ?>
</div>

<?php require 'common/footer.php'; ?>