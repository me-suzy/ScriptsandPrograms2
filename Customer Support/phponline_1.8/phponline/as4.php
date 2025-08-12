<?php
include_once('noca.php');
include_once('rcq.php');

$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
mysql_select_db($DBDatabase,$dbh);
mysql_query("DELETE FROM bvars WHERE bname='adm_login_time'",$dbh);
mysql_close($dbh);

echo "test2=123&sema1=0&test=123";


?>