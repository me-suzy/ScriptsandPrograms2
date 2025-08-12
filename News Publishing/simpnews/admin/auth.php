<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function do_login($username, $pw, $db)
{
	global $cookiedomain, $tableprefix, $userdata, $sesscookietime, $sesscookiename, $cookiepath, $cookiesecure, $act_lang, $url_sessid, $sessid_url, $enablerecoverpw;
	global $emaillog;

	$pinlogin=0;
	$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database. (1) ".mysql_error());
	if ($myrow = mysql_fetch_array($result))
	{
		$watchlogins=$myrow["watchlogins"];
		$enablefailednotify=$myrow["enablefailednotify"];
		$simpnewsmail=$myrow["simpnewsmail"];
		if(!$simpnewsmail)
			$simpnewsmail="simpnews@foo.bar";
		$loginlimit=$myrow["loginlimit"];
		$dateformat="Y-m-d H:i:s";
		$emaillog=$myrow["emaillog"];
	}
	else
	{
		$watchlogins=1;
		$enablefailednotify=0;
		$simpnewsmail="simpnews@foo.bar";
		$dateformat="Y-m-d H:i:s";
		$loginlimit=0;
		$emaillog=0;
	}
	if(isbanned(get_userip(),$db))
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
	$sql = "select * from ".$tableprefix."_users where (username = '$username') and (password = '$pw')";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database (2) ".mysql_error());
	if (!$myrow = mysql_fetch_array($result))
	{
		if(!$enablerecoverpw)
		{
			log_failed($username, $entered_pw, get_userip(), $dateformat, $db, $tableprefix, $enablefailednotify, $simpnewsmail);
			return 0;
		}
		$sql = "select * from ".$tableprefix."_users where (username = '$username') and (autopin = '$entered_pw') and (autopin!=0)";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database (3) ".mysql_error());
		if (!$myrow = mysql_fetch_array($result))
		{
			log_failed($username, $entered_pw, get_userip(), $dateformat, $db, $tableprefix, $enablefailednotify, $simpnewsmail);
			return 0;
		}
		else
			$pinlogin=1;
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
	$sql = "UPDATE ".$tableprefix."_users set lastlogin='$actdate' where usernr=".$userdata["usernr"];
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database (".$tableprefix."_users).".mysql_error());
	if($watchlogins==1)
	{
		$actdate = date("Y-m-d H:i:s");
		$sql = "INSERT ".$tableprefix."_iplog (usernr, logtime, ipadr, used_lang) values (".$userdata["usernr"].", '$actdate', '".get_userip()."', '$act_lang')";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database (".$tableprefix."_iplog).".mysql_error());
	}
	if($pinlogin==0)
	{
		$sql = "update ".$tableprefix."_users set autopin=0 where usernr=".$userdata["usernr"];
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database (".$tableprefix."_users).".mysql_error());
		return 1; exit;
	}
	else
	{
		return 4711; exit;
	}
}

function count_logged_users($db)
{
	global $tableprefix;
	$sql = "select * from ".$tableprefix."_session";
	if(!$result=mysql_query($sql,$db))
		die("<tr class=\"errorrow\"><td>Error counting logged users");
	return mysql_numrows($result);
}

function get_userdata($username, $db)
{
	global $tableprefix;
	$sql = "SELECT * FROM ".$tableprefix."_users WHERE username = '$username'";
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
	if (!$delresult)
		die("<tr class=\"errorrow\"><td>Error deleting old sessions");
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
		die("<tr class=\"errorrow\"><td>Unable to create new session");
	}
}

function set_sessioncookie($sessid, $cookietime, $cookiename, $cookiepath, $cookiedomain, $cookiesecure)
{
	$cookieexpire=time()+(2*$cookietime);
	setcookie($cookiename,$sessid,$cookieexpire,$cookiepath,$cookiedomain,$cookiesecure);
}

function get_userid_from_session($sessid, $cookietime, $ip, $db)
{
	global $tableprefix;
	$mintime = time() - $cookietime;
	$sql = "SELECT usernr FROM ".$tableprefix."_session WHERE (sessid = '$sessid') AND (starttime > $mintime) AND (remoteip = '$ip')";
	$result = mysql_query($sql, $db);
	if (!$result)
		die("<tr class=\"errorrow\"><td>Unable to get sessiondata from database");
	$row = mysql_fetch_array($result);
	if (!$row)
		return 0;
	else
		return $row["usernr"];
}

