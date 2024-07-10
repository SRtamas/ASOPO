<?php
session_start();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <title>ASO PORTAL　|　official</title>
</head>

<body>
    <center>
        <div class="login-all">
            <div class="login-back">
                <div class="login">
                    <br>
                    <img id="topikon" src="img/5.png" class="show">

                    <h2>ASOPOにログイン</h2>

                    <form action="login-output.php" method="post">

                        <p><input type="text" class="login-textbox" name="student_id" placeholder="学籍番号"
                                pattern="^[0-9]{7}$" maxlength="7" required></p>
                        <p><input type="password" class="login-textbox" name="pass" placeholder="パスワード" required></p>
                        <?php
                        if (!(empty($_SESSION['login']['error']))) {
                            echo '<p><span class="error">', $_SESSION['login']['error'], '</span></p>';
                        }
                        unset($_SESSION['login']['error']);
                        ?>

                        <p><input type="submit" class="login-button" value="ログイン"></p>
                    </form>

                </div>
            </div>
            <br>
            アカウントをお持ちでない方は
            <a href="sign-up.php">こちら</a>

        </div>
    </center>


</body>


</html>