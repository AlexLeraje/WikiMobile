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

echo  '<a class="url" href="./">'.$lang['content'].'</a><br />';
if ($set['site'])
  echo  '<a class="url" href="'.$set['site'].'">'.$lang['index_page'].'</a><br />';
echo '<hr />
<center><a href="http://wikimobile.su"><small>© WikiMobile</small></a></center>
</body>
</html>';
?> 