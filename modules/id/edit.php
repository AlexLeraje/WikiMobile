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
  
      if($use_not_st_lang)
      $mess_err = $lang['its_trans_vers'].' <a href="'.$parent.''.($mod_rewrite ? 'wiki/'.prw(($curr_name ? $curr_name : $wikipage['name'])) : '?uid='. $id).'"> '.out(($curr_name ? $curr_name : $wikipage['name'])).' </a> ';
  
  if (!isset($_POST['pred']))
  {
    require_once ('inc/admin/func.php');
    $action=$_GET['act'];
    if($rights=='admin' and isset($_POST['save']))
    {
      $protected=$_POST['protect'];         
      if ($protected==1)
        $protected='admin';
      else
         $protected='superuser';
      mysql_query("UPDATE `wm_pages` SET `can_edit` = '".$protected."' WHERE `id` = '".$id."';");   // защищенная/незащищенная страница
      
      $agree_com = $_POST['agree_com'];
      if($agree_com)
        mysql_query("UPDATE `wm_pages` SET `comments` = '1' WHERE `id` = '".$id."';");
      else
        mysql_query("UPDATE `wm_pages` SET `comments` = '0' WHERE `id` = '".$id."';"); 
      unset($ini_set);
      unset($fput); 
   
      
      
   }
   if (can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin') and isset($_POST['save']))
   {
     $whom=$username;
     if (!$whom)
     {
       $whom=out($lang['guest_g'].'('.$lang['ip_i'].$ip.')');
       $user_id = 0;
     }
     $text=trim($_POST['text']);
     $page_name = trim($_POST['name']);
     
     if(file_exists($wikipage['path'].'.'.$stat_lang.'.txt'))
       $tmp_tt_text = file_get_contents($wikipage['path'].'.'.$stat_lang.'.txt');
     
     if(!$text)
       $err .= $lang['txt_em_ty'].'<br/>';
     elseif($text != $tmp_tt_text and $text)
     {
       if(file_exists($wikipage['path'].'.'.$stat_lang.'.hist_count.dat'))
         $hist_numb = file_get_contents($wikipage['path'].'.'.$stat_lang.'.hist_count.dat');
       else
         $hist_numb = 1;
       
       if(file_exists($wikipage['path'].'.'.$stat_lang.'.txt'))
       {  
         $old_file= file_get_contents($wikipage['path'].'.'.$stat_lang.'.txt');
         $zap_rous = mysql_result(mysql_query('SELECT COUNT(*) FROM `wm_history` WHERE `page`="'.$id.'" AND `lang` = "'.$stat_lang.'" AND `file`="'.$hist_numb.'";'), 0);
         if($zap_rous <= 100)
         {
           if(!$zap_rous)
             file_put_contents($wikipage['path'].'.'.$stat_lang.'.arh.'.$hist_numb.'.dat',gz_pack($old_file));  
           else
           {
             $back_file= file_get_contents($wikipage['path'].'.'.$stat_lang.'.arh.'.$hist_numb.'.dat');
             file_put_contents($wikipage['path'].'.'.$stat_lang.'.arh.'.$hist_numb.'.dat',$back_file.'||____||'.gz_pack($old_file));   
           }
           mysql_query("INSERT INTO `wm_history` SET
          `page` = '".$id."',
          `user_id` = '".$user_id."',
          `username` = '".$whom."',
          `type` = '1',
          `time` = '".time()."',
          `file` = '".$hist_numb."',
          `lang` = '".$stat_lang."',
          `numb` = '".($zap_rous+1)."' ;");
         }
         else
         {
           $hist_numb = $hist_numb+1;
           file_put_contents($wikipage['path'].'.'.$stat_lang.'.hist_count.dat',$hist_numb);
           file_put_contents($wikipage['path'].'.'.$stat_lang.'.arh.'.$hist_numb.'.dat',gz_pack($old_file));
           mysql_query("INSERT INTO `wm_history` SET
          `page` = '".$id."',
          `user_id` = '".$user_id."',
          `username` = '".$whom."',
          `type` = '1',
          `time` = '".time()."',
          `file` = '".$hist_numb."',
          `lang` = '".$stat_lang."',
          `numb` = '1' ;");  
         }
       }  
       file_put_contents($wikipage['path'].'.'.$stat_lang.'.txt',$text); //перезаписываем основной файл
       unset($_SESSION['preview']);   //удаляем из сессии запись о предосмотре
       unset($_SESSION['whatedit']);  
       unset($_SESSION['langedit']);
       if (file_exists('files/preview/'.$_SESSION['preview'].'.'.$stat_lang.'.ini'))  //удаляем файл предосмотра
         unlink('files/preview/'.$_SESSION['preview'].'.'.$stat_lang.'.ini');
       if (file_exists($wikipage['path'].'.'.$stat_lang.'.temp.dat')) //удаляем кэш 
         unlink($wikipage['path'].'.'.$stat_lang.'.temp.dat');
       
       mysql_query("UPDATE `wm_pages` SET
        `last_edit` = '".time()."',
        `id_edit` = '".$user_id."',
        `lang_edit` = '".$stat_lang."',
        `user_edit` = '".mysql_real_escape_string($whom)."'
        WHERE `id` = '".$id."'");  //записываем в базу, что были изменения и кем произведены
     }
     elseif(mb_strtolower($page_name) == mb_strtolower($wikipage['name']) and $protected == $wikipage['can_edit'] and $agree_com == $wikipage['comments'])
       $err .= $lang['noth_changed'].'<br/>'; 
     if ($page_name)
     {
    	if(substr_count($name,'|') or (substr_count($name,'/')) or (substr_count($name,"\\")) or (substr_count($name,'_')))
    	  $err .= $lang['depr_symb'].'<br/>';
    	else
    	{
    	  $to_name=$page_name;
    	  if (mb_strtolower($to_name) != mb_strtolower($wikipage['name']))
    	  {
            if(mb_strlen($page_name) > 200)
              $err .= $lang['nm_bgg_200'].'<br/>';
            else
            {
              $req_name = mysql_query('SELECT * FROM `wm_page_lang` WHERE `name`="' . mysql_real_escape_string($to_name) . '";');
              $req_name2 = mysql_query('SELECT * FROM `wm_mod` WHERE `name`="' . mysql_real_escape_string($to_name) . '";');
              if(mysql_num_rows($req_name) or mysql_num_rows($req_name2))
 		 	    $err .= $lang['page_al_ex'].'<br/>';
    	  	  else
              {
                $req_ln = mysql_query('SELECT * FROM `wm_page_lang` WHERE `pid`="'.$id.'" AND `lang` = "'.$stat_lang.'";');  
                if(mysql_num_rows($req_ln))
                   mysql_query("UPDATE `wm_page_lang` SET `name` = '".mysql_real_escape_string($to_name)."' WHERE `pid` = '".$id."' AND `lang` = '".$stat_lang."';");  
                else
                   mysql_query("INSERT INTO `wm_page_lang` SET `name` = '".mysql_real_escape_string($to_name)."', `pid` = '".$id."', `lang` = '".$stat_lang."', `dir` = '".$wikipage['dir']."' ;");
              }
            }
    	  }
        }
     }
     else
     {
       $err .= $lang['pg_name_em'].'<br/>';
     }
     if (!$err) $info_mess .=  $lang['ch_saved'];
  }
  
$numvl=abs(intval($_POST['p']));
$numfiles=abs(intval($_POST['f']));  
if(isset($_POST['files']))
{
  if($numvl)
    unset($numvl);
  else
    $numvl = 1;  
}
if(isset($_POST['addfiles']))
{
  if($numfiles)
    unset($numfiles);
  else
    $numfiles = 2;  
}
if(isset($_POST['else']))
{
  $numfiles = $numfiles+2;  
}
if (can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin') and isset($_POST['load']))
{
	if ($numfiles > 20) $numfiles=20;     //загрузка файлов
		$succ = 0;
        for($i=1;$i<=$numfiles;$i++)
		{
			if ($_FILES['file'.$i.'']['name'])
			{
			  $tempname=$_FILES['file'.$i.'']['tmp_name'];
			  $upfile=$_FILES['file'.$i.'']['name'];
			  $ext=mb_strtolower(getextension($upfile));
			  if ($ext)
			  {
                if(ceil($_FILES['file'.$i.'']['size']/1024) < $set['max_file'])
                {  
			  	  $file_count=file_get_contents('files/cache/files_count.dat')+1;
                  file_put_contents('files/cache/files_count.dat',$file_count);
                  $namefile = func('gename',cut_ext($upfile)).'_'.$file_count;
				  move_uploaded_file($tempname, 'sourse/files/'.$namefile.'.'.$ext.'.dat');
                  mysql_query("INSERT INTO `wm_files` SET
                   `name`='" . mysql_real_escape_string(out($upfile)) . "',
                   `filename`='" . mysql_real_escape_string(out($namefile.'.'.$ext)) . "',
                   `page`='" . $id . "',
                   `time`='" . time() . "',
                   `view`='0';");
			      unset($tempname);
			      unset($upfile);
                  $succ+1;
                }
                else
                {
                  $errfiles[]=$_FILES['file'.$i.'']['name'];  
                }
			  }
			  else
			  {
			  	$errfiles[]=$_FILES['file'.$i.'']['name'];
			  }
		  }
		}
}

if (isset($_POST['del_attachment']) and can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))   //удаление файла
{
    $del = $_POST['del_attachment'];
    $del = intval(abs(implode('',array_flip($del))));
    if($del)
    {
      $del_file1 = mysql_query('SELECT * FROM `wm_files` WHERE `id`="'.$del.'" and `page` = "'.$id.'" LIMIT 1;');
      if (mysql_num_rows($del_file1))
      {
        $del_file = mysql_fetch_array($del_file1);
        mysql_query('DELETE FROM `wm_files` WHERE `id`="'.$del.'" and `page` = "'.$id.'" LIMIT 1;');
        if(file_exists('sourse/files/'.$del_file['filename'].'.dat'))
         unlink('sourse/files/'.$del_file['filename'].'.dat');  
	    $info_mess .=  $lang['del_fl_1'].' "'.$del_file['name'].'" '.$lang['del_fl_2'].'<br/>';
      }
      else
      {
        $err .= $lang['cant_del_file'].'<br/>';  
      }
    }
    else
      $err .= $lang['cant_del_file'].'<br/>'; 
}
if ($errfiles and can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
{
    if (count($errfiles) > 1)
      $co = 1;
    $err .= $lang['file_inf_1'].' <b>'.out(implode(', ',$errfiles)).'</b> '.$lang['file_inf_2'].' ('.$set['max_file'].' kb)'.''.($succ ? ' '.$lang['file_ost_ld'] : '' ).'<br/>';
}
elseif(isset($_POST['load']) and can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
	$info_mess .=  $lang['all_f_loaded'];

$wikipage1 = mysql_query("SELECT * FROM `wm_pages` LEFT JOIN `wm_page_lang` ON `wm_pages`.`id`=`wm_page_lang`.`pid` AND `wm_page_lang`.`lang` = '".$stat_lang."' WHERE `wm_page_lang`.`pid` IS NOT NULL AND `wm_pages`.`id` = '".$id."' LIMIT 1;");
if(!mysql_num_rows($wikipage1))
  $wikipage1= mysql_query("SELECT * FROM `wm_pages` LEFT JOIN `wm_page_lang` ON `wm_pages`.`id`=`wm_page_lang`.`pid` WHERE `wm_pages`.`id` = '".$id."' ORDER BY `wm_page_lang`.`id` LIMIT 1;");
$wikipage = mysql_fetch_array($wikipage1);

if($use_not_st_lang)
   $mess_err = $lang['its_trans_vers'].' <a href="'.($mod_rewrite ? 'wiki/'.prw(($curr_name ? $curr_name : $wikipage['name'])) : '?uid='. $id).'"> '.out(($curr_name ? $curr_name : $wikipage['name'])).' </a> ';

   
     if ($user_id)
    {
      // Фиксация факта прочтения
      $req = mysql_query("SELECT * FROM `wm_page_view` WHERE `page` = '".$id."' AND `userid` = '".$user_id."' LIMIT 1");
      if (mysql_num_rows($req) > 0)
      {
        $res = mysql_fetch_assoc($req);
        if ($wikipage['last_edit'] > $res['time'])
          mysql_query("UPDATE `wm_page_view` SET `time` = '".time()."' WHERE `page`='".$id."' AND `userid` = '".$user_id."'");
      }
      else
      {
        // Ставим метку о прочтении
        mysql_query("INSERT INTO `wm_page_view` SET  `page` = '".$id."', `userid` = '".$user_id."', `time` = '".time()."'");
      }
    }  
   
require_once ('inc/head.php');
echo '<h2>'.((can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin')) ? $lang['editing'] : $lang['sour_code']).' "'.out($wikipage['name']).'"</h2><hr />';
if (!can('edit_stat')) echo $lang['pg_for_no_ed'];
elseif(can('edit_stat') and $wikipage['can_edit']=='admin' and $rights!='admin') echo $lang['pg_pr_by_ad']; 
echo '</div><form name="mess" action="?uid='.$id.'&amp;do=edit&amp;act=save'.($_GET['lang'] ? '&amp;lang='.$stat_lang : '').'" method="post" enctype="multipart/form-data"><div class="stat">';
echo '<input type="hidden" name="p" value="'.$numvl.'"/>';
echo '<input type="hidden" name="f" value="'.$numfiles.'"/>';
if (can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
{
    $tmp_nm = trim($_POST['name']);
    echo '<b>'.$lang['name_for_ot'].'</b><br /><input tupe="text" name="name" class="edit2" value="'.($tmp_nm ? out($tmp_nm) : out($wikipage['name'])).'" /> <hr/>';
}
echo '<b>'.$lang['page_text_t'].' </b>';
if(can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
 echo func('tagspanel','text');
echo '<textarea name="text" class="edit" cols="40"  rows="15">';
$tmp_txt = trim($_POST['text']);

if($tmp_txt)
   echo out($tmp_txt); 
elseif ($id_view and $id_view==$_SESSION['preview'] and file_exists('files/preview/'.$id_view.'.ini'))
   echo out(file_get_contents('files/preview/'.$id_view.'.ini'));
else
{
  if(file_exists($wikipage['path'].'.'.$stat_lang.'.txt'))  
   echo out(file_get_contents($wikipage['path'].'.'.$stat_lang.'.txt'));
}
echo '</textarea>';
if(can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
  echo func('tagspanelbt');
if (can('edit_stat') and $rights=='admin')
{
   echo '<input type="checkbox" name="protect" value="1" '.(($wikipage['can_edit']=='admin') ? 'checked="checked"' : '').' /> '.$lang['prot_page'].'<br />';    
   echo '<input type="checkbox" name="agree_com" value="1" '.(($wikipage['comments']==1) ? 'checked="checked"' : '').' " /> '.$lang['agree_comm'].'<hr />'; 
}

	echo '<ul>';
    $count=mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_files` WHERE `page` = '".$id."' ;"), 0);
	if (!$numvl and !$downfiles)
	{
	  echo '<li class="dir"><input class="edittext" name="files" type="submit" value="'.$lang['page_files'].'" /> ('.$count.')</li>';
	}
	else
	{
	  echo '<li class="dir2"><input class="edittext" name="files" type="submit" value="'.$lang['page_files'].'" /> ('.$count.')</li><hr/>';
      $att1= mysql_query("SELECT * FROM `wm_files` WHERE `page` = '".$id."' ;");
      while($att = mysql_fetch_assoc($att1))
      {
	     $ext=getextension($att['filename']);
         echo '<img width="16" height="16" src="'.(file_exists('sourse/ext/'.$ext.'.png') ? 'sourse/ext/'.$ext.'.png' : 'themes/admin/'.$set['theme'].'/sis.png').'" />  <input class="edit2" type="text" size="10" value="{{'.(is_image($att['filename']) ? 'img' : 'file').':'.$att['filename'].'|'.$att['name'].'}}"/> ';
         if (can('edit_stat'))
         {
           echo ' <a href="javascript:tag(\'\', \' {{'.(is_image($att['filename']) ? 'img' : 'file').':'.$att['filename'].'|'.$att['name'].'}} \')">'.$lang['s_paste'].'</a>';
           echo ' <input type="submit" class="edittext" style="font-weight: normal;" name="del_attachment['.$att['id'].']" value="'.$lang['s_del_l'].'"/>';
         }
         echo '- <b><a href="?do=fileinfo&amp;file='.$att['id'].'">'.cut_filename($att['name']).'</a></b><br />';
      }
		echo '<hr/>';
	}
if (can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
{
	if (!$numfiles or $downfiles) echo '<li class="dir"><input class="edittext" name="addfiles" type="submit" value="'.$lang['add_files'].'" /></li>';
	else
	{
	  echo '<li class="dir2"><input class="edittext" name="addfiles" type="submit" value="'.$lang['add_files'].'" /></li><hr/>';
		for($i=1;$i<=$numfiles;$i++)
		{
			echo '<input class="edit2" size="15" type="file" name="file'.$i.'" /><br />';
		}
		echo '<input type="submit" name="load" class="edit" value="'.$lang['load_f'].'" /> <input type="submit" class="edittext" name="else" value="'.$lang['more_r'].'" />';
		echo '<hr />';
	}
}
echo '</ul>';
echo '</div>';
if (can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
  echo '<div class="add"><input type="submit" name="save" class="edit" value="'.$lang['save'].'" /> <input type="submit" name="pred" class="edit" value="'.$lang['preview'].'" /></div>';
echo '</form>';
echo '<hr />';
echo '<a href="'.($mod_rewrite ? 'wiki/'.prw(($curr_name ? $curr_name : $wikipage['name'])).($use_not_st_lang ? '/'.$stat_lang : '') : '?uid='. $id.($use_not_st_lang ? '&amp;lang='.$stat_lang : '')).'"><img src="themes/engine/'.$set['theme'].'/up.png" /> '.$lang['to_page'].'</a><br />';
echo '<a href="./?do=smiles"><img src="'.$parent.'themes/engine/'.$set['theme'].'/sm.png" /> '.$lang['smiles'].'</a><br />';
require_once ('inc/fin.php');
}
elseif(can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
{
   $text=trim($_POST['text']);
   if (!file_exists($wikipage['path'].'.'.$stat_lang.'.txt') or file_get_contents($wikipage['path'].'.'.$stat_lang.'.txt')!=$text)
   {
     if ($_SESSION['preview'] and file_exists('files/preview/'.$_SESSION['preview'].'.ini'))
       $id_view=$_SESSION['preview'];
     else
       $id_view=rand(100000000,9999999999);
     file_put_contents('files/preview/'.$id_view.'.ini',$text);
     $_SESSION['preview']=$id_view;
     $_SESSION['whatedit']=$id;
     $_SESSION['langedit']=$stat_lang; 
     header('Location: ./?do=preview'.($use_not_st_lang ? '&lang='.$stat_lang : '').'&uid='.$id.'&edit='.$id_view);
     exit();
   }
   else
   {
    require_once ('inc/admin/head_info.php');
    echo '<b>'.$lang['error_imp'].'</b><br/>'.$lang['y_not_chang'].'</td></table><center>';
    echo '<form style="display:inline" method="post" action="?do=edit'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;uid='.$id.'"><input type="submit" class="edit" value="'.$lang['back'].'" /></a></center>';    
    require_once ('inc/admin/fin_info.php');
    exit(); 
   }
}
else
{
  header ('Location: ./?do=404'); 
}
?>
