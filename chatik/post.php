<?php
  $msg_by_room = json_decode(file_get_contents("msg_by_room.json"),true);
  $msg_by_user = json_decode(file_get_contents("msg_by_user.json"),true);
  $user_id = ((isset($_POST['user_id']))? intval($_POST['user_id']):1);
  $room_id = ((isset($_POST['room_id']))? intval($_POST['room_id']):-1);
  $text = ((isset($_POST['text']))? $_POST['text']:"test");
  $yearmonth = date('Y-m');
  if ($room_id < 0){
    echo json_encode("POST[room_id] < 0");
    return 0;
  }
  if(is_file($yearmonth.'.json')){
    $msg = json_decode(file_get_contents($yearmonth.".json"),true);
  } else {
    $msg = array();
  }
  usleep(50000);
  for($i = 0; is_file("msg_id.lock") && $i < 1000; $i++){
    usleep(50000);
  }
  if(is_file("msg_id.lock")){
    echo json_encode("msg_id.lock");
    return 0;
  }
  file_put_contents("msg_id.lock","locked");
  $data = json_decode(file_get_contents("msg_id.json"),true);
  $data['msg_id'] = $data['msg_id']+1;
  file_put_contents("msg_id.json",json_encode($data));
  $msg['_'.$data['msg_id']] = array(
    'user_id' => $user_id,
    'room_id' => $room_id,
    'when' => date('Y-m-d H:i:s'),
    'text' => $text
  );
  file_put_contents($yearmonth.'.json',json_encode($msg));
  $msg_by_user[$user_id] []= '_'.$data['msg_id'];
  $msg_by_room[$room_id] []= '_'.$data['msg_id'];
  file_put_contents("msg_by_user.json",json_encode($msg_by_user));
  file_put_contents("msg_by_room.json",json_encode($msg_by_room));
  unlink("msg_id.lock");
  echo json_encode("ok");
?>