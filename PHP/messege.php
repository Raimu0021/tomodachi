<?php require 'common/db-kyosuke.php';

$receiver_id = 1;
$sender_id = 2;

$pdo = new PDO($connect, USER, PASS);
$sql = $pdo->prepare('');
query
execute





?>

<form action="" method = "post">
    <input type="text" name="message">
    <button type="submit" name="send">送信</button>
</form>


<a href="home.php">もどる</a>

