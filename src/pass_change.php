<?php
session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
require "db-connect.php";

if (empty($_SESSION['user'])) {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
    header("Location: $redirect_url");
    exit();
}
if (isset($_POST['board_id'])) {
    $board_id = $_POST['board_id'];
}

if (!empty($_POST['pass_change'])) {
    // パスワード変更
    $pass = $_POST['pass_change'];
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
    $sql_update = $pdo->prepare('UPDATE Board SET board_password = ? WHERE board_id = ?');
    $sql_update->execute([$pass_hash, $board_id]);
}

if (!empty($_POST['pass_delete'])) {
    // パスワード削除
    $sql_update = $pdo->prepare('UPDATE Board SET board_password = ? WHERE board_id = ?');
    $sql_update->execute([null, $board_id]);
}

if (!empty($_POST['delete_board_id'])) {
    // 掲示板削除
    $delete_board_id = $_POST['delete_board_id'];
    $post_sql = $pdo->prepare('SELECT * FROM Post WHERE board_id = ?');
    $post_sql->execute([$delete_board_id]);
    foreach ($post_sql as $post_row) {
        $post_id = $post_row['post_id'];
        $post_pic = $post_row['post_pic'];
        if ($post_pic == 1) {
            $pic_file = "pic/post_pic/{$post_id}.jpg";
            unlink($pic_file);
        } else if ($post_pic == 2) {
            $video_file = "movie/post_movie/{$post_id}.mp4";
            unlink($video_file);
        }
    }
    $post_delete = $pdo->prepare('DELETE FROM Post WHERE board_id = ?');
    $post_delete->execute([$delete_board_id]);
    $sql_delete = $pdo->prepare('DELETE FROM Board WHERE board_id = ?');
    $sql_delete->execute([$delete_board_id]);
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/home-login.php'; // 適切なURLに変更する
    header("Location: $redirect_url");
    exit();
}
if (!empty($_POST['favorite'])) {
    $favorite_sql = $pdo->prepare('SELECT * FROM Favorite WHERE student_id=? AND board_id=?');
    $student_id_me = $_SESSION['user']['student_id'];
    $favorite_sql->execute([$student_id_me, $board_id]);
    if ($favorite_sql->rowCount() > 0) {
        $favorite_delete = $pdo->prepare('DELETE FROM Favorite WHERE student_id = ? AND board_id = ?');
        $favorite_delete->execute([$student_id_me, $board_id]);
    } else {
        $favorite_insert = $pdo->prepare('INSERT INTO Favorite (student_id, board_id) VALUES (?, ?)');
        $favorite_insert->execute([$student_id_me, $board_id]);
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/pass_change.css">
    <title>ASO PORTAL　|　official</title>
</head>

<?php
require 'header.php';
?>

<main>
    <div class="main">
        <h1>掲示板情報</h1>
        <table>
            <thead>
                <tr>
                    <th>掲示板ID</th>
                    <th>掲示板名</th>
                    <th>ジャンル</th>
                    <th>作成者</th>
                </tr>
            </thead>
            <?php
            $board_sql = $pdo->prepare('SELECT b.*, COUNT(p.post_id) AS count
                                            FROM Board b LEFT JOIN Post p ON b.board_id = p.board_id
                                            WHERE b.board_id = ? GROUP BY b.board_id');
            $board_sql->execute([$board_id]);
            foreach ($board_sql as $board_row) {
                $board_name = $board_row['board_name'];
                $genre_id = $board_row['genre_id'];
                $student_id = $board_row['student_id'];
                $post_count = $board_row['count'];
                $create_date = $board_row['board_createdate'];
                $update_date = empty($board_row['board_updatedate']) ? "-" : $board_row['board_updatedate'];
                $pass_dis = empty($board_row['board_password']) ? '未設定' : '設定済';
            }

            $genre_sql = $pdo->prepare('SELECT * FROM Ganre WHERE genre_id = ?');
            $genre_sql->execute([$genre_id]);
            foreach ($genre_sql as $genre_row) {
                $genre_name = $genre_row['genre_name'];
            }

            $user_sql = $pdo->prepare('SELECT * FROM User WHERE student_id = ?');
            $user_sql->execute([$student_id]);
            foreach ($user_sql as $user_row) {
                $user_name = $user_row['user_name'];
            }
            ?>
            <tr>
                <td><a
                        href="thread.php?id=<?php echo intval($board_id); ?>"><?php echo htmlspecialchars($board_id); ?></a>
                </td>
                <td><a
                        href="thread.php?id=<?php echo intval($board_id); ?>"><?php echo htmlspecialchars($board_name); ?></a>
                </td>
                <td><a
                        href="Genre.php?id=<?php echo intval($genre_id); ?>"><?php echo htmlspecialchars($genre_name); ?></a>
                </td>
                <?php if ($student_id == $_SESSION['user']['student_id']): ?>
                    <td><a href="profile-input.php"><?php echo htmlspecialchars($user_name); ?></a></td>
                <?php else: ?>
                    <td><a
                            href="profile_con.php?id=<?php echo intval($student_id); ?>"><?php echo htmlspecialchars($user_name); ?></a>
                    </td>
                <?php endif; ?>
            </tr>
            <thead>
                <tr>
                    <th>投稿数</th>
                    <th>作成日</th>
                    <th>更新日</th>
                    <th>パスワード</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($post_count); ?></td>
                    <td><?php echo htmlspecialchars($create_date); ?></td>
                    <td><?php echo htmlspecialchars($update_date); ?></td>
                    <td><?php echo htmlspecialchars($pass_dis); ?></td>
                </tr>
            </tbody>
        </table>
        <form aciton="pass_change.php" method="post">
            <input type="hidden" name="board_id" value="<?php echo $board_id; ?>">
            <input type="hidden" name="favorite" value="1">
            <?php
            $student_id_me = $_SESSION['user']['student_id'];
            $favorite_sql = $pdo->prepare('SELECT * FROM Favorite WHERE student_id=? AND board_id=?');
            $favorite_sql->execute([$student_id_me, $board_id]);
            if ($favorite_sql->rowCount() > 0) {
                echo '<button type="submit">お気に入り登録済み</button>';
            } else {
                echo '<button type="submit">お気に入り登録</button>';
            }
            ?>
        </form>
        <?php if ($student_id == $_SESSION['user']['student_id']): ?>
            <form action="pass_change.php" method="post">
                <?php if (empty($board_row['board_password'])): ?>
                    <span>パスワード設定</span><br>
                <?php else: ?>
                    <span>パスワード変更</span>
                <?php endif; ?>
                <input type="text" name="pass_change">
                <input type="hidden" name="board_id" value="<?php echo $board_id; ?>">
                <button type="submit">変更</button>
            </form>

            <?php if (!empty($board_row['board_password'])): ?>
                <form action="pass_change.php" method="post">
                    <span>パスワード削除</span>
                    <input type="hidden" name="pass_delete" value="1">
                    <input type="hidden" name="board_id" value="<?php echo $board_id; ?>">
                    <button type="submit">削除</button>
                </form>
                <form id="deleteForm" action="pass_change.php" method="post">
                    <button type="button" id="deleteButton" class="button">掲示板削除</button>
                    <input type="hidden" name="delete_board_id" value="<?php echo $board_id; ?>">
                </form>
            <?php endif; ?>
        <?php endif; ?>

        <form action="thread.php?id=<?php echo intval($board_id); ?>" method="post">
            <button type="submit" name="back" class="button">戻る</button>
        </form>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <center>
                <p>本当に削除していいですか？</p>
            </center>
            <div class="modal-buttons">
                <button id="confirmDelete" class="button">はい</button>
                <button id="cancelDelete" class="button">いいえ</button>
            </div>
        </div>
    </div>
</main>

<script>
    // モーダル表示用のJavaScript
    var modal = document.getElementById("deleteModal");
    var btn = document.getElementById("deleteButton");
    var span = document.getElementsByClassName("close")[0];
    var cancelBtn = document.getElementById("cancelDelete");
    var confirmBtn = document.getElementById("confirmDelete");

    btn.onclick = function () {
        modal.style.display = "block";
    }

    span.onclick = function () {
        modal.style.display = "none";
    }

    cancelBtn.onclick = function (event) {
        event.preventDefault(); // デフォルトの動作をキャンセル
        modal.style.display = "none";
    }

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    confirmBtn.onclick = function () {
        document.getElementById("deleteForm").submit();
    }
</script>

</body>

</html>