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

function get_file_size($path)
{
  global $lang;  
  $file_size = filesize($path);
  if($file_size >= 1073741824)
      $file_size = round($file_size / 1073741824 * 100) / 100 . " ".$lang['flz_gb'];
  elseif($file_size >= 1048576)
    $file_size = round($file_size / 1048576 * 100) / 100 . " ".$lang['flz_mb'];
  elseif($file_size >= 1024)
    $file_size = round($file_size / 1024 ) . " ".$lang['flz_kb'];
  else
    $file_size = round($file_size) . " ".$lang['flz_bt'];
  return $file_size;
}
?>
