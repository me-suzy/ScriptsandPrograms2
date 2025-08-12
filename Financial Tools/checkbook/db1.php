<?
$host="";
$db_user="";
$db_password="";
$database="";
$dbh=mysql_connect ($host, $db_user, $db_password) or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ($database);
?>