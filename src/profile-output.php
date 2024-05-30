<?php
session_start();
require "db-connect.php";
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile-output.css"> 
    <title>プロフィール編集</title>
</head>
<body>
    <?php
        require 'header.php'
    ?>

    <center>
    
    <?php
    $pdo = new PDO($connect, USER, PASS);

    
    $student_id = $_SESSION['user']['student_id'];
    $user_name = $_SESSION['user']['user_name'];
    $sql = $pdo->prepare('SELECT * FROM User WHERE student_id=?');
    $sql->execute([$student_id]);
    foreach ($sql as $row) {
        echo '<form action="profile-fin.php?student_id=' , $student_id , '" method="post" class = "profile-from" enctype="multipart/form-data">';
        echo '<table>';
        echo '<h1 class = "profileout-h1">アカウント変更</h1>';

        
        // アイコンファイルパスを指定
        $icon_file = "pic/icon/{$student_id}.jpg";
        
        // アイコンが存在する場合は表示
        if (file_exists($icon_file)) {
            echo '<tr>';
            echo '<td align=center class = "icon-out"><h3>アイコン</h3></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td align="center" class = "icon-out"><img id="icon" src="' . $icon_file . '" alt="アイコン"></td>';
            echo '</tr>';
        } else {
            echo '<tr>';
            echo '<td align="center" class = "icon-out"><img id="icon" src="pic/icon/guest.jpg" alt="デフォルトアイコン"></td>';
            echo '</tr>';
        }
        
        // アイコン選択フォーム
        echo '<tr>';
        echo '<td align=center class = "icon-out"><span>アイコン選択</span></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td align="center" class = "icon-out"><input type="file" name="tmp_icon" class = "icon-choose" accept=".jpg, .jpeg, .png" /></td>';
        echo '</tr>';
        //デフォルトアイコンを選択するフォーム
        echo '<tr><th>学籍番号</th>';
        echo '<td>' .$student_id. '</td></tr>';
        echo '<tr><th>名前</th>';
        echo '<td><input type="text" name="user_name" class = "name-text" value="' , $row['user_name'] ,'" required></td>';
        echo '</tr>';
        if (!empty($row['user_profile'])) {
            echo '<tr><th colspan="2">説明分</th></tr>';
            echo '<tr><td colspan="2"><textarea class = "user_profile" name="user_profile" cols="50" rows="5" placeholder="説明分を入力">' . $row['user_profile'] . '</textarea></td></tr>';
        } else {
            echo '<tr><th colspan="2">説明分がありません</th></tr>';
            echo '<tr><td colspan="2"><textarea class = "user_profile" name="user_profile" cols="50" rows="5" placeholder="説明分を入力"></textarea></td></tr>';
        }
        echo '</table>';
        echo '<tr><td>';
        echo '<div class = button-all><a href="profile-input" class="back-button">戻る</a>';
        echo '<button type="submit" class = "profile-button" >変更</button>';
        echo '<div></td></tr>';
        echo '</form>';
    }
    ?>
    </center>
    <script src="js/login_top.js"></script>
</body>

</html>
