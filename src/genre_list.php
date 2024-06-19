<?php
session_start();
require 'db-connect.php';
if (empty($_SESSION['user'])) {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
    header("Location: $redirect_url");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ジャンル一覧</title>
    <link rel="stylesheet" href="css/genre-list.css">
</head>

<body>
    <?php require 'header.php'; ?>
    <main>
        <div class="main">
            <h2>ジャンル一覧</h2>
            <div class="genre-list">
                <?php include 'genre-list-table.php'; ?>
            </div>
        </div>
    </main>
</body>

</html>

