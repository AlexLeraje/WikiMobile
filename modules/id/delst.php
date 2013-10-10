<?
////////////////////////////////////////////////////
//                 WikiMobile                     //
////////////////////////////////////////////////////
// Автор:              web_demon                  //
// Оф. Сайт:           http://wikimobile.su       //
// Oф. сайт поддержки: http://annimon.com         //
// E-mail:             web_demon@mail.ru          //
////////////////////////////////////////////////////

defined('MOBILE_WIKI') or die('Demon laughs');
require_once ('inc/admin/func.php');
if (can('delete_stat') and $id and $id!=1)
{
$action=$_GET['act'];
if (!$action)
{ 
  require_once ('inc/admin/head_info.php');
  echo '<b>'.$lang['del_page_q'].' "'.out($wikipage['name']).'"?</b>';
  echo '<br />'.$lang['del_page_wr'].'</td></table><center>';
  echo '<form action="?" style="display: inline"><input type="hidden" name="uid" value="'.$id.'"><input type="hidden" name="do" value="delst"><input type="hidden" name="act" value="delete"><input type="submit" value="'.$lang['delete_yes'].'" class="edit" /></form>';	
  echo '<form action="'.($mod_rewrite ? 'wiki/'.prw($wikipage['name']) : '?uid='. $id).'" style="display: inline" method="post"><input type="submit" value="'.$lang['cancel'].'" class="edit" /></form>';	
  echo '</center>';
  require_once ('inc/admin/fin_info.php');
}
elseif($action=='delete')
{
    $path_lln = 'inc/lang';
    $dir = opendir($path_lln);
    while ($file = readdir($dir))
    { 
      if ((is_dir($path_lln.'/'.$file)) and ($file !=".")&&($file !=".."))
      {
        if(!preg_match("/[^1-9a-z]+/",$file))  
          {
            if(file_exists($wikipage['path'].'.'.$file.'.txt'))
            {
               //Удаляем всю историю страницы 
               if(file_exists($wikipage['path'].'.'.$file.'.hist_count.dat'))
               {
                 $hist_numb = file_get_contents($wikipage['path'].'.'.$file.'.hist_count.dat');
                 unlink($wikipage['path'].'.'.$file.'.hist_count.dat');  
               }
               else
                 $hist_numb = 1;
               for($i=1;$i <= $hist_numb; $i++)
               {
                 if(file_exists($wikipage['path'].'.'.$file.'.arh.'.$i.'.dat'))
                   unlink($wikipage['path'].'.'.$file.'.arh.'.$i.'.dat');  
               }
               //Удаляем остальное
               if (file_exists($wikipage['path'].'.'.$file.'.temp.dat'))
                 unlink($wikipage['path'].'.'.$file.'.temp.dat');
               if (file_exists($wikipage['path'].'.'.$file.'.txt'))
                 unlink($wikipage['path'].'.'.$file.'.txt');
            }   
          }
      }
    }
    closedir($dir);
    mysql_query("DELETE FROM `wm_history` WHERE `page` = '".$id."';");
    mysql_query("DELETE FROM `wm_pages` WHERE `id` = '".$id."' LIMIT 1;");
    mysql_query("DELETE FROM `wm_page_lang` WHERE `pid` = '".$id."';");
    mysql_query("DELETE FROM `wm_discusion` WHERE `page` = '".$id."';");
    
    
    if (file_exists('files/cache/count.dat'))
      unlink('files/cache/count.dat');
    
	require_once ('inc/admin/head_info.php');
    $path_p=$path;
    $path=add_data($path);
	echo '<b>'.$lang['page_deleted'].'</b></td></table><center>';
	echo '<form style="display:inline" method="post" action="?id='.str_replace('/',':',cut_data($wikipage['dir'])).'&amp;do=sod"><input type="submit" class="edit" value="'.$lang['continue'].'" /></form></center>';
    require_once ('inc/admin/fin_info.php');
}
}
else
	header ('Location: ./?do=404');
?>