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

require_once ('inc/admin/func.php');

if (can('remote_dir', cut_dir2($path)) and $path_p)
{
  $action=$_GET['act'];
  if (file_exists($path.'/name.'.$requied_lang.'.dat'))
    $name = file_get_contents($path.'/name.'.$requied_lang.'.dat');
  elseif (file_exists($path.'/name.dat'))
    $name = file_get_contents($path.'/name.dat');
  else
    $name = out(cut_name($path_p));
  if (!$action)
  {
    require_once ('inc/admin/head_info.php');
    echo '<b>'.$lang['del_razd'].' "'.$name.'"?</b>';
    echo '<br />'.$lang['re_del_razd'].'</td></table><center>';
    echo '<center>';
    echo '<form action="?" style="display: inline"><input type="hidden" name="id" value="'.$path_p.'"><input type="hidden" name="do" value="deldir"><input type="hidden" name="act" value="delete"><input type="submit" value="'.$lang['delete_yes'].'" class="edit" /></form>';	
    echo '<form action="?" style="display: inline"><input type="hidden" name="id" value="'.$path_p.'"><input type="submit" value="'.$lang['cancel'].'" class="edit" /></form>';	
    echo '</center>';
    require_once ('inc/admin/fin_info.php');
  }
  elseif($action=='delete')
  {
    if (file_exists('files/cache/count.dat'))
      unlink('files/cache/count.dat'); 
	func('full_del_dir',$path);
	require_once ('inc/admin/head_info.php');
	echo '<b>'.$lang['rezd_ed'].'</b></td></table><center>';
	echo '<form style="display:inline" method="post" action="?id='. cut_dir($path_p) .'"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';
    require_once ('inc/admin/fin_info.php');
 }
}
else
{
 header ('Location: ./?do=404');
}
?>