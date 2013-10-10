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
echo '<h2>'.$lang['last_changes'].'</h2><hr />';
$colpages=10;
$nat = abs(intval($_GET['p']));
if (!$nat) $nat=1;
$tr=($nat-1)*$colpages;


$old = time() - (3 * 24 * 3600);
$total = pages_new();
if ($total)
{
  $raz1 = mysql_query("SELECT * FROM `wm_pages` LEFT JOIN `wm_page_view` ON `wm_pages`.`id` = `wm_page_view`.`page` AND `wm_page_view`.`userid` = '" . $user_id . "' WHERE `wm_pages`.`time` > '0' AND (`wm_page_view`.`page` Is Null OR `wm_pages`.`last_edit` > `wm_page_view`.`time`) ORDER BY `last_edit` DESC LIMIT $tr,$colpages");  
  while($raz = mysql_fetch_assoc($raz1))
  {
    $link_name1 = mysql_query("SELECT * FROM `wm_page_lang` WHERE `pid` = '".$raz['id']."' ORDER BY `id` ASC;");
    
    $arr_link=array();
    $last_name='';
        
    while ($link_name = mysql_fetch_array($link_name1))
    {
      $arr_link[$link_name['lang']] = $link_name['name'];
      $last_name=$link_name['name'];  
    }
    $page_lang=$raz['lang_edit'];
    if($arr_link[$page_lang])
      $raz['name'] = $arr_link[$page_lang];
    elseif($arr_link[$requied_lang])
      $raz['name'] = $arr_link[$requied_lang];
    else
      $raz['name'] = $last_name;
     if(!$page_lang) $page_lang =  $requied_lang;
     echo '<img src="'.$parent.'themes/engine/'.$set['theme'].'/list.png" /> <img src="./sourse/lang/'.$page_lang.'.png"/> <a class="list" href="'.($mod_rewrite ? './wiki/'.prw($raz['name']).($raz['lang_edit'] ? '/'.$raz['lang_edit'] : '') : './?uid='.$raz['id'].'&amp;lang='.$raz['lang_edit']).'">'.out($raz['name']).'</a> - <span class="gray">('.date("d/m/y  H:i", $raz['last_edit']).')</span><br/>';
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
