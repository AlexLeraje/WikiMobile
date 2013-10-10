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
if($rights=='admin')
{
  $act=$_GET['act'];
  if(!$act)
  {
    require_once ('inc/admin/head_info.php');
    echo '<b>'.$lang['del_unus_fil'].'</b><br/>'.$lang['del_files_inf'].'<br />';
    echo '</td></table><center>';
    echo '<form action="./?act=del&amp;do=delfiles" style="display: inline" method="post"><input type="submit" value="'.$lang['delete'].'" class="edit" /></form>';    
    echo ' <form action="./?do=files" style="display: inline" method="post"><input type="submit" value="'.$lang['cancel'].'" class="edit" /></form>';    
    echo '</center>';
    require_once ('inc/admin/fin_info.php');
  }
  else
  {
    $fil1= mysql_query("SELECT * FROM `wm_files` WHERE `time` < '".(time() - 3600)."' ;");
    $i=0;
    while ($fil = mysql_fetch_assoc($fil1))
    {
       $req = mysql_query('SELECT * FROM `wm_pages` WHERE `id`="'.$fil['page'].'" LIMIT 1;'); 
       if(mysql_num_rows($req))
       {
       
       }
       else
       {
         if($fil['att'])
         {
           $req2 = mysql_query('SELECT * FROM `wm_mod` WHERE `att`="'.$fil['att'].'" LIMIT 1;');
           if(mysql_num_rows($req2))
           {
               
           }
           else
           { 
             if(file_exists('sourse/files/'.$fil['filename'].'.dat'))
               unlink('sourse/files/'.$fil['filename'].'.dat');
             mysql_query('DELETE FROM `wm_files` WHERE `id`="'.$fil['id'].'" LIMIT 1;');
             $i++;  
           }
         }
         else
         {
           if(file_exists('sourse/files/'.$fil['filename'].'.dat'))
             unlink('sourse/files/'.$fil['filename'].'.dat');
           mysql_query('DELETE FROM `wm_files` WHERE `id`="'.$fil['id'].'" LIMIT 1;');
           $i++;
         } 
       }
    }
      
    
    require_once ('inc/admin/head_info.php');
    echo '<b>'.$lang['unu_files_del'].'</b><br/>'.$lang['total'].' '.$i.'<br />';
    echo '</td></table><center>';
    echo ' <form action="./?do=files" style="display: inline" method="post"><input type="submit" value="'.$lang['continue'].'" class="edit" /></form>';    
    echo '</center>';
    require_once ('inc/admin/fin_info.php');
  }
}
else
  header ('Location: ./?do=404');
?>
