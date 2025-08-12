<?php
include("config.php");
$dbh=mysql_connect ("localhost", "$username", "$password") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("$dbase");
$query = "CREATE TABLE `emails` (
`username` VARCHAR( 40 ) NOT NULL ,
`email` VARCHAR( 40 ) NOT NULL ,
`password` VARCHAR( 40 ) NOT NULL
) TYPE = MYISAM ;"
mysql_query($query);
mysql_close();
?>
