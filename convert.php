<?php

define('MOBILE_WIKI', 1);

mb_internal_encoding('UTF-8');
session_start();

require_once ('inc/db.php');
$connect = @ mysql_connect($db_host, $db_user, $db_pass) or die('cannot connect to db');
@ mysql_select_db($db_name) or die('cannot connect to db');
@ mysql_query("SET NAMES 'utf8'", $connect);

  
?>
