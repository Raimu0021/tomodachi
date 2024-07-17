<?php
session_start();
require 'common/header.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <link rel="stylesheet" href="../CSS/setting.css">
</head>
<body>
<div class="main-container">
  <a href="mailaddress.php" class="button">
    <div class="button__line"></div>
    <div class="button__line"></div>
    <span class="button__text">メールアドレス変更</span>
    <div class="button__drow1"></div>
    <div class="button__drow2"></div>
  </a>
  <a href="password.php" class="button">
    <div class="button__line"></div>
    <div class="button__line"></div>
    <span class="button__text">パスワード変更</span>
    <div class="button__drow1"></div>
    <div class="button__drow2"></div>
  </a>
  <a href="Account_deletion.php" class="button">
    <div class="button__line"></div>
    <div class="button__line"></div>
    <span class="button__text">アカウント削除</span>
    <div class="button__drow1"></div>
    <div class="button__drow2"></div>
  </a>
</div>
</body>
</html>
