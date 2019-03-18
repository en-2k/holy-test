<?php
  $k = 'msg_by_user';
  $data = json_decode(file_get_contents($k.'.json'),true);
  foreach ($data as $key => $v){
    $p = count($v);
    for ($i = 0; $i < $p; $i++){
      $data[$key][$i] = '_'.$v[$i];
      
    }
  }
  file_put_contents($k.".json",json_encode($data));
  var_dump($data);
?>