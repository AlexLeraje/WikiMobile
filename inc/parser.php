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

class WikiParser
{
  var $fnt_count;
  
//////////////////////////////////////////////
//     ВОССТАНОВЛЕНИЕ ДАННЫХ                //
//////////////////////////////////////////////
  
  function handle_restore_table($m)
  {
    return array_shift($this->table);  
  }
  
  function handle_restore_nums($matches)
  {    
    return array_shift($this->nums);
  }
  
  function handle_restore_fnt($maches)
  {
    return $type=array_shift($this->fnt_text);
  }  
  
  function handle_restore_image($maches)
  {
    return $type=array_shift($this->image);
  }
  
  function handle_restore_nw2($matches)
  {    
    return str_replace("\n","<br/>",htmlentities(array_shift($this->nw2), ENT_QUOTES, 'UTF-8'));
  }
  
  function handle_restore_nw($matches)
  {    
    return str_replace("\n","<br/>",htmlentities(array_shift($this->nw), ENT_QUOTES, 'UTF-8'));
  }
  
  function handle_restore_url($matches)     
  { 
    $this->url = preg_replace_callback('/\{\{(img)\:([^\n\&\/\"\\\\<\>\+\&\;\:]{1,100})\}\}/', array(&$this,'image_url_replace'), $this->url);
    $this->url = preg_replace_callback('/\[image\]\[\/image\]/i',           array(&$this,"handle_restore_image"),  $this->url);                                   
    return array_shift($this->url);         
  }                                         
                                            
  function handle_restore_img($matches)     
  {                                         
    return array_shift($this->img);         
  }                                         
                                            
  function handle_restore_file($matches)    
  {                                         
    return array_shift($this->file);        
  }                                         
                                            
  function handle_restore_code($matches)    
  {                                         
    return array_shift($this->code);        
  }                                         
                                            
  function handle_restore_nowiki($matches)  
  {                                         
    return '<div class="nowiki">'.str_replace("\n","<br/>",htmlentities(array_shift($this->nowikis), ENT_QUOTES, 'UTF-8')).'</div>';
  }
  
  function wiki_replace()
  {  
    return array_shift($this->wikilink);
  }                                                                                  
//////////////////////////////////////////////




//////////////////////////////////////////////
//       ПАРСИНГ ДАННЫХ                     //
//////////////////////////////////////////////
  
  function handle_save_nw2($matches)
  {
    array_push($this->nw2,$matches[1]);
    return '[nw2][/nw2]';
  }
  
  function handle_save_nw($matches)
  {
    array_push($this->nw,$matches[1]);
    return '[nw][/nw]';
  }

  function handle_save_code($matches)
  {
      
    $code = $this->parsecode($matches[2],$matches[1]);   
    array_push($this->code,'<div class="code">'.$code.'</div>');
    return '[code][/code]';
  }

  function color_replace($m)
  {
    $tag='<#'.$m[1].'>';
    $tag_fin = '<#/'.$m[1].'>';
    if(!in_array($tag,$this->array_tags))
    {
       array_push($this->array_tags, $tag);
       array_push($this->array_tags_fin, $tag_fin);
    } 
    return $tag.$m[2].$tag_fin;
  }
  
  function save_wikilink($m)
  {
    array_push($this->wikilink,'<wiki='.htmlentities($m[1]).'>');
    return '[wiki][/wiki]';  
  }
  
  function url_replace($matches)
  {
    if ($matches[2])
      array_push($this->url,'<a href="' . $matches[1] . '" '.(func('get_host',$matches[1])==$_SERVER['SERVER_NAME'] ? '' : 'class="urlextern"').' >' . $matches[2] . '</a>');  
    elseif($matches[3])
      array_push($this->url,'<a href="' . $matches[3] . '" '.(func('get_host',$matches[3])==$_SERVER['SERVER_NAME'] ? '' : 'class="urlextern"').' >' . $matches[3] . '</a>');  
    elseif($matches[4])
      array_push($this->url,'<a href="' . $matches[4] . '" '.(func('get_host',$matches[4])==$_SERVER['SERVER_NAME'] ? '' : 'class="urlextern"').' >' . $matches[4] . '</a>');  
    return "[url][/url]";
  }
  
  function handle_save_nowiki($matches)
  {
    array_push($this->nowikis,$matches[1]);
    return "[nowiki][/nowiki]";
  }
  
