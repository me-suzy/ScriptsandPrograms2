<?php
/*
include.php
Author : Thomas Whitecotton
Email  : admin@ciamosbase.com
Website: http://www.simplyphp.com
*/

/*
 Database host
 Default: localhost
*/
$dbhost = "localhost";

/*
 Database name
*/
$dbname = "";

/*
 Database username and password
*/
$dblogin ="";
$dbpass = "";

$failure = "MySQL problem. Connection to ".$dbhost." failed.";

if(!$connect = @mysql_connect($dbhost, $dblogin, $dbpass)) {
	die($failure);
} else {
	if(!@mysql_select_db($dbname,$connect)) {
		die($failure);
	}
}
// Otherwise connection succeeds :-)
$dbtable = $dbname;
?>