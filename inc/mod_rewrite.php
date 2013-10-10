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

function rewrite_on()
{                       
  if(function_exists('apache_get_modules'))
  {
    $apache_mod=@apache_get_modules();
    if (array_search('mod_rewrite', $apache_mod))
       return TRUE;
    else
       return FALSE; 
  }
  else
   return FALSE;
}
?>