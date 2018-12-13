<?php
if( !empty($_GET['code']) ){   //空じゃないよ指定する 空のcodeを予め認証させる

//1.connect.phpでDBをつなぐ
require_once('connect.php');

//2.denpyoとtokuiに対してSQL文発行
$sql="SELECT `denpyo_id`, denpyo.`tokui_id`, tokui_name,tokui_addr,`hiduke` 
FROM `denpyo` 
	LEFT JOIN tokui
    ON denpyo.tokui_id = tokui.tokui_id
WHERE denpyo_id = ?";
$stmt = $dbh->prepare($sql);    //プリペアドステートメント
$stmt->bindValue(1 ,$_GET['code'],PDO::PARAM_INT);    //これで様々な伝票IDから表示が変えられる
$stmt->execute();               //SQLの実行

//ASSOCはクエリ結果をフィールド名の連想配列で取り出す
$row = $stmt->fetch(PDO::FETCH_ASSOC);  
echo "<p>","伝票番号:",$row["denpyo_id"];
echo "<p>","得意先コード:",$row["tokui_id"];
echo "<p>","得意先名:",$row["tokui_name"];
echo "<p>","得意先住所:",$row["tokui_addr"];
echo "<p>","日付:",$row["hiduke"];

//課題 下半分をココに書き出す

$sql="SELECT shosai.shohin_id as 商品コード
,shohin_name as 商品名, num as 数量
FROM `shosai`
LEFT JOIN shohin
ON shohin.shohin_id = shosai.shohin_id
WHERE shosai.denpyo_id = ?";

$stmt = $dbh->prepare($sql);    //プリペアドステートメント
$stmt->bindValue(1 ,$_GET['code'],PDO::PARAM_INT);
$stmt->execute();  

//tableの作成
echo "<table border='1'>
    <tr><th>商品コード</th><th>商品名</th><th>数量</th></tr>";
    foreach ( $stmt as $key => $v){
        echo 
        "<tr><td>{$v['商品コード']}</td>
             <td>{$v['商品名']}</td>
             <td>{$v['数量']}</td></tr>";  
    }
        echo "</table>";
        //exit;    //処理を中断する命令  印刷するとき、これより下は印刷されない
}  //GETがあれば if end
?>

<form>
<label>伝票番号</label>
<input type="number" name="code">   <!--  $stmt->bindValue(1 ,$_GET['code'],PDO::PARAM_INT);でcodeのため -->
<input type="submit" value="検索">
</form>


//3.2の戻り値を表示する
//4.shosaiとshohinに対してSQL文発行
//5.3の戻り値をループしてテーブル状に表示する
//6.色と幅をつける

<pre>
データが増えたら、伝票を発行してこれらも試してみる
ソート ORDER BY
行制限 LIMIT