new Vue({
  el: '#app', // main要素にid="app"を指定してVue.jsのインスタンスをマウント
  data: {
      imageSrc: '../img/2.png', // 画像のパス
      showImage: true, // 画像の表示状態
      showContent: false, // コンテンツの表示状態
      showikon: true // showikonの初期値を定義
  },
  methods: {
      fadeImage() {
          setTimeout(() => {
              this.showImage = false; // 画像を非表示にする
              setTimeout(() => {
                  this.showContent = true; // コンテンツを表示する
              }, 500); // 3000ミリ秒後にコンテンツを表示
          }, 2500); // 1500ミリ秒後に画像を非表示にする
      }
  },
  mounted() {
      this.fadeImage(); // 初期化時に画像をフェードイン・アウトする
  },
  beforeDestroy() {
    clearTimeout(this.fadeImage); 
  }
});
