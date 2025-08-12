<?php
include_once('noca.php');
include_once('rcq.php');

$StaffUserName = $HTTP_POST_VARS['staffusername'];
$StaffPassword = $HTTP_POST_VARS['staffpassword'];


$StaffLogin = array();
foreach($CONF as $k=>$v)
{
	if(substr($k,0,strlen("conf_staff")) == "conf_staff")
	{
		list($v1,$v2,$v3) = explode(":",$v);
		$StaffLogin[$v1] = $v2;
		$StaffLoginPU[$v1] = $v3;
	}
}

if(!isset($CONF['conf_GCompanyName']))
{
	$StaffLogin['admin'] = 'YWRtMTIz';
	$StaffLoginPU['admin'] = 1;
}




$RV = 0;
$STV = 0;

if(isset($StaffLogin[$StaffUserName])==false)
{
	$RV=2;
	$STV = 1;
}

if(trim($StaffUserName)=='')
{
	$RV=2;
	$STV = 2;
}

if(($StaffLogin[$StaffUserName]==base64_encode($StaffPassword)) && $RV==0)
{
	$RV=1;
	$STV = 3;
	$ADMID = str_replace(array('=','+','/','_'),array('XxexX','XxpxX','XxsxX','XxuxX'),base64_encode($StaffLogin[$StaffUserName]));
	$ADMPU = $StaffLoginPU[$StaffUserName];
}
else
{
	$RV=2;
	$STV = 4;
}


echo "test1=123&loginstatus=$RV&admid=$ADMID&admpu=$ADMPU&ssttvv=$STV&fi213987123=12396192";


if(($HTTP_POST_VARS['debug']==1) || ($HTTP_GET_VARS['debug']==1))
{
	echo "\n";
	print_r($HTTP_POST_VARS);
	echo "\n";
	echo $STV;
}


?>