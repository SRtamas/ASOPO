<?php
session_start();
require "db-connect.php";

// ログインしていない場合はトップページにリダイレクト
if (empty($_SESSION['user'])) {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
    header("Location: $redirect_url");
    exit();
}

// 検索クエリの処理
$search_query = '';
if (!empty($_GET['search'])) {
    $search_query = htmlspecialchars($_GET['search']);
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
    if (empty($_GET['search'])) {
        header("Location: board-list.php#board_" . intval($board_id));
    } else {
        header("Location: board-list.php?search=" . $_GET['search'] . "#board_" . intval($board_id));
    }
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
        <div class="main_header">掲示板一覧</div>

        <!-- 検索フォーム -->
        <form method="get" action="board-list.php" class="search-form">
            <input type="text" name="search" placeholder=" 掲示板または作成者を検索" value="<?php echo $search_query; ?>">
            <button type="submit" class="search-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search"
                    viewBox="0 0 16 16">
                    <path
                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                </svg>
            </button>
        </form>

        <a href="#low" id="bottomLink" class = "low">▼</a>

        <?php
        // 掲示板の情報を取得するクエリ
        $sql = $pdo->prepare('SELECT B.board_id, B.board_name, B.student_id AS board_owner_id, B.board_password, U.user_name, 
                                    (SELECT COUNT(*) FROM Post P WHERE P.board_id = B.board_id) AS post_count,
                                    (SELECT post_content FROM Post P WHERE P.board_id = B.board_id ORDER BY post_date DESC LIMIT 1) AS latest_post_content,
                                    (SELECT post_pic FROM Post P WHERE P.board_id = B.board_id ORDER BY post_date DESC LIMIT 1) AS latest_post_pic
                            FROM Board B
                            LEFT JOIN User U ON B.student_id = U.student_id
                            WHERE B.board_name LIKE :search_query OR U.user_name LIKE :search_query
                            ORDER BY B.board_id ASC');
        $sql->execute([':search_query' => "%$search_query%"]);
        $boards = $sql->fetchAll(PDO::FETCH_ASSOC);

        if ($sql->rowCount() > 0) {
            foreach ($boards as $board) {
                $board_id = $board['board_id'];
                $board_name = htmlspecialchars($board['board_name']);
                $board_owner_name = htmlspecialchars($board['user_name']);
                $post_count = $board['post_count'];
                $latest_post_content = $board['latest_post_content'];
                $latest_post_pic = $board['latest_post_pic'];

                // 最新の投稿内容を適切な表現に変換する
                if ($latest_post_content === null) {
                    $latest_post_display = '投稿がありません';
                } elseif ($latest_post_pic == 1) {
                    $latest_post_display = '画像の投稿 📷';
                } elseif ($latest_post_pic == 2) {
                    $latest_post_display = '動画の投稿 🎥';
                } else {
                    $latest_post_display = htmlspecialchars($latest_post_content);
                }
                // 掲示板がパスワードで保護されている場合の表示
                $password_protected = isset($board['board_password']) ? '<span class="locked">🔒</span>' : '';

                // 掲示板への参加ボタン
                $join_button = '<form action="thread.php?id=' . intval($board_id) . '" method="post">
                                    <button type="submit" class="button">参加する</button>
                                </form>';

                // 自分の掲示板かどうかを判定
                $is_own_board = $_SESSION['user']['student_id'] == $board['board_owner_id'];

                // 掲示板の表示（自分の掲示板には特別なクラスを追加）
                $board_class = $is_own_board ? 'board board-own' : 'board';
                echo '<div class="' . $board_class . '" >';
                echo '<h3 class="board-title" id="board_' . $board_id . '">' . $board_name . $password_protected . '</h3>';
                echo '<p class="board-detail">作成者: ' . $board_owner_name . '</p>';
                echo '<p class="board-detail">投稿数: ' . $post_count . '</p>';
                echo '<p class="board-detail">最新の投稿: ' . $latest_post_display . '</p>';
                echo $join_button;
                ?>
                <?php
                if (empty($_GET['search'])) {
                    echo '<form action="board-list.php?id="board_' . intval($board_id) . '" method="post">';
                } else {
                    echo '<form action="board-list.php?id=board_' . intval($board_id) . '&search=' . urlencode($_GET['search']) . '" method="post">';
                }
                ?>
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
            echo '<div id="low">';
            echo '<a href="#up" class = "up">▲</a>';
            echo '</div>';
        } else {
            echo '<p class="not-found">掲示板が見つかりません</p>';
            echo '<script>document.getElementById("bottomLink").style.display = "none";</script>';
        }
        ?>
    </div>
</body>

</html>