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

//взято из johncms 4.3.0 Спасибо Alkatraz'у (johncms.com) за ценную статью :)
if ((stristr($agn, "msie") && stristr($agn, "windows")) or stristr($agn, "chrome"))
{
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header('Content-type: text/html; charset=UTF-8'); 
}
else
{
    header("Cache-Control: public");
    header('Content-type: text/html; charset=UTF-8');
}

echo '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" dir="ltr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>WikiMobile | Админпанель</title>
<link rel="stylesheet" type="text/css" href="'.$parent.'themes/admin/'.$set['admintheme'].'/style.css" />
<link rel="shortcut icon" href="favicon.ico" />
<body>
<div class="logo"><img src="'.$parent.'themes/admin/'.$set['admintheme'].'/logo.png" /></div>
<hr/>';

?>