<?php
  $users = json_decode(file_get_contents("users.json"),true);
  $users_by_id = json_decode(file_get_contents("users_by_id.json"),true);
  $rooms = json_decode(file_get_contents("rooms.json"),true);
  $rooms_by_id = json_decode(file_get_contents("rooms_by_id.json"),true);
  $room_user = json_decode(file_get_contents("room_user.json"),true);
  $room_user_by_room = json_decode(file_get_contents("room_user_by_room.json"),true);
  $room_user_by_user = json_decode(file_get_contents("room_user_by_user.json"),true);
  $msg = json_decode(file_get_contents("msg.json"),true);
  $msg_by_user = json_decode(file_get_contents("msg_by_user.json"),true);
  $msg_by_room = json_decode(file_get_contents("msg_by_room.json"),true);
  $user_id = ((isset($_POST['user_id']))? intval($_POST['user_id']):1);
  $user_rooms = array();
  
  $ret = array();
  $ret['rooms'] = array();
  
  for ($i = 0; $i < count($room_user_by_user[$user_id]); $i++){
    $r_u_index = $room_user_by_user[$user_id][$i];
    $room_id = $room_user[$r_u_index]['room_id'];
    $rur = $room_user_by_room[$room_id];
    if (count($rur) === 2){
      for ($j = 0; $j < 2; $j++){
        $u_id = $room_user[$rur[$j]]['user_id'];
        if ($u_id !== $user_id){
          $ret['rooms'] []= array(
            $room_id, 
            $users[$users_by_id[$u_id][0]]['name'] );
          break;
        }
      }
    } else {
      $ret['rooms'] []= array(
        $room_id, 
        $rooms[$rooms_by_id[$room_id][0]]['name'] );
    }
  }
  $ret['msg'] = array();
  for ($i = 0; $i < count($room_user_by_user[$user_id]); $i++){
    $r_u_index = $room_user_by_user[$user_id][$i];
    $room_id = $room_user[$r_u_index]['room_id'];
    $msgs = $msg_by_room[$room_id];
    for ($j = 0; $j < count($msgs); $j++){
      $msg_index = $msgs[$j];
      $arr = $msg[$msg_index];
      $arr['user'] = $users[$users_by_id[$arr['user_id']][0]]['name'];
      $ret['msg'] []= $arr;
    }
  }
  
  echo json_encode($ret);
  //var_dump($msg);
  //var_dump($ret);
  
?>