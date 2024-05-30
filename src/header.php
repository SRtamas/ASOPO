<link rel="stylesheet" type="text/css" href="css/login_top.css">
<header class="header">
    <h1 class="header__title header-title">
        <a href="home-login.php"><img src="img/6.png" alt="ロゴ画像"></a>
    </h1>
    <?php
    if (isset($_SESSION['user']) && isset($_SESSION['user']['student_id'])) {
        $student_id = $_SESSION['user']['student_id'];
        $icon_file = "pic/icon/{$student_id}.jpg";
        if (file_exists($icon_file)) {
            echo '<a href="profile-input.php"><img id="me" src="' . $icon_file . '" alt="アイコン"></a>';
        } else {
            echo '<a href="profile-input.php"><img id="me" src="pic/icon/guest.jpg" alt="デフォルトアイコン"></a>';
        }
    }
    ?>
    <!-- <div id="profileModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span id="cancelButton" class="close">&times;</span>
        <a href="profile_settings.php">プロフィール設定</a>
        <a href="logout.php">ログアウト</a>
    </div>
</div> -->
    <button class="header__hamburger hamburger" id="js-hamburger">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <nav class="header__nav nav" id="js-nav">
        <div class="nav__items nav-items">
            <div class="nav_content">
                <div id="welcom_message"><?php $userName = $_SESSION['user']['user_name'];
                echo 'ようこそ、' . $userName . 'さん'; ?></div>


                <div class="search-container cp_iptxt">


                    <form action="search.php" method="post">
                        <input type="text" id="board_search" name="board_search">
                        <label>掲示板検索</label>
                        <span class="focus_line"><i></i></span>
                        <button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                            </svg>
                        </button>
                    </form>

                </div>
                <!-- <ul> -->
                    <li class="nav-items__item"><a href="home-login.php">トップページ</a></li>
                    <li class="nav-items__item"><a href="rank.php">ランキング</a></li>
                    <li class="nav-items__item"><a href="new-board.php">スレッド作成</a></li>
                    <li class="nav-items__item"><a href="board.php">参加中のスレッド</a></li>
                    <hr>
                    <li class="nav-items__item"><a href=""><b>カテゴリ</b></a></li>
                    <?php
                    $pdo = new PDO($connect, USER, PASS);
                    foreach ($pdo->query('select * from Ganre') as $row) {

                        echo '<li class="nav-items__item"><a href="Genre.php?id=', $row['genre_id'], '">', $row['genre_name'], '</a></li>';
                    }

                    ?>
                <!-- </ul> -->
            </div>
        </div>
    </nav>
    <script src="js/login_top.js"></script>
</header>