<?
$database="db";
$sqlhost="YourHost"; 
$sqluser="User"; 
$sqlpass="Pass";
mysql_connect($sqlhost,$sqluser,$sqlpass) OR DIE("1"); 
mysql_select_db($database) OR DIE("1");
?>