<?
// This script was made by Josh Rendek @ http://gamersnetwork.us/ Do not remove any of the copyrights please.
$server = 'localhost'; // Usually localhost
$user = ''; // the username to access your database
$pass = ''; // the password to access your database
$db = ''; // the database the files will be stored on
$dbLink = mysql_pconnect($server, $user, $pass) or die("Couldnt select user"); 
$dbC = mysql_select_db($db, $dbLink) or die("Couldnt select database"); 
$adminUser = 'demo';
$adminPass = 'pass';
$sotwPath = '/home/gamnet/www/scripts/gnSOTW/'; // make sure you leave the trailing slash
$copyright = "<center><br><b>Powered by:</b> <a href='http://gamersnetwork.us/'>gnSOTW v1.2</a></center>";
?>