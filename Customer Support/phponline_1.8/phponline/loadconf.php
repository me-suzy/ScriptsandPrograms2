<?php
include_once('noca.php');
include_once('rcq.php');

$CCode = $HTTP_POST_VARS['ccode'];

include('loadconfdb.php');

$CondStr = "";
foreach($CONF as $k=>$v)
{
	if(substr($k,0,strlen("conf_staff")) == "conf_staff")
	{
		list($v1,$v2,$v3) = explode(":",$v);
		$v = $v1.":xxxxxxxx:".$v3;
	}
	$CondStr .= ($k."=".str_replace("\n","[nl]",$v)."&");
}


echo "test1=123&consema=1&".$CondStr."test=tes1212";

?>