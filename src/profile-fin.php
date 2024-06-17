<?php
session_start();
require "db-connect.php";

if (empty($_SESSION['user'])) {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
    header("Location: $redirect_url");
    exit();
}

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                    // 成功した場合のメッセージ
                    $_SESSION['message'] = 'プロフィールが編集されました。';
                } else {
                    $_SESSION['message'] = '画像の保存に失敗しました。';
                }
            } else {
                $_SESSION['message'] = 'プロフィールが編集されましたが、画像のアップロードはありませんでした。';
            }
        } elseif ($_POST['action'] == 'delete_icon') {
            $destination_directory = 'pic/icon/';
            $student_id = $_GET['student_id'];
            $file = $destination_directory . $student_id . '.jpg';

            // 既存のアイコンがあれば削除
            if (file_exists($file)) {
                unlink($file);
                $_SESSION['message'] = 'アイコンが削除されました。デフォルトアイコンが表示されます。';
            } else {
                $_SESSION['message'] = 'アイコンが存在しません。デフォルトアイコンが表示されます。';
            }
        }

        $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/profile-input.php';
        header("Location: $redirect_url");
        exit();
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>

