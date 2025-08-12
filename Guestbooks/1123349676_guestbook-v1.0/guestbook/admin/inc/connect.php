<?
// Guestbook v1.0 
// Copyright 2005 Armand Niculescu
// Website: www.armandniculescu.com

$hostname="localhost"; 
$database="guestbook";
$username="";
$password="";

mysql_connect($hostname, $username, $password) or die (mysql_error());
mysql_select_db($database) or die (mysql_error()); 

?>
