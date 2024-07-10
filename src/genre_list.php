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
    <title>ASO PORTAL | official</title>
    <link rel="stylesheet" href="css/genre_list.css">
</head>

<body>
    <?php require 'header.php'; ?>
    <main>
        <div class="main">
            <h2>カテゴリ一覧</h2>
            <ul class="cp_list">
                <?php
                $sql = $pdo->prepare('SELECT * FROM Ganre');
                $sql->execute();
                foreach ($sql as $row) {
                    echo '<li><a href="Genre.php?id=' . intval($row["genre_id"]) . '">' . htmlspecialchars($row["genre_name"]) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </main>
</body>

</html>


