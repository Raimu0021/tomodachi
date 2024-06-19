<?php
require 'common/header.php';
require 'common/db-connect.php'; // includeではなくrequireに変更

// ユーザーIDはセッションやクッキーから取得することを想定（例：1）
$user_id = 1;

// プロフィール情報を取得
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    echo "プロフィール情報が見つかりませんでした。";
    exit;
}

// 各フィールドの値をチェックし、存在しない場合は「入力されていません」と表示する
$profileImage = isset($profile['profile_image']) ? htmlspecialchars($profile['profile_image']) : '';
$profileName = isset($profile['user_name']) && !empty($profile['user_name']) ? htmlspecialchars($profile['user_name']) : '入力されていません';
$profileAgeGender = isset($profile['gender']) && !empty($profile['gender']) ? htmlspecialchars($profile['gender']) : '入力されていません';
$profileSchool = isset($profile['school_id']) && !empty($profile['school_id']) ? htmlspecialchars($profile['school_id']) : '入力されていません';
$profileGrade = isset($profile['grade']) && !empty($profile['grade']) ? htmlspecialchars($profile['grade']) : '入力されていません';
$profileBio = isset($profile['self_introduction']) && !empty($profile['self_introduction']) ? htmlspecialchars($profile['self_introduction']) : '入力されていません';
?>

<div class="container">
    <div class="profile">
        <img id="profileImage" src="<?= $profileImage ?>" alt="プロフィール画像">
        <p>名前: <span id="profileName"><?= $profileName ?></span></p>
        <p>年齢/性別: <span id="profileAgeGender"><?= $profileAgeGender ?></span></p>
        <p>学校: <span id="profileSchool"><?= $profileSchool ?></span></p>
        <p>学年: <span id="profileGrade"><?= $profileGrade ?></span></p>
        <p>自己紹介: <span id="profileBio"><?= $profileBio ?></span></p>
    </div>
    <div class="edit-profile">
        <a href="profile_edit.php">プロフィールを編集</a>
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

    .profile {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 20px;
    }

    .profile img {
        max-width: 100px;
        border-radius: 50%;
        margin-bottom: 10px;
    }

    .edit-profile {
        text-align: center;
    }

    .edit-profile a {
        text-decoration: none;
        color: #333;
        border: 1px solid #333;
        padding: 5px 10px;
        border-radius: 5px;
    }
</style>

<?php require 'common/footer.php'; ?>
