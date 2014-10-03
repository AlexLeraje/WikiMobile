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
$microtime = microtime(1);
$parent = '';
require_once ('inc/core.php');
require_once ('inc/fnc.php');
$mod_rewrite=rewrite_on();
$act= $_GET['do'];
$id=abs(intval($_GET['uid']));
$name_wiki = str_replace('_',' ',trim($_GET['wiki']));
$path=cut_up($_GET['id']);

if($_SESSION['preview'] and file_exists('files/preview/'.$_SESSION['preview'].'.ini') and $act!='preview' and $act!='edit' and $act!='img' and $_SESSION['whatedit'] and !$_SESSION['page_create'])
{
  if($_GET['wiki']) $parent='../';
  require_once ('inc/admin/head_info.php');
  echo '<b>'.$lang['info'].'</b>';
  echo '<br />'.$lang['pred_info'].'</td></table><center>';
  echo '<form action="'.$parent.'?do=edit'.(($_SESSION['langedit'] and in_array($_SESSION['langedit'],$all_langs)) ? '&amp;lang='.$_SESSION['langedit'] : '' ).'&amp;uid='.$_SESSION['whatedit'].'&amp;edit='.$_SESSION['preview'].'" style="display: inline" method="post"><input type="submit" value="'.$lang['editing'].'" class="edit" /></form>';    
  echo '<form action="'.$parent.'unsetedit.php" style="display: inline" method="post"><input type="hidden" name="u" value="'.encrypt($_SERVER['REQUEST_URI']).'"/><input type="submit" value="'.$lang['cancel'].'" class="edit" /></form>';    
  echo '</center>';
  require_once ('inc/admin/fin_info.php');
  exit(); 
}

