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

function new_upd()
{
   global $set, $lang;
   if(file_exists('files/cache/last_update.dat'))
     $last_time = file_get_contents('files/cache/last_update.dat');
   if(time() > ($last_time+(24*60*60)))
   { 
     $new_data = @file_get_contents('http://wikimobile.su/update.php?version='.$set['wiki_version']);
     if(!$new_data)
     {
       mysql_query("UPDATE `wm_settings` SET `value` = '0' WHERE `key` = 'update';");
       return $lang['r_depr'];  
     }    
     elseif(substr($new_data,0,4)!= 'DATA')
     {
       file_put_contents('files/cache/last_update.dat',time());
       return $lang['r_depr'];  
     } 
     else
     {
       $upd_arr = explode('<version>',$new_data);
       $new_count = count($upd_arr)-1;
       if($new_count)
         return '<span style="color:red">'.$lang['r_new'].': '.$new_count.'</span>';
       else
       {
         file_put_contents('files/cache/last_update.dat',time());  
         return $lang['r_no_new'];     
       }  
     }
   }
   else
   {
     return $lang['r_no_new'];  
   }   
}

if ($rights=='admin')
{
  require_once ('inc/admin/head.php');
  echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['adminpanel'].'</div>';
  echo'<div class="elem"><a href="?do=config"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/sis.png" /> '.$lang['wiki_set'].'</a></div>';
  echo '<div class="elem"><a href="?do=config&act=usadm"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/members.gif" /> '.$lang['users_adm'].'</a></div>';
  echo '<div class="elem"><a href="?do=config&act=rekl"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/edit.png" /> '.$lang['ads_adm'].'</a></div>';
  echo '<div class="elem"><a href="?do=config&act=baner"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/counter.png" /> '.$lang['counters'].'</a></div>';
  echo '<div class="elem"><a href="?do=config&act=renew"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/renew.png" /> '.$lang['renew'].'</a> ('.new_upd().')</div>';
  echo '<div class="elem"><a href="http://wikimobile.su/?id=doc"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/about.png" /> '.$lang['documentation'].'</a></div>';
  echo '<div class="elem"><a href="http://annimon.com/forum/id49978"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/select_users.png" /> '.$lang['forum_adm'].'</a></div>';
  echo '<div class="helem">&nbsp;</div>';
  require_once ('inc/admin/fin.php');
}
else
{
	header ('Location: ./?do=404');
}
?> 