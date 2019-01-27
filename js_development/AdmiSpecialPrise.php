<!DOCTYPE html >
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <title>特価情報管理ページ</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <link rel="stylesheet" href="base.css" type="text/css" media="screen" />
    <script src="http://code.jquery.com/jquery.min.js"></script>
    <script type = "text/javascript">
      var data_insert =[];
      var select1_data =["野菜・果物","肉・卵","魚介類","米・パン・粉類",
                        "乳製品","惣菜","インスタント・レトルト",
                        "菓子・冷凍","飲料水","その他(食品)","その他(食品外)"];
      var select2_data =[["野菜","キャベツ","きゅうり"],["肉","牛肉","鶏肉","豚肉"],["リンゴ"],["刺身"]];
        <?php //include("??Sale.php") ?>//;
      /*  var test = '<?php// echo $sale_data;?>';
        var mydata = JSON.parse(test);
        console.log(mydata);
        for (var cat_con = 0;cat_con < 11;cat_con++) {
          select2_data[cat_con] =[];
          select2_data[cat_con][0] = select1_data[i];
          for (var j = 0; j < mydata["price"].length;j++) {
            if(mydata["price"][j]["categry_id"] == cat_con)
          select2_data[cat_con][j+1] = mydata["price"][j]["name"];
            }
          }*/
          window.addEventListener("load", function(){
            var store_id = 2;
            var data = {
              store_id : store_id
            }
            $.ajax({
              type:"post",
              url:"showSpecialPrice.php",
              data:JSON.stringify(data),
              contentType: 'Content-Type: application/json; charset=UTF-8',
            }).done(
              function(data){
                console.log(data);
                //var log = JSON.parse(data);
                if(data != "null") {
                  var mydata = JSON.parse(data);
                  console.log(mydata);
                  for (var i = 0; i < mydata["sp_price"].length; i++){
                    data_insert[i] = [];
                    data_insert[i][0] = select1_data[mydata["sp_price"][i]["b.category_id"] - 1];
                    data_insert[i][1] =mydata["sp_price"][i]["b.name"];
                    data_insert[i][2] =mydata["sp_price"][i]["b.price"];
                    if (mydata["sp_price"][i]["rateFlg"] ==  "1") {
                      data_insert[i][3] =data_insert[i][2] * (1 - mydata["sp_price"][i]["a.discntVal"] * 0.01);
                    } else {
                      data_insert[i][3] =data_insert[i][2] - mydata["sp_price"][i]["a.discntVal"];
                    }
                  }
                  creatTable(data_insert);
                } else {
                  alert("情報取得ができませんでした。リロードしてください");
                  console.log(data);
                }

              }).fail(
              function() { //失敗した時
                alert("通信エラーです。もう一度入力してください");
              }
            );
          <?php //include("showSpecialPrice.php") ?>;
          //var test = '<?php// echo $spPrice_data;?>';

    //   }
    }, false)
       </script>

  <script type = "text/javascript">
  function SetChoice1() {
      var select1 = document.forms.formName.selectName1; //変数select1を宣言
      select1.options.length = 0;
      select1.options[0] = new Option("選択してください");
      var select2 = document.forms.formName.selectName2; //変数select2を宣言


      for(var i = 0;i<select1_data.length - 1;i++){
        select1.options[i+1]=new Option(select1_data[i]);
      }

  }
  function SetChoice2() {
    var select1 = document.forms.formName.selectName1;
    var select2 = document.forms.formName.selectName2; //変数select1を宣言
    select2.options.length = 0;
    select2.options[0] = new Option("選択してください");

    for(var i = 0;i<select1_data.length-1;i++){
      if(select1.options[select1.selectedIndex].value != select1_data[i]) continue;
      for (var j = 0;j <select2_data[i].length;j++)
      select2.options[j+1]=new Option(select2_data[i][j+1]);
    }
  }
  function creatdata() {
    if((count_col || editFlag) != 1 ){
      var select1 = document.forms.formName.selectName1;
      var select2 = document.forms.formName.selectName2; //変数select1を宣言
      var select3 = document.forms.formName.selectName3;
      var sel1 = select1.options[select1.selectedIndex].value;
      var sel2 = select2.options[select2.selectedIndex].value;
      var sel3 = select3.options[select3.selectedIndex].value;
          if ((sel1 || sel2 || sel3)  == "選択してください"){//空白チェック
           alert("選択してください");
         } else {
            var data = [sel1, sel2, sel3];
            creatTable(data);
          }
        }
  }
  function creatTable(data){
    // 表の作成開始
    var table = document.getElementById("table");
    // 表に2次元配列の要素を格納
    table.style.border ="1px solid";         //枠
      for (var i = 0; i<data.length; i++){
      var rows = table.insertRow(-1);        // 新しい行の追加//-1で下に追加する
        for(var j = 0; j < data[0].length; j++){
          var  cell=rows.insertCell(-1);    //列
            cellNode = document.createTextNode(data[i][j]);
            cell.appendChild(cellNode);     //データノードの作成、ノードの連結
            cell.style.border ="1px solid"; //枠
        }
      }
  }

  var count_col=0;
  var editFlag = 0;
  function deleteTable() {
    count_col +=1;
    if(count_col ==1){


    var table = document.getElementById("table");
    var rows = table.rows.length;


    //
    for (var i = 0; i < rows; i ++) {
      if(i == 0)continue;
      var cell = table.rows[i].insertCell(-1);

  //  alert();
  cell.innerHTML = '<input type="button" value="削除" name="addDelete" onclick="dB(this)">';
    }
    var newButton = document.createElement("BUTTON");
    newButton.textContent="編集終了";
    document.getElementById("table").appendChild(newButton);
    //--編集終了のボタンを押したときの反応
              newButton.onclick= function() {
                var table = document.getElementById("table");
                var rows = table.rows.length;
    // 削除を削除
                for ( var i = 0; i < rows; i++) {
                  if (i == 0) continue;
                  table.rows[i].deleteCell(-1);
                }
                newButton.parentNode.removeChild(newButton);

                count_col = 0;

    }
}

  }

  function dB(obj) {
    tr = obj.parentNode.parentNode;
    tr.parentNode.deleteRow(tr.sectionRowIndex);
  }

  function SetUpdataSpecialSale(){
    editFlag +=1;
    if(editFlag ==1){


    var table = document.getElementById("table");
    var rows = table.rows.length;


    //
    for (var i = 0; i < rows; i ++) {
      if(i == 0)continue;
      var cell = table.rows[i].insertCell(-1);

  //  alert();
  cell.innerHTML = '<input type="button" value="soldout" name="addDelete" onclick="chengeColor(this)">';
    }
    var newButton = document.createElement("BUTTON");
    newButton.textContent="編集終了";
    document.getElementById("table").appendChild(newButton);
    //--編集終了のボタンを押したときの反応
              newButton.onclick= function() {
                var table = document.getElementById("table");
                var rows = table.rows.length;
    // 削除を削除
                for ( var i = 0; i < rows; i++) {
                  if (i == 0) continue;
                  table.rows[i].deleteCell(-1);
                }
                newButton.parentNode.removeChild(newButton);

              editFlag= 0;

    }
}
}
function chengeColor(obj) {
  tr = obj.parentNode.parentNode;
  tr.style.color = "red";
}
  </script>
