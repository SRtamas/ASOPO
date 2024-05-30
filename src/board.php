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
    <link rel="stylesheet" href="css/board2.css">
    <title>参加ボード</title>
</head>

<body>
    <?php
    require 'header.php';
    ?>
    <main>
        <div class="main">
            <div class="main_header">参加中のスレッド一覧</div>
            <?php
            if (isset($_SESSION['user']) && isset($_SESSION['user']['student_id'])) {
                $student_id = $_SESSION['user']['student_id'];
                $sql = $pdo->prepare('SELECT board_id, COUNT(*) AS count FROM Post WHERE student_id = ? GROUP BY board_id;');
                $sql->execute([$student_id]);
                $boards = $sql->fetchAll(PDO::FETCH_ASSOC);
                if ($sql->rowCount() > 0) {
                    foreach ($boards as $row) {
                        $id = $row['board_id'];

                        $sql2 = $pdo->prepare('SELECT * FROM Post WHERE board_id = ? ORDER BY post_date DESC LIMIT 1');
                        $sql2->execute([$id]);
                        $latestPost = $sql2->fetch(PDO::FETCH_ASSOC);

                        $latestPostContent = '投稿がありません';
                        if ($latestPost !== false) {
                            if ($latestPost['post_pic'] == 1) {
                                $latestPostContent = '画像の投稿 <span class="icon">📷</span>';
                            } elseif ($latestPost['post_pic'] == 2) {
                                $latestPostContent = '動画の投稿 <span class="icon">🎥</span>';
                            } else {
                                $latestPostContent = htmlspecialchars($latestPost['post_content']);
                            }
                        }
                       
                        $sql3 = $pdo->prepare('SELECT * FROM Board WHERE board_id = ? ');
                        $sql3->execute([$id]);
                        $row3 = $sql3->fetch(PDO::FETCH_ASSOC);
                        $stu_id = $row3['student_id'];
                        $name = $row3['board_name'];

                        $sql4 = $pdo->prepare('SELECT * FROM User WHERE student_id = ? ');
                        $sql4->execute([$stu_id]);
                        $row4 = $sql4->fetch(PDO::FETCH_ASSOC);

                        $pass_dis = isset($row3['board_password']) ? '<span class="locked">🔒</span>' : '';

                        echo '<div class="board">';
                        echo '<h3 class="board-title">' . htmlspecialchars($name) . '</h3>';
                        echo '<p class="board-detail">投稿者: ' . htmlspecialchars($row4['user_name']) . '</p>';
                        echo '<p class="board-detail">最新の投稿: ' . $latestPostContent . '</p>';
                        echo '<form action="thread.php?id=' . intval($id) . '" method="post">';
                        echo '<button type="submit" class="button">参加する</button>';
                        echo '</form>';
                        echo $pass_dis;
                        echo '</div>';
                    }
                } else {
                    echo '<h3 class="not-found">掲示板が見つかりません</h3>';
                }
            } else {
                echo '<p class="error">ユーザー情報が見つかりません</p>';
            }
            ?>
        </div>
    </main>
</body>

</html>
