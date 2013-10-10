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
if (can('add_dir'))
{
  $dirname = trim($_POST['dirname']);
  $name =  trim($_POST['name']);
  if (preg_match("/[^0-9a-z\-\_]+/",$dirname) or !$dirname)
  { 
     admer($lang['wr_nm_razd']);
  }
 elseif(!$name)
 {
    admer($lang['wr_dir_name']); 
 }
 else
 {
  if (!is_dir($path.'/'.$dirname))
  {
      mkdir($path.'/'.$dirname,0777);
      file_put_contents($path.'/'.$dirname.'/name.'.$requied_lang.'.dat',out($name));
      file_put_contents($path.'/'.$dirname.'/name.dat',out($name));
      require_once ('inc/admin/head_info.php');
      echo '<b>'.$lang['r_cr_1'].' "'.out($name).'" '.$lang['r_cr_2'].'</b>';
      echo '</td></table><center><form method="post" style="display: inline" action="?id='.$path_p.'"><input class="edit" type="submit" value="'.$lang['continue'].'" /></form></center>';
      require_once ('inc/admin/fin_info.php'); 
  }
  else
  {
    admer($lang['dir_al_exx']);
  }
 }
}
else
{
  header ('Location: ./?do=404');
}

?>