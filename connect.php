<?php
$db = 'ikinari';    //書き換えるので   ←↓のようにしておくと良い
$host = 'localhost';
$dsn = "mysql:dbname=$db;host=$host;charset=utf8;";
    $user = 'pikarumina';
    $password = "pochi456web";

//try {   //PDOはtry catch文はエラーを出したくない場合に使用!!

//php組み込みライブラリPDO DB接続ドライバ
$dbh =new PDO($dsn, $user ,$password) ;
//エミュレート機能 はオフ
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
//PDOエラーモードのオン。 デフォルトに入れておく!
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//PDOオブジェクト自体に指定。レスポンスは常に連想配列形式のみ取得するようになる
//$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


// ↑必須  ↓必須ではない
//$dbh->query('SET NAMES utf8');  //これは4行目に組み込むため不要

//} catch (PDOException $e) {
//    echo 'Connection failed: ' . $e->getMessage();
//}