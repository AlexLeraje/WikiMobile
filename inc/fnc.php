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

require_once ('mod_rewrite.php');

$called_functions = array();

function func()
{
  $num_args = func_num_args();
  $args = array();
  $i=1;
  while($i < $num_args)
  {
    $args[] = func_get_arg($i);  
    $i++;  
  }
  $function = func_get_arg(0);
  global $called_functions;
  if(!$called_functions[$function])
  {
    require('func/'.$function.'.php');
    $called_functions[$function] = 1;
  }
  return call_user_func_array($function,$args);
}

function encrypt($string)  
{   
  $string = str_replace('=','*',base64_encode($string));
  return $string;
}  
   
function decrypt($string)  
{   
  $string = base64_decode(str_replace('*','=',$string));
  return $string;  
}

function cut_ext($string)
{
  //////////////////////////////////////////////
  //обрезание расширения                  //////
  //////////////////////////////////////////////
  $n=strrpos($string,".");
  $ext=substr($string,0,$n);
  return $ext;
}

function getextension($string)
{
  $n=strrpos($string,".");
  if($n)
  {
    $n=$n+1;
    $ext=mb_strtolower(substr($string,$n));
    return $ext;
  }
  else    
   return '';
}

function number_of_pages($directory)
{
  if (file_exists('files/cache/count.dat'))
  {
  	return file_get_contents('files/cache/count.dat');
  }
  else
  {
  	$count=mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_pages`"), 0);
  	file_put_contents('files/cache/count.dat',$count);
  	return $count;
  }
}

function cut_dir($string)
{
  /////////////////////////////////////////////////////////////
  //обрезает последний элемент вместе с двоеточием       //////
  /////////////////////////////////////////////////////////////
  if(strrpos($string,":"))
  {
    $n=strrpos($string,":");
    $ext=substr($string,0,$n);
    return $ext;
  }
  else
  {
    return '';
  }
}


function cut_dir2($string)
{
  /////////////////////////////////////////////////////////////
  //обрезает последний элемент вместе с двоеточием       //////
  /////////////////////////////////////////////////////////////
  if(strrpos($string,"/"))
  {
    $n=strrpos($string,"/");
    $ext=substr($string,0,$n);
    return $ext;
  }
  else
  {
    return '';
  }
}

function cut_name($string)
{
  //////////////////////////////////////////////
  //функция отделения крайней папки       //////
  //////////////////////////////////////////////
  if(strrpos($string,":"))
  {
    $n=strrpos($string,":");
    $n=$n+1;
    $ext=substr($string,$n);
    return $ext;
  }
  else
  {
	return $string;
  }
}


function cut_name2($string)
{
  //////////////////////////////////////////////
  //функция отделения крайней папки       //////
  //////////////////////////////////////////////
  if(strrpos($string,"/"))
  {
    $n=strrpos($string,"/");
    $n=$n+1;
    $ext=substr($string,$n);
    return $ext;
  }
  else
  {
	return $string;
  }
}


function cut_data($string)
{
  //////////////////////////////////////////////
  //обрезание папки дата вначале          //////
  //////////////////////////////////////////////
  if ($string=='data')
  {
  	return '';
  }
  else
  {
   return substr($string,5);
  }	
}

function cut_up($path)
{
  $path = str_replace('../','',$path);
  $path = str_replace('..:','',$path);
  if (preg_match("/[^0-9a-z\-\_\:]+/",$path))
  {
  	return '';
  }
  else
    return $path;
}

function add_data($path)
{
  //////////////////////////////////////////////
  //добавление папки дата вначале         //////
  //////////////////////////////////////////////
  if (!$path) $path='data';
  else $path='data/'.$path;
  $path=str_replace(':','/',$path);
  return $path;
}

function can($whatdo='',$path='')
{
   global $user_id;
   $ttl1= mysql_query("SELECT * FROM `wm_users_ban` WHERE `user_id` = '".$user_id."' AND `type` = '1' LIMIT 1;");
   if(!mysql_num_rows($ttl1))
   {
     if($path)
     {
        if(file_exists($path.'/set.ini'))
         $set=parse_ini_file($path.'/set.ini');
       else
         $set=parse_ini_file('files/rights.ini');  
     }
     else
     {
        global $path,$wikipage;
      
       if ($path and file_exists($path.'/set.ini'))
          $set=parse_ini_file($path.'/set.ini');   
       elseif($wikipage['path'] and file_exists(cut_dir2($wikipage['path']).'/set.ini'))
       {
         
          $path = cut_dir2($wikipage['path']);
          $set=parse_ini_file($path.'/set.ini');
       }
       else
          $set=parse_ini_file('files/rights.ini');
     }

      global $rights;

      $r['admin']=9;
      $r['moder']=7;
      $r['superuser']=5;
      $r['user']=3;
      $r['guest']=1;
      $numrights=$r[$rights];
      $num=$r[$set[$whatdo]];
	  if ($numrights >= $num) return TRUE;
	  else return FALSE;
   }
   else
    return FALSE; 
}

function vpage($all,$path,$colpages='')
{
 ////////////////////////////////////////////////////////////
 // постраничная навигация                                 //
 ////////////////////////////////////////////////////////////	
  global $nat, $lang, $name_wiki, $mod_rewrite;
  
  if($name_wiki and $mod_rewrite)
    $rad = '';
  else
    $rad = 'p=';
  
  if (!$colpages) global $colpages;	
  $pages=ceil($all/$colpages);
  if ($pages > 1)
  {
	$i_page = $nat;
	$_left = $i_page-2;
	if ($_left <= 1)
      $_left=1;
	$_right = $i_page+2;
	if ($_right >= $pages)
      $_right=$pages;
	$enter[]=$lang['pgd'].' ';
    if ($i_page > 1)
    {
      $tt=$i_page;
      $tt=$tt-1;
      $enter[]='<a class="navpg" href="'.$path.$rad.$tt.'">&lt;&lt;</a>&nbsp;';
    } 
	if ($i_page > 4)
      $enter[]='<a class="navpg" href="'.$path.$rad.$tt.'">1</a>...';
	if ($i_page == 4)
      $enter[]='<a class="navpg" href="'.$path.$rad.$tt.'">1</a> ';
	for ($d=$_left;$d <= $_right;$d++)
	{
 	  if($d == ($i_page))
        $enter[]='<span class="currentpage">'.$d.'</span> ';
      else
        $enter[]='<a class="navpg" href="'.$path.$rad.$d.'">'.$d.'</a> ';
	}
	if ($i_page < $pages-3)
      $enter[]='...<a class="navpg" href="'.$path.$rad.$pages.'">'.$pages.'</a> ';
    elseif($i_page == $pages-3)
      $enter[]='<a class="navpg" href="'.$path.$rad.$pages.'">'.$pages.'</a> ';
    if ($i_page < $pages)
    {
      $tt2=$i_page;
      $tt2=$tt2+1;
      $enter[]='<a class="navpg" href="'.$path.$rad.$tt2.'">&gt;&gt;</a>';
    }
	$enter = implode('',$enter);
	return '<div class="pagenumb">'.$enter.'</div>';
  }
else
	return '';
}

function mod()
{
  global $rights, $set;  
  if ($rights=='admin' or $set['mod']=='nomod')
  return FALSE;
  else
  {
    $r['moder']=7;
    $r['superuser']=5;
    $r['user']=3;
    $r['guest']=1;
    $numrights=$r[$rights];
    $num=$r[$set['mod']];
	if ($numrights > $num) return FAlSE;
	else return TRUE;
  }
}

function out($string)
{
  return htmlentities($string, ENT_QUOTES, 'UTF-8');  
}

function adds($string)
{
  $len=mb_strlen($string)-1;
  for($i=0;$i<=$len;$i++)
  {
      $text=mb_substr($string,$i,1);
      if (preg_match("/[0-9A-zА-я\|]/U",$text))
        $out .= $text;
      else
      {
         $last = '';
         $last = mb_substr($string,$i-1,1); 
         if ($text==';' and ($last == '|' or $i==0))
           $out .= '\s\\'.$text;
         else
           $out .= '\\'.$text; 
      }
  }
  return $out;
}

function smiles($str)
{
  global $parent;
  $sm1 = mysql_query('SELECT * FROM `wm_smiles` WHERE `type` = "sm" ;');
  while ($sm = mysql_fetch_assoc($sm1))
  {
    $str = preg_replace('/('.adds($sm['pattern']).')/su', '<img src="'.$parent.'sourse/smiles/'.$sm['image'].'" alt="" />', $str, 3);
  }
  return $str;  
}

function gz_pack($string)
{
 return gzdeflate($string, 9);
}

function gz_unpack($string)
{
  return gzinflate($string);  
}

function is_image($string)
{
  $exts = array('png', 'jpg', 'gif', 'jpeg');  
  if(in_array(getextension($string),$exts))
    return TRUE;
  else
    return FALSE;
}

function cut_filename($string,$cut = 15)
{ 
   $ext = getextension($string);
   $string = cut_ext($string);
   if(mb_strlen($string) > 15)
   {
     $string = mb_substr($string, 0, $cut);
     $string = out($string).'(...)';
   }
   return  $string.'.'.$ext;
}


function disk_new($mode='')
{
  global $user_id;
  $req = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_pages` LEFT JOIN `wm_page_comm` ON `wm_pages`.`id` = `wm_page_comm`.`page` AND `wm_page_comm`.`userid` = '" . $user_id . "' WHERE `wm_pages`.`comm_time` > '0' AND (`wm_page_comm`.`page` Is Null OR `wm_pages`.`comm_time` > `wm_page_comm`.`time`)"), 0);
  if($req)
  {
    if($mode)  
      return '+'.$req;
    else
      return $req;  
  }  
}

function pages_new($mode='')
{
  global $user_id;
  $req = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_pages` LEFT JOIN `wm_page_view` ON `wm_pages`.`id` = `wm_page_view`.`page` AND `wm_page_view`.`userid` = '" . $user_id . "' WHERE `wm_pages`.`time` > '0' AND (`wm_page_view`.`page` Is Null OR `wm_pages`.`last_edit` > `wm_page_view`.`time`)"), 0);
  if($req)
  {
    if($mode)  
      return '+'.$req;
    else
      return $req;  
  }  
}

function prw($string)  //безопасный вывод названия страниц
{
  return rawurlencode(str_replace(' ','_',$string));  
}

function prd($string)  //безопасный вывод url папок
{
  return rawurlencode($string);  
}
