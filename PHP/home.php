<?php require './common/header.php' ?>
<?php require './common/card_component.php' ?>
<?php require './common/db-connect.php'; ?>

<form action="search.php" method="get" class="mb-4">
    <div class="input-group">
        <input type="text" name="school_name" class="form-control" placeholder="学校名を入力">
        <button class="btn btn-primary" type="submit">検索</button>
    </div>
</form>

<div class="container">
    <div class="row">
        <?php
        $school_id = 1; // 例: 表示したい学校のID
        // $sql = "SELECT profile_image, user_name, age, gender, school FROM users WHERE school_id = $school_id ORDER BY RAND() LIMIT 8";
        // $result = $conn->query($sql);

        // if ($result->num_rows > 0) {
        //     while($row = $result->fetch_assoc()) {
                echo '<div class="col-md-3 mb-4">';
                echo ' test';
                // renderCard($row['image'], $row['name'], $row['age'], $row['gender'], $row['school']);
                echo '</div>';
        //     }
        // } else {
        //     echo "<p>No results found</p>";
        // }

        // $conn = null;
        ?>
    </div>
</div>

<?php require './common/footer.php'; ?>