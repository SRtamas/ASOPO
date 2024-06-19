<?php
session_start();
require "db-connect.php";
if (empty($_SESSION['user'])) {
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
    header("Location: $redirect_url");
    exit();
}
$board_id = $_POST['board_id'];
if (!(empty($_POST['pass_change']))) {
    // パスワード変更
    $pass = $_POST['pass_change'];
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
    $sql_update = $pdo->prepare('UPDATE Board SET board_password = ? WHERE board_id = ?');
    $sql_update->execute([$pass_hash, $board_id]);
    // $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/thread.php?id=' . intval($board_id);
    // header("Location: $redirect_url");
    // exit();
}
if (!(empty($_POST['pass_delete']))) {
    // パスワード削除
    $sql_update = $pdo->prepare('UPDATE Board SET board_password = ? WHERE board_id = ?');
    $sql_update->execute([null, $board_id]);
    // $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/thread.php?id=' . intval($board_id);
    // header("Location: $redirect_url");
    // exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/pass_change.css">
    <title>掲示板情報</title>
</head>
<?php
require 'header.php';
?>
<main>
    <div class="main">
        <p>
        <h1>掲示板情報</h1>
        </p>
        <?php
        $board_sql = $pdo->prepare('SELECT b.*, COUNT(p.post_id) AS count
                                    FROM Board b LEFT JOIN Post p ON b.board_id = p.board_id
                                    WHERE b.board_id = ? GROUP BY b.board_id;
                                    ');
        $board_sql->execute([$board_id]);
        foreach ($board_sql as $board_row) {
            $board_name = $board_row['board_name'];
            $genre_id = $board_row['genre_id'];
            $student_id = $board_row['student_id'];
            $post_count = $board_row['count'];
            $create_date = $board_row['board_createdate'];
            $update_date = $board_row['board_updatedate'];
            if (empty($board_row['board_password'])) {
                $pass_dis = '未設定';
            } else {
                $pass_dis = '設定済';
            }
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
        <table>
            <thead>
                <tr>
                    <th>掲示板ID</th>
                    <th>掲示板名</th>
                    <th>ジャンル</th>
                    <th>作成者</th>
                </tr>
            </thead>
            <tr>
                <td><?php echo $board_id ?></td>
                <td><?php echo $board_name ?></td>
                <td><?php echo $genre_name ?></td>
                <td><?php echo $user_name ?></td>
            </tr>
            <thead>
                <tr>
                    <th>投稿数</th>
                    <th>作成日</th>
                    <th>更新日</th>
                    <th>パスワード</th>
                </tr>
            </thead>
            <tr>
                <td><?php echo $post_count ?></td>
                <td><?php echo $create_date ?></td>
                <td><?php echo $update_date ?></td>
                <td><?php echo $pass_dis ?></td>
            </tr>
        </table>
        <p>
            <?php
            if ($student_id == $_SESSION['user']['student_id']) {
                ?>
            <p>
            <form action="pass_change.php" method="post">
                <?php
                if (empty($board_row['board_password'])) {
                    echo '<span>パスワード設定</span><br>';
                } else {
                    echo '<span>パスワード変更</span>';
                }
                ?>
                <input type="text" name="pass_change">
                <?php
                echo '<input type="hidden" name="board_id" value=', $board_id, '>';
                ?>
                <button>変更</button>
            </form>
            </p>
            <?php
            if (!(empty($board_row['board_password']))) {
                echo '<form action="pass_change.php" method="post">';
                echo '<span>パスワード削除</span>';
                echo '<input type="hidden" name="pass_delete" value=1>';
                echo '<input type="hidden" name="board_id" value=', $board_id, '>';
                echo '<button>削除</button>';
                echo '</form>';
            }
            }
            ?>
        </p>
        <?php
        echo '<form action="thread.php?id=' . intval($board_id) . '" method="post">';
        echo '<button type="submit" name="back" class="button">戻る</button>';
        ?>
    </div>
</main>
</body>

</html>