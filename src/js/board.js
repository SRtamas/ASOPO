const ham = document.querySelector('#js-hamburger');
const nav = document.querySelector('.header__nav');
const navContent = document.querySelector('.nav_content');

ham.addEventListener('click', function () {
    ham.classList.toggle('active');
    nav.classList.toggle('active');
    navContent.classList.toggle('active'); // 追加
});


document.addEventListener('DOMContentLoaded', function () {
    const uproadButton = document.querySelector('button[name="uproad"]');
    const anotherButton = document.getElementById('uproad_photo');

    // アップロードボタンをクリックしたときの処理
    uproadButton.addEventListener('click', function () {
        if (anotherButton.style.display === 'block') {
            anotherButton.style.display = 'none'; // 別のボタンを非表示にする
        } else {
            anotherButton.style.display = 'block'; // 別のボタンを表示する
        }
    });
});


document.getElementById('uproad_photo').addEventListener('click', function() {
    document.getElementById('fileInput').click(); // ファイル選択ダイアログを開く
});

document.getElementById('fileInput').addEventListener('change', function() {
    // 選択されたファイルを処理するコード
    const selectedFile = this.files[0]; // 選択されたファイルを取得

    // テキストボックスにファイル名を表示する
    const textBox = document.querySelector('.textbox');
    textBox.value = selectedFile.name; // ファイル名をテキストボックスに表示
});