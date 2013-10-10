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

function tagspanel($msg='')
{
  global $parent, $lang;
  if (!$msg)
    $msg = 'msg';
  $out = '<script language="JavaScript" type="text/javascript">
   function tag(text1, text2) {
   if ((document.selection)) {
   document.mess.'.$msg.'.focus();
   document.mess.document.selection.createRange().text = text1+document.mess.document.selection.createRange().text+text2;
   } else if(document.forms[\'mess\'].elements[\''.$msg.'\'].selectionStart!=undefined) {
   var element = document.forms[\'mess\'].elements[\''.$msg.'\'];
   var len = document.mess.'.$msg.'.selectionStart;
   var str = element.value;
   var scroll =  document.mess.'.$msg.'.scrollTop; 
   var start = element.selectionStart;
   var length = element.selectionEnd - element.selectionStart;
   element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);
   var scroll2 = scroll + text1.length + text2.length + length;
   document.mess.'.$msg.'.scrollTop = scroll2;
   var len2 = text1.length + len + text2.length + length;
   
   document.mess.'.$msg.'.setSelectionRange(len2,len2);
   document.mess.'.$msg.'.focus();
   } else {document.mess.'.$msg.'.value += text1+text2;
   }
   
   }
</script>';
 $out .= '<div class="tagspanel">';   
 $out .= '<a href="javascript:tag(\'[http://\', \'|]\')"><img class="bar" src="'.$parent.'sourse/bb/l.png" alt="url" title="'.$lang['tag_link'].'" /></a>';
 $out .= '<a href="javascript:tag(\'[[\', \'|]]\')"><img class="bar" src="'.$parent.'sourse/bb/w.png" alt="b" title="'.$lang['tag_wikilink'].'"/></a>';
 $out .= '<a href="javascript:tag(\'**\', \'**\')"><img class="bar" src="'.$parent.'sourse/bb/b.png" alt="b" title="'.$lang['tag_bold'].'"/></a>';
 $out .= '<a href="javascript:tag(\'//\', \'//\')"><img class="bar" src="'.$parent.'sourse/bb/i.png" alt="i" title="'.$lang['tag_oblique'].'"/></a>';
 $out .= '<a href="javascript:tag(\'__\', \'__\')"><img class="bar" src="'.$parent.'sourse/bb/u.png" alt="u" title="'.$lang['tag_under'].'"/></a>';
 $out .= '<a href="javascript:tag(\'--\', \'--\')"><img class="bar" src="'.$parent.'sourse/bb/s.png" alt="s" title="'.$lang['tag_strike'].'"/></a>';
 $out .= '<a href="javascript:tag(\'&lt;code &gt;\', \'&lt;/code&gt;\')"><img class="bar" src="'.$parent.'sourse/bb/code.png" alt="i" title="'.$lang['tag_sourse'].'"/></a>';
 $out .= '<a href="javascript:tag(\'==\', \'==\')"><img class="bar" src="'.$parent.'sourse/bb/a1.png" alt="i" title="'.$lang['tag_head1'].'"/></a>';
 $out .= '<a href="javascript:tag(\'[color ]\', \'[/color]\')"><img class="bar" src="'.$parent.'sourse/bb/cl.png" alt="i" title="'.$lang['tag_color'].'"/></a>';
  $out .= '<a href="javascript:tag(\'&lt;!--\', \'--&gt;\')"><img class="bar" src="'.$parent.'sourse/bb/cm.png" alt="s" title="'.$lang['tag_comm'].'"/></a>';
 $out .= '</div>';
 return $out;   
}
?>
