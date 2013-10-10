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

if($username)
{
  header ('Location: ./?do=404');
  exit();
}

$action=$_GET['act'];
$login = $_POST['login'];
$password=$_POST['password'];
$password_2=$_POST['password_2'];
$mail=$_POST['mail'];

if ($set['reg']!='regon')
{
	$inv=abs(intval($_GET['inv']));
	if($_SESSION['number_of_register'] > 20)
	{
		mysql_query("DELETE FROM `wm_invites` WHERE `link` = '".$inv."'  LIMIT 1");
		unset($_SESSION['number_of_register']);
	}
	$invite1 = mysql_query("SELECT * FROM `wm_invites` WHERE `link` = '".$inv."' LIMIT 1;");	
	if (mysql_num_rows($invite1))
    
		$invite='&inv='.$inv;
	else
	 header ('Location: ./?do=404');
}

if ($action=='save_user')
{
if (!$login)
$err .= $lang['lg_no_enter'].'<br />';
else
{
  if (strlen($login)<4 or strlen($login)>15)
  {
  $err .= $lang['lg_more_15'].'<br />';
  unset($login);
  }
  else
  {
    if (preg_match("/[^1-9a-z\-\@\*\(\)\?\!\~\_\=\[\]]+/", func('rus_lat',mb_strtolower($login))))
    {
    $err .= $lang['error_login'].'<br/>';
    unset($login);
    }
    else
    {
      $req = mysql_query('SELECT * FROM `wm_users` WHERE `name`="' . mysql_real_escape_string($login) . '";');
      if (mysql_num_rows($req))
      {
        $err .= $lang['us_exists'].'<br/>';
        unset($login);
      }
    }
  }
}
if (!$password)
$err .= $lang['ps_no_enter'].'<br/>';
else
{
  if (strlen($password)<4 or strlen($password)>15)
  {
  $err .= $lang['ps_more_15'].'<br/>';
  unset($password);
  }
  else
  {
  	if (substr_count($password,"\\") or substr_count($password," ")!=0)
  	{
      $err .= $lang['wrong_pass'].'<br/>';
      unset($password);
    }
    else
    {
      if ($password!=$password_2)
      {
        $err .= $lang['ps_not_r'].'<br/>';
        unset($password);
      }
    } 
  }
}
if (!$mail)
$err .= $lang['empty_mail'].'<br/>';
else
{
  if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i",$mail) or substr_count($mail," ")!=0 or substr_count($mail,"\\")!=0)
  {
    $err .= $lang['wrong_mail'].'<br/>';
    unset($mail);
  }
  else
  {
    $req2 = mysql_query('SELECT * FROM `wm_mail_ban` WHERE `mail`="' . mysql_real_escape_string($mail) . '";');
    $req = mysql_query('SELECT * FROM `wm_users` WHERE `mail`="' . mysql_real_escape_string($mail) . '";');
    if (mysql_num_rows($req) or mysql_num_rows($req2))
    {
      $err .= $lang['mail_exists'].'<br/>';
      unset($mail);
    }  
  }
}
if ($_POST['vcode']!=$_SESSION['code'])
{
$err .= $lang['wrong_code'].'!<br/>';
unset($_SESSION['code']);
}

}

