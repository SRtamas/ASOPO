<?php
session_start();
require 'db-connect.php';
?>
<?php
if (!(isset($_SESSION['sign_up']))) {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/sign-up.php';
    header("Location: $redirect_url");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<script>
    // ブラウザの戻るボタンを無効にする
    window.history.pushState(null, null, window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, null, window.location.href);
    };
</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <center>
        <p>
            <img id="topikon" src="img/5.png" class="show">
        </p>
        <h3>
            <p>登録が完了しました<br>ASOPOをお楽しみください！！
        </h3>
        </p>
        <a href="login-input.php">
            <h3>ログインへ</h3>
        </a>

    </center>
</body>

</html>