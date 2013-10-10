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

ob_start();

//взято из johncms 4.3.0 Спасибо Alkatraz'у (johncms.com) за ценную статью :)
if ((stristr($agn, "msie") && stristr($agn, "windows")) or stristr($agn, "chrome"))
{
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header('Content-type: text/html; charset=UTF-8'); 
}
else
{
    header("Cache-Control: public");
    header('Content-type: text/html; charset=UTF-8');
}
header("Expires: " . date("r",  $realtime + 60));
echo '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head><meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/>
  <title>';
  if ($wikipage['name'])
    echo out($wikipage['name']);
  else
    echo 'WikiMobile';
echo '</title>'."\n";
if($set['key_words'])
  echo '<meta name="keywords" content="'.($wikipage['name'] ? str_replace(' ',', ',out($wikipage['name'])) : '').($set['key_words'] ? ', ' : '').$set['key_words'].'" />'."\n";
if($set['site_descr'])
  echo '<meta name="description" content="'.$set['site_descr'].'" />'."\n";
echo '<link rel="stylesheet" type="text/css" href="'.$parent.'themes/engine/'.$set['theme'].'/style.css" />
<link rel="stylesheet" type="text/css" href="'.$parent.'sourse/style/systemstyle.css" />
<link rel="shortcut icon" href="'.$parent.'favicon.ico" />
<link rel="apple-touch-icon" href="'.$parent.'apple-touch-icon.png" />
</head>
<body>
<div class="body">';
  echo '<div class="header">';
  echo '<table width="100%"><tr><td width="42px">';
  echo '<a href="'.$parent.'?"><img src="'.$parent.'themes/engine/'.$set['theme'].'/logo.png" /></a>';
  echo '</td><td>';
  
  echo '<table cellpadding="0" cellspacing="0" width="100%"><tr><td>';  
    if ($wikipage['name'] or $curr_name) echo '[['.out($curr_name ? $curr_name : $wikipage['name']).']]';
  else
    echo $set['head'];
  echo '</td><td width="10px">';   
  
  echo '<form action="'.$parent.'"><input type="hidden" name="do" value="setlang"/>';
  $l = 0;
  echo '<select class="langsp" name="l" onchange="this.form.submit()">';
  while($l < count($all_langs))
  {
    echo '<option '.($all_langs[$l]==$requied_lang ? 'selected="selected"' : '').' value="'.$all_langs[$l].'">'.$all_langs[$l].'</option>';
    $l++;  
  }
  echo '</select></form>';
  echo '</td></tr></table>';
  echo '<hr class="headhr" />';  
  echo '<form action="'.$parent.'?" >';
  echo '<input type="hidden" name="do" value="search" />';
  echo '<input type="text" name="item" placeholder="'.$lang['search_s'].'" value=""  size="10" class="hedit2" />';
  echo '<input type="submit" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" class="hedit" title="'.$lang['search_s'].'" />';
  echo '</form>';
  echo '</td></tr></table>';  
  echo '</div>';

//Вывод рекламы
$l_top1= mysql_query("SELECT * FROM `wm_ads` WHERE `type` = '1' ;");
if (mysql_num_rows($l_top1))
{
  while($l_top = mysql_fetch_assoc($l_top1))
  {  
  	 if(($l_top['time']+($l_top['view']*3600*24)) > time())
      $out_reklam .= '<a href="'.$l_top['link'].'" style="'.$l_top['style'].'">'.$l_top['name'].'</a><br />';
  }
  if($out_reklam)
  {
    echo '<div class="reklam">';    
    echo $out_reklam;  
    echo '</div>';    
  }
}
 
//менюшка юзера
echo '<div class="headelem">';
if ($username)
  echo '<b><a href="'.$parent.'?do=user&amp;us='.$user_id.'">'.$username.'</a></b> ('.($rights=='admin' ? '<a style="color:red;" href="'.$parent.'?do=adm">'.$lang['adminpanel'].'</a> | ' : '').'<a href="'.$parent.'?do=exit">'.$lang['exit'].'</a>)';
elseif($set['reg']=='regon') echo '<a href="'.$parent.'?do=login"><img src="'.$parent.'themes/engine/'.$set['theme'].'/login.png" /> '.$lang['login'].'</a> <a href="'.$parent.'?do=register"><img src="'.$parent.'themes/engine/'.$set['theme'].'/register.png" /> '.$lang['register'].'</a>';
echo '</div>';

//вывод ошибок и варнингов
if ($mess_err) echo func('display_error',$mess_err);  
if ($err) echo func('display_error',$err);
if ($info_mess) echo func('display_info',$info_mess);

//Вывод ссылки на статьи с премодерацией (для модеров и админов)
if (($rights=='admin' or $rights=='moder') and $_GET['do']!='modp' and $_GET['do']!='getmod')
{
  $page_mod = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_mod`;"), 0);
  if ($page_mod)
    echo '<div class="warning"><a href="'.$parent.'?do=modp">'.$lang['moderation'].' ('.$page_mod.')</a></div>';
}

//Ссылка на бан
$bnt1= mysql_query("SELECT * FROM `wm_users_ban` WHERE `user_id` = '".$user_id."' AND `type` = '1' LIMIT 1;");
if(mysql_num_rows($bnt1))
{
  $bnt = mysql_fetch_array($bnt1);
  echo func('display_error','<a href="./?do=banhistory&amp;us='.$user_id.'">'.$lang['banned_to'].' '.date("d/m/y  H:i",$bnt['to_time'].'</a>'));  
}
//Тело документа
echo '<div class="stat">';
?>