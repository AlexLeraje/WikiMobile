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

require_once ('inc/admin/func.php');
$act=$_GET['act'];
$text=trim($_POST['text']);
$name=trim($_POST['name']);
if (can('create_stat'))
{
   if (!isset($_POST['pred']))
   {
    $numb = file_get_contents('files/cache/page_count.dat')+1;
	$filename=func('gename',$name).'_'.$numb;
    if (file_exists($path.'/'.$filename.'.'.$requied_lang.'.txt'))
       $filename=$filename.time(); 
	if (isset($_POST['create']))
	{
      if($name)
      {
        if (substr_count($name,'|') or (substr_count($name,'/')) or (substr_count($name,"\\")) or (substr_count($name,'_')) )
        {
            $err .= $lang['depr_symb']; 
        }
        else
        {
          $wikipath=$path.'/'.$filename.'.'.$requied_lang.'.txt';
 	      if (!file_exists($wikipath))
 	      {
            $req_name = mysql_query('SELECT * FROM `wm_page_lang` WHERE `name`="' . mysql_real_escape_string($name) . '";');
            $req_name2 = mysql_query('SELECT * FROM `wm_mod` WHERE `name`="' . mysql_real_escape_string($name) . '";');  
        
 		    if (mysql_num_rows($req_name) or mysql_num_rows($req_name2))
 		      $err .= $lang['page_al_ex'].'<br/>';
 		    if(!$text)
              $err .= $lang['txt_em_ty'].'<br/>';
            if(mb_strlen($name) > 200)
              $err .= $lang['nm_bgg_200'].'<br/>';
        
            if ($rights=='admin')
            {
              if ($_POST['ch']==1) $can_edit='admin';
              else $can_edit='superuser';
            }
            else
             $can_edit='superuser';
          
            if ($_POST['com']==1)
              $use_com=1;
            else
              $use_com=0;  
 	      } 
 	      else
  	        $err .= 'file is already exists! try to change page name';
        }
      }
       else
         $err .= $lang['pg_name_empty'];
   }
   $att = intval(abs($_POST['att']));
   if (!$att)
   { 
     $att = file_get_contents('files/cache/att_count.dat') + 1;
     file_put_contents('files/cache/att_count.dat',$att);
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
   
   if (isset($_POST['load']))
   {
    if ($numfiles > 20) $numfiles=20;     //загрузка файлов
        for($i=1;$i<=$numfiles;$i++)
        {
            if ($_FILES['file'.$i.'']['name'])
            {
              $tempname=$_FILES['file'.$i.'']['tmp_name'];
              $upfile=$_FILES['file'.$i.'']['name'];
              $ext=getextension($upfile);
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
                   `page`='0',
                   `att`='".$att."',
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
if ($errfiles)
{
    if (count($errfiles) > 1)
      $co = 1;
    $err .= $lang['file_inf_1'].' <b>'.out(implode(', ',$errfiles)).'</b> '.$lang['file_inf_2'].'('.$set['max_file'].' kb)'.''.($succ ? ' '.$lang['file_ost_ld'] : '' ).'<br/>';
}
elseif(isset($_POST['load']))
    $info_mess .=  $lang['all_f_loaded'];
    
if (isset($_POST['del_attachment']))   //удаление файла
{
    $del = $_POST['del_attachment'];
    $del = intval(abs(implode('',array_flip($del))));
    if($del)
    {
      $del_file1 = mysql_query('SELECT * FROM `wm_files` WHERE `id`="'.$del.'" and `att` = "'.$att.'" LIMIT 1;');
      if (mysql_num_rows($del_file1))
      {
        $del_file = mysql_fetch_array($del_file1);
        mysql_query('DELETE FROM `wm_files` WHERE `id`="'.$del.'" and `att` = "'.$att.'" LIMIT 1;');
        if(file_exists('sourse/files/'.$del_file['filename'].'.dat'))
         unlink('sourse/files/'.$del_file['filename'].'.dat');  
        $info_mess .=  $lang['del_fl_1'].' "'.$del_file['name'].'" '.$lang['del_fl_2'];
      }
      else
      {
        $err .= $lang['cant_del_file'].'<br/>';  
      }
    }
    else
      $err .= $lang['cant_del_file'].'<br/>'; 
}
    
   if (!$err and isset($_POST['create']))
   {
     $whom = $username;
     if(!$username)
     {
       $user_id=0;
       $whom = $lang['guest_g'].'('.$lang['ip_i'].$ip.')';  
     }
     if (!mod())
	 {
       mysql_query("INSERT INTO `wm_pages` SET
        `path` = '".mysql_real_escape_string(cut_ext(cut_ext($wikipath)))."',
        `dir` = '".mysql_real_escape_string(cut_dir2(cut_ext(cut_ext($wikipath))))."',
        `time` = '".time()."',
        `id_create` = '".$user_id."',
        `user_name` = '".$whom."',
        `comments` = '".$use_com."',
        `can_edit` = '".$can_edit."';");
       $postid = mysql_insert_id();
        mysql_query("INSERT INTO `wm_page_lang` SET
        `name` = '".mysql_real_escape_string(str_replace('_',' ',$name))."',
        `dir` = '".mysql_real_escape_string(cut_dir2(cut_ext(cut_ext($wikipath))))."',
        `pid` = '".$postid."',
        `lang` = '".$requied_lang."';");
       
       mysql_query("UPDATE `wm_files` SET `page` = '".$postid."' WHERE `att` = '".$att."' AND `page` = '0';"); 
       file_put_contents($wikipath,$text);
       if (file_exists('files/cache/count.dat'))
         unlink('files/cache/count.dat');
       file_put_contents('files/cache/page_count.dat',$numb);       
	   
       $prev_id = $_SESSION['preview'];
       if ($_SESSION['whatedit']==$path_p and $prev_id)
       {
         if(file_exists('files/preview/'.$prev_id.'.ini'))
           unlink('files/preview/'.$prev_id.'.ini');
         unset($_SESSION['preview']);
         unset($_SESSION['newedit']);
         unset($_SESSION['langedit']);
         unset($_SESSION['pagename']);
         unset($_SESSION['att']);
       }
       
       require_once ('inc/admin/head_info.php');
	   echo '<b>'.$lang['pg_adde_new'].'</b></td></table><center>';
	   echo '<form style="display:inline" method="post" action="'.($mod_rewrite ? 'wiki/'.prw($name) : '?uid='.$postid).'"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';
	   require_once ('inc/admin/fin_info.php');
       exit();
    }
    else
    {
        mysql_query("INSERT INTO `wm_mod` SET
        `user_id` = '".$user_id."',
        `user_name` = '".$whom."',
        `time` = '".time()."',
        `type` = 'new',
        `name` = '".mysql_real_escape_string($name)."',
        `comments` = '".$use_com."',
        `att` = '".$att."',
        `lang` = '".$requied_lang."',
        `path` = '".cut_ext(cut_ext($wikipath))."';");
        
         $prev_id = $_SESSION['preview'];
         if ($_SESSION['whatedit']==$path_p and $prev_id)
         {
           if(file_exists('files/preview/'.$prev_id.'.ini'))
             unlink('files/preview/'.$prev_id.'.ini');
           unset($_SESSION['preview']);
           unset($_SESSION['newedit']);
           unset($_SESSION['langedit']);
           unset($_SESSION['pagename']);
           unset($_SESSION['att']);
         }
        
        $postid = mysql_insert_id();	
        file_put_contents('files/mod/'.$postid.'.ini',$text);
		require_once ('inc/admin/head_info.php');
		echo '<b>'.$lang['pg_add_on_mod'].'</b></td></table><center>';
		echo '<form style="display:inline" method="post" action="?do=sod"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';
		require_once ('inc/admin/fin_info.php');
        exit();
     }
   }
        //для предосмотра
        $prev_id = $_SESSION['preview'];
		if ($_SESSION['whatedit']==$path_p and $prev_id and file_exists('files/preview/'.$prev_id.'.ini'))
          $prev_mode = 1;
         
        require_once ('inc/head.php');
		echo '<h2>'.$lang['pg_creating'].'</h2><hr />';
		echo '</div><form name="mess" action="?do=wikicreate&amp;id='. $path_p .'&amp;act=save" method="post" enctype="multipart/form-data"><div class="stat">';
        
        if($prev_mode)
        {
          if($_SESSION['att']) 
          $att = $_SESSION['att'];  
        }
        echo '<input type="hidden" name="p" value="'.$numvl.'"/>';
        echo '<input type="hidden" name="f" value="'.$numfiles.'"/>';
        echo '<input type="hidden" name="att" value="'.$att.'"/>';
		echo '<b>'.$lang['pagename_e'].'</b><br />';
        if($prev_mode) $name = $_SESSION['pagename']; 
		echo '<input type="text" size="20" name="name" class="edit2" value="'.out($name).'" /><hr />';
		echo '<b>'.$lang['text_t'].'</b>';
		echo func('tagspanel','text').'<textarea name="text" class="edit" cols="80"  rows="15">';
		
        if ($_SESSION['whatedit']==$path_p and $prev_id and file_exists('files/preview/'.$prev_id.'.ini'))
          echo out(file_get_contents('files/preview/'.$prev_id.'.ini'));
        else
          echo out($text);
		echo '</textarea>'.func('tagspanelbt').'<hr />';
        if ($rights=='admin')
        {
          echo '<input type="checkbox" name="ch" value="1" /> '.$lang['prot_page'].'<br/>';
          echo '<input type="checkbox" name="com" value="1" checked = "checked" /> '.$lang['agree_comm'].'<hr />';
        }
    echo '<ul>';
    $count=mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_files` WHERE `att` = '".$att."' ;"), 0);
    if (!$numvl and !$downfiles)
    {
      echo '<li class="dir"><input class="edittext" name="files" type="submit" value="'.$lang['page_files'].'" /> ('.$count.')</li>';
    }
    else
    {
      echo '<li class="dir2"><input class="edittext" name="files" type="submit" value="'.$lang['page_files'].'" /> ('.$count.')</li><hr/>';
      $attach1= mysql_query("SELECT * FROM `wm_files` WHERE `att` = '".$att."' ;");
      while($attach = mysql_fetch_assoc($attach1))
      {
         $ext=getextension($attach['filename']);
         echo '<img width="16" height="16" src="'.(file_exists('sourse/ext/'.$ext.'.png') ? 'sourse/ext/'.$ext.'.png' : 'themes/admin/'.$set['theme'].'/sis.png').'" />  <input class="edit2" type="text" size="10" value="{{'.(is_image($attach['filename']) ? 'img' : 'file').':'.$attach['filename'].'|'.$attach['name'].'}}"/> ';
         if (can('create_stat'))
         {
           echo ' <a href="javascript:tag(\'\', \' {{'.(is_image($attach['filename']) ? 'img' : 'file').':'.$attach['filename'].'|'.$attach['name'].'}} \')">'.$lang['s_paste'].'</a>';
           echo ' <input type="submit" class="edittext" style="font-weight: normal;" name="del_attachment['.$attach['id'].']" value="'.$lang['s_del_l'].'"/>';
         }
         echo '- <b><a href="?do=fileinfo&amp;file='.$attach['id'].'">'.$attach['name'].'</a></b><br />';
      }
      
        echo '<hr/>';
    }
if (can('create_stat'))
{
    if (!$numfiles or $downfiles) echo '<li class="dir"><input class="edittext" name="addfiles" type="submit" value="'.$lang['add_files'].'" /></li>';
    else
    {
      echo '<li class="dir2"><input class="edittext" name="addfiles" type="submit" value="'.$lang['add_files'].'" /></li><hr/>';
        for($i=1;$i<=$numfiles;$i++)
        {
            echo '<input class="edit2" size="15" type="file" name="file'.$i.'" /><br />';
        }
        echo '<input type="submit" name="load" class="edit" value="'.$lang['load_f'].'" /> <input type="submit" class="edittext" name="else"  value="'.$lang['more_r'].'" />';
        echo '<hr />';
    }
}
echo '</ul>';
        
		echo '</div><div class="add"><input type="submit" name="create" value="'.$lang['create'].'" class="edit" /> <input type="submit" name="pred" class="edit" value="'.$lang['preview'].'" />';
		echo '</div></form>';
		echo '<hr />';
		echo '<a href="?id='. $path_p .'"><img src="themes/engine/'.$set['theme'].'/up.png" /> '.$lang['back'].'</a><br />';
        echo '<a href="./?do=smiles"><img src="'.$parent.'themes/engine/'.$set['theme'].'/sm.png" /> '.$lang['smiles'].'</a><br />';
		require_once ('inc/fin.php');

  }
  elseif(can('create_stat'))
  {  
      //Предосмотр   
   $text=trim($_POST['text']);
   if (true)
   {
     if ($_SESSION['preview'] and file_exists('files/preview/'.$_SESSION['preview'].'.ini'))
       $id_view=intval(abs($_SESSION['preview']));
     else
       $id_view=rand(100000000,9999999999);
     file_put_contents('files/preview/'.$id_view.'.ini',$text);
     $_SESSION['preview']=$id_view;
     $_SESSION['newedit']=$path_p;
     $_SESSION['langedit']=$stat_lang;
     $_SESSION['pagename']=$name;
     $_SESSION['page_create']=1;
     if($att) $_SESSION['att']=$att; 
     
      
     header('Location: ./?do=newprev&id='.$path_p.'&edit='.$id_view);
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
  }
else
{
	header ('Location: ./?do=404');
}
?>