  function razd_replace($maches)
  {
    array_push($this->razd,$maches[2]);
    $len=mb_strlen($maches[1]);
    if ($len==2)
      return '<h2>'.$maches[2].'</h2>';
    elseif ($len==3)
      return '<h3>'.$maches[2].'</h3>';
    elseif ($len==4)
      return '<h4>'.$maches[2].'</h4>';
  }
  
  
  function parse_numbers($m)
  {
    global $parent, $set;
    $nb = mb_strlen($m[1]);
    for ($i=1;$i<=$nb;$i++)
    {
      $string .= '&nbsp;&nbsp;';
    }
    return '<br/>'.$string.'<img src="<parent>themes/engine/'.$set['theme'].'/list.png" /> '; 
  }
  
  function parse_nums($m)
  {
    global $parent, $set;
    $nb = mb_strlen($m[1]);
    for ($i=1;$i<=$nb;$i++)
    {
      $string .= '&nbsp;&nbsp;&nbsp;';
    }
    array_push($this->nums,'<br/>'.$string.'<img src="<parent>themes/engine/'.$set['theme'].'/list.png" /> ');
    return '[nums][/nums]';
  }
  
  function allowed_languages($symb)
  {
    $elem=array();
    $dir = opendir('inc/geshi');
     while ($file = readdir($dir))
     { 
       if (($file !=".")&&($file !="..")&&($file !="index.php")&&($file !=".htaccess"))
       {
         $elem[]= cut_ext($file);
         $i++;
       }
     }
     return implode($symb,$elem);  
  }
  
  function save_fnt($matches)
  {
     $matches[1] = out($matches[1]); 
     array_push($this->fnt,htmlentities($matches[1], ENT_QUOTES, 'UTF-8'));
     $this->fnt_count=$this->fnt_count+1;
     array_push($this->fnt_text,'<sup><a href="#fn__'.$this->fnt_count.'" name="fnt__'.$this->fnt_count.'" id="fnt__'.$this->fnt_count.'">'.$this->fnt_count.')</a></sup>');
     return '[fnt][/fnt]'; 
  }
  
  function image_url_replace($m)
  {
  global $parent,$set;
     if (substr_count($m[2],'|'))
     {
      $n=mb_strpos($m[2],'|');
      $item=mb_substr($m[2],0,$n); 
      $n=$n+1;
      $text = mb_substr($m[2],$n);
     }
     else
     {
       $item= $m[2]; 
     }
     $fileitem=$item;
     if(preg_match('/^ ([^\s]{1,}) $/',$item))
     {
       $align='none_float';  
     }
     elseif(preg_match('/^ ([^\s]{1,})$/',$item))
     {
        $align='right_float';    
     }
     elseif(preg_match('/^([^\s]{1,}) $/',$item))
     {
        $align='left_float'; 
     }
     $item=trim($item);     
     preg_match('/\?([0-9]{0,4})x([0-9]{0,4})$/',$item,$maches);
     if ($maches[0])
     {
       $item=str_replace($maches[0],'',$item);
       $w= $maches[1];
       $h= $maches[2];
     }
     if (!$text) $text = $item;
     $item = htmlentities($item, ENT_QUOTES, 'UTF-8');
     $fileitem = htmlentities($fileitem, ENT_QUOTES, 'UTF-8');
     $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
    if($m[1]=='img' and is_image($item))
    {
      array_push($this->image,'<img '.($align ? 'class="'.$align.'"' : '').' alt="'.$text.'" src="<parent>img.php?i='.$item.''.($w ? '&amp;w='.$w : '').''.($h ? '&amp;h='.$h : '').'" />');
      return '[image][/image]';
    }
    else
    {
      $ext=getextension($fileitem);
      array_push($this->image,'<img src="<parent>sourse/img_error.png" />');
      return '[image][/image]';
    }
  }
  
