<?php

include_once('config.php');


$CONF = array();
$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
mysql_select_db($DBDatabase,$dbh);
$res = mysql_query("SELECT * from bvars",$dbh);
if($res)
{
	while($row = mysql_fetch_array($res))
	{
		if(substr($row['bname'],0,strlen("conf_")) == "conf_")
		{
			$CONF[$row['bname']] = $row['bvalue'];
		}
	}
}
mysql_close($dbh);

$CDEF['GCompanyName'] 	= 'Our Company Name';
$CDEF['GDomainName'] 	= str_replace("www.","",$_SERVER["HTTP_HOST"]);
$CDEF['GEmailAddress'] 	= 'support@'.$CDEF['GDomainName'];
$CDEF['GEmailSubject'] 	= "Message from online support";

$CDEF['Msg1']  = "Please Wait...";
$CDEF['Msg2']  = "Locating an Available and Online\nCustomer Service Representative.";
$CDEF['Msg3']  = "Sorry, all online customer service representatives are busy right now, Please try again later or you can leave us a message using the form below:";
$CDEF['Msg4']  = "Welcome to ".str_replace("www.","",$_SERVER["HTTP_HOST"])." Online Support.";
$CDEF['Msg5']  = "Sending Message";
$CDEF['Msg6']  = "Thank you";
$CDEF['Msg7']  = "One of our representatives will\ncontact you as soon as possible.";
$CDEF['Msg8']  = "For some reasons we are unable to send your message.\nPlease contact us directly using our email address\nand please accept our apologize for this incontinence.";
$CDEF['Msg9']  = "";
$CDEF['Msg10'] = "";

$CDEF['MaxWaitTime'] = 30;
$CDEF['UseLanguageTranslator'] = 1;
$CDEF['License'] = "";

$CDEF['staff0'] = 'admin:YWRtMTIz:1';

if(isset($GCompanyName))	$CDEF['GCompanyName'] 	= $GCompanyName;
if(isset($GDomainName))		$CDEF['GDomainName'] 	= $GDomainName;
if(isset($GEmailAddress))	$CDEF['GEmailAddress'] 	= $GEmailAddress;
if(isset($GEmailSubject))	$CDEF['GEmailSubject'] 	= $GEmailSubject;
if(isset($Msg1))		$CDEF['Msg1'] 	= $Msg1;
if(isset($Msg2))		$CDEF['Msg2'] 	= $Msg2;
if(isset($Msg3))		$CDEF['Msg3'] 	= $Msg3;
if(isset($Msg4))		$CDEF['Msg4'] 	= $Msg4;
if(isset($Msg5))		$CDEF['Msg5'] 	= $Msg5;
if(isset($Msg6))		$CDEF['Msg6'] 	= $Msg6;
if(isset($Msg7))		$CDEF['Msg7'] 	= $Msg7;
if(isset($Msg8))		$CDEF['Msg8'] 	= $Msg8;
if(isset($Msg9))		$CDEF['Msg9'] 	= $Msg9;
if(isset($Msg10))		$CDEF['Msg10'] 	= $Msg10;
if(isset($MaxWaitTime))		$CDEF['MaxWaitTime'] 	= $MaxWaitTime;
if(isset($UseLanguageTranslator))	$CDEF['UseLanguageTranslator'] 	= $UseLanguageTranslator;
if(isset($License))		$CDEF['License']= $License;


$ConfReload = false;
foreach($CDEF as $k=>$v)
{
	if(!isset($CONF['conf_'.$k]))
	{
		$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
		mysql_select_db($DBDatabase,$dbh);
		mysql_query("INSERT INTO bvars VALUES(\"conf_$k\",\"$v\")",$dbh);
		mysql_close($dbh);
		$ConfReload = true;
	}
}

if($ConfReload)
{
	$CONF = array();
	$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
	mysql_select_db($DBDatabase,$dbh);
	$res = mysql_query("SELECT * from bvars",$dbh);
	if($res)
	{
		while($row = mysql_fetch_array($res))
		{
			if(substr($row['bname'],0,strlen("conf_")) == "conf_")
			{
				$CONF[$row['bname']] = $row['bvalue'];
			}
		}
	}
}

$CONF['conf_phpOnlineVer'] = $phpOnlineVer;


?>