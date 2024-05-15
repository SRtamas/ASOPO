<?php
session_start();
require 'db-connect.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="css/logout-input.css">
   <title>ログアウト</title>
</head>
<body>
<h1>ログアウトしますか？</h1>
<?php
$student_id = $_SESSION['user']['student_id'];
          $icon_file = "pic/icon/{$student_id}.jpg"; 
          if (file_exists($icon_file)) {
              echo '<img id="me" src="' . $icon_file . '" alt="アイコン">';
          } else {
              echo '<img id="me" src="pic/icon/guest.jpg" alt="デフォルトアイコン">';
          }
?>
<div class="useridname">
  <?php echo $_SESSION['user']['student_id'],'<br>'; echo $_SESSION['user']['user_name']; ?>
</div>
<div class="bg_test">
</div>

   <!--商品画面に遷移する-->
   <form action="top.php" method="post">
       <input type="submit" class="back" value="いいえ">
   </form>

   <!--ログアウト完了画面に遷移する-->
   <form action="logout-output.php" method="post">
       <input type="submit" class="out" value="はい">
   </form>
</body>
</html>