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


if($rights=='admin')
{
function ch($string1,$string2)
{
    if ($string1==$string2) return 'checked="checked"';
    else return '';
}
$action=$_GET['act'];
if ($action=='save')
{
  
  $name = htmlentities(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
  if (file_exists($path.'/name.'.$requied_lang.'.dat'))
     $name2=file_get_contents($path.'/name.'.$requied_lang.'.dat');
  if ($name2 != $name)
     file_put_contents($path.'/name.'.$requied_lang.'.dat',$name);
     
  $raz_set = intval(abs($_POST['raz_set']));
  if ($raz_set==1)
  {
     if(file_exists($path.'/set.ini'))
       unlink($path.'/set.ini');  
  }
  else
  {
    $users_rights=array('admin','superuser','user','moder','guest');
    if (in_array($_POST['create_stat'], $users_rights) and in_array($_POST['edit_stat'], $users_rights) and in_array($_POST['delete_message'], $users_rights) and in_array($_POST['delete_stat'], $users_rights) and in_array($_POST['add_dir'], $users_rights) and in_array($_POST['remote_dir'], $users_rights) and in_array($_POST['write_comments'], $users_rights))
    {
                  $config='[info]
create_stat='.$_POST['create_stat'].';
edit_stat='.$_POST['edit_stat'].';
delete_message='.$_POST['delete_message'].';
delete_stat='.$_POST['delete_stat'].';
add_dir='.$_POST['add_dir'].';
remote_dir='.$_POST['remote_dir'].';
write_comments='.$_POST['write_comments'].';
';
      file_put_contents($path.'/set.ini',$config);
    }
    else
     $err = $lang['wrong_ind'];  
  }
     
  $info_mess= $lang['ch_saved'];  
}


if (file_exists($path.'/name.'.$requied_lang.'.dat'))
{
   $name=file_get_contents($path.'/name.'.$requied_lang.'.dat');
   $nm=$name; 
}
else
   $name=cut_name($path_p);
          
require_once ('inc/head.php');
echo '<h2>'.$lang['properties_raz'].' "'.$name.'"</h2><hr />';
echo '<b>'.$lang['name_for_ot'].'</b>';
echo '</div><form action="?do=erazd&amp;id='.$path_p.'&amp;act=save" method="post"><div class="stat">';
echo '<input class="edit2" type="text" name="name" value="'.$nm.'" /><hr/>';
echo '<h2>'.$lang['razd_rules'].'</h2>';
if(file_exists($path.'/set.ini')) $fe=1;
echo '<input type="radio" name="raz_set" value="1" '.($fe ? '' : 'checked="checked"').'  /> <b>'.$lang['razd_def'].'</b><br />';
echo '<input type="radio" name="raz_set" value="0" '.($fe ? 'checked="checked"' : '').' /> <b>'.$lang['spec_set'].'</b><br />'; 
   
    if(file_exists($path.'/set.ini'))
       $dr=parse_ini_file($path.'/set.ini');
    else
       $dr=parse_ini_file($parent.'files/rights.ini');
    echo ' <b>'.$lang['cr_stats'].'</b><hr />';
    echo '<input type="radio" name="create_stat" value="admin" '.ch($dr['create_stat'],'admin').' /> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="create_stat" value="moder" '.ch($dr['create_stat'],'moder').' /> '.$lang['on_moders'].'<br />';    
    echo '<input type="radio" name="create_stat" value="superuser" '.ch($dr['create_stat'],'superuser').' /> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="create_stat" value="user" '.ch($dr['create_stat'],'user').' /> '.$lang['on_users'].'<br />';
      echo '<input type="radio" name="create_stat" value="guest" '.ch($dr['create_stat'],'guest').' /> '.$lang['on_guests'].'<br />';
      
    echo ' <b>'.$lang['ed_stats'].'</b><hr />';
    echo '<input type="radio" name="edit_stat" value="admin" '.ch($dr['edit_stat'],'admin').' /> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="edit_stat" value="moder" '.ch($dr['edit_stat'],'moder').' /> '.$lang['on_moders'].'<br />';  
    echo '<input type="radio" name="edit_stat" value="superuser" '.ch($dr['edit_stat'],'superuser').' /> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="edit_stat" value="user" '.ch($dr['edit_stat'],'user').' /> '.$lang['on_users'].'<br />';
      echo '<input type="radio" name="edit_stat" value="guest" '.ch($dr['edit_stat'],'guest').' /> '.$lang['on_guests'].'<br />';
      
    echo ' <b>'.$lang['can_del_mess'].'</b><hr />';
    echo '<input type="radio" name="delete_message" value="admin" '.ch($dr['delete_message'],'admin').' /> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="delete_message" value="moder" '.ch($dr['delete_message'],'moder').' /> '.$lang['on_moders'].'<br />';  
    echo '<input type="radio" name="delete_message" value="superuser" '.ch($dr['delete_message'],'superuser').' /> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="delete_message" value="user" '.ch($dr['delete_message'],'user').' /> '.$lang['on_users'].'<br />';
      echo '<input type="radio" name="delete_message" value="guest" '.ch($dr['delete_message'],'guest').' /> '.$lang['on_guests'].'<br />';
      
    echo ' <b>'.$lang['can_del_stats'].'</b><hr />';
    echo '<input type="radio" name="delete_stat" value="admin" '.ch($dr['delete_stat'],'admin').' /> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="delete_stat" value="moder" '.ch($dr['delete_stat'],'moder').' /> '.$lang['on_moders'].'<br />';  
    echo '<input type="radio" name="delete_stat" value="superuser" '.ch($dr['delete_stat'],'superuser').' /> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="delete_stat" value="user" '.ch($dr['delete_stat'],'user').' /> '.$lang['on_users'].'<br />';
      echo '<input type="radio" name="delete_stat" value="guest" '.ch($dr['delete_stat'],'guest').' /> '.$lang['on_guests'].'<br />';
      
    echo ' <b>'.$lang['can_cr_raz'].'</b><hr />';
    echo '<input type="radio" name="add_dir" value="admin" '.ch($dr['add_dir'],'admin').' /> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="add_dir" value="moder" '.ch($dr['add_dir'],'moder').' /> '.$lang['on_moders'].'<br />';  
    echo '<input type="radio" name="add_dir" value="superuser" '.ch($dr['add_dir'],'superuser').' /> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="add_dir" value="user" '.ch($dr['add_dir'],'user').' /> '.$lang['on_users'].'<br />';
      echo '<input type="radio" name="add_dir" value="guest" '.ch($dr['add_dir'],'guest').' /> '.$lang['on_guests'].'<br />';
      
    echo ' <b>'.$lang['can_del_raz'].'</b><hr />';
    echo '<input type="radio" name="remote_dir" value="admin" '.ch($dr['remote_dir'],'admin').' /> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="remote_dir" value="moder" '.ch($dr['remote_dir'],'moder').' /> '.$lang['on_moders'].'<br />';  
    echo '<input type="radio" name="remote_dir" value="superuser" '.ch($dr['remote_dir'],'superuser').' /> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="remote_dir" value="user" '.ch($dr['remote_dir'],'user').' /> '.$lang['on_users'].'<br />';
      echo '<input type="radio" name="remote_dir" value="guest" '.ch($dr['remote_dir'],'guest').' /> '.$lang['on_guests'].'<br />';
      
    echo '<b>'.$lang['can_comm'].'</b><hr />';
    echo '<input type="radio" name="write_comments" value="admin" '.ch($dr['write_comments'],'admin').' /> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="write_comments" value="moder" '.ch($dr['write_comments'],'moder').' /> '.$lang['on_moders'].'<br />';  
    echo '<input type="radio" name="write_comments" value="superuser" '.ch($dr['write_comments'],'superuser').' /> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="write_comments" value="user" '.ch($dr['write_comments'],'user').' /> '.$lang['on_users'].'<br />';
      echo '<input type="radio" name="write_comments" value="guest" '.ch($dr['write_comments'],'guest').' /> '.$lang['on_guests'].'<br />';
    echo '</div><div class="add"><input type="submit" value="Сохранить" class="edit" /></div>';      
    echo '</form>';

echo '<hr />';
echo '<img width="14" height="14" src="'.$parent.'themes/engine/'.$set['theme'].'/up.png" /> <a href="?id='.$path_p.'">'.$name.'</a><br />';
require_once ('inc/fin.php');
}
else
{
   header ('Location: ./?do=404'); 
}
?>
