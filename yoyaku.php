<?php
session_start();
//トークンの作成  → セッションと非表示フィールドへ
  //関数を呼び出す前にインクルードしてください
  // ランダム文字列生成  ※外部参照
include("makerandstr.php"); //使い回すので

  $_SESSION["himitsu"]= makeRandStr(20);

?>
<!DOCTYPE html>
<html lang="ja">
<head>  
<meta  charset=utf-8> <!--HTML5での書き方-->
<!--どこのサイトにも標準で設定されているので、検証してコピペしましょう
    デバイスピクセルに対して等倍になる指定  ビューポート指定-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- カレンダライブラリ のスタイル --> 
<link rel="stylesheet" type="text/css" href="./jquery.datetimepicker.css"/>
<title>予約フォーム</title>

<!--stylw.cssを作って幅や行間を直す-->
<link rel="stylesheet" href="style.css">

</head><body>
<form action="kakunin.php" method="post">
  <!--作成したトークンの読み込み位置-->
  <input type="hidden" name="himitsu" value="<?=$_SESSION["himitsu"]?>">
<!--コース選択フォーム-->
  <div>
    <label>希望コースの選択:</label>
    <select name="course" required>
    <option value="">コースを選んでください</option>
    <option>ディナー/伊勢志摩の祝宴コース ￥18500</option>
    <option>ディナー/神楽坂の晩餐コース ￥15000</option>
    <option>ディナー/神楽坂の晩餐コース ￥13500</option>
  </select></div>
<!--ご予約日時フォーム-->
<div>
  <label>ご予約日時:</label>
  <input type="text" name="yoyakuji" id="yyk_dhms" placeholder="年/月/日 時間" size="24" autocomplete="off" required>
</div>
<!--予約人数選択フォーム-->
<div>
  <label>ご予約人数:</label>
  <select name="ninzu" required>
  <option value="">ご利用人数</option>
    <option>1</option>
    <option>2</option>
    <option>3</option>
    <option>4</option>
  </select> 名様
</div>
<!--メールアドレス入力フォーム-->
<div>
  <label>メールアドレス:</label>
  <input type="email" name="email" id="" required>
</div>

<div>
    <label>お電話:</label>
    <input type="tel" name="tel" id="">
  </div>

<div>
  <label>郵便番号:</label>
  <input type="text" name="zip" size="10" maxlength="8" onkeyup="AjaxZip3.zip2addr(this,'','addr','addr');" required></div>
  <!--メソッドの引数が住所フィールドのname属性を示している'都道府県'+'住所'-->
  <!-- キーボードの何れかのキーを放した時のイベント -->
<div>
  <label>住所:</label>
  <input type="text" name="addr">
</div>

<div>
  <!--姓名をfirstnameとlastnameで分けていたが、
  ライブラリが別れているわけではないため、nameで統一する。
  ライブラリとスクリプトはid属性で結びついている-->
  <label>ご氏名:</label><input type="text" name="name" id="name" required />
  フリガナ:<input type="text" name="kana" id="name-furigana" /><br>
</div>

<div>
  <span role="label">ご要望:</span>
  <textarea name="yobo" id="" cols="50" rows="5"></textarea>
</div>
<button type="submit">確認画面へ</button>
</form>

<!-- 郵便番号検索のライブラリ読み込み -->
<script src="http://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
<!--ライブラリ共通読み込み-->
<script src="./jquery.js"></script>  
<!--日時カレンダーライブラリ読み込み-->
<script src="./jquery.datetimepicker.js"></script> 
<!--自動でふりがなライブラリ読み込み-->
<script src="jquery.autoKana.js"></script> 
<!--バリデートライブラリ読み込み-->
<script src="jquery.validate.js"></script>
<!--バリデートライブラリを読み込むためのライブラリ-->
<script src="localization/messages_ja.js"></script>

<script>
$(function(){ //無名関数=呼び出し不要,読み込み時に即時実行
//フォーム指定
$('form').validate({
rules:{
      email:{         //フォームのネーム属性名を合わせる
        required:true,
        email:true
    },
    ninzu:{   //予約人数
        required:true,
        range:[1,4]// 最⼩数、最⼤数を同時に設定します
    },
      tel:{//電話番号
          required:true,
          digits:true,  //整数以外はNG
          rangelength:[10,12]  //文字数範囲
      },
      zip:{//〒
          required:true,
          digits:true,  //整数以外はNG
          rangelength:[7,7]  //文字数範囲
      },
      email:{//email
        required:'メールアドレスを入力してください',
        email:'メールアドレスを正確に入力してください'
      }
      },
//エラーメッセージの表示
messages:{
     
      ninzu:{//予約人数
        required:'番号を入力してください',
        range:'1以上、3以下の番語を入力してください'
    },
    tel:{//電話番号
        required:'電話番号(数字)を入力してください',
        digits:'整数で入力してください'
        rangelength:'10~12桁で入力してください'
    },
      zip:{//郵便番号用
          required:'郵便番号を入力してください',
          digits:'整数7桁で入力してください',
          rangelength:'7桁で入力してください'
          }
    },
     
  //エラーメッセージ出力箇所設定
  //errorPlacement:function(error, element){
  //ここにエラーメッセージの出力箇所を設定
  //error.appendTo($('p'));
   //}
  });
})
</script>
<script>
//タグ内に要素が出てくるので、ユーザビリティはセーフ
$("label").append("<em>必須</em>"); 
//自動ふりがな入力スクリプト開始
//姓名をfirstnameとlastnameで分けていたが、
//ライブラリが別れているわけではないため、nameで統一する。
//ライブラリとスクリプトはid属性で結びついている
    $(function() {
      $.fn.autoKana('#name', '#name-furigana', {katakana:true});
          });

//日時カレンダースクリプト開始
$('#yyk_dhms').datetimepicker({
lang:'ja',
//format:			'Y-m-d H:i',
minDate : '-1970/01/01',
maxDate : '+1970/01/31',
//timepicker:false
allowTimes : ['17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30','22:00'],
//step : 30
}); 

</script>

</body>
</html>