  function image_replace($m)
  {
  global $parent,$set;
     if (substr_count($m[2],'|'))
     {
      $n=mb_strpos($m[2],'|');
      $item=mb_substr($m[2],0,$n); 
      $n=$n+1;
      $text = mb_substr($m[2],$n);
     }
     else
     {
       $item= $m[2]; 
     }
     $fileitem=$item;
     if(preg_match('/^ ([^\s]{1,}) $/',$item))
     {
       $align='none_float';  
     }
     elseif(preg_match('/^ ([^\s]{1,})$/',$item))
     {
        $align='right_float';    
     }
     elseif(preg_match('/^([^\s]{1,}) $/',$item))
     {
        $align='left_float'; 
     }
     $item=trim($item);     
     preg_match('/\?([0-9]{0,4})x([0-9]{0,4})$/',$item,$maches);
     if ($maches[0])
     {
       $item=str_replace($maches[0],'',$item);
       $w= $maches[1];
       $h= $maches[2];
     }
     if (!$text) $text = $item;
     $item = htmlentities($item, ENT_QUOTES, 'UTF-8');
     $fileitem = htmlentities($fileitem, ENT_QUOTES, 'UTF-8');
     $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
    if($m[1]=='img' and is_image($item))
    {
      array_push($this->image,'<a href="<parent>?do=fileinfo&amp;file=<#'.$item.'#>"><img '.($align ? 'class="'.$align.'"' : '').' alt="'.$text.'" src="<parent>img.php?i='.$item.''.($w ? '&amp;w='.$w : '').''.($h ? '&amp;h='.$h : '').'" /></a>');
      return '[image][/image]';
    }
    elseif($m[1]=='file' and getextension($fileitem))
    {
      $ext=getextension($fileitem);
      array_push($this->image,'<a style="color:<file=sourse/files/'.$fileitem.'>;" href="<parent>?do=fileinfo&amp;file=<#'.$fileitem.'#>"><img width="16" height="16" src="<parent>'.(file_exists('sourse/ext/'.$ext.'.png') ? 'sourse/ext/'.$ext.'.png' : 'themes/admin/'.$set['theme'].'/sis.png').'" /> '.($text ? $text : $fileitem).'</a>');
      return '[image][/image]';
    }
    else
      return $m[0];
  }
  
//////////////////////////////////////////////


//////////////////////////////////////////////
//    ТАБЛИЦЫ                               //
//////////////////////////////////////////////
  
  function parse_param($string)
  {
    $a_styles = explode(';', $string);
    $count = count($a_styles);
    for ($a=0;$a<$count;$a++)
    {
        if ($a_styles[$a] != '') 
        {
            $a_key_value = explode(':', $a_styles[$a]);
            $a_key_value[0]= trim($a_key_value[0]);
            $a_key_value[1] = trim($a_key_value[1]);
            if(mb_substr($a_key_value[1],0,1)=='#')
            {
              if(preg_match('/^\#[A-f0-9]{6}$/',$a_key_value[1]))  
                $css_array[$a_key_value[0]] = $a_key_value[1];
              else
                $css_array[$a_key_value[0]] = '';
            }
            else
              $css_array[$a_key_value[0]] = $a_key_value[1];
            
        }
    }  
    return $css_array;     
  }
  
