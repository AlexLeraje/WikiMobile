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

function full_del_dir ($directory)
{
  /////////////////////////////////
  //рекурсивное удаление папки/////
  /////////////////////////////////
  $dir=opendir($directory);
  while(($file=readdir($dir)))
  {
    if (is_file ($directory."/".$file))
    {
      unlink ($directory."/".$file);
      if(getextension($file)=='txt')
      {
        $req = mysql_query('SELECT * FROM `wm_pages` WHERE `path`="'.cut_ext(cut_ext($directory."/".$file)).'" LIMIT 1;');
        $res = mysql_fetch_array($req);
        mysql_query("DELETE FROM `wm_discusion` WHERE `page` = '".$res['id']."';");
        mysql_query("DELETE FROM `wm_history` WHERE `page` = '".$res['id']."';"); 
        mysql_query("DELETE FROM `wm_pages` WHERE `path` = '".cut_ext(cut_ext($directory."/".$file))."'  LIMIT 1");
        mysql_query("DELETE FROM `wm_page_lang` WHERE `pid` = '".$res['id']."'");
      }
    }
    elseif(is_dir($directory."/".$file)&&($file != ".")&&($file != ".."))
    {
      full_del_dir($directory."/".$file);  
    }
  }
  closedir ($dir);
  rmdir ($directory);
  return TRUE;
}
?>
