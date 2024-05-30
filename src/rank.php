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
    <title>ランキング</title>
    <link rel="stylesheet" href="css/rank.css">
</head>
<body>
<?php
require 'header.php';
?>
<main>
    <div class="main">
        <div class="main_header">ランキング</div>
        <?php
        $sql = $pdo->prepare('SELECT board_id, COUNT(*) AS count FROM Post GROUP BY board_id ORDER BY count DESC');
        $sql->execute();
        $boards = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>順位</th>';
        echo '<th>タイトル</th>';
        echo '<th>ジャンル</th>';
        echo '<th>作成者</th>';
        echo '<th>投稿数</th>';
        echo '<th>最新の投稿</th>';
        echo '<th></th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        $count = 1;
        foreach ($boards as $board) {
            if($count > 10) {
                break;
            }
            $boardId = $board['board_id'];
            $postCount = $board['count'];

            $sql2 = $pdo->prepare('SELECT post_content, post_pic FROM Post WHERE board_id = ? ORDER BY post_date DESC LIMIT 1');
            $sql2->execute([$boardId]);
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

            $sql3 = $pdo->prepare('SELECT * FROM Post WHERE board_id = ?');
            $sql3->execute([$boardId]);
            $posterId = $sql3->fetch(PDO::FETCH_ASSOC);

            $sql4 = $pdo->prepare('SELECT * FROM User WHERE student_id = ?');
            $sql4->execute([$posterId['student_id']]);
            $poster = $sql4->fetch(PDO::FETCH_ASSOC);

            $sql5 = $pdo->prepare('SELECT * FROM Board WHERE board_id = ?');
            $sql5->execute([$boardId]);
            $Board = $sql5->fetch(PDO::FETCH_ASSOC);
            $genre_id = $Board['genre_id'];

            $sql6 = $pdo->prepare('SELECT * FROM Ganre WHERE genre_id = ?');
            $sql6->execute([$genre_id]);
            $genre = $sql6->fetch(PDO::FETCH_ASSOC);

            $pass_dis = isset($Board['board_password']) ? '<span class="locked">🔒</span>' : '';

            echo '<tr>';
            echo '<td>' . $count;
            if ($count == 1) {
                echo '位 <span class="medal">🥇</span>';
            } elseif ($count == 2) {
                echo '位 <span class="medal">🥈</span>';
            } elseif ($count == 3) {
                echo '位 <span class="medal">🥉</span>';
            } else {
                echo '位';
            }
            echo '</td>';
            echo '<td>' . htmlspecialchars($Board['board_name']) . '</td>';
            echo '<td>' . htmlspecialchars($genre['genre_name']) . '</td>';
            echo '<td>' . ($poster ? htmlspecialchars($poster['user_name']) : '不明') . '</td>';
            echo '<td>' . htmlspecialchars($postCount) . '</td>';
            echo '<td>' . $latestPostContent . '</td>';
            echo '<td>';
            echo '<form action="thread.php?id=' . intval($boardId) . '" method="post">';
            echo '<button class="button">参加する</button>';
            echo '</form>';
            echo $pass_dis;
            echo '</td>';
            echo '</tr>';

            $count += 1;
        }

        echo '</tbody>';
        echo '</table>';
        ?>
    </div>
</main>
</body>
</html>
