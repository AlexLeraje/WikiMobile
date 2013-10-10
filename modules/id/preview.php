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
$id_view=intval(abs($_GET['edit']));
  if(!file_exists('files/preview/'.$id_view.'.ini') or $id_view!=$_SESSION['preview'])
  {
    header ('Location: ./?do=404');
    exit(); 
  }
  $_SESSION['preview']=$id_view;
  $_SESSION['whatedit']=$id;
  $_SESSION['langedit']=$stat_lang;
  if(can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
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
    $vpage=vpage($len,'?uid='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;do=preview'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;edit='.($id_view).'&amp;',1);
    if ($vpage)
    {
      echo '<hr/>'.$vpage;
    }
    echo '</div>';
    echo '<div class="add"><form action="?uid='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;do=edit'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;edit='.$id_view.'" method="POST"><input type="submit" value="'.$lang['editing'].'" class="edit" /></form></div>';
    echo '<hr />';
    echo '<a href="'.$parent.'?uid='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;do=history'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'"><img src="'.$parent.'themes/engine/'.$set['theme'].'/history.png" /> '.$lang['history'].'</a><br />';
    require_once ('inc/fin.php');
  }
  else
  {
    header ('Location: ./?do=404');
    exit();
  }
?>
