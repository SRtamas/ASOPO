<?php
session_start();
require "db-connect.php";
if (empty($_SESSION['user'])) {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
    header("Location: $redirect_url");
    exit();
} else {
    $student_id_con = $_GET['id'];
    $sql_user = $pdo->prepare('SELECT * FROM User WHERE student_id=?');
    $sql_user->execute([$student_id_con]);
    foreach ($sql_user as $row_user) {
        $user_name = $row_user['user_name'];
        $user_school = $row_user['School_id'];
        $user_profile = $row_user['user_profile'];
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/profile-input.css">
    <title>Document</title>
</head>

<body>
    <?php
    require 'header.php';
    ?>
    <center>
        <table class="profile-input-form">
            <tr>
                <th colspan="2" class="h1-pro">プロフィール</th>
            </tr>

            <tr>
                <td colspan="2" align="center">
                    <?php
                    $icon_file = "pic/icon/{$student_id_con}.jpg";
                    if (file_exists($icon_file)) {
                        echo '<img id="profile_img" src="' . $icon_file . '" alt="アイコン">';
                    } else {
                        echo '<img id="profile_img" src="pic/icon/guest.jpg" alt="デフォルトアイコン">';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>学籍番号</th>
                <td><?php echo $student_id_con; ?></td>
            </tr>
            <tr>
                <th>ユーザー名</th>
                <td><?php echo $user_name; ?></td>
            </tr>

            <tr>
                <th colspan="2" align="center">説明文</th>
            </tr>
            <tr>
                <td colspan="2" align="center"><?php
                if (isset($user_profile)) {
                    echo nl2br($user_profile);
                } else {
                    echo '説明文がありません';
                }
                ?>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <button onclick="history.back();">戻る</button>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>