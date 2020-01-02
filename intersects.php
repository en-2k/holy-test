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
  $orderby = "d.name,em.name";
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
      if (strlen(trim($_val)) > 0){
        if(strtoupper($_key) == "NM_1"){
          $where []= 'upper(em.name) like upper(' . "'%" . str_replace("'","''",$_val) . "%')";
        } else if(strtoupper($_key) == "NM_2"){
          $where []= 'upper(v1.name) like upper(' . "'%" . str_replace("'","''",$_val) . "%')";
        } else if(strtoupper($_key) == "MIN_INTERS"){
          $where []= '(CASE WHEN v1.d_start<v.d_start THEN v.d_start ELSE v1.d_start END)::varchar(10) like upper(' . "'%" . str_replace("'","''",$_val) . "%')";
        } else if(strtoupper($_key) == "DEPT"){
          $where []= 'upper(d.name) like upper(' . "'%" . str_replace("'","''",$_val) . "%')";
        }
      }
    }
    if (count($where)>0){
      $where = ' AND ' . implode(' AND ', $where);
    } else {
      $where = "";
    }
  }
  $query = <<<SQL
SELECT 
  d.name AS dept,
  em.name AS nm_1,
  v1.name AS nm_2, 
  CASE WHEN v1.d_start<v.d_start THEN v.d_start 
  ELSE v1.d_start END AS min_inters 
FROM vacations v 
INNER JOIN (SELECT v1.*,em.* 
            FROM vacations v1 
            INNER JOIN employee em ON em.id=v1.employee_id
            WHERE em.fired IS NULL) v1 
  ON v.employee_id<>v1.employee_id 
INNER JOIN employee em ON em.id=v.employee_id 
INNER JOIN department d ON d.id=em.department_id 
WHERE em.fired IS NULL
  AND d.id=v1.department_id 
  AND v.d_start <= v1.d_end 
  AND v.d_end   >= v1.d_start 
  #where#
ORDER BY #orderby#;  
SQL;
  
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
<p>Періоди відпусток пар співробітників в межах одного підрозділу, що перетинаються</p>
  <table border="1">
  <thead><tr>
    <th><a data-act="SORT" data-attr="dept" href="#">Підрозділ</a></th>
    <th><a data-act="SORT" data-attr="nm_1" href="#">Співробітник 1</a></th>
    <th><a data-act="SORT" data-attr="nm_2" href="#">Співробітник 2</a></th>
    <th><a data-act="SORT" data-attr="min_inters" href="#">Дата перетину відпусток</a></th>
  </tr><tr>
    <td><input type="text" data-act="FILTER" data-attr="dept" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["dept"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["dept"]):"")?>"/></td>
    <td><input type="text" data-act="FILTER" data-attr="nm_1" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["nm_1"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["nm_1"]):"")?>"/></td>
    <td><input type="text" data-act="FILTER" data-attr="nm_2" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["nm_2"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["nm_2"]):"")?>"/></td>
    <td><input type="text" data-act="FILTER" data-attr="min_inters" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["min_inters"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["min_inters"]):"")?>"/></td>
  </tr></thead>
  <tbody>
<?php 
  for ($i = 0; $i < count($rows); $i++){
?>
<tr>
  <td attr="dept"><?php echo  $rows[$i]['dept'];?></td>
  <td attr="nm_1"><?php echo  $rows[$i]['nm_1'];?></td>
  <td attr="nm_2"><?php echo  $rows[$i]['nm_2'];?></td>
  <td attr="min_inters"><?php echo  $rows[$i]['min_inters'];?></td>
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
          url_params += "FILTER["+f+"]="+encodeURIComponent(filter[f])+"&";
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
          url_params += "FILTER["+f+"]="+encodeURIComponent(filter[f])+"&";
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
