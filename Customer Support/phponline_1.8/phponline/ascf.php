<?php
include_once('noca.php');
include_once('rcq.php');

$CCode = $HTTP_POST_VARS['ccode'];


$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
mysql_select_db($DBDatabase,$dbh);
$res = mysql_query("SELECT * from cs WHERE ccode=\"$CCode\"",$dbh);
$UINFO = array();
while($row = mysql_fetch_array($res))
{
	$UINFO = $row;
}
mysql_close($dbh);

echo "test1=123&ascinit_sema=1&client_name=".$UINFO['name']."&client_ip=".$UINFO['ip']."&conf_GEmailAddress=".$CONF['conf_GEmailAddress']."&test2=123";


?>