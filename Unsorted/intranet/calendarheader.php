<?php
include("../config.php");
$ipaddy = $BHCIntranet;
if ($ipaddy == "192.168.1.69") { $ipaddy = exec("cat ../faux.ip"); }
/*
if (substr($ipaddy, 0, 9) != "192.168.1" or $ipaddy == "192.168.1.254")
	{
	dbconnect($dbusername,$dbuserpasswd);
	$result = mysql_query("select realipaddy, fauxipaddy from visitors
where realipaddy = '$ipaddy'");
	$row = mysql_fetch_row($result);
	$ipaddy = $row[1];
	}
*/
$appheaderstring='Calendar';
include("../header.php");
?>
