<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>
<body>
    <div class="sidebar">
        <a href="./home.php">
            <img src="../CSS/copuruLogo.jpg" alt="ロゴ" class="logo">
        </a>
        <ul>
            <li><a href="./home.php">ホーム</a></li>
            <li><a href="#" id="noti">通知</a></li>
            <li><a href="./date.php">デート</a></li>
            <li><a href="./chat_list.php">メッセージ</a></li>
            <li><a href="./setting.php">設定</a></li>
            <li id="profile"><a href="./profile">プロフィール</a></li>
        </ul>
        
    </div>
    <div class="notification" style="display:none;">
            <?php require "./notification.php";?>
    </div>
    <style>
        .notification {
            position: absolute;
            width: 250px;
            height: 100%;
            top: 0;
            border: solid #dadada 1px;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const notification = document.querySelector(".notification");
            const nLink = document.getElementById("noti");

            nLink.addEventListener("click", () => {
                if (notification.style.display === 'block') {
                    notification.style.display = 'none';
                } else {
                    notification.style.display = 'block';
                }
            });
        });
    </script>