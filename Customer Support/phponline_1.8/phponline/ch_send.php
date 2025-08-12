<?php
include_once('noca.php');
include_once('rcq.php');

$CCode = $HTTP_POST_VARS['ccode'];
$LanguageSel = $HTTP_POST_VARS['language_sel'];
$Msg = $HTTP_POST_VARS['input'];
$WriteSignal = $HTTP_POST_VARS['write_signal'];


if(substr($WriteSignal,0,strlen('EOw3OkAE062f8628bz2y7v47vmW85q4c'))=='EOw3OkAE062f8628bz2y7v47vmW85q4c')
{
	$Msg = $WriteSignal;
}

if(substr($Msg,0,strlen('EOw3OkAE062f8628bz2y7v47vmW85q4c'))!='EOw3OkAE062f8628bz2y7v47vmW85q4c')
{

	$Msg = ':4-|ln|_4:'.$LanguageSel.':4-|ln|_4:'.$Msg;

}



list($usec, $sec) = explode(" ",microtime()); 
$TTime = ((double)$sec)+((double)$usec);

$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
mysql_select_db($DBDatabase,$dbh);
$res = mysql_query("INSERT INTO msgdb VALUES($TTime,\"$CCode\",\"$Msg\",2,0)",$dbh);
SetCCodeStatus($CCode,"1","1");


mysql_close($dbh);


	$s = "";
	foreach($_SERVER as $a=>$v)
	{
		$s .= "_SERVER['".$a."']=".$v."\n";
	}
	
	$fp = fopen ("/tmp/phponline_c1.log", "a");
	fputs($fp,$s);
	fclose($fp);



echo "test1=123&ttm=$TTime&test=tes1212";

?>