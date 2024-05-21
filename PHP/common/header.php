<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/header.css">
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
    <div class="sidebar">
        <a href="./home.php">
            <img src="../CSS/copuruLogo.jpg" alt="ロゴ" class="logo">
        </a>
        <ul>
            <li><a href="./home.php">ホーム</a></li>
            <li><a href="./notification.php" id="noti">通知</a></li>
            <li><a href="./date.php">デート</a></li>
            <li><a href="./messege.php">メッセージ</a></li>
            <li><a href="./setting.php">設定</a></li>
            <li id="profile"><a href="./profile">プロフィール</a></li>
        </ul>
        <div class="notification">
            <?php require "./notification.php";?>
        </div>


        <style>
            .notification {
                display: none;
            }
        
        .open {
            width: 250px;
            transform: translatex(300px);
            border: solid #DADADA 2px;
        }
        </style>

        <script>
            const noti = document.getElementaryById("noti");
            noti.document.addEventlist("Click",()=>{
                noti.classList.toggle("open");
            })
        </script>
    </div>

