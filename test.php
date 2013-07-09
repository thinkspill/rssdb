<?php 

function LetterCapitalize($str) {  

  $words = explode(' ', $str);
  $sentence = '';
  foreach ($words as $w)
  {
    $sentence .= ucfirst($w) . " ";
  }
  return trim($sentence);
         
}

echo LetterCapitalize('a b c d e f');
// keep this function call here  
// to see how to enter arguments in PHP scroll down
//echo LetterCapitalize(fgets(fopen('php://stdin', 'r')));

?>