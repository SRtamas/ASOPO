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
    header("Location: board.php#board_" . intval($board_id));
    exit();
  }
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/board2.css">
    <title>ASO PORTAL　|　official</title>
</head>

<body>
    <?php
    require 'header.php';
    ?>

    <main>


        <div class="boarrd-main">
        <div id="up"></div>

            <div class="main_header">参加中の掲示板一覧</div>
            <a href="#low" id="bottomLink" class = "low">▼</a>

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

                        $sql3 = $pdo->prepare('SELECT * FROM Board AS borad_owner_id WHERE board_id = ? ');
                        $sql3->execute([$id]);
                        $row3 = $sql3->fetch(PDO::FETCH_ASSOC);
                        $stu_id = $row3['student_id'];
                        $name = $row3['board_name'];
                        $user_sql = $pdo->prepare('SELECT * FROM User WHERE student_id=?');
                        $user_sql->execute([$stu_id]);
                        foreach ($user_sql as $user_row) {
                            $board_owner_name = htmlspecialchars($user_row['user_name']);
                        }
                        $sql4 = $pdo->prepare('SELECT * FROM User WHERE student_id = ? ');
                        $sql4->execute([$stu_id]);
                        $row4 = $sql4->fetch(PDO::FETCH_ASSOC);

                        $pass_dis = isset($row3['board_password']) ? '<span class="locked">🔒</span>' : '';

                        // 自分の掲示板かどうかを判定
                        $is_own_board = $stu_id == $student_id;
                        $board_class = $is_own_board ? 'board own-board' : 'board';

                        echo '<div class="' . $board_class . '" id="board_'.$id.'">';
                        echo '<h3 class="board-title">' . htmlspecialchars($name) . $pass_dis . '</h3>';
                        echo '<p class="board-detail">作成者: ' . $board_owner_name . '</p>';

                        // echo '<p class="board-detail">投稿者: ' . htmlspecialchars($row4['user_name']) . '</p>';
                        echo '<p class="board-detail">最新の投稿: ' . $latestPostContent . '</p>';
                        echo '<form action="thread.php?id=' . intval($id) . '" method="post">';
                        echo '<button type="submit" class="button">参加する</button>';
                        echo '</form>';
                        ?>
                        <form aciton="board.php" method="post">
                            <input type="hidden" name="board_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="favorite" value="1">
                            <?php
                            $student_id_me = $_SESSION['user']['student_id'];
                            $favorite_sql = $pdo->prepare('SELECT * FROM Favorite WHERE student_id=? AND board_id=?');
                            $favorite_sql->execute([$student_id_me, $id]);
                            if ($favorite_sql->rowCount() > 0) {
                                echo '<button type="submit" class="button">お気に入り登録済み</button>';
                            } else {
                                echo '<button type="submit" class="button">お気に入り登録</button>';
                            }
                            ?>
                        </form>

                        <?php
                        echo '</div>';
            
                    }
                } else {
                    echo '<h3 class="not-found">掲示板が見つかりません</h3>';
                }
            } else {
                echo '<p class="error">ユーザー情報が見つかりません</p>';
            }
            echo '<div id="low">';
            echo '<a href="#up" class = "up">▲</a>';
            echo '</div>';

            ?>
        </div>
    </main>
</body>

</html>