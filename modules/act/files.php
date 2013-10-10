<?php
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
echo '<h2>'.$lang['all_files'].'</h2>';
$total = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_files`"), 0);
if($total > 0)
{
  $colpages=10;
  $nat = abs(intval($_GET['p']));
  if (!$nat) $nat=1;
  $tr=($nat-1)*$colpages;
  $fil1= mysql_query("SELECT * FROM `wm_files` ORDER BY `time` DESC LIMIT $tr,$colpages");
  while ($fil = mysql_fetch_assoc($fil1))
  {
    $ext = getextension($fil['filename']);
    $req = mysql_query('SELECT * FROM `wm_page_lang` WHERE `pid`="'.$fil['page'].'" AND `lang` = "'.$requied_lang.'" LIMIT 1;');
    if(mysql_num_rows($req))
    {
      $res = mysql_fetch_array($req);   
      $lk = '<a style="color:green" href="'.($mod_rewrite ? 'wiki/'.prw($res['name']) : '?fid='.$res['link']).'">'.out($res['name']).'</a>';  
    }
    else
    {
      $req = mysql_query('SELECT * FROM `wm_page_lang` WHERE `pid`="'.$fil['page'].'"  ORDER BY `id` LIMIT 1;');
      if(mysql_num_rows($req))
      {
        $res = mysql_fetch_array($req);  
        $lk = '<a style="color:green" href="'.($mod_rewrite ? 'wiki/'.prw($res['name']) : '?fid='.$res['link']).'">'.out($res['name']).'</a>';  
      }
      else
      {  
        if($fil['att'])
        {
          $req2 = mysql_query('SELECT * FROM `wm_mod` WHERE `att`="'.$fil['att'].'" LIMIT 1;');
          if(mysql_num_rows($req2))
          {
            $res2 = mysql_fetch_array($req2);
            $lk = (($rights=='admin' or $rights=='moder') ? '<a style="color:green" href="./?do=getmod&amp;mod='.$res2['id'].'">' : '').''.out($res2['name']).(($rights=='admin' or $rights=='moder') ? '</a>' : '').' '.$lang['on_moder_f'].'';  
          }
          else
            $lk = $lang['file_no'];
        }
        else
          $lk = $lang['file_no'];
      }  
    }
    echo '<hr/><a href="./?do=fileinfo&amp;file='.$fil['id'].'"><img width="16" height="16" src="'.(file_exists('sourse/ext/'.$ext.'.png') ? 'sourse/ext/'.$ext.'.png' : 'themes/admin/'.$set['theme'].'/sis.png').'" /> '.cut_filename($fil['name']).'</a> ('.func('get_file_size','sourse/files/'.$fil['filename'].'.dat').')<br/>';
    echo '<small><span class="gray">'.$lang['add_to_page2'].'</span>'.$lk.'<br/><span class="gray">'.$lang['file_f'].' </span>'.cut_filename($fil['filename']).'</small>';
  }
  $vpage=vpage($total,'?do=files&amp;',$colpages);
  if ($vpage)
    {
      echo '<hr/>'.$vpage;
    }
  echo '</div>';
  if($rights=='admin')
  {
     echo '<div class="add">';
     echo '<form style="display:inline" action="./?do=delfiles" method="post"><input type="submit" value="'.$lang['del_all_f'].'" class="edit" /></form>'; 
     echo '</div>';  
  }  
}
else
{
  echo '<hr/>'.$lang['no_files'].'</div>';  
}
echo '<hr />';
require_once ('inc/fin.php'); 
?>
