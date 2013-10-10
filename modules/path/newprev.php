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
if (can('create_stat'))
{

$id_view=intval(abs($_GET['edit']));
  if(!file_exists('files/preview/'.$id_view.'.ini') or $id_view!=$_SESSION['preview'])
  {
    header ('Location: ./?do=404');
    exit(); 
  }
  $_SESSION['preview']=$_SESSION['preview'];
  $_SESSION['whatedit']=$path_p;
  $_SESSION['langedit']=$stat_lang;
  $_SESSION['pagename']=$_SESSION['pagename'];
  $_SESSION['att']=$_SESSION['att'];
  $_SESSION['page_create']=$_SESSION['page_create'];
  if(can('create_stat'))
  {   
    $textbody = file_get_contents('files/preview/'.$id_view.'.ini');
    require_once ('inc/parser.php');
    $err= $lang['pre_w_w'];
    require_once ('inc/head.php');

    $nat = abs(intval($_GET['p']));
    if (!$nat) $nat=1;
    $nach=($nat-1)*$set['symbols'];

    $wiki=new WikiParser;

    $textarray = $wiki->parse($textbody);
    $len=count($textarray);
    $textarray=$textarray[$nat-1];
    require_once ('inc/preparse.php');
    $textarray = preparse($textarray);
    echo $textarray;
    echo '<div style="clear: both;"></div>'; 
    if($wiki->fnt_count)
    {
      echo '<hr/><small>';
      $i=0;
      while($wiki->fnt[$i])
      {
        $a=$i+1;
        echo '<a href="#fnt__'. $a.'" id="fn__'. $a.'" name="fn__'. $a.'">'. $a.')</a>'.$wiki->fnt[$i].'</br>';
        $i++;  
      }
      echo '</small>';  
    }
    $vpage=vpage($len,'?id='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;do=newprev'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;edit='.($id_view).'&amp;',1);
    if ($vpage)
    {
      echo '<hr/>'.$vpage;
    }
    echo '</div>';
    echo '<div class="add"><form action="?id='.$path_p.'&amp;do=wikicreate" method="POST"><input type="submit" value="'.$lang['editing'].'" class="edit" /></form></div>';
    echo '<hr />';

    require_once ('inc/fin.php');
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