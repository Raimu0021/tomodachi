<!--
問題
・ペアがこのサイトに訪れた際どのような画面を表示すべきか
実施した対処法
もう一方のペアが成功、または失敗を選んだ際、ペアも同じ処理を走らせる
二人が同じ画面を開き、一人の処理が終了した際にもう一人が同じ処理を走らせ、予期しない処理が起こらないよう、
handlerでエラーハンドリング（自身のcurrently_datingが0の場合などにエラーメッセージを返す等）の必要あり
-->

<?php
session_start();
require './common/header.php';
require './common/db-connect.php'; 
$sender_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

//非ログインユーザーの場合、ログインページにリダイレクト
if ($sender_id == null) {
  header('Location: login-logout.php'); 
  exit;
}

// currently_dating = 1
$stmt = $conn->prepare("SELECT sender_id, receiver_id FROM dates WHERE is_dating = 1 AND (sender_id = :sender_id OR receiver_id = :sender_id)");
        $stmt->execute(['sender_id' => $sender_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        
$reciever_id;
        if ($result['sender_id'] != $sender_id) {
            $receiver_id = $result['receiver_id'];
        }else{
            $receiver_id = $result['sender_id'];
        }
?>

<style>
  .buttons {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 20px 0; /* 上下に20pxのパディングを追加 */
  }
</style>



<div class="container text-center">
  <h2 class="mt-5">デートの調子はどうですか</h2>
</div>

<div class="container text-center mt-5 buttons">
  <div class="row justify-content-center">
    <div class="col-12 col-md-4 mb-5">
      <button class="btn btn-success w-100" onclick='dateSuccess($sender_id, $receiver_id)'>成功</button>
    </div>
  </div>
  
    <div class="row justify-content-center">
        <div class="col-12 col-md-4 m-4">
        <button class="btn btn-danger w-100" onclick='dateFailure($sender_id, $receiver_id)'>失敗</button>
        </div>
    </div>
</div>


<script>
    function dateSuccess(sender_id, receiver_id) {
        // AJAXリクエストをバックエンドに送信
        fetch('handler/date_success_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ sender_id: sender_id, receiver_id: receiver_id }),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    function dateFailure(sender_id, receiver_id){
        // AJAXリクエストをバックエンドに送信
        fetch('handler/date_failure_handler.php',{
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ sender_id: sender_id, receiver_id: receiver_id }),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:',data);
        })
        .catch((error) =>{
            console.error('Error:', error);
        });
    }
    </script>
<!-- 
成功した場合
それぞれのusersのcurrently_datingを0、is_privateを1に変更、datesのis_datingを0に変更、date_success.phpに飛ばす 

失敗した場合
それぞれのusersのcurrently_datingを0、datesのis_datingを0に変更、date_failure.phpに飛ばす
-->