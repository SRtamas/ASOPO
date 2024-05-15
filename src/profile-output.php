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

    <!-- <img id="topikon" src="img/5.png" class="show"> -->
    <center>
    <h1>アカウント変更</h1>
    
    <?php
    $pdo = new PDO($connect, USER, PASS);

    
    $student_id = $_SESSION['user']['student_id'];
    $user_name = $_SESSION['user']['user_name'];
    $sql = $pdo->prepare('SELECT * FROM User WHERE student_id=?');
    $sql->execute([$student_id]);
    foreach ($sql as $row) {
        echo '<form action="profile-fin.php?student_id=' , $student_id , '" method="post" enctype="multipart/form-data">';
        echo '<table>';
        
        // アイコンファイルパスを指定
        $icon_file = "pic/icon/{$student_id}.jpg";
        
        // アイコンが存在する場合は表示
        if (file_exists($icon_file)) {
            echo '<tr>';
            echo '<td align=center><span>現在のアイコン</span></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td align="center"><img id="icon" src="' . $icon_file . '" alt="アイコン"></td>';
            echo '</tr>';
        } else {
            echo '<tr>';
            echo '<td colspan="2" align="center"><img id="icon" src="pic/icon/guest.jpg" alt="デフォルトアイコン"></td>';
            echo '</tr>';
        }
        
        // アイコン選択フォーム
        echo '<tr>';
        echo '<td align=center><span>アイコン選択</span></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td align="center"><input type="file" name="tmp_icon" accept=".jpg, .jpeg, .png" /></td>';
        echo '</tr>';
        
        echo '<tr><td>名前<input type="text" name="user_name" value="' , $row['user_name'] ,'" required></td></tr>';
        if (!empty($row['user_profile'])) {
            echo '<tr><td>説明分<textarea class = "user_profile" name="user_profile" cols="50" rows="5">' . $row['user_profile'] . '</textarea></td></tr>';
        } else {
            echo '<tr><td>説明分がありません<textarea class = "user_profile" name="user_profile" cols="50" rows="5"></textarea></td></tr>';
        }
        echo '<tr><td><input type="submit" value="変更"></td></tr>';
        echo '</table>';
        echo '</form>';
    }
    ?>
    <form action="profile-input.php" method="post">
        <button type="submit" class="signup-button">戻る</button>
    </form>
    </center>
    <script src="js/login_top.js"></script>
</body>

</html>
