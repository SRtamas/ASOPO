<?php
session_start();
require "db-connect.php";
if(empty($_SESSION['user'])){
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
            header("Location: $redirect_url");
            exit();
  }
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/new-board.css">
    <title>ASOPO</title>
</head>

<body>
    
  <center><h1>新規作成</h1>
<h1>新規作成</h1>
    <?php 
    require 'header.php';
    echo '<form action="new-board-output.php" method="post">';
    echo '<p>スレッド名<input type="text" name="board" class="textbox" required></p>';
    echo 'ジャンル<select name="genre" required>';
    $pdo = new PDO($connect, USER, PASS);
    foreach ($pdo->query('select * from Ganre') as $row) {
        echo '<option value=', $row['genre_id'], '>', $row['genre_name'], '</option>';
    }
    echo '</select><br>';
    
    echo '※非公開スレッドの場合はパスワードを入力してください';

    echo '<p>パスワード';
    echo '<td><input type="password" name="password" class="sign-up-textbox" placeholder="パスワード"
            maxlength="10" ></td>';
    if (isset($password_error)) {
        echo $password_error;
    }
    echo '</p>';
    echo '<p>パスワード確認<input type="password" name="password_con" class="sign-up-textbox" placeholder="パスワード確認用"
            maxlength="10" >';
    if (isset($password_con_error)) {
        echo $password_con_error;
    }
    echo ' </p></div>';
    echo '<button type="submit" class="new-board-button">登録</button>';
    echo '</form>';
    ?>
    </div>
  </center>
</body>

</html>