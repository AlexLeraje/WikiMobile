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
  function handle_restole_file($m)
  {
    if (file_exists($m[1].'.dat'))
      return 'green';
    else
      return 'red';  
  }

  function handle_restole_wiki($m)
  {
   $m[1]= func('unhtmlentities',$m[1]);   
   global $parent, $mod_rewrite;
   if (substr_count($m[1],'|'))
   {
      $n=mb_strpos($m[1],'|');
      $item=mb_substr($m[1],0,$n);
      $link = str_replace(" ",'_',$item); 
      $n=$n+1;
      $text = out(mb_substr($m[1],$n));
   }
   else
   {
     $item = $m[1];
     $text = out(preg_replace("/\s\((.*?)\)$/",'',$m[1]));
     $link = str_replace(" ",'_',$m[1]);
   }
   $req1= mysql_query("SELECT * FROM `wm_page_lang` WHERE `name` = '".mysql_real_escape_string($item)."' LIMIT 1;");
   if (mysql_num_rows($req1))
   {
     $res = mysql_fetch_array($req1);  
   }
      return '<a style="color:'.($res['name'] ? 'green' : 'red').';text-decoration:underline" href="'.$parent.($mod_rewrite ? 'wiki/'.prw($link) : ($res['name'] ? '?uid='.$res['id'] : '?do=404')).'" >'.($text ? $text : out($link)).'</a>';     
  }

function file_name_to_id($m)
{
  $file1 = mysql_query('SELECT * FROM `wm_files` WHERE `filename`="'.$m[1].'" LIMIT 1;');
  if (mysql_num_rows($file1))
    {
      $file = mysql_fetch_array($file1);
      return $file['id'];
    }  
}  
  
function preparse($string)
{
    global $parent;
    $string = str_replace('<parent>',$parent,$string);
    $string = str_replace('<clboth>',' style="clear: both;" ',$string);
    $string = preg_replace_callback('/\<wiki\=(.*?)\>/i',"handle_restole_wiki",$string);
    $string = preg_replace_callback('/\<file\=(.*?)\>/i',"handle_restole_file",$string);
    $string = preg_replace_callback('/\<\#(.*?)\#\>/i',"file_name_to_id",$string);
    
    return $string;
}
  
?>