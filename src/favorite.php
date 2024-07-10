<?php
session_start();
require "db-connect.php";

// ログインしていない場合はトップページにリダイレクト
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
    header("Location: favorite.php#board_" . intval($board_id));
    exit();
  }
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/board-list.css">
    <title>ASO PORTAL　|　official</title>
</head>

<body>
    <?php require 'header.php'; ?>
    <li id="up"></li>
    <div class="main">
        <div class="main_header">お気に入り一覧</div>

        <?php
        // 掲示板の情報を取得するクエリ
        $student_id = $_SESSION['user']['student_id'];
        $sql = $pdo->prepare('SELECT * FROM Favorite WHERE student_id=?');
        $sql->execute([$student_id]);
        if ($sql->rowCount() > 0) {
            echo '<a href="#low" id="bottomLink">一番下へ</a>';
            foreach ($sql as $favorite_row) {
                $board_sql = $pdo->prepare('SELECT * FROM Board WHERE board_id=?');
                $board_sql->execute([$favorite_row['board_id']]);
                foreach ($board_sql as $board) {
                    $board_id = $board['board_id'];
                    $board_name = $board['board_name'];
                    $student_sql = $pdo->prepare('SELECT * FROM User WHERE student_id=?');
                    $student_sql->execute([$board['student_id']]);
                    foreach ($student_sql as $student_row) {
                        $board_owner_name = $student_row['user_name'];
                    }
                    $sql = $pdo->prepare('SELECT COUNT(*) AS count FROM Post WHERE board_id = ?');
                    $sql->execute([$board_id]);
                    $postCount = $sql->fetch(PDO::FETCH_ASSOC)['count'];
                    $post_count = $postCount;
                    $sql_last = $pdo->prepare('SELECT post_content, post_date, post_pic
                           FROM Post WHERE board_id = ?
                           ORDER BY post_date DESC LIMIT 1
                          ');
                    $sql_last->execute([$board_id]);
                    $latest_post = $sql_last->fetch(PDO::FETCH_ASSOC);

                    if ($latest_post) {
                        $latest_post_content = $latest_post['post_content'];
                        $latest_post_pic = $latest_post['post_pic'];
                    } else {
                        $latest_post_content = '投稿がありません';
                        $latest_post_pic = null;
                    }

                    // $post_count = $board['post_count'];
                    // $latest_post_content = $board['latest_post_content'];
                    // $latest_post_pic = $board['latest_post_pic'];
                    // 最新の投稿内容を適切な表現に変換する
                    if ($latest_post_content === null) {
                        $latest_post_display = '投稿がありません';
                    } elseif ($latest_post_pic == 1) {
                        $latest_post_display = '画像の投稿 📷';
                    } elseif ($latest_post_pic == 2) {
                        $latest_post_display = '動画の投稿 🎥';
                    } else {
                        $latest_post_display = $latest_post_content;
                    }
                    // 掲示板がパスワードで保護されている場合の表示
                    $password_protected = isset($board['board_password']) ? '<span class="locked">🔒</span>' : '';

                    // 掲示板への参加ボタン
                    $join_button = '<form action="thread.php method="post">
                                    <button type="submit" class="button">参加する</button>
                                </form>';

                    // 自分の掲示板かどうかを判定
                    $is_own_board = $_SESSION['user']['student_id'] == $board['student_id'];

                    // 掲示板の表示（自分の掲示板には特別なクラスを追加）
                    $board_class = $is_own_board ? 'board board-own' : 'board';
                    echo '<div class="' . $board_class . '" id="board_'.$board_id.'">';
                    echo '<h3 class="board-title">' . $board_name . $password_protected . '</h3>';
                    echo '<p class="board-detail">作成者: ' . $board_owner_name . '</p>';
                    echo '<p class="board-detail">投稿数: ' . $post_count . '</p>';
                    echo '<p class="board-detail">最新の投稿: ' . $latest_post_display . '</p>';
                    echo $join_button;
                    ?>
                    <form aciton="favorite.php?id=' . intval($board_id) . '"  method="post">
                        <input type="hidden" name="board_id" value="<?php echo $board_id; ?>">
                        <input type="hidden" name="favorite" value="1">
                        <?php
                        $student_id_me = $_SESSION['user']['student_id'];
                        $favorite_sql = $pdo->prepare('SELECT * FROM Favorite WHERE student_id=? AND board_id=?');
                        $favorite_sql->execute([$student_id_me, $board_id]);
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
            }
            echo '<div id="low">';
            echo '<a href="#up">一番上へ</a>';
            echo '</div>';
        } else {
            echo '<p class="not-found">掲示板が見つかりません</p>';
            echo '<script>document.getElementById("bottomLink").style.display = "none";</script>';
        }
        ?>
    </div>
</body>

</html>