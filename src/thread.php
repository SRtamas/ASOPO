<?php
session_start();
require "db-connect.php";
if (empty($_SESSION['user'])) {
  $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
  header("Location: $redirect_url");
  exit();
}
$board_id = $_GET['id'];
$pdo = new PDO($connect, USER, PASS);
$sql = $pdo->prepare('SELECT * FROM Board WHERE board_id=? ');
// unset($post_content, $post_date, $post_pic);
$sql->execute([$board_id]);
$student_id = $_SESSION['user']['student_id'];
foreach ($sql as $row) {
  $board_name = $row['board_name'];
  $board_create = $row['student_id'];
}
if (!(empty($row['board_password']))) {
  if ($row['student_id'] != $student_id) {
    if (empty($_SESSION['$board_id']['judge']) || $_SESSION['$board_id']['judge'] != $board_id) {
      $_SESSION['board_id']['board_id'] = $board_id;
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
      $post_id_post = $pdo->lastInsertId();
      $sql_update = $pdo->prepare('UPDATE Board SET board_updatedate = ? WHERE board_id = ?');
      $sql_update->execute([$post_date, $board_id]);
    }
    if (is_uploaded_file($_FILES['post_pic']['tmp_name'])) {
      // MIMEタイプを取得
      $fileType = mime_content_type($_FILES['post_pic']['tmp_name']);

      // ディレクトリの設定
      $image_directory = 'pic/post_pic/';
      $video_directory = 'movie/post_movie/';

      // 画像の処理
      if (in_array($fileType, ['image/jpeg', 'image/png'])) {
        if (!file_exists($image_directory)) {
          mkdir($image_directory, 0755, true);
        }
        // $file_extension = $fileType == 'image/jpeg' ? '.jpg' : '.png';
        $file = $image_directory . $post_id_post . '.jpg';

        if (!move_uploaded_file($_FILES['post_pic']['tmp_name'], $file)) {
          // エラー処理
          echo "画像のアップロードに失敗しました。";
        }
      }
      // 動画の処理
      elseif ($fileType == 'video/mp4') {
        if (!file_exists($video_directory)) {
          mkdir($video_directory, 0755, true);
        }
        $file = $video_directory . $post_id_post . '.mp4';

        if (!move_uploaded_file($_FILES['post_pic']['tmp_name'], $file)) {
          // エラー処理
          echo "動画のアップロードに失敗しました。";
        }
        $post_pic = 2;
        $sql_update = $pdo->prepare('UPDATE Post SET post_pic = ? WHERE post_id = ?');
        $sql_update->execute([$post_pic, $post_id_post]);
      } else {
        // 対応していないファイルタイプのエラー処理
        echo "対応していないファイル形式です。";
      }

      // 成功した場合はリダイレクト
      header("Location: " . $_SERVER['REQUEST_URI']);
      exit();
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
      $post_id_post = $pdo->lastInsertId();
      $sql_update = $pdo->prepare('UPDATE Board SET board_updatedate = ? WHERE board_id = ?');
      $sql_update->execute([$post_date, $board_id]);
    }

    if (is_uploaded_file($_FILES['post_pic']['tmp_name'])) {
      // MIMEタイプを取得
      $fileType = mime_content_type($_FILES['post_pic']['tmp_name']);

      // ディレクトリの設定
      $image_directory = 'pic/post_pic/';
      $video_directory = 'movie/post_movie/';

      // 画像の処理
      if (in_array($fileType, ['image/jpeg', 'image/png'])) {
        if (!file_exists($image_directory)) {
          mkdir($image_directory, 0755, true);
        }
        // $file_extension = $fileType == 'image/jpeg' ? '.jpg' : '.png';
        $file = $image_directory . $post_id_post . '.jpg';

        if (!move_uploaded_file($_FILES['post_pic']['tmp_name'], $file)) {
          // エラー処理
          echo "画像のアップロードに失敗しました。";
        }
      }
      // 動画の処理
      elseif ($fileType == 'video/mp4') {
        if (!file_exists($video_directory)) {
          mkdir($video_directory, 0755, true);
        }
        $file = $video_directory . $post_id_post . '.mp4';

        if (!move_uploaded_file($_FILES['post_pic']['tmp_name'], $file)) {
          // エラー処理
          echo "動画のアップロードに失敗しました。";
        }
        $post_pic = 2;
        $sql_update = $pdo->prepare('UPDATE Post SET post_pic = ? WHERE post_id = ?');
        $sql_update->execute([$post_pic, $post_id_post]);
      } else {
        // 対応していないファイルタイプのエラー処理
        echo "対応していないファイル形式です。";
      }

      // 成功した場合はリダイレクト
      header("Location: " . $_SERVER['REQUEST_URI']);
      exit();
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
  <?php
  require 'header.php';
  ?>
  <main>
    <div class="container">
      <!-- <a href="board-list.php" class="board-link">ボードリストへ</a> -->
      <div class="chat-container">
      <div class="thread-name"><?php echo $board_name ?></div>
      <a href="#text">一番下へ移動</a>
        <?php
        $sql_thread = $pdo->prepare('SELECT * FROM Post WHERE board_id=?');
        $sql_thread->execute([$board_id]);
        foreach ($sql_thread as $row_thread) {
          $post_id_post = $row_thread['post_id'];
          $student_id_post = $row_thread['student_id'];
          $post_date_post = $row_thread['post_date'];
          $post_content_post = $row_thread['post_content'];
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
            echo '<div class="message-text">';
            echo '<span class="post_date">', $post_date_post, '</span><br>';
            // echo '<span class="post_school">', $user_school_naem, '</span><br>';
            echo '<span class="post_content">', nl2br($post_content_post), '</span>';
            if ($post_pic_post == 2) {
              $video_file = "movie/post_movie/{$post_id_post}.mp4";
              echo '<a href="' . htmlspecialchars($video_file) . '" target="_blank">動画を再生する</a>';
            }
            echo '</div>';
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
            echo '<div class="message-text">';
            echo '<span class="post_date">', $post_date_post, '</span><br>';
            echo '<span class="post_school">', $user_school_naem, '</span><br>';
            echo '<span class="post_name">', $user_name_post, '</span><br>';
            echo '<span class="post_content">', nl2br($post_content_post), '</span>';
            if ($post_pic_post == 2) {
              $video_file = "movie/post_movie/{$post_id_post}.mp4";
              echo '<a href="' . htmlspecialchars($video_file) . '" target="_blank">動画を再生する</a>';
            }
            echo '</div>';
            if ($post_pic_post == 1) {
              $pic_file = "pic/post_pic/{$post_id_post}.jpg";
              echo '<img class="pic" src="' . $pic_file . '" alt="投稿画像">';
            }
            echo '</div>';
          }
        }
        ?>
      </div>
      <p>
      <div class="input-container">
      <form action="thread.php?id=<?php echo intval($board_id); ?>" method="post" enctype="multipart/form-data" onsubmit="return validateFileSize()">
          <textarea class="post_text" name="post_content" placeholder="メッセージを入力" id="text"></textarea>
          <button class="send-button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
              class="bi bi-send" viewBox="0 0 16 16">
              <path
                d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z" />
            </svg></button>
          <!-- <input type="file" name="post_pic" accept=".jpg, .jpeg, .png" /> -->
          <div class="custom-file-upload">
            <label for="post_pic" class="custom-file-label">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-clipboard2" viewBox="0 0 16 16">
                <path
                  d="M3.5 2a.5.5 0 0 0-.5.5v12a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-12a.5.5 0 0 0-.5-.5H12a.5.5 0 0 1 0-1h.5A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1H4a.5.5 0 0 1 0 1h-.5Z" />
                <path
                  d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z" />
              </svg>
              <!-- 画像を選択 -->
            </label>
            <input type="file" id="post_pic" name="post_pic" accept=".jpg, .jpeg, .png, .mp4" />
            <span id="file-name"></span> <!-- ファイル名を表示するための要素 -->

          </div>
      </div>
      </form>
      </p>
    </div>
    <br>
    <?php
    if ($student_id == $board_create) {
      echo '<form action="pass_change.php" class="pass_change" method="post">';
      echo '<input type="hidden" name="board_id" value=', $board_id, '>';
      echo '<button>パスワード設定</button>';
      echo '</form>';
    }
    ?>
  </main>

</body>
<script>
  function validateFileSize() {
    var fileInput = document.getElementById('post_pic');
    if (fileInput.files.length > 0) {
      var maxSizeMB = 10; // 最大許容サイズ（MB）
      var fileSizeMB = fileInput.files[0].size / (1024 * 1024); // ファイルサイズをMB単位に変換
      if (fileSizeMB > maxSizeMB) {
        alert('動画ファイルのサイズは' + maxSizeMB + 'MB以下にしてください。');
        return false; // アップロードをキャンセル
      }
    }
    return true; // アップロードを許可
  }
</script>

</html>