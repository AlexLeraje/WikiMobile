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

function  getnav($path)
{
 ////////////////////////////////////////////////////////////
 // вложенность файлов в папках                            //
 ////////////////////////////////////////////////////////////    
  $path=str_replace("/",":",$path);
  global $parent, $lang, $requied_lang, $set;
  $element = cut_dir($path);

  while ($element)
  {
     $elem2=str_replace(":","/",$element);
    if(file_exists($elem2.'/name.'.$requied_lang.'.dat'))
      $elem2 =  file_get_contents($elem2.'/name.'.$requied_lang.'.dat');
    elseif(file_exists($elem2.'/name.dat'))
      $elem2 =  file_get_contents($elem2.'/name.dat');
    else
    {
        $elem2 = cut_name($element);
        if ($elem2=='data')
         {
             $elem2=$lang['content'];
         }
    }
    $temp_link=cut_data($element);
    $a_element[]= ''.($temp_link ? '» ' : '').'<a href="'.($temp_link ? $parent.'?id='.$temp_link : ($parent ? $parent.'?do=sod' : './?do=sod')).'">'.$elem2.'</a> ';

    $element = cut_dir($element);
  }
  $a_element = @array_reverse($a_element);
  $a_element = implode('',$a_element);
  return $a_element;
}
?>
