<?php
/**
 * Numeric Converter Class
 *
 * @version 0.0.0
 */
class NumericConverter {
  
  /**
   * @var array $roman_values roman bassic numerals
   */
  public static $roman_values=array(
      'I' => 1, 'V' => 5, 
      'X' => 10, 'L' => 50,
      'C' => 100, 'D' => 500,
      'M' => 1000,
  );
  /**
   * @var string $roman_regex roman numbers check regex
   */
  public static $roman_regex
    ='/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/';
    
  /**
   * @var array $roman_zero roman zeros signs
   */
  public static $roman_zero=array('N', 'nulla');
    
  /**
   * Convert base digit 1,5,10,50,100,500,1000 to Roman digit
   *
   * @return string
   */
  static function basedigit($x) {
    $y = "";
    switch ($x) {
      case 1:    $y="I";
      break;
      case 5:    $y="V";
      break;
      case 10:   $y="X";
      break;
      case 50:   $y="L";
      break;
      case 100:  $y="C";
      break;
      case 500:  $y="D";
      break;
      case 1000: $y="M";
      break;
      default: 
      $y = "?";
    }//switch
    return $y;
  }
   
  /**
   * Converts number between 1 and 3999999 to Roman number
   *
   * @return string
   */
  public static function toRoman($x){
    $tmp="";
    $base=0;
    if ( 
        ( ($x < 0) || ($x > 3999) ) 
        || ( !is_int($x) ) 
        ){
      return "!value must be in [0..3999]!";
    } else {
      if ($x == 0){
        return 'N';
      }
      while ($x > 0) {
        if (($x >= 1)&&($x <= 9)){
          $base = 1;
        } elseif (($x >= 10)&&($x <= 99)) {
          $base = 10;
        } elseif (($x >= 100)&&($x <= 999)) {
          $base = 100;
        } elseif (($x >= 1000)&&($x <= 3999)) {
          $base = 1000;
        } elseif ($x >= 4000) {
          $temp = ($x - $x % 1000) / 1000;
          $tmp2 = self::toRoman($temp);
          $base = 100;
          $tmp = $tmp2 . "&";
          $x = $x - ($x - $x % 1000);
        } else  {
          return "error";
        }
        if ($x >= 9 * $base) {
          $tmp = $tmp 
            . self::basedigit($base) 
            . self::basedigit( $base * 10);
          $x = $x - 9 * $base ;
        } elseif ($x >= 5 * $base) {
          $tmp = $tmp 
            . self::basedigit(5 * $base);
          $x = $x - 5*$base; 
        } elseif ($x >= 4 * $base){
          $tmp = $tmp
            . self::basedigit($base)
            . self::basedigit(5 * $base);
          $x = $x - 4 * $base;
        }
        while ($x >= $base){
          $tmp = $tmp
            . self::basedigit($base);
          $x = $x - $base; 
        } 
      } //end while
      return $tmp;
    } // end else block
  }
  
  /**
   * Roman numeral validation function - is the string a valid Roman Number?
   * @param $roman string input roman number for checking
   * @return boolean
   */
  static function IsRomanNumber($roman) {
     return preg_match(self::$roman_regex, strtoupper($roman)) > 0;
  }

  /**
   * Conversion: Roman Numeral to Integer
   * @param $roman string input roman number for convertation
   * @return mixed
   */
  public static function toArabic ($in_roman) {
    //checking for zero values
    $roman = '';
    if ($in_roman){
      $roman = strtoupper($in_roman);
    }
    if (in_array($roman, self::$roman_zero)) {
      return 0;
    }
    //validating string
    if (!self::IsRomanNumber($roman)) {
      return '!value is not Roman numeral!';
    }
    $values=self::$roman_values;
    $result = 0;
    //iterating through characters LTR
    for ($i = 0, $length = strlen($roman); $i < $length; $i++) {
      //getting value of current char
      $value = $values[$roman[$i]];
      //getting value of next char - null if there is no next char
      $nextvalue = !isset($roman[$i + 1]) ? null : $values[$roman[$i + 1]];
      //adding/subtracting value from result based on $nextvalue
      $result += (!is_null($nextvalue) && $nextvalue > $value) ? -$value : $value;
    }
    return $result;
  }

}