<?
require ('functions.php');

	$dbuser = '';
	$dbpass = '';
	$dbhost = '';
	$dbname = '';

	$dblink = mysql_connect($dbhost, $dbuser, $dbpass);
	if(!$dblink) {echo "ERROR:  Could not make connection to the database."; exit;}
	mysql_select_db($dbname, $dblink);
?>