<?php
session_start();
require "db-connect.php";
$board_id = $_GET['id'];
$pdo = new PDO($connect, USER, PASS);
$sql = $pdo->prepare('SELECT * FROM Board WHERE board_id=? ');
// unset($post_content, $post_date, $post_pic);
$sql->execute([$board_id]);
$student_id = $_SESSION['user']['student_id'];
foreach ($sql as $row) {
  $board_name = $row['board_name'];
}
// var_dump($_SESSION[$board_id]['judge']);
if (!(empty($row['board_password']))) {
  if ($row['student_id'] != $student_id) {
    if (empty($_SESSION['$board_id']['judge']) || $_SESSION['$board_id']['judge'] != $board_id) {
      $_SESSION['board_id']['board_id'] = $board_id;
      // var_dump($_SESSION);
      // var_dump($_SESSION['$board_id']['judge']);
      // var_dump($_SESSION['board_id']['board_id']);
      // var_dump($_SESSION['test']['judge']);
      // var_dump($board_id);
      $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/thread_pass.php';
      header("Location: $redirect_url");
      exit();
    }
  }
}

// パスワードが一致している場合のみ以下のコードを実行する
if (isset($_POST['post_content'])) {
  if ($_POST['post_content'] != '' && !empty($_FILES['post_pic']['name'])) {
    // 文字有、画像有
    $post_content = $_POST['post_content'];
    $post_date = date("Y/m/d H:i:s");
    $post_pic = 1;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $sql_insert = $pdo->prepare('INSERT INTO Post (student_id, post_date, post_content, board_id, post_pic) VALUES (?, ?, ?, ?, ?)');
      $sql_insert->execute([$student_id, $post_date, $post_content, $board_id, $post_pic]);
      $sql_update = $pdo->prepare('UPDATE Board SET board_updatedate = ? WHERE board_id = ?');
      $sql_update->execute([$post_date, $board_id]);
      header("Location: " . $_SERVER['REQUEST_URI']);
      exit();
    }
    $post_id_post = $pdo->lastInsertId();
    if (is_uploaded_file($_FILES['post_pic']['tmp_name'])) {
      $destination_directory = 'pic/post_pic/';
      if (!file_exists($destination_directory)) {
        mkdir($destination_directory, 0755, true);
      }
      $file = $destination_directory . $post_id_post . '.jpg';
      if (!(move_uploaded_file($_FILES['post_pic']['tmp_name'], $file))) {
        // エラー
      }
    }
  } else if ($_POST['post_content'] != '' && empty($_FILES['post_pic']['name'])) {
    // 文字有、画像なし
    $post_content = $_POST['post_content'];
    $post_date = date("Y/m/d H:i:s");
    $post_pic = 0;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $sql_insert = $pdo->prepare('INSERT INTO Post (student_id, post_date, post_content, board_id, post_pic) VALUES (?, ?, ?, ?, ?)');
      $sql_insert->execute([$student_id, $post_date, $post_content, $board_id, $post_pic]);
      $sql_update = $pdo->prepare('UPDATE Board SET board_updatedate = ? WHERE board_id = ?');
      $sql_update->execute([$post_date, $board_id]);
      header("Location: " . $_SERVER['REQUEST_URI']);
      exit();
    }
  } else if (empty($_POST['post_content']) && !empty($_FILES['post_pic']['name'])) {
    // 文字なし、画像有
    $post_content = '';
    $post_date = date("Y/m/d H:i:s");
    $post_pic = 1;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $sql_insert = $pdo->prepare('INSERT INTO Post (student_id, post_date, post_content, board_id, post_pic) VALUES (?, ?, ?, ?, ?)');
      $sql_insert->execute([$student_id, $post_date, $post_content, $board_id, $post_pic]);
      $sql_update = $pdo->prepare('UPDATE Board SET board_updatedate = ? WHERE board_id = ?');
      $sql_update->execute([$post_date, $board_id]);
      header("Location: " . $_SERVER['REQUEST_URI']);
      exit();
    }
    $post_id_post = $pdo->lastInsertId();
    if (is_uploaded_file($_FILES['post_pic']['tmp_name'])) {
      $destination_directory = 'pic/post_pic/';
      if (!file_exists($destination_directory)) {
        mkdir($destination_directory, 0755, true);
      }
      $file = $destination_directory . $post_id_post . '.jpg';
      if (!(move_uploaded_file($_FILES['post_pic']['tmp_name'], $file))) {
        // エラー
      }
    }
  }
  // ここに unset を移動
  unset($_POST['post_content']);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>スレッド</title>
  <link rel="stylesheet" href="css/thread.css">
