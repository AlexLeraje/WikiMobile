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
  $textbody = $textbody[$history];  
  $textbody = gz_unpack($textbody);
  require_once ('inc/parser.php');
  $err= $lang['s_ver_for'].' '.date("d.m.Y (Изм. в. H:i)",$zap['time']);
  require_once ('inc/head.php');
  echo '<form action="'.$parent.'?" ><input type="hidden" name="do" value="search" /><input type="text" name="item" value="'.out($wikipage['name']).'"  size="15" class="edit2" /><input type="submit" value="'.$lang['search_s'].'" class="edit" title="'.$lang['search_s'].'" /></form><hr/>';

  $nat = abs(intval($_GET['p']));
  if (!$nat) $nat=1;
  $nach=($nat-1)*$set['symbols'];

  $wiki=new WikiParser;
  $textarray = $wiki->parse($textbody);
  $len=count($textarray);
  $textarray=$textarray[$nat-1];
  require_once ('inc/preparse.php');
  $textarray = preparse($textarray);
  echo $textarray;
  echo '<div style="clear: both;"></div>';
  if($wiki->fnt_count)
  {
    echo '<hr/><small>';
    $i=0;
    while($wiki->fnt[$i])
    {
       $a=$i+1;
       echo '<a href="#fnt__'. $a.'" id="fn__'. $a.'" name="fn__'. $a.'">'. $a.')</a>'.$wiki->fnt[$i].'</br>';
       $i++;  
    }
    echo '</small>';  
  }

  $vpage=vpage($len,'?uid='.$id.'&amp;do=viewold'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;history='.$link_hist.'&amp;',1);
  if ($vpage)
  {
    echo '<hr/>'.$vpage;
  }
echo '</div>';
 echo '<div class="add"><form action="?uid='.$id.'&amp;do=editold'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;history='.$link_hist.'" method="POST"><input type="submit" value="'.$lang['sour_code'].'" class="edit" /></form></div>';
$getnav=func('getnav',$wikipage['path']);
if ($getnav or $_GET['wiki']) echo '<hr /><img src="'.$parent.'themes/engine/'.$set['theme'].'/note_go.png" /> '.$getnav.'';
echo '<hr />';
if(can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin')) echo '<a href="'.$parent.'?uid='.$id.'&amp;do=restole'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;history='.$link_hist.'"><img src="'.$parent.'themes/engine/'.$set['theme'].'/restole.png" /> '.$lang['rise_page'].'</a><br />';
echo '<a href="'.$parent.'?uid='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;do=history"><img src="'.$parent.'themes/engine/'.$set['theme'].'/history.png" /> '.$lang['history'].'</a><br />';
}
require_once ('inc/fin.php');
?>
