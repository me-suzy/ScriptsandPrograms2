<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
function do_login($username, $pw, $db, $banreason)
{
	global $tableprefix, $userdata, $sesscookietime, $sesscookiename, $cookiepath, $cookiedomain, $cookiesecure, $lang, $url_sessid, $sessid_url;
	global $crlf, $contentcharset, $use_smtpmail;

	$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database");
	if ($myrow = mysql_fetch_array($result))
	{
		$watchlogins=$myrow["watchlogins"];
		$enablefailednotify=$myrow["enablefailednotify"];
		$progsysmail=$myrow["progsysmail"];
		if(!$progsysmail)
			$progsysmail="progsys@foo.bar";
		$dateformat=$myrow["dateformat"];
		$dateformat.=" H:i:s";
		$loginlimit=$myrow["loginlimit"];
	}
	else
	{
		$watchlogins=1;
		$enablefailednotify=0;
		$progsysmail="progsys@foo.bar";
		$dateformat="Y-m-d H:i:s";
		$loginlimit=0;
	}
	$banreason="";
	if(isbanned(get_userip(),$db, &$banreason))
	{
		return -99; exit;
	}
	if(!$username)
	{
		return -1; exit;
	}
	if(!$pw)
	{
		return -2; exit;
	}
	$entered_pw=$pw;
	$pw=md5($pw);
	$sql = "select * from ".$tableprefix."_admins where (username = '$username') and (password = '$pw')";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database");
	if (!$myrow = mysql_fetch_array($result))
	{
		$actdate = date("Y-m-d H:i:s");
		$displaydate = date($dateformat);
		$sql = "INSERT INTO ".$tableprefix."_failed_logins (username, ipadr, logindate, usedpw) ";
		$sql .="values ('$username', '".get_userip()."', '$actdate', '$entered_pw')";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database");
		if($enablefailednotify)
		{
			include_once('../includes/htmlMimeMail.inc');
			if($use_smtpmail)
			{
				include_once('../includes/smtp.inc');
				include_once('../includes/RFC822.inc');
			}
			$sql = "select u.email from ".$tableprefix."_failed_notify fn, ".$tableprefix."_admins u where u.usernr=fn.usernr";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database");
			if($myrow=mysql_fetch_array($result))
			{
				$usercount=0;
				$subject = "Info from ProgSys";
				$mailmsg = "Fehlgeschlagener Loginversuch.".$crlf;
				$mailmsg .="Failed login.".$crlf;
				$mailmsg .="$displaydate, ".get_userip().$crlf;
				$mailmsg .="Username: $username".$crlf;
				$mailmsg .="PW: $entered_pw".$crlf.$crlf;
				do{
					$receiver[$usercount]=$myrow["email"];
					$usercount++;
				}while($myrow=mysql_fetch_array($result));
				$mail = new htmlMimeMail();
				$mail->setCrlf($crlf);
				$mail->setTextCharset($contentcharset);
				$mail->setText($mailmsg);
				$mail->setSubject($subject);
				$mail->setFrom($progsysmail);
				for($i=0;$i<count($receiver);$i++)
				{
					$currentreceiver=array($receiver[$i]);
					if($use_smtpmail)
					{
						$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
							$mail->send($currentreceiver, "smtp");
					}
					else
							$mail->send($currentreceiver, "mail");
				}
			}
		}
		return 0; exit;
	}
	$userdata = get_userdata($username, $db);
	if(($userdata["rights"]<4) && ($loginlimit>0))
	{
		cleanup_old_sessions($sesscookietime,$db);
		if($loginlimit <= count_logged_users($db))
		{
			return 22; exit;
		}
	}
	$sessid = new_session($userdata["usernr"], get_userip(), $sesscookietime, $db, $userdata["lastlogin"]);
	if($sessid_url)
		$url_sessid=$sessid;
	else
		set_sessioncookie($sessid, $sesscookietime, $sesscookiename, $cookiepath, $cookiedomain, $cookiesecure);
	$actdate = date("Y-m-d H:i:s");
	$sql = "UPDATE ".$tableprefix."_admins set lastlogin='$actdate' where usernr=".$userdata["usernr"];
	if(!$result = mysql_query($sql, $db))
		die("Could not connect to the database (".$tableprefix."_admins).");
	if($watchlogins==1)
	{
		$actdate = date("Y-m-d H:i:s");
		$sql = "INSERT ".$tableprefix."_iplog (usernr, logtime, ipadr, used_lang) values (".$userdata["usernr"].", '$actdate', '".get_userip()."', '$lang')";
		if(!$result = mysql_query($sql, $db))
			die("Could not connect to the database (".$tableprefix."_iplog).");
	}
	return 1; exit;
}

function count_logged_users($db)
{
	global $tableprefix;
	$sql = "select * from ".$tableprefix."_session";
	if(!$result=mysql_query($sql,$db))
		die("Error counting logged users");
	return mysql_numrows($result);
}

function get_userdata($username, $db)
{
	global $tableprefix;
	$sql = "SELECT * FROM ".$tableprefix."_admins WHERE username = '$username'";
	if(!$result = mysql_query($sql, $db))
		$userdata = array("error" => "1");
	if(!$myrow = mysql_fetch_array($result))
		$userdata = array("error" => "1");
	return($myrow);
}

