<?php
session_start();
require 'common/header.php';
require 'common/db-connect.php';
?>

<link rel="stylesheet" href="../CSS/profile.css">

<?php
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    echo "プロフィール情報が見つかりませんでした。";
    exit;
}

$profileImage = isset($profile['profile_image']) ? htmlspecialchars($profile['profile_image']) : 'default.png';
$profileName = isset($profile['user_name']) && !empty($profile['user_name']) ? htmlspecialchars($profile['user_name']) : '入力されていません';
$profileAgeGender = isset($profile['gender']) && !empty($profile['gender']) ? htmlspecialchars($profile['gender']) : '入力されていません';
$profileAgeGender .= isset($profile['age']) && !empty($profile['age']) ? '/' . htmlspecialchars($profile['age']) . '歳' : '';
$profileSchool = isset($profile['school_id']) && !empty($profile['school_id']) ? htmlspecialchars($profile['school_id']) : '入力されていません';
$profileGrade = isset($profile['grade']) && !empty($profile['grade']) ? htmlspecialchars($profile['grade']) : '入力されていません';
$profileBio = isset($profile['self_introduction']) && !empty($profile['self_introduction']) ? htmlspecialchars($profile['self_introduction']) : '入力されていません';
$profileLikes = isset($profile['likes']) ? htmlspecialchars($profile['likes']) : '0';
?>

<div class="container">
    <div class="profile-header">
        <img id="profileImage" src="<?= $profileImage ?>" alt="プロフィール画像">
        <div class="profile-info">
            <h2 id="profileName"><?= $profileName ?></h2>
            <p id="profileAgeGender"><?= $profileAgeGender ?></p>
            <p>いいね済み <span id="profileLikes"><?= $profileLikes ?></span> 人</p>
        </div>
        <a href="logout.php" class="logout-button">ログアウトする</a>
    </div>
    <div class="profile-details">
        <p>学校: <span id="profileSchool"><?= $profileSchool ?></span></p>
        <p>学年: <span id="profileGrade"><?= $profileGrade ?></span></p>
        <p>自己紹介: <span id="profileBio"><?= $profileBio ?></span></p>
    </div>
    <div class="edit-profile">
        <a href="profile_edit.php">プロフィールを編集</a>
    </div>
</div>

<?php require 'common/footer.php'; ?>
