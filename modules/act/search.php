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

$item=trim($_GET['item']);
$req1= mysql_query("SELECT * FROM `wm_pages` LEFT JOIN `wm_page_lang` ON `wm_pages`.`id`=`wm_page_lang`.`pid`  WHERE `wm_page_lang`.`name` = '".mysql_real_escape_string($item)."' AND `wm_page_lang`.`pid` IS NOT NULL LIMIT 1;");
if (mysql_num_rows($req1))
{
  $wikipage = mysql_fetch_array($req1); 
  if ($mod_rewrite)
  {
    header('Location: ./wiki/'.prw($item));
    exit();
  }
  else
  { 
    header('Location: ?uid='.$wikipage['id']);
    exit();
  }
}

$colpages=$set['search_on_page'];
$nat = abs(intval($_GET['p']));
if (!$nat) $nat=1;
$tr=($nat-1)*$colpages;
$fin=$nat*$colpages-1;


class search
{
var $item;
var $path;
var $name;
var $total;
var $out;
var $now=0;
var $all=0;

  function number_of_mod($directory,$count=0)
  {
  global $set,$page;
  $dir=opendir($directory);
  while(($file=readdir($dir)))
    {
      if ( is_file ($directory."/".$file) and getextension($file)=='txt')
      {
      	  $content=mb_strtolower(file_get_contents($directory.'/'.$file));
      	  $this->file = substr_count($content,$this->item);
      	  if ($this->file > 0)
      	  {
      	  	$this->name[]=str_replace("/",":",(cut_data(cut_ext($directory."/".$file))));
      	  	$this->path[]=$directory."/".$file;
      	  	$this->now++;
      	  	$this->total[]=$this->file;
      	  	$s_item=$this->item;
      	  	$pos=mb_strpos($content,$s_item);
      	  	$len=mb_strlen($s_item);
      	  	$pos_to=$pos-20;
      	  	if ($pos_to < 0)
      	  		$pos_to=0;
      	  	$str_long=20+$len+20;
      	  	$out=htmlentities(mb_substr($content, $pos_to, $str_long), ENT_QUOTES, 'UTF-8');
      	  	$s_item=htmlentities($s_item, ENT_QUOTES, 'UTF-8');
      	  	$this->out[]=str_replace($s_item,'<span class="found">'.$s_item.'</span>',$out);
      	  	unset ($s_item);
          }
      	$this->all++;
      }
      else if ( is_dir ($directory."/".$file) && ($file != ".") && ($file != ".."))
      {
        $count=$this->number_of_mod($directory."/".$file,$count);  
      }
    }
    closedir ($dir);
    return TRUE;
  }
  
  function search($item)
  {
  	$this->path=array();  //абсолютный путь
  	$this->name=array();  //ссылка в вики
  	$this->total=array(); //соответствий на страницу
  	$this->out=array();   //строки с подсветкой
    $this->item=mb_strtolower($item);    //искомая срока
    $this->number_of_mod('data');
  }
}

if ((mb_strlen($item) < 100) and (mb_strlen($item) >= 1)){}
elseif($item)
  $err=$lang['string_short'];

require_once ('inc/head.php');
echo '<h2>'.$lang['search_h'].'</h2><hr/>';
echo '<form action="?" style="margin: 5px;" ><input type="hidden" name="do" value="search" /><input type="text" name="item" value="'.htmlentities($item, ENT_QUOTES, 'UTF-8').'"  size="15" class="edit2" /><input type="submit" value="'.$lang['sea_do'].'" class="edit" title="'.$lang['sea_do'].'" /></form>';

if ($item)
{
echo '<hr/>'.$lang['s_warn_1'].' "<b>'.out($item).'</b>" '.$lang['s_warn_2'];
echo '<br/>'.$lang['s_down_res'].'<hr />';
echo '<h2>'.$lang['results_s'].' </h2>';

if ((mb_strlen($item) < 100) and (mb_strlen($item) >= 1))
{
  $search= new search($item);
  if (!$search->name)
  echo '<div class="search_snippet">'.$lang['bad_search'].'</div>';
  else
  {
    for($i=$tr;$i<=$fin;$i++)
    {
    	if($i >=$search->now)break;
        $link1 = mysql_query("SELECT * FROM `wm_pages` WHERE `path` = '".mysql_real_escape_string(cut_ext(cut_ext($search->path[$i])))."' LIMIT 1;");
        $link = mysql_fetch_array($link1);
        $link_name1 = mysql_query("SELECT * FROM `wm_page_lang` WHERE `pid` = '".$link['id']."';");
        
        $arr_link=array();
        $last_name='';
        
        while ($link_name = mysql_fetch_array($link_name1))
        {
          $arr_link[$link_name['lang']] = $link_name['name'];
          $last_name=$link_name['name'];  
        }
        $page_lang=getextension(cut_ext($search->path[$i]));
        if($arr_link[$page_lang])
          $link['name'] = $arr_link[$page_lang];
        elseif($arr_link[$requied_lang])
          $link['name'] = $arr_link[$requied_lang];
        else
          $link['name'] = $last_name;                                                                                                 //dfghdfghfd
    	echo '<img src="./sourse/lang/'.$page_lang.'.png"/> <a class="list" href="'.($mod_rewrite ? 'wiki/'.prw($link['name']).'/'.$page_lang : './?fid='.$search->name[$i].'&amp;lang='.$page_lang).'">'.out($link['name']).'</a>: '.$search->total[$i].' '.$lang['sootv'].'<br />';
    	echo '<div class="search_snippet">'.$search->out[$i].'...</div><hr/>';
    }
  }
}
$vpage=vpage($search->now,'?do=search&item='.out($item).'&',$colpages);
if($vpage)
{
	echo '</div><div class="add"><center>';
	echo $vpage;
	echo '</center>';
}
}
echo '</div><hr />';
require_once ('inc/fin.php');

?>