<?php
session_start();
require 'db-connect.php';
require 'header.php';
?>

<?php
if (isset($_GET['id'])) {
    $genre = intval($_GET['id']);
    $sql = $pdo->prepare('SELECT * FROM Board WHERE genre_id = ?');
    $sql->execute([$genre]);

    if ($sql->rowCount() > 0) {
        foreach ($sql as $row) {
            $id = $row['board_id'];
            $stu_id = $row['student_id'];
            $name = $row['board_name'];

            $sql2 = $pdo->prepare('SELECT post_content FROM Post WHERE board_id = ? ORDER BY post_date DESC LIMIT 1');
            $sql2->execute([$id]);
            $row2 = $sql2->fetch(PDO::FETCH_ASSOC);
            $content = ($sql2->rowCount() > 0) ? $row2['post_content'] : '投稿がありません';

            $sql3 = $pdo->prepare('SELECT user_name FROM User WHERE student_id = ?');
            $sql3->execute([$stu_id]);
            $row3 = $sql3->fetch(PDO::FETCH_ASSOC);

            echo '<div>';
            echo '<h3>' . $name . '</h3>';
            echo '<p>投稿者: ' . $row3['user_name'] . '</p>';
            echo '<p>最新の投稿: ' . $content . '</p>';
            echo '<form action="thread.php?id=' . $id . '" method="post">';
            echo '<button>参加する</button></form>';
            echo '</div>';
        }
    } else {
        echo '<h3 class="not-search">掲示板が見つかりません</h3>';
    }
}
?>
