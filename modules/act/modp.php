<?
////////////////////////////////////////////////////
//                 WikiMobile                     //
////////////////////////////////////////////////////
// Автор:              web_demon                  //
// Оф. Сайт:           http://wikimobile.su       //
// Oф. сайт поддержки: http://annimon.com         //
// E-mail:             web_demon@mail.ru          //
////////////////////////////////////////////////////

if($rights=='admin' or $rights=='moder')
{
    $act=$_GET['act'];
    $mod =intval(abs($_GET['mod']));
    if($act=='agree')
    { 
      if(!file_exists('files/mod/'.$mod.'.ini'))
      {
         header ('Location: ./?do=404'); 
         exit(); 
      }
       
      $disk1= mysql_query("SELECT * FROM `wm_mod` WHERE `id` = '".$mod."' LIMIT 1;");
      if (!mysql_num_rows($disk1))
      {
        require_once ('inc/admin/head_info.php');
        echo '<b>'.$lang['error_imp'].'</b><br/>'.$lang['page_exists'].'</td></table><center>';
        echo '<form style="display:inline" method="post" action="?"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';
        require_once ('inc/admin/fin_info.php');
      }
      else
      {
        $disk = mysql_fetch_array($disk1);
         mysql_query("INSERT INTO `wm_pages` SET
          `path` = '".mysql_real_escape_string($disk['path'])."',
          `dir` = '".mysql_real_escape_string(cut_dir2($disk['path']))."',
          `time` = '".$disk['time']."',
          `id_create` = '".$disk['user_id']."',
          `user_name` = '".$disk['user_name']."',
          `comments` = '".$disk['comments']."',
          `can_edit` = 'superuser';");
        $postid = mysql_insert_id();
        mysql_query("INSERT INTO `wm_page_lang` SET
          `name` = '".mysql_real_escape_string($disk['name'])."',
          `dir` = '".mysql_real_escape_string(cut_dir2($disk['path']))."',
          `pid` = '".$postid."',
          `lang` = '".$disk['lang']."';"); 
        mysql_query("UPDATE `wm_files` SET `page` = '".$postid."' WHERE `att` = '".$disk['att']."' AND `page` = '0';");
        file_put_contents($disk['path'].'.'.$disk['lang'].'.txt',file_get_contents('files/mod/'.$mod.'.ini'));
        mysql_query("DELETE FROM `wm_mod` WHERE `id` = '".$mod."' LIMIT 1;");
        unlink('files/mod/'.$mod.'.ini');
        unlink('files/cache/count.dat');
        
        if ($user_id)
        {
          // Ставим метку о прочтении
          mysql_query("INSERT INTO `wm_page_view` SET  `page` = '".$postid."', `userid` = '".$user_id."', `time` = '".time()."'");
        }
        
        require_once ('inc/admin/head_info.php');
        echo '<b>'.$lang['page_added'].'</b>';
        echo '</td></table><center>';
        echo '<form action="./?do=sod" style="display: inline" method="post"><input type="submit" value="'.$lang['moderation'].'" class="edit" /></form>';
        echo '<form action="./?do=sod" style="display: inline" method="post"><input type="submit" value="'.$lang['to_page'].'" class="edit" /></form>';    
        echo '</center>';
        require_once ('inc/admin/fin_info.php');
      }  
    }
    elseif($act=='no')
    {
       if(!file_exists('files/mod/'.$mod.'.ini'))
       {
         header ('Location: ./?do=404'); 
         exit(); 
       } 
       require_once ('inc/admin/head_info.php');
       echo '<b>'.$lang['del_imp'].'</b>';
       echo '<br />'.$lang['really_del'].'</td></table><center>';
       echo '<form action="?do=modp&amp;mod='.$mod.'&amp;act=del" style="display: inline" method="post"><input type="submit" value="'.$lang['delete_yes'].'" class="edit" /></form>';    
       echo '<form action="?do=getmod&amp;mod='.$mod.'" style="display: inline" method="post"><input type="submit" value="'.$lang['cancel'].'" class="edit" /></form>';    
       echo '</center>';
       require_once ('inc/admin/fin_info.php'); 
    }
    elseif($act=='del')
    {
       if(!file_exists('files/mod/'.$mod.'.ini'))
       {
         header ('Location: ./?do=404'); 
         exit(); 
       }
       unlink('files/mod/'.$mod.'.ini');
       mysql_query("DELETE FROM `wm_mod` WHERE `id` = '".$mod."' LIMIT 1;"); 
       require_once ('inc/admin/head_info.php');
       echo '<b>'.$lang['page_deleted'].'</b>';
       echo '<br /></td></table><center>'; 
       echo '<form action="./?do=modp" style="display: inline" method="post"><input type="submit" value="'.$lang['continue'].'" class="edit" /></form>';    
       echo '</center>';
       require_once ('inc/admin/fin_info.php'); 
    }
    else
    {
      $colpages=10;
      $nat = abs(intval($_GET['p']));
      if (!$nat) $nat=1;
      $tr=($nat-1)*$colpages;
      
      $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_mod`;"), 0);
      require_once ('inc/head.php');
      echo '<h2>'.$lang['page_on_mod'].'</h2>';
      if($total)
      {
        $disk1= mysql_query("SELECT * FROM `wm_mod` ORDER BY `time` DESC LIMIT ".$tr.",".$colpages.";");
        while($disk = mysql_fetch_assoc($disk1))
        {
          $getnav=func('getnav',$disk['path']);
          echo '<hr/><img src="'.$parent.'themes/engine/'.$set['theme'].'/list.png" />  <a href="?do=getmod&amp;mod='.$disk['id'].'" class="list">'.out($disk['name']).'</a><br/>';
          echo $lang['razd'].' '.$getnav.'<br/>';
          echo '<small>'.$lang['created_d'].' ';
          if($disk['user_id'])
            echo '<a href="'.$parent.'?do=user&amp;us='.$disk['user_id'].'" style="font-size:small">'.$disk['user_name'].'</a>';
          else
            echo $disk['user_name'];
          echo ' '.$lang['in'].' '.date("H:i d/m/y ", $disk['time']);
          echo '<br/>'.$lang['type_e'].' '.(($disk['type'])=='new' ? $lang['new_page'] : $lang['chanes_s']);
          echo '</small>';
        }
      }
    else
    {
       echo '<hr/>'.$lang['no_mod_pg']; 
    }
    $vpage=vpage($total,'?do=modp&amp;',$colpages);
    if ($vpage)
    {
       echo '<hr/>'.$vpage;
    }
    echo '</div><hr />';
    require_once ('inc/fin.php');
   }
}
else
{
  header ('Location: ./?do=404');  
}
?>
