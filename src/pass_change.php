<?php
session_start();
require "db-connect.php";
if (empty($_SESSION['user'])) {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
    header("Location: $redirect_url");
    exit();
}
$board_id = $_POST['board_id'];
if (!(empty($_POST['pass_change']))) {
    // パスワード変更
    $pass = $_POST['pass_change'];
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
    $sql_update = $pdo->prepare('UPDATE Board SET board_password = ? WHERE board_id = ?');
    $sql_update->execute([$pass_hash, $board_id]);
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/thread.php?id='.intval($board_id);
    header("Location: $redirect_url");
    exit();
}
if (!(empty($_POST['pass_delete']))) {
    // パスワード削除
    $sql_update = $pdo->prepare('UPDATE Board SET board_password = ? WHERE board_id = ?');
    $sql_update->execute([null, $board_id]);
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/thread.php?id='.intval($board_id);
    header("Location: $redirect_url");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/pass_change.css">
    <title>パスワード変更</title>
</head>

<body>
    <h1>パスワード変更/削除画面</h1>
    <form action="pass_change.php" method="post">
        <span>パスワード変更</span>
        <input type="text" name="pass_change">
        <?php
        echo '<input type="hidden" name="board_id" value=', $board_id, '>';
        ?>
        <button>変更</button>
    </form>
    <br>
    <form action="pass_change.php" method="post">
        <span>パスワード削除</span>
        <input type="hidden" name="pass_delete" value=1>
        <?php
        echo '<input type="hidden" name="board_id" value=', $board_id, '>';
        ?>
        <button>削除</button>
    </form>
    <input type="button" name="back" onclick="history.back()" value="戻る">
</body>

</html>