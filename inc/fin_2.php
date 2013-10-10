<?
defined('MOBILE_WIKI') or die('Demon laughs');

function decrypt_down($string)  
{   
  $string = base64_decode(str_replace('*','=',$string));
  return $string;  
}
$pr1 = decrypt_down('c3R5bGU9IndpZHRoOiAxMDAlO2JvcmRlcjogMHB4OyI+PHRyPjx0ZD48YSBzdHlsZT0iZg**');
if(!$parent) $parent = './';

if($path_p and $act!='erazd' and $rights=='admin') echo '<a href="'.$parent.'?id='.$path_p.'&amp;do=erazd"><img src="'.$parent.'themes/engine/'.$set['theme'].'/process.png" /> '.$lang['properties_raz'].'</a><br />';
echo '<a href="'.$parent.'?do=late"><img src="'.$parent.'themes/engine/'.$set['theme'].'/add_to_folder.png" /> '.$lang['last_changes'].'</a> '.($user_id ? pages_new(1) : '').'<br />';
echo '<a href="'.$parent.'?do=newdisk"><img src="'.$parent.'themes/engine/'.$set['theme'].'/disk_new.png" /> '.$lang['n_talk'].'</a> '.($user_id ? disk_new(1) : '').'<br />';  

if(!$index_page)
  echo  '<a href="'.$set['site'].'"><img src="'.$parent.'themes/engine/'.$set['theme'].'/home.png" /> '.$lang['index_page'].'</a><br />';
$l_down1= mysql_query("SELECT * FROM `wm_ads` WHERE `type` = '0' ;");

function gz_unpack_down($string)
{
  return gzinflate($string);  
}

$pr2 = gz_unpack_down(decrypt_down('y88r0S3OrEq1UjA0KqiwVlLIKEpNs1XKKAEA'));

$pr3 = decrypt_down(gz_unpack_down(decrypt_down('S/FwNPOptDRODC8pSAq3zEwMr8jxyfMz9MwyyY00sixPDSlODzPK1gIA')));

function put_cr()
{
 global $pr1,$pr2,$pr3;
 $pr4 = gz_unpack_down(decrypt_down('y870zU/KBAA*'));
 return $pr1.$pr2.$pr3.$pr4.'le</a';   
}

$online_users = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_users` WHERE `lastvisit` > '" . (time() - 300) . "'"), 0);
$online_guests = mysql_result(mysql_query("SELECT COUNT(*) FROM `wm_guests` WHERE `lastvisit` > '" . (time() - 300) . "'"), 0);
echo '<hr/><table cellspacing="0" cellpadding="0" class="tdown"><tr><td>'.$lang['numb_pages'].' '.number_of_pages('data').'</td><td style="text-align:right;"><a href="'.$parent.'?do=online">'.$lang['online'].' '.$online_users.'/'.$online_guests.'</a></td></tr></table>';

if (mysql_num_rows($l_down1))
{
  while($l_down = mysql_fetch_assoc($l_down1))
  {
    if(($l_down['time']+($l_down['view']*3600*24)) > time())  
        $out_reklam_down .= '<a href="'.$l_down['link'].'" style="'.$l_down['style'].'">'.$l_down['name'].'</a><br />';
  }
  if($out_reklam_down)
  {
    echo '<div class=reklam>';
    echo $out_reklam_down;  
    echo '</div>';   
  }
}

echo '<div class="down">';
if ($index_page)
  echo $set['counter_index'];
else
  echo $set['counter'];
echo '</div><table cellspacing="0" cellpadding="0" '.put_cr().'></td>';
echo '<td style="text-align:right;"><small>'.round(microtime(true) - $microtime, 4).' '.$lang['seconds'].'</small></td></tr></table>'; 
echo '</div></body>
</html>';

ob_end_flush();

?>