<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'common/db-connect.php';

$message = '';
$uploadDir = 'uploads/'; // アップロードディレクトリを指定
$profile = []; // プロフィール情報を初期化

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // アップロードディレクトリが存在しない場合は作成
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // 現在のプロフィール情報を取得
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    // Null値をデフォルト値に置き換え
    $profile_name = htmlspecialchars($profile['user_name'] ?? '');
    $profile_bio = htmlspecialchars($profile['self_introduction'] ?? '');
    $profile_gender = htmlspecialchars($profile['gender'] ?? '');
    $profile_date_of_birth = htmlspecialchars($profile['date_of_birth'] ?? '');
    $profile_image = htmlspecialchars($profile['profile_image'] ?? 'default.png');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $bio = $_POST['bio'] ?? ''; // 空欄の場合は空文字に設定
        $gender = $_POST['gender'];
        $date_of_birth = $_POST['date_of_birth'];
        $profile_image_path = $profile_image; // デフォルトで現在の画像パスを設定

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $profile_image_path = $uploadDir . basename($_FILES['profile_image']['name']);
            if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $profile_image_path)) {
                $message = '画像のアップロードに失敗しました。';
            }
        }

        // プロフィール情報を更新
        $stmt = $conn->prepare("UPDATE users SET user_name = :name, self_introduction = :bio, gender = :gender, date_of_birth = :date_of_birth, profile_image = :profile_image WHERE user_id = :user_id");
        $stmt->execute([
            'user_id' => $user_id,
            'name' => $name,
            'bio' => $bio,
            'gender' => $gender,
            'date_of_birth' => $date_of_birth,
            'profile_image' => $profile_image_path
        ]);

        header('Location: profile.php');
        exit;
    }
} else {
    $message = "セッションが見つかりません。再度ログインしてください。";
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/profile_edit.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    require 'common/header.php';
    ?>
    <div class="container">
        <h2>プロフィール編集</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form id="profileForm" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile_image">プロフィール画像</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*">
                <img src="<?= $profile_image ?>" alt="プロフィール画像" class="current-image" id="profileImagePreview">
            </div>
            <div class="form-group">
                <label for="name">名前</label>
                <input type="text" id="name" name="name" value="<?= $profile_name ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="gender">性別</label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="">選択してください</option>
                    <option value="Male" <?= $profile_gender === 'Male' ? 'selected' : '' ?>>男性</option>
                    <option value="Female" <?= $profile_gender === 'Female' ? 'selected' : '' ?>>女性</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date_of_birth">生年月日</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="<?= $profile_date_of_birth ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="bio">自己紹介</label>
                <textarea id="bio" name="bio" class="form-control"><?= $profile_bio ?></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
        </form>
        <div class="back-link">
            <a href="profile.php" class="btn btn-secondary">戻る</a>
        </div>
    </div>
    <script>
        document.getElementById('profile_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImagePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>
<?php require 'common/footer.php'; ?>
