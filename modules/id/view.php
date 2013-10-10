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
    if($use_not_st_lang)
      $mess_err = $lang['its_trans_vers'].' <a href="'.$parent.''.($mod_rewrite ? 'wiki/'.prw(($curr_name ? $curr_name : $wikipage['name'])) : '?uid='. $id).'"> '.out(($curr_name ? $curr_name : $wikipage['name'])).' </a> ';      
    require_once ('inc/head.php');
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
    
    $nat = abs(intval($_GET['p']));
    if (!$nat) $nat=1;
    $nach=($nat-1)*$set['symbols'];
    
    if(!file_exists($wikipage['path'].'.'.$stat_lang.'.txt'))
    {
      echo '<h2>'.$lang['pg_not_its_warn'].'</h2><hr/>';
      echo '&nbsp;&nbsp; '.$lang['pg_not_its_lang'];
      if(can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
        echo ' '.$lang['pg_not_its_ln2'];
      echo '<br/><b>'.$lang['pg_all_to_lang'].'</b><hr/>';
      $i=0;
      while($all_langs[$i])
      {
        if(file_exists($wikipage['path'].'.'.$all_langs[$i].'.txt'))
          echo '&nbsp;&nbsp; <a href="'.$parent.($mod_rewrite ? 'wiki/'.prw($wikipage['name']).'/'.$all_langs[$i] : '?uid='.$id.'&amp;lang='.$all_langs[$i]).'"><img src="'.$parent.'sourse/lang/'.$all_langs[$i].'.png" /> '.file_get_contents('inc/lang/'.$all_langs[$i].'/lang.dat').'</a><br/>';  
        $i++;  
      }
      echo '</div>';
      if(can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin'))
        echo '<div class="add"><form action="'.$parent.'?"><input type="hidden" name="uid" value="'.$wikipage['id'].'" /><input type="hidden" name="do" value="edit" />'.($use_not_st_lang ? '<input type="hidden" name="lang" value="'.$stat_lang.'" />' : '').'<input type="submit" value="'.$lang['cr_pg_lang'].'" class="edit" /></form></div>';
      echo '<hr/>';
      echo '<a href="'.$parent.'?do=sod"><img src="'.$parent.'themes/engine/'.$set['theme'].'/note_go.png" /> '.$lang['content'].'</a><br />';  
    }
    else
    {  
      if(!file_exists($wikipage['path'].'.'.$stat_lang.'.temp.dat'))  //если нет кэша парсим все наново
      {
        $textbody=file_get_contents($wikipage['path'].'.'.$stat_lang.'.txt');
        require_once ('inc/parser.php');
        $wiki=new WikiParser;
        $textarray = $wiki->parse($textbody);
        $len=count($textarray);
        for($i=0;$i<=$len-1;$i++)
        {
          $textarray_2[]=gz_pack($textarray[$i]);  
        }
        file_put_contents($wikipage['path'].'.'.$stat_lang.'.temp.dat',implode('||____||',$textarray_2));   //записываем кэш
      }
      else //есть кэш, берем файл из него
      {
        $textbody=file_get_contents($wikipage['path'].'.'.$stat_lang.'.temp.dat');
        $textbody = explode('||____||',$textbody);
        $len= count($textbody);
        $textarray=array();
        for($i=0;$i<=$len-1;$i++)
        {
         $textarray[]=gz_unpack($textbody[$i]);  
        }
      } 

      $textarray=$textarray[$nat-1];
      require_once ('inc/preparse.php');
      $textarray = preparse($textarray);
      echo $textarray;  //выводим пропарсенный текст
      echo '<div style="clear: both;"></div>';
      
      ////////////////////////////////
      /// Сноски, пока выпиливаем
      ///////////////////////////////
      //if($wiki->fnt_count)   //выводим сноски, данные получили от парсера
      //{
      //  echo '<hr/><small>';
      //  $i=0;
      //  while($wiki->fnt[$i])
      //  {
      //     $a=$i+1;
      //     echo '<a href="#fnt__'. $a.'" id="fn__'. $a.'" name="fn__'. $a.'">'. $a.')</a>'.$wiki->fnt[$i].'</br>';
      //     $i++;  
      //  }
      //  echo '</small>';  
      //}
      ////////////////////////////////////////
      
      
      if($wikipage['last_edit'])
	  echo '<div align="right"><span class="small">'.$lang['last_chang_g'].' '.date("d.m.Y / H:i",$wikipage['last_edit']).'  '.$lang['from_user'].' <a href="'.$parent.'?do=user&amp;us='.$wikipage['id_edit'].'" style="font-size:small">'.$wikipage['user_edit'].'</a></span></div>';

      if ($mod_rewrite)  //постраничная навигация
        $vpage=vpage($len,$parent.'wiki/'.prw($wikipage['name']).''.($use_not_st_lang ? '/'.$stat_lang : '').'/',1);
      else
        $vpage=vpage($len,'?uid='.$id.''.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;',1);
      if ($vpage)
      {
	    echo $vpage;
      }
      echo '</div>';
      
      if ($wikipage['comments'])
      {
        $disc = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_discusion` WHERE `page` = '".$id."' "), 0);
        echo '<div class="disscusion"><a href="'.$parent.'?uid='.$id.'&amp;do=discusion">'.$lang['discussion'].' ('.$disc.')</a></div>';
      }
      echo '<div class="add"><form action="'.$parent.'?"><input type="hidden" name="uid" value="'.$id.'" /><input type="hidden" name="do" value="edit" />'.($use_not_st_lang ? '<input type="hidden" name="lang" value="'.$stat_lang.'" />' : '').'<input type="submit" value="'.((can('edit_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin')) ? $lang['change'] : $lang['sour_code']).'" class="edit" /></form></div>';
      $getnav=func('getnav',$wikipage['path']);
      if ($getnav or $_GET['wiki'])
        echo '<hr /><img src="'.$parent.'themes/engine/'.$set['theme'].'/note_go.png" /> '.$getnav.'<hr />';
      if (can('delete_stat') and ($wikipage['can_edit']!='admin' or $rights=='admin') and $id!=1)
      {
        echo '<a href="'.$parent.'?uid='.$id.'&amp;do=move'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'&amp;id='.str_replace('/',':',cut_data($wikipage['dir'])).'"><img src="'.$parent.'themes/engine/'.$set['theme'].'/config.png" /> '.$lang['d_move_page'].'</a> | ';
        echo '<a href="'.$parent.'?uid='.$id.'&amp;do=delst'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'">'.$lang['d_th_page'].'</a><br />';
      }
      echo '<a href="'.$parent.'?uid='.$id.'&amp;do=olang'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'"><img src="'.$parent.'themes/engine/'.$set['theme'].'/locale.png" /> '.$lang['other_lang_view'].'</a><br />';
      echo '<a href="'.$parent.'?uid='.$id.'&amp;do=history'.($use_not_st_lang ? '&amp;lang='.$stat_lang : '').'"><img src="'.$parent.'themes/engine/'.$set['theme'].'/history.png" /> '.$lang['pg_th_hist'].'</a><br />';
    }
    require_once ('inc/fin.php');
?>