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
    echo '<h2>'.$lang['anketa'].' '.$file['name'].':</h2><hr/>';
    $r['admin'] = $lang['admin'];
    $r['moder'] = $lang['moder'];  
    $r['superuser'] = $lang['superuser'];
    $r['user'] = $lang['user'];
    $r['guest'] = $lang['guest_g'];
    $ttl1= mysql_query("SELECT * FROM `wm_users_ban` WHERE `user_id` = '".$us."' AND `type` = '1' LIMIT 1;");
    if(mysql_num_rows($ttl1))
    {
      $ttl = mysql_fetch_array($ttl1);  
      echo '<b style="color:red;">('.$lang['banned_to_b'].' '.date("d/m/y  H:i",$ttl['to_time']).')</b><hr/>';
    }
    echo '<b>'.$lang['a_obs'].'</b><br/>';
    echo '&nbsp;&nbsp;<span class="green" >'.$lang['a_status'].'</span> <b style="color:red;">'.$r[$file['rights']].'</b> '.$tx.'<br/>';
    echo '&nbsp;&nbsp;<span class="green">'.$lang['login_l'].'</span> '.$file['name'].'<br/>';
    echo '&nbsp;&nbsp;<span class="green">'.$lang['a_mail'].'</span> '.$file['mail'].'<br/>';
    echo '&nbsp;&nbsp;<span class="green">'.$lang['a_regged'].'</span> '.date("d/m/y  H:i",$file['time']).'<br/>';
    $dat1 = mysql_query('SELECT * FROM `wm_users_info` WHERE `userid`="'.$us.'" LIMIT 1;');
    $dat = mysql_fetch_array($dat1);
    echo '<hr/><b>'.$lang['a_private'].'</b><br/>';
    if($dat['sex']==1)
      $sex = $lang['female'];
    elseif($dat['sex']==2)
      $sex = $lang['male'];
    else
      $sex = '<span class="gray">'.$lang['not_spec_2'].'</span>';
    echo '&nbsp;&nbsp;<span class="green">'.$lang['a_gen'].'</span> '.$sex.'<br/>';
    echo '&nbsp;&nbsp;<span class="green">'.$lang['a_name'].'</span> '.( $dat['name'] ? $dat['name'] : '<span class="gray">'.$lang['not_spec_3'].'</span>').'<br/>';
    echo '&nbsp;&nbsp;<span class="green">'.$lang['a_birth'].'</span> '.( $dat['born'] ? $dat['born'] : '<span class="gray">'.$lang['not_spec_3'].'</span>').'<br/>';
    echo '&nbsp;&nbsp;<span class="green">'.$lang['a_from'].'</span> '.( $dat['place'] ? $dat['place'] : '<span class="gray">'.$lang['not_spec_3'].'</span>').'<br/>';
    echo '&nbsp;&nbsp;<span class="green">'.$lang['a_icq'].'</span> '.( $dat['icq'] ? $dat['icq'] : '<span class="gray">'.$lang['not_spec_2'].'</span>').'<br/>';
    echo '&nbsp;&nbsp;<span class="green">'.$lang['a_site'].'</span> '.( $dat['site'] ? $dat['site'] : '<span class="gray">'.$lang['not_spec_2'].'</span>').'<br/>';
    echo '&nbsp;&nbsp;<span class="green">'.$lang['a_ph_mod'].'</span> '.( $dat['phone'] ? $dat['phone'] : '<span class="gray">'.$lang['not_spec_2'].'</span>').'<br/>';
    echo '&nbsp;&nbsp;<span class="green">'.$lang['a_a_me'].'</span> '.( $dat['about'] ? $dat['about'] : '<span class="gray">'.$lang['empty'].'</span>').'<br/>';
    echo '</div>';
    if($rights=='admin' or ($rights=='moder' and $file['rights']!='admin') or $us==$user_id)
    {
       echo '<div class="add">';
       if($rights=='admin' or $us==$user_id)
       {
         echo '<form style="display:inline" action="./?do=useredit&amp;us='.$us.'" method="post"><input type="submit" value="'.$lang['change'].'" class="edit" /></form>';    
       }
       if((($rights=='moder' and $file['rights']!='moder' and $file['rights']!='admin') or $rights=='admin') and $us!=$user_id)
       {
         if(mysql_num_rows($ttl1))
           echo '<form style="display:inline" action="./?do=unsetban&amp;us='.$us.'" method="post"><input type="submit" value="'.$lang['do_razb'].'" class="edit" /></form>';  
         else
           echo '<form style="display:inline" action="./?do=ban&amp;us='.$us.'" method="post"><input type="submit" value="'.$lang['give_ban'].'" class="edit" /></form>';
       }  
       echo '</div>'; 
    }
    echo '<hr />';
    echo '<a href="./?do=banhistory&amp;us='.$us.'"><img src="themes/engine/'.$set['theme'].'/ban.png" /> '.$lang['ban_history'].'</a><br />';
    if($rights=='admin' and $us!=$user_id)
      echo '<a href="?do=config&amp;act=usdel&amp;usid='.$us.'"><img src="themes/engine/'.$set['theme'].'/del.png" /> '.$lang['del_user_d'].'</a><br />';
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
