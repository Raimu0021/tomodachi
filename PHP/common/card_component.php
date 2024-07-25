<?php

// db-connect.phpと一緒に読み込むように
function renderCard($user_id, $profile_image, $user_name, $date_of_birth, $gender, $school_id, $current_user_id) {
    $profile_image = $profile_image ? htmlspecialchars($profile_image) : '../img/default-avatar.png';
    $age = calculateAge($date_of_birth);
    $gender = convertGenderToJapanese($gender);
    $school = getSchoolName($school_id);
    $liked = isLikedByUser($user_id, $current_user_id) ? 'fa-solid fa-heart' : 'fa-regular fa-heart';

    echo "
        <div class='user_card'>
            <img src='{$profile_image}' alt='{$user_name}'>
            <button class='like-btn' data-user-id='{$user_id}'>
                <i class='{$liked}'></i>
            </button>
            <form action='show_profile.php' method='post'>
                <input type='hidden' name='user_id' value='{$user_id}'>
                <button type='submit' class='card-body'>
                    <p class='user_name'>{$user_name}</p>
                    <p class='age'>{$age}歳/{$gender}</p>
                    <p class='school'>{$school}</p>
                </button>
            </form>
        </div>
    ";
}

function calculateAge($date_of_birth) {
    $dob = new DateTime($date_of_birth);
    $now = new DateTime();
    $diff = $now->diff($dob);
    return $diff->y;
}

function convertGenderToJapanese($gender) {
    if ($gender == 'Male') {
        return '男性';
    } elseif ($gender == 'Female') {
        return '女性';
    } else {
        return 'その他';
    }
}

function getSchoolName($school_id) {
    global $conn;

    if ($conn === null) {
        error_log("データベース接続に失敗しました。");
        return "データベース接続エラー";
    }

    try {
        $stmt = $conn->prepare("SELECT school_name FROM schools WHERE school_id = :school_id");
        $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['school_name'];
        } else {
            return "学校名不明";
        }
    } catch (PDOException $e) {
        error_log("データベースクエリエラー: " . $e->getMessage());
        return "クエリエラー";
    }
}

function isLikedByUser($user_id, $current_user_id) {
    global $conn;

    if ($conn === null) {
        error_log("データベース接続に失敗しました。");
        return false;
    }

    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE liked_user_id = :user_id AND user_id = :current_user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':current_user_id', $current_user_id);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("データベースクエリエラー: " . $e->getMessage());
        return false;
    }
}
?>
