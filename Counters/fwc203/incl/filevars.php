<?php

/******************************************************************************
File Name    : incl/filevars.php
Description  : used only in login.php - retrieves the sid & usr file data
Author       : mike@mfrank.net (Mike Frank)
Date Created : March 25, 2004
Last Change  : April 14, 2004
Licence      : Freeware (GPL)
******************************************************************************/

// open session id file
$cpass = md5($adminpass);
$cpass = substr($cpass, strlen($cpass)-6, 4);
$sidpath = "sidtemp/".$sessionid.".".$cpass.".sid";
if (!file_exists($sidpath)) {
	header("location: login.php?err=5");
	exit;
}

$fp = fopen($sidpath,"r");
$fdata = fgets($fp,1024);
$fdata = split("!", $fdata);
$ip = $fdata[0];
$username = $fdata[1];	// username in sid
$password = $fdata[2];	// password in sid
$loggedas = '<table align="right"><tr><td class="noboldtitle"><b>logged on as <i>'.$username.'</i></b></td></tr></table>';
fclose($fp);

if ($ip!=$_SERVER["REMOTE_ADDR"]) {
	header("location: login.php?err=6");
	exit;
}

// open user file	
$userfile = "userdb/".$username.".db";
if (!file_exists($userfile)) {
	header("location: login.php?err=2");
	exit;
}

$fp = fopen($userfile,"r");
$fdata = fgets($fp,1024);
$fdata = split("{}", $fdata);
$fusername = ereg_replace("u:", "", $fdata[0]);	// username in userdb
$fpassword = ereg_replace("p:", "", $fdata[1]);	// password in userdb
$fname = ereg_replace("n:", "", $fdata[2]);	// name in userdb
$femail = ereg_replace("e:", "", $fdata[3]);	// email in userdb
$fsiteid = ereg_replace("i:", "", $fdata[4]);	// siteid(s) in userdb
fclose($fp);
?>