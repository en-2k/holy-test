<?php
/**
 * Numeric Converter Class
 *
 * @version 0.0.0
 */
class NumericConverter {
  
  /**
   * Convert base digit 1,5,10,50,100,500,1000 to Roman digit
   *
   * @return string
   */
  private function basedigit($x) {
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
  public function toRoman(x){
    $tmp="";
    $base=0;
    if ( 
        ( ($x < 0) || ($x > 3999999) ) 
        || ( !is_int($x) ) 
        ){
      return "error";
    } else {
      while ($x > 0) {
        if (($x >= 1)&&($x <= 9)){
          $base = 1;
        } elseif (($x >= 10)&&($x <= 99)) {
          $base = 10;
        } elseif ((x >= 100)&&(x <= 999)) {
          $base = 100;
        } elseif ((x >= 1000)&&(x <= 3999)) {
          $base = 1000;
        } elseif (x>=4000) {
          $temp = ($x - $x % 1000) / 1000;
          $tmp2 = $this->toRoman($temp);
          $base = 100;
          $tmp = $tmp2 . "&";
          $x = $x - ($x - $x % 1000);
        } else  {
          return "error";
        }
        if ($x >= 9 * $base) {
          $tmp = $tmp 
            . $this->basedigit($base) 
            . $this->basedigit( $base * 10);
          $x = $x - 9 * $base ;
        } elseif ($x >= 5 * $base) {
          $tmp = $tmp 
            . $this->basedigit(5 * $base);
          $x = $x - 5*$base; 
        } elseif (x>=4*base){
          $tmp = $tmp
            . $this->basedigit($base)
            . $this->basedigit(5 * $base);
          $x = $x - 4 * $base;
        }
        while ($x >= $base){
          $tmp = $tmp
            . $this->basedigit($base);
          $x = $x - $base; 
        } 
      } //end while
      return $tmp);
    } // end else block
  }

}