</head>

<body>
  <header>
    <img id="topikon" src="img/5.png" class="show">
    <div class="user-icon"></div>
    <div class="menu-icon"></div>
  </header>
  <main>
    <div class="thread-name"><?php echo $board_name ?></div>
    <div class="chat-container">
      <?php
      $sql_thread = $pdo->prepare('SELECT * FROM Post WHERE board_id=?');
      $sql_thread->execute([$board_id]);
      foreach ($sql_thread as $row_thread) {
        $post_id_post = $row_thread['post_id'];
        $student_id_post = $row_thread['student_id'];
        $post_date_post = $row_thread['post_date'];
        $post_content_post = $row_thread['post_content'];
        $post_pic_post = $row_thread['post_pic'];
        $post_pic_post = $row_thread['post_pic'];
        $sql_user = $pdo->prepare('SELECT * FROM User WHERE student_id=?');
        $sql_user->execute([$student_id_post]);
        foreach ($sql_user as $row_user) {
          $user_name_post = $row_user['user_name'];
          $user_school_id = $row_user['School_id'];
        }
        $sql_school = $pdo->prepare('SELECT * FROM School WHERE School_id=?');
        $sql_school->execute([$user_school_id]);
        foreach ($sql_school as $row_school) {
          $user_school_naem = $row_school['School_name'];
        }
        if ($student_id == $student_id_post) {
          echo '<div class="message sent">';
          echo '<div class="message-text"><span class="post_date">', $post_date_post, '</span><br><span class="post_school">', $user_school_naem, '</span><br><span class="post_content">', $post_content_post, '</span></div>';
          if ($post_pic_post == 1) {
            $pic_file = "pic/post_pic/{$post_id_post}.jpg";
            echo '<img class="pic" src="' . $pic_file . '" alt="投稿画像">';
          }
          echo '</div>';
        } else {
          echo '<div class="message received">';
          $icon_file = "pic/icon/{$student_id_post}.jpg";
          if (file_exists($icon_file)) {
            echo '<img class="icon" src="' . $icon_file . '" alt="アイコン">';
          } else {
            echo '<img class="icon" src="pic/icon/guest.jpg" alt="デフォルトアイコン">';
          }
          echo '<div class="message-text"><span class="post_date">', $post_date_post, '</span><br><span class="post_school">', $user_school_naem, '</span><br><span class="post_name">', $user_name_post, '</span><br><span class="post_content">', $post_content_post, '</span></div>';
          if ($post_pic_post == 1) {
            $pic_file = "pic/post_pic/{$post_id_post}.jpg";
            echo '<img class="pic" src="' . $pic_file . '" alt="投稿画像">';
          }
          echo '</div>';
        }
      }
      ?>
    </div>
    <div class="input-container">
      <form action="thread.php?id=<?php echo intval($row['board_id']); ?>" method="post" enctype="multipart/form-data">
        <textarea name="post_content" placeholder="メッセージを入力"></textarea>
        <input type="file" name="post_pic" accept=".jpg, .jpeg, .png" />
        <button class="send-button">送信</button>
      </form>
    </div>
    <br>
    <a href="board-list.php">ボードリストへ</a>
  </main>
</body>

</html>