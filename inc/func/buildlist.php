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

function buildlist($path_to,$path='data',$elem=0)
{
  //////////////////////////////////////////////
  //построение списка файлов              //////
  //////////////////////////////////////////////
  $mod_rewrite=rewrite_on();
  global $lang, $requied_lang, $all_langs, $set;
  $part=explode('/',$path_to);

  $elem=$elem+1;
  $dir = opendir($path);
  while ($file = readdir($dir))
    { 
      if ((is_dir($path.'/'.$file)) and ($file !=".")&&($file !=".."))
      {
        $array[] = $file;
        $i++;
      }
    }
  closedir($dir);
  $count = $i;
    
  echo '<ul>';
  for ($c=0;$c <= $count-1;$c++)
  { 
        $path2=str_replace("/",":",$path);
     if (file_exists($path.'/'.$array[$c].'/name.'.$requied_lang.'.dat'))
       $name=file_get_contents($path.'/'.$array[$c].'/name.'.$requied_lang.'.dat');
     elseif (file_exists($path.'/'.$array[$c].'/name.dat'))
       $name=file_get_contents($path.'/'.$array[$c].'/name.dat');
     else $name=$array[$c];
     
     if ($array[$c]==$part[$elem])
     {
       $temp_path=cut_data($path2);
       echo '<li class="dir2"><a href="'.($temp_path ? '?id='.prd($temp_path) : './?do=sod').'">'.$name.'</a></li>';
       if (func('countfiles',$path.'/'.$array[$c]) > 0 )
         buildlist($path_to,$path.'/'.$array[$c],$elem);
       else
         echo '<ul><li class="file"><span class="empty">['.$lang['empty'].']</span></li></ul>'; 
     }
     else
       echo '<li class="dir"><a  href="?id='.prd(cut_data($path2.':'.$array[$c])).'">'.$name.'</a></li>';
  }
  $req_name = mysql_query("SELECT * FROM `wm_page_lang` WHERE `dir` = '".mysql_real_escape_string($path)."' ORDER BY `id` ASC;");
  while ($res_name = mysql_fetch_array($req_name))
  {
     if($res_name['lang']==$requied_lang) 
       $page_name[$res_name['pid']] = $res_name['name'];
     elseif(!$page_name[$res_name['pid']])
       $page_name[$res_name['pid']] = $res_name['name']; 
  }

  
  $w_langs = array_flip($all_langs);
  unset($w_langs[$requied_lang]);
  $w_langs = array_flip($w_langs);
  array_unshift($w_langs,$requied_lang);
  
  $id_page_show = array();
  
  if($set['sort_sod'])
    $sql_sort = '`wm_page_lang`.`name`';  //сортировка по названию
  else
    $sql_sort = '`wm_pages`.`time`';      //сортировка по дате добавления
  
  if($set['obr_sort'])    //ОБратная сортировка
    $obr_sort = 'DESC';
  else
    $obr_sort = 'ASC';
  
  foreach($w_langs as $value)
  {
    $req = mysql_query("SELECT `wm_pages`.`id`,`wm_page_lang`.`name`,`wm_pages`.`time` FROM `wm_pages` LEFT JOIN `wm_page_lang` ON `wm_pages`.`id`=`wm_page_lang`.`pid` AND `wm_page_lang`.`lang` = '".$value."' WHERE `wm_page_lang`.`pid` IS NOT NULL AND `wm_pages`.`dir` = '".mysql_real_escape_string($path)."' ORDER BY ".$sql_sort." ".$obr_sort.";");
    if(mysql_num_rows($req))
    {
      while ($res = mysql_fetch_array($req))
      {
        if(!in_array($res['id'],$id_page_show))
        {
          $res['name'] = $page_name[$res['id']];
          if ($mod_rewrite)
            echo '<li class="file"><a class="list" href="wiki/'.prw($res['name']).'">'.out($res['name']).'</a></li>';
          else
            echo '<li class="file"><a class="list" href="?uid='.$res['id'].'">'.out($res['name']).'</a></li>';  
      
          $id_page_show[] = $res['id'];
          $page_in_cur_lang++;
        }
      }
    }
  }
  
  if(!$c and !$page_in_cur_lang)
    echo '<ul><li class="file"><span class="empty">['.$lang['empty'].']</span></li></ul>';
  echo '</ul>'; 
  return true;    
}
?>