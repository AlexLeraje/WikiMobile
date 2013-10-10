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
echo '<h2>'.$lang['us_online'].'</h2>';
$time=time();
$total = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_users` WHERE `lastvisit` > '" . ($time - 300) . "'"), 0);
$colpages=20;
$nat = abs(intval($_GET['p']));
if (!$nat) $nat=1;
$tr=($nat-1)*$colpages;
if($total > 0)
{
  $user1= mysql_query("SELECT * FROM `wm_users` WHERE `lastvisit` > '" . ($time - 300) . "' ORDER BY `lastvisit` DESC LIMIT $tr,$colpages"); 
  while ($user = mysql_fetch_assoc($user1))
  {
    $r['admin'] = '<span style="color:red;">'.$lang['admin'].'</span>';
    $r['moder'] = '<span style="color:orange;">'.$lang['moder'].'</span>';  
    $r['superuser'] = $lang['superuser'];
    $r['user'] = $lang['user'];
    echo '<hr/><img width="14" height="14" src="themes/engine/'.$set['theme'].'/user.png" /><b> <a href="'.$parent.'?do=user&amp;us='.$user['id'].'">'.$user['name'].'</a></b> ';
    echo '('.$r[$user['rights']].')';
    if($rights=='admin' or ($rights=='moder' and $user['rights']!='admin'))
    {
      echo '<br/>&nbsp; <b>'.$lang['ip_i'].'</b> '.$user['ip'].'<br/>';
      echo '&nbsp; <b>'.$lang['ua_a'].'</b> '.$user['ua'];
    }   
  }
  $vpage=vpage($total,'?do=online&amp;',$colpages);
  if ($vpage)
  {
    echo '<hr/>'.$vpage;
  }
}
else
{
   echo '<hr/>'.$lang['no_us_online']; 
}
echo '</div><hr/>';
echo '<a href="./?do=gonline"><img src="themes/engine/'.$set['theme'].'/ban.png" /> '.$lang['show_gs'].'</a><br />';
require_once ('inc/fin.php');
?>
