<?php
session_start();
require "db-connect.php";
if (empty($_SESSION['user'])) {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
    header("Location: $redirect_url");
    exit();
}
$board_id = $_SESSION['board_id']['board_id'];
$sql = $pdo->prepare('SELECT * FROM Board WHERE board_id=? ');
$sql->execute([$board_id]);
foreach ($sql as $row) {
    $board_pass = $row['board_password'];
    $board_name = $row['board_name'];
}
unset($error);
if (!(empty($_POST['thread_pass']))) {
    $thread_pass = $_POST['thread_pass'];
    if (password_verify($thread_pass, $board_pass)) {
        $_SESSION['$board_id']['judge'] = $board_id;
        // var_dump($_SESSION[$board_id]['judge']);
        // var_dump($board_id);
        $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/thread.php?id=' . $row['board_id'];
        header("Location: $redirect_url");
        exit();
    } else {
        $error = 'パスワードが一致しません';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/thread_pass.css">
    <title>Document</title>
</head>

<body>
    <?php
    require 'header.php';
    ?>
    <main>
        <form class="form-container" action="thread_pass.php" method="post">
            <center>
                <span class="form-title">掲示板のパスワードを入力してください</span><br>
                <span class="form-title"><?php echo $board_name ?></span><br>
                <input class="form-input" type="text" name="thread_pass" placeholder="パスワードを入力してください" required>
                <?php
                if (!empty($error)) {
                    echo '<br><span class="error">' . $error . '</span>';
                }
                ?>
                <button class="form-button" type="submit">入力</button>
                <button onclick="history.back();" class="form-button">戻る</button>
            </center>
        </form>
    </main>
</body>

</html>