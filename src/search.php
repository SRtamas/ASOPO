<?php
session_start();
require 'db-connect.php';
if (empty($_SESSION['user'])) {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
    header("Location: $redirect_url");
    exit();
}
if (!empty($_POST['favorite'])) {
    $favorite_sql = $pdo->prepare('SELECT * FROM Favorite WHERE student_id=? AND board_id=?');
    $student_id_me = $_SESSION['user']['student_id'];
    $board_id = $_POST['board_id'];
    $favorite_sql->execute([$student_id_me, $board_id]);
    if ($favorite_sql->rowCount() > 0) {
        $favorite_delete = $pdo->prepare('DELETE FROM Favorite WHERE student_id = ? AND board_id = ?');
        $favorite_delete->execute([$student_id_me, $board_id]);
    } else {
        $favorite_insert = $pdo->prepare('INSERT INTO Favorite (student_id, board_id) VALUES (?, ?)');
        $favorite_insert->execute([$student_id_me, $board_id]);
    }
    if (isset($_POST['board_search']) or isset($_SESSION['favorite']['search'])) {
        if (isset($_POST['board_search'])) {
            $_SESSION['favorite']['search'] = $_POST['board_search'];
        }
    }

    header("Location: search.php#board_" . intval($board_id));
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/search.css">
    <title>ASO PORTAL　|　official</title>
</head>

<body>
    <?php
    require 'header.php';
    ?>
    <main>
        <div class="main">
            <?php
            if (isset($_POST['board_search']) or isset($_SESSION['favorite']['search'])) {
                if (isset($_POST['board_search'])) {
                    $search = $_POST['board_search'];
                } else {
                    $search = $_SESSION['favorite']['search'];
                    unset($_SESSION['favorite']['search']);
                }
                $hantei = 0;
                echo '<div class="search-result-header">【' . htmlspecialchars($search) . '】の検索結果</div><p>';
                $sql = $pdo->prepare('SELECT * FROM Board WHERE board_name LIKE ?');
                $sql->execute(["%$search%"]);
                // if (!($sql->rowCount() == 0)) {
                echo '<form action="search.php" method="post">';
                echo '<input type="text" class="search_re_text" name="board_search" placeholder="掲示板再検索">';
                echo '<input type="submit" class="search_re_button" value="再検索">';
                echo '</form>';
                echo '<form action="search.php" method="post">';
                echo '<input type="hidden" value="' . $search . '" name="board_search">';
                echo '<select name="search_genre" class="search_genre_pull">';
                echo '<option value="">全てのジャンル</option>';
                $genre_sql = $pdo->query('SELECT * FROM Ganre');
                foreach ($genre_sql as $genre_row) {
                    $hantei = 1;
                    if (!(empty($_POST['search_genre']))) {
                        if ($genre_row['genre_id'] == $_POST['search_genre']) {
                            echo '<option value=' . $genre_row['genre_id'] . ' selected>' . $genre_row['genre_name'] . '</option>';
                        } else {
                            echo '<option value=' . $genre_row['genre_id'] . '>' . $genre_row['genre_name'] . '</option>';

                        }
                    } else {
                        echo '<option value=' . $genre_row['genre_id'] . '>' . $genre_row['genre_name'] . '</option>';
                    }
                }
                echo '</select>';
                echo '<input type="submit" class="search_genre_button" value="絞込">';
                echo '</form>';
                // }
                if (!(empty($_POST['search_genre']))) {
                    $search_genre = $_POST['search_genre'];
                    $sql = $pdo->prepare('SELECT * FROM Board WHERE board_name LIKE ? AND genre_id=?');
                    $sql->execute(["%$search%", $search_genre]);
                } else {
                    $sql = $pdo->prepare('SELECT * FROM Board WHERE board_name LIKE ?');
                    $sql->execute(["%$search%"]);
                }
                if ($sql->rowCount() > 0) {
                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                        $id = $row['board_id'];
                        $stu_id = $row['student_id'];
                        $name = $row['board_name'];
                        $create = $row['board_createdate'];
                        $genre_id = $row['genre_id'];
                        $sql2 = $pdo->prepare('SELECT post_content,post_pic FROM Post WHERE board_id = ? ORDER BY post_date DESC LIMIT 1');
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
                        $pass_dis = isset($row['board_password']) ? '<span class="locked">　🔒</span>' : '';

                        $sql3 = $pdo->prepare('SELECT user_name FROM User WHERE student_id = ?');
                        $sql3->execute([$stu_id]);
                        $row3 = $sql3->fetch(PDO::FETCH_ASSOC);

                        $sql4 = $pdo->prepare('SELECT * FROM Ganre WHERE genre_id = ?');
                        $sql4->execute([$genre_id]);
                        $row4 = $sql4->fetch(PDO::FETCH_ASSOC);

                        echo '<div class="board-card"  id="board_' . $id . '">';
                        echo '<h3 class="board-title">' . htmlspecialchars($name) . $pass_dis . '</h3>';
                        echo '<p class="board-info">作成者：' . htmlspecialchars($row3['user_name']) . '</p>';
                        echo '<p class="board-info">ジャンル：' . htmlspecialchars($row4['genre_name']) . '</p>';
                        echo '<p class="board-info">最新の投稿：' . $latestPostContent . '</p>';
                        echo '<form action="thread.php?id=' . intval($id) . '" method="post">';
                        echo '<button type="submit" class="join-button">参加する</button>';
                        echo '</form>';
                        ?>
                        <?php
                        echo '<form action="search.php?id="board_' . intval($id) . '" method="post">';
                        ?>
                        <input type="hidden" name="board_search" value="<?php echo $search ?>">
                        <input type="hidden" name="board_id" value="<?php echo $id; ?>">
                        <input type="hidden" name="favorite" value="1">
                        <?php
                        $student_id_me = $_SESSION['user']['student_id'];
                        $favorite_sql = $pdo->prepare('SELECT * FROM Favorite WHERE student_id=? AND board_id=?');
                        $favorite_sql->execute([$student_id_me, $id]);
                        if ($favorite_sql->rowCount() > 0) {
                            echo '<button type="submit" class="join-button">お気に入り登録済み</button>';
                        } else {
                            echo '<button type="submit" class="join-button">お気に入り登録</button>';
                        }
                        ?>
                        </form>
                        <?php
                        echo '</div>';
                    }
                } else {
                    echo '<h3 class="not-search">掲示板が見つかりません</h3>';
                    // if ($hantei == 0) {
                    //     echo '<form class="search_re_form" action="search.php" method="post">';
                    //     echo '<input type="text" class="search_re_text" name="board_search" placeholder="掲示板再検索">';
                    //     echo '<input type="submit" class="search_re_button" value="再検索">';
                    //     echo '</form>';
                    // }
                }
            }
            ?>
        </div>
    </main>
</body>

</html>