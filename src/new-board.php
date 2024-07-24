<?php
session_start();
require "db-connect.php";
if (empty($_SESSION['user'])) {
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
  <title>ASO PORTAL　|　official</title>
</head>

<body>
  <?php
  require 'header.php';
  echo '<center>';
  echo '<form action="new-board-output.php" class = "new-form" method="post">';
  echo '<h1 class="new_head">新規作成</h1>';
  echo '<p class = "new-p">掲示板名<br><input type="text" name="board" class="sign-up-textbox" placeholder="掲示板名" required></p>';
  echo 'カテゴリ<br><select class="sign-up-textbox" name="genre" required>';
  $pdo = new PDO($connect, USER, PASS);
  foreach ($pdo->query('select * from Ganre') as $row) {
    echo '<option value=', $row['genre_id'], '>', $row['genre_name'], '</option>';
  }
  echo '</select><br>';

  echo '<p class = "new-p">パスワード';
  echo '<br><span class="note">※非公開の掲示板の場合、パスワードを設定してください</>';
  echo '<td><input type="password" name="password" class="sign-up-textbox" placeholder="パスワード"
            maxlength="10" ></td>';
  if (isset($password_error)) {
    echo $password_error;
  }
  echo '</p>';
  echo '<p class = "new-p">パスワード確認<br><input type="password" name="password_con" class="sign-up-textbox" placeholder="パスワード確認用"
            maxlength="10" >';
  if (isset($_SESSION['new_board']['error']) && !empty($_SESSION['new_board']['error'])) {
    echo '<br><span class="error">'.$_SESSION['new_board']['error'].'</>';
  } else {
  }
  unset($_SESSION['new_board']['error']);
  echo ' </p></div>';
  echo '<button type="submit" class="new-board-button">登録</button>';
  echo '</form>';
  ?>
  </div>
  </center>
</body>

</html>