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
$us= intval(abs($_GET['us']));
if($us)
{
  $file1 = mysql_query('SELECT * FROM `wm_users` WHERE `id`="'.$us.'" LIMIT 1;');
  if(mysql_num_rows($file1))
  {
     require_once ('inc/head.php');
     $file = mysql_fetch_array($file1);
     echo '<h2>'.$lang['ban_history'].' '.$file['name'].':</h2>'; 
     $colpages=10;
     $nat = intval(abs($_GET['p']));
     if (!$nat) $nat=1;
     $tr=($nat-1)*$colpages;
     $total = mysql_result(mysql_query("SELECT COUNT(*)  FROM `wm_users_ban` WHERE `user_id` = '".$us."'"), 0);
     if($total >0)
     { 
       $ban1= mysql_query("SELECT * FROM `wm_users_ban` WHERE `user_id` = '".$us."' ORDER BY `time` DESC LIMIT $tr,$colpages");
       while ($ban = mysql_fetch_assoc($ban1))
       {
         echo '<hr/>'.$lang['letter_c'].' <span class="green">'.date("d/m/y  H:i",$ban['time']).'</span> '.$lang['to'].' <span class="green">'.date("d/m/y  H:i",$ban['to_time']).'</span><br/>';  
         echo '<b>'.$lang['ban_status'].'</b> ';
         if($ban['type']==1)
           echo '<span style="color:red;">'.$lang['active'].'</span>';
         elseif($ban['type']==0)
           echo $lang['unbanned'];
         else
           echo $lang['ban_is_fin'];
         echo '<br/><b>'.$lang['ban_who'].' </b>'.$ban['md_name'].'<br/>';
         echo '<b>'.$lang['ban_reason'].' </b>'.($ban['reason'] ? $ban['reason'] : $lang['not_spec']);
       }
       $vpage=vpage($total,'?do=banhistory&amp;us='.$us.'&amp;',$colpages);
       if ($vpage)
       {
         echo '<hr/>'.$vpage;
       }
     }
     else
       echo '<hr/>'.$lang['no_bans'].''; 
     echo '</div>';
     echo '<hr/>'; 
     echo '<a href="./?do=user&amp;us='.$us.'"><img src="themes/engine/'.$set['theme'].'/register.png" /> '.$lang['anketa'].'</a><br />';
     require_once ('inc/fin.php'); 
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
