<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/open.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
    
    <title>ASO PORTAL　|　official</title>
</head>
<body>
   
    <main id="app">
        <img id="topikon" src="img/5.png" class="show" v-show="showImage" @load="fadeImage">
        <div class="content" v-show="showContent">

            <img id="subikon" src="img/5.png">

            <!-- <h1>ようこそ</h1> -->

            <a href="sign-up.php"><button id="touroku">新規登録</button></a>
            <a href="login-input.php"><button id="rogin">　ログイン　</button></a>
           
        </div>
    </main>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="js/top.js"></script>
</body>
</html> 