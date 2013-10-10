<?
////////////////////////////////////////////////////
//                 WikiMobile                     //
////////////////////////////////////////////////////
// Автор:              web_demon                  //
// Оф. Сайт:           http://wikimobile.su       //
// Oф. сайт поддержки: http://annimon.com         //
// E-mail:             web_demon@mail.ru          //
////////////////////////////////////////////////////

$f=abs(intval($_GET['file']));
if($f)
{
  $file1 = mysql_query('SELECT * FROM `wm_files` WHERE `id`="'.$f.'" LIMIT 1;');
  if(mysql_num_rows($file1))
  {
    $file = mysql_fetch_array($file1);
    mysql_query("UPDATE `wm_files` SET `view` = '".($file['view']+1)."' WHERE `id` = '".$f."'");  
    func('download',$file['name'],file_get_contents('sourse/files/'.$file['filename'].'.dat')); 
  }
  else
  {
    header ('Location: ./?do=404');
    exit();
  }  
}
else
{
   header ('Location: ./?do=404');
   exit();  
}
?>
