<?php
session_start();
require 'db-connect.php';

unset($student_id_error);
unset($user_name_error);
unset($password_error);
unset($password_con_error);
unset($tmp_icon_error);
unset($_SESSION['sign_up']);
unset($_SESSION['sign_up_icon']);
unset($file);
$judg = 0;
if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $password_con = $_POST['password_con'];
    $sql = $pdo->prepare('SELECT * FROM User WHERE student_id=?');
    $sql->execute([$student_id]);
    if (!($sql->rowCount() === 0)) {
        $student_id_error = '既に登録されています';
        $judg = 1;
    }
    if (!($password === $password_con)) {
        $password_error = '確認のパスワードが一致しません';
        $judg = 1;
    }
    if ($judg === 0) {
        $_SESSION['sign_up'] = [
            'student_id' => $_POST['student_id'],
            'user_name' => $_POST['user_name'],
            'password' => $_POST['password'],
            'school_id' => $_POST['school']
        ];
        if (is_uploaded_file($_FILES['tmp_icon']['tmp_name'])) {
            $destination_directory = 'pic/tmp_icon/';
            $delete_icon = 'pic/tmp_icon/' . $student_id;
            if (file_exists($delete_icon)) {
                unlink($delete_icon);
            }
            if (!file_exists($destination_directory)) {
                mkdir($destination_directory, 0755, true);
            }
            $file = $destination_directory . $student_id . '.jpg';
            if (move_uploaded_file($_FILES['tmp_icon']['tmp_name'], $file)) {
                $_SESSION['sign_up_icon'] = ['tmp_icon' => $file];
                $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/sign-up-con.php';
                header("Location: $redirect_url");
                exit();
            } else {
                $tmp_icon_error = 'アップロードに失敗しました';
            }
        } else {
            $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/sign-up-con.php';
            header("Location: $redirect_url");
            exit();
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
        <form action="sign-up.php" method="post" enctype="multipart/form-data">
            <table class="sign-up-form">
                <tr>
                    <?php
                    if (isset($_POST['student_id_con'])) {
                        echo '<td><input type="text" name="student_id" class="sign-up-textbox" placeholder="学籍番号"
                        pattern="^[0-9]{7}$" maxlength="7" value="', $_POST['student_id_con'], '" required></td>';
                    } else if (isset($student_id)) {
                        echo '<td><input type="text" name="student_id" class="sign-up-textbox" placeholder="学籍番号"
                        pattern="^[0-9]{7}$" maxlength="7" value="', $student_id, '" required></td>';
                    } else {
                        echo '<td><input type="text" name="student_id" class="sign-up-textbox" placeholder="学籍番号"
                        pattern="^[0-9]{7}$" maxlength="7" required></td>';
                    }
                    ?>
                </tr>
                <?php if (isset($student_id_error)): ?>
                <tr>
                    <td colspan="2"><span class="error"><?php echo $student_id_error; ?></span></td>
                </tr>
                <?php unset($student_id_error); endif; ?>
                <tr>
                    <?php
                    if (isset($_POST['user_name_con'])) {
                        echo '<td><input type="text" name="user_name" class="sign-up-textbox" placeholder="ユーザー名" maxlength="10"
                        required value="', $_POST['user_name_con'], '"></td>';
                    } else if (isset($user_name)) {
                        echo '<td><input type="text" name="user_name" class="sign-up-textbox" placeholder="ユーザー名" maxlength="10"
                        required value="', $user_name, '"></td>';
                    } else {
                        echo '<td><input type="text" name="user_name" class="sign-up-textbox" placeholder="ユーザー名" maxlength="10"
                            required></td>';
                    }
                    ?>
                </tr>
                <tr>
                    <td>
                        <select name="school" class="sign-up-select" required>
                            <?php
                            $pdo = new PDO($connect, USER, PASS);
                            if (isset($_POST['school_id_con'])) {
                                foreach ($pdo->query('select * from School') as $row) {
                                    if ($row['School_id'] == $_POST['school_id_con']) {
                                        echo '<option value=', $row['School_id'], ' selected>', $row['School_name'], '</option>';
                                    } else {
                                        echo '<option value=', $row['School_id'], '>', $row['School_name'], '</option>';
                                    }
                                }
                            } else {
                                foreach ($pdo->query('select * from School') as $row) {
                                    echo '<option value=', $row['School_id'], '>', $row['School_name'], '</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <?php if (isset($user_name_error)): ?>
                <tr>
                    <td colspan="2"><span class="error"><?php echo $user_name_error; ?></span></td>
                </tr>
                <?php unset($user_name_error); endif; ?>
                <tr>
                    <?php
                    if (isset($_POST['password_con'])) {
                        echo '<td><input type="password" name="password" class="sign-up-textbox" placeholder="パスワード"
                        maxlength="10" value="', $_POST['password_con'], '" required></td>';
                    } else if (isset($password)) {
                        echo '<td><input type="password" name="password" class="sign-up-textbox" placeholder="パスワード"
                        maxlength="10" value="', $password, '" required></td>';
                    } else {
                        echo '<td><input type="password" name="password" class="sign-up-textbox" placeholder="パスワード"
                            maxlength="10" required></td>';
                    }
                    ?>
                </tr>
                <?php if (isset($password_error)): ?>
                <tr>
                    <td colspan="2"><span class="error"><?php echo $password_error; ?></span></td>
                </tr>
                <?php unset($password_error); endif; ?>
                <tr>
                    <td><input type="password" name="password_con" class="sign-up-textbox" placeholder="パスワード確認用"
                            maxlength="10" required></td>
                </tr>
                <?php if (isset($password_con_error)): ?>
                <tr>
                    <td colspan="2"><span class="error"><?php echo $password_con_error; ?></span></td>
                </tr>
                <?php unset($password_con_error); endif; ?>
                <tr>
                    <td align="center"><span>アイコン選択</span></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="file" id="imageInput" name="tmp_icon" accept=".jpg, .jpeg, .png" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <div>
                            <img id="preview" src="" alt="画像プレビュー">
                        </div>
                    </td>
                </tr>
                <?php if (isset($tmp_icon_error)): ?>
                <tr>
                    <td colspan="2"><span class="error"><?php echo $tmp_icon_error; ?></span></td>
                </tr>
                <?php unset($tmp_icon_error); endif; ?>
            </table>
            <p>
                <button type="submit" class="signup-button">確認</button>
        </form>
    </p>
    <p>
        <form action="top.php" method="post">
            <button type="submit" class="signup-button">戻る</button>
        </form>
    </p>
    <script>
        document.getElementById('imageInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>
