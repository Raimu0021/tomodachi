<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'common/header.php';
include 'common/db-connect.php';

$message = '';

if (isset($_SESSION['user_id'])) {
    $user_id = $_POST['user_id'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$profile) {
        $message = "プロフィール情報が見つかりませんでした。";
    } else {
        $school_id = $profile['school_id'];
        $stmt = $conn->prepare("SELECT school_name FROM schools WHERE school_id = :school_id");
        $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
        $stmt->execute();
        $school = $stmt->fetch(PDO::FETCH_ASSOC);
        $profile['school_name'] = $school ? $school['school_name'] : '不明';
    }
    $stmt->closeCursor();
} else {
    $message = "セッションが見つかりません。再度ログインしてください。";
}

$conn = null;

// nullチェックとデフォルト値の設定
$profileImage = isset($profile['profile_image']) ? htmlspecialchars($profile['profile_image']) : 'default.png';
$profileName = isset($profile['user_name']) && !empty($profile['user_name']) ? htmlspecialchars($profile['user_name']) : '入力されていません';
$profileBirthday = isset($profile['date_of_birth']) && !empty($profile['date_of_birth']) ? $profile['date_of_birth'] : '入力されていません';

// 年齢計算のための関数
function calculate_age($birthday) {
    $birthDate = new DateTime($birthday);
    $today = new DateTime('today');
    $age = $birthDate->diff($today)->y;
    return $age;
}

$profileAge = ($profileBirthday != '入力されていません') ? calculate_age($profileBirthday) : '入力されていません';
$profileSchool = isset($profile['school_name']) ? htmlspecialchars($profile['school_name']) : '入力されていません';
$profileGender = isset($profile['gender']) && $profile['gender'] === 'Male' ? '男性' : '女性';
$profileBio = isset($profile['self_introduction']) && !empty($profile['self_introduction']) ? htmlspecialchars($profile['self_introduction']) : '入力されていません';
?>

    <link rel="stylesheet" href="../CSS/profile.css">
    <div class="content">
            <div class="login-form">
                <?php if (!empty($message)): ?>
                    <div class="message"><?php echo htmlspecialchars($message); ?></div>
                <?php else: ?>
                    <div class="profile-container">
                        <div class="profile-header">
                            <img id="profileImage" src="<?= $profileImage ?>" alt="プロフィール画像">
                            <div class="profile-info">
                                <h1 id="profileName"><?= $profileName ?></h1>
                                <p id="profileAge"><?= $profileAge ?>歳</p>
                                <p id="profileSchool"><?= $profileSchool ?></p>
                                <p id="profileGender"><?= $profileGender ?></p>
                            </div>
                        </div>
                        <div class="profile-bio">
                            <p id="profileBio"><?= $profileBio ?></p>
                        </div>
                        <div class="profile-actions">
                            <a href="profile_edit.php" class="edit-profile-button">プロフィールを編集</a>
                            <form action="login-logout.php" method="POST" style="display:inline;">
                                <button type="submit" class="logout-button" name="logout">ログアウトする</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
    </div>       

<?php require 'common/footer.php'; ?>
