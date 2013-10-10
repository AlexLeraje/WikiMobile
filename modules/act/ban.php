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
   $file1 = mysql_query('SELECT * FROM `wm_users` WHERE `id`="'.$us.'"  LIMIT 1;');
   if(mysql_num_rows($file1) and ($rights=='admin' or $rights=='moder'))
   {
     $file = mysql_fetch_array($file1);
     if($file['rights']!='admin' and $user_id)
     {
       if(($rights=='moder' and $file['rights']!='moder') or $rights=='admin')
       {
          $act=$_GET['act'];
          if($act=='ban')
          {
            $time = intval(abs($_POST['time']));
            if($time)
            {
              $bid = intval(abs($_POST['id']));
              if($bid and $bid >= 1 and $bid <= 5)
              {
                switch ($bid)
                {
                  case 2:
                    if($time > 24)
                      $err .= $lang['err_ban_time'].'<br/>';
                    else
                      $ban_time = 3600 * $time;
                    break;
                  case 3:
                    if($time > 30)
                      $err .= $lang['err_ban_time'].'<br/>';
                    else
                      $ban_time = 86400 * $time;
                    break;
                  case 4:
                    $ban_time = 315360000;
                    break;
                  default:
                    if($time > 60)
                      $err .= $lang['err_ban_time'].'<br/>';
                    else
                      $ban_time = 60 * $time;
                }  
              }
              else
                $err .= $lang['err_ban_type'].'<br/>';
            }
            else
              $err .= $lang['emp_ban_time'].'<br/>';
          $ttl= mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_users_ban` WHERE `user_id` = '".$us."' AND `type` = '1' LIMIT 1;"), 0);
          if($ttl > 0)
            $err .= $lang['us_alrd_ban'].'<br/>';
          }
          if($act=='ban' and !$err)
          {
            $tm = time();
            mysql_query("INSERT INTO `wm_users_ban` SET `user_id` = '".$us."', `type` = '1', `time` = '".$tm."', `to_time` = '".($tm + $ban_time)."', `id_who` = '".$user_id."', `md_name` = '".$username."', `reason` = '".mysql_real_escape_string(out(trim($_POST['reason'])))."' ");  
            require_once ('inc/admin/head_info.php');
            echo '<b>'.$lang['user_banned'].'</b></td></table><center>';
            echo '<form style="display:inline" method="post" action="?do=user&amp;us='.$us.'"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';    
            require_once ('inc/admin/fin_info.php');
            exit();  
          }
          require_once ('inc/head.php');
          echo '<h2>'.$lang['ban_user'].'</h2><hr/>';
          echo '<form action="./?do=ban&amp;act=ban&amp;us='.$us.'" method="post">';
          echo '<b>'.$lang['user_who'].'</b> <a href="'.$parent.'?do=user&amp;us='.$file['id'].'">'.$file['name'].'</a><hr/>';
          echo ''.$lang['ban_info_adm'].'<hr/>';
          echo '<b>'.$lang['ban_to'].'</b>';
          echo '&nbsp; <input type="text" class="edit2" name="time" size="2"  value="12" /><br/>';
          echo '<input type="radio" name="id" value="1" > '.$lang['ban_min'].'<br/>';
          echo '<input type="radio" name="id" value="2" checked="checked" > '.$lang['ban_hour'].'<br/>';
          echo '<input type="radio" name="id" value="3" > '.$lang['ban_days'].'<br/>';
          echo '<input type="radio" name="id" value="4" > '.$lang['ban_for_can'].'<hr/>';
          echo '<b>'.$lang['ban_reason'].' </b>'.$lang['ban_optional'].'<br/>';
          echo '&nbsp; <textarea class="edit2" rows="2" name="reason"></textarea>';
          echo '</div>';
          echo '<div class="add"><input type="submit" value="'.$lang['give_ban'].'" class="edit" /></div><hr /></form>';
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
