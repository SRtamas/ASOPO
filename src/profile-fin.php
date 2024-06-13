<?php
session_start();
require "db-connect.php";
if (empty($_SESSION['user'])) {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
    header("Location: $redirect_url");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile-fin.css">
    <title>プロフィール完了</title>
</head>

<body>
    <?php
        require 'header.php';
    ?>

    <?php

    $pdo = new PDO($connect, USER, PASS);

    if ($_POST['action'] == 'update_profile') {
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
    } elseif ($_POST['action'] == 'delete_icon') {
        $destination_directory = 'pic/icon/';
        $student_id = $_GET['student_id'];
        $file = $destination_directory . $student_id . '.jpg';

        // 既存のアイコンがあれば削除
        if (file_exists($file)) {
            unlink($file);
        }

        // デフォルトアイコンを表示
        echo '<h1>アイコンが削除されました。</h1>';
        echo '<p>デフォルトアイコンが表示されます。</p>';
        echo '<img src="pic/icon/guest.jpg" alt="デフォルトアイコン" class = "default-icon">';

        // ログイン画面にリダイレクト
        echo '<form action="profile-input.php" method="post">';
        echo '<input type="submit" class="login" value="プロフィール画面">';
        echo '</form>';
        exit(); // 処理を終了する
    }
    ?>
</body>

</html>
