<?php
include_once('noca.php');
include_once('rcq.php');

$CCode = $HTTP_POST_VARS['ccode'];


$PRECONF = array();
$PREUSERPASS = array();
$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
mysql_select_db($DBDatabase,$dbh);
$res = mysql_query("SELECT * from bvars",$dbh);
while($row = mysql_fetch_array($res))
{
	if(substr($row['bname'],0,strlen("conf_")) == "conf_")
	{
		$PRECONF[$row['bname']] = $row['bvalue'];
		if(substr($row['bname'],0,strlen("conf_staff")) == "conf_staff")
		{
			list($v1,$v2,$v3) = explode(":",$row['bvalue']);
			$PREUSERPASS[$v1] = $v2;
		}
	}
}




$CONF = array();
$StaffIndex = 0;
foreach($HTTP_POST_VARS as $k=>$v)
{
	if(substr($k,0,strlen("conf_")) == "conf_")
	{
		if(substr($k,0,strlen("conf_staff")) == "conf_staff")
		{
			list($v1,$v2,$v3) = explode(":",$v);
			if($v2=='xxxxxxxx')
			{
				$v = $v1.":".$PREUSERPASS[$v1].":".$v3;
			}
			else
			{
				$v = $v1.":".base64_encode($v2).":".$v3;
			}
			$k="conf_staff".$StaffIndex;
			$StaffIndex++;
		}
		$CONF[$k] = $v;
	}
}


$CondStr = "";
$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
mysql_select_db($DBDatabase,$dbh);
foreach($CONF as $k=>$v)
{
	$v = str_replace("[nl]","\n",$v);
	$rv = mysql_query("UPDATE bvars SET bvalue=\"$v\" WHERE bname=\"$k\"",$dbh);
	if(mysql_affected_rows($dbh)==0 || $rv===false)
	{
		mysql_query("INSERT INTO bvars VALUES(\"$k\",\"$v\")",$dbh);
	}
}
mysql_close($dbh);


echo "test1=123&consema=1&status=Configuration Saved.&test=tes1212";

?>