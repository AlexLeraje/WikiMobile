<?php
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
require('func.php');
Error_Reporting(E_ALL & ~ E_NOTICE);
$parent = '../';
$theme = 'freeze';
$all_langs = array('ru', 'en', 'ua');
$version = '1.3';
$all_stages = 4;
$stage = (isset($_GET['stage']) ? intval(abs($_GET['stage'])) : '1');

//Подключаем языковой файл
$requied_lang = install::set_lang();
if(file_exists('lang/'.$requied_lang.'.php'))
  require('lang/'.$requied_lang.'.php');
else
  exit('Can not load lang file!');

//подключаемся к базе
require_once ('../inc/db.php');  
$connect = @ mysql_connect($db_host, $db_user, $db_pass) or die('cannot connect to db');
@ mysql_select_db($db_name) or die('cannot connect to db');
@ mysql_query("SET NAMES 'utf8'", $connect);
  
if($stage==1)
{
  install::head();
  echo '<form action="?stage=2" method="post">';
  echo '<h2>Обновление Wikimobile:</h2><hr/>';
  echo '1) <span style="color:red"><b>Для начала процедуры обновления обязательно сделайте бэкап бызы данных и всей директории в которой расположен WikiMobile!</b></span><hr/>';
  echo '2) Удалите все файлы и папки в директории с WikiMobile <b>кроме</b> следующих файлов и папок:<ul>';
   echo '<li class="file">data</li>';
   echo '<li class="file">files</li>';
   echo '<li class="file">sourse/files</li>';
   echo '<li class="file">sourse/smiles</li>';
   echo '<li class="file">inc/db.php</li>';
  echo '</ul>';
  echo '<hr/>';
  echo '3) Скопируйте файлы и папки из дистрибутива с обновлением в папку с WikiMobile<hr/>';
  echo '4) Нажмите "продолжить" после того как выполнили первые три пункта<br/>';
  echo '</div>';
  echo '<div class="add"><input type="submit" class="edit" value="Продолжить" /></form></div><hr/>'; 
  install::fin();
}
else
{
  $req = mysql_query("SELECT * FROM `wm_settings`");
  while ($res = mysql_fetch_row($req)) $set[$res[0]] = $res[1];
  mysql_free_result($req);
   
  if($set['wiki_version'])
  {
    install::head();
    echo '<form action="../" method="post">';
    echo '<h2>Обновление Wikimobile:</h2><hr/>';
    echo 'Вы уже обновили WikiMobile';
    echo '</div>';
    echo '<div class="add"><input type="submit" class="edit" value="Назад" /></form></div><hr/>'; 
    install::fin();
    exit();  
  }
   
  mysql_query('ALTER TABLE  `wm_history` ADD  `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
  mysql_query('TRUNCATE `wm_history`');  
    
  mysql_query('ALTER TABLE  `wm_mod` ADD  `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
  mysql_query("UPDATE `wm_mod` SET `lang` = '".$set['lang']."'");
  
  mysql_query('ALTER TABLE  `wm_users` ADD  `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
  
  mysql_query('CREATE TABLE IF NOT EXISTS `wm_page_comm` (
    `page` int(11) NOT NULL,
    `userid` int(11) NOT NULL,
    `time` int(11) NOT NULL,
    PRIMARY KEY (`page`,`userid`),
    KEY `time` (`time`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
    
  mysql_query('CREATE TABLE IF NOT EXISTS `wm_page_lang` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `pid` int(11) NOT NULL,
    `name` varchar(200) NOT NULL,
    `lang` varchar(3) NOT NULL,
    `dir` text NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;');
    
  mysql_query('CREATE TABLE IF NOT EXISTS `wm_page_view` (
    `page` int(11) NOT NULL,
    `userid` int(11) NOT NULL,
    `time` int(11) NOT NULL,
    PRIMARY KEY (`page`,`userid`),
    KEY `time` (`time`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
  
  mysql_query("INSERT INTO `wm_settings` VALUES ('inst_lang', 'a:3:{i:0;s:2:\"en\";i:1;s:2:\"ua\";i:2;s:2:\"ru\";}');"); 
  mysql_query("INSERT INTO `wm_settings` VALUES ('show_sod', '0');"); 
  mysql_query("INSERT INTO `wm_settings` VALUES ('sort_sod', '1');");
  mysql_query("INSERT INTO `wm_settings` VALUES ('obr_sort', '0');");
  mysql_query("INSERT INTO `wm_settings` VALUES ('key_words', '');");
  mysql_query("INSERT INTO `wm_settings` VALUES ('site_descr', '');");
  mysql_query("INSERT INTO `wm_settings` VALUES ('mail_reg', '0');");   
  mysql_query("INSERT INTO `wm_settings` VALUES ('update', '0');"); 
  mysql_query("INSERT INTO `wm_settings` VALUES ('wiki_version', '1.3');"); 
  mysql_query("INSERT INTO `wm_settings` VALUES ('max_file', '1000');");
  
  mysql_query('ALTER TABLE  `wm_pages` ADD  `lang_edit` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
  mysql_query('ALTER TABLE  `wm_pages` ADD  `comm_time` int (11) NOT NULL');
  
  
  
  $req = mysql_query("SELECT * FROM  `wm_pages`");
  while ($res = mysql_fetch_array($req))
  {
    $res['path'] = $parent.$res['path'];
    
    mysql_query("INSERT INTO `wm_page_lang` SET `pid` = '".$res['id']."', `name` = '".$res['name']."', `lang` = '".$set['lang']."', `dir` = '".$res['dir']."' ;");
    if(file_exists($res['path'].'.txt'))
    {          
        rename($res['path'].'.txt', $res['path'].'.'.$set['lang'].'.txt');
        if(file_exists($res['path'].'.temp.dat'))
          unlink($res['path'].'.temp.dat');
        if(file_exists($res['path'].'.hist_count.dat'))
        {
          $hist_numb = file_get_contents($res['path'].'.hist_count.dat');
          unlink($res['path'].'.hist_count.dat');
        }
        else
          $hist_numb = 1;
        for($i=1;$i <= $hist_numb; $i++)
        {
          if(file_exists($res['path'].'.arh.'.$i.'.dat'))
            unlink($res['path'].'.arh.'.$i.'.dat');         
        }
    }
     
  }
  mysql_free_result($req);
  
  mysql_query('ALTER TABLE `wm_pages` DROP `name`');
  
  $req = mysql_query("SELECT * FROM  `wm_files`");
  while ($res = mysql_fetch_array($req))
  {
    $res['filename'] = $parent.'sourse/files/'.$res['filename'];  
    if(file_exists($res['filename']))
    {         
      rename($res['filename'], $res['filename'].'.dat');  
    }   
  }
  mysql_free_result($req);
  
  $l = 0;
  while($l < count($all_langs))
  {
    if($all_langs[$l]!=$set['lang'])
      file_put_contents('../data/index.'.$all_langs[$l].'.txt',file_get_contents('./default/index.'.$all_langs[$l].'.txt'));
    $l++;  
  }
    
  install::head();
  echo '<form action="../" method="post">';
  echo '<h2>Обновление Wikimobile:</h2><hr/>';
  echo 'Обновление прошло успешно!';
  echo '</div>';
  echo '<div class="add"><input type="submit" class="edit" value="Продолжить" /></form></div><hr/>'; 
  install::fin();  
}  
