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
if (can('delete_stat') and $id and $id!=1)
{
  $action=$_GET['act'];  
  $path_p=$path;
  $path=add_data($path);
  if (!$action)
  {  
      require_once ('inc/admin/head_info.php');
      echo '<b>'.$lang['move_page_q'].' "<a href="'.($mod_rewrite ? 'wiki/'.prw($wikipage['name']) : '?uid='. $id).'">'.out($wikipage['name']).'</a>"</b>';
      echo '<hr />';
      func('move_buildlist',$path,'data',0,'uid='.$id.'&do=move');
      echo '<hr />';
      echo '</td></table><center>';
      echo '<form method="POST" action="?do=move&amp;uid='.$id.'&amp;id='.$path_p.'&amp;act=move" style="display: inline"><input type="submit" value="'.$lang['move_yes'].'" class="edit" /></form>';    
      echo '<form action="'.($mod_rewrite ? 'wiki/'.prw($wikipage['name']) : '?uid='. $id).'" style="display: inline" method="post"><input type="submit" value="'.$lang['cancel'].'" class="edit" /></form>';    
      echo '</center>';
      require_once ('inc/admin/fin_info.php');
  }
  else
  {
      require_once ('inc/admin/head_info.php');
      if(is_dir($path))
      {
        if($path==$wikipage['dir'])
        {
          echo '<b>'.$lang['dir_exists'].'</b>';  
        }
        else
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
                  if(file_exists($wikipage['path'].'.'.$file.'.hist_count.dat'))
                  {
                    $hist_numb = file_get_contents($wikipage['path'].'.'.$file.'.hist_count.dat');
                    rename($wikipage['path'].'.'.$file.'.hist_count.dat', $path.'/'.cut_name2($wikipage['path']).'.'.$file.'.hist_count.dat');
                  }
                  else
                    $hist_numb = 1;
                  for($i=1;$i <= $hist_numb; $i++)
                  {
                    if(file_exists($wikipage['path'].'.'.$file.'.arh.'.$i.'.dat'))
                      rename($wikipage['path'].'.'.$file.'.arh.'.$i.'.dat', $path.'/'.cut_name2($wikipage['path']).'.'.$file.'.arh.'.$i.'.dat');
                  }
                  if (file_exists($wikipage['path'].'.'.$file.'.temp.dat'))
                    rename($wikipage['path'].'.'.$file.'.temp.dat', $path.'/'.cut_name2($wikipage['path']).'.'.$file.'.temp.dat');
                  if (file_exists($wikipage['path'].'.'.$file.'.txt'))
                    rename($wikipage['path'].'.'.$file.'.txt', $path.'/'.cut_name2($wikipage['path']).'.'.$file.'.txt');
                }   
              }
            }
          }
          closedir($dir);
          mysql_query("UPDATE `wm_pages` SET `path` = '".mysql_real_escape_string($path.'/'.cut_name2($wikipage['path']))."' WHERE `id` = '".$id."' LIMIT 1;");
          mysql_query("UPDATE `wm_pages` SET `dir` = '".mysql_real_escape_string($path)."' WHERE `id` = '".$id."' LIMIT 1;");
          mysql_query("UPDATE `wm_page_lang` SET `dir` = '".mysql_real_escape_string($path)."' WHERE `pid` = '".$id."';");
          
          echo '<b>'.$lang['page_moved'].'</b>';  
        }  
      }
      else
        echo '<b>'.$lang['page_no_moved'].'</b>';  
      echo '</td></table><center>';
      echo '<form action="'.($mod_rewrite ? 'wiki/'.prw($wikipage['name']) : '?uid='. $id).'" style="display: inline" method="post"><input type="submit" value="'.$lang['continue'].'" class="edit" /></form>';    
      echo '</center>';
      require_once ('inc/admin/fin_info.php');
  } 
}
else
    header ('Location: ./?do=404');
?>
