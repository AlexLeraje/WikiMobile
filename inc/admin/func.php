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

function admer($err)
{
	global $parent, $set, $lang;
	require_once ('inc/admin/head_info.php');
	echo '<b>'.$lang['error_imp'].' '.$err.'</b></td></table><center>';
	echo '<form style="display:inline" method="post" action="'.out(getenv("HTTP_REFERER")).'"><input type="submit" class="edit" value="'.$lang['back'].'" /></form>';
	echo '<form style="display:inline" method="post" action="?"><input type="submit" class="edit" value="'.$lang['content'].'" /></form></center>';
	require_once ('inc/admin/fin_info.php');
}

?>