if (!$action or($action=="save_user" and $err))
{
  require_once ('inc/head.php');
  if ($err and !empty($action))
   $_SESSION['number_of_register']++;
  echo '<h2>'.$lang['wiki_reg'].'</h2><hr />';
  echo '</div><form action="?do=register&amp;act=save_user'.$invite.'" method="post"><div class="start">';
  echo '<b>'.$lang['you_login'].'</b><br />';
  echo '<input type="text" size="20" name="login" class="edit2" value="'.$login.'" /><hr />';
  echo '<b>'.$lang['you_mail'].'</b><br />';
  echo '<input type="text" size="20" name="mail" class="edit2" value="'.$mail.'" /><hr />';
  echo '<b>'.$lang['pass_p'].'</b><br />';
  echo '<input type="password" size="20" name="password" class="edit2" value="'.$password.'" /><hr />';
  echo '<b>'.$lang['repeat_ps'].'</b><br />';
  echo '<input type="password" size="20" name="password_2" class="edit2" value="'.$password.'" /><hr />';
  echo '<img src="captcha.php?r='.rand(1000, 9999).'" alt="code" /><hr />';
  echo '<b>'.$lang['vkode_v'].'</b><br />';
  echo '<input type="text" size="20" name="vcode" class="edit2" maxlength="7" />';
  echo '</div><div class="add"><input type="submit" value="'.$lang['register_do'].'" class="edit" />';
  echo '</div></form>';
  echo '<hr />';
  require_once ('inc/fin.php');
}
elseif($action=="save_user" and !$err and $set['mail_reg'])
{
   
    function passgen($length)
    {
      $vals = "abcdefghijklmnopqrstuvwxyz0123456789";
      for ($i = 1; $i <= $length; $i++)
      {
        $result .= $vals{rand(0, strlen($vals))};
      } 
     return md5($result);
    }
    
 $pass_mail =passgen(rand(15,30)); 
    
 $subject = $lang['m_acc_act'];
 $mail_body  .= $lang['m_th_to_reg'].' '.$set['site']."\r\n";
 $mail_body  .= $lang['m_do_link'].' '.$set['url'].'?do=activate&link='.$pass_mail."\r\n";
 $mail_body  .= $lang['m_ps_wil_del']."\r\n\r\n";
 $mail_body  .= $lang['n_data_enter']."\r\n";
 $mail_body  .= '---------------'."\r\n";
 $mail_body  .= $lang['login_l'].' '.$login."\r\n";
 $mail_body  .= $lang['pass_p'].' '.$password."\r\n";
 $mail_body  .= '---------------'."\r\n";
 $mail_body  .= $lang['m_site_adm'].' '.$set['site']."\r\n".$lang['m_ign_mail'];
 $admin1 = mysql_query('SELECT * FROM `wm_users` WHERE `id`="1" LIMIT 1;');
 $admin = mysql_fetch_array($admin1);
 $adds = $lang['m_from']." <".$admin['mail'].">\r\n";
 $adds .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
 mail($mail, $subject, $mail_body, $adds);

 mysql_query("INSERT INTO `wm_users_inactive` SET
  `name`='" . mysql_real_escape_string($login) . "',
  `password`='" . md5(md5($password)) . "',
  `mail`='" . mysql_real_escape_string($mail). "',
  `time`='" . time() . "',
  `link`='" . $pass_mail . "',
  `rights`='user';");    
if ($set['reg']!='regon')
{
  mysql_query("DELETE FROM `wm_invites` WHERE `link` = '".$inv."' LIMIT 1;");
  unset($_SESSION['number_of_register']);
}
require_once ('inc/head.php');
echo '<h2>'.$lang['act_akk'].'</h2><hr />';
echo $lang['mail_sended'].'<br/>'.$lang['warn_activ'].'<hr/>';
echo '<center><form style="display:inline" method="post" action="?"><input type="submit" class="edit" value="'.$lang['content'].'" /></form></center>';
echo '</div>';
echo '<hr />';
require_once ('inc/fin.php');
}
elseif(!$set['mail_reg'] and !$err)
{    
 mysql_query("INSERT INTO `wm_users` SET
  `name`='" . mysql_real_escape_string($login) . "',
  `password`='" . md5(md5($password)) . "',
  `mail`='" . mysql_real_escape_string($mail). "',
  `time`='" . time() . "',
  `rights`='user';");    
if ($set['reg']!='regon')
{
  mysql_query("DELETE FROM `wm_invites` WHERE `link` = '".$inv."' LIMIT 1;");
  unset($_SESSION['number_of_register']);
}    
    
require_once ('inc/head.php');
echo '<h2>'.$lang['act_akk'].'</h2><hr />';
echo ''.$lang['activ_suc'].'<hr/>';
echo '<center><form style="display:inline" method="post" action="./?do=login"><input type="submit" class="edit" value="Вход" /></form> <form style="display:inline" method="post" action="?"><input type="submit" class="edit" value="'.$lang['content'].'" /></form></center>';
echo '</div>';
echo '<hr />';
require_once ('inc/fin.php');  
}

?> 