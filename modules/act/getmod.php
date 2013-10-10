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
$mod =intval(abs($_GET['mod'])); 
$disk1= mysql_query("SELECT * FROM `wm_mod` WHERE `id` = '".$mod."' LIMIT 1;");
if(mysql_num_rows($disk1))
{
  $disk = mysql_fetch_array($disk1);
  $textbody = file_get_contents('files/mod/'.$mod.'.ini');
  require_once ('inc/parser.php');
  $wikipage['name'] = $disk['name'];
  $err = $lang['premoder_i'];
  require_once ('inc/head.php');

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

  $vpage=vpage($len,'?do=getmod&amp;mod='.$mod.'&amp;',1);
  if ($vpage)
    echo '<hr/>'.$vpage;
  echo '</div>';
  echo '<div class="add"><form style="display:inline" action="?do=modp&amp;mod='.$mod.'&amp;act=agree" method="POST"><input type="submit" value="'.$lang['agree'].'" class="edit" /></form>  <form style="display:inline" action="?do=modp&amp;mod='.$mod.'&amp;act=no" method="POST"><input type="submit" value="'.$lang['delete'].'" class="edit" /></form></div>';
  $hist = $disk['path'];
  $getnav=func('getnav',$hist);
  if ($getnav or $_GET['wiki']) echo '<hr /><img src="'.$parent.'themes/engine/'.$set['theme'].'/note_go.png" /> '.$getnav.'';
  echo '<hr />';
  echo '<a href="./?do=modp"><img src="themes/engine/'.$set['theme'].'/up.png" /> '.$lang['premoder'].'</a><br />';
  require_once ('inc/fin.php');
}
else
{

} 
?>
