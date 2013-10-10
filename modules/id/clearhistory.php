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
   $action=$_GET['act']; 
     if(!$action)
     {
        require_once ('inc/admin/head_info.php');
        echo '<b>'.$lang['rea_cl_hist'].' "'.out($wikipage['name']).'"?</b><br />';
        echo '</td></table><center>';
        echo '<form action="./?do=clearhistory'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;uid='.$id.'&amp;act=clear" style="display: inline" method="post"><input type="submit" value="'.$lang['clear'].'" class="edit" /></form>';    
        echo ' <form action="./?do=history'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;uid='.$id.'" style="display: inline" method="post"><input type="submit" value="'.$lang['cancel'].'" class="edit" /></form>';    
        echo '</center>';
        require_once ('inc/admin/fin_info.php'); 
     }
     elseif($action=='clear')
     {
        if(file_exists($wikipage['path'].'.'.$stat_lang.'.hist_count.dat'))
        {
          $hist_numb = file_get_contents($wikipage['path'].'.'.$stat_lang.'.hist_count.dat');
          unlink($wikipage['path'].'.'.$stat_lang.'.hist_count.dat');  
        }
        else
          $hist_numb = 1;
        for($i=1;$i <= $hist_numb; $i++)
        {
          if(file_exists($wikipage['path'].'.'.$stat_lang.'.arh.'.$i.'.dat'))
            unlink($wikipage['path'].'.'.$stat_lang.'.arh.'.$i.'.dat');  
        }
        mysql_query("DELETE FROM `wm_history` WHERE `page` = '".$wikipage['id']."' AND `lang` = '".$stat_lang."';");
        require_once ('inc/admin/head_info.php');
        echo '<b>'.$lang['cl_hist_1'].' "'.out($wikipage['name']).'" '.$lang['cl_hist_2'].'</b><br />';
        echo '</td></table><center>';    
        echo ' <form action="./?do=history'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;uid='.$id.'" style="display: inline" method="post"><input type="submit" value="'.$lang['continue'].'" class="edit" /></form>';    
        echo '</center>';
        require_once ('inc/admin/fin_info.php');  
     }
}
else
{
  header ('Location: ./?do=404');  
}
?>
