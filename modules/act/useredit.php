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
  $us= intval(abs($_GET['us']));
  if($us)
  {
     $file1 = mysql_query('SELECT * FROM `wm_users` WHERE `id`="'.$us.'" LIMIT 1;');
     if(mysql_num_rows($file1))
     {
        if($rights=='admin' or $us==$user_id)
        {
        $action=$_GET['act']; 
        if($action=='save')
        {
          $sex=$_POST['sex'];
          if ($_POST['sex']!=1 and $_POST['sex']!=2)
            $err .= $lang['er_bad_gen'].'<br/>';
            
          $name = trim($_POST['name']);
          if(mb_strlen($name) > 50)
            $err .= $lang['er_dl_name'].'<br/>';
            
          $born_day = intval(abs($_POST['born_day']));
          if($born_day > 31 or $born_day < 0)
            $err .= $lang['er_bad_birth'].'<br/>';
          
          $born_month = intval(abs($_POST['born_month']));
          if($born_month > 12 or $born_month < 0)
            $err .= $lang['er_bad_mon'].'<br/>';  
          
          $born_year = intval(abs($_POST['born_year']));
          if($born_year)
          {
          if(mb_strlen($born_year)!=4)
            $err .= $lang['er_bad_year'].'<br/>';
          }
          
          if($born_day == 0 or $born_month==0 or !$born_year)
            $date= '';
          else
            $date=$born_day.':'.$born_month.':'.$born_year; 
          
          $place = trim($_POST['place']);
          if(mb_strlen($place) > 50)
           $err .= $lang['er_from'].'<br/>';  
          
          $icq = intval(abs($_POST['icq']));
          if(mb_strlen($icq ) > 50)
            $err .= $lang['er_icq'].'<br/>';    
          
          $site =  trim($_POST['site']);
          if(mb_strlen($site ) > 150)
            $err .= $lang['er_site'].'<br/>';
           
          $phone =  trim($_POST['phone']);
          if(mb_strlen($phone ) > 150)
            $err .= $lang['er_phone'].'<br/>';
            
          $about =  trim($_POST['about']);
          if(mb_strlen($about ) > 300)
            $err .= $lang['er_about'].'<br/>';          
          
        } 
        if(!$err and $action=='save')
        { 
          mysql_query("UPDATE `wm_users_info` SET
          `sex`='" . $sex . "',
          `name`='" . mysql_real_escape_string(out($name)) . "',
          `born`='" . mysql_real_escape_string($date) . "',
          `place`='" . mysql_real_escape_string(out($place)) . "',
          `icq`='" . mysql_real_escape_string($icq) . "',
          `site`='" . mysql_real_escape_string(out($site)) . "',
          `phone`='" . mysql_real_escape_string(out($phone)) . "',
          `about`='" . mysql_real_escape_string(out($about)) . "'
           WHERE `userid` = '".$us."';");  
          $info_mess = $lang['ch_saved'];
        } 
         
        function ch2($string1,$string2)
        {
          if ($string1==$string2) return 'checked="checked"';
          else return '';
        }
        function ch($string1,$string2)
        {
          if ($string1==$string2) return 'selected="selected"';
          else return '';
        }
        $dat1 = mysql_query('SELECT * FROM `wm_users_info` WHERE `userid`="'.$us.'" LIMIT 1;');
        $dat = mysql_fetch_array($dat1);
        $born = explode(':',$dat['born']);
        require_once ('inc/head.php');
        echo '</div><form action="./?do=useredit&amp;act=save&amp;us='.$us.'" method="post"><div class="stat">';
        echo '<h2>'.$lang['anc_edit'].' '.$file['name'].':</h2><hr/>';
        echo '<b>'.$lang['a_gen'].'</b><br/>';
        echo '<input type="radio" name="sex" value="2" '.ch2($dat['sex'],2).' /> '.$lang['male'].'<br/><input type="radio" name="sex" value="1" '.ch2($dat['sex'],1).' /> '.$lang['female'].' <hr/>';
        echo '<b>'.$lang['a_name'].'</b><br/>';
        echo '&nbsp; <input type="text" name="name" class="edit2" value="'.$dat['name'].'" /><hr/>';
        echo '<b>'.$lang['a_birth'].'</b><br/>';
        echo '&nbsp; '.$lang['ed_day'].' <select class="edit2" name="born_day" size="1">';
        echo '<option value="0">-</option>';
        echo '<option value="1" '.ch($born[0],1).' >1</option>';
        echo '<option value="2" '.ch($born[0],2).' >2</option>';
        echo '<option value="3" '.ch($born[0],3).' >3</option>';
        echo '<option value="4" '.ch($born[0],4).' >4</option>';
        echo '<option value="5" '.ch($born[0],5).' >5</option>';
        echo '<option value="6" '.ch($born[0],6).' >6</option>';
        echo '<option value="7" '.ch($born[0],7).' >7</option>';
        echo '<option value="8" '.ch($born[0],8).' >8</option>';
        echo '<option value="9" '.ch($born[0],9).' >9</option>';
        echo '<option value="10" '.ch($born[0],10).' >10</option>';
        echo '<option value="11" '.ch($born[0],11).' >11</option>';
        echo '<option value="12" '.ch($born[0],12).' >12</option>';
        echo '<option value="13" '.ch($born[0],13).' >13</option>';
        echo '<option value="14" '.ch($born[0],14).' >14</option>';
        echo '<option value="15" '.ch($born[0],15).' >15</option>';
        echo '<option value="16" '.ch($born[0],16).' >16</option>';
        echo '<option value="17" '.ch($born[0],17).' >17</option>';
        echo '<option value="18" '.ch($born[0],18).' >18</option>';
        echo '<option value="19" '.ch($born[0],19).' >19</option>';
        echo '<option value="20" '.ch($born[0],20).' >20</option>'; 
        echo '<option value="21" '.ch($born[0],21).' >21</option>'; 
        echo '<option value="22" '.ch($born[0],22).' >22</option>';     
        echo '<option value="23" '.ch($born[0],23).' >23</option>';  
        echo '<option value="24" '.ch($born[0],24).' >24</option>';   
        echo '<option value="25" '.ch($born[0],25).' >25</option>';            
        echo '<option value="26" '.ch($born[0],26).' >26</option>';
        echo '<option value="27" '.ch($born[0],27).' >27</option>';
        echo '<option value="28" '.ch($born[0],28).' >28</option>';
        echo '<option value="29" '.ch($born[0],29).' >29</option>'; 
        echo '<option value="30" '.ch($born[0],30).' >30</option>';
        echo '<option value="31" '.ch($born[0],31).' >31</option>';
        echo '</select>';
        echo '<br/>&nbsp; '.$lang['ed_month'].' <select class="edit2" name="born_month" size="1">';
        echo '<option value="0">-</option> 
        <option value="1" '.ch($born[1],1).' >'.$lang['m1'].'</option>
        <option value="2" '.ch($born[1],2).' >'.$lang['m2'].'</option>
        <option value="3" '.ch($born[1],3).' >'.$lang['m3'].'</option>
        <option value="4" '.ch($born[1],4).' >'.$lang['m4'].'</option>
        <option value="5" '.ch($born[1],5).' >'.$lang['m5'].'</option>
        <option value="6" '.ch($born[1],6).' >'.$lang['m6'].'</option>
        <option value="7" '.ch($born[1],7).' >'.$lang['m7'].'</option>
        <option value="8" '.ch($born[1],8).' >'.$lang['m8'].'</option>
        <option value="9" '.ch($born[1],9).' >'.$lang['m9'].'</option>
        <option value="10" '.ch($born[1],10).' >'.$lang['m10'].'</option>
        <option value="11" '.ch($born[1],11).' >'.$lang['m11'].'</option>
        <option value="12" '.ch($born[1],12).' >'.$lang['m12'].'</option>';
        echo '</select>';
        echo '<br/>&nbsp; '.$lang['year_r'].' <input type="text" class="edit2" name="born_year" size="4" maxlength="4" value="'.$born[2].'" /><hr/>';
        echo '<b>'.$lang['a_from'].'</b><br/>';
        echo '&nbsp; <input type="text" class="edit2" name="place"  value="'.$dat['place'].'" /><hr/>';
        echo '<b>'.$lang['a_icq'].'</b><br/>';
        echo '&nbsp; <input type="text" class="edit2" name="icq"  value="'.$dat['icq'].'" /><hr/>';
        echo '<b>'.$lang['a_site'].'</b><br/>';
        echo '&nbsp; <input type="text" class="edit2" name="site"  value="'.$dat['site'].'" /><hr/>';
        echo '<b>'.$lang['a_ph_mod'].'</b><br/>';
        echo '&nbsp; <input type="text" class="edit2" name="phone"  value="'.$dat['phone'].'" /><hr/>';
        echo '<b>'.$lang['a_a_me'].'</b><br/>';
        echo '&nbsp; <textarea class="edit2" rows="3" name="about">'.$dat['about'].'</textarea>';
        
        echo '</div><hr />';
        echo '<div class="add"><input type="submit" value="'.$lang['save'].'" class="edit" /></div></form>';
        echo '<a href="./?do=user&amp;us='.$us.'"><img src="themes/engine/'.$set['theme'].'/register.png" /> '.$lang['anketa'].'</a><br />';
        require_once ('inc/fin.php');
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
  }
  else
  {
    header ('Location: ./?do=404');
  }  
?>
