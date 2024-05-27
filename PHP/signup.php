
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
                <input type="email" name="email"></p>
 
                <p>パスワード<br>
                <input type="password" name="pass"></p><br>
 
                <button type="submit" class="btn">新規登録</button>
        </form>
    </div><br>
        <p class="box">登録済みですか？
        <a href="login.php"><button class="btn">ログイン</button></a></p>
</div>
</body>
</html>