function cleanup_old_sessions($lifetime,$db)
{
	global $tableprefix;
	$expiretime = (string) (time() - $lifetime);
	$delsql = "DELETE FROM ".$tableprefix."_session WHERE (starttime < $expiretime)";
	$delresult = mysql_query($delsql, $db);
	if (!$delresult) {
		die("Error deleting old sessions");
	}
}

function new_session($userid, $ip, $lifetime, $db, $lastlogin)
{
	global $tableprefix;
	mt_srand((double)microtime()*1000000);
	$sessid = mt_rand();
	$currtime = (string) (time());
	cleanup_old_sessions($lifetime,$db);
	$sql = "INSERT INTO ".$tableprefix."_session (sessid, usernr, starttime, remoteip, lastlogin) VALUES ($sessid, $userid, $currtime, '$ip', '$lastlogin')";
	$result = mysql_query($sql, $db);
	if ($result) {
		return $sessid;
	} else {
		die("Unable to create new session");
	}
}

function set_sessioncookie($sessid, $cookietime, $cookiename, $cookiepath, $cookiedomain, $cookiesecure) {
	$cookieexpire=time()+($cookietime*2);
	setcookie($cookiename,$sessid,$cookieexpire,$cookiepath,$cookiedomain,$cookiesecure);
}

function get_userid_from_session($sessid, $cookietime, $ip, $db)
{
	global $tableprefix;
	$mintime = time() - $cookietime;
	$sql = "SELECT usernr FROM ".$tableprefix."_session WHERE (sessid = '$sessid') AND (starttime > $mintime) AND (remoteip = '$ip')";
	$result = mysql_query($sql, $db);
	if (!$result) {
		die("Unable to connect to database");
	}
	$row = mysql_fetch_array($result);
	if (!$row) {
		return 0;
	} else {
		return $row["usernr"];
	}
}

function get_lastlogin_from_session($sessid, $cookietime, $ip, $db)
{
	global $tableprefix;
	$mintime = time() - $cookietime;
	$sql = "SELECT lastlogin FROM ".$tableprefix."_session WHERE (sessid = '$sessid') AND (starttime > $mintime) AND (remoteip = '$ip')";
	$result = mysql_query($sql, $db);
	if (!$result) {
		die("Unable to connect to database");
	}
	$row = mysql_fetch_array($result);
	if (!$row) {
		return 0;
	} else {
		return $row["lastlogin"];
	}
}

function update_session($sessid, $db)
{
	global $tableprefix, $sesscookietime, $sesscookiename, $cookiepath, $cookiedomain, $cookiesecure, $sessid_url;
	$newtime = (string) time();
	$sql = "UPDATE ".$tableprefix."_session SET starttime=$newtime WHERE (sessid = '$sessid')";
	$result = mysql_query($sql, $db);
	if (!$result) {
		die("Unable to connect to database");
	}
	if(!$sessid_url)
		set_sessioncookie($sessid, $sesscookietime, $sesscookiename, $cookiepath, $cookiedomain, $cookiesecure);
	return 1;
}

function get_userdata_by_id($userid, $db)
{
	global $tableprefix;
	$sql = "SELECT * FROM ".$tableprefix."_admins WHERE usernr = '$userid'";
	if(!$result = mysql_query($sql, $db)) {
		$userdata = array("error" => "1");
		return ($userdata);
	}
	if(!$myrow = mysql_fetch_array($result)) {
		$userdata = array("error" => "1");
		return ($userdata);
	}
	return($myrow);
}

function end_session($userid, $db)
{
	global $tableprefix;
	$sql = "DELETE FROM ".$tableprefix."_session WHERE (usernr = '$userid')";
	$result = mysql_query($sql, $db);
	if (!$result) {
		die("Unable to connect to database");
	}
	return 1;
}

function ipinrange($network, $mask, $ip) {
    $ip_long=ip2long($ip);
    $network_long=ip2long($network);
    $mask_long=ip2long($mask);

    if (($ip_long & $mask_long) == $network_long) {
        return true;
    } else {
        return false;
    }
}

function isbanned($ipadr, $db, $banreason)
{
	global $banprefix;

	$sql="select * from ".$banprefix."_banlist";
	if(!$result = mysql_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database ($banprefix_banlist)");
	if (!$myrow = mysql_fetch_array($result))
	{
		return false; exit;
	}
	do{
		if(ipinrange($ipadr,$myrow["ipadr"],$myrow["subnetmask"]))
		{
			$banreason=stripslashes($myrow["reason"]);
			$banreason = undo_htmlspecialchars($banreason);
			return true;
		}
	}while($myrow = mysql_fetch_array($result));
	return false;
}

function count_sessions()
{
	global $tableprefix, $sesscookietime, $db, $loginlimit;

	cleanup_old_sessions($sesscookietime,$db);
	$sql = "SELECT * FROM ".$tableprefix."_session";
	$result = mysql_query($sql, $db);
	if (!$result) {
		die("<tr class=\"errorrow\"><td>Error counting sessions");
	}
	$numsessions=mysql_num_rows($result);
	if($numsessions>1)
	{
		if($loginlimit>0)
		{
			if($numsessions<$loginlimit)
				return ("<span class=\"sesswarn\">$numsessions");
			else
				return ("<span class=\"sesslimit\">$numsessions");
		}
		else
			return ("<span class=\"sesswarn\">$numsessions");
	}
	else
		return ("<span class=\"sessok\">$numsessions");
}
?>