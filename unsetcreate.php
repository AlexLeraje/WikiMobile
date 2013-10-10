<?
////////////////////////////////////////////////////
//                 WikiMobile                     //
////////////////////////////////////////////////////
// Автор:              web_demon                  //
// Оф. Сайт:           http://wikimobile.su       //
// Oф. сайт поддержки: http://annimon.com         //
// E-mail:             web_demon@mail.ru          //
////////////////////////////////////////////////////

define('MOBILE_WIKI', 1);
require_once ('inc/core.php');
require_once ('inc/fnc.php');
if(can('edit_stat') and $_SESSION['preview'] and $_SESSION['page_create'])
{
   if (file_exists('files/preview/'.$_SESSION['preview'].'.ini'))
     unlink('files/preview/'.$_SESSION['preview'].'.ini');
   unset($_SESSION['preview']);
   unset($_SESSION['whatedit']);
   unset($_SESSION['langedit']);
   unset($_SESSION['page_create']);
   unset($_SESSION['att']);
   unset($_SESSION['pagename']);
  require_once ('inc/admin/head_info.php');
  echo '<b>'.$lang['fin_editing'].'</b>';
  echo '<br /></td></table><center>';
  $action = htmlentities(decrypt(trim($_POST['u'])), ENT_QUOTES, 'UTF-8');
  echo '<form action="'.($action ? $action : './').'" style="display: inline" method="post"><input type="submit" value="'.$lang['back'].'" class="edit" /></form>';     
  echo '</center>';
  require_once ('inc/admin/fin_info.php');
   
}
else
{
  header ('Location: ./?do=404');
} 
?>