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

$login=$_POST['login'];
$password=$_POST['password'];
if ($_SESSION['number_of_login'] >= 3) $capcha=TRUE;
if ($action)
{
if (!$login)
   $err .= $lang['lg_no_enter'].'<br/>';
 else
 {
   $req = mysql_query('SELECT * FROM `wm_users` WHERE `name`="' . mysql_real_escape_string(mb_strtolower($login)) . '";');
   if (!mysql_num_rows($req))
     $err .= $lang['us_no_exists'].'<br/>';
   elseif (!$password)
    $err .= $lang['ps_no_enter'].'<br/>';
   else
   {
   $user_data= mysql_fetch_array($req);
   if(md5(md5($password)) != $user_data['password'])
     $err .= $lang['wrong_pass'].'<br/>';
  }
 }
}
if ($capcha)
{
  if ($_POST['vcode']!=$_SESSION['code'])
  {
    $err .= $lang['wrong_code'].'<br/>';
    unset($_SESSION['code']);
  }
}
if (!$action or $err)
{
require_once ('inc/head.php');
	echo '<h2>'.$lang['login'].'</h2><hr/>';
	if ($err and !empty($action))
   $_SESSION['number_of_login']++;
	echo '</div><form action="?do=login&amp;act=login" method="post"><div class="stat">';
	echo '<b>'.$lang['login_l'].'</b><hr /><input type="text" name="login" class="edit2" /><br/>';
	echo '<b>'.$lang['pass_p'].'</b><hr /><input type="password" name="password" class="edit2" /><br/>';
	if ($capcha)
    echo '<hr /><img class="cod" src="captcha.php?r=' . rand(1000, 9999) . '" alt="'.$lang['vkode'].'" /><hr /><b>'.$lang['vkode_v'].' </b><hr /> <input class="edit2" type="text" name="vcode" />';
	echo '</div><div class="add"><input type="submit" value="'.$lang['login'].'" class="edit" /></div>';
	echo '</form><hr />';
require_once ('inc/fin.php');
}
elseif($action=='login' and !$err)
{
	unset($_SESSION['number_of_login']);
	$_SESSION['wiki_user']=mb_strtolower($login);
    $req = mysql_query('SELECT * FROM `wm_users` WHERE `name`="' . mysql_real_escape_string(mb_strtolower($login)) . '" LIMIT 1;');
    $user_data= mysql_fetch_array($req);
    //шлем печеньки
    setcookie('log', encrypt(mb_strtolower($login)), time() + 3600 * 24 * 365);
    setcookie('psw', encrypt($user_data['password']), time() + 3600 * 24 * 365);
    require_once ('inc/admin/head_info.php');
	echo '<b>'.$lang['u_entered'].'</b></td></table><center><form style="display:inline" method="post" action="?"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';
require_once ('inc/admin/fin_info.php');
}
?> 