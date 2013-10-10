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
  if ($_GET['say']=='1')
  {
    if ($_SESSION['lastmess'] + $set['antiflood'] < time())
      unset($_SESSION['lastmess']);
    if (can('write_comments'))
    {
     if ($wikipage['comments'] or $rights=='admin')
     {
      if ($rights=='guest')
      {
        $u_n = trim($_POST['username']);
      	if ($_POST['vcode']!=$_SESSION['code'])
        {
          $err .= $lang['wrong_code'].'<br/>';
          unset($_SESSION['code']);
        }
        elseif(!$u_n)
        {
         	$err .= $lang['nik_empty'].'<br/>';
        }
      }
      $text=trim($_POST['text']);
      if (!$err)
      {
  	    $whom=$username;
  	    if (!$whom)
        {
          $whom=$lang['guest_g'].' '.out($u_n);
          $user_id = 0;
        }
        $lastdisk1= mysql_query("SELECT * FROM `wm_discusion` WHERE `page` = '".$id."' ORDER BY `time` DESC LIMIT 1;");
        if(mysql_num_rows($lastdisk1))
        {
          $lastdisk = mysql_fetch_array($lastdisk1);
          if ($lastdisk['text']==out($text))
             $err .= $lang['mess_double'].'<br/>';
          if($rights!='admin' and $user_id and $lastdisk['user_id']==$user_id)
          {
            if($lastdisk['time'] + $set['antiflood'] > time())
              $err .= $lang['intiflood_1'].' '.$set['antiflood'].' '.$lang['intiflood_2'].'<br/>';
          }
          elseif($rights!='admin' and !$user_id)
          {
             if($_SESSION['lastmess'] + $set['antiflood'] > time())
               $err .= $lang['intiflood_1'].' '.$set['antiflood'].' '.$lang['intiflood_2'].'<br/>'; 
          }
        }
        
        if (mb_strlen($text) > $set['max_comm'])
           $err .= $lang['mess_bgg_1'].' '.$set['max_comm'].' '.$lang['mess_bgg_1'].'<br/>'; 

        if(!$err)
        {
        mysql_query("INSERT INTO `wm_discusion` SET
         `user_id`='" . $user_id . "',
         `username`='" . mysql_real_escape_string($whom) . "',
         `page`='" . $id . "',
         `text`='" . mysql_real_escape_string(out($text)). "',
         `time`='" . time() . "';");
         
        mysql_query("UPDATE `wm_pages` SET  `comm_time` = '".time()."' WHERE `id` = '".$id."'"); 
        if(!$user_id)
        {
          $_SESSION['lastmess'] = time();
          $_SESSION['username'] = $u_n;
        }
         $info_mess .= $lang['comm_added'].'<br/>'; 
        }
     }
   }
   else
	  $err .= $lang['comm_depr'].'<br/>';
   }
   else
	  $err .= $lang['comm_not_all'].'<br/>';
   }

$page=cut_name($file_p);

$nat = abs(intval($_GET['p']));
if (!$nat) $nat=1;
$tr=($nat-1)*$set['disscusion_on_page'];
if ($user_id)
{
  // Фиксация факта прочтения
  $req = mysql_query("SELECT * FROM `wm_page_comm` WHERE `page` = '".$id."' AND `userid` = '".$user_id."' LIMIT 1");
  if (mysql_num_rows($req) > 0)
  {
     $res = mysql_fetch_assoc($req);
     $wikipage1= mysql_query("SELECT * FROM `wm_pages` LEFT JOIN `wm_page_lang` ON `wm_pages`.`id`=`wm_page_lang`.`pid` WHERE `wm_page_lang`.`pid` IS NOT NULL AND `wm_pages`.`id` = '".$id."' ORDER BY `wm_page_lang`.`id` LIMIT 1;");
     $wikipage = mysql_fetch_array($wikipage1);
     if ($wikipage['comm_time'] > $res['time'])
        mysql_query("UPDATE `wm_page_comm` SET `time` = '".time()."' WHERE `page`='".$id."' AND `userid` = '".$user_id."'");
  }
  else
  {
    // Ставим метку о прочтении
    mysql_query("INSERT INTO `wm_page_comm` SET  `page` = '".$id."', `userid` = '".$user_id."', `time` = '".time()."'");
  }
}
require_once ('inc/head.php');

echo '<h2>'.$lang['diss_to'].' "'.out($wikipage['name']).'"</h2><hr /><br />';

$total = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_discusion` WHERE `page` = '".$id."' ;"), 0);
if ($total >0)
{
  $disk1= mysql_query("SELECT * FROM `wm_discusion` WHERE `page` = '".$id."' ORDER BY `time` DESC LIMIT ".$tr.",".$set['disscusion_on_page'].";");
  while($disk = mysql_fetch_assoc($disk1))
  {
 	 echo '<div class="headpost">'.$lang['from_user'].' <b>';
     if($disk['user_id'])
       echo '<a href="?do=user&amp;us='.$disk['user_id'].'">'.$disk['username'].'</a>';
     else
       echo $disk['username'];
     echo '</b> '.$lang['in'].' '.date("H:i / d.m.Y",$disk['time']).'';
 	 if (can('delete_message')) echo ' <a href="?do=mod&amp;act=edit&amp;pid='.$disk['id'].'">'.$lang['s_change'].'</a> | <a href="?do=mod&amp;act=del&amp;pid='.$disk['id'].'">'.$lang['s_del'].'</a> ';
 	 echo '</div><div class="post">'.smiles($disk['text']);
     if ($disk['lastedit'])
       echo '<hr /><small>'.$lang['s_change'].' '.$disk['user_edit'].' ('.date("H:i / d.m.Y",$disk['lastedit']).')</small>';
     echo '</div><br />';
  }
}
else
{
	echo $lang['no_mess'].'<br /><br /><hr />';
}
$vpage=vpage($total,'?do=discusion&amp;uid='.$id.'&amp;',$set['disscusion_on_page']);
if ($vpage)
{
	echo $vpage;
}
else
  echo '<hr />';
if (can('write_comments'))
{
  if ($wikipage['comments'] or $rights=='admin')
  {
	echo '</div><form action="./?do=discusion&amp;uid='.$id.'&amp;say=1" method="post"><div class="stat">';
	if($rights=='guest')
	{
		echo '<b>'.$lang['you_nic'].' </b><br />';
		echo '<input class="edit2" type="text" size="15" name="username" value="'.out($_SESSION['username']).'" /><br />';
		
	}
    echo '<b>'.$lang['you_comm'].' </b><br />';
    echo '<textarea name="text" class="edit" cols="80"  rows="2">';
    if($err)
    {
      $txt= trim($_POST['text']); 
      if($txt)
        echo out($txt);    
    }
    echo '</textarea><br />';
    if ($rights=='guest')
      echo '<hr /><img class="cod" src="captcha.php?r='.rand(1000, 9999).'" alt="'.$lang['vkode'].'" /><hr /><b>'.$lang['vkode_v'].' </b><br /> <input class="edit2" type="text" size="7" name="vcode"/><hr />';
    echo '</div><div class="add"><input type="submit" class="edit" value="'.$lang['send'].'" /></div></form>';
  }
  else
	echo $lang['comm_depr'].'</div>';
}
else
	echo $lang['comm_not_all'].'</div>';

echo '<hr /><a href="'.($mod_rewrite ? 'wiki/'.prw($wikipage['name']) : '?uid='.$id).'"><img src="themes/engine/'.$set['theme'].'/up.png" /> '.$lang['to_page'].'</a><br />';
echo '<a href="./?do=smiles"><img src="'.$parent.'themes/engine/'.$set['theme'].'/sm.png" /> '.$lang['smiles'].'</a><br />';
require_once ('inc/fin.php');
?> 