</head>


<!--以下通常のドロップボタン-->
<body bgcolor onLoad="SetChoice1()">
  <div id="wrapper">
  <div id="contents">
   <h3> <p class="logo">*** 株式会社マルナカ | 特価情報管理ページ***</p></h3>
    <p class="description">*** 管理者専用ページです。特価情報の編集を行えます ***</p>
      <h5>特価情報選択</h5>
<form name="formName" method="post" action="./pathToProgramFile">
<!--選択肢その1-->
<select name = "selectName1" onChange="SetChoice2()">

</select>

<!--選択肢その2（選択肢その1の項目によって変化）-->
<select name = "selectName2">
  <option value = "選択してください">選択してください</option>
</select>
<select name = "selectName3">
<option value = "選択してください">選択してください</option>
<option value = "10%">10%</option>
<option value = "20%">20%</option>
<option value = "30%">30%</option>
<option value = "40%">40%</option>
<option value = "50円引き">50%</option>
<option value = "10円引き">10円引き</option>
<option value = "20円引き">20円引き</option>
<option value = "30円引き">30円引き</option>
<option value = "50円引き">50円引き</option>
<option value = "100円引き">100円引き</option>
<option value = "150円引き">150円引き</option>

</select>
<input name="button" type="button" value="登録" onClick="creatdata()">
<h5>登録された商品</h5>
<input name="delete" type="button" value="登録情報削除" onClick="deleteTable()">
<input name="delete" type="button" value="品切れ商品の登録" onClick="SetUpdataSpecialSale()">
<br><br>
<table border="1" id = "table">
  <tr>
    <th>商品カテゴリ</th><th>商品名</th><th>定価</th><th>商品価格</th>
  </tr>
  <div align="right">
   <input name="button" type="button" value="ログアウト" onClick="logout()">
 </div>
</table>
</body>
</html>
