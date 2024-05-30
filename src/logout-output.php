<?php
session_start();
if(empty($_SESSION['user'])){
   $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
           header("Location: $redirect_url");
           exit();
 }
session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="css/logout-output.css">
   <title>ログアウト</title>
</head>
<body>

   <h1>ログアウトしました。</h1>
   <p>またのご利用お待ちしております。</p>
   <!-- ログイン画面に遷移する -->
   <form action="login-input.php" method="post">
       <input type="submit" class="login" value="ログイン画面へ">
   </form>
</body>
</html>