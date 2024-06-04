<?php
// db-connect.phpと一緒に読み込むように　（学校名が表示されません）
function renderCard($profile_image, $user_name, $date_of_birth, $gender, $school_id) {
    //性別を日本語に変換
    $age = calculateAge($date_of_birth);
    $gender = convertGenderToJapanese($gender);
    $school = getSchoolName($school_id);

    /* 
    - いいねボタンの処理
    ・相手のidと自分のidをlikesデータベースで検索する　見つからなかったら空のハート
    ・ない場合
    クリックされたらlikesデータベースに登録
    ・ある場合
    クリックされたらlikesデータベースから削除
    

    -画像表示処理
    ・別途でimgファイルを用意する
    ・imgファイルの中の画像を参照するよう、$profile_imageの内容を変更する必要がある
    */
    echo "
    <div class='card'>
        <img src='$profile_image' alt='$user_name'>
        <div class='card-body'>
            <h2>$user_name</h2>
            <p>$age 歳/$gender</p>
            
            <p>$school</p>
            
            <button>Like</button>
        </div>
    </div>";
}

function calculateAge($date_of_birth){
    $dob = new DateTime($date_of_birth);
    $now = new DateTime();
    $diff = $now->diff($dob);
    return $diff->y;
}

function convertGenderToJapanese($gender){
    
    if($gender == 'Male'){
        $gender = '男性';
    }elseif ($gender == 'Female') {
        $gender = '女性';
    }else{
        $gender = 'その他';
    }
    
    return $gender;
}


function getSchoolName($school_id){
    global $conn;

    $stmt = $conn->prepare("SELECT school_name FROM schools WHERE school_id = :school_id");
    $stmt->bindParam(':school_id', $school_id);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['school_name'];
    } else {
        return "学校名不明";
    }
}
?>
