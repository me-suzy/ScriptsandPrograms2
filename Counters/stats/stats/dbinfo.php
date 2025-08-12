<?
/*
version 0.1
Build by chris mccabe- under the gpl license
for updates and news or if you have feedback
http://scripts.maxersmix.com
*/
$username = "USER"; //the username you connect to the mysql server with
$password = "PASS"; //the password that you use to login to the server
$server = "localhost"; //the db server address example- mysqlserver.yourwebhost.com
$db_conn = "database"; //the database which your information will be stored


$conn = mysql_connect($server,$username,$password) or die ('cant connect to server: ' . mysql_error());
mysql_select_db("".$db_conn) or die ('can use the db : ' . mysql_error());
