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
        require 'header.php';
    ?>

    <center>
    
    <?php
    $pdo = new PDO($connect, USER, PASS);

    $student_id = $_SESSION['user']['student_id'];
    $user_name = $_SESSION['user']['user_name'];
    $sql = $pdo->prepare('SELECT * FROM User WHERE student_id=?');
    $sql->execute([$student_id]);
    foreach ($sql as $row) {
        echo '<form action="profile-fin.php?student_id=' , $student_id , '" method="post" class="profile-from" enctype="multipart/form-data">';
        echo '<table>';
        echo '<h1 class="profileout-h1">アカウント変更</h1>';

        // アイコンファイルパスを指定
        $icon_file = "pic/icon/{$student_id}.jpg";

        // アイコンが存在する場合は表示
        if (file_exists($icon_file)) {
            echo '<tr>';
            // echo '<td align="center" class="icon-out"><h3>アイコン</h3></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td align="center" class="icon-out"><img id="icon" src="' . $icon_file . '" alt="アイコン"></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td align="center" class="icon-out">';
            echo '<button id="deleteButton" type="button" class="delete-icon-button">アイコンを削除</button>';
            echo '</td>';
            echo '</tr>';
        } else {
            echo '<tr>';
            echo '<td align="center" class="icon-out"><img id="icon" src="pic/icon/guest.jpg" alt="デフォルトアイコン"></td>';
            echo '</tr>';
        }

        // アイコン選択フォーム
        echo '<tr>';
        // echo '<td align="center" class="icon-out"><span class = "icon-select">アイコン選択</span></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td align="center" class="icon-out"><input type="file" name="tmp_icon" class="icon-choose" accept=".jpg, .jpeg, .png" /></td>';
        echo '</tr>';

        echo '<tr><th>学籍番号</th>';
        echo '<td>' . $student_id . '</td></tr>';
        echo '<tr><th>名前</th>';
        echo '<td><input type="text" name="user_name" class="name-text" value="' . htmlspecialchars($row['user_name']) . '" required></td>';
        echo '</tr>';
        if (!empty($row['user_profile'])) {
            echo '<tr><th colspan="2">説明分</th></tr>';
            echo '<tr><td colspan="2"><textarea class="user_profile" name="user_profile" cols="50" rows="5" placeholder="説明分を入力">' . htmlspecialchars($row['user_profile']) . '</textarea></td></tr>';
        } else {
            echo '<tr><th colspan="2">説明分がありません</th></tr>';
            echo '<tr><td colspan="2"><textarea class="user_profile" name="user_profile" cols="50" rows="5" placeholder="説明分を入力"></textarea></td></tr>';
        }
        echo '</table>';
        echo '<tr><td>';
        echo '<div class="button-all"><a href="profile-input" class="back-button">戻る</a>';
        echo '<button type="submit" name="action" value="update_profile" class="profile-button">変更</button>';
        echo '</div></td></tr>';
        echo '</form>';
    }
    ?>
    </center>
    <div id="deleteModal" class="modal-delete">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>アイコンを削除しますか？</p>
            <form action="profile-fin.php?student_id=<?php echo $student_id; ?>" method="post">
                <button type="submit" name="action" value="delete_icon" class="confirm-button">はい</button>
                <button type="button" class="cancel-button">いいえ</button>
            </form>
        </div>
    </div>

    <script>
        const deleteButton = document.getElementById('deleteButton');
        const deleteModal = document.getElementById('deleteModal');
        const closeButton = document.querySelector('.close');
        const cancelButton = document.querySelector('.cancel-button');

        deleteButton.addEventListener('click', function () {
            deleteModal.style.display = 'block';
        });

        closeButton.addEventListener('click', function () {
            deleteModal.style.display = 'none';
        });

        cancelButton.addEventListener('click', function () {
            deleteModal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target == deleteModal) {
                deleteModal.style.display = 'none';
            }
        });
    </script>
    <script src="js/login_top.js"></script>
</body>

</html>
