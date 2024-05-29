<?php 
require 'common/header.php';
require 'common/db-connect.php';

$user_id = 1;
$pdo = new PDO($connect, USER, PASS);

    // チャットの取得
    $chats = $pdo->("select * from chats where participant1_id or participant2_id");
    $pdo->execute([$user_id,$user_id]);

    foreach($chats as $chat){
        if($chat['participant2_id'] == $user_id){
            $totolk = $chat['participant2_id'];
        }
        echo $chat['id'],$chat['participant1_id'],$chat['participant2_id'],$chat['last_message_id'];
    }
?>

<?php require 'common/footer.php';?>
