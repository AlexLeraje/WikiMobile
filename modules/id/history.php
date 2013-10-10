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
  $nat = abs(intval($_GET['p']));
  if (!$nat) $nat=1;
  $tr=($nat-1)*$set['history_on_page'];
  $fin=$nat*$set['history_on_page']-1;

  if($use_not_st_lang)
      $mess_err = $lang['its_trans_vers'].' <a href="'.$parent.''.($mod_rewrite ? 'wiki/'.prw(($curr_name ? $curr_name : $wikipage['name'])) : '?uid='. $id).'"> '.out(($curr_name ? $curr_name : $wikipage['name'])).' </a> ';
  
  require_once ('inc/head.php');
  echo '<h2>'.$lang['ch_history'].' "'.out($wikipage['name']).'"</h2><hr />';
  $total=mysql_result(mysql_query('SELECT COUNT(*) FROM `wm_history` WHERE `page` = "'.$id.'" AND `lang` = "'.$stat_lang.'" ;'), 0);
  if($total)
  {
    $zap1= mysql_query("SELECT * FROM `wm_history` WHERE `page` = '".$id."' AND `lang` = '".$stat_lang."' ORDER BY `time` DESC LIMIT $tr,".$set['history_on_page']."");
    while ($zap = mysql_fetch_assoc($zap1))
    {
      echo '<img src="'.$parent.'themes/engine/'.$set['theme'].'/list.png" /> <a class="list" href="?uid='.$id.'&amp;do=viewold'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;history='.$zap['id'].'">'.date("d/m/y в  H:i", $zap['time']).'</a> <span class="gray">('.(($zap['type']=='1') ? $lang['ch_pg_ed'] : $lang['ris_pg_ed']).' '.($zap['username'] ? ($zap['user_id'] ? '<a href="./?do=user&amp;us='.$zap['user_id'].'">'.$zap['username'].'</a>' : $zap['username']) : $lang['unknown']).')</span><br/>';  
    }  
  }
  else
  {
    echo '<img src="'.$parent.'themes/engine/'.$set['theme'].'/list.png" />  '.$lang['empty_hist'];  
  }
  $vpage=vpage($total,'?do=history&amp;uid='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;',$set['history_on_page']);
  if ($vpage)
    echo '<hr/>'.$vpage;
  echo '</div>';
  echo '<hr /><a href="'.($mod_rewrite ? 'wiki/'.prw(($curr_name ? $curr_name : $wikipage['name'])).($use_not_st_lang ? '/'.$stat_lang : '') : '?uid='. $id.($use_not_st_lang ? '&amp;lang='.$stat_lang : '')).'"><img src="themes/engine/'.$set['theme'].'/up.png" /> '.$lang['to_page'].'</a><br />';
  if($rights=='admin' and $total)
    echo '<a href="'.$parent.'?uid='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;do=clearhistory"><img src="themes/engine/'.$set['theme'].'/del.png" /> '.$lang['cl_hist_now'].'</a><br />';
  require_once ('inc/fin.php');
?>
