<?php require 'common/header.php'; ?>
<div class="container">
    <form id="profileForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="profile_image">プロフィール画像</label>
            <input type="file" id="profile_image" name="profile_image">
        </div>
        <div class="form-group">
            <label for="name">名前</label>
            <input type="text" id="name" name="name">
        </div>
        <div class="form-group">
            <label for="age_gender">年齢/性別</label>
            <input type="text" id="age_gender" name="age_gender">
        </div>
        <div class="form-group">
            <label for="school">学校</label>
            <input type="text" id="school" name="school">
        </div>
        <div class="form-group">
            <label for="grade">学年</label>
            <input type="text" id="grade" name="grade">
        </div>
        <div class="form-group">
            <label for="bio">自己紹介</label>
            <textarea id="bio" name="bio"></textarea>
        </div>
        <div class="form-group">
            <button type="submit">保存</button>
        </div>
    </form>
    <div class="back-link">
        <a href="profile.php">戻る</a>
    </div>
</div>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    #header {
        background-color: #333;
        color: white;
        padding: 10px;
        text-align: center;
    }

    .container {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input, .form-group textarea {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
    }

    .form-group input[type="file"] {
        padding: 3px;
    }

    .form-group textarea {
        height: 100px;
    }

    .form-group button {
        background-color: #333;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
    }

    .form-group button:hover {
        background-color: #555;
    }

    .back-link {
        text-align: center;
        margin-top: 20px;
    }

    .back-link a {
        text-decoration: none;
        color: #333;
        border: 1px solid #333;
        padding: 5px 10px;
        border-radius: 5px;
    }
</style>
<?php require 'common/footer.php';?>
