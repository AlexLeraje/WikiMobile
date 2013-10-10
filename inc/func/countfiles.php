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

function countfiles($dir)
{
  //////////////////////////////////////////////
  //подсчет элементов в директории        //////
  //////////////////////////////////////////////
  if (is_dir($dir))
  {
   $dir_id = opendir($dir);
   while ($file = readdir($dir_id))
   {
     if($file != '..' and $file != '.')
     {   
       if (getextension($file)=='txt' or is_dir($dir.'/'.$file))
       $i++;
     }
   }
   if (!$i)return '0';
     else    return $i;
   }
     else return FALSE;
}
?>
