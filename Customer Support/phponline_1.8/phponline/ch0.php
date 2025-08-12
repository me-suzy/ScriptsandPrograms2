<?php
include_once('noca.php');
include_once('rcq.php');

$CCode = $HTTP_COOKIE_VARS['ocode'];
$IP = $HTTP_SERVER_VARS["REMOTE_ADDR"];
$NickName = $HTTP_POST_VARS["nickname"];
$LanguageSel = $HTTP_POST_VARS['language_sel'];
$TTime = gmdate("U");

if($NickName=='')
	$NickName = 'Guest';


if($CCode == "")
{
	$CCode = time() . rand(111111,999999);
	setcookie("ocode", $CCode, time()+31536000);
}

$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
mysql_select_db($DBDatabase,$dbh);

$IsFind = 0;
$res = mysql_query("SELECT * FROM cs WHERE ccode=$CCode",$dbh);
while ($row = mysql_fetch_array($res)) 
{
	$IsFind = 1;
}
mysql_free_result($res);

$CSCode1 = 0;
foreach($TransLangs as $TI=>$LG)
{
	if($LG[0]==$LanguageSel)
	{
		$CSCode1 = $TI;
		break;
	}
}


if($IsFind ==0)
{
	mysql_query("INSERT INTO cs VALUES('$CCode',0,0,'$IP',$CSCode1,0,$TTime,'$NickName','','')",$dbh);
}
else
{
	mysql_query("UPDATE cs SET online=0,assign=0,ip='$IP',lastact=$TTime,code1=$CSCode1,name='$NickName' WHERE ccode=$CCode",$dbh);
}

mysql_close($dbh);
SetPStatus("1");

$GCN = urlencode(ucwords($CONF['conf_GCompanyName']));

echo "test1=123&ccode=$CCode&cass=0&compname=$GCN&fi213987123=12396192";


?>