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

function ch($string1,$string2)
{
	if ($string1==$string2) return 'checked="checked"';
	else return '';
}

require_once ('inc/admin/func.php');

if ($rights=='admin')
{
  $act=$_GET['act'];
  if (!$act)
  {
  	require_once ('inc/admin/head.php');
    echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['wiki_set'].'</div>';
    echo '<div class="elem"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/sis.png" /> <a href="?do=config&act=base">'.$lang['baze_set'].'</a></div>';
    echo '<div class="elem"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/locale.png" /> <a href="?do=config&act=lang">'.$lang['lang_rul'].'</a></div>';
    echo '<div class="elem"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/sm.png" /> <a href="?do=config&act=smiles">'.$lang['sm_set'].'</a></div>';
    echo '<div class="elem"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/exit.png" /> <a href="?do=config&act=modp">'.$lang['adm_p_mod'].'</a></div>';
    echo '<div class="elem"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/dost.png" /> <a href="?do=config&act=rights">'.$lang['access_rig'].'</a></div>';
    echo '<div class="helem">&nbsp;</div>';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
    require_once ('inc/admin/fin.php');
  }
  elseif($act=='modp')
  {
  	require_once ('inc/admin/head.php');
  	echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['adm_p_mod'].'</div>';
  	echo '<form action="?do=config&act=modp_true" method="post">';
  	echo '<div class="elem"><input type="radio" '.ch($set['mod'],'nomod').' name="mod" value="nomod"><b>'.$lang['mod_off'].'</b></div>';
  	echo '<div class="elem">'.$lang['mod_on'].' <hr />';
  	echo '<input type="radio" name="mod" '.ch($set['mod'],'guest').' value="guest">'.$lang['guest_r'].'<br />';
  	echo '<input type="radio" name="mod" '.ch($set['mod'],'user').' value="user">'.$lang['us_and_low'].'<br />';
  	echo '<input type="radio" name="mod" '.ch($set['mod'],'superuser').' value="superuser">'.$lang['sup_and_low'].'<br />';
    echo '</div>';
  	echo '<div class="elem"><input type="submit" value="'.$lang['save'].'" class="edit"></div>';
    echo '<div class="helem">&nbsp;</div>';
    echo '<a class="url" href="?do=config">'.$lang['wiki_set'].'</a><br />';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
    require_once ('inc/admin/fin.php');
  }
  elseif($act=='modp_true')
  {
   $mod_array=array('nomod','guest','user','superuser','moder');
   if(in_array($_POST['mod'],$mod_array))
   {
     mysql_query("UPDATE `wm_settings` SET `value` = '".$_POST['mod']."' WHERE `key` = 'mod'");  
     require_once ('inc/admin/head_info.php');
     echo '<b>'.$lang['set_saved'].'</b></td></table><center>';
     echo '<form style="display:inline" method="post" action="?do=config"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';
     require_once ('inc/admin/fin_info.php');
   }
   else
   {
      admer($lang['rig_wrong']); 
   }
  }
  elseif($act=='usadm')
  {
  	require_once ('inc/admin/head.php');
  	echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['users_adm'].'</div>';
    echo '<div class="elem"><a href="?do=config&act=user"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/members.gif" /> '.$lang['sp_users'].'</a></div>';
  	echo '<div class="elem"><a href="?do=config&act=newuser"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/plus.gif" /> '.$lang['cr_user'].'</a></div>';
    echo '<div class="helem">&nbsp;</div>';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
    require_once ('inc/admin/fin.php');
  }
  elseif($act=='base')
  {
  	require_once ('inc/admin/head.php');
  	echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['baze_set'].'</div>';
  	echo '<form action="?do=config&act=sbase" method="post">';
  	echo '<div class="elem"><b>'.$lang['site_url'].'</b><br />';
    echo '<input type="text" size="20" name="site" class="edit2" value="'.$set['site'].'"></div>';
  	echo '<div class="elem"><b>'.$lang['full_url'].'</b> '.$lang['full_notice'].'<br />';
    echo '<input type="text" size="20" name="url" class="edit2" value="'.$set['url'].'"></div>';
    echo '<div class="elem"><b>'.$lang['header'].'</b><br />';
    echo '<input type="text" size="20" name="head" class="edit2" value="'.$set['head'].'"></div>';
    echo '<div class="elem"><b>'.$lang['theme'].'</b><br />';
    echo '<select name="theme">';
    $dir = opendir ('themes/engine');
      while ($file = readdir($dir))
      {
        if (( $file != ".") && ($file != ".."))
        {
           echo '<option value='.$file.' >'.$file.'</option>';
        }
     }
    closedir ($dir);    
    echo '</select></div>';
    echo '<div class="elem"><b>'.$lang['adm_theme'].'</b><br />';
    echo '<select name="admintheme">';
    $dir = opendir ('themes/admin');
      while ( $file = readdir ($dir))
      {
         if (( $file != ".") && ($file != ".."))
        {
           echo '<option value='.$file.' >'.$file.'</option>';
        }
     }
    closedir ($dir);    
    echo '</select></div>';
    echo '<div class="elem"><b>'.$lang['elem_saerch'].'</b><br />';
    echo '<input type="text" size="20" name="search_on_page" class="edit2" value="'.$set['search_on_page'].'"></div>';
    echo '<div class="elem"><b>'.$lang['elem_history'].'</b><br />';
    echo '<input type="text" size="20" name="history_on_page" class="edit2" value="'.$set['history_on_page'].'"></div>';
    echo '<div class="elem"><b>'.$lang['elem_dissc'].'</b><br />';
    echo '<input type="text" size="20" name="disscusion_on_page" class="edit2" value="'.$set['disscusion_on_page'].'"></div>';
    echo '<div class="elem"><b>'.$lang['elem_wiki'].'</b><br />';
    echo '<input type="text" size="20" name="symbols" class="edit2" value="'.$set['symbols'].'"></div>';
    echo '<div class="elem"><b>'.$lang['max_file'].'</b><br />';
    echo '<input type="text" size="20" name="max_file" class="edit2" value="'.$set['max_file'].'"></div>';
    echo '<div class="elem"><b>'.$lang['reg_g'].'</b><br />';
    echo '<input type="radio" name="reg" '.ch($set['reg'],'noreg').' value="noreg"> '.$lang['reg_off'].'<br />';
    echo '<input type="radio" name="reg" '.ch($set['reg'],'regon').' value="regon"> '.$lang['allow_to_all'].'<hr />';
    echo '<input type="checkbox" name="mail_reg" value="1" '.($set['mail_reg'] ? 'checked="checked"' : '').'/>'.$lang['reg_by_mail'].'<br />';
    echo '</div>';
    
    echo '<div class="elem"><b>'.$lang['index_show'].'</b><br />';
    echo '<input type="radio" name="show_sod" '.ch($set['show_sod'],'1').' value="1"> '.$lang['index_show_sod'].'<br />';
    echo '<input type="radio" name="show_sod" '.ch($set['show_sod'],'0').' value="0"> '.$lang['index_show_ind'].'<br />';
    echo '</div>';
    
    echo '<div class="elem"><b>'.$lang['sort_sod'].'</b><br />';
    echo '<input type="radio" name="sort_sod" '.ch($set['sort_sod'],'1').' value="1"> '.$lang['sort_sod_by_alp'].'<br />';
    echo '<input type="radio" name="sort_sod" '.ch($set['sort_sod'],'0').' value="0"> '.$lang['sort_sod_by_dat'].'<hr />';
    echo '<input type="checkbox" name="obr_sort" value="1" '.($set['obr_sort'] ? 'checked="checked"' : '').'/>'.$lang['sort_sod_by_obr'].'<br />';
    echo '</div>';

    echo '<div class="elem"><b>'.$lang['key_words'].'</b><br />';
    echo '<textarea class="edit" name="key_words" cols="30" rows="3">'.$set['key_words'].'</textarea>';
    echo '</div>';
    echo '<div class="elem"><b>'.$lang['site_descr'].'</b><br />';
    echo '<textarea class="edit" name="site_descr" cols="30" rows="3">'.$set['site_descr'].'</textarea>';
    echo '</div>';
    
    echo '<div class="elem"><input type="submit" value="'.$lang['save'].'" class="edit"></div>';
    echo '</form>';
    echo '<div class="helem">&nbsp;</div>';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
    require_once ('inc/admin/fin.php');
  }
  elseif($act=='sbase')
  {
    $set_site=htmlentities(trim($_POST['site']), ENT_QUOTES, 'UTF-8');
    if ($set_site != $set['site'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".mysql_real_escape_string($set_site)."' WHERE `key` = 'site'");
     
    $set_theme=htmlentities(trim($_POST['theme']), ENT_QUOTES, 'UTF-8');
    if ($set_theme != $set['theme'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".mysql_real_escape_string($set_theme)."' WHERE `key` = 'theme'");  
  
    $set_admintheme=htmlentities(trim($_POST['admintheme']), ENT_QUOTES, 'UTF-8');
    if ($set_admintheme != $set['admintheme'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".mysql_real_escape_string($set_admintheme)."' WHERE `key` = 'admintheme'");
     
    $set_reg=trim($_POST['reg']);
    $set_reg_arr=array('noreg','regon');
    if ($set_reg != $set['reg'] and in_array($set_reg, $set_reg_arr))
       mysql_query("UPDATE `wm_settings` SET `value` = '".$set_reg."' WHERE `key` = 'reg'"); 
            
    $set_url=htmlentities(trim($_POST['url']), ENT_QUOTES, 'UTF-8');
    if ($set_url != $set['url'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".mysql_real_escape_string($set_url)."' WHERE `key` = 'url'"); 
 
    $set_head=htmlentities(trim($_POST['head']), ENT_QUOTES, 'UTF-8');
    if ($set_head != $set['head'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".mysql_real_escape_string($set_head)."' WHERE `key` = 'head'"); 
  
    $set_search_on_page=intval(abs(trim($_POST['search_on_page'])));
    if ($set_search_on_page != $set['search_on_page'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".$set_search_on_page."' WHERE `key` = 'search_on_page'");  
  
    $set_history_on_page=intval(abs(trim($_POST['history_on_page'])));
    if ($set_history_on_page != $set['history_on_page'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".$set_history_on_page."' WHERE `key` = 'history_on_page'");
     
    $set_disscusion_on_page=intval(abs(trim($_POST['disscusion_on_page'])));
    if ($set_disscusion_on_page != $set['disscusion_on_page'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".$set_disscusion_on_page."' WHERE `key` = 'disscusion_on_page'");
     
    $set_symbols=intval(abs(trim($_POST['symbols'])));
    if ($set_symbols != $set['symbols'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".$set_symbols."' WHERE `key` = 'symbols'");          
    
    $set_show_sod=intval(abs(trim($_POST['show_sod'])));
    if ($set_show_sod != $set['show_sod'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".$set_show_sod."' WHERE `key` = 'show_sod'");
   
    $set_sort_sod=intval(abs(trim($_POST['sort_sod'])));
    if ($set_sort_sod != $set['sort_sod'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".$set_sort_sod."' WHERE `key` = 'sort_sod'");
    
    $set_obr_sort=intval(abs(trim($_POST['obr_sort'])));
    if ($set_obr_sort != $set['obr_sort'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".$set_obr_sort."' WHERE `key` = 'obr_sort'");
    
    $set_key_words=htmlentities(trim($_POST['key_words']), ENT_QUOTES, 'UTF-8');
    if ($set_key_words != $set['key_words'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".mysql_real_escape_string($set_key_words)."' WHERE `key` = 'key_words'");

    $set_site_descr=htmlentities(trim($_POST['site_descr']), ENT_QUOTES, 'UTF-8');
    if ($set_site_descr != $set['site_descr'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".mysql_real_escape_string($set_site_descr)."' WHERE `key` = 'site_descr'");
    
    $set_mail_reg=intval(abs(trim($_POST['mail_reg'])));
    if ($set_mail_reg != $set['mail_reg'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".$set_mail_reg."' WHERE `key` = 'mail_reg'");
    
    $set_max_file=intval(abs(trim($_POST['max_file'])));
    if ($set_max_file != $set['max_file'])
       mysql_query("UPDATE `wm_settings` SET `value` = '".$set_max_file."' WHERE `key` = 'max_file'");
       
   require_once ('inc/admin/head_info.php');
   echo '<b>'.$lang['set_saved'].'</b></td></table><center>';
   echo '<form style="display:inline" method="post" action="?do=config&act=base"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';
   require_once ('inc/admin/fin_info.php');
  }
  elseif($act=='rights')
  {
  	$dr=parse_ini_file($parent.'files/rights.ini');
  	require_once ('inc/admin/head.php');
  	echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['access_rig'].'</div>';
  	echo '<form action="?do=config&act=srights" method="post">';
    echo '<div class="elem"><b>'.$lang['cr_stats'].'</b><hr />';
    echo '<input type="radio" name="create_stat" value="admin" '.ch($dr['create_stat'],'admin').'> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="create_stat" value="moder" '.ch($dr['create_stat'],'moder').'> '.$lang['on_moders'].'<br />';    
    echo '<input type="radio" name="create_stat" value="superuser" '.ch($dr['create_stat'],'superuser').'> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="create_stat" value="user" '.ch($dr['create_stat'],'user').'> '.$lang['on_users'].'<br />';
  	echo '<input type="radio" name="create_stat" value="guest" '.ch($dr['create_stat'],'guest').'> '.$lang['on_guests'].'<br /></div>';
  	
    echo '<div class="elem"><b>'.$lang['ed_stats'].'</b><hr />';
    echo '<input type="radio" name="edit_stat" value="admin" '.ch($dr['edit_stat'],'admin').'> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="edit_stat" value="moder" '.ch($dr['edit_stat'],'moder').'> '.$lang['on_moders'].'<br />';  
    echo '<input type="radio" name="edit_stat" value="superuser" '.ch($dr['edit_stat'],'superuser').'> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="edit_stat" value="user" '.ch($dr['edit_stat'],'user').'> '.$lang['on_users'].'<br />';
  	echo '<input type="radio" name="edit_stat" value="guest" '.ch($dr['edit_stat'],'guest').'> '.$lang['on_guests'].'<br /></div>';
  	
    echo '<div class="elem"><b>'.$lang['can_del_mess'].'</b><hr />';
    echo '<input type="radio" name="delete_message" value="admin" '.ch($dr['delete_message'],'admin').'> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="delete_message" value="moder" '.ch($dr['delete_message'],'moder').'> '.$lang['on_moders'].'<br />';  
    echo '<input type="radio" name="delete_message" value="superuser" '.ch($dr['delete_message'],'superuser').'> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="delete_message" value="user" '.ch($dr['delete_message'],'user').'> '.$lang['on_users'].'<br />';
  	echo '<input type="radio" name="delete_message" value="guest" '.ch($dr['delete_message'],'guest').'> '.$lang['on_guests'].'<br /></div>';
  	
    echo '<div class="elem"><b>'.$lang['can_del_stats'].'</b><hr />';
    echo '<input type="radio" name="delete_stat" value="admin" '.ch($dr['delete_stat'],'admin').'> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="delete_stat" value="moder" '.ch($dr['delete_stat'],'moder').'> '.$lang['on_moders'].'<br />';  
    echo '<input type="radio" name="delete_stat" value="superuser" '.ch($dr['delete_stat'],'superuser').'> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="delete_stat" value="user" '.ch($dr['delete_stat'],'user').'> '.$lang['on_users'].'<br />';
  	echo '<input type="radio" name="delete_stat" value="guest" '.ch($dr['delete_stat'],'guest').'> '.$lang['on_guests'].'<br /></div>';
  	
    echo '<div class="elem"><b>'.$lang['can_cr_raz'].'</b><hr />';
    echo '<input type="radio" name="add_dir" value="admin" '.ch($dr['add_dir'],'admin').'> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="add_dir" value="moder" '.ch($dr['add_dir'],'moder').'> '.$lang['on_moders'].'<br />';  
    echo '<input type="radio" name="add_dir" value="superuser" '.ch($dr['add_dir'],'superuser').'> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="add_dir" value="user" '.ch($dr['add_dir'],'user').'> '.$lang['on_users'].'<br />';
  	echo '<input type="radio" name="add_dir" value="guest" '.ch($dr['add_dir'],'guest').'> '.$lang['on_guests'].'<br /></div>';
  	
    echo '<div class="elem"><b>'.$lang['can_del_raz'].'</b><hr />';
    echo '<input type="radio" name="remote_dir" value="admin" '.ch($dr['remote_dir'],'admin').'> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="remote_dir" value="moder" '.ch($dr['remote_dir'],'moder').'> '.$lang['on_moders'].'<br />';  
    echo '<input type="radio" name="remote_dir" value="superuser" '.ch($dr['remote_dir'],'superuser').'> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="remote_dir" value="user" '.ch($dr['remote_dir'],'user').'> '.$lang['on_users'].'<br />';
  	echo '<input type="radio" name="remote_dir" value="guest" '.ch($dr['remote_dir'],'guest').'> '.$lang['on_guests'].'<br /></div>';
  	
    echo '<div class="elem"><b>'.$lang['can_comm'].'</b><hr />';
    echo '<input type="radio" name="write_comments" value="admin" '.ch($dr['write_comments'],'admin').'> '.$lang['on_admins'].'<br />';
    echo '<input type="radio" name="write_comments" value="moder" '.ch($dr['write_comments'],'moder').'> '.$lang['on_moders'].'<br />';  
    echo '<input type="radio" name="write_comments" value="superuser" '.ch($dr['write_comments'],'superuser').'> '.$lang['on_supus'].'<br />';
    echo '<input type="radio" name="write_comments" value="user" '.ch($dr['write_comments'],'user').'> '.$lang['on_users'].'<br />';
  	echo '<input type="radio" name="write_comments" value="guest" '.ch($dr['write_comments'],'guest').'> '.$lang['on_guests'].'<br /></div>';
    echo '<div class="elem"><input type="submit" value="'.$lang['save'].'" class="edit"></div>';  	
    echo '</form>';
    echo '<div class="helem">&nbsp;</div>';
    echo '<a class="url" href="?do=config">'.$lang['wiki_set'].'</a><br />';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
    require_once ('inc/admin/fin.php');
  }
  elseif($act=='srights')
  {
  	$users=array('admin','superuser','user','moder','guest');
  	if (in_array($_POST['create_stat'], $users) and in_array($_POST['edit_stat'], $users) and in_array($_POST['delete_message'], $users) and in_array($_POST['delete_stat'], $users) and in_array($_POST['add_dir'], $users) and in_array($_POST['remote_dir'], $users) and in_array($_POST['write_comments'], $users))
  	{
  		$config='[info]
create_stat='.$_POST['create_stat'].';
edit_stat='.$_POST['edit_stat'].';
delete_message='.$_POST['delete_message'].';
delete_stat='.$_POST['delete_stat'].';
add_dir='.$_POST['add_dir'].';
remote_dir='.$_POST['remote_dir'].';
write_comments='.$_POST['write_comments'].';
';

      file_put_contents($parent.'files/rights.ini',$config);
      require_once ('inc/admin/head_info.php');
      echo '<b>'.$lang['set_saved'].'</b></td></table><center>';
      echo '<form style="display:inline" method="post" action="?do=config&act=rights"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';	
      require_once ('inc/admin/fin_info.php');
  	}
  	else
	    admer($lang['wrong_ind']);
  }
  elseif($act=='rekedit')
  { 
  	$d=abs(intval($_GET['d']));
    $dr1= mysql_query("SELECT * FROM `wm_ads` WHERE `id` = '".$d."' LIMIT 1;");
    if(mysql_num_rows($dr1))
    {
      $dr = mysql_fetch_array($dr1);  
    }
    else
    {
      admer($lang['link_id_wrong']);
      exit();  
    }
    if ($d)
    {
      $ost_days =  ceil($dr['time']/(3600*24))+$dr['view']-ceil(time()/(3600*24));  
      if ($_GET['k']=='1')
  	  {
      $days = intval(abs($_POST['time']));
      if($days)
      {
        if($days != $ost_days)
        {
          $total_time = $dr['time']+(($dr['view']-$ost_days)*3600*24);
          $up_days =  $days;
          $ost_days = $days;
        }
        else
        {
          $total_time = $dr['time'];
          $up_days =  $dr['view'];
        }
      }
      else
        $err .= $lang['link_t_wr'].'<br />';   
      $addtext_1 = trim($_POST['name']); 
  	  if ($addtext_1)
  	    $addtext=htmlentities($addtext_1, ENT_QUOTES, 'UTF-8');
  	  else
  	    $err .= $lang['link_nm_wr'].'<br />';
      $addlink_1= trim($_POST['link']);
  	  if ($addlink_1)
  	    $addlink=htmlentities(str_replace('"','',$addlink_1), ENT_QUOTES, 'UTF-8');
  	  else
  	    $err .= $lang['link_ad_wr'].'<br />';
  	  if ($_POST['color'])
    	{
    	  if (preg_match("/^[0-9A-f]{6}$/",$_POST['color']))
  	       $addcolor=$_POST['color'];
    	  else
  	      $err .= $lang['link_cl_wr'].'<br />'; 
    	}
  	  if ($_POST['t_bold']=='1')
  	    $t_bold= 'font-weight:bold;';
  	  if ($_POST['t_ir']=='1')
  	    $t_ir = 'font-style:italic;';
  	  if ($_POST['t_und']=='1')
  	    $t_und = 'text-decoration:underline;';
  	  if ($_POST['time'])
  	  {
  	    if (preg_match("/^[0-9]{1,2}$/",$_POST['time']))
  	      $time=$_POST['time'];
  	    else 
  	      $err .= $lang['link_tm_wr'].'<br />';
  	  }
  	  else
  	    $err .= $lang['ln_sh_wr'].'<br />';
  	  	if (!$err)
  	  	{
          mysql_query("UPDATE `wm_ads` SET
          `name`='" . mysql_real_escape_string($addtext) . "',
          `time`='" . $total_time . "',
          `view`='" . $up_days . "',
          `link`='" . mysql_real_escape_string($addlink) . "',
          `style`='" . mysql_real_escape_string($t_bold.$t_ir.$t_und.($addcolor ? 'color:#'.$addcolor : '')). "'
           WHERE `id` = '".$d."';");
        
  	    require_once ('inc/admin/head_info.php');
        echo '<b>'.$lang['link_chang'].'</b></td></table><center>';
        echo '<form style="display:inline" method="post" action="?do=config&act=rekl"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';	
        require_once ('inc/admin/fin_info.php');
  	  	exit();
  	   }
  	  }
  	  else
  	  {
  	    $addtext=$dr['name'];
  	    $addlink=$dr['link'];
  	    $time=$dr['view'];
  	    $data=$dr['time'];
  	    $style=$dr['style'];
  	    if (substr_count($style,'font-weight:bold;'))
  	      $t_bold='font-weight:bold;';
  	    if (substr_count($style,'font-style:italic;'))
  	      $t_ir='font-style:italic;';
  	    if (substr_count($style,'text-decoration:underline;'))
  	      $t_und='text-decoration:underline;';
    	  preg_match('#([0-9A-f]{6})#', $style, $color);
    	  $addcolor=$color[1];
  	  }
  	}
  	else
  	  $err .= $lang['f_id_error'].'<br />';
  	
  	require_once ('inc/admin/head.php');
  	echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['ln_edition'].'</div>';
    if ($err) echo '<div class="rmenu">'.$err.'</div>';
  	echo '<div class="elem">'.$lang['naming'].'<hr /><form action="?do=config&act=rekedit&d='.$dr['id'].'&k=1" method="post">';
  	echo '<input name="name" type="text" value="'.$addtext.'" class="edit2"></div>';
  	echo '<div class="elem">'.$lang['ln_wt_ht'].'<hr />';
  	echo '<input name="link" type="text" value="'.$addlink.'" class="edit2"></div>';
  	echo '<div class="elem">'.$lang['style_e'].'<hr/>';
  	echo '<input type="checkbox" class="edit" name="t_bold" '.($t_bold ? 'checked="checked"' : '').' value="1"> '.$lang['hemibold'].'<br />';
  	echo '<input type="checkbox" class="edit" name="t_ir" '.($t_ir ? 'checked="checked"' : '').' value="1"> '.$lang['tag_oblique'].'<br />';
  	echo '<input type="checkbox" class="edit" name="t_und" '.($t_und ? 'checked="checked"' : '').' value="1"> '.$lang['und_link'].'<br /></div>';
  	echo '<div class="elem">'.$lang['cl_choose'].'<hr/>';
  	echo '<input name="color" type="text" value="'.$addcolor.'" class="edit2"></div>';
  	echo '<div class="elem">'.$lang['view_days'].'<hr/><input name="time" type="text" value="'.$ost_days.'" class="edit2"></div>';
    echo '<div class="elem"><input type="submit" value="'.$lang['save'].'" class="edit"></div>';
    echo '</form>';
  	echo '<div class="helem">&nbsp;</div>';
  	echo '<a class="url" href="?do=config&act=rekl">'.$lang['bn_rekl'].'</a><br />';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
  	require_once ('inc/admin/fin.php');
  }
  elseif($act=='rekl')
  {
  	$del=abs(intval($_GET['del']));
  	if ($del and ($_GET['t']=='top' or $_GET['t']=='down'))
  	{
      $del_link1= mysql_query("SELECT * FROM `wm_ads` WHERE `id` = '".$del."' LIMIT 1;");
      if(mysql_num_rows($del_link1))
      {
        mysql_query("DELETE FROM `wm_ads` WHERE `id` = '".$del."'  LIMIT 1");  
      }
  	}
  	require_once ('inc/admin/head.php');
  	echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['bn_rekl'].'</div>';
  	echo '<div class="helem"><b>'.$lang['top_rekl'].'</b></div>';
    $link1= mysql_query("SELECT * FROM `wm_ads` WHERE `type` = '1' ;");
  	if(mysql_num_rows($link1))
  	{
  		while($link = mysql_fetch_assoc($link1))
  		{
            $ost_days =  ceil($link['time']/(3600*24))+$link['view']-ceil(time()/(3600*24));
  			echo '<div class="elem"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/edit.png" /> <a href="'.$link['link'].'" style="'.$link['style'].'">'.$link['name'].'</a> ('.$link['link'].')<br/>'.$lang['ln_os_days'].' '.$ost_days.'<hr/> <a href="?do=config&act=rekedit&d='.$link['id'].'">'.$lang['change'].'</a> | <a href="?do=config&act=rekl&t=top&del='.$link['id'].'">'.$lang['delete'].'</a></div>';
  		}
  	}
    else
    {
       echo '<div class="elem">'.$lang['no_links'].'</div>'; 
    }
  	echo '<div class="elem"><a href="?do=config&act=adlink&t=top"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/plus.gif" /> '.$lang['add_link'].'</a></div>';
  	echo '<div class="helem"><b>'.$lang['dn_rekl'].'</b></div>';
    $link_down1= mysql_query("SELECT * FROM `wm_ads` WHERE `type` = '0' ;");
    if(mysql_num_rows($link_down1))
  	{
  		while($link_down = mysql_fetch_assoc($link_down1))
  		{
            $ost_days_down = ceil($link_down['time']/(3600*24))+$link_down['view']-ceil(time()/(3600*24));
  			echo '<div class="elem"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/edit.png" /> <a href="'.$link_down['link'].'" style="'.$link_down['style'].'">'.$link_down['name'].'</a> ('.$link_down['link'].')<br/>'.$lang['ln_os_days'].' '.$ost_days_down.'<hr/> <a href="?do=config&act=rekedit&d='.$link_down['id'].'">'.$lang['change'].'</a> | <a href="?do=config&act=rekl&t=down&del='.$link_down['id'].'">'.$lang['delete'].'</a></div>';
  		}
  	}
    else
    {
       echo '<div class="elem">'.$lang['no_links'].'</div>'; 
    }
  	echo '<div class="elem"><a href="?do=config&act=adlink&t=down"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/plus.gif" /> '.$lang['add_link'].'</a></div>';
    echo '<div class="helem">&nbsp;</div>';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
    require_once ('inc/admin/fin.php');
  }
  elseif($act=='baner')
  {
  	require_once ('inc/admin/head.php');
  	echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['kode_ban_n'].'</div>';
  	echo '<form action="?do=config&act=srekl" method="post">';
    echo '<div class="elem"><b>'.$lang['bann_notice'].'</b></div>';
    echo '<div class="elem">'.$lang['on_all_pg'].'<hr/><textarea name="text" class="edit" cols="80"  rows="2">';
    echo htmlentities($set['counter'], ENT_QUOTES, 'UTF-8');
    echo '</textarea></div>';
    echo '<div class="elem">'.$lang['on_main_n'].'<hr/><textarea name="index" class="edit" cols="80"  rows="2">';
    echo htmlentities($set['counter_index'], ENT_QUOTES, 'UTF-8');
    echo '</textarea></div>';
    echo '</div><div class="elem"><input type="submit" value="'.$lang['save'].'" class="edit"></div>';
    echo '</form>';
  	echo '<div class="helem">&nbsp;</div>';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
    require_once ('inc/admin/fin.php');
  }
  elseif($act=='adlink')
  {
    $m_act = $_GET['t'];  
  	if ($_GET['d']=='1' and ($m_act=='top' or $m_act=='down'))
  	{
      $addtext_1 = trim($_POST['name']);
  	  if ($addtext_1)
  	    $addtext=htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
  	  else
  	    $err .= $lang['link_nm_wr'].'<br />';
      $addlink_1 = trim($_POST['link']);
  	  if ($addlink_1)
  	    $addlink=htmlentities(str_replace('"','',$addlink_1), ENT_QUOTES, 'UTF-8');
  	  else
  	    $err .= $lang['link_ad_wr'].'<br />';
  	  if ($_POST['color'])
    	{
    	  if (preg_match("/^[0-9A-f]{6}$/",$_POST['color']))
  	       $addcolor=$_POST['color'];
    	  else
  	      $err .= $lang['link_cl_wr'].'<br />'; 
    	}
  	  if ($_POST['t_bold']=='1')
  	    $t_bold= 'font-weight:bold;';
  	  if ($_POST['t_ir']=='1')
  	    $t_ir = 'font-style:italic;';
  	  if ($_POST['t_und']=='1')
  	    $t_und = 'text-decoration:underline;';
  	  if ($_POST['time'])
  	  {
  	    if (preg_match("/^[0-9]{1,2}$/",$_POST['time']))
  	      $time=$_POST['time'];
  	    else 
  	      $err .= $lang['link_tm_wr'].'<br />';
  	  }
  	  else
  	    $err .= $lang['ln_sh_wr'].'<br />';
  	  
  	  if(!$err)
  	  {
        mysql_query("INSERT INTO `wm_ads` SET
          `type`='" . ($_GET['t']=='top' ? '1' : '0') . "',
          `name`='" . mysql_real_escape_string($addtext) . "',
          `link`='" . mysql_real_escape_string($addlink) . "',
          `time`='" . time() . "',
          `style`='" . mysql_real_escape_string($t_bold.$t_ir.$t_und.($addcolor ? 'color:#'.$addcolor : '')). "',
          `view`='".$time."';");
  	    require_once ('inc/admin/head_info.php');
        echo '<b>'.$lang['link_chang'].'</b></td></table><center>';
        echo '<form style="display:inline" method="post" action="?do=config&act=rekl"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';	
        require_once ('inc/admin/fin_info.php');
  	  	exit();
  	  }
  	}
    if($m_act!='top' and $m_act!='down')
    {
      unset($m_act);
      $err .= $lang['f_id_error'];  
    }
  	require_once ('inc/admin/head.php');
  	echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['ln_addition'].'</div>';
    if ($err) echo '<div class="rmenu">'.$err.'</div>';
  	echo '<div class="elem">'.$lang['naming'].'<hr /><form action="?do=config&act=adlink&d=1&t='.$m_act.'" method="post">';
  	echo '<input name="name" type="text" value="'.htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8').'" class="edit2"></div>';
  	echo '<div class="elem">'.$lang['ln_wt_ht'].'<hr />';
  	echo '<input name="link" type="text" value="'.htmlentities($_POST['link'], ENT_QUOTES, 'UTF-8').'" class="edit2"></div>';
  	echo '<div class="elem">'.$lang['style_e'].'<hr/>';
  	echo '<input type="checkbox" class="edit" name="t_bold" '.($t_bold ? 'checked="checked"' : '').' value="1"> '.$lang['hemibold'].'<br />';
  	echo '<input type="checkbox" class="edit" name="t_ir" '.($t_ir ? 'checked="checked"' : '').' value="1"> '.$lang['tag_oblique'].'<br />';
  	echo '<input type="checkbox" class="edit" name="t_und" '.($t_und ? 'checked="checked"' : '').' value="1"> '.$lang['und_link'].'<br /></div>';
  	echo '<div class="elem">'.$lang['cl_choose'].'<hr/>';
  	echo '<input name="color" type="text" value="'.htmlentities($_POST['color'], ENT_QUOTES, 'UTF-8').'" class="edit2"></div>';
  	echo '<div class="elem">'.$lang['view_days'].'<hr/>';
  	echo '<input name="time" type="text" value="'.($_POST['time'] ? htmlentities($_POST['time'], ENT_QUOTES, 'UTF-8') : '7').'" class="edit2"></div>';
    echo '<div class="elem"><input type="submit" value="'.$lang['save'].'" class="edit"></div>';
    echo '</form>';
  	echo '<div class="helem">&nbsp;</div>';
  	echo '<a class="url" href="?do=config&act=rekl">'.$lang['ads_adm'].'</a><br />';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
  	require_once ('inc/admin/fin.php');
  }
  elseif($act=='srekl')
  {
    mysql_query("UPDATE `wm_settings` SET `value` = '".mysql_real_escape_string(trim($_POST['text']))."' WHERE `key` = 'counter'");
    mysql_query("UPDATE `wm_settings` SET `value` = '".mysql_real_escape_string(trim($_POST['index']))."' WHERE `key` = 'counter_index'");
  	require_once ('inc/admin/head_info.php');
    echo '<b>'.$lang['set_saved'].'</b></td></table><center>';
    echo '<form style="display:inline" method="post" action="?do=config&act=baner"><input type="submit" class="edit" value="'.$lang['continue'].'" /></a></center>';	
    require_once ('inc/admin/fin_info.php');
  }
  elseif($act=='user')
  {
  	require_once ('inc/admin/head.php');
  	echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['sp_users'].'</div>';
    $r['admin']=$lang['admin'];
    $r['moder']=$lang['moder'];  
    $r['superuser']=$lang['superuser'];
    $r['user']=$lang['user'];
    $r['guest']=$lang['guest_g'];
    $colpages=20;
    $nat = abs(intval($_GET['p']));
    if (!$nat) $nat=1;
    $tr=($nat-1)*$colpages;
  
    $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_users`"), 0);
    $user1= mysql_query("SELECT * FROM `wm_users` ORDER BY `time` DESC LIMIT $tr,$colpages");
    if($total >0)
    {	
      while ($user = mysql_fetch_assoc($user1))
      {
         $ttl= mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_users_ban` WHERE `user_id` = '".$user['id']."' AND `type` = '1' LIMIT 1;"), 0);
         echo '<div class="elem"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/members.gif" /> <b><a href="'.$parent.'?do=user&amp;us='.$user['id'].'">'.$user['name'].'</a></b> <span class="empty">('.$r[$user['rights']].')</span>';
         if ($rights=='admin' and $user_id != $user['id'])
           echo ''.($ttl ? '<a href="?do=unsetban&us='.$user['id'].'"> '.$lang['s_razb'].'</a>' : '<a href="?do=ban&us='.$user['id'].'"> '.$lang['s_ban'].'</a>').' | <a href="?do=config&act=us_ed&usid='.$user['id'].'"> '.$lang['s_change'].'</a> | <a href="?do=config&act=usdel&usid='.$user['id'].'"> '.$lang['s_del'].'</a>';
         echo '</div>';
      }
    }
    else
    {
      echo '<div class="elem">'.$lang['no_users'].'</div>';  
    }
    echo '<div class="helem">'.$lang['total'].' '.$total.'</div>';
      $vpage=vpage($total,'?do=config&amp;act=user&amp;',$colpages);
    if ($vpage)
    {
      echo $vpage.'<hr/>';
    }
    echo '<a class="url" href="?do=config&act=usadm">'.$lang['users_adm'].'</a><br />';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />'; 
    require_once ('inc/admin/fin.php');
  }
  elseif($act=='us_ed')
  {
    $uid=intval(abs($_GET['usid']));
    $req = mysql_query('SELECT * FROM `wm_users` WHERE `id`="'.$uid.'" LIMIT 1;');
  	if (mysql_num_rows($req))
  	{
  	  $user = mysql_fetch_array($req);
  	  require_once ('inc/admin/head.php');
  	  echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['us_editing'].'</div>';
      echo '<form action="?do=config&act=us_sv&usid='.$uid.'" method="post">';
      echo '<div class="elem"><b>'.$lang['nik_k'].'</b> <span class="empty">'.$user['name'].'</span><br /><hr />';
      echo '<b>'.$lang['ban_status'].'</b><br />';
      echo '<input type="radio" name="rights" value="admin" '.ch($user['rights'],'admin').'> '.$lang['admin'].'<br />';
      echo '<input type="radio" name="rights" value="moder" '.ch($user['rights'],'moder').'> '.$lang['moder'].'<br />';
      echo '<input type="radio" name="rights" value="superuser" '.ch($user['rights'],'superuser').'> '.$lang['superuser'].'<br />';
      echo '<input type="radio" name="rights" value="user" '.ch($user['rights'],'user').'> '.$lang['user'].'<br />';
      echo '</div><div class="elem"><input type="submit" value="'.$lang['save'].'" class="edit"></div>';
      echo '</form>';
      echo '<div class="helem">&nbsp;</div>';
      echo '<a class="url" href="?do=config&act=user">'.$lang['sp_users'].'</a><br />';
      echo '<a class="url" href="?do=config&act=usadm">'.$lang['users_adm'].'</a><br />';
      echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />'; 
	  require_once ('inc/admin/fin.php');	
  	}
  	else
	    admer($lang['us_no_exists']);
  }
  elseif($act=='us_sv')
  {
    $uid=intval(abs($_GET['usid']));
    $req = mysql_query('SELECT * FROM `wm_users` WHERE `id`="'.$uid.'" LIMIT 1;');  
  	if (mysql_num_rows($req))
  	{ 
  		$users=array('admin','moder','superuser','user');
  		if (in_array($_POST['rights'], $users))
  		{
           mysql_query("UPDATE `wm_users` SET
            `rights` = '".$_POST['rights']."'
             WHERE `id` = '".$uid."'");
	      require_once ('inc/admin/head_info.php');
  	  	  echo '<b>'.$lang['rig_changed'].'</b><br /></td></tr></table>';
          echo '<form style="display: inline" action="?do=config&act=us_ed&usid='.$uid.'" method="post"><center><input class="edit" type="submit" value="'.$lang['continue'].'"/></center></form>';
	      require_once ('inc/admin/fin_info.php');
    	}
    	else
	      admer($lang['wrong_ind']);
  	}
  	else
	 admer($lang['us_no_exists']);
  }
  elseif($act=='usdel')
  {
    $uid=intval(abs($_GET['usid']));
    $req = mysql_query('SELECT * FROM `wm_users` WHERE `id`="'.$uid.'" LIMIT 1;');
  	if (mysql_num_rows($req))
  	{
        $user = mysql_fetch_array($req);
  		require_once ('inc/admin/head_info.php');
  	    echo '<b>'.$lang['del_user_d'].' "'.$user['name'].'"?</b><br />';
        echo '</td></table><center>';
        echo '<form action="?" style="display: inline"><input type="hidden" name="do" value="config"><input type="hidden" name="act" value="us_del"><input type="hidden" name="usid" value="'.$uid.'"><input type="submit" value="'.$lang['delete_yes'].'" class="edit" /></form>';	
        echo ' <form action="?" style="display: inline"><input type="hidden" name="do" value="config"><input type="hidden" name="act" value="user"><input type="submit" value="'.$lang['cancel'].'" class="edit" /></form>';	
        echo '</center>';
	    require_once ('inc/admin/fin_info.php');
  	}
  	else
      admer($lang['us_no_exists']);
  }
  elseif($act=='us_del')
  {
    $uid=intval(abs($_GET['usid']));
    $req = mysql_query('SELECT * FROM `wm_users` WHERE `id`="'.$uid.'" LIMIT 1;');
    if (mysql_num_rows($req))
    {
        $user = mysql_fetch_array($req);
        mysql_query("DELETE FROM `wm_users` WHERE `id` = '".$uid."'  LIMIT 1");
        mysql_query("DELETE FROM `wm_users_info` WHERE `userid` = '".$uid."'  LIMIT 1");
        mysql_query("INSERT INTO `wm_mail_ban` SET `mail` = '".mysql_real_escape_string($user['mail'])."', `username` = '".mysql_real_escape_string($user['name'])."', `userid` = '".$user['id']."' ;");
  		require_once ('inc/admin/head_info.php');
  		echo '<b>'.$lang['user'].' '.$user['name'].' '.$lang['del_do'].'</b>';
	    echo '</td></table><center><form method="post" style="display: inline" action="?do=config&act=user"><input class="edit" type="submit" value="'.$lang['back'].'" /></form></center>';
	    require_once ('inc/admin/fin_info.php');  		
  	}
  	else
      admer($lang['us_no_exists']);
  }
  elseif($act=='newuser')
  {
  	require_once ('inc/admin/head.php');
  	echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['new_user'].'</div>';
    if($set['reg']!='regon')
    {
  	  echo '<form action="?do=config&act=new_us" method="post">';
  	  echo '<div class="elem"><b>'.$lang['warn_n'].'</b><hr />';
  	  echo $lang['nb_to_know'].'</div>';
  	  echo '<div class="elem"><input class="edit" type="submit" value="'.$lang['cr_user'].'" class="edit"  /></div>';
    }
    else
    {
       echo '<div class="elem">'.$lang['inv_cn_cr_us'].'</div>'; 
    }
	echo '<div class="helem">&nbsp;</div>';
	echo '<a class="url" href="?do=config&act=usadm">'.$lang['users_adm'].'</a><br />';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />'; 
	require_once ('inc/admin/fin.php');
  }
  elseif($act=='new_us')
  {
  	 $link=rand(10000000000,99999999999);
     mysql_query("INSERT INTO `wm_invites` SET `link`='".$link."', `time`='" . time() . "';");
     require_once ('inc/admin/head.php');
     echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['new_user'].'</div>';
     echo '<div class="elem"><b>'.$lang['ln_is_gen'].'</b><hr />';
     echo $lang['gv_to_us'].'<hr />';
     echo '<input type="text" name="" class="edit2" value="'.$set['url'].'?do=register&inv='.$link.'"></div>';
     echo '<div class="elem"><b></b><hr /></div>';
     echo '<div class="helem">&nbsp;</div>';
	 echo '<a class="url" href="?do=config&act=usadm">'.$lang['users_adm'].'</a><br />';
     echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />'; 
     require_once ('inc/admin/fin.php');
  }
  elseif($act=='smiles')
  {
    $d = intval(abs($_GET['d']));
    $act = $_GET['in'];
    if(!$act)
    {  
      require_once ('inc/admin/head.php');  
      echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['sm_set'].'</div>';
      $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_smiles` WHERE `type` = 'ct'"), 0);
      if ($total > 0)
      {
        $req = mysql_query("SELECT * FROM `wm_smiles` WHERE `type` = 'ct'");  
        while ($res = mysql_fetch_assoc($req))
        {
          $total_sm = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_smiles` WHERE `type` = 'sm' AND `refid`='".$res['id']."' ;"), 0);
          echo '<div class="elem"><a href="./?do=config&amp;act=smiles&amp;in=cat&amp;d='.$res['id'].'"><img src="'.$parent.'themes/admin/'.$set['admintheme'].'/folder.png"  /> '.$res['pattern'].'</a> ('.$total_sm.')';
          echo '<hr/><a href="./?do=config&amp;act=smiles&amp;in=editct&amp;d='.$res['id'].'">'.$lang['change'].'</a> | <a href="./?do=config&amp;act=smiles&amp;in=del&amp;d='.$res['id'].'">'.$lang['delete'].'</a>';
          echo '</div>';
        }  
      }
      else
      {
         echo '<div class="elem">'.$lang['no_cat'].'</div>'; 
      }
      echo '<div class="helem">&nbsp;</div>';
      echo '<a class="url" href="./?do=config&amp;act=smiles&amp;in=addct">'.$lang['new_cat'].'</a><br />';
      echo '<a class="url" href="./?do=adm">'.$lang['adminpanel'].'</a><br />';
      require_once ('inc/admin/fin.php');
    }
    elseif($act=='cat')
    {
      if($d)
      {
         require_once ('inc/admin/head.php'); 
         $smcat1 = mysql_query("SELECT * FROM `wm_smiles` WHERE `type` = 'ct' AND `id` = '".$d."' LIMIT 1 ;");
         if(mysql_num_rows($smcat1))
         {
           $smcat = mysql_fetch_assoc($smcat1);
           echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['sm_set'].' | '.$smcat['pattern'].'</div>';
           $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_smiles` WHERE `type` = 'sm' AND `refid` = '".$d."';"), 0);
           if($total)
           {
             $colpages=10;
             $nat = abs(intval($_GET['p']));
             if (!$nat) $nat=1;
             $tr=($nat-1)*$colpages; 
             $req_sm = mysql_query("SELECT * FROM `wm_smiles` WHERE `type` = 'sm' AND `refid` = '".$d."'  ORDER BY `id` DESC LIMIT $tr,$colpages;;");
             while ($res_sm = mysql_fetch_assoc($req_sm))
             {                                           
               echo '<div class="elem"><img src="'.$parent.'sourse/smiles/'.$res_sm['image'].'" alt="" /> - '.str_replace('|',' ',$res_sm['pattern']).'';
               echo '<hr/><a href="./?do=config&amp;act=smiles&amp;in=editsm&amp;d='.$res_sm['id'].'">'.$lang['change'].'</a> | <a href="./?do=config&amp;act=smiles&amp;in=delsm&amp;d='.$res_sm['id'].'">'.$lang['delete'].'</a>';  
               echo '</div>';
             }
             echo '<div class="helem">'.$lang['total'].' '.$total.'</div>';
             $vpage=vpage($total,'./?do=config&amp;act=smiles&amp;in=cat&amp;d='.$d.'&amp;',$colpages);
             if ($vpage)
             {
                echo '<div class="elem">'.$vpage.'</div>';
             }
           
           }
           else
           {
             echo '<div class="elem">'.$lang['no_sm_cat'].'</div>';  
             echo '<div class="helem">&nbsp;</div>';  
           }
           echo '<a class="url" href="./?do=config&amp;act=smiles&amp;in=addsm&amp;d='.$d.'">'.$lang['new_smile'].'</a><br/>';
           echo '<a class="url" href="./?do=config&amp;act=smiles&amp;in=editct&amp;d='.$d.'">'.$lang['change'].'</a> | ';
           echo '<a href="./?do=config&amp;act=smiles&amp;in=del&amp;d='.$d.'">'.$lang['delete'].'</a><br/>';
           echo '<a class="url" href="./?do=config&amp;act=smiles">'.$lang['sm_set'].'</a><br/>';
           echo '<a class="url" href="./?do=adm">'.$lang['adminpanel'].'</a><br />';
         }
         require_once ('inc/admin/fin.php');
       }
      else
      {
        header('Location: ./');
        exit();
      }
    }
    elseif($act=='addct')
    {
      $save=$_GET['save'];
      if($save)
      {
        $err='';
        $name=trim($_POST['name']);
        if(!$name)
          $err .= $lang['inv_name_cat'].'<br/>';
        else
        {
          $threat = mysql_query('SELECT * FROM `wm_smiles` WHERE `type` = "ct" AND `pattern`="' . mysql_real_escape_string(htmlentities($name, ENT_QUOTES, 'UTF-8')) . '";');
          if(mysql_num_rows($threat))
            $err .= $lang['cat_ex_sm'].'<br/>';  
        }
      }  
      if($save and !$err)
      {
        mysql_query("INSERT INTO `wm_smiles` SET `type` = 'ct', `pattern` = '".mysql_real_escape_string(htmlentities($name, ENT_QUOTES, 'UTF-8'))."' ;");
        $fadd = mysql_insert_id();
        require_once ('inc/admin/head_info.php');
        echo $lang['cat_cr_sm'].'<br/>';
        echo '</td></table><center>';
        echo '<form style="display: inline" action="./?do=config&amp;act=smiles&amp;in=cat&amp;d='.$fadd.'" method="post"><center><input class="edit" type="submit" value="'.$lang['continue'].'"/></center></form>';
        echo '</center>';
        require_once ('inc/admin/fin_info.php');
        exit();
     }
   
      require_once ('inc/admin/head.php');
      echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['sm_set'].' | '.$lang['new_cat'].'</div>';
      if($err)
        echo '<div class="rmenu">'.$err.'</div>';
      echo '<form action="./?do=config&amp;act=smiles&amp;in=addct&amp;save=save" method="post">';
      echo '<div class="elem">';  
      echo $lang['naming'].'<br/><input class="edit2" type="text" name="name" value="'.($name ? htmlentities($name, ENT_QUOTES, 'UTF-8') : '').'"/>';
      echo '</div>';
      echo '<div class="elem"><input type="submit" name="submit" class="edit" value="'.$lang['add'].'"/></div></form>';
      echo '<div class="helem">&nbsp;</div>';
      echo '<a class="url" href="./?do=config&amp;act=smiles">'.$lang['sm_set'].'</a><br/>';
      echo '<a class="url" href="./?do=adm">'.$lang['adminpanel'].'</a><br />';
      require_once ('inc/admin/fin.php');  
    }
    elseif($act=='editct')
    {
       if($d)
      {  
        $smcat1 = mysql_query("SELECT * FROM `wm_smiles` WHERE `type` = 'ct' AND `id` = '".$d."' LIMIT 1 ;"); 
        if(mysql_num_rows($smcat1))
        {
          $smcat = mysql_fetch_assoc($smcat1); 
          $save=$_GET['save']; 
          if($save)
          {
            $err='';
            $name=trim($_POST['name']);
            if(!$name)
              $err .= $lang['inv_name_cat'].'<br/>';
            else
            {
              if($smcat['pattern']!=$name)
              {
                $threat = mysql_query('SELECT * FROM `wm_smiles` WHERE `type` = "ct" AND `pattern`="' . mysql_real_escape_string(htmlentities($name, ENT_QUOTES, 'UTF-8')) . '";');
                if(mysql_num_rows($threat))
                  $err .= $lang['cat_ex_sm'].'<br/>';
              }  
            }
          }  
          if($save and !$err)
          {
            mysql_query("UPDATE `wm_smiles` SET `type` = 'ct', `pattern` = '".mysql_real_escape_string(htmlentities($name, ENT_QUOTES, 'UTF-8'))."' WHERE `id` = '".$d."';");
            require_once ('inc/admin/head_info.php');
            echo $lang['cat_changed'].'<br/>';
            echo '</td></table><center>';
            echo '<form style="display: inline" action="./?do=config&amp;act=smiles&amp;in=cat&amp;d='.$d.'" method="post"><center><input class="edit" type="submit" value="'.$lang['continue'].'"/></center></form>';
            echo '</center>';
            require_once ('inc/admin/fin_info.php');
            exit();
          }
          require_once ('inc/admin/head.php');  
          echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['sm_set'].' | '.$lang['cat_ch_sm'].'</div>';
          if($err)
            echo '<div class="rmenu">'.$err.'</div>';
          echo '<form action="./?do=config&amp;act=smiles&amp;in=editct&amp;save=save&amp;d='.$d.'" method="post">';
          echo '<div class="elem">';  
          echo $lang['naming'].'<br/><input class="edit2" type="text" name="name" value="'.$smcat['pattern'].'"/>';
          echo '</div>';
          echo '<div class="elem"><input class="edit" type="submit" name="submit" value="'.$lang['save'].'"/></div></form>';
          echo '<div class="helem">&nbsp;</div>';
          require_once ('inc/admin/fin.php');
        }
        else
        {
          header('Location: ./');
          exit();  
        }
      }
      else
      {
        header('Location: ./');
        exit();
      } 
    }
    elseif($act=='del')
    {
      if($d)
      {  
        $smcat1 = mysql_query("SELECT * FROM `wm_smiles` WHERE `type` = 'ct' AND `id` = '".$d."' LIMIT 1 ;"); 
        if(mysql_num_rows($smcat1))
        {
          $smcat = mysql_fetch_assoc($smcat1);
              
          require_once ('inc/admin/head_info.php');
          echo $lang['is_del_cat'].' "'.$smcat['pattern'].'"?<br/>';
          echo '</td></table><center>';
          echo '<form style="display: inline" action="./?do=config&amp;act=smiles&amp;in=del2&amp;d='.$d.'" method="post"><input class="edit" type="submit" value="'.$lang['delete'].'"/></form>';
          echo '<form style="display: inline" action="./?do=config&amp;act=smiles&amp;in=cat&amp;d='.$d.'" method="post"><input class="edit" type="submit" value="'.$lang['cancel'].'"/></form>';
          echo '</center>';
          require_once ('inc/admin/fin_info.php');
        }
        else
        {
           header('Location: ./');
           exit();  
        }
      }
      else
      {
        header('Location: ./');
        exit();
      }   
    } 
    elseif($act=='del2')
    {
      if($d)
      {  
        $smcat1 = mysql_query("SELECT * FROM `wm_smiles` WHERE `type` = 'ct' AND `id` = '".$d."' LIMIT 1 ;"); 
        if(mysql_num_rows($smcat1))
        {
          $req_sm = mysql_query("SELECT * FROM `wm_smiles` WHERE `type` = 'sm' AND `refid` = '".$d."';");  
          while ($res_sm = mysql_fetch_assoc($req_sm))
          {
            if(file_exists($parent.'sourse/smiles/'.$res_sm['image']))
              unlink($parent.'sourse/smiles/'.$res_sm['image']);
          }  
          mysql_query("DELETE FROM `wm_smiles` WHERE `type` = 'sm' AND `refid` = '".$d."';");
          mysql_query("DELETE FROM `wm_smiles` WHERE `type` = 'ct' AND `id` = '".$d."' LIMIT 1;");  
        
          $smcat = mysql_fetch_assoc($smcat1);
          require_once ('inc/admin/head_info.php');
          echo $lang['cat_del_1'].' "'.$smcat['pattern'].'" '.$lang['cat_del_2'].'<br/>';
          echo '</td></table><center>';
          echo '<form style="display: inline" action="./?do=config&amp;act=smiles" method="post"><input class="edit" type="submit" value="'.$lang['continue'].'"/></form>';
          echo '</center>';
          require_once ('inc/admin/fin_info.php');
        }
        else
        {
           header('Location: ./');
           exit();  
        }
      }
      else
      {
        header('Location: ./');
        exit();
      } 
    } 
    elseif($act=='addsm')
    {
       $raz1 = mysql_query('SELECT * FROM `wm_smiles` where `type` = "ct" ;');
       if(mysql_num_rows($raz1))
       {
          $cat=intval(abs($_POST['cat']));
          $save=$_GET['save'];
          if($save and $cat)
          {
             $ct1 = mysql_query('SELECT * FROM `wm_smiles` where `type` = "ct" AND `id`="'.$cat.'" LIMIT 1;');
             if(!mysql_num_rows($ct1))
               $err .= $lang['cat_empty'].'<br/>';
             else
               $ct = mysql_fetch_array($ct1); 
             $pattern = trim($_POST['text']);
             if (!$pattern)
               $err .= $lang['pat_empty'].'<br/>';
             else
             {
               $patt = explode('|',$pattern);
               $i=0;
               $error = array();
               $cnt = count($patt);
               while($cnt > $i)
               {
                  if(preg_match("/[\s]/U", $patt[$i]) or !$patt[$i])
                    $error[] = $i+1;
                  $i++; 
               }
               if($error)
                $err .= $lang['n_ex_in_pt_1'].' '.implode(' ,',$error).' '.$lang['n_ex_in_pt_2'].'<br/>';
               else
               {
                 $psm1 = mysql_query('SELECT * FROM `wm_smiles` where `type` = "sm";');
                 $allsmiles=array();
                 while ($psm = mysql_fetch_assoc($psm1))
                 {
                   $t_arr=array();
                   $t_arr = explode('|',$psm['pattern']);
                   $a=0;
                   while($t_arr[$a])
                   {
                      $allsmiles[] =  $t_arr[$a];
                      $a++;
                   }  
                 }
                 $error2 = array(); 
                 $i = 0;
                 while($patt[$i])
                 {
                   if(in_array($patt[$i],$allsmiles))
                     $error2[] = $i+1;
                   $i++;  
                 }
                 if($error2)
                  $err .= $lang['pat_exis_1'].' '.implode(' ,',$error2).' '.$lang['pat_exis_2'].'<br/>';
               }   
             }
         
             $file=$_FILES['file']['name'];
             if(!$file)
               $err .= $lang['file_not_ch'].'<br/>';
             else
             {
               $ext=mb_strtolower(getextension($file));
               if($ext!='png' and $ext!='gif')
                  $err .= $lang['ext_sm_wrong'].'<br/>';
               else
               {
                 $size=ceil($_FILES['file']['size']/1024);
                 if($size > 50)
                   $err .= $lang['file_bgg_50'].'<br/>';   
               }
             }
         
          }
          if($save and !$err)
          {
             $num=mysql_result(mysql_query('SELECT max(`id`+0) FROM `wm_smiles`;'), 0)+1;
             mysql_query("INSERT INTO `wm_smiles` SET `refid`='" . $cat . "', `type`='sm', `pattern`='" . mysql_real_escape_string($pattern) . "', `image`='" . mysql_real_escape_string($num.'.'.$ext) . "'  ;");
             move_uploaded_file($_FILES['file']['tmp_name'], $parent.'sourse/smiles/'.$num.'.'.$ext);
             
             require_once ('inc/admin/head_info.php');
             echo $lang['sm_added'].'<br/><br/>';
             echo '</td></table><center>';
             echo '<form style="display: inline" action="./?do=config&amp;act=smiles&amp;in=cat&amp;d='.$cat.'" method="post"><input class="edit" type="submit" value="'.$lang['continue'].'"/></form>';
             echo '</center>';
             require_once ('inc/admin/fin_info.php');
             exit();
          }
          require_once ('inc/admin/head.php'); 
          echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['sm_set'].' | '.$lang['new_smile'].'</div>';
          if($err)
            echo '<div class="rmenu">'.$err.'</div>';
          echo '<form action="./?do=config&amp;act=smiles&amp;in=addsm&amp;save=save" method="post" enctype="multipart/form-data">';
          echo '<div class="elem">'.$lang['sm_cat_t'].'<br/><select name="cat">';
          while($raz = mysql_fetch_array($raz1))
          {
            echo '<option value="'.$raz['id'].'" '.($cat==$raz['id'] ? 'selected="selected"' : ($raz['id']==$d ? 'selected="selected"' : '' )).'>'.$raz['pattern'].'</option>';  
          }
          echo '</select></div>';
          echo '<div class="elem">';
          echo $lang['pattern_n'].'<br/><textarea class="edit" name="text" cols="30" rows="2">'.htmlentities($pattern, ENT_QUOTES, 'UTF-8').'</textarea><br/>';
          echo $lang['image_50'].'<br/><input type="file" name="file"/>';
          echo '</div>';
          echo '<div class="elem"><input class="edit" type="submit" value="'.$lang['create'].'" /></div></form>';
          echo '<div class="helem">&nbsp;</div>';
          echo '<a class="url" href="./?do=config&amp;act=smiles">'.$lang['sm_set'].'</a><br/>';
          echo '<a class="url" href="./?do=adm">'.$lang['adminpanel'].'</a><br />';
          require_once ('inc/admin/fin.php');  
       }
       else
       {
          require_once ('inc/admin/head.php'); 
          echo '<div class="phdr"><a href="./?act=smiles">'.$lang['sm_set'].'</a> | '.$lang['new_smile'].'</div>';
          echo '<div class="menu">'.$lang['no_cat_cr'].'<br/><a href="./?act=smiles">'.$lang['back'].'</a></div>';
          echo '<div class="bmenu">&nbsp;</div>';
          require_once ('inc/admin/fin.php'); 
       }
    } 
    elseif($act=='editsm')
    {
       if($d)
       {
          $ct1 = mysql_query('SELECT * FROM `wm_smiles` where `type` = "sm" AND `id`="'.$d.'" LIMIT 1;');
          if(mysql_num_rows($ct1))
          {
            $ct = mysql_fetch_array($ct1);
            $cat=intval(abs($_POST['cat']));  
            $save=$_GET['save'];
            if($save and $cat)
            {
               $ctr1 = mysql_query('SELECT * FROM `wm_smiles` where `type` = "ct" AND `id`="'.$cat.'" LIMIT 1;');
               if(!mysql_num_rows($ctr1))
                 $err .= $lang['cat_empty'].'<br/>';
               else
                 $ctr = mysql_fetch_array($ctr1);
               $pattern = trim($_POST['text']);
               if (!$pattern)
                 $err .= $lang['pat_empty'].'<br/>';
               else
               {
                 $patt = explode('|',$pattern);
                 $i=0;
                 $error = array();
                 $cnt = count($patt);
                 while($cnt > $i)
                 {
                    if(preg_match("/[\s]/U", $patt[$i]) or !$patt[$i])
                      $error[] = $i+1;
                    $i++; 
                 }
                 if($error)
                  $err .= $lang['n_ex_in_pt_1'].' '.implode(' ,',$error).' '.$lang['n_ex_in_pt_2'].'<br/>';
                 elseif($pattern!=$ct['pattern'])
                 {
                   $psm1 = mysql_query('SELECT * FROM `wm_smiles` where `type` = "sm";');
                   $allsmiles=array();
                   while ($psm = mysql_fetch_assoc($psm1))
                   {
                     $t_arr=array();
                     $t_arr = explode('|',$psm['pattern']);
                     $a=0;
                     while($t_arr[$a])
                     {
                        $allsmiles[] =  $t_arr[$a];
                        $a++;
                     }  
                   }
                   $pat_ar = explode('|',$ct['pattern']);
                   $error2 = array(); 
                   $i = 0;
                   while($patt[$i])
                   {
                     if(in_array($patt[$i],$allsmiles) and !in_array($patt[$i],$pat_ar))
                      $error2[] = $i+1;
                     $i++;  
                   }
                   if($error2)
                    $err .= $lang['pat_exis_1'].' '.implode(' ,',$error2).' '.$lang['pat_exis_2'].'<br/>';
                 } 
               }
            }
            if($save and !$err)
            {
               mysql_query("UPDATE `wm_smiles` SET `refid`='" . $cat . "', `pattern`='" . mysql_real_escape_string($pattern) . "' WHERE `id` = '".$d."';"); 
               require_once ('inc/admin/head_info.php');
               echo $lang['sm_changed'].'<br/>';
               echo '</td></table><center>';
               echo '<form style="display: inline" action="./?do=config&amp;act=smiles&amp;in=cat&amp;d='.$cat.'" method="post"><input class="edit" type="submit" value="'.$lang['continue'].'"/></form>';
               echo '</center>';
               require_once ('inc/admin/fin_info.php');
               exit(); 
            }
            require_once ('inc/admin/head.php');
            echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['sm_set'].' | '.$lang['sm_editing'].'</div>';   
            echo '<form action="./?do=config&amp;act=smiles&amp;in=editsm&amp;save=save&amp;d='.$d.'" method="post">';
            if($err)
              echo '<div class="rmenu">'.$err.'</div>';
            echo '<div class="elem"><img src="'.$parent.'sourse/smiles/'.$ct['image'].'" alt="" /> - '.$ct['image'].'';
            echo '</div>';  
            echo '<div class="elem">'.$lang['sm_cat_t'].'<br/><select name="cat">';
            $raz1 = mysql_query('SELECT * FROM `wm_smiles` where `type` = "ct" ;');
            while($raz = mysql_fetch_array($raz1))
            {
              echo '<option value="'.$raz['id'].'" '.($cat==$raz['id'] ? 'selected="selected"' : ($raz['id']==$ct['refid'] ? 'selected="selected"' : '' )).'>'.$raz['pattern'].'</option>';  
            }
            echo '</select></div>';
            echo '<div class="elem">';
            echo $lang['pattern_n'].'<br/><textarea class="edit" name="text" cols="30" rows="2">'.($pattern ? htmlentities($pattern, ENT_QUOTES, 'UTF-8') : htmlentities($ct['pattern'], ENT_QUOTES, 'UTF-8')).'</textarea>';
            echo '</div>';
            echo '<div class="elem"><input class="edit" type="submit" value="'.$lang['save'].'" /></div></form>';
            echo '<div class="helem">&nbsp;</div>';
            $rz1 = mysql_query('SELECT * FROM `wm_smiles` where `type` = "ct" AND `id`="'.$ct['refid'].'" LIMIT 1;');
            $rz = mysql_fetch_array($rz1);
            echo '<a class="url" href="./?do=config&amp;act=smiles&amp;in=cat&amp;d='.$rz['id'].'">'.$rz['pattern'].'</a><br/>';
            echo '<a class="url" href="./?do=config&amp;act=smiles">'.$lang['sm_set'].'</a><br/>';
            echo '<a class="url" href="./?do=adm">'.$lang['adminpanel'].'</a><br />';
            require_once ('inc/admin/fin.php');
          }
          else
          {
             header('Location: ./');
             exit();  
          }
        } 
       else
       {
           header('Location: ./');
           exit();
       }
    }
    elseif($act=='delsm')
    {
       if($d)
       {
          $ct1 = mysql_query('SELECT * FROM `wm_smiles` where `type` = "sm" AND `id`="'.$d.'" LIMIT 1;');
          if(mysql_num_rows($ct1))
          {
            $ct = mysql_fetch_array($ct1);
            require_once ('inc/admin/head_info.php');
            echo $lang['del_sm_qa'].'<br/>';
            echo '</td></table><center>';
            echo '<form style="display: inline" action="./?do=config&amp;act=smiles&amp;in=delsm2&amp;d='.$d.'" method="post"><input class="edit" type="submit" value="'.$lang['delete'].'"/></form>';
            echo '<form style="display: inline" action="./?do=config&amp;act=smiles&amp;in=cat&amp;d='.$ct['refid'].'" method="post"><input class="edit" type="submit" value="'.$lang['cancel'].'"/></form>';
            echo '</center>';
            require_once ('inc/admin/fin_info.php');
          }
          else
          {
             header('Location: ./');
             exit();  
         }
       } 
       else
       {
           header('Location: ./');
           exit();
       }    
    }
    elseif($act=='delsm2')
    {
        if($d)
       {
          $ct1 = mysql_query('SELECT * FROM `wm_smiles` where `type` = "sm" AND `id`="'.$d.'" LIMIT 1;');
          if(mysql_num_rows($ct1))
          {
            $ct = mysql_fetch_array($ct1);  
            mysql_query("DELETE FROM `wm_smiles` WHERE `type` = 'sm' AND `id` = '".$d."';");
            if(file_exists($parent.'sourse/smiles/'.$ct['image']))
              unlink($parent.'sourse/smiles/'.$ct['image']);
            
            require_once ('inc/admin/head_info.php');
            echo ''.$lang['sm_deleted'].'<br/>';
            echo '</td></table><center>';
            echo '<form style="display: inline" action="./?do=config&amp;act=smiles&amp;in=cat&amp;d='.$ct['refid'].'" method="post"><input class="edit" type="submit" value="'.$lang['continue'].'"/></form>';
            echo '</center>';
            require_once ('inc/admin/fin_info.php'); 
          }
          else
          {
             header('Location: ./');
             exit();  
          }
       } 
       else
       {
           header('Location: ./');
           exit();
       }   
    } 
  }
  elseif($act=='lang')
  {
    require_once ('inc/admin/head.php');
    echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['lang_rul'].'</div>';  
    echo '<div class="elem"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/locale.png" /> <a href="?do=config&act=slang">'.$lang['sys_lang_set'].'</a></div>';
    echo '<div class="elem"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/alllang.png" /> <a href="?do=config&act=alllang">'.$lang['sys_lang_all'].'</a></div>';
    echo '<div class="helem">&nbsp;</div>';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
    require_once ('inc/admin/fin.php');
  }
  elseif($act=='alllang')
  {
    require_once ('inc/admin/head.php');
    echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['lang_rul'].'</div>';  
    echo '<form action="./?do=config&amp;act=save_alllang" method="post">';
    echo '<div class="elem">'.$lang['choose_lang_all'].'<hr/>';
    $path = 'inc/lang';
    $dir = opendir($path);
    while ($file = readdir($dir))
    { 
      if ((is_dir($path.'/'.$file)) and ($file !=".")&&($file !=".."))
      {
        if(!preg_match("/[^1-9a-z]+/",$file))  
          echo ($file == $set['lang'] ? '&nbsp;<img src="themes/admin/'.$set['admintheme'].'/notch.png" />&nbsp;' : '<input type="checkbox" name="lang_'.$file.'" value="1" '.(in_array($file,$all_langs) ? 'checked="checked"' : '').'/>').' <img src="sourse/lang/'.$file.'.png" /> '.file_get_contents($path.'/'.$file.'/lang.dat').'<br/>';
        $i++;
      }
    }
    closedir($dir);
    echo '</div>';
    echo '<div class="elem"><input class="edit" type="submit" value="'.$lang['save'].'" /></div></form>';
    echo '</form>';       
    echo '<div class="helem">&nbsp;</div>';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
    require_once ('inc/admin/fin.php');   
  }
  elseif($act=='save_alllang')
  {
    $i=0;
    $on_langs = array();
    $path = 'inc/lang';
    $dir = opendir($path);
    while ($file = readdir($dir))
    { 
      if ((is_dir($path.'/'.$file)) and ($file !=".")&&($file !=".."))
      {
        if(!preg_match("/[^1-9a-z]+/",$file))
        {  
          if($_POST['lang_'.$file])
            $on_langs[] = $file;
        }  
        $i++;
      }
    }
    closedir($dir);
    if(!in_array($set['lang'],$on_langs))
      $on_langs[] = $set['lang'];
      
    mysql_query("UPDATE `wm_settings` SET `value` = '".serialize($on_langs)."' WHERE `key` = 'inst_lang';");
    require_once ('inc/admin/head_info.php');
    echo ''.$lang['ch_saved'].'<br/>';
    echo '</td></table><center>';
    echo '<form style="display: inline" action="./?do=config&amp;act=alllang" method="post"><input class="edit" type="submit" value="'.$lang['continue'].'"/></form>';
    echo '</center>';
    require_once ('inc/admin/fin_info.php');
  }
  elseif($act=='slang')
  {
    require_once ('inc/admin/head.php');
    echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['lang_rul'].'</div>';  
    echo '<form action="./?do=config&amp;act=save_lang" method="post">';
    echo '<div class="elem">'.$lang['ch_lan_system'].'<hr/>';
    $i=0;
    while ($all_langs[$i])
    {
      $file = $all_langs[$i];
      echo '<input type="radio" name="lang" value="'.$file.'" '.ch($set['lang'],$file).'/> <img src="sourse/lang/'.$file.'.png" /> '.file_get_contents('inc/lang/'.$file.'/lang.dat').'<br/>';
      $i++;
    }
    echo '</div>';
    echo '<div class="elem"><input class="edit" type="submit" value="'.$lang['save'].'" /></div></form>';
    echo '</form>';       
    echo '<div class="helem">&nbsp;</div>';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
    require_once ('inc/admin/fin.php');    
  }
  elseif($act=='save_lang')
  {
    $ln = trim($_POST['lang']);
    if(!in_array($ln,$all_langs))
    {
      header('Location: ./');
      exit();  
    }
    mysql_query("UPDATE `wm_settings` SET `value` = '".$ln."' WHERE `key` = 'lang';");
    require_once ('inc/admin/head_info.php');
    echo ''.$lang['ch_saved'].'<br/>';
    echo '</td></table><center>';
    echo '<form style="display: inline" action="./?do=config&amp;act=slang" method="post"><input class="edit" type="submit" value="'.$lang['continue'].'"/></form>';
    echo '</center>';
    require_once ('inc/admin/fin_info.php');  
  }
  elseif($act=='renew')
  {
    require_once ('inc/admin/head.php');
    echo '<div class="helem"><img width="24" height="24" src="themes/admin/'.$set['admintheme'].'/preferences.png" /> '.$lang['renew'].'</div>';
    if($set['update'])
    {
      $new_data = @file_get_contents('http://wikimobile.su/update.php?version='.$set['wiki_version']);
      if(!$new_data)
      {
        echo '<div class="elem">'.$lang['u_url_denied'].'</div>';
        mysql_query("UPDATE `wm_settings` SET `value` = '0' WHERE `key` = 'update';");  
      }    
      elseif(substr($new_data,0,4)!= 'DATA')
        echo '<div class="elem">'.$lang['u_inv_url'].'</div>';
      else
      {
        $upd_arr = explode('<version>',$new_data);
        $new_count = count($upd_arr)-1;
        if($new_count)
        {
          $i = 1;
          while($i <= $new_count)
          {
            $version_data = explode('<i>',$upd_arr[$i]);  
            echo '<div class="elem"><img width="14" height="14" src="themes/admin/'.$set['admintheme'].'/renew.png" /> <a href="'.out($version_data[2]).'">'.out($version_data[0]).'</a><hr/><small>'.str_replace('[br/]', '<br/>', out($version_data[1])).'</small></div>';
            $i++;  
          }
        }
        else
        {
          echo '<div class="elem">'.$lang['u_no_upd'].'</div>';  
        } 
      }    
    }
    else
    {
      echo '<div class="elem">'.$lang['upd_off'].'</div>';   
    }  
    echo '<div class="helem">&nbsp;</div>';
    echo '<a class="url" href="?do=adm">'.$lang['adminpanel'].'</a><br />';
    require_once ('inc/admin/fin.php'); 
  }
}
else
{
	header ('Location: ./?do=404');
}

?>