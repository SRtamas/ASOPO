const ham = document.querySelector('#js-hamburger');
const nav = document.querySelector('.header__nav');
const navContent = document.querySelector('.nav_content');

ham.addEventListener('click', function () {
    ham.classList.toggle('active');
    nav.classList.toggle('active');
    navContent.classList.toggle('active'); // 追加
});
let modalElement = null; // モーダルウィンドウ要素をグローバルで定義

document.querySelector('.write-button').addEventListener('click', openOrCloseModal);

function openOrCloseModal() {
    if (!modalElement) {
        createModal();
    } else {
        closeModalWindow();
    }
}

function createModal() {
    modalElement = document.createElement('div');
    modalElement.classList.add('modal');

    const innerElement = document.createElement('div');
    innerElement.classList.add('inner');

    // 入力フォームを含むモーダルコンテンツ
    innerElement.innerHTML =
    `<form>
    <button type="button" class="modal_message">キャンセル</button>
    <?php
        if (isset($_SESSION['user']) && isset($_SESSION['user']['student_id'])){
            $student_id = $_SESSION['user']['student_id'];
            $icon_file = "pic/icon/{$student_id}.jpg"; 
            if (file_exists($icon_file)) {
                echo '<img id="me1" src="' . $icon_file . '" alt="アイコン">
                <img id="me2" src="pic/icon/guest.jpg" alt="デフォルトアイコン">
            
    <textarea name="message" rows="10" cols="50" required placeholder="今なにしてる？"></textarea>
    <button class="post" type="submit">投稿</button>
</form>`;

    modalElement.appendChild(innerElement);
    document.getElementById('modalContainer').appendChild(modalElement);

    // モーダルウィンドウの背景をクリックしたら閉じる
    modalElement.addEventListener('click', (event) => {
        if (event.target === modalElement) {
            closeModalWindow();
        }
    });

    // キャンセルボタンにクリックイベントを追加
    document.querySelector('.modal_message').addEventListener('click', closeModalWindow);
}

// モーダルウィンドウを閉じる関数
function closeModalWindow() {
    if (modalElement) {
        modalElement.remove();
        modalElement = null;
    }
}
const textarea = document.getElementById('message');
const postButton = document.querySelector('.post');

// テキストエリアの入力を監視
textarea.addEventListener('input', () => {
    // テキストが空でない場合はボタンを有効にする
    if (textarea.value.trim() !== '') {
        postButton.disabled = false;
        postButton.classList.remove('disabled');
    } else {
        // テキストが空の場合はボタンを無効にする
        postButton.disabled = true;
        postButton.classList.add('disabled');
    }
});