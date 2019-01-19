<?php //toroku.php
session_start();
if( empty($_SESSION["name"] )){
    echo "入力情報がありません<a href='yoyaku.php'>予約ページ</a>から必須事項を入れて送信してください。";
    exit();
} 
// DB接続も外部参照 DB接続 ファイルとして準備する→インクルード
//todoで書いたファイルをコピーして使う
    include("connect.php");
//var_dump($_SESSION);  //試しに全部出す
//var_dump($_POST);  //試しに全部出すarray(0) 

try {
    $dbh->beginTransaction();   //トランザクション開始
// → DB登録 ( cst , yyk )
//1 SQL文 cstにインサート
    $sql="INSERT INTO cst (name,kana, zip, addr, email, tel )values(
        ?,?,?,?,?,? )";
       
//ブリペアドステートメント 
    $sth= $dbh->prepare($sql);
//バインド機構
    $i=0;//↓の++$1=1から始まる
    $sth->bindValue(++$i, $_SESSION['name'],PDO::PARAM_STR);
    $sth->bindValue(++$i, $_SESSION['kana'],PDO::PARAM_STR);
    $sth->bindValue(++$i, $_SESSION['zip'],PDO::PARAM_STR);
    $sth->bindValue(++$i, $_SESSION['addr'],PDO::PARAM_STR);
    $sth->bindValue(++$i, $_SESSION['email'],PDO::PARAM_STR);
    $sth->bindValue(++$i, $_SESSION['tel'],PDO::PARAM_STR);
//エクスキュート    ※エラーを探す時は↓より↑でvar_dump();で調べていくこと
//var_dump($sql,$_SESSION['name'],$_SESSION['kana'],
//$_SESSION['zip'],$_SESSION['addr'],$_SESSION['email'], $_SESSION['tel']);
    
    $sth->execute();//SQL文の実行 挿入出来たらtrueなので
//最後のオートインクリメントを取得(顧客ID取得している)
    $kid = $dbh->lastInsertId('kid');

// 2 SQL文 yykにインサート   
$sql="INSERT INTO yyk(course, yoyakuji, ninzu, yobo,kid) VALUES (?,?,?,?,?) ";
//プリペアドステートメント
  $sth = $dbh->prepare($sql);
//   バインド機構
    $i=0;
    $sth->bindValue(++$i, $_SESSION['course'],PDO::PARAM_STR);
    $sth->bindValue(++$i, $_SESSION['yoyakuji'],PDO::PARAM_STR);
    $sth->bindValue(++$i, $_SESSION['ninzu'],PDO::PARAM_INT);
    $sth->bindValue(++$i, $_SESSION['yobo'],PDO::PARAM_STR);
    $sth->bindValue(++$i, $kid,PDO::PARAM_INT);
//   エクスキュート
    $sth->execute();
// 3 cst に入って yykに入らない事態をさける方法(トランザクション)※試験に出る
    
    $dbh->commit(); //コミットで全て実行
//送信成功したらありがとうの画面で終わる
//メール送信(予約者、管理者)
echo "ご予約ありがとうございました";

} catch (Exception $e) {
    $dbh->rollBack();   //ロールバック 全てなかったことになる
    echo "失敗しました。" . $e->getMessage();
  }//tryの終わり

//セッションの削除
$_SESSION = array();     //セッションを空にする
session_destroy();      //セッションを完全に破壊する
   
/*書けるところまで書いてみよう
1 SQL文 cstにインサート
    プリペアドステートメント
    バインド機構
        エクスキュート
            最後のオートインクリメントを取得(ググる)
2 SQL文 yykにインサート
    以下は同じ
*/

/*ロジック
    1 cst , yyk どっちに先にいれるべき?  →  cstが先
    2 自動連番で生成したkidを取得してyykに入れる方法
        → 次のA_Iの番号を取得する関数をいう
    3 cst に入って yykに入らない事態を避ける方法(トランザクション機能)
        トランザクション→コミット→ロールバック
*/
// 	→ メール送信 (予約者, 管理者)
//   → "ありがとう"の画面で終わる
//   → セッション削除
