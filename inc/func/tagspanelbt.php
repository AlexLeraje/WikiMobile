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

function tagspanelbt()
{
  global $parent;
 $out .= '<div class="tagspanelbt">';   
 $out .= '<a href="javascript:tag(\'{{file:\', \'|}}\')"><img class="bar" src="'.$parent.'sourse/bb/file.png" alt="url" title="'.$lang['tag_file'].'" /></a>';
 $out .= '<a href="javascript:tag(\'{{img:\', \'|}}\')"><img class="bar" src="'.$parent.'sourse/bb/img.png" alt="b" title="'.$lang['tag_image'].'"/></a>';
 $out .= '<a href="javascript:tag(\'\n * \', \'\')"><img class="bar" src="'.$parent.'sourse/bb/z.png" alt="b" title="'.$lang['tag_elem'].'"/></a>';
 $out .= '<a href="javascript:tag(\'\', \'\n{|\n|\n|\n|-\n|\n|\n|}\')"><img class="bar" src="'.$parent.'sourse/bb/tb.png" alt="i" title="'.$lang['tag_table'].'"/></a>';
 $out .= '<a href="javascript:tag(\'[nowiki]\', \'[/nowiki]\')"><img class="bar" src="'.$parent.'sourse/bb/nw.png" alt="u" title="'.$lang['tag_nowiki'].'"/></a>';
 $out .= '<a href="javascript:tag(\'&lt;left&gt;\', \'&lt;/left&gt;\')"><img class="bar" src="'.$parent.'sourse/bb/left.png" alt="i" title="'.$lang['tag_left'].'"/></a>';
 $out .= '<a href="javascript:tag(\'&lt;center&gt;\', \'&lt;/center&gt;\')"><img class="bar" src="'.$parent.'sourse/bb/cn.png" alt="i" title="'.$lang['tag_center'].'"/></a>';
 $out .= '<a href="javascript:tag(\'&lt;right&gt;\', \'&lt;/right&gt;\')"><img class="bar" src="'.$parent.'sourse/bb/rig.png" alt="i" title="'.$lang['tag_right'].'"/></a>';
 $out .= '<a href="javascript:tag(\'\', \'\n&lt;br/&gt;\')"><img class="bar" src="'.$parent.'sourse/bb/br.png" alt="red" title="'.$lang['tag_enter'].'"/></a>';
 $out .= '<a href="javascript:tag(\'\', \'\n-----\n\')"><img class="bar" src="'.$parent.'sourse/bb/hr.png" alt="green" title="'.$lang['tag_line'].'"/></a>';
 $out .= '</div>';
 return $out;   
}
?>
