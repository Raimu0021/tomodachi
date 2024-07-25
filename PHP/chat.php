<?php 
require 'common/header.php';

// チャット一覧を取得
$chats = $conn->prepare('SELECT * FROM participants WHERE user_id=?');
if (!$chats->execute([$user_id])) {
    $errorInfo = $chats->errorInfo();
    echo "Error in chats query: " . $errorInfo[2];
    exit;
}
?>
<link rel="stylesheet" href="../CSS/chat.css">

<div class="content">
    <div class="chat_list">
        <p class="title">チャット一覧</p>

        <?php
        // チャット一覧を表示
        foreach ($chats as $row) {
            $chatId = $row['chat_id'];

            // チャット情報を取得
            $joinChatQuery = $conn->prepare('SELECT * FROM chats WHERE chat_id=?');
            if (!$joinChatQuery->execute([$chatId])) {
                $errorInfo = $joinChatQuery->errorInfo();
                echo "Error in join_chat query: " . $errorInfo[2];
                continue;
            }
            $chat = $joinChatQuery->fetch(PDO::FETCH_ASSOC);

            if (!$chat) {
                echo "Chat not found for chat_id: $chatId";
                continue;
            }

            // 最後のメッセージを取得
            $lastMessageQuery = $conn->prepare('SELECT *, 
                                                YEAR(message_at) AS year, 
                                                MONTH(message_at) AS month, 
                                                DAY(message_at) AS day, 
                                                HOUR(message_at) AS hour, 
                                                MINUTE(message_at) AS minute, 
                                                SECOND(message_at) AS second 
                                                FROM messages WHERE chat_id=? 
                                                ORDER BY message_id DESC LIMIT 1');
            if (!$lastMessageQuery->execute([$chatId])) {
                $errorInfo = $lastMessageQuery->errorInfo();
                echo "Error in last_message_query query: " . $errorInfo[2];
                continue;
            }

            $lastMessage = $lastMessageQuery->fetch(PDO::FETCH_ASSOC);
            $lastMessageText = $lastMessage ? $lastMessage['message'] : "チャットしてみよう！";

            // チャット名を取得
            if (!$chat['chat_name']) {
                $participantQuery = $conn->prepare('SELECT * FROM participants WHERE chat_id=? AND user_id != ? ORDER BY joined_at DESC');
                if (!$participantQuery->execute([$chatId, $user_id])) {
                    $errorInfo = $participantQuery->errorInfo();
                    echo "Error in participant_query query: " . $errorInfo[2];
                    continue;
                }
                $participant = $participantQuery->fetch(PDO::FETCH_ASSOC);

                if (!$participant) {
                    echo "Participant not found for chat_id: $chatId";
                    continue;
                }

                $userQuery = $conn->prepare('SELECT * FROM users WHERE user_id=?');
                if (!$userQuery->execute([$participant['user_id']])) {
                    $errorInfo = $userQuery->errorInfo();
                    echo "Error in user_query query: " . $errorInfo[2];
                    continue;
                }
                $partner = $userQuery->fetch(PDO::FETCH_ASSOC);

                if (!$partner) {
                    echo "Partner not found for user_id: " . $participant['user_id'];
                    continue;
                }

                $chatName = $partner['user_name'];
            } else {
                $chatName = $chat['chat_name'];
            }
        ?>
        <form action="" method="post">
            <input type="hidden" name="chat_id" value="<?php echo htmlspecialchars($chat['chat_id'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="chat_name" value="<?php echo htmlspecialchars($chatName, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="partner" value="<?php echo htmlspecialchars($participant['user_id'], ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" class="chat">
                <p class="name"><?php echo htmlspecialchars($chatName, ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="last_message_date">
                    <?php 
                    if ($lastMessage) {
                        echo htmlspecialchars($lastMessage['month'] . '/' . $lastMessage['day'], ENT_QUOTES, 'UTF-8');
                    }
                    ?>
                </p>
                <p class="last_message"><?php echo htmlspecialchars($lastMessageText, ENT_QUOTES, 'UTF-8'); ?></p>
            </button>
        </form>
        <?php
        }
        ?>
    </div>
    <div class="chat_area">
        <?php
                // チャットを開く
                if (isset($_POST['open_chat'])) {
                    $chatExists = false;
        
                    // パートナーのチャット一覧を取得
                    $partnerChatsQuery = $conn->prepare('SELECT chat_id FROM participants WHERE user_id = ? ORDER BY joined_at DESC');
                    $partnerChatsQuery->execute([$_POST['partner_id']]);
                    $partnerChats = $partnerChatsQuery->fetchAll(PDO::FETCH_ASSOC);
        
                    foreach ($partnerChats as $partnerChat) {
                        // ユーザーのチャット一覧を取得
                        $userChatsQuery = $conn->prepare('SELECT chat_id FROM participants WHERE user_id = ? ORDER BY joined_at DESC');
                        $userChatsQuery->execute([$user_id]);
                        $userChats = $userChatsQuery->fetchAll(PDO::FETCH_ASSOC);
        
                        foreach ($userChats as $userChat) {
                            if ($partnerChat['chat_id'] === $userChat['chat_id']) {
                                $chatExists = true;
                                $chatId = $userChat['chat_id'];
                                break;
                            }
                        }
                    }
        
                    if ($chatExists) {
                        echo '<body onload="document.chatForm.submit();">
                                <form method="POST" action="" name="chatForm">
                                    <input type="hidden" name="chat_id" value="' . htmlspecialchars($chatId, ENT_QUOTES, 'UTF-8') . '">
                                    <input type="hidden" name="chat_name" value="' . htmlspecialchars($_POST['partner_name'], ENT_QUOTES, 'UTF-8') . '">
                                </form>
                              </body>';
                    } else {
                        // 新しいチャットを作成
                        $createChatQuery = $conn->prepare("INSERT INTO chats (created_by, created_at) VALUES (:created_by, NOW())");
                        $createChatQuery->bindValue(':created_by', $user_id, PDO::PARAM_INT);
                        $createChatQuery->execute();
        
                        // 最新のチャットを取得（created_byがユーザである条件を追加）
                        $latestChatQuery = $conn->prepare("SELECT * FROM chats WHERE created_by = :created_by ORDER BY created_at DESC LIMIT 1");
                        $latestChatQuery->bindValue(':created_by', $user_id, PDO::PARAM_INT);
                        $latestChatQuery->execute();
                        $latestChat = $latestChatQuery->fetch(PDO::FETCH_ASSOC);
        
                        // 参加者を追加
                        $addParticipantQuery = $conn->prepare("INSERT INTO participants (chat_id, user_id, joined_at) VALUES (:chat_id, :user_id, NOW())");
                        $addParticipantQuery->bindValue(':chat_id', $latestChat['chat_id'], PDO::PARAM_INT);
                        $addParticipantQuery->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                        $addParticipantQuery->execute();
        
                        $addParticipantQuery->bindValue(':user_id', $_POST['partner_id'], PDO::PARAM_INT);
                        $addParticipantQuery->execute();

                        echo '<body onload="document.chatForm.submit();">
                                <form method="POST" action="" name="chatForm">
                                    <input type="hidden" name="chat_id" value="' . htmlspecialchars($chatId, ENT_QUOTES, 'UTF-8') . '">
                                    <input type="hidden" name="chat_name" value="' . htmlspecialchars($_POST['partner_name'], ENT_QUOTES, 'UTF-8') . '">
                                </form>
                              </body>';
                    }
                }
        // チャットエリアの表示
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['chat_id'])) {
            $chatId = $_POST['chat_id'];
            $chatName = $_POST['chat_name'];
            require 'message.php';
        } else {
            echo '<div class="noneId"><p>チャットを選択してください</p></div>';
        }
        ?>
    </div>
</div>
<?php require 'common/footer.php'; ?>
