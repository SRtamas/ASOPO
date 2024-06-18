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
            <br>
            <?php
            $sql = $pdo->prepare('SELECT board_id, COUNT(*) AS count FROM Post GROUP BY board_id ORDER BY count DESC');
            $sql->execute();
            $boards = $sql->fetchAll(PDO::FETCH_ASSOC);

            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th colspan="7">投稿数ランキング</th>';
            echo '<tr>';
            echo '<th style="text-align: center">順位</th>';
            echo '<th style="text-align: center">タイトル</th>';
            echo '<th style="text-align: center">ジャンル</th>';
            echo '<th style="text-align: center">作成者</th>';
            echo '<th style="text-align: center">投稿数</th>';
            echo '<th style="text-align: center">最新の投稿</th>';
            echo '<th></th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            $count = 1;
            foreach ($boards as $board) {
                if ($count > 3) {
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
                        $latestPostContent = '画像の投稿 📷';
                    } elseif ($latestPost['post_pic'] == 2) {
                        $latestPostContent = '動画の投稿 🎥';
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
                if (isset($Board['board_password'])) {
                    $latestPostContent = "パスワードが必要";
                }


                echo '<tr>';
                echo '<td style="text-align: center">';
                if ($count == 1) {
                    echo '<span class="medal">🥇</span>';
                } elseif ($count == 2) {
                    echo '<span class="medal">🥈</span>';
                } elseif ($count == 3) {
                    echo '<span class="medal">🥉</span>';
                } else {
                    echo $count . '位';
                }
                echo '</td>';


                echo '<td class="truncate" style="text-align: center">' . htmlspecialchars(mb_strimwidth($Board['board_name'], 0, 15, '...')) . '</td>';
                echo '<td class="truncate" style="text-align: center">' . htmlspecialchars($genre['genre_name']) . '</td>';
                echo '<td class="truncate" style="text-align: center">' . ($poster ? htmlspecialchars($poster['user_name']) : '不明') . '</td>';
                echo '<td>' . htmlspecialchars($postCount) . '</td>';
                echo '<td class="truncate" style="text-align: center">' . mb_strimwidth($latestPostContent, 0, 20, '...') . '</td>';
                echo '<td>';
                echo '<form action="thread.php?id=' . intval($boardId) . '" method="post">';
                echo '<button class="button">参加</button>';
                echo '</form>';
                echo $pass_dis;
                echo '</td>';
                echo '</tr>';

                $count += 1;
            }
            echo '</tbody>';
            echo '</table>';
            ?>
            <br>
            <?php
            $genre_num_sql = $pdo->query('SELECT genre_id, COUNT(*) AS total_posts
            FROM Board
            GROUP BY genre_id
            ORDER BY total_posts DESC;
            ');
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th colspan="4">人気ジャンルランキング</th>';
            echo '<tr>';
            echo '<th style="text-align: center">順位</th>';
            echo '<th style="text-align: center">ジャンル</th>';
            echo '<th style="text-align: center">掲示板数</th>';
            echo '<th></th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $count = 1;
            foreach ($genre_num_sql as $genre_num_row) {
                if ($count > 3) {
                    break;
                }
                $genre_num_id = $genre_num_row['genre_id'];
                $genre_sql = $pdo->prepare('SELECT * FROM Ganre where genre_id = ?');
                $genre_sql->execute([$genre_num_id]);
                foreach ($genre_sql as $genre_row) {
                    echo '<tr>';
                    echo '<td  style="text-align: center">';
                    if ($count == 1) {
                        echo '<span class="medal">🥇</span>';
                    } elseif ($count == 2) {
                        echo '<span class="medal">🥈</span>';
                    } elseif ($count == 3) {
                        echo '<span class="medal">🥉</span>';
                    } else {
                        echo $count . '位';
                    }
                    echo '</td>';
                    echo '<td class="truncate"  style="text-align: center">' . $genre_row['genre_name'] . '</td>';
                    echo '<td class="truncate"  style="text-align: center">' . $genre_num_row['total_posts'] . '</td>';
                    echo '<td style="text-align: center">';
                    echo '<form action="Genre.php?id=', $genre_row['genre_id'], '" method="post">';
                    echo '<button class="button">参加</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';

                    $count += 1;
                }
            }

            echo '</tbody>';
            echo '</table>';
            ?>
        </div>
    </main>
</body>

</html>