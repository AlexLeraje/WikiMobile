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

function display_error($error='')
{ 
    global $set, $lang;
    if (!$error) $error=$lang['uncerr'];
    $out .= '<div class="error">'.$error.'</div>';
    return $out;
}
?>
