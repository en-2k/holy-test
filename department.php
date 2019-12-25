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
  $conn = pg_connect($_ENV['DATABASE_URL']);
  if (!$conn){
?>
Помилка. Не вдалося з'єднатися з Postgres.
<br/>
<?php
    exit;
  }
  $result = pg_query($conn,"SELECT * FROM department ORDER BY id");
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
<button id="INSERT"></button>
  <table>
  <thead><tr>
    <th>#</th>
    <th>id</th>
    <th>Назва підрозділу</th>
  </tr></thead>
  <tbody>
<?php 
  for ($i = 0; $i < count($rows); $i++){
?>
<tr>
  <td><select data-id="<?php $rows[$i]['id']; ?>"><option value="UPDATE">Редагувати</option><option value="DELETE">Видалити</option></select></td>
  <td><?php echo  $rows[$i]['id'];?></td>
  <td><?php echo  $rows[$i]['name'];?></td>
</tr>
<?php
  }
?>
  </tbody>
  </table>
  </body>
</html>