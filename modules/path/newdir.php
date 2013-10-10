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

unlink('files/cache/count.dat');

if (can('add_dir'))
{
  require_once ('inc/head.php');
  echo '<h2>'.$lang['razd_adding'].'</h2><hr />';
  echo '</div><form action="?do=savedir&amp;id='. $path_p .'" method="post"><div class="stat">';
  echo '<b>'.$lang['dir_name'].' </b><br />';
  echo '<input type="text" size="20" name="dirname" class="edit2" /><hr />';
  echo '<b>'.$lang['name_for_ot'].' </b><br />';
  echo '<input type="text" size="20" name="name" class="edit2" /><hr /></div><div class="add">';
  echo '<input type="submit" value="'.$lang['create'].'" class="edit"  /><br />';
  echo '</div></form>';
  echo '<hr />';
  echo '<a href="?id='. $path_p .'"><img src="themes/engine/'.$set['theme'].'/up.png" /> '.$lang['back'].'</a><br />';
  require_once ('inc/fin.php');
}
else
{
	require_once ('inc/head.php');
	echo '<h2>'.$lang['razd_adding'].'</h2><br />';
	echo func('display_error',$lang['cn_cr_razd']);
	echo '<br /><a href="?id='. $path_p .'"><img src="themes/engine/'.$set['theme'].'/up.png" /> '.$lang['back'].'</a>';
	echo '</div><hr />';
	require_once ('inc/fin.php');
}
?>