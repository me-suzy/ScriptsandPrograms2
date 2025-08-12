 <?php

$username = "";
$password = "";
$host = "localhost";
$database = "";
mysql_connect($host,$username,$password) or die("Cannot connect to the database.<br>" . mysql_error());

mysql_select_db($database) or die("Cannot select the database.<br>" . mysql_error());


?>
