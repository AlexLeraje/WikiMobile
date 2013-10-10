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

$act = $_GET['in'];
$d = intval(abs($_GET['d']));
if(!$act)
{
  require_once ('inc/head.php');
  echo '<h2>'.$lang['smile_cat'].'</h2>';  
  $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_smiles` WHERE `type` = 'ct'"), 0);
  if ($total > 0)
  {
    $req = mysql_query("SELECT * FROM `wm_smiles` WHERE `type` = 'ct'");  
    while ($res = mysql_fetch_assoc($req))
    {
      $total_sm = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_smiles` WHERE `type` = 'sm' AND `refid`='".$res['id']."' ;"), 0);
      echo '<hr/><a href="./?do=smiles&amp;in=cat&amp;d='.$res['id'].'"><img src="'.$parent.'themes/engine/'.$set['theme'].'/folder.png"  /> '.$res['pattern'].'</a>';
      echo '<br/>&nbsp;&nbsp;&nbsp;&nbsp;'.$lang['smiles_s_num'].' '.$total_sm.'';
    }  
  }
  else
  {
     echo '<hr/>'.$lang['no_cat']; 
  }
  echo '</div>';
  echo '<hr />';
  require_once ('inc/fin.php');
}
elseif($act=='cat')
{
       if($d)
      {
         require_once ('inc/head.php');
         $smcat1 = mysql_query("SELECT * FROM `wm_smiles` WHERE `type` = 'ct' AND `id` = '".$d."' LIMIT 1 ;");
         if(mysql_num_rows($smcat1))
         {
           $smcat = mysql_fetch_assoc($smcat1);
           echo '<h2>'.$lang['smiles'].' &raquo; '.$smcat['pattern'].'</h2>';
           $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_smiles` WHERE `type` = 'sm' AND `refid` = '".$d."';"), 0);
           if($total)
           {
             $colpages=10;
             $nat = abs(intval($_GET['p']));
             if (!$nat) $nat=1;
             $tr=($nat-1)*$colpages;  
             $req_sm = mysql_query("SELECT * FROM `wm_smiles` WHERE `type` = 'sm' AND `refid` = '".$d."'  ORDER BY `id` DESC LIMIT $tr,$colpages;");
             while ($res_sm = mysql_fetch_assoc($req_sm))
             {                                           
               echo '<hr/><img src="'.$parent.'sourse/smiles/'.$res_sm['image'].'" alt="" /> - '.str_replace('|',' ',$res_sm['pattern']).'';
             }
             $vpage=vpage($total,'./?do=smiles&amp;in=cat&amp;d='.$d.'&amp;',$colpages);
             if ($vpage)
             {
                echo '<hr/>'.$vpage;
             }
           }
           else
           {
             echo '<hr/>'.$lang['no_smiles'];  
           }

         }
         echo '</div>';
         echo '<hr />';
         echo '<a href="./?do=smiles"><img src="'.$parent.'themes/engine/'.$set['theme'].'/sm.png" /> '.$lang['smiles'].'</a><br />';
         require_once ('inc/fin.php');
       }
      else
      {
        header('Location: ./');
        exit();
      }
}
?>
