<?
$serveur = "localhost";
$database = "disco4";
$username = "root";
$password = "";
$link = mysql_connect($serveur,$username,$password) or die(mysql_error());
if (!isset($install)){$install="";}if ($install!="oui")
{mysql_select_db($database,$link) or die(mysql_error());}
?>