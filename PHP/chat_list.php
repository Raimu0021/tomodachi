<?php 
session_start();

require 'common/header.php';
require 'common/db-connect.php';


$user_id = $_SESSION['user_id'];


$chat_ids = $conn->prepare('SELECT * FROM participants WHERE user_id=?');
$chat_ids->execute([$user_id]);

?>
<link rel="stylesheet" href="../CSS/chat.css">

<div class="content">
    <div class="chat_list">
        <form action="" method="post" >
        <?php
        foreach($chat_ids as $id_row){
            $chat_id = $id_row['chat_id'];

            $join_chat = $conn->prepare('SELECT * FROM chats WHERE chat_id=?');
            $join_chat->execute([$chat_id]);
            $chat = $join_chat->fetch(PDO::FETCH_ASSOC);

            $last_message_query = $conn->prepare('SELECT *, YEAR(message_at) AS year, MONTH(message_at) AS month, DAY(message_at) AS day, HOUR(message_at) AS hour, MINUTE(message_at) AS minute, SECOND(message_at) AS second FROM messages WHERE chat_id=? ORDER BY message_id DESC LIMIT 1');
            $last_message_query->execute([$chat_id]);
            $last_message = $last_message_query->fetch(PDO::FETCH_ASSOC);

            if(!$chat['chat_name']){
                $participant_query = $conn->prepare
                ('SELECT *     
                FROM participants 
                WHERE chat_id=? AND user_id != ? 
                ORDER BY joined_at DESC');
                $participant_query->execute([$chat_id,$user_id]);
                $participant = $participant_query->fetch(PDO::FETCH_ASSOC);

                $user_sql = $conn->prepare('SELECT * FROM users WHERE user_id=?');
                $user_sql->execute([$participant['user_id']]);
                $partner_name = $user_sql->fetch(PDO::FETCH_ASSOC);

                $chat_name = $partner_name['user_name'];
            }else {
                $chat_name = $chat['chat_name'];
            }

            if(!$last_message){
                $last_message_text = "チャットしてみよう！";
            } else {
                $last_message_text = $last_message['message'];
                $last_message_time = $last_message['message_at'];
            }
        ?>
        <input type="hidden" name="chat_id" value="<?php echo htmlspecialchars($chat['chat_id'], ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" class="chat" >
            <p><?php echo htmlspecialchars($chat_name, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><?php echo htmlspecialchars($last_message_text, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php 
            if (isset($last_message_text)){
                echo '<p>',$last_message['month'],'/',$last_message['day'],'</p>';   
            }
            ?>
        </button>     
        <div class="under_line"></div>   
        <?php
        }
        ?>
        </form>
    </div>
    <div class="chat_area">
        <?php
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['chat_id'])) {
            $chat_id = $_POST['chat_id'];
            require 'message.php';
        } else {
            echo "<p>チャットを選択してください</P>";
        }
        ?>
    </div>
</div>

<?php require 'common/footer.php'; ?>