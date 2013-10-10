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

$ln=$_GET['l'];
if(preg_match("/[^1-9a-z]+/",$ln))
{
  header('Location: ./?do=404');
  exit();  
}
if(!is_dir('inc/lang/'.$ln))
{
  header('Location: ./?do=404');
  exit();        
}
if($user_id)
{
  mysql_query("UPDATE `wm_users` SET `lang` = '".$ln."' WHERE `id` = '".$user_id."' LIMIT 1;");
  setcookie('lang', $ln);
  header('Location: ./');  
}
else
{
  setcookie('lang', $ln); 
  header('Location: ./');
}
?>
