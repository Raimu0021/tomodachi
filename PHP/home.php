<?php require './common/header.php' ?>
<?php require './common/card_component.php' ?>

<form action="search.php" method="get">
    <input type="text" name="school_name" placeholder="学校名を入力">
    <input type="submit" value="検索">
</form>

<div class="container">
    <div class="card"></div>
    <div class="card"></div>
    <div class="card"></div>
    <div class="card"></div>
</div>

<?php require './common/footer.php' ?>