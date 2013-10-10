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

if($use_not_st_lang)
  $mess_err = $lang['its_trans_vers'].' <a href="'.$parent.''.($mod_rewrite ? 'wiki/'.prw(($curr_name ? $curr_name : $wikipage['name'])) : '?uid='. $id).'"> '.out(($curr_name ? $curr_name : $wikipage['name'])).' </a> ';

$textbody = explode('||____||',$textbody);
if(!$textbody[$history])
   header ('Location: ./?do=404');
else
{
  require_once ('inc/admin/func.php');
  $textbody = $textbody[$history];  
  $textbody = gz_unpack($textbody);
  $action=$_GET['act'];

  $numvl=abs(intval($_GET['p']));
  $numfiles=abs(intval($_GET['f']));
  
  $err= $lang['s_ver_for'].' '.date("d.m.Y (Изм. в. H:i)",$zap['time']);
  require_once ('inc/head.php');
  echo '<h2>'.$lang['sour_code'].' "'.out($wikipage['name']).'"</h2><hr />';
  echo ''.$lang['it_old_page'].'<hr/>';
  echo '<form action="" method="post">';
  echo '<b>'.$lang['page_text_t'].' </b><textarea name="text" class="edit" cols="40"  rows="15">';
  echo out($textbody);
  echo '</textarea>';
  echo '</form>';
  echo '<hr />';
  echo '<ul>';
  $count=mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_files` WHERE `page` = '".$id."' ;"), 0);
  if (!$numvl and !$downfiles)
  {
    echo '<li class="dir"><a  href="?uid='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;do=editold'.($numfiles ? '&amp;f='.$numfiles : '').'&amp;p=1&amp;history='.$link_hist.'">'.$lang['page_files'].' ('.$count.')</a></li>';
  }
  else
    {
      echo '<li class="dir2"><a  href="?uid='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;do=editold'.($numfiles ? '&amp;f='.$numfiles : '').'&amp;history='.$link_hist.'">'.$lang['page_files'].' ('.$count.')</a></li><hr/>';
      $att1= mysql_query("SELECT * FROM `wm_files` WHERE `page` = '".$id."' ;");
      while($att = mysql_fetch_assoc($att1))
      {
         $ext=getextension($att['filename']);
         echo '<img width="16" height="16" src="'.(file_exists('sourse/ext/'.$ext.'.png') ? 'sourse/ext/'.$ext.'.png' : 'themes/admin/'.$set['theme'].'/sis.png').'" />  <input class="edit2" type="text" size="10" value="{{'.(is_image($att['filename']) ? 'img' : 'file').':'.$att['filename'].'|'.$att['name'].'}}"/> ';
         echo '<a href="?do=fileinfo&amp;file='.$att['id'].'">'.$lang['s_inf'].'</a>- <b>'.$att['name'].'</b><br />';
      }
        echo '<hr/>';
    }
  echo '</ul></div>';
  echo '<div class="add"><form action="'.$parent.'?uid='.$id.'&amp;do=viewold'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;history='.$link_hist.'" method="post"><input type="submit" value="'.$lang['vieing'].'" class="edit" /></form></div>';
  echo '<hr />';
  echo '<a href="'.($mod_rewrite ? 'wiki/'.prw(($curr_name ? $curr_name : $wikipage['name'])) : '?uid='. $id).''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'"><img src="themes/engine/'.$set['theme'].'/up.png" /> '.$lang['to_page'].'</a><br />';
  if(can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
    echo '<a href="'.$parent.'?uid='.$id.'&amp;do=restole'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;history='.$link_hist.'"><img src="'.$parent.'themes/engine/'.$set['theme'].'/restole.png" /> '.$lang['rise_page'].'</a><br />';
  echo '<a href="'.$parent.'?uid='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;do=history"><img src="'.$parent.'themes/engine/'.$set['theme'].'/history.png" /> '.$lang['history'].'</a><br />';
  require_once ('inc/fin.php');
}

?>

