<?php
session_start();
require "db-connect.php";
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile-fin.css">
    <title>プロフィール編集</title>
</head>

<body>

    <?php

    $pdo = new PDO($connect, USER, PASS);

    $sql = $pdo->prepare('UPDATE User SET user_name=?, user_profile=? WHERE student_id=?');
    $sql->execute([htmlspecialchars($_POST['user_name']), $_POST['user_profile'], $_GET['student_id']]);
    $_SESSION['user']['user_name'] = $_POST['user_name'];

    // 画像がアップロードされた場合の処理
    if (isset($_FILES['tmp_icon']) && is_uploaded_file($_FILES['tmp_icon']['tmp_name'])) {
        $destination_directory = 'pic/icon/';
        $student_id = $_GET['student_id'];
        $file = $destination_directory . $student_id . '.jpg';

        // 既存のアイコンがあれば削除
        if (file_exists($file)) {
            unlink($file);
        }

        // 画像を保存
        if (move_uploaded_file($_FILES['tmp_icon']['tmp_name'], $file)) {
            // 成功したらメッセージを表示
            echo '<h1>プロフィールが編集されました。</h1>';
            echo '<p>引き続きお楽しみください。</p>';


            // ログイン画面にリダイレクト
            echo '<form action="home-login.php" method="post">';
            echo '<input type="submit" class="login" value="ホーム画面">';
            echo '</form>';
            exit(); // 処理を終了する
        } else {
            echo '<h1>画像の保存に失敗しました。</h1>';
            echo '<p>再度お試しください。</p>';
        }
    }

    // 画像がアップロードされなかった場合は、メッセージを表示してログイン画面にリダイレクト
    echo '<h1>プロフィールが編集されました。</h1>';
    echo '<p>引き続きお楽しみください。</p>';
    echo '<form action="home-login.php" method="post">';
    echo '<input type="submit" class="login" value="ホーム画面">';
    echo '</form>';
    ?>
</body>

</html>