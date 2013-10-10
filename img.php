<?
////////////////////////////////////////////////////
//                 WikiMobile                     //
////////////////////////////////////////////////////
// Автор:              web_demon                  //
// Оф. Сайт:           http://wikimobile.su       //
// Oф. сайт поддержки: http://annimon.com         //
// E-mail:             web_demon@mail.ru          //
////////////////////////////////////////////////////
Error_Reporting(E_ALL & ~ E_NOTICE);
header("Expires: " . date("r",  $realtime + 60));
header("Content-type: image/png");
$maxh=250;
$maxw= 250;


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

$image  =  trim($_GET['i']);
if(preg_match("/[^0-9a-z\-\_\.]+/",$image))
{
  echo file_get_contents('sourse/img_error.png');
  exit();  
}
$imgfile = $image;
$image='sourse/files/'.$image.'.dat';
if (!file_exists($image))
{
  $image = 'sourse/img_error.png';
  $error=true;  
}
   //$image = 'sourse/img_error.png';
   $h=intval(abs($_GET['h']));
   $w=intval(abs($_GET['w']));
   $size= getimagesize($image);
   if($size[1] and $size[0])
   {
     //определяемся с размерами
     if(!$h and !$w)
     {
       $h = $size[1];
       $w = $size[0];
       if ($h>=$w and $h>= $maxh)
       {
          $w=$w*$maxh/$h;
          $h=$maxh; 
       }
       elseif($w>$h and $w> $maxw)
       {
           $h=$h*$maxw/$w;
           $w=$maxh;             
       }
     }
     elseif($h and !$w)
     {
        if($h > $maxh)
        {
          $w=$size[0]*$maxh/$size[1];
          $h=$maxh; 
        }
        else
          $w=$size[0]*$h/$size[1];  
     }
     elseif($w and !$h)
     {
        if($w > $maxw)
        {
          $h=$size[1]*$maxw/$size[0];
          $w=$maxw; 
        }
        else
          $h=$size[1]*$w/$size[0];
     }
     else
     {
       if ($h>$w and $h> $maxh)
       {
          $w=$w*$maxh/$h;
          $h=$maxh; 
       }
       elseif($w>$h and $w> $maxw)
       {
           $h=$h*$maxw/$w;
           $w=$maxh;             
       }
     }
     $h=ceil($h);
     $w=ceil($w);
     if(!$error and file_exists('sourse/screens/'.$imgfile.'.'.$w.'x'.$h.'.png'))
       echo file_get_contents('sourse/screens/'.$imgfile.'.'.$w.'x'.$h.'.png');  
     else
     {
       if(!$error)
         $smallext=getextension($imgfile);
       else
         $smallext='png';
       if($smallext=="gif")
         $img = imagecreatefromgif($image);
       elseif($smallext=="jpeg")
         $img = imagecreatefromjpeg($image);
       elseif($smallext=="jpg")
         $img = imagecreatefromjpeg($image);
       elseif($smallext=="png")
         $img = imagecreatefrompng($image);
       $out=ImageCreateTrueColor($w,$h);
       imagealphablending($out, false);
       imagesavealpha($out,true);
       $transparent = imagecolorallocatealpha($out, 255, 255, 255, 127);
       imagefilledrectangle($out, 0, 0, $w, $h, $transparent);
       imagecopyresampled($out, $img, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
       $out2 = $out;
       if(!$error)
         imagepng($out,'sourse/screens/'.$imgfile.'.'.$w.'x'.$h.'.png'); 
       imagepng($out);
     }
   }
   else
   {
     echo file_get_contents('sourse/img_error.png'); 
   }
?>