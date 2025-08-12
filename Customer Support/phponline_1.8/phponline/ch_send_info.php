<?php
include_once('noca.php');
include_once('rcq.php');

$CCode = $HTTP_GET_VARS['ccode'];
$UN = $HTTP_GET_VARS['un'];
$Msg = "USERNAME: ".$UN;


list($usec, $sec) = explode(" ",microtime()); 
$TTime = ((double)$sec)+((double)$usec);

$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
mysql_select_db($DBDatabase,$dbh);
$res = mysql_query("INSERT INTO msgdb VALUES($TTime,\"$CCode\",\"$Msg\",2,0)",$dbh);
SetCCodeStatus($CCode,"1","1");


mysql_close($dbh);

echo "test1=123&test=tes1212";

?>