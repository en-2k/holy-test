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
$deps = [
  'Служба інформаційних технологій',
  'Управління кадрів',
  'Енергоремонтний підрозділ',
  'Управління будівництва',
  'Цех дезактивації',
  'Служба якості',
  'Договірне управління',
  'Бухгалтерія',
  'Управління соціальних програм',
  'Управління капітального будівництва',
];
$surnames = [
  'Іваненко',
  'Бондаренко',
  'Петренко',
  "Дерев'янко",
  "Сидоренко",
  "Бут",
  "Деркач",
];
$names = [
  'Петро',
  'Борис',
  'Ігор',
  'Дмитро',
  'Олександр',
];
$namesf = [
  'Ірина',
  'Наталя',
  'Людмила',
  'Надія',
  'Лариса',
];
$names2 = [
  'Петрович',
  'Миколайович',
  'Володимирович',
  'Олексійович',
  'Олександрович',
  'Ігоревич',
  'Георгієвич',
];
$names2f = [
  'Петрівна',
  'Миколаївна',
  'Володимирівна',
  'Олексіївна',
  'Олександрівна',
  'Ігорівна',
  'Георгіївна',
];
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes'){
  $conn = pg_connect($_ENV['DATABASE_URL']);
  if (!$conn){
?>
Помилка. Не вдалося з'єднатися з Postgres.
<br/>
<?php
    print pg_last_error($conn);
    exit;
  }
  $sql = file_get_contents("schema.sql");
  $ids = [];
  $dep_len = random_int(5,count($deps));
  $sql .= "/* " . $dep_len . " */". PHP_EOL;
  for ($i = 0,$j=0; $i < $dep_len && $j < 1000000; $i++,$j++){
    $id = random_int(0,count($deps)-1);
    if(!in_array($id, $ids)){
      $ids []= $id;
    } else {
      $i--; continue;
    }
    $sql .= "INSERT INTO Department(name) "
      . "VALUES(" 
      . "'" . str_replace( "'", "''", $deps[$id] ) . "'"
      . ");" . PHP_EOL;
  }
  
  $ids = [];
  $emp_len = random_int(50,300);
  $sql .= "/* " . $emp_len . " */". PHP_EOL;
  for ($i = 0,$j=0; $i < $emp_len && $j < 1000000; $i++,$j++){
    $mf = random_int(0,1);
    $id1 = random_int(0,count($surnames)-1);
    $id2 = random_int(0,count( (($mf === 0)? $names:$namesf) )-1);
    $id3 = random_int(0,count( (($mf === 0)? $names2:$names2f) )-1);
    $id = $id1 . $id2 . $id3;
    $name = $surnames[$id1] 
      . ' ' . (($mf === 0)? $names[$id2]:$namesf[$id2])
      . ' ' . (($mf === 0)? $names2[$id3]:$names2f[$id3]);
    if(!in_array($id, $ids)){
      $ids []= $id;
    } else {
      $i--; continue;
    }
    $dep_id = random_int(1,$dep_len);
    $fired = ((random_int(1,10) === 5)? 
        "'201" . random_int(0,9) 
        . "-0" . random_int(1,9) 
        . "-" . random_int(10,28) . "'" 
      : "NULL");
    $sql .= "INSERT INTO Employee(name,department_id,fired) "
      . "VALUES(" 
      . "'" . str_replace( "'", "''", $name ) . "',"
      . "" . $dep_id . ","
      . "" . $fired . ""
      . ");" . PHP_EOL;
  }

  $ids = [];
  $vac_each = random_int(2,4);
  $vac_len = $vac_each * $emp_len;
  $sql .= "/* " . $vac_len . " */". PHP_EOL;
  for ($i = 0,$j=0; $i < $vac_len && $j < 1000000; $i++,$j++){
    $id = random_int(1, $emp_len);
    if (isset($ids[ "'" . $id ])){
      $ids[ "'" . $id ]['cnt']++;
      if ($ids[ "'" . $id ]['cnt'] > $vac_each){
        $ids[ "'" . $id ]['cnt']--;
        $i--;
        continue;
      }
    } else {
      $month = random_int(1,5);
      $ids[ "'" . $id ] = ['cnt' => 1, 'month' => $month];
      
    }
    $d_start = 
        "'2019"
        . "-0" . ($ids[ "'" . $id ]['month'] + $ids[ "'" . $id ]['cnt'])
        . "-" . random_int(10,28) . "'" ;
    $d_end = "date " . $d_start . " + integer '" . random_int(0,28) . "'" ;
    $sql .= "INSERT INTO Vacations(employee_id,d_start,d_end) "
      . "VALUES(" 
      . "" . $id . ","
      . "" . $d_start . ","
      . "" . $d_end . ""
      . ");" . PHP_EOL;
    
  }
  
  $result = pg_query($conn,$sql);
  if ($result === FALSE){
    print pg_last_error($conn);
    pg_close($conn);
    exit;
  }
  pg_close($conn);
?>
Виконався запит
<br/>
<pre>
<?php echo $sql; ?>
</pre>
<?php
} else {
?>
Ця дія видалить усі дані таблиць і заповнить випадковими даними. Перейдіть за <a href="?confirm=yes">цим посиланням</a> для підтвердження
<?php
}
?>
  </body>
</html>