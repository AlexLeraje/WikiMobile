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

session_name('SESID');
session_start();
mb_internal_encoding('UTF-8');
Error_Reporting(E_ALL & ~ E_NOTICE);

require_once ('inc/db.php');
$connect = mysql_connect($db_host, $db_user, $db_pass) or die('cannot connect to db');
mysql_select_db($db_name) or die('cannot connect to db');
mysql_query("SET NAMES 'utf8'", $connect);

function decrypt2($string)  
{   
 $string = base64_decode(str_replace('*','=',$string));
 return $string;  
}

if (isset($_COOKIE['log']) && isset($_COOKIE['psw']))
{ 
    $req = mysql_query('SELECT * FROM `wm_users` WHERE `name`="' . mysql_real_escape_string(decrypt2($_COOKIE['log'])) . '";');
    if(mysql_num_rows($req))
    {
       $set_user= mysql_fetch_array($req);
       if ($set_user['password']==decrypt2($_COOKIE['psw']))
           $_SESSION['wiki_user'] = decrypt2($_COOKIE['log']);
       else
       {
          setcookie('log', '');
          setcookie('psw', '');
       }    
    }
    else
    {
      setcookie('log', '');
      setcookie('psw', '');  
    }
}

if ($_SESSION['wiki_user'])
{
  $req = mysql_query('SELECT * FROM `wm_users` WHERE `name`="' . mysql_real_escape_string($_SESSION['wiki_user']) . '";');
  if(mysql_num_rows($req))
  {
    $set_user= mysql_fetch_array($req);
    $login=$_SESSION['wiki_user'];
    $username=$set_user['name'];
    $rights=$set_user['rights'];
    $user_id=$set_user['id'];
    
  }
  else
  {
    unset($_SESSION['wiki_user']);
    $rights='guest';  
  }
}
else
{
    unset($_SESSION['wiki_user']);
    $rights='guest';
}

////////////////////////////////
////////////////////////////////
////////////////////////////////
$agn = htmlentities(substr($_SERVER['HTTP_USER_AGENT'], 0, 100), ENT_QUOTES);
$req = mysql_query("SELECT * FROM `wm_settings`");
$set = array();
while ($res = mysql_fetch_row($req)) $set[$res[0]] = $res[1];
mysql_free_result($req);

$all_langs = unserialize($set['inst_lang']);

if($user_id and $set_user['lang'] and in_array($set_user['lang'],$all_langs))
   $requied_lang=$set_user['lang']; 
else
{
    
  if(in_array($set['lang'],$all_langs))
  {
    if($_COOKIE['lang'] and in_array($_COOKIE['lang'],$all_langs))
      $requied_lang=$_COOKIE['lang'];
    else
    {
      function auto_lang()
      {    
        $langcode = (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
        $langcode = (!empty($langcode)) ? explode(";", $langcode) : $langcode;
        $langcode = (!empty($langcode['0'])) ? explode(",", $langcode['0']) : $langcode;
        $langcode = (!empty($langcode['0'])) ? explode("-", $langcode['0']) : $langcode;
        return $langcode['0'];
      }  
      $auto_lang = auto_lang();
      if(in_array($auto_lang,$all_langs))
        $requied_lang=$auto_lang;
      else
        $requied_lang=$set['lang'];  
    }     
  }
  else
    $requied_lang='ru';   
}
require_once('inc/lang/'.$requied_lang.'/all.php');
require_once ('inc/class_ipinit.php');
$ipinit = new ipinit();
$ipl = $ipinit->ip;
$ip = long2ip($ipl);

mysql_query("DELETE FROM `wm_guests` WHERE `lastvisit` < '" . (time() - 300) . "'");

if($user_id)
  mysql_query("UPDATE `wm_users` SET `lastvisit` = '".time()."',`ua` = '".mysql_real_escape_string(htmlentities($agn, ENT_QUOTES, 'UTF-8'))."', `ip` = '".mysql_real_escape_string($ip)."' WHERE `id` = '".$user_id."'");
else
{
  $sid = md5($ipl . $agn);
  $req333 = mysql_query("SELECT * FROM `wm_guests` WHERE `session` = '".$sid."' LIMIT 1");
  if(mysql_num_rows($req333))
    mysql_query("UPDATE `wm_guests` SET `lastvisit` = '".time()."', `ip` = '".mysql_real_escape_string($ip)."', `user_agent` = '".mysql_real_escape_string(htmlentities($agn, ENT_QUOTES, 'UTF-8'))."' WHERE `session` = '".$sid."'");
  else
    mysql_query("INSERT INTO `wm_guests` SET `session` = '".$sid."', `lastvisit` = '".time()."', `ip` = '".mysql_real_escape_string($ip)."', `user_agent` = '".mysql_real_escape_string(htmlentities($agn, ENT_QUOTES, 'UTF-8'))."';");
}

//защита от умеющих составлять веб-формы
if($_POST)
{
  $mmd_ref = getenv("HTTP_REFERER");
  if($mmd_ref)
  {
    $mmd_home = parse_url($set['url']);  
    $mmd_parse = parse_url($mmd_ref);  
    if($mmd_parse['host']!=$mmd_home['host'])
    {
      header('Location: '.$home.'/?do=404');  
      exit();  
    }
  }    
}

mysql_query("UPDATE `wm_users_ban` SET `type` = '2' WHERE `to_time` < '".time()."' AND `type` = '1'");
mysql_query("DELETE FROM `wm_users_inactive` WHERE  `time` < '" . (time()-(24*3600)) . "'");

//очистка системы
$last_clean = file_get_contents('files/cache/last_clean.dat');
if($last_clean < time()-(24*3600))
{
  function clear_dir($scan)
  {
    $dir = scandir($scan);
    foreach($dir as $file)
    {
      if($file!='.htaccess' || $file!='.' || $file!='..' || $file!='index.php')
      {
        if(filectime($scan.'/'.$file) > $last_clean+(24*3600))
          @unlink($scan.'/'.$file);  
      }
    }
  } 
  clear_dir('sourse/screens');  
  clear_dir('files/preview');
  file_put_contents('files/cache/last_clean.dat',time());
}
