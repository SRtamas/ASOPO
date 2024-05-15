<?php
session_start();
require 'db-connect.php';
require 'header.php';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/login_top.css">
  <title>TOPページ/ASOPO</title>
</head>

<body>  
  <div class="container">
    <main>
      <div class="main">
        
        <div class="main_hedder">
          <strong>タイムライン</strong>
          <button class="write-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
            </svg>
          </button>
        </div>

        <div id="modalContent" style="display: none;">
          <button type="button" class="modal_message">キャンセル</button>
          <?php
          if (isset($_SESSION['user']) && isset($_SESSION['user']['student_id'])) {
            $student_id = $_SESSION['user']['student_id'];
            $icon_file = "pic/icon/{$student_id}.jpg";
            if (file_exists($icon_file)) {
              echo '<img id="me1" src="' . $icon_file . '" alt="アイコン">';
            } else {
              echo '<img id="me2" src="pic/icon/guest.jpg" alt="デフォルトアイコン">';
            }
          }
          ?>
          <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <textarea class="message" name="message" rows="10" cols="50" required placeholder="今なにしてる？"></textarea>
            <button class="post" type="submit">投稿</button>
          </form>
        </div>

        <!-- データベースからの投稿表示 -->
        <?php
        $sql = "SELECT Timeline.*, User.user_name 
                FROM Timeline 
                INNER JOIN User ON Timeline.student_id = User.student_id 
                ORDER BY Timeline.post_date DESC";

        $stmt = $pdo->query($sql);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $content = htmlspecialchars($row['post_content']);
          $postDate = htmlspecialchars($row['post_date']);
          $good = htmlspecialchars($row['good']);
          $comentcount = htmlspecialchars($row['comment_count']);
          $userName = htmlspecialchars($row['user_name']);

          echo '<div class="timeline_content">';
          echo '<p>', $content, '</p>';
          echo '<hr>
                <div class="g_c">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-thumbs-up-fill" viewBox="0 0 16 16">
                    <path d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
                  </svg>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left-dots-fill" viewBox="0 0 16 16">
                    <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793V2zm5 4a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                  </svg>
                </div>
              </div>';
        }
        ?>
      </div>
    </main>
  </div>



  <script src="js/login_top.js"></script>
  <script>document.addEventListener('DOMContentLoaded', function() {
  const writeButton = document.querySelector('.write-button');
  const modalContent = document.getElementById('modalContent');

  writeButton.addEventListener('click', function() {
    modalContent.style.display = 'block';
  });

  const modalMessageButton = document.querySelector('.modal_message');
  modalMessageButton.addEventListener('click', function() {
    modalContent.style.display = 'none';
  });
});
</script>
</body>

</html>