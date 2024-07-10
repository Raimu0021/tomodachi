<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
        rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
        crossorigin="anonymous"
    >
    <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" 
    crossorigin="anonymous"
    ></script>
    
</head>
<body>
    <?php
    require "db-connect.php";
    $user_id = $_SESSION['user_id'];

    $user_sql = $conn->prepare('SELECT * FROM users WHERE user_id=?');
    $user_sql->execute([$user_id]);
    $user = $user_sql->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="sidebar">
        <a href="./home.php">
            <img src="../CSS/copuruLogo.jpg" alt="ロゴ" class="logo">
        </a>
        <ul>
            <li><a href="./home.php"><img src="../CSS/icon_home.png" alt="ホーム">ホーム</a></li>
            <li><a href="#" id="noti"><img src="../CSS/icon_notification.png" alt="通知">通知</a></li>
            <li><a href="./date.php"><img src="../CSS/icon_date.png" alt="デート">デート</a></li>
            <li><a href="./chat.php"><img src="../CSS/icon_message.png" alt="メッセージ">メッセージ</a></li>
            <li><a href="./setting.php"><img src="../CSS/icon_setting.png" alt="設定">設定</a></li>
            <li id="user"><a href="./profile"><img src="<?php echo $user['profile_image']?>" alt=""><?php echo $user['user_name']?></a></li>
        </ul>
        
    </div>
    <div class="notification" style="display:none;">
            <?php require "notification.php";?>
    </div>
    <style>
        .notification {
            position: absolute;
            width: 250px;
            height: 100%;
            top: 0;
            border: solid #dadada 1px;
            background-color: white;
            z-index: 10;
            overflow-y: scroll;
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