  function parse_table($maches)
  { 
    //потом переделать этот бред
    $params = trim($maches[1]);
    $param = $this->parse_param($params);
    $text="\r\n".$maches[2];
    $text_back=$text;
    $rows=explode('|-',$text);
    $out=array();
    $i=0;
    
     if($param)
      {
        if($param['backgroung'])
          $tablestyle .= 'background: '.$param['backgroung'].'; ';
        if($param['border-color'])
          $td_style .=  'border-color: '.$param['border-color'].'; ';
        if($param['width'])
        {
          $param['width'] = abs(intval(str_replace('%','',$param['width'])));
          if($param['width'] and $param['width'] <= 100)
           $tablestyle .= 'width: '.$param['width'].'%; '; 
        }
        if($param['align'])
        {
          if($param['align']=='center')
            $tablestyle .='margin:  auto;';
          elseif($param['align']=='right')
            $tablestyle .='margin-left:  auto;';
          elseif($param['align']=='left')
            $tablestyle .='margin-right:  auto;';
        }
      } 
    while($rows[$i])
    {
      $td_pos = mb_strpos($rows[$i], "\n".'|');
      $th_pos = mb_strpos($rows[$i], "\n".'!');
      if($td_pos and $th_pos)
      {
        if ($td_pos < $th_pos)
          $pos=$td_pos;
        else
          $pos=$th_pos;  
      }
      elseif($td_pos)
        $pos=$td_pos;
      elseif($th_pos)
        $pos=$th_pos;
      //если нет ни одного столбца или есть ошибка в синтаксисе прекращаем парсинг
      if($pos!=1)
        {
          $err='1';
          break;  
        }
        
      unset($arr);
      $arr = preg_split('/\n(\||\!)/si',$rows[$i]);
      $b= 1;
      $out_temp ='';
      while($arr[$b])
      {
        $out_ =  $this->parse_text($arr[$b]);
        $out_ = preg_replace('/\[color ([0-9A-f]{6})\](.*?)\[\/color\]/si', '<span style="color: #\1">\2</span>', $out_);
        $out_ = preg_replace('/(\=\=\=\=|\=\=\=|\=\=)(.*?)\1/si', '<span class="h'.strlen('\1').'">\2</span>', $out_); 
        $out_temp .= '<td'.($td_style ? ' style="'.$td_style.'"' : '').'>'.$out_.'</td>';
        $b++;
      }
      $out[]='<tr>'.$out_temp.'</tr>';      
      $i++;  
    }
    if ($err)
      return '{|'.$text_back.'|}';
    else
    {
      $out=implode('',$out);
      array_push($this->table,'<table class="wikitable" style="'.$tablestyle.'">'.$out.'</table>');
      return '[table][/table]';
    }  
  }
//////////////////////////////////////////////


  
  
//////////////////////////////////////////////
//    ПАРСИНГ                               //
//////////////////////////////////////////////
  
  function parsecode($code,$type)
  {
    require_once('geshi.php');    
    $language = $type;
    $geshi = new GeSHi($code, $language);
    $geshi->set_header_type(GESHI_HEADER_DIV);
    return $geshi->parse_code();
  }

  function auto_tag($string)
  {
    $tags=array();
    $i=0;
    while($this->array_tags[$i])
    {
        $pos=strrpos($string,$this->array_tags[$i]);
        $pos_fin=strrpos($string,$this->array_tags_fin[$i]);
        if (!$pos_fin) $pos_fin = 0;
        if($pos)
        {
          if ($pos > $pos_fin)
          {
            $tags[$pos]= $this->array_tags_fin[$i]; 
          }
        }
        $i++;
    }
    ksort($tags);
    
    $head_tags=array();
    $i=0;
    while($this->array_tags_fin[$i])
    {
        $pos=strpos($string,$this->array_tags[$i]);
        $pos_fin=strpos($string,$this->array_tags_fin[$i]); 
        if (!$pos and $pos!==0) $pos = mb_strlen($string);
        if($pos_fin)
        {
          if ($pos_fin < $pos)
          {
            $head_tags[$pos_fin]= $this->array_tags[$i]; 
          }
        }
        $i++;
    }
    ksort($head_tags);
    
    $head_tags=implode('',$head_tags);
    $tags=implode('',$tags);
    return $head_tags.$string.$tags; 
  }
  
  function add_nw($string)
  {
     $string = preg_replace('#\[(wiki|url|code|nowiki|nums|color|nw|fnt|table|nw2|head)\]\[/\1\]#sU', '[nw][\1][/\1][/nw]', $string);
     return $string; 
  }
  

  
  function parse_text($string)
  {
    global $set,$parent;
    
    $string = preg_replace('/\<(left|center|right|sup|sub|small)\>(.*?)\<\/\1\>/si', '[\1]\2[/\1]',              $string);
    $string = preg_replace('/\<(hr|br)\/\>/si',                                      '[\1/]',                    $string);
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    $string = htmlentities($string, ENT_QUOTES,'UTF-8');
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $string = preg_replace('/\[(sup|sub|small)\](.*?)\[\/\1\]/si',                   '<\1>\2</\1>',                $string);
    $string = preg_replace('/\[(left|center|right)\](.*?)\[\/\1\]/si',               '<<\1>>\2<</\1>>',            $string);
    $string = preg_replace('/\[(red|green|blue)\](.*?)\[\/\1\]/si',                  '<\1>\2</\1>',                $string);
    $string = preg_replace('/\*\*(.*?)\*\*/si',                                      '<b>\1</b>',                  $string);
    $string = preg_replace_callback("/".PHP_EOL."(\ *?)\*/si",array(&$this,'parse_nums'),$string);
    $string = preg_replace('/(\/\/\/|\/\/)(.*?)\1/si',                               '<em>\2</em>',                $string);
    $string = preg_replace('/\_\_(.*?)\_\_/si',                                      '<u>\1</u>',                  $string);
    $string = preg_replace('/\[(hr)\/\]/si',                                         '<\1/>',                      $string);
    $string = preg_replace('/\[(br)\/\]/si',                                         '<\1<clboth>/>',$string);
    $string = preg_replace('/([\-]{4,})/',                                           '<hr/>',                      $string);
    $string = preg_replace('/(\-\-\-|\-\-)(.*?)\1/si',                               '<del>\2</del>',              $string);
    $string = preg_replace('/\n\;(.*?)/si',                                          '<br/>&nbsp;&nbsp;&nbsp; ',   $string);

    
    return $string;
  }  

