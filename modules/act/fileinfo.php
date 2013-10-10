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


$f=abs(intval($_GET['file']));
if($f)
{
  $file1 = mysql_query('SELECT * FROM `wm_files` WHERE `id`="'.$f.'" LIMIT 1;');
  if(mysql_num_rows($file1))
  {
    require_once ('inc/head.php');
    $file = mysql_fetch_array($file1);
    echo '<h2>'.$lang['fileinfo_ab'].' "'.cut_filename($file['name']).'"</h2><hr /><br />';
    if (is_image($file['filename']))
    {
      echo '<img alt="'.$file['name'].'" src="'.$parent.'img.php?i='.$file['filename'].'" /><br/>';
      $size= getimagesize('sourse/files/'.$file['filename'].'.dat');
      echo $lang['pixels_w'].' '.$size[0].'x'.$size[1].' '.$lang['pixels'].'<br/>';
    }
    echo '<b>'.$lang['kode_to_p'].'</b><br/>&nbsp; <input class="edit2" type="text" size="20" value="{{'.(is_image($file['filename']) ? 'img' : 'file').':'.$file['filename'].'|'.$file['name'].'}}"/><br />';
    echo $lang['name_to_wiki'].' <b>'.cut_filename($file['filename']).'</b><br />';
    $ext = getextension($file['filename']); 
    echo $lang['extension'].' <b>'.$ext.'</b><br />';
    echo $lang['size_f'].' <b>'.func('get_file_size','sourse/files/'.$file['filename'].'.dat').'</b><br />';
    echo $lang['loadtime'].' <b>'.$file['view'].'</b> '.$lang['time_s'].'<br />';
    $wiki_page1 = mysql_query('SELECT * FROM `wm_page_lang` WHERE `pid`="'.$file['page'].'" AND `lang` = "'.$requied_lang.'" LIMIT 1;');
    if(mysql_num_rows($wiki_page1))
    {
       $wiki_page = mysql_fetch_array($wiki_page1); 
       echo $lang['add_to_page'].' <a style="color:green" href="'.($mod_rewrite ? 'wiki/'.prw($wiki_page['name']) : '?fid='.$wiki_page['link']).'">'.out($wiki_page['name']).'</a><br />'; 
    }
    else
    {
      $wiki_page1 = mysql_query('SELECT * FROM `wm_page_lang` WHERE `pid`="'.$file['page'].'" ORDER BY `id` LIMIT 1;');
      if(mysql_num_rows($wiki_page1))
      {
        $wiki_page = mysql_fetch_array($wiki_page1); 
        echo $lang['add_to_page'].' <a style="color:green" href="'.($mod_rewrite ? 'wiki/'.prw($wiki_page['name']) : '?fid='.$wiki_page['link']).'">'.out($wiki_page['name']).'</a><br />';   
      }  
    }
    echo '<a style="color:green" href="'.$parent.($mod_rewrite ? 'file'.$f.'/'.rawurlencode($file['name']) : '?do=down&amp;file='.$f).'"> <img width="16" height="16" src="'.(file_exists('sourse/ext/'.$ext.'.png') ? 'sourse/ext/'.$ext.'.png' : 'themes/admin/'.$set['theme'].'/sis.png').'" /> '.$lang['down_file'].'</a><br />';
    echo '</div><hr />';
    echo '<a href="./?do=files"><img src="themes/engine/'.$set['theme'].'/album.png" /> '.$lang['all_files'].'</a><br />'; 
    
    require_once ('inc/fin.php');
  }
  else
  {
    header ('Location: ./?do=404');
    exit();
  }  
}
else
{
  header ('Location: ./?do=404');
  exit();  
}

?>
