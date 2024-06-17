<?php
session_start();
require 'db-connect.php';
if (empty($_SESSION['user'])) {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
    header("Location: $redirect_url");
    exit();
} else {
    $student_id = $_SESSION['user']['student_id'];
    $user_name = $_SESSION['user']['user_name'];
    $sql = $pdo->prepare('SELECT * FROM User WHERE student_id=?');
    $sql->execute([$student_id]);
    foreach ($sql as $row) {
        $user_profile = $row['user_profile'];
        $School_id = $row['School_id'];
    }
    $Schoolsql = $pdo->prepare('SELECT School_name FROM School where School_id = ?');
    $Schoolsql->execute([$School_id]);
    foreach ($Schoolsql as $row2) {
        $school_name = $row2['School_name'];
    }

}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/profile-input.css">
    <title>プロフィール</title>
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
                <th>所属学校</th>
                <td><?php $school_name_kai = str_replace(' ', "\n", $school_name);
                echo nl2br($school_name_kai); ?></td>
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
                <td colspan="2" align="center" class="button-td">
                    <div class="button-container">

                        <form action="home-login.php" method="post">
                            <button type="submit" class="backhome-button">戻る</button>
                        </form>

                        <form action="profile-output.php" method="post">
                            <button type="submit" class="signup-button">⇒プロフィール編集画面へ</button>
                        </form>
                    </div>
                </td>
            </tr>

        </table>


        <button id="logoutButton" class="logout-button">ログアウト</button>

        <div id="logoutModal" class="modal-logout">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>本当にログアウトしますか？</p>
                <form action="logout-output.php" method="post">
                    <button type="submit" class="confirm-button">はい</button>
                    <button type="button" class="cancel-button">いいえ</button>
                </form>
            </div>
        </div>

        <script>
            // ログアウトボタンをクリックしたときの処理
            const logoutButton = document.getElementById('logoutButton');
            const logoutModal = document.getElementById('logoutModal');
            const closeButton = document.querySelector('.close');
            const cancelButton = document.querySelector('.cancel-button');

            logoutButton.addEventListener('click', function () {
                logoutModal.style.display = 'block'; // モーダルを表示
            });

            closeButton.addEventListener('click', function () {
                logoutModal.style.display = 'none'; // モーダルを非表示
            });

            cancelButton.addEventListener('click', function () {
                logoutModal.style.display = 'none'; // モーダルを非表示
            });

            window.addEventListener('click', function (event) {
                if (event.target == logoutModal) {
                    logoutModal.style.display = 'none'; // モーダルを非表示
                }
            });
        </script>
</body>

</html>