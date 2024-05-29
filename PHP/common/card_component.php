<?php
// card_component.php
function renderCard($image, $name, $age, $gender, $school) {
    //性別を日本語に変換
    $gender = convertGenderToJapanese($gender);
    

    /* 
    いいねボタンの処理
    ・相手のidと自分のidをlikesデータベースで検索する　見つからなかったら空のハート
    ・ない場合
    クリックされたらlikesデータベースに登録
    ・ある場合
    クリックされたらlikesデータベースから削除
    
    */
    echo "
    <div class='card'>
        <img src='$image' alt='$name'>
        <div class='card-body'>
            <h2>$name</h2>
            <p>$age 歳/$gender</p>
            
            <p>$school</p>
            
            <button>Like</button>
        </div>
    </div>";
}

// function

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
?>