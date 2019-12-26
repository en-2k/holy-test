<!doctype html>
<html lang="uk">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Тестове завдання</title>
  </head>
  <body>
<p><a href="index.php">&lt; Повернутися на головну</a></p>
<?php
  if($err){
    ?><p><?php echo $err; ?></p><?php
  }
  $conn = pg_connect($_ENV['DATABASE_URL']);
  if (!$conn){
?>
Помилка. Не вдалося з'єднатися з Postgres.
<br/>
<?php
    exit;
  }
  $orderby = "id desc";
  $where = "";
  if (isset($_GET['SORT']) && is_array($_GET['SORT'])){
    $orderby = [];
    foreach($_GET['SORT'] as $_key => $_val){
      $orderby []= $_key . ' ' . $_val;
    }
    $orderby = implode(',', $orderby);
  }
  if (isset($_GET['FILTER']) && is_array($_GET['FILTER'])){
    $where = [];
    foreach($_GET['FILTER'] as $_key => $_val){
      if ($_key == "id" && intval($_val) > 0){
        $where []= "id = ".intval($_val);
        continue;
      }
      if (strlen(trim($_val)) > 0){
        $where []= 'upper(' . $_key . '::varchar(64)) like upper(' . "'" . str_replace("'","''",$_val) . "')";
      }
    }
    if (count($where)>0){
      $where = ' AND ' . implode(' AND ', $where);
    } else {
      $where = "";
    }
  }
  $query = "select * from protocol where true #where# order by #orderby#";
  $result = pg_query($conn,str_replace("#orderby#",$orderby,str_replace("#where#",$where,$query)));
  if ($result === FALSE){
    print pg_last_error($conn);
    pg_close($conn);
    exit;
  }
  $rows = [];
  while ($row = pg_fetch_assoc($result)) {
    $rows []= $row;
  }
  pg_free_result ( $result );
  pg_close($conn);
?>
<p>Протокол дій</p>
  <table border="1">
  <thead><tr>
    <th><a data-act="SORT" data-attr="id" href="#">ID</a></th>
    <th><a data-act="SORT" data-attr="stamp" href="#">Дата/час</a></th>
    <th><a data-act="SORT" data-attr="act" href="#">Подія</a></th>
    <th><a data-act="SORT" data-attr="table_name" href="#">Таблиця</a></th>
    <th><a data-act="SORT" data-attr="table_id" href="#">Ключ</a></th>
    <th><a data-act="SORT" data-attr="descr" href="#">Деталі</a></th>
  </tr><tr>
    <td><input type="text" data-act="FILTER" data-attr="id" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["id"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["id"]):"")?>"/></td>
    <td><input type="text" data-act="FILTER" data-attr="stamp" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["stamp"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["stamp"]):"")?>"/></td>
    <td><input type="text" data-act="FILTER" data-attr="act" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["act"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["act"]):"")?>"/></td>
    <td><input type="text" data-act="FILTER" data-attr="table_name" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["table_name"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["table_name"]):"")?>"/></td>
    <td><input type="text" data-act="FILTER" data-attr="table_id" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["table_id"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["table_id"]):"")?>"/></td>
    <td><input type="text" data-act="FILTER" data-attr="descr" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["descr"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["descr"]):"")?>"/></td>
  </tr></thead>
  <tbody>
<?php 
  for ($i = 0; $i < count($rows); $i++){
?>
<tr>
  <td attr="id"><?php echo  $rows[$i]['id'];?></td>
  <td attr="stamp"><?php echo  $rows[$i]['stamp'];?></td>
  <td attr="act"><?php echo  $rows[$i]['act'];?></td>
  <td attr="table_name"><?php echo  $rows[$i]['table_name'];?></td>
  <td attr="table_id"><?php echo  $rows[$i]['table_id'];?></td>
  <td attr="descr"><pre><?php echo  $rows[$i]['descr'];?></pre></td>
</tr>
<?php
  }
?>
  </tbody>
  </table>
  <script>
    var filter_doms = document.querySelectorAll('input[data-act="FILTER"]');
    var sort_doms = document.querySelectorAll('a[data-act="SORT"]');
    var filter = <?php echo ((isset($_GET['FILTER']))? json_encode($_GET['FILTER']): "{}"); ?>;
    var sort = <?php echo ((isset($_GET['SORT']))? json_encode($_GET['SORT']): "{}"); ?>;
    for (var i = 0; i < filter_doms.length; i++){
      var durl = document.URL;
      filter_doms[i].onchange = function(){
        var attr = this.getAttribute("data-attr");
        filter[attr] = this.value;
        var url_params = "?";
        for(var f in filter){
          url_params += "FILTER["+f+"]="+filter[f]+"&";
        }
        for(var s in sort){
          url_params += "SORT["+s+"]="+sort[s];
          break;
        }
        document.location = document.URL.replace(/\?.*$/,"") + url_params;
        return false;
      }
    }
    for (var i = 0; i < sort_doms.length; i++){
      sort_doms[i].onclick = function(){
        var attr = this.getAttribute("data-attr");
        sort[attr] = ((sort[attr] === "ASC")? "DESC":"ASC");
        var url_params = "?";
        for(var f in filter){
          url_params += "FILTER["+f+"]="+filter[f]+"&";
        }
        for(var s in sort){
          if(s !== attr){ continue; }
          url_params += "SORT["+s+"]="+sort[s];
        }
        document.location = document.URL.replace(/\?.*$/,"") + url_params;
        return false;
      };
    }
  </script>
  </body>
</html>
