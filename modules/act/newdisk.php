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

require_once ('inc/head.php');
echo '<h2>'.$lang['last_dissk'].'</h2><hr />';
$colpages=10;
$nat = abs(intval($_GET['p']));
if (!$nat) $nat=1;
$tr=($nat-1)*$colpages;


$old = time() - (3 * 24 * 3600);
$total = disk_new(); 
if ($total)
{
  $raz1 = mysql_query("SELECT * FROM `wm_pages` LEFT JOIN `wm_page_comm` ON `wm_pages`.`id` = `wm_page_comm`.`page` AND `wm_page_comm`.`userid` = '" . $user_id . "' WHERE `wm_pages`.`comm_time` > '0' AND (`wm_page_comm`.`page` Is Null OR `wm_pages`.`comm_time` > `wm_page_comm`.`time`) ORDER BY `comm_time` DESC LIMIT $tr,$colpages");  
  while($raz = mysql_fetch_assoc($raz1))
  {
    $link_name1 = mysql_query("SELECT * FROM `wm_page_lang` WHERE `pid` = '".$raz['id']."' ORDER BY `id` ASC;;");
    
    $arr_link=array();
    $last_name='';
        
    while ($link_name = mysql_fetch_array($link_name1))
    {
      $arr_link[$link_name['lang']] = $link_name['name'];
      $last_name=$link_name['name'];  
    }
    $page_lang=$requied_lang;
    if($arr_link[$requied_lang])
      $raz['name'] = $arr_link[$requied_lang];
    else
      $raz['name'] = $last_name;
     echo '<img src="'.$parent.'themes/engine/'.$set['theme'].'/list.png" /> <a class="list" href="?uid='.$raz['id'].'&amp;do=discusion">'.out($raz['name']).'</a> - <span class="gray">('.date("d/m/y  H:i", $raz['comm_time']).')</span><br/>';
  }
  $vpage=vpage($total,'?do=late&amp;',$colpages);
  if ($vpage)
  {
    echo '<hr/>'.$vpage;
  }
}
else
  echo $lang['no_new_chang'];

echo '</div><hr />';
require_once ('inc/fin.php');
  
?>