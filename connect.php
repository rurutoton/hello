<?php 
// データベースへの接続を確認
//教科書p214 リスト12.1よりも↓の書き方を推奨
$host = 'localhost';
$dbname = 'yoyaku';     //DB名
$user = 'p';
$pswd = '********'; //接続情報は変数にいれましょう

try{  //つながったら有効にする
$dsn="mysql:dbname=$dbname;host=$host;charset=utf8";
$dbh=new PDO($dsn,$user,$pswd);
//PDOのエラーモードをONにする
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
//エミュートOFF、構文チェックと実行を分離する(必須) p223参照 書かなくてもサーバーには送れる、セキュリティ上は必須なので書くべき。
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
// データベースへの接続を確認 P214 のコード
//var_dump($dbh); //object(PDO)と出てればOK

//try catch文は接続エラーの原因が表記されずにわからなくなるので、最初は無効にしておく
}catch(PDOException $e){
    die('接続エラー: ' . $e->getCode());
}
