<?php
  $users = json_decode(file_get_contents("users.json"),true);
  $users_by_id = json_decode(file_get_contents("users_by_id.json"),true);
  $rooms = json_decode(file_get_contents("rooms.json"),true);
  $rooms_by_id = json_decode(file_get_contents("rooms_by_id.json"),true);
  $room_user = json_decode(file_get_contents("room_user.json"),true);
  $room_user_by_room = json_decode(file_get_contents("room_user_by_room.json"),true);
  $room_user_by_user = json_decode(file_get_contents("room_user_by_user.json"),true);
  //$msg = json_decode(file_get_contents("msg.json"),true);
  $msg_by_room = json_decode(file_get_contents("msg_by_room.json"),true);
  $user_id = ((isset($_POST['user_id']))? intval($_POST['user_id']):1);
  $room_id_need = ((isset($_POST['room_id']))? intval($_POST['room_id']):-1);
  $yearmonth = ((isset($_POST['yearmonth']))? intval($_POST['yearmonth']):date('Y-m'));
  for($i = 0; (!is_file($yearmonth.'.json')) && ($i < 1000); $i++){
    $yearmonth = date('Y-m',strtotime($yearmonth.' -1 months'));
  }
  $msg = json_decode(file_get_contents($yearmonth.".json"),true);
  $user_rooms = array();
  
  $ret = array();
  $ret['rooms'] = array();
  $limit = 12;
  
  for ($i = 0; $i < count($room_user_by_user[$user_id]); $i++){
    $r_u_index = $room_user_by_user[$user_id][$i];
    $room_id = $room_user[$r_u_index]['room_id'];
    if ($room_id_need !== -1 && $room_id !== $room_id_need){
      continue;
    }
    $rur = $room_user_by_room[$room_id];
    $r_data = array();
    if (count($rur) === 2){
      for ($j = 0; $j < 2; $j++){
        $u_id = $room_user[$rur[$j]]['user_id'];
        if ($u_id !== $user_id){
          $r_data['room_id'] = $room_id;
          $r_data['room_name'] = $users[$users_by_id[$u_id][0]]['name'];
          break;
        }
      }
    } else {
        $r_data['room_id'] = $room_id;  
        $r_data['room_name'] = $rooms[$rooms_by_id[$room_id][0]]['name'];
    }
    $r_data['msg'] = array();
    $msgs = $msg_by_room[$room_id];
    /*
    $start = count($msgs)-1-$limit*($offset+1);
    if ($start < 0){
      $start = 0;
    }
    $end = count($msgs)-$limit*($offset);
    */
    for ($j = 0; $j < count($msgs); $j++){
      $msg_index = $msgs[$j];
      $arr = $msg[$msg_index];
      $arr['user'] = $users[$users_by_id[$arr['user_id']][0]]['name'];
      $r_data['msg'] []= $arr;
    }
    $ret['rooms'] []= $r_data;
  }
    
  echo json_encode($ret);
  //var_dump($msg);
  //var_dump($ret);
  
?>