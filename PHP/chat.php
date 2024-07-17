<?php 
session_start();

require 'common/header.php';
require 'common/db-connect.php';

$user_id = $_SESSION['user_id'];

$chat_ids = $conn->prepare('SELECT * FROM participants WHERE user_id=?');
if (!$chat_ids->execute([$user_id])) {
    $errorInfo = $chat_ids->errorInfo();
    echo "Error in chat_ids query: " . $errorInfo[2];
    exit;
}

?>
<link rel="stylesheet" href="../CSS/chat.css">

<div class="content">
    <div class="chat_list">
        <p class="title">チャット一覧</p>
        
        <?php
        foreach ($chat_ids as $row) {
            $chat_id = $row['chat_id'];

            $join_chat = $conn->prepare('SELECT * FROM chats WHERE chat_id=?');
            if (!$join_chat->execute([$chat_id])) {
                $errorInfo = $join_chat->errorInfo();
                echo "Error in join_chat query: " . $errorInfo[2];
            }
            $chat = $join_chat->fetch(PDO::FETCH_ASSOC);

            if (!$chat) {
                echo "Chat not found for chat_id: $chat_id";
            }

            $last_message_query = $conn->prepare('SELECT *,
                                                 YEAR(message_at) AS year,
                                                 MONTH(message_at) AS month, 
                                                 DAY(message_at) AS day, 
                                                 HOUR(message_at) AS hour, 
                                                 MINUTE(message_at) AS minute, 
                                                 SECOND(message_at) AS second 
                                                 FROM messages WHERE chat_id=? 
                                                 ORDER BY message_id DESC LIMIT 1');

            if (!$last_message_query->execute([$chat_id])) {
                $errorInfo = $last_message_query->errorInfo();
                echo "Error in last_message_query query: " . $errorInfo[2];
            }

            $last_message = $last_message_query->fetch(PDO::FETCH_ASSOC);

            if (!$last_message) {
                $last_message_text = "チャットしてみよう！";
            } else {
                $last_message_text = $last_message['message'];
            }

            if (!$chat['chat_name']) {
                $participant_query = $conn->prepare('SELECT * FROM participants WHERE chat_id=? AND user_id != ? ORDER BY joined_at DESC');
                if (!$participant_query->execute([$chat_id, $user_id])) {
                    $errorInfo = $participant_query->errorInfo();
                    echo "Error in participant_query query: " . $errorInfo[2];
                }
                $participant = $participant_query->fetch(PDO::FETCH_ASSOC);

                if (!$participant) {
                    echo "Participant not found for chat_id: $chat_id";
                }

                $user_sql = $conn->prepare('SELECT * FROM users WHERE user_id=?');
                if (!$user_sql->execute([$participant['user_id']])) {
                    $errorInfo = $user_sql->errorInfo();
                    echo "Error in user_sql query: " . $errorInfo[2];
                                    }
                $partner_name = $user_sql->fetch(PDO::FETCH_ASSOC);

                if (!$partner_name) {
                    echo "Partner not found for user_id: " . $participant['user_id'];
                }

                $chat_name = $partner_name['user_name'];
            } else {
                $chat_name = $chat['chat_name'];
            }
        ?>
        <form action="" method="post">
            <input type="hidden" name="chat_id" value="<?php echo htmlspecialchars($chat['chat_id'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="chat_name" value="<?php echo htmlspecialchars($chat_name, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="participant" value="<?php echo htmlspecialchars($participant['user_id'], ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" class="chat">
                <p class="name"><?php echo htmlspecialchars($chat_name, ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="last_message_date">
                    <?php 
                    if ($last_message) {
                        echo htmlspecialchars($last_message['month'] . '/' . $last_message['day'], ENT_QUOTES, 'UTF-8');
                    }
                    ?>
                </p>
                <p class="last_message"><?php echo htmlspecialchars($last_message_text, ENT_QUOTES, 'UTF-8'); ?></p>
            </button>
        </form>
        <?php
        }
        ?>
    </div>
    <div class="chat_area">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['chat_id'])) {
            $chat_id = $_POST['chat_id'];
            $chat_name = $_POST['chat_name'];
            require 'message.php';
        } else {
            echo '<div class="noneId"><p>チャットを選択してください</P></div>';
        }
        ?>
    </div>
</div>
<?php require 'common/footer.php'; ?>
