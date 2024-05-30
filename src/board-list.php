<?php
session_start();
require "db-connect.php";
if(empty($_SESSION['user'])){
    $redirect_url = 'https://aso2201203.babyblue.jp/ASOPO/src/top.php';
            header("Location: $redirect_url");
            exit();
  }
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table>
        <?php
        $pdo = new PDO($connect, USER, PASS);
        foreach ($pdo->query('select * from Board') as $row) {
            if(isset($row['board_password'])){
                $pass_dis = '🔒';
            }else{
                $pass_dis ='';
            }
            echo '<tr>';
            echo '<td><a href="thread.php?id=', $row['board_id'], '">', $row['board_id'], '</a></td>';
            echo '<td><a href="thread.php?id=', $row['board_id'], '">', $row['board_name'], '</a></td>';
            echo '<td>',$pass_dis,'</td>';
            echo '<td>',$row['student_id'],'</td>';
            echo '</tr>';
        }
        ?>
    </table>
</body>

</html>