function get_lastlogin_from_session($sessid, $cookietime, $ip, $db)
{
	global $tableprefix;
	$mintime = time() - $cookietime;
	$sql = "SELECT lastlogin FROM ".$tableprefix."_session WHERE (sessid = '$sessid') AND (starttime > $mintime) AND (remoteip = '$ip')";
	$result = mysql_query($sql, $db);
	if (!$result) {
		die("<tr class=\"errorrow\"><td>Unable to connect to database ".mysql_error());
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
	$sql = "UPDATE ".$tableprefix."_session SET starttime=$newtime WHERE (sessid = $sessid)";
	$result = mysql_query($sql, $db);
	if (!$result) {
		die("<tr class=\"errorrow\"><td>Unable to connect to database ".mysql_error());
	}
	if(!$sessid_url)
		set_sessioncookie($sessid, $sesscookietime, $sesscookiename, $cookiepath, $cookiedomain, $cookiesecure);
	return 1;
}

function get_userdata_by_id($userid, $db)
{
	global $tableprefix;
	$sql = "SELECT * FROM ".$tableprefix."_users WHERE usernr = '$userid'";
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
		die("<tr class=\"errorrow\"><td>Unable to connect to database ".mysql_error());
	}
	return 1;
}

function isbanned($ipadr, $db)
{
	global $banprefix, $banreason, $tableprefix, $act_lang;

	$sql="select * from ".$banprefix."_banlist";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database (".$banprefix."_banlist)".mysql_error());
	if (!$myrow = mysql_fetch_array($result))
	{
		return false; exit;
	}
	do{
		if(ipinrange($ipadr,$myrow["ipadr"],$myrow["subnetmask"]))
		{
			$banreason=stripslashes($myrow["reason"]);
			$banreason = undo_htmlspecialchars($banreason);
			if(!$banreason)
			{
				$tmpsql="select * from ".$tableprefix."_texts where textid='defbr' and lang='$act_lang'";
				if(!$tmpresult = mysql_query($tmpsql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database (".$tableprefix."_texts)".mysql_error());
				if($tmprow=mysql_fetch_array($tmpresult))
				{
					$banreason=stripslashes($myrow["text"]);
					$banreason = undo_htmlspecialchars($banreason);
				}
			}
			return true;
		}
	}while($myrow = mysql_fetch_array($result));
	return false;
}

function log_failed($username, $password, $remoteip, $dateformat, $db, $tableprefix, $enablefailednotify, $simpnewsmail)
{
	global $use_smtpmail, $contentcharset, $crlf;
	global $smtpserver, $smtpport, $smtpauth, $smtpuser, $smtppasswd, $path_simpnews;

	$actdate = date("Y-m-d H:i:s");
	$displaydate = date($dateformat);
	$sql = "INSERT INTO ".$tableprefix."_failed_logins (username, ipadr, logindate, usedpw) ";
	$sql .="values ('$username', '$remoteip', '$actdate', '$password')";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database ".mysql_error());
	if($enablefailednotify)
	{
		include_once($path_simpnews.'/includes/htmlMimeMail.inc');
		include_once($path_simpnews.'/includes/smtp.inc');
		include_once($path_simpnews.'/includes/RFC822.inc');
		$sql = "select u.email from ".$tableprefix."_failed_notify fn, ".$tableprefix."_users u where u.usernr=fn.usernr";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database ".mysql_error());
		if($myrow=mysql_fetch_array($result))
		{
			$subject = "Info from SimpNews";
			$mailmsg = "Fehlgeschlagener Loginversuch.".$crlf;
			$mailmsg .="Failed login.\r\n";
			$mailmsg .="$displaydate, ".$remoteip.$crlf;
			$mailmsg .="Username: $username".$crlf;
			$mailmsg .="PW: $password".$crlf.$crlf;
			$mail = new htmlMimeMail();
			$mail->setCrlf($crlf);
			$mail->setTextWrap(80);
			$mail->setTextCharset($contentcharset);
			$mail->setText($mailmsg);
			$mail->setSubject($subject);
			$mail->setFrom($simpnewsmail);
			do{
				if(strlen($myrow["email"])>1)
				{
					$receiver=array($myrow["email"]);
					if($use_smtpmail)
					{
						$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
						$sendresult=$mail->send($receiver, "smtp");
					}
					else
						$sendresult=$mail->send($receiver, "mail");
					do_emaillog($sendresult,$myrow["email"],"log_failed");
				}
			}while($myrow=mysql_fetch_array($result));
		}
	}
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