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
setcookie('log', '');
setcookie('psw', '');
require_once ('inc/head.php');
echo '<h2>'.$lang['bye'].' '.$login.'!</h2>';
echo ''.$lang['thn_for_vis'].'<br /><br /><a href="?"><img src="themes/engine/'.$set['theme'].'/up.png" /> '.$lang['continue'].'</a>';
echo '</div><hr />';

unset($_SESSION['wiki_user']);
require_once ('inc/fin.php');
?> 