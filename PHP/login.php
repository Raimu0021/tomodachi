<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
<div class="flexbox">
    <div class="content">
        <form action="home.php" method="POST">
        <img src="../CSS/copuruLogo.jpg" alt="ロゴ" class="logo">
            <p>あなたの出会いをサポートします</p>
            <br>
                <p>メールアドレス<br>
                <input type="email" name="email"></p>
 
                <p>パスワード<br>
                <input type="password" name="password"><br>
                <a href="">パスワードの変更</a></p><br>
 
                <button type="submit" class="btn">ログイン</button>
        </form>
    </div><br>
        <p class="box">今すぐ出会いが欲しいですか？
        <a href="signup.php"><button class="btn">新規登録</button></a></p>
</div>
</body>
</html>