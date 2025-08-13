<?
include("inc/config.php");

$db = mysql_connect("$hostname", "$user","$pass");
mysql_select_db("$database",$db);

?>