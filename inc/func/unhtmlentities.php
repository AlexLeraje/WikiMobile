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

function unhtmlentities($string)
{
  ////////////////////////////////////////////////////////////
  // Декодирование htmlentities                            //
  ////////////////////////////////////////////////////////////
  $string = str_replace('&amp;', '&', $string);
  $string = preg_replace('~&#x0*([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
  $string = preg_replace('~&#0*([0-9]+);~e', 'chr(\\1)', $string);
  $trans_tbl = get_html_translation_table(HTML_ENTITIES);
  $trans_tbl = array_flip($trans_tbl);
  return strtr($string, $trans_tbl);
}
?>
