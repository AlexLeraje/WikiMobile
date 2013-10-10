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

// И не надо ругать типо надо свич и бла-бла-бла
// Мне реально так удобнее =)

if($stage==1)
{
  install::head();
  echo '<form action="?stage=2" method="post">';
  echo '<h2>'.$lang['wiki_inst_p'].'</h2><hr/>';
  echo $lang['welc_inst'].' ('.$lang['wiki_vers'].' '.$version.')<br/>';
  echo $lang['wiki_site'].' <a class="list" href="http://wikimobile.su">http://wikimobile.su</a><br/>';
  echo $lang['wiki_use'].' <a class="list" href="http://annimon.com">http://annimon.com</a><hr/>';
  echo $lang['wiki_not'];
  echo '<br/>'.$lang['sys_test'];
  echo '</div>';
  echo '<div class="add"><input type="submit" class="edit" value="'.$lang['continue'].'" /></form></div><hr/>'; 
  install::fin();  
}
elseif($stage==2)
{
  $php_errors = install::check_php_errors();  
  $php_warnings = install::check_php_warnings();  
  $folders = install::check_folders_rights();  
  $files = install::check_files_rights();  
  if ($php_errors || $folders || $files)
    $err = $lang['crit_err'];
  elseif($php_warnings)
    $info_err = $lang['warn_err'];  
    
  install::head();
  echo '<h2>'.$lang['php_set'].'</h2><hr/>';
  if ($php_errors !== false)
  {
    echo '<span style="color:red">'.$lang['err_found'].'*</span><ul>';
    foreach ($php_errors as $val) echo '<li class="file">' . $val . '</li>';
    echo '</ul>';
  }
  else
    echo '&nbsp; <img src="../themes/engine/'.$theme.'/process.png" /> '.$lang['no_cr_err'];
  echo '<hr/>';
  if ($php_warnings !== false)
  {
    echo '<span style="color:red">'.$lang['fd_wr_err'].'</span><ul>';
    foreach ($php_warnings as $val) echo '<li class="file">' . $val . '</li>';
    echo '</ul>';
  }
  else
    echo '&nbsp; <img src="../themes/engine/'.$theme.'/process.png" /> '.$lang['no_f_err'];
    
  //////////////////////////////////
  echo '<h2>'.$lang['f_acc_rigts'].'</h2><hr/>';
  if ($folders !== false)
  {
    echo '<span style="color:red">'.$lang['f_need_rigts'].'*</span><ul>';
    foreach ($folders as $val) echo '<li class="file">' . $val . '</li>';
    echo '</ul>';
  }
  else
    echo '&nbsp; <img src="../themes/engine/'.$theme.'/process.png" /> '.$lang['f_gd_rigts'];  
  
  //////////////////////////////////
  echo '<h2>'.$lang['dr_acc_rigts'].'</h2><hr/>';
  if ($files !== false)
  {
    echo '<span style="color:red">'.$lang['dr_need_rigts'].'*</span><ul>';
    foreach ($files as $val) echo '<li class="file">' . $val . '</li>';
    echo '</ul>';
  }
  else
    echo '&nbsp; <img src="../themes/engine/'.$theme.'/process.png" /> '.$lang['dr_gd_rigts'];
  
  echo '</div>';
  if ($err)
    echo '<div class="add"><form action="?stage=2" method="post"><input type="submit" class="edit" value="'.$lang['renew'].'" /></form></div><hr/>';  
  else
    echo '<div class="add"><form action="?stage=3" method="post"><input type="submit" class="edit" value="'.$lang['continue'].'" /></form></div><hr/>';

  install::fin();  
}
elseif($stage==3)
{
  if(isset($_GET['act']))
  {
      $db_host=trim($_POST['db_host']);
      $db_user=trim($_POST['db_user']);
      $db_pass=trim($_POST['db_pass']);
      $db_name=trim($_POST['db_name']);  
      
      $connect = @ mysql_connect($db_host, $db_user, $db_pass);
      @ mysql_select_db($db_name);
      @ mysql_query("SET NAMES 'utf8'", $connect);
      
      $err = '';
      if(!$connect)
        $err .= $lang['data_err'];  
      else
      {
        // Создаем системный файл db.php
        $dbfile = "<?php\r\n\r\n" .
             'defined("MOBILE_WIKI") or die("Demon laughs");'."\r\n" .
             '$db_host = ' . "'$db_host';\r\n" .
             '$db_name = ' . "'$db_name';\r\n" .
             '$db_user = ' . "'$db_user';\r\n" .
             '$db_pass = ' . "'$db_pass';\r\n\r\n" .
          '?>';
        if (!file_put_contents('../inc/db.php', $dbfile))
          $err .= $lang['file_db_err'];
        else
        {
           $sql_err = install::parse_sql('./table.sql');
           if($sql_err)
             $err .= $lang['file_sql_err'];
           else
           {
             $url=$_SERVER['SCRIPT_NAME'];
             $pos=strpos($url,'/install/index.php');
             $url=substr($url,0,$pos).'/';
      
             mysql_query("INSERT INTO `wm_settings` VALUES ('site', '".mysql_real_escape_string('http://'.$_SERVER['HTTP_HOST'].'/')."');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('theme', 'freeze');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('admintheme', 'freeze');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('reg', 'regon');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('url', '".mysql_real_escape_string('http://'.$_SERVER['HTTP_HOST'].$url)."');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('search_on_page', '10');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('history_on_page', '10');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('disscusion_on_page', '5');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('symbols', '1000');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('mod', 'superuser');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('head', 'WikiMobile ".$version."');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('counter', '".mysql_real_escape_string('<img src="http://'.$_SERVER['HTTP_HOST'].$url.'sourse/wiki_banner.png" />')."');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('counter_index', '".mysql_real_escape_string('<img src="http://'.$_SERVER['HTTP_HOST'].$url.'sourse/wiki_banner.png" />')."');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('antiflood', '20');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('max_comm', '200');");
             mysql_query("INSERT INTO `wm_settings` VALUES ('lang', 'ru');");
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
              
             
             mysql_query("INSERT INTO `wm_smiles` VALUES (27, 17, 'sm', ':-(|:(', '27.gif');");
             mysql_query("INSERT INTO `wm_smiles` VALUES (19, 17, 'sm', ':*', '19.gif');");
             mysql_query("INSERT INTO `wm_smiles` VALUES (18, 17, 'sm', ':!', '18.gif');");
             mysql_query("INSERT INTO `wm_smiles` VALUES (17, 0, 'ct', 'Default', '');");
             mysql_query("INSERT INTO `wm_smiles` VALUES (26, 17, 'sm', ':)', '26.gif');");
             mysql_query("INSERT INTO `wm_smiles` VALUES (25, 17, 'sm', ':-)', '25.gif');");
             mysql_query("INSERT INTO `wm_smiles` VALUES (24, 17, 'sm', ':P|:-P', '24.gif');");
             mysql_query("INSERT INTO `wm_smiles` VALUES (23, 17, 'sm', ':?', '23.gif');");
             mysql_query("INSERT INTO `wm_smiles` VALUES (22, 17, 'sm', ':@', '22.gif');");
             mysql_query("INSERT INTO `wm_smiles` VALUES (21, 17, 'sm', ';)', '21.gif');"); 
             mysql_query("INSERT INTO `wm_smiles` VALUES (20, 17, 'sm', '8-)', '20.gif');"); 
             mysql_query("INSERT INTO `wm_smiles` VALUES (28, 17, 'sm', ':-D|:D', '28.gif');");
             
             mkdir('../data',0777);
             file_put_contents('../data/index.ru.txt',file_get_contents('./default/index.ru.txt'));
             file_put_contents('../data/index.en.txt',file_get_contents('./default/index.en.txt'));
             file_put_contents('../data/index.ua.txt',file_get_contents('./default/index.ua.txt'));
             
             file_put_contents('../files/cache/att_count.dat','1');
             file_put_contents('../files/cache/count.dat','1');
             file_put_contents('../files/cache/files_count.dat','5');
             file_put_contents('../files/cache/last_clean.dat','1332517594');
             file_put_contents('../files/cache/page_count.dat','1');
             
             mysql_query("INSERT INTO `wm_files` (`id`, `name`, `filename`, `page`, `att`, `time`, `view`) VALUES
               (1, 'wiki.png', 'wiki_2.png', 1, 0, 1333056403, 0),
               (2, 'syn.png', 'syn_3.png', 1, 0, 1333056403, 0),
               (3, 'prop.png', 'prop_4.png', 1, 0, 1333056403, 0),
               (4, 'pay.png', 'pay_5.png', 1, 0, 1333056403, 0);");
             
             file_put_contents('../sourse/files/pay_5.png.dat',file_get_contents('./files/pay_5.png.dat'));
             file_put_contents('../sourse/files/prop_4.png.dat',file_get_contents('./files/prop_4.png.dat'));
             file_put_contents('../sourse/files/syn_3.png.dat',file_get_contents('./files/syn_3.png.dat'));
             file_put_contents('../sourse/files/wiki_2.png.dat',file_get_contents('./files/wiki_2.png.dat'));
             
             file_put_contents('../files/rights.ini',
                                '[info]'."\n".
                                'create_stat=user;'."\n".
                                'edit_stat=superuser;'."\n".
                                'delete_message=moder;'."\n".
                                'delete_stat=admin;'."\n".
                                'add_dir=admin;'."\n".
                                'remote_dir=admin;'."\n".
                                'write_comments=guest;'."\n"
                              );
               
             header ('Location: ./?stage=4'); 
             exit();
          } 
        }
      }
  }  
    
  install::head();
  echo '<form action="?stage=3&amp;act=load" method="post">';
  echo '<h2>'.$lang['sql_param'].'</h2><hr/>';
  echo $lang['sql_server'].'<br/>';
  echo '<input type="text" name="db_host" class="edit2" value="localhost" /><br/>';
  echo $lang['sql_database'].'<br/>';
  echo '<input type="text" name="db_name" class="edit2" /><br/>';
  echo $lang['sql_user'] .'<br/>';
  echo '<input type="text" name="db_user" class="edit2" /><br/>';
  echo $lang['sql_pass'].'<br/>';
  echo '<input type="text" name="db_pass" class="edit2" /><br/>';
  echo '</div>';
  echo '<div class="add"><input type="submit" class="edit" value="'.$lang['continue'].'" /></form></div><hr/>';  
  install::fin();  
}
elseif($stage==4)
{
  require_once ('../inc/db.php');  
  $connect = @ mysql_connect($db_host, $db_user, $db_pass) or die('cannot connect to db');
  @ mysql_select_db($db_name) or die('cannot connect to db');
  @ mysql_query("SET NAMES 'utf8'", $connect);
  
    
  $action = isset($_GET['do']) ?  $_GET['do'] : '';
  $login = isset($_POST['login']) ? trim($_POST['login']) : '';
  $password= isset($_POST['password']) ? trim($_POST['password']) : '';
  $password_2= isset($_POST['password_2']) ? trim($_POST['password_2']) : '';
  $mail= isset($_POST['mail']) ?  trim($_POST['mail']) : '';
  
  $err = '';
  if ($action=='save_user')
  {
  if (!$login)
  $err .= $lang['no_login'].'<br />';
  else
  {
    if (mb_strlen($login)<4 or mb_strlen($login)>15)
    {
    $err .= $lang['iv_login'].'<br />';
    unset($login);
    }
    else
    {
      if (preg_match("/[^1-9a-z\-\@\*\(\)\?\!\~\_\=\[\]]+/", install::rus_lat(mb_strtolower($login))))
      {
      $err .= $lang['err_login'].'<br />';
      unset($login);
      }
      else
      {
        $req = mysql_query('SELECT * FROM `wm_users` WHERE `name`="' . mysql_real_escape_string($login) . '";');
        if (mysql_num_rows($req))
        {
          $err .= $lang['user_ex'].'<br/>';
          unset($login);
        }
      }
    }
  }
  if (!$password)
  $err .= $lang['no_pass'].'<br/>';
  else
  {
    if (strlen($password)<4 or strlen($password)>15)
    {
    $err .= $lang['iv_pass'].'<br/>';
    unset($password);
    }
    else
    {
      if (substr_count($password,"\\") or substr_count($password," ")!=0)
      {
        $err .= $lang['err_pass'].'<br/>';
        unset($password);
      }
      else
      {
        if ($password!=$password_2)
        {
        $err .= $lang['mc_pass'].'<br/>';
        unset($password);
        }
      } 
    }
  }
  if (!$mail)
  $err .= $lang['no_mail'].'<br/>';
  else
  {
    if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i",$mail) or substr_count($mail," ")!=0 or substr_count($mail,"\\")!=0)
    {
    $err .= $lang['in_mail'].'<br/>';
     unset($mail);
    }
  }
  
    if(!$err)
    {
      mysql_query("INSERT INTO `wm_users` SET
       `name`='" . mysql_real_escape_string($login) . "',
       `password`='" . md5(md5($password)) . "',
       `mail`='" . mysql_real_escape_string($mail). "',
       `time`='" .time() . "',
       `rights`='admin';");
      $postid = mysql_insert_id();
      mysql_query("INSERT INTO `wm_users_info` SET `userid`='" . $postid . "'");
      
      mysql_query("INSERT INTO `wm_pages` SET
              `path` = '".mysql_real_escape_string('data/index')."',
              `dir` = '".mysql_real_escape_string('data')."',
              `time` = '".time()."',
              `id_create` = '1',
              `user_name` = '".mysql_real_escape_string($login)."',
              `comments` = '1',
              `can_edit` = 'admin';");  
             
      mysql_query("INSERT INTO `wm_page_lang` SET
              `name` = '".mysql_real_escape_string('WikiMobile '.$version.'')."',
              `dir` = '".mysql_real_escape_string('data')."',
              `pid` = '1',
              `lang` = '".$requied_lang."';");
      
      unlink('../index.php');
      rename('../index2.php','../index.php');
      
      header ('Location: ../');
      exit();
    }
  }
  install::head();
  echo '<form action="?stage=4&do=save_user" method="post">';
  echo '<h2>'.$lang['adm_cr'].'</h2><hr/>';
  echo $lang['login_p'].'<br/>';
  echo '<input type="text" name="login" class="edit2" value="'.$login.'" /><br/>';
  echo $lang['mail_p'].'<br/>';
  echo '<input type="text" name="mail" class="edit2" value="'.$mail.'" /><br/>';
  echo $lang['pass_p'].'<br/>';
  echo '<input type="text" name="password" class="edit2" value="'.$password.'" /><br/>';
  echo $lang['rp_pass_p'].'<br/>';
  echo '<input type="text" name="password_2" class="edit2" value="'.$password.'" /><br/>';
  echo '</div><hr/>';
  echo '<div class="add"><input type="submit" class="edit" value="'.$lang['continue'].'" /></form></div><hr/>';
  install::fin();   
}