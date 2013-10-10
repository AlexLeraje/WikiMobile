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

$url_no = parse_url($_SERVER['REQUEST_URI']);
$sl_count = substr_count($url_no['path'],'/');
$t = 0;
while($t < $sl_count)
{
  $parent .= '../';
  $t++;  
}

require_once ('inc/head.php');
echo '<h2>'.$lang['error404'].'</h2><hr />';
echo ''.$lang['page_dnt_ex'].'<br />'.$lang['page_removed'];
echo '</div><hr />';
require_once ('inc/fin.php');

?>