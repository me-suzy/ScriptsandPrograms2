<?php
include_once('noca.php');
include_once('rcq.php');

$CCode = $HTTP_POST_VARS['ccode'];


if($CCode != "")
{
	list($usec, $sec) = explode(" ",microtime()); 
	$TTime = ((double)$sec)+((double)$usec);
	$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
	mysql_select_db($DBDatabase,$dbh);
	mysql_query("UPDATE cs SET assign=2 WHERE ccode=\"$CCode\"",$dbh);
	mysql_query("INSERT INTO msgdb VALUES($TTime,\"$CCode\",\"Client+loged+out.\",2,0)",$dbh);
	SetCCodeStatus($CCode,"1","1");
	mysql_close($dbh);
	SetPStatus("1");
}

echo "a1=1&a2=2&a3=3";



?>