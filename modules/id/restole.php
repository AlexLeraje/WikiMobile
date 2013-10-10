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
$history =intval(abs($_GET['history']));
$zap1 = mysql_query("SELECT * FROM `wm_history` where `page` = '" .$id. "' AND `id` = '" .$history. "' AND `lang` = '".$stat_lang."' LIMIT 1;");
if(mysql_num_rows($zap1))
{
  $zap = mysql_fetch_array($zap1);
  $link_hist = $history;  
  $history = $zap['numb']-1;
  $textbody = file_get_contents($wikipage['path'].'.'.$stat_lang.'.arh.'.$zap['file'].'.dat');  
}
else
{
  header ('Location: ./?do=404');
  exit();  
}
$textbody = explode('||____||',$textbody);
if(!$textbody[$history])
{
  header ('Location: ./?do=404');
  exit();
}
if(can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
{
  $action=$_GET['act'];
  if(!$action)
  {
    require_once ('inc/admin/head_info.php');
    echo '<b>'.$lang['rise_pg_q'].'</b>';
    echo '<br />'.$lang['rise_notice'].'</td></table><center>';
    echo '<form action="./?uid='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;do=restole&amp;history='.$link_hist.'&amp;act=save" style="display: inline" method="post"><input type="submit" value="'.$lang['do_rise'].'" class="edit" /></form>';    
    echo '<form action="./?uid='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;do=viewold&amp;history='.$link_hist.'" style="display: inline" method="post"><input type="submit" value="'.$lang['cancel'].'" class="edit" /></form>';    
    echo '</center>';
    require_once ('inc/admin/fin_info.php');  
  }
  else
  {
      $textbody = $textbody[$history];
      $textbody = gz_unpack($textbody);
      $whom=$username;
      if (!$whom)
      {
        $whom=$lang['guest_g'].'('.$lang['ip_i'].$ip.')';
        $user_id = 0;
      }
      if (file_exists($wikipage['path'].'.'.$stat_lang.'.temp.dat'))
        unlink($wikipage['path'].'.'.$stat_lang.'.temp.dat'); 
      
       if(file_exists($wikipage['path'].'.'.$stat_lang.'.hist_count.dat'))
         $hist_numb = file_get_contents($wikipage['path'].'.'.$stat_lang.'.hist_count.dat');
       else
         $hist_numb = 1;
       $old_file= file_get_contents($wikipage['path'].'.'.$stat_lang.'.txt');
       $zap_rous = mysql_result(mysql_query('SELECT COUNT(*) FROM `wm_history` WHERE `page`="'.$id.'" AND `file`="'.$hist_numb.'" AND `lang` = "'.$stat_lang.'";'), 0);
       if($zap_rous <= 100)
       {
         if(!$zap_rous)
           file_put_contents($wikipage['path'].'.'.$stat_lang.'.arh.'.$hist_numb.'.dat',gz_pack($old_file));  
         else
         {
           $back_file= file_get_contents($wikipage['path'].'.'.$stat_lang.'.arh.'.$hist_numb.'.dat');
           file_put_contents($wikipage['path'].'.'.$stat_lang.'.arh.'.$hist_numb.'.dat',$back_file.'||____||'.gz_pack($old_file));   
         }
         mysql_query("INSERT INTO `wm_history` SET
          `page` = '".$id."',
          `user_id` = '".$user_id."',
          `username` = '".$whom."',
          `type` = '0',
          `time` = '".time()."',
          `file` = '".$hist_numb."',
          `lang` = '".$stat_lang."',
          `numb` = '".($zap_rous+1)."' ;");
       }
       else
       {
         $hist_numb = $hist_numb+1;
         file_put_contents($wikipage['path'].'.'.$stat_lang.'.hist_count.dat',$hist_numb);
         file_put_contents($wikipage['path'].'.'.$stat_lang.'.arh.'.$hist_numb.'.dat',gz_pack($old_file));
         mysql_query("INSERT INTO `wm_history` SET
          `page` = '".$id."',
          `user_id` = '".$user_id."',
          `username` = '".$whom."',
          `type` = '0',
          `time` = '".time()."',
          `file` = '".$hist_numb."',
          `lang` = '".$stat_lang."',
          `numb` = '1' ;");  
       }
      
      file_put_contents($wikipage['path'].'.'.$stat_lang.'.txt',$textbody);
      mysql_query("UPDATE `wm_pages` SET
       `last_edit` = '".time()."',
       `id_edit` = '".$user_id."',
       `user_edit` = '".$whom."'
       WHERE `id` = '".$id."'");  //записываем в базу, что были изменения и кем произведены 
      require_once ('inc/admin/head_info.php');
      echo '<b>'.$lang['page_rised'].'</b></td></table><center>';
      echo '<form style="display:inline" method="post" action="'.($mod_rewrite ? 'wiki/'.prw(($curr_name ? $curr_name : $wikipage['name'])) : '?uid='. $id).''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';    
      require_once ('inc/admin/fin_info.php');  
  }

}
else
{
  header ('Location: ./?do=404');
}  
?>
