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

function get_host($url)
{
  $url = str_replace('http://','',$url);
  $n=strpos($url,"/");
  $url = mb_substr($url,0,$n);
  return $url; 
}
?>
