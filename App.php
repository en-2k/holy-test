<?php
require_once 'NumericConverter.php';

class App {
  public static $output = '';
  public static $input = '';
  public static $d = 1;
  public static $json = 0;
  public static function  perform(){
    if (isset($_GET['n']) && isset($_GET['direction']) 
          && $_GET['direction'] == '1'){
      self::$input = htmlspecialchars($_GET['n']);
      $t = NumericConverter::toRoman(intval($_GET['n']));
      self::$output = $t;
      if (self::$output[0] !== '!'){
        self::$input = intval(self::$input);
      }
    }

    if (isset($_GET['n']) && isset($_GET['direction']) 
          && $_GET['direction'] == '2'){
      self::$d = 2;
      $t = NumericConverter::toArabic(trim($_GET['n']));
      self::$output = $t;
      self::$input = htmlspecialchars($_GET['n']);
      if ($t[0] !== '!'){
        self::$input = strtoupper(trim(self::$input));
      }
    }
    if (isset($_GET['json'])){
      App::$json = 1;
      echo json_encode(self::$output);
    }
  }
}