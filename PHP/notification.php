<?php
    $notification_sql = $conn->prepare('SELECT * FROM notification WHERE user_id=? AND is_read = 0 ORDER BY notification_at');
    $notification_sql->execute([$user_id]);
    $notification = $notification_sql->fetch(PDO::FETCH_ASSOC);

    foreach($notification as $no){
        echo $no;
    }
?>