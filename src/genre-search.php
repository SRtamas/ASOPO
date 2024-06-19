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
    <title>ジャンル</title>
    <link rel="stylesheet" href="css/genre-search.css">
</head>

<body>
    <?php
    require 'header.php';
    ?>
    <main>
        <div class="main">
            <br>
            <?php
            if (isset($_GET['id'])) {
                $genre = intval($_GET['id']);
                $search = isset($_POST['board_search']) ? $_POST['board_search'] : '';

                if ($search) {
                    echo '<div class="search-result-header">【' . htmlspecialchars($search) . '】の検索結果</div>';
                }
                
                $sql = $pdo->prepare('SELECT * FROM Board WHERE board_name LIKE ? AND genre_id = ?');
                $sql->execute(["%$search%", $genre]);

                $sql4 = $pdo->prepare('SELECT genre_name FROM Ganre WHERE genre_id = ?');
                $sql4->execute([$genre]);
                $row4 = $sql4->fetch(PDO::FETCH_ASSOC);
                $genre_name = $row4['genre_name'];
                echo '<div class="main_header">' . htmlspecialchars($genre_name) . '</div><br>';
                echo '<form class="search-form" action="genre-search.php?id=' . $genre . '" method="post">
                    <input type="hidden" name="genre_id" value="' . $genre . '">
                    <input type="text" id="board_search" name="board_search" value="' . htmlspecialchars($search) . '" placeholder="掲示板を検索">
                    <button type="submit" class="search-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                    </button>
                </form>';

                if ($sql->rowCount() > 0) {
                    foreach ($sql as $row) {
                        $id = $row['board_id'];
                        $stu_id = $row['student_id'];
                        $name = $row['board_name'];

                        $sql2 = $pdo->prepare('SELECT post_content, post_pic FROM Post WHERE board_id = ? ORDER BY post_date DESC LIMIT 1');
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

                        $sql3 = $pdo->prepare('SELECT user_name FROM User WHERE student_id = ?');
                        $sql3->execute([$stu_id]);
                        $row3 = $sql3->fetch(PDO::FETCH_ASSOC);

                        $pass_dis = isset($row['board_password']) ? '🔒' : '';

                        echo '<div class="board-card">';
                        echo '<h3 class="board-title">' . htmlspecialchars($name) . '</h3>';
                        echo '<p class="board-info">投稿者: ' . htmlspecialchars($row3['user_name']) . '</p>';
                        echo '<p class="board-info">最新の投稿: ' . $latestPostContent . '</p>';
                        echo '<form action="thread.php?id=' . intval($id) . '" method="post">';
                        echo '<button type="submit" class="join-button">参加する</button>';
                        echo '</form>' . $pass_dis;
                        echo '</div>';
                    }
                } else {
                    echo '<h3 class="not-found">掲示板が見つかりません</h3>';
                }
            } else {
                echo '<h3 class="error">ジャンルIDが指定されていません</h3>';
            }
            ?>
        </div>
    </main>
</body>

</html>


