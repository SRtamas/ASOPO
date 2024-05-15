<?php
    const SERVER = 'mysql303.phy.lolipop.lan';
    const DBNAME = 'LAA1516798-asopo';
    const USER = 'LAA1516798';
    const PASS = 'asopo';

    $connect = 'mysql:host='. SERVER . ';dbname='. DBNAME . ';charset=utf8';
    $pdo = new PDO($connect, USER, PASS);
?>