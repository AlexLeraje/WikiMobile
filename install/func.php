<?php

class install
{
     private static $folders = array(
        '../files/',
        '../files/cache/',
        '../files/mod/',
        '../files/preview/',
        '../files/temp/',
        '../sourse/captcha/',
        '../sourse/files/',
        '../sourse/screens/',
        '../sourse/smiles/',
        '../inc/'
    );
    
    static function check_folders_rights()
    {
        $error = array();
        foreach (self::$folders as $val) if ((@decoct(@fileperms($val)) % 1000) < 777) $error[] = $val;
        return !empty($error) ? $error : false;
    }
    
    private static $files = array(
        '../index.php',
        '../index2.php',
    );
    
    static function check_files_rights()
    {
        $error = array();
        foreach (self::$files as $val) if ((@decoct(@fileperms($val)) % 1000) < 666) $error[] = $val;
        return !empty($error) ? $error : false;
    }
    
    static function check_php_errors()
    {
        global $lang;
        $error = array();
        if (version_compare(phpversion(), '5.1.0', '<')) $error[] = 'PHP ' . phpversion();
        if (!extension_loaded('mysql')) $error[] = $lang['no_mysql'] ;
        if (!extension_loaded('gd')) $error[] = $lang['no_gd'];
        if (!extension_loaded('zlib')) $error[] = $lang['no_zlib'];
        if (!extension_loaded('mbstring')) $error[] = $lang['no_mb'];
        if (ini_get('register_globals')) $error[] = 'register_globals <span style="color:red">ON</span>';
        return !empty($error) ? $error : false;
    }

    static function check_php_warnings()
    {
        global $lang;
        $error = array();
        if(function_exists('apache_get_modules'))
        {
          $apache_mod=@apache_get_modules();
          if (array_search('mod_rewrite', $apache_mod)) {}
          else $error[] = $lang['no_mod_r'];
        }
          else $error[] = $lang['no_mod_r'];
        if (ini_get('arg_separator.output') != '&amp;') $error[] = 'arg_separator.output <span style="color:red">'.ini_get('arg_separator.output').'</span>';
        return !empty($error) ? $error : false;
    }
    
    static function parse_sql($file = false)
    {
        $errors = array();
        if ($file && file_exists($file)) {
            $query = fread(fopen($file, 'r'), filesize($file));
            $query = trim($query);
            $query = preg_replace("/\n\#[^\n]*/", '', "\n" . $query);
            $buffer = array();
            $ret = array();
            $in_string = false;
            for ($i = 0; $i < strlen($query) - 1; $i++) {
                if ($query[$i] == ";" && !$in_string) {
                    $ret[] = substr($query, 0, $i);
                    $query = substr($query, $i + 1);
                    $i = 0;
                }
                if ($in_string && ($query[$i] == $in_string) && $buffer[1] != "\\") {
                    $in_string = false;
                } elseif (!$in_string && ($query[$i] == '"' || $query[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
                    $in_string = $query[$i];
                }
                if (isset($buffer[1])) {
                    $buffer[0] = $buffer[1];
                }
                $buffer[1] = $query[$i];
            }
            if (!empty($query)) {
                $ret[] = $query;
            }
            for ($i = 0; $i < count($ret); $i++) {
                $ret[$i] = trim($ret[$i]);
                if (!empty($ret[$i]) && $ret[$i] != "#") {
                    if (!mysql_query($ret[$i])) {
                        $errors[] = mysql_error();
                    }
                }
            }
        } else {
            $errors[] = 'ERROR: SQL file';
        }
        return $errors;
    }

    static function head()
    {
       global $parent, $theme, $all_langs, $requied_lang, $version, $stage, $all_stages,$err, $info_err, $lang; 
       echo '<?xml version="1.0" encoding="utf-8"?>';
       echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">';
       echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">';
       echo '<head><meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/>';
       echo '<title>';
       echo 'WikiMobile '.$version;
       echo '</title>'."\n";
       echo '<link rel="stylesheet" type="text/css" href="'.$parent.'themes/engine/'.$theme.'/style.css" />';
       echo '<link rel="stylesheet" type="text/css" href="'.$parent.'sourse/style/systemstyle.css" />';
       echo '<link rel="shortcut icon" href="'.$parent.'favicon.ico" />';
       echo '</head>';
       echo '<body>';
       echo '<div class="body">';
       echo '<div class="header">';
       echo '<table width="100%"><tr><td width="42px">';
       echo '<img src="'.$parent.'themes/engine/'.$theme.'/logo.png" />';
       echo '</td><td>';
       echo '<table cellpadding="0" cellspacing="0" width="100%"><tr><td>';  
       echo '[[WikiMobile '.$version.']]';
       echo '</td><td width="10px">';   
       echo '<form action="./"><input type="hidden" name="stage" value="'.$stage.'"/>';
       $l = 0;
       echo '<select class="langsp" name="l" onchange="this.form.submit()">';
       while($l < count($all_langs))
       {
         echo '<option '.($all_langs[$l]==$requied_lang ? 'selected="selected"' : '').' value="'.$all_langs[$l].'">'.$all_langs[$l].'</option>';
         $l++;  
       }
       echo '</select></form>';
       echo '</td></tr></table>';
       echo '<hr class="headhr" />';  
       echo '<small>'.$lang['step'].' '.$stage.' '.$lang['step2'].' '.$all_stages.'</small>';
       echo '</td></tr></table>';  
       echo '</div><hr/>';
       if($err)
         echo '<div class="error">'.$err.'</div>';
       if($info_err)
         echo '<div class="info">'.$info_err.'</div>';  
       echo '<div class="stat">';
     }
     
     static function fin()
     {
       global $microtime;  
       echo '
        <div class="down"><img src="../sourse/wiki_banner.png" /></div><table cellspaсing="0" cellpadding="0" style="width: 100%; border: 0px"><tr><td><a href="http://wikimobile.su"><small>&copy; WikiMobile</small></a></td>
        <td style="text-align:right;"><small>'.round(microtime(true) - $microtime, 4).' сек.</small></td></tr></table>
        </div></body>
        </html>';
     }

     static function rus_lat($str)
     {
       $tr = array(
        "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
        "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
        "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
        "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
        "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
        "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
        "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"," "=>"_"
       );
      return strtr($str,$tr);
     }

     function used_lang()
     {    
        $langcode = (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
        $langcode = (!empty($langcode)) ? explode(";", $langcode) : $langcode;
        $langcode = (!empty($langcode['0'])) ? explode(",", $langcode['0']) : $langcode;
        $langcode = (!empty($langcode['0'])) ? explode("-", $langcode['0']) : $langcode;
        return $langcode['0'];
     }
     
     static function set_lang()
     {
       global $all_langs;  
       $requied_lang = 'ru';  
       $nd_lang = (isset($_GET['l']) ? trim($_GET['l']) : '');
       
       //Автоопределение языка
       $det_lang = install::used_lang();
       if(in_array($det_lang,$all_langs))
        $requied_lang = $det_lang;   

       //Выбор языка
       if($nd_lang and in_array($nd_lang,$all_langs))
       {
         $requied_lang = $nd_lang;
         setcookie('inst_lang', $requied_lang);
         $_COOKIE['inst_lang']=$requied_lang;   
       }

       //Загрузка языка
       if($_COOKIE['inst_lang'])
       {
         $load_lang = $_COOKIE['inst_lang'];
         if(in_array($load_lang,$all_langs))
           $requied_lang = $load_lang; 
       }    
       return $requied_lang;  
     }

     
     
}