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
    <link rel="stylesheet" type="text/css" href="css/new-board-output.css">
    <title>ASOPO</title>
</head>

<body>
    <?php
$board_name = $_POST['board'];
$genre_id = $_POST['genre'];
$student_id = $_SESSION['user']['student_id'];
$createdate = date("Y/m/d H:i");

if (!empty($_POST['password'])) {
    $board_password = $_POST['password'];
    $board_password_con = $_POST['password_con'];
    
    if ($board_password == $board_password_con) {
        $password_hash = password_hash($board_password, PASSWORD_DEFAULT);
    } else {
        require 'header.php';
        echo '<div class="message">パスワードが一致しません。</div>';
        echo '<button onclick="history.back();" class="form-button">戻る</button>';
        
        exit; // パスワードが一致しない場合は処理を終了
    }
} else {
    $password_hash = null; // パスワードが入力されていない場合は NULL を設定
}


$sql_insert = $pdo->prepare('INSERT INTO Board (board_name, genre_id, student_id, board_createdate, board_password) VALUES (?, ?, ?, ?, ?)');
$sql_insert->execute([$board_name, $genre_id, $student_id, $createdate, $password_hash]);


$last_insert_id = $pdo->lastInsertId();

$redirect_url = "https://aso2201203.babyblue.jp/ASOPO/src/thread.php?id=" . $last_insert_id;
header("Location: " . $redirect_url);
?>


    <h2>$_GET['board_name']</h2>
    <a href="thread.php">インプットへ</a>
</body>

</html>
<!-- <a href="thread.php?id=', $row['board_id'], '">', $row['board_id'], '</a> -->