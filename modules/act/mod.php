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

if (can('delete_message') and $user_id)
{
$pid=abs(intval($_GET['pid']));
$com1= mysql_query("SELECT * FROM `wm_discusion` WHERE `id` = '".$pid."' LIMIT 1;");
if(mysql_num_rows($com1))
{
  require_once ('inc/admin/func.php');  
  $com = mysql_fetch_array($com1);
	$act=$_GET['act'];
	if ($act=='edit')
	{
	  require_once ('inc/head.php');
      echo '<h2>'.$lang['change_msg'].'</h2>';
  	  echo '</div><form action="?do=mod&amp;act=save&amp;fid='.$file_p.'&amp;pid='.$pid.'" method="post"><div class="stat">';
      echo '<textarea name="text" class="edit" cols="80" rows="3">';
      echo $com['text'];
      echo '</textarea><br />';
      echo '</div><div class="add"><input type="submit" class="edit" value="'.$lang['send'].'" /></div></form>';
      echo '<hr /><a href="'.$parent.'?uid='.$com['page'].'&amp;do=discusion"><img src="themes/engine/'.$set['theme'].'/up.png" /> '.$lang['discussion'].'</a><br />';
      require_once ('inc/fin.php');
    }
    elseif($act=='save')
    {
        
  	  $mess=trim($_POST['text']);
      if(!$mess)
      {
        admer($lang['mess_empty']);
        exit();
      }
      if(mb_strlen($mess) > $set['max_comm'])
      {
        admer($lang['text_too_big'].' '.$set['max_comm'].' '.$lang['text_too_big_2']);
        exit();  
      }
      mysql_query("UPDATE `wm_discusion` SET `text` = '".mysql_real_escape_string(out($mess))."', `id_edit` = '".$user_id."', `user_edit` = '".$username."', `lastedit` = '".time()."'  WHERE `id` = '".$pid."'");
      require_once ('inc/admin/head_info.php');
      echo '<b>'.$lang['mess_changed'].'</b></td></table><center>';
      echo '<form style="display:inline" method="post" action="?uid='.$com['page'].'&amp;do=discusion"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';    
      require_once ('inc/admin/fin_info.php');
    }
  elseif($act=='del')
  {
    require_once ('inc/admin/head_info.php');
    echo '<b>'.$lang['del_comm'].'</b><br />';
    echo '</td></table><center>';
    echo '<form action="./?pid='.$pid.'&amp;act=sdel&amp;do=mod" style="display: inline" method="post"><input type="submit" value="'.$lang['delete'].'" class="edit" /></form>';    
    echo ' <form action="./?do=discusion&amp;uid='.$com['page'].'" style="display: inline" method="post"><input type="submit" value="'.$lang['cancel'].'" class="edit" /></form>';    
    echo '</center>';
    require_once ('inc/admin/fin_info.php');
  }
  elseif($act=='sdel')
  {
    mysql_query("DELETE FROM `wm_discusion` WHERE `id` = '".$pid."'  LIMIT 1");
    require_once ('inc/admin/head_info.php');
    echo '<b>'.$lang['com_deleted'].'</b><br />';
    echo '</td></table><center>';
    echo ' <form action="./?do=discusion&amp;uid='.$com['page'].'" style="display: inline" method="post"><input type="submit" value="'.$lang['continue'].'" class="edit" /></form>';    
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

?>