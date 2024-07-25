問題
・ペアがこのサイトに訪れた際どのような画面を表示すべきか
実施した対処法
そもそも成功した際の処理が

<?php
session_start();
require './common/header.php';
require './common/db-connect.php'; 
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
      <button class="btn btn-success w-100">成功</button>
    </div>
  </div>
  
    <div class="row justify-content-center">
        <div class="col-12 col-md-4 m-4">
        <button class="btn btn-danger w-100">失敗</button>
        </div>
    </div>
</div>

成功した場合
それぞれの