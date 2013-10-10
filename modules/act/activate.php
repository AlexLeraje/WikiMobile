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
$link=trim($_GET['link']);
if (!$link or $_SESSION['mail_reg'])
{
  header ('Location: ./?do=404');
  $_SESSION['mail_reg']=='1';
  exit();  
}
$file1 = mysql_query('SELECT * FROM `wm_users_inactive` WHERE `link`="'.$link.'" LIMIT 1;');
if (mysql_num_rows($file1))
{
  $file = mysql_fetch_array($file1);
   mysql_query("INSERT INTO `wm_users` SET
  `name`='" . mysql_real_escape_string($file['name']) . "',
  `password`='" . mysql_real_escape_string($file['password']) . "',
  `mail`='" . mysql_real_escape_string($file['mail']). "',
  `time`='" . $file['time'] . "',
  `rights`='".$file['rights']."';");  
   $postid = mysql_insert_id();
   mysql_query("INSERT INTO `wm_users_info` SET `userid`='" . $postid . "'");
   
   mysql_query("DELETE FROM `wm_users_inactive` WHERE `id` = '".$file['id']."'  LIMIT 1");
   
   require_once ('inc/head.php');
   echo '<h2>'.$lang['act_akk'].'</h2><hr />';
   echo ''.$lang['activ_suc'].'<hr/>';
   echo '<center><form style="display:inline" method="post" action="./?do=login"><input type="submit" class="edit" value="Вход" /></form> <form style="display:inline" method="post" action="?"><input type="submit" class="edit" value="'.$lang['content'].'" /></form></center>';
   echo '</div>';
   echo '<hr />';
   require_once ('inc/fin.php'); 
}
else
{
  header ('Location: ./?do=404');
  $_SESSION['mail_reg']=='1';
  exit(); 
}
?>
