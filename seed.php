<!doctype html>
<html lang="uk">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Тестове завдання</title>
  </head>
  <body>
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
  for ($i = 0; $i < 5; $i++){
    $id = random_int(0,count($deps)-1);
    if(!in_array($id, $ids)){
      $ids []= $id;
    } else {
      $i--; continue;
    }
    $sql .= "INSERT INTO Department(name) "
      . "VALUES(" 
      . "'" . str_replace("\\","\\\\",str_replace( "'", "\\'", $deps[$id] )) . "'"
      . ");" . PHP_EOL;
  }
  $result = pg_query($conn,$sql);
  if ($result === FALSE){
    print pg_last_error($conn);
    pg_close($conn);
    exit;
  }
  pg_close($conn);
} else {
?>
Ця дія видалить усі дані таблиць і заповнить випадковими даними. Перейдіть за <a href="?confirm=yes">цим посиланням</a> для підтвердження
<?php
}
?>
  </body>
</html>