if($_SESSION['preview'] and file_exists('files/preview/'.$_SESSION['preview'].'.ini') and $act!='newprev' and $act!='wikicreate' and $act!='img' and $_SESSION['page_create'])
{
  if($_GET['wiki']) $parent='../';
  require_once ('inc/admin/head_info.php');
  echo '<b>'.$lang['info'].'</b>';
  echo '<br />'.$lang['pred_info'].'</td></table><center>';
  echo '<form action="'.$parent.'?do=wikicreate'.(($_SESSION['langedit'] and in_array($_SESSION['langedit'],$all_langs)) ? '&amp;lang='.$_SESSION['langedit'] : '' ).'&amp;id='.$_SESSION['whatedit'].'" style="display: inline" method="post"><input type="submit" value="'.$lang['editing'].'" class="edit" /></form>';    
  echo '<form action="'.$parent.'unsetcreate.php" style="display: inline" method="post"><input type="hidden" name="u" value="'.encrypt($_SERVER['REQUEST_URI']).'"/><input type="submit" value="'.$lang['cancel'].'" class="edit" /></form>';    
  echo '</center>';
  require_once ('inc/admin/fin_info.php');
  exit(); 
}
if ($name_wiki and mb_strlen($name_wiki) <= 200)
{
  $req1= mysql_query("SELECT * FROM `wm_pages` LEFT JOIN `wm_page_lang` ON `wm_pages`.`id`=`wm_page_lang`.`pid`  WHERE `wm_page_lang`.`name` = '".mysql_real_escape_string($name_wiki)."' AND `wm_page_lang`.`pid` IS NOT NULL LIMIT 1;");
  if (mysql_num_rows($req1))
  {
    $stat_lang = $_GET['lang'];
    if(!in_array($stat_lang,$all_langs))
      unset($stat_lang);
    if(!$stat_lang or $stat_lang==$requied_lang)
      $stat_lang = $requied_lang;    
    else
    {  
      $use_not_st_lang =1;
    }
      
    $wikipage = mysql_fetch_array($req1);
    $id = $wikipage['pid'];
    $wikipage['id'] = $wikipage['pid'];
    $parent='../';
  	if(intval(abs($_GET['p'])))
      $parent=$parent.'../';
    if($_GET['lang'])
      $parent=$parent.'../';
    require_once('inc/lang/'.$requied_lang.'/id.view.php');
  	require_once ('modules/id/view.php');
  }
  else
    header ('Location: ../?do=search&item='.urlencode($name_wiki));
}
elseif($id) // папка "modules/id" 
{
    $stat_lang = $_GET['lang'];
    if(!in_array($stat_lang,$all_langs))
      unset($stat_lang);
    if(!$stat_lang or $stat_lang==$requied_lang)
      $stat_lang = $requied_lang;    
    else
    {
      $use_not_st_lang =1;
      $curr_name= mysql_query("SELECT `name` FROM `wm_page_lang` WHERE `lang`='".$requied_lang."' AND `pid` = '".$id."' LIMIT 1;");
      $curr_name = mysql_fetch_array($curr_name);
      $curr_name = $curr_name['name'];
    }  
    
  $wikipage1 = mysql_query("SELECT * FROM `wm_pages` LEFT JOIN `wm_page_lang` ON `wm_pages`.`id`=`wm_page_lang`.`pid` AND `wm_page_lang`.`lang` = '".$stat_lang."' WHERE `wm_page_lang`.`pid` IS NOT NULL AND `wm_pages`.`id` = '".$id."' LIMIT 1;");
  if(!mysql_num_rows($wikipage1))
    $wikipage1= mysql_query("SELECT * FROM `wm_pages` LEFT JOIN `wm_page_lang` ON `wm_pages`.`id`=`wm_page_lang`.`pid` WHERE `wm_page_lang`.`pid` IS NOT NULL AND `wm_pages`.`id` = '".$id."' ORDER BY `wm_page_lang`.`id` LIMIT 1;");  
  if(mysql_num_rows($wikipage1))
  {       
    $wikipage = mysql_fetch_array($wikipage1);
    $wikipage['id'] = $wikipage['pid'];
     
    $array = array('discusion', 'history', 'edit', 'preview', 'editold', 'restole', 'viewold','delst', 'clearhistory','olang','move');
    if (in_array($act, $array))
    {
       require_once('inc/lang/'.$requied_lang.'/id.'.$act.'.php');
       require_once ('modules/id/'.$act . '.php'); 
    }
    else
    {
      require_once('inc/lang/'.$requied_lang.'/id.view.php');  
      require_once ('modules/id/view.php'); 
    }
        
  }
  else
    header ('Location: ./?err=2');
}
elseif(isset($_GET['id'])) // папка "modules/path"
{
  $path_p=$path;
  $path=add_data($path);
  $array = array('deldir','erazd','newdir','savedir','wikicreate','newprev');
  $hist=$path;
  if (in_array($act, $array) and is_dir($path))
  {
    require_once('inc/lang/'.$requied_lang.'/path.'.$act.'.php');  
    require_once ('modules/path/'.$act . '.php');
  }
  elseif(is_dir($path))
  {
    require_once('inc/lang/'.$requied_lang.'/act.sod.php');  
    require_once ('modules/act/sod.php');  
  }
  else
  {
    header('Location: ./?err=1');
    exit();
  }    
}
elseif($act)  // папка "modules/act"
{
   $array = array('mod','img','fileinfo','down','exit','404','sod','login','register','adm','config','search','late'  ,'getmod','modp','files','user','useredit','ban','unsetban','banhistory', 'online', 'gonline', 'activate', 'delfiles','smiles','setlang','newdisk');
   if(in_array($act, $array))
   {
     require_once('inc/lang/'.$requied_lang.'/act.'.$act.'.php');  
     require_once ('modules/act/'.$act.'.php');
   }
   else
   {
     header('Location: ./');
   } 
}
else
{

   $err=abs(intval($_GET['err']));
   if ($err==1)
     $err=$lang['sec_dnt_ex'];
   elseif ($err==2)
     $err=$lang['page_dnt_ex'];
   else unset($err);
   if($set['show_sod'])
   {
     $index_page = 1;  
     $stat_lang = $requied_lang;    
     require_once('inc/lang/'.$requied_lang.'/act.sod.php');
     require_once ('modules/act/sod.php');  
   }
   else
   {
     $wikipage1 = mysql_query("SELECT * FROM `wm_pages` LEFT JOIN `wm_page_lang` ON `wm_pages`.`id`=`wm_page_lang`.`pid` AND `wm_page_lang`.`lang` = '".$requied_lang."' WHERE `wm_page_lang`.`pid` IS NOT NULL AND `wm_pages`.`id` = '1' LIMIT 1;");
     if(!mysql_num_rows($wikipage1))
       $wikipage1= mysql_query("SELECT * FROM `wm_pages` LEFT JOIN `wm_page_lang` ON `wm_pages`.`id`=`wm_page_lang`.`pid` WHERE `wm_pages`.`id` = '1' ORDER BY `wm_page_lang`.`id` LIMIT 1;");
     $wikipage = mysql_fetch_array($wikipage1);
     $index_page = 1;
     $id = $wikipage['pid'];  
     $parent='./';
     
     $stat_lang = $requied_lang;
     $name_wiki = $wikipage['name'];    
     require_once('inc/lang/'.$requied_lang.'/id.view.php');
     require_once ('modules/id/view.php');
   } 
}
?>