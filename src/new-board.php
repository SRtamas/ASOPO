<?php
session_start();
require "db-connect.php";
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASOPO</title>

</head>

<body>

    <?php
    echo '<form action="new-board-output.php" method="post">';
    echo 'スレッド名<input type="text" name="board" class="textbox" required><br>';
    echo 'ジャンル<select name="genre" required>';
    $pdo = new PDO($connect, USER, PASS);
    foreach ($pdo->query('select * from Ganre') as $row) {
        echo '<option value=', $row['genre_id'], '>', $row['genre_name'], '</option>';
    }
    echo '</select><br>';
    echo 'パスワード';
    echo '<td><input type="password" name="password" class="sign-up-textbox" placeholder="パスワード"
            maxlength="10" ></td>';
    if (isset($password_error)) {
        echo $password_error;
    }
    echo '<input type="password" name="password_con" class="sign-up-textbox" placeholder="パスワード確認用"
            maxlength="10" >';
    if (isset($password_con_error)) {
        echo $password_con_error;
    }
    echo ' </div>';
    echo '<button type="submit" class="new-board-button">登録</button>';
    echo '</form>';
    ?>
</body>

</html>