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

require_once ('inc/admin/head_info.php');
echo '<b>'.$lang['ol_page'].' "'.out($wikipage['name']).'" '.$lang['ol_page_all_to'].'</b></td></table><hr/>';
      $i=0;
      while($all_langs[$i])
      {
        if(file_exists($wikipage['path'].'.'.$all_langs[$i].'.txt'))
          echo '&nbsp;&nbsp; <a href="'.$parent.($mod_rewrite ? 'wiki/'.prw($wikipage['name']).'/'.$all_langs[$i] : '?uid='.$id.'&amp;lang='.$all_langs[$i]).'"><img src="'.$parent.'sourse/lang/'.$all_langs[$i].'.png" /> '.file_get_contents('inc/lang/'.$all_langs[$i].'/lang.dat').'</a><br/>';  
        $i++;  
      }
echo '<hr/><center><form style="display:inline" method="post" action="'.($mod_rewrite ? 'wiki/'.prw(($curr_name ? $curr_name : $wikipage['name'])).($use_not_st_lang ? '/'.$stat_lang : '') : '?uid='. $id.($use_not_st_lang ? '&amp;lang='.$stat_lang : '')).'"><input type="submit" class="edit" value="'.$lang['back'].'" /></a></center>';
require_once ('inc/admin/fin_info.php');
?>