  function postparse($out)
  {
    $out = preg_replace('/\<(sup)\>(.*?)\<\/\1\>/si',            '<span style="vertical-align:super">\2</span>',          $out);
    $out = preg_replace('/\<(sub)\>(.*?)\<\/\1\>/si',            '<span style="vertical-align:sub">\2</span>',            $out);
    $out = preg_replace('/\<(small)\>(.*?)\<\/\1\>/si',          '<span style="font-size:small">\2</span>',               $out);
    $out = preg_replace('/\<(b)\>(.*?)\<\/\1\>/si',              '<span style="font-weight:bold">\2</span>',              $out);
    $out = preg_replace('/\<(em)\>(.*?)\<\/\1\>/si',             '<span style="font-style:italic">\2</span>',             $out);
    $out = preg_replace('/\<(del)\>(.*?)\<\/\1\>/si',            '<span style="text-decoration: line-through">\2</span>', $out);
    $out = preg_replace('/\<(u)\>(.*?)\<\/\1\>/si',              '<span style="text-decoration:underline">\2</span>',     $out);
    $out = preg_replace('/\<(red|green|blue)\>(.*?)\<\/\1\>/si', '<span style="color:\1">\2</span>',                      $out);
    $out = preg_replace('/\<h(2|3|4)\>(.*?)\<\/h\1\>/si',        '<span class="h\1">\2</span>',                           $out);

    $out = preg_replace('#\<\<(left|center|right)\>\>(.*?)\<\</\1\>\>#si', '<span class="\1">\2</span>', $out);
    $out = preg_replace('/\<\#([0-9A-f]{6})\>(.*?)\<\#\/\1\>/si', '<span style="color: #\1">\2</span>', $out);
    
    return $out;
  }
  
