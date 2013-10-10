<?php
////////////////////////////////////////////////////
//                 WikiMobile                     //
////////////////////////////////////////////////////
// Автор:              web_demon                  //
// Оф. Сайт:           http://wikimobile.su       //
// Oф. сайт поддержки: http://annimon.com         //
// E-mail:             web_demon@mail.ru          //
////////////////////////////////////////////////////

$us= intval(abs($_GET['us']));
if($us)
{
   $file1 = mysql_query('SELECT * FROM `wm_users` WHERE `id`="'.$us.'" LIMIT 1;');
   if(mysql_num_rows($file1) and ($rights=='admin' or $rights=='moder'))
   {
     $file = mysql_fetch_array($file1);
     if($file['rights']!='admin' and $user_id)
     {
       if(($rights=='moder' and $file['rights']!='moder') or $rights=='admin')
       {
         $ttl1= mysql_query("SELECT * FROM `wm_users_ban` WHERE `user_id` = '".$us."' AND `type` = '1' LIMIT 1;");
         if(mysql_num_rows($ttl1))
         {
           $ttl = mysql_fetch_array($ttl1);
           $act=$_GET['act'];
           if(!$act)
           {
             require_once ('inc/admin/head_info.php');
             echo '<b>'.$lang['razb_user'].'</b><br />';
             echo ''.$lang['ban_reason'].' '.($ttl['reason'] ? $ttl['reason'] : $lang['not_spec']);
             echo '</td></table><center>';
             echo '<form action="./?do=unsetban&amp;us='.$us.'&amp;act=unset" style="display: inline" method="post"><input type="submit" value="'.$lang['do_razb'].'" class="edit" /></form>';    
             echo ' <form action="./?do=user&amp;us='.$us.'" style="display: inline" method="post"><input type="submit" value="'.$lang['cancel'].'" class="edit" /></form>';    
             echo '</center>';
             require_once ('inc/admin/fin_info.php');
           }
           else
           {
             mysql_query("UPDATE `wm_users_ban` SET `type` = '0'  WHERE `user_id` = '".$us."'");
             require_once ('inc/admin/head_info.php');
             echo '<b>'.$lang['b_user'].' '.$file['name'].' '.$lang['b_razb2'].'</b><br />';
             echo '</td></table><center>';
             echo ' <form action="./?do=user&amp;us='.$us.'" style="display: inline" method="post"><input type="submit" value="'.$lang['continue'].'" class="edit" /></form>';    
             echo '</center>';
             require_once ('inc/admin/fin_info.php'); 
           }
         }
         else
         {
           header ('Location: ./?do=404'); 
         }  
       }
       else
       {
         header ('Location: ./?do=404');
       }  
     }
     else
     {
       header ('Location: ./?do=404');  
     }
   }
   else
   {
     header ('Location: ./?do=404');
   } 
}
else
{
  header ('Location: ./?do=404');
}  
?>
