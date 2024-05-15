<?php
session_start();
require 'db-connect.php';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    require 'header.php';

    if (isset($_POST['board_search'])) {
        $search = $_POST['board_search'];
        $sql = $pdo->prepare('SELECT * FROM Board WHERE board_name LIKE ?');
        $sql->execute(["%$search%"]);
        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['board_id'];
                $stu_id = $row['student_id'];
                $name = $row['board_name'];
                $create = $row['board_createdate'];

                $sql2 = $pdo->prepare('SELECT post_content FROM Post WHERE board_id = ? ORDER BY post_date DESC LIMIT 1');
                $sql2->execute([$id]);
                $row2 = $sql2->fetch(PDO::FETCH_ASSOC);
                $content = ($sql2->rowCount() > 0) ? htmlspecialchars($row2['post_content']) : '投稿がありません';

                $sql3 = $pdo->prepare('SELECT user_name FROM User WHERE student_id = ?');
                $sql3->execute([$stu_id]);
                $row3 = $sql3->fetch(PDO::FETCH_ASSOC);

                echo '<div>';
                echo '<h3>' . htmlspecialchars($name) . '</h3>';
                echo '<p>投稿者: ' . htmlspecialchars($row3['user_name']) . '</p>';
                echo '<p>最新の投稿: ' . $content . '</p>';
                echo '<form action="thread.php?id=' . intval($row['board_id']) . '" method="post">';
                echo '<button>参加する</button></form>';
                echo '</div>';
            }
        } else {
            echo '<h3 class="not-search">掲示板が見つかりません</h3>';
        }
    }
    ?>
</body>

</html>
