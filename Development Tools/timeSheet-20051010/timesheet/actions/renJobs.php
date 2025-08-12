<?php

$rate = $_REQUEST[rate];

foreach($_REQUEST[desc] as $key=>$value)
{
	$Q="UPDATE jobs SET jobDesc='$value',rate='".floatval($rate[$key])."' WHERE id='".intval($key)."' AND user_id='$_SESSION[id]'";
	print $Q;
	mysql_query($Q);
}

if ($_REQUEST['REF'])
{
    header("Location: ".base64_decode($_REQUEST[REF]));
}
else header("Location: $_SERVER[HTTP_REFERER]");

?>
