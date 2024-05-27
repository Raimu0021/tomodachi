<?php
require_once( "./common/db-connect.php" );
dbconnection();
 
// ログの表示
ini_set('log_errors','On');
ini_set('error_log','php.log');
 
 
//（2） postされていれば処理を開始する
if(!empty($_POST)){
 
  // （3）postした「名前」と「パスワード」を変数に入れる
  $user_email = !empty($_POST['user_email']) ? $_POST['user_email']:'';
  $user_pass = !empty($_POST['user_pass']) ? $_POST['user_pass']:'';
 
  // （4）例外処理でDB接続準備
  // *通常はここでバリデーションチェック*
  try{

  $options = array(
    // SQL実行失敗時には例外をスローしてくれる
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // カラム名をキーとする連想配列で取得する．これが一番ポピュラーな設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,);
 
    // （5）PDOオブジェクトでDBに接続
    $dbh = new PDO($dsn,$user,$password,$options);
 
    // （6）SQL文：usersテーブルにuser_nameとpasswordを挿入する
    // 変数値はそのままセットせず、プレースホルダを使用する
    $sql = 'INSERT INTO users(user_name ,password,create_date) VALUES(:user_name,:password,:create_date)';
 
    // （7）prepareメソッドを使ってSQL実行準備
    $stmt = $dbh->prepare($sql);
 
    // （8）executeメソッドでクエリの実行：ハッシュ化したパスワードをDBに登録
    $stmt->execute(array(
      ':user_email' => $user_name,
      ':password' =>  password_hash($user_pass,PASSWORD_DEFAULT),
      ':create_date' => date('Y-m-d H:i:s')));
 
     }catch(Exception $e){
       error_log('エラー発生：' . $e->getMessage());
  }
 
}
 
 
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>新規登録</title>
    <link rel="stylesheet" href="../CSS/signup.css">
</head>
<body>
<div class="flexbox">
    <div class="content">
        <form action="home.php" method="POST">
        <img src="../CSS/copuruLogo.jpg" alt="ロゴ" class="logo">
            <p>あなたの出会いをサポートします</p>
            <br>
                <p>メールアドレス<br>
                <input id="email" type="email" name="email"></p>
 
                <p>パスワード<br>
                <input id="password" type="password" name="password"></p><br>
 
                <button type="submit" class="btn">新規登録</button>
        </form>
    </div><br>
        <p class="box">登録済みですか？
        <a href="login.php"><button class="btn">ログイン</button></a></p>
</div>
</body>
</html>