  function parse($text)
  {
    global $parent, $set;
    
    $this->array_tags    =array('<sup>', '<sub>', '<small>', '<em>', '<del>', '<b>', '<u>', '<red>', '<green>', '<blue>', '<<right>>', '<<left>>', '<<center>>', '<h2>', '<h3>', '<h4>');
    $this->array_tags_fin=array('</sup>','</sub>','</small>','</em>','</del>','</b>','</u>','</red>','</green>','</blue>','<</right>>','<</left>>','<</center>>','</h2>','</h3>','</h4>');
    
    $this->sm_array = array();
    $this->tg = array();
    $this->image = array();
    $this->razd = array();
    $this->fnt = array();
    $this->fnt_text = array();
    $this->nw = array();
    $this->nw2 = array();
    $this->nowikis = array();
    $this->code = array();
    $this->url = array();
    $this->wikilink = array();
    $this->table = array();
    $this->nums = array();
    
    $text=$this->add_nw($text);
    
    //Убираем комментарии
    $text = preg_replace("/<!--(.*?)-->/",'',$text);
    //убираем блочные элементы
    
    $text = preg_replace_callback('#\[nw\](.*?)\[\/nw\]#si',array(&$this,"handle_save_nw"),$text);
    $text = preg_replace_callback('#\<code ('.$this->allowed_languages('|').')\>(.*?)\<\/code\>#si',array(&$this,"handle_save_code"),$text);
    $text = preg_replace_callback('#\[nowiki\](.*?)\[\/nowiki\]#si',array(&$this,"handle_save_nowiki"),$text); 
    $text = preg_replace_callback('#\%\%(.*?)\%\%#si',array(&$this,"handle_save_nw2"),$text);
    
      
    $text = preg_replace_callback('~\\[(https?://.+?)\\|(.+?)\\]|\\[(https?://.+?)\\|?]|(https?://(www.)?[0-9a-z\.-]+\.[0-9a-z]{2,6}[0-9a-zA-Z/\?\.\~&amp;_=/%-:#]*)~', array(&$this,'url_replace'), $text);

    //Убираем комментарии
    $text = preg_replace("/<!--(.*?)-->/",'',$text);
    /////////////////////////////////////////////////////////
                      //////
    /////////////////////////////////////////////////////////
    $text = preg_replace_callback('/\[\[([^\n\/\\\]*?)\]\]/', array(&$this,'save_wikilink'), $text);
    $text = preg_replace_callback('/\{\{(file|img)\:([^\n\&\/\"\\\\<\>\+\&\;\:]{1,100})\}\}/', array(&$this,'image_replace'), $text);
    //$text = preg_replace_callback('/\(\((.*?)\)\)/', array(&$this,'save_fnt'), $text); //Сноски, пока выпиливаем
    
    $text = preg_replace_callback('/\{\|(.*?)\r?\n(.*?)\|\}/s', array(&$this,'parse_table'), $text);
    
    $text = $this->parse_text($text);
    $text = preg_replace_callback('/\[color ([0-9A-f]{6})\](.*?)\[\/color\]/si', array(&$this,'color_replace'), $text);
    $text = preg_replace('/(\=\=\=\=|\=\=\=|\=\=)(.*?)\1\s?\<br\/\>/si', '\1\2\1', $text);
    $text = preg_replace_callback('/(\=\=\=\=|\=\=\=|\=\=)(.*?)\1/si', array(&$this,'razd_replace'), $text);
    $text = str_replace("\r\n\r\n",'<br/>',$text);
    $text = str_replace('[LANG]',$this->allowed_languages(', '),$text);
    
    $text=explode(' ',$text);
    
    //восстанавливаем блочные элементы
    
    $text = preg_replace_callback('/\[table\]\[\/table\]/i',           array(&$this,"handle_restore_table"),  $text);
    $text = preg_replace_callback('/\[code\]\[\/code\]/i',             array(&$this,"handle_restore_code"),   $text);
    $text = preg_replace_callback('/\[wiki\]\[\/wiki\]/i',             array(&$this,"wiki_replace"),          $text);
    $text = preg_replace_callback('/\[image\]\[\/image\]/i',           array(&$this,"handle_restore_image"),  $text);
    $text = preg_replace_callback('/\[url\]\[\/url\]/i',               array(&$this,"handle_restore_url"),    $text);
   	$text = preg_replace_callback('/\[nowiki\]\[\/nowiki\]/i',         array(&$this,"handle_restore_nowiki"), $text);
    $text = preg_replace_callback('/\[nums\]\[\/nums\]/i',             array(&$this,"handle_restore_nums"),   $text);
    $text = preg_replace_callback('/\[nw\]\[\/nw\]/i',                 array(&$this,"handle_restore_nw"),     $text);
    $text = preg_replace_callback('/\[nw2\]\[\/nw2\]/i',               array(&$this,"handle_restore_nw2"),    $text);
    $text = preg_replace_callback('/\[fnt\]\[\/fnt\]/i',               array(&$this,"handle_restore_fnt"),    $text);
    
    //Разбиваем по страницам
    $out=array();
    $a=0;
    $count=count($text);
    for($i=0;$i<=$count-1;$i++)
    {
       $outlen=mb_strlen(strip_tags($out[$a]));
       $len=mb_strlen(strip_tags($text[$i]));
       if (($outlen+$len) <= $set['symbols'])
       {
          $out[$a] .= $text[$i].' ';
       }
       else
       {
           $out[$a] .= $text[$i].' ';
           $a++; 
       }
    }
    //делаем так, чтоб не было ошибок
    $i=0;
    while($out[$i])
    {
       $out[$i]=$this->auto_tag($out[$i]);
       $i++; 
    }
    //Возвращаем цвета и вырывнивание
    
    $out = $this->postparse($out);
    //Убиваем лишние переносы строк после блочных элементов
    $out = preg_replace("/(\<hr\/\>|\<\/div\>|\<\/table\>)\s?\<br\/\>/si",'\1',$out);
    return $out;
  }	
}
?>