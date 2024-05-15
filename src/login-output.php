<?php
session_start();
require "db-connect.php";

unset($_SESSION['user']);
$student_id = $_POST['student_id'];
$pass = $_POST['pass'];
$sql = $pdo->prepare('select * from User where student_id=?');
$sql->execute([$student_id]);
if ($sql->rowCount() > 0) {
    $row = $sql->fetch();
    $user_password = $row['user_password'];
    if (password_verify($pass, $user_password)) {
        $_SESSION['user'] = [
            'student_id' => $row['student_id'],
            'user_name' => $row['user_name'],
            'password' => $row['user_password']
        ];
        $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/home-login.php';
        header("Location: $redirect_url");
        exit();
    } else {
        echo '<html>
        <head>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    </head>
    <body>';
        echo ' <center><form action="login-input.php" method="post"> <div class="error">学籍番号または、パスワードが間違えています。</div>';
        echo '<a href="login-input.php" class="button">戻る</a>';
    }
} else {
    echo '<html>
    <head>
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>';
    echo ' <center>
    <form action="login-input.php" method="post"> <div class="error">学籍番号または、パスワードが間違えています。</div>';
    echo '<a href="login-input.php" class="button">戻る</a>';
}
?>


