<?php
////////////////////////////////////////////////////
//                 WikiMobile                     //
////////////////////////////////////////////////////
// Автор:              web_demon                  //
// Оф. Сайт:           http://wikimobile.su       //
// Oф. сайт поддержки: http://annimon.com         //
// E-mail:             web_demon@mail.ru          //
////////////////////////////////////////////////////

defined('MOBILE_WIKI') or die('Demon laughs');

function gename($string)
{
  $tr = array(
    "а"=>"a","б"=>"b",
    "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
    "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
    "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
    "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
    "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
    "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"," "=>"_" );
    $len=mb_strlen($string);
    $string=str_replace(' ','_',$string);
    $string=str_replace('\\','',$string);
  for($i=0;$i<=$len;$i++)
  {
      $text=mb_substr($string,$i,1);
      if (preg_match("/[0-9A-zА-я\-\_]/",$text))
        $out .= $text;
  }
  $out=mb_strtolower($out);
  $out=strtr($out,$tr);
    return $out;
}
?>
