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
require_once ('inc/head.php');
echo '<h2>'.$lang['guests_online'].'</h2>';
$time=time();
$total = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_guests` WHERE `lastvisit` > '" . ($time - 300) . "'"), 0);
$colpages=20;
$nat = abs(intval($_GET['p']));
if (!$nat) $nat=1;
$tr=($nat-1)*$colpages;
if($total > 0)
{
   $user1= mysql_query("SELECT * FROM `wm_guests` WHERE `lastvisit` > '" . ($time - 300) . "' ORDER BY `lastvisit` DESC LIMIT $tr,$colpages"); 
   while ($user = mysql_fetch_assoc($user1))
   {
      echo '<hr/><img width="14" height="14" src="themes/engine/'.$set['theme'].'/user.png" /><b> '.$lang['guest_g'].'</b> ';
      echo '<br/>&nbsp; <b>'.$lang['ip_i'].'</b> '.$user['ip'].'<br/>';
      echo '&nbsp; <b>'.$lang['ua_a'].'</b> '.$user['user_agent'];
   } 
  $vpage=vpage($total,'?do=gonline&amp;',$colpages);
  if ($vpage)
  {
    echo '<hr/>'.$vpage;
  }
}
else
{
   echo '<hr/>'.$lang['no_g_online'].''; 
}
echo '</div><hr/>';
echo '<a href="./?do=online"><img src="themes/engine/'.$set['theme'].'/ban.png" /> '.$lang['show_users'].'</a><br />';
require_once ('inc/fin.php');
?>
