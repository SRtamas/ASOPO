<?php
session_start();
require 'db-connect.php';
?>
<?php
if (isset($_SESSION['sign_up'])) {
    $student_id = $_SESSION['sign_up']['student_id'];
    $user_name = $_SESSION['sign_up']['user_name'];
    $password = $_SESSION['sign_up']['password'];
    $school_id = $_SESSION['sign_up']['school_id'];
    if (isset($_SESSION['sign_up_icon']['tmp_icon'])) {
        $tmp_icon = $_SESSION['sign_up_icon']['tmp_icon'];
    }
} else {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/sign-up.php';
    header("Location: $redirect_url");
    exit();
}
if (isset($_POST['juge'])) {
    $sql = $pdo->prepare('SELECT * FROM User WHERE student_id=?');
    $sql->execute([$student_id]);
    if (!($sql->rowCount() === 0)) {
        $error = '既に登録されている学籍番号です';
    } else {
        $tmp_icon_path = 'pic/tmp_icon/' . $student_id . '.jpg';
        $icon_path = 'pic/icon/' . $student_id . '.jpg';
        if (isset($_SESSION['sign_up_icon']['tmp_icon']) && rename($tmp_icon_path, $icon_path)) {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql_insert = $pdo->prepare('INSERT INTO User (student_id,user_name,School_id,user_password) VALUES(?,?,?,?)');
            $sql_insert->execute([
                $student_id,
                $user_name,
                $school_id,
                $pass_hash
            ]);
            $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/sign-up-fin.php';
            header("Location: $redirect_url");
            exit();
        } else {
            $error = '登録でエラーが起きましたa';
        }
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql_insert = $pdo->prepare('INSERT INTO User (student_id,user_name,School_id,user_password) VALUES(?,?,?,?)');
        $sql_insert->execute([
            $student_id,
            $user_name,
            $school_id,
            $pass_hash
        ]);
        $sql = $pdo->prepare('SELECT * FROM User WHERE student_id=?');
        $sql->execute([$student_id]);
        if (!($sql->rowCount() === 0)) {
            $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/sign-up-fin.php';
            header("Location: $redirect_url");
            exit();
        } else {
            $error = '登録でエラーが起きましたb';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/sign-up.css">
    <title>ASO PORTAL　|　official</title>
</head>

<body>
    <center>
        <img id="topikon" src="img/5.png" class="show">
        <h2 class="title">アカウント新規作成</h2>
        <table class="sign-up-con-form">
            <tr>
                <td><span>学籍番号</span></td>
                <?php echo '<td><span>', $student_id, '</span></td>' ?>
            </tr>
            <tr>
                <td><span>ユーザー名</span></td>
                <?php echo '<td><span>', $user_name, '</span></td>' ?>
            </tr>
            <tr>
                <?php
                $sql = $pdo->prepare('SELECT * FROM School WHERE School_id=?');
                $sql->execute([$school_id]);
                foreach ($sql as $row) {
                    echo '<td colspan="2"><spam>', $row['School_name'], '</spam></td>';
                }
                ?>
            </tr>
            <tr>
                <td><span>パスワード</span></td>
                <?php echo '<td><span>', $password, '</span></td>' ?>
            </tr>
            <!-- <tr>
                <td colspan="2" style="text-align: center;"><spna>アイコン</spna></td>
            </tr> -->
            <tr>
                <?php
                if (isset($tmp_icon)) {
                    echo '<td colspan="2" style="text-align: center;"><img class="icon" alt="' . $student_id . '" src="' . $tmp_icon . '"/></td>';
                }else{
                    echo '<td colspan="2" style="text-align: center;"><img class="icon" alt="guest.jpg" src="pic/icon/guest.jpg"/></td>';
                }
                ?>
            </tr>
            <?php
            if (isset($error)) {
                echo '<tr>';
                echo '<td colspan="2"><span>', $error, '</span></td>';
                echo '</tr>';
                unset($error);
            }
            ?>
        </table>
        <form action="sign-up-con.php" method="post">
            <button type="submit" class="signup-button">作成</button>
            <input type="hidden" name="juge">
        </form>
        <br>
        <form action="sign-up.php" method="post">
            <?php
            echo '<input type="hidden" name="student_id_con" value="', $student_id, '">';
            echo '<input type="hidden" name="user_name_con" value="', $user_name, '">';
            echo '<input type="hidden" name="school_id_con" value="', $school_id, '">';
            echo '<input type="hidden" name="password_con" value="', $password, '">';
            ?>
            <button type="submit" class="signup-button">戻る</button>
        </form>
    </center>
</body>

</html>