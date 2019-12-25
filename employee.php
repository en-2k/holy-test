<?php
  $err = false;
  if(isset($_GET['UPDATE']) && isset($_GET['id']) && intval($_GET['id']) > 0){
    $conn = pg_connect($_ENV['DATABASE_URL']);
    if (!$conn){
      $err = 'Помилка з\'єднання з Postgres.';
    } else {
      $name = null;
      $todo = false;
      $sql = "UPDATE employee SET ";
      if (isset($_GET['name'])){
        $name = "'" . str_replace("'", "''", $_GET['name']) .  "'";
        $sql .= "name = " . $name;
        $todo = true;
      }
      if (isset($_GET['dept']) && intval($_GET['dept']) > 0){
        $dep_id = intval($_GET['dept']);
        $sql .= "department_id = " . $dep_id;
        $todo = true;
      }
      if (isset($_GET['fired'])){
        $fired = "'" . str_replace("'", "''", $_GET['fired']) .  "'::date";
        $sql .= "fired = " . $fired;
        $todo = true;
      }
      $sql .= " WHERE id = ".(intval($_GET['id']));
      if ($todo){
        $result = pg_query($conn,$sql);
        if ($result === FALSE){
          $err = pg_last_error($conn);
        }
      }
      pg_close($conn);
    }
  }
  if(isset($_GET['INSERT'])){
    $conn = pg_connect($_ENV['DATABASE_URL']);
    if (!$conn){
      $err = 'Помилка з\'єднання з Postgres.';
    } else {
      $name = null;
      $todo = false;
      $sql = "INSERT INTO eployee(name,department_id,fired) VALUES( ";
      if (isset($_GET['name']) && strlen(trim($_GET['name'])) > 0){
        $name = "'" . str_replace("'", "''", $_GET['name']) .  "'";
        $sql .= $name;
        $todo = true;
      }
      if (isset($_GET['department_id']) && intval($_GET['department_id']) > 0){
        $dep_id = intval($_GET['department_id']);
        $sql .= "," . $dep_id;
      } else {
        $sql .= ",NULL";
      }
      if (isset($_GET['fired'])){
        $fired = "'" . str_replace("'", "''", $_GET['fired']) .  "'::date";
        $sql .= "," . $fired;
      } else {
        $sql .= ",NULL";
      }
      $sql .= " ) ";
      if ($todo){
        $result = pg_query($conn,$sql);
        if ($result === FALSE){
          $err = pg_last_error($conn);
        }
      }
      pg_close($conn);
    }
  }
  if(isset($_GET['DELETE']) && isset($_GET['id']) && intval($_GET['id']) > 0){
    $conn = pg_connect($_ENV['DATABASE_URL']);
    if (!$conn){
      $err = 'Помилка з\'єднання з Postgres.';
    } else {
      $sql = "DELETE FROM employee ";
      $sql .= " WHERE id = ".(intval($_GET['id']));
      $result = pg_query($conn,$sql);
      if ($result === FALSE){
        $err = pg_last_error($conn);
      }
      pg_close($conn);
    }
  }
  
?>
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
  $orderby = "id";
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
      if ($_key === 'id' && intval($_val) > 0){
        $where []= "e." . $_key . '=' . $_val;
      } else if (strlen(trim($_val)) > 0){
        if(strtoupper($_key) == "DEPT"){
          $where []= 'upper(dep.name) like upper(' . "'" . str_replace("'","''",$_val) . "')";
        } else if (strtoupper($_key) == "NAME"){
          $where []= 'upper(e.name) like upper(' . "'" . str_replace("'","''",$_val) . "')";
        } else if (strtoupper($_val) == "NULL"){
          $where []= $_key . ' IS NULL';
        } else {
          $where []= 'upper(' . $_key . '::varchar(255)) like upper(' . "'" . str_replace("'","''",$_val) . "')";
        }
      }
    }
    if (count($where)>0){
      $where = ' AND ' . implode(' AND ', $where);
    } else {
      $where = "";
    }
  }
  $result = pg_query($conn,"SELECT e.id,e.name,dep.name as dept,e.fired FROM employee e LEFT JOIN department dep ON dep.id=e.department_id WHERE true " . $where . " ORDER BY " . $orderby);
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
  $result = pg_query($conn,"SELECT id,name FROM department ORDER BY name");
  if ($result === FALSE){
    print pg_last_error($conn);
    pg_close($conn);
    exit;
  }
  $depts = [];
  while ($row = pg_fetch_assoc($result)) {
    $depts []= $row;
  }
  pg_close($conn);
?>
<button id="INSERT">Додати співробітника</button>
  <table border="1">
  <thead><tr>
    <th></th>
    <th><a data-act="SORT" data-attr="id" href="#">id</a></th>
    <th><a data-act="SORT" data-attr="name" href="#">ПІБ</a></th>
    <th><a data-act="SORT" data-attr="dept" href="#">Підрозділ</a></th>
    <th><a data-act="SORT" data-attr="fired" href="#">Звільнено</a></th>
  </tr><tr>
    <td></td>
    <td><input type="text" data-act="FILTER" data-attr="id" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["id"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["id"]):"")?>"/></td>
    <td><input type="text" data-act="FILTER" data-attr="name" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["name"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["name"]):"")?>"/></td>
    <td><input type="text" data-act="FILTER" data-attr="dept" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["dept"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["dept"]):"")?>"/></td>
    <td><input type="text" data-act="FILTER" data-attr="fired" value="<?php echo ((isset($_GET["FILTER"]) && isset($_GET["FILTER"]["fired"]))? 
      str_replace('"',"&quot;",$_GET["FILTER"]["fired"]):"")?>"/></td>
  </tr></thead>
  <tbody>
<?php 
  for ($i = 0; $i < count($rows); $i++){
?>
<tr>
  <td><select class="act" data-id="<?php echo $rows[$i]['id']; ?>">
    <option value="ACT">--Дії--</option>
    <option value="UPDATE">Редагувати</option>
    <option value="DELETE">Видалити</option>
  </select></td>
  <td attr="id"><?php echo  $rows[$i]['id'];?></td>
  <td attr="name"><?php echo  $rows[$i]['name'];?></td>
  <td attr="dept"><?php echo  $rows[$i]['dept'];?></td>
  <td attr="fired"><?php echo  $rows[$i]['fired'];?></td>
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
    var doms = document.querySelectorAll("select.act");
    for (var i = 0; i < doms.length; i++){
      doms[i].onchange = function(){
        var id = this.getAttribute("data-id");
        if(this.value == "UPDATE"){
          var tds = this.parentNode.parentNode.querySelectorAll("td");
          for (var j = 1; j < tds.length; j++){
            var attr = tds[j].getAttribute("attr");
            if (attr == "id"){
              continue;
            }
            var val = tds[j].innerHTML;
            if (attr === "dept"){
              tds[j].innerHTML = '<select data-id="'+id+'" data-attr="'+attr+'" id="'+attr+'_'+id+'"></select>';
              var depts = <?php echo json_encode($depts); ?>;
              var sel = document.getElementById(attr+'_'+id);
              for (var k = 0; k < depts.length; k++){
                var opt = document.createElement("OPTION");
                opt.value = depts[i].id;
                opt.innerText = depts[i].name;
                if(val === depts[i].name){
                  opt.setAttribute("selected","selected");
                }
                sel.appendChild(opt);
              }
            } else {
              tds[j].innerHTML = '<input type="text" data-id="'+id+'" data-attr="'+attr+'"  value="" />';
            }
            tds[j].querySelector("INPUT").value = val;
          }
          var parent = this.parentNode;
          var butt = document.createElement('BUTTON');
          butt.setAttribute("data-id",id);
          butt.setAttribute("data-act",this.value);
          butt.innerText = "зберегти";
          butt.onclick = function(){
            var id = this.getAttribute('data-id');
            var act = this.getAttribute('data-act');
            var url_params = "?";
            for(var f in filter){
              url_params += "FILTER["+f+"]="+filter[f]+"&";
            }
            for(var s in sort){
              url_params += "SORT["+s+"]="+sort[s];
            }
            url_params += "&"+act+"=&id="+id;
            var inputs = this.parentNode.parentNode.querySelectorAll('input[data-id="'+id+'"],select[data-id="'+id+'"]');
            for (var k = 0; k < inputs.length; k++){
              var param = inputs[k].getAttribute('data-attr');
              url_params += "&"+param+"="+encodeURIComponent(inputs[k].value);
            }
            document.location = document.URL.replace(/\?.*$/,"") + url_params;
            return false;
          };
          parent.appendChild(butt);
          this.parentNode.removeChild(this);
        }
        if (this.value === "DELETE"){
          var url_params = "?";
          for(var f in filter){
            url_params += "FILTER["+f+"]="+filter[f]+"&";
          }
          for(var s in sort){
            url_params += "SORT["+s+"]="+sort[s];
          }
          url_params += "&"+this.value+"=&id="+id;
          if (!confirm("Точно видалити?")){
            return false;
          }
          document.location = document.URL.replace(/\?.*$/,"") + url_params;
        }
      };
    }
    document.querySelector("#INSERT").onclick = function(){
      var tbody = document.querySelector("table tbody");
      var tr_first = document.querySelector("table tbody tr:nth-child(1)");
      var trhtml = tr_first.innerHTML;
      var td1html = document.querySelector("table tbody tr td:nth-child(1)").innerHTML;
      var tr = document.createElement('TR');
      tr.setAttribute("data-act","INSERT");
      tr.innerHTML = trhtml.replace(td1html,"");
      tbody.insertBefore(tr,tr_first);
      var tds = document.querySelectorAll('tr[data-act="INSERT"] td');
      for (var j = 0; j < tds.length; j++){
        if (j === 0){
          var butt = document.createElement('BUTTON');
          butt.setAttribute("data-act","INSERT");
          butt.innerText = "зберегти";
          butt.onclick = function(){
            var url_params = "?";
            for(var f in filter){
              url_params += "FILTER["+f+"]="+filter[f]+"&";
            }
            for(var s in sort){
              url_params += "SORT["+s+"]="+sort[s];
            }
            url_params += "&INSERT=";
            var inputs = this.parentNode.parentNode.querySelectorAll('input[data-act="INSERT"],select[data-act="INSERT"]');
            for (var k = 0; k < inputs.length; k++){
              var param = inputs[k].getAttribute('data-attr');
              url_params += "&"+param+"="+encodeURIComponent(inputs[k].value);
            }
            document.location = document.URL.replace(/\?.*$/,"") + url_params;
            return false;
          };
          tds[j].appendChild(butt);
          continue;
        }
        var attr = tds[j].getAttribute("attr");
        if (attr == "id"){
          tds[j].innerHTML = '';
          continue;
        }
        var val = tds[j].innerHTML;
        if (attr === "dept"){
          tds[j].innerHTML = '<select data-act="INSERT" data-attr="'+attr+'" id="'+attr+'_'+'"></select>';
          var depts = <?php echo json_encode($depts); ?>;
          var sel = document.getElementById(attr+'_');
          for (var k = 0; k < depts.length; k++){
            var opt = document.createElement("OPTION");
            opt.value = depts[i].id;
            opt.innerText = depts[i].name;
            sel.appendChild(opt);
          }
        } else {
          tds[j].innerHTML = '<input type="text" data-act="INSERT" data-attr="'+attr+'"  value="" />';
        }
        tds[j].querySelector("INPUT").value = '';
      }
    };
  </script>
  </body>
</html>