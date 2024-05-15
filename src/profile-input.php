<?php
session_start();
require 'db-connect.php';
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
        <h1>プロフィール</h1>
        <?php
        $student_id = $_SESSION['user']['student_id'];
        $user_name = $_SESSION['user']['user_name'];
        $sql = $pdo->prepare('SELECT * FROM User WHERE student_id=?');
        $sql->execute([$student_id]);
        foreach ($sql as $row) {
            $user_profile = $row['user_profile'];
        }
        ?>
        <table class="profile-input-form">
            <tr>
                <td colspan="2" align="center">
                    <?php
                    $icon_file = "pic/icon/{$student_id}.jpg";
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
                <td><?php echo $student_id; ?></td>
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
                    echo $user_profile;
                } else {
                    echo '説明文がありません';
                }
                ?></td>
            </tr>
        </table>
        <div class="button-container">

    <form action="home-login.php" method="post">
        <button type="submit" class="backhome-button">戻る</button>
    </form>
    
    <form action="profile-output.php" method="post">
        <button type="submit" class="signup-button">⇒プロフィール編集画面へ</button>
    </form>
    <form action="logout-input.php" method="post">
        <button type="submit" class="logout-button">ログアウト</button>
    </form>    
   
</div>
        
        <script src="js/login_top.js"></script>
</body>

</html>