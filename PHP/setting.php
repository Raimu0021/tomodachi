<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>設定ページ</title>
    <link rel="stylesheet" href="../CSS/setting.css">
</head>
<body>
    <div class="container">
        <form action="mailaddress.php" method="get">
            <button type="submit">メールアドレス変更</button>
        </form>
        <form action="change_password.php" method="get">
            <button type="submit">パスワード変更</button>
        </form>
        <form action="delete_account.php" method="get">
            <button type="submit">アカウント削除</button>
        </form>
    </div>
</body>
</html>
