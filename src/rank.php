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

<>
<?php

require 'header.php';
echo '<main>';
$sql = $pdo->prepare('SELECT board_id, COUNT(*) AS count FROM Post GROUP BY board_id ORDER BY count DESC');
$sql->execute();
$boards = $sql->fetchAll(PDO::FETCH_ASSOC);

$count = 1;
foreach($boards as $board){
    $boardId = $board['board_id'];
    $postCount = $board['count'];

    $sql2 = $pdo->prepare('SELECT post_content FROM Post WHERE board_id = ? ORDER BY post_date DESC LIMIT 1');
    $sql2->execute([$boardId]);
    $latestPost = $sql2->fetch(PDO::FETCH_ASSOC);
    $latestPostContent = $latestPost !== false ? htmlspecialchars($latestPost['post_content']) : '投稿がありません';

    $sql3 = $pdo->prepare('SELECT student_id FROM Post WHERE board_id = ?');
    $sql3->execute([$boardId]);
    $posterId = $sql3->fetch(PDO::FETCH_ASSOC);

    $sql4 = $pdo->prepare('SELECT user_name FROM User WHERE student_id = ?');
    $sql4->execute([$posterId['student_id']]);
    $poster = $sql4->fetch(PDO::FETCH_ASSOC);

    $sql5 = $pdo->prepare('SELECT board_name FROM Board WHERE board_id = ?');
    $sql5->execute([$boardId]);
    $Board = $sql5->fetch(PDO::FETCH_ASSOC);

    echo '<div>';
    echo $count,'位';
    echo '<p>投稿数: ' . htmlspecialchars($postCount) . '</p>';
    echo '<p>タイトル: ' . htmlspecialchars($Board['board_name']) . '</p>';
    echo '<p>投稿者: ' . ($poster ? htmlspecialchars($poster['user_name']) : '不明') . '</p>';
    echo '<p>最新の投稿: ' . $latestPostContent . '</p>';
    echo '<form action="thread.php?id=' . intval($boardId) . '" method="post">';
    echo '<button>参加する</button></form>';
    echo '</div>';
    $count += 1;
}
echo '</main>';
?>

</body>
</html>

