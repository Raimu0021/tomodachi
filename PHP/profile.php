<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(!isset($_SESSION['user_id'])){
    header('Location: login-logout.php');
    $_SESSION['noLogin'] = "ログインしてください";
    exit;
  }

include 'common/db-connect.php';

$message = '';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
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

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    require 'common/header.php';
    ?>
    <div class="login-field">
        <div class="login-background">
            <div class="login-title">
            </div>
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
    </div>
    <span class="square square-tl"></span>
    <span class="square square-tr"></span>
    <span class="square square-bl"></span>
    <span class="square square-br"></span>
    <span class="star star1"></span>
    <span class="star star2"></span>
</body>

</html>
<?php require 'common/footer.php'; ?>
