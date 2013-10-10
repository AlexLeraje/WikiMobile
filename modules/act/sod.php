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
      require_once ('inc/head.php');
      echo '<h2>'.$lang['content_t'].'</h2><hr />';
      func('buildlist',$path);
      echo '</div>';
      if (can('create_stat')) echo '<div class="add"><form action="?"><input type="hidden" name="id" value="'.$path_p.'" /><input type="hidden" name="do" value="wikicreate" /><input type="submit" value="'.$lang['new_page'].'" class="edit" /></form></div>';    
      echo '<hr />';
      if (can('add_dir')) echo '<a href="?id='.$path_p.'&amp;do=newdir"><img src="themes/engine/'.$set['theme'].'/new.png" /> '.$lang['add_razd'].'</a><br />';
      if (can('remote_dir', cut_dir2($path)) and $path_p) echo '<a href="?id='.$path_p.'&amp;do=deldir"><img src="themes/engine/'.$set['theme'].'/del.png" /> '.$lang['del_razd'].'</a><br />';
      require_once ('inc/fin.php');
?>
