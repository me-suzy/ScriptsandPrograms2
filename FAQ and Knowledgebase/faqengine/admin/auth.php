<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function do_login($username, $password, $db)
{
	global $tableprefix, $userdata, $sesscookietime, $sesscookiename, $cookiepath, $cookiedomain, $cookiesecure, $act_lang, $url_sessid, $sessid_url, $enablerecoverpw, $db_dateformat_full;

	$sql = "select * from ".$tableprefix."_settings where settingnr=1";
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Could not connect to the database (settings).");
	if ($myrow = faqe_db_fetch_array($result))
	{
		$faqemail=$myrow["faqemail"];
		$msendlimit=$myrow["msendlimit"];
		$watchlogins=$myrow["watchlogins"];
		$enablefailednotify=$myrow["enablefailednotify"];
		$loginlimit=$myrow["loginlimit"];
		$dateformat=$myrow["admdateformat"];
		$extfailedlog=$myrow["extfailedlog"];
	}
	else
	{
		$faqemail="faqenine@foo.bar";
		$msendlimit=30;
		$watchlogins=1;
		$enablefailednotify=1;
		$loginlimit=0;
		$dateformat="Y-m-d H:i:s";
		$extfailedlog=0;
	}
	$pinlogin=0;
	$banreason="";
	if(isbanned(get_user_ip(),$db))
	{
		return -99; exit;
	}
	if(!$username)
	{
		log_failed($username, $password, get_user_ip(), $dateformat, $db, $tableprefix, $enablefailednotify, $faqemail, $extfailedlog);
		return -1; exit;
	}
	if(!$password)
	{
		log_failed($username, $password, get_user_ip(), $dateformat, $db, $tableprefix, $enablefailednotify, $faqemail, $extfailedlog);
		return -2; exit;
	}
	$pw=md5($password);
	$sql = "select * from ".$tableprefix."_admins where (username = '$username') and (password = '$pw')";
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Unable to connect to database ".faqe_db_error());
	if (!$myrow = faqe_db_fetch_array($result))
	{
		if(!$enablerecoverpw)
		{
			log_failed($username, $password, get_user_ip(), $dateformat, $db, $tableprefix, $enablefailednotify, $faqemail, $extfailedlog);
			return 0;
		}
		$sql = "select * from ".$tableprefix."_admins where (username = '$username') and (autopin = '$password') and (autopin!=0)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database ".faqe_db_error());
		if (!$myrow = faqe_db_fetch_array($result))
		{
			log_failed($username, $password, get_user_ip(), $dateformat, $db, $tableprefix, $enablefailednotify, $faqemail, $extfailedlog);
			return 0;
		}
		else
		{
			$pinlogin=1;
		}
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
	$sessid = new_session($userdata["usernr"], get_user_ip(), $sesscookietime, $db, $userdata["lastlogin"]);
	if($sessid_url)
		$url_sessid=$sessid;
	else
		set_sessioncookie($sessid, $sesscookietime, $sesscookiename, $cookiepath, $cookiedomain, $cookiesecure);
	$actdate = date($db_dateformat_full);
	$sql = "UPDATE ".$tableprefix."_admins set lastlogin='$actdate' where usernr=".$userdata["usernr"];
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Could not connect to the database (".$tableprefix."_admins).");
	if($watchlogins==1)
	{
		$actdate = date($db_dateformat_full);
		$sql = "INSERT ".$tableprefix."_iplog (usernr, logtime, ipadr, used_lang) values (".$userdata["usernr"].", '$actdate', '".get_user_ip()."', '$act_lang')";
		if(!$result = faqe_db_query($sql, $db))
			db_die("<tr class=\"errorrow\"><td>Could not connect to the database (".$tableprefix."_iplog).");
	}
	if($pinlogin==0)
	{
		$sql = "update ".$tableprefix."_admins set autopin=0 where usernr=".$userdata["usernr"];
		if(!$result = faqe_db_query($sql, $db))
			db_die("<tr class=\"errorrow\"><td>Could not connect to the database (".$tableprefix."_admins).");
		return 1;
		exit;
	}
	else
	{
		return 4711;
		exit;
	}
}

function count_logged_users($db)
{
	global $tableprefix;
	$sql = "select * from ".$tableprefix."_session";
	if(!$result=faqe_db_query($sql,$db))
		db_die("<tr class=\"errorrow\"><td>Error counting logged users");
	$numusers=faqe_db_num_rows($result);
	return $numusers;
}

function get_userdata($username, $db)
{
	global $tableprefix;
	$sql = "SELECT * FROM ".$tableprefix."_admins WHERE username = '$username'";
	if(!$result = faqe_db_query($sql, $db))
		$userdata = array("error" => "1");
	if(!$myrow = faqe_db_fetch_array($result))
		$userdata = array("error" => "1");
	return($myrow);
}

function cleanup_old_sessions($lifetime,$db)
{
	global $tableprefix;
	$expiretime = (string) (time() - $lifetime);
	$delsql = "DELETE FROM ".$tableprefix."_session WHERE (starttime < $expiretime)";
	$delresult = faqe_db_query($delsql, $db);
	if (!$delresult)
		db_die("<tr class=\"errorrow\"><td>Error deleting old sessions");
}

function new_session($userid, $ip, $lifetime, $db, $lastlogin)
{
	global $tableprefix;
	mt_srand((double)microtime()*1000000);
	$sessid = mt_rand();
	$currtime = time();
	cleanup_old_sessions($lifetime,$db);
	$sql = "INSERT INTO ".$tableprefix."_session (sessid, usernr, starttime, remoteip, lastlogin) VALUES ($sessid, $userid, $currtime, '$ip', '$lastlogin')";
	$result = faqe_db_query($sql, $db);
	if ($result)
		return $sessid;
	else
		db_die("<tr class=\"errorrow\"><td>Unable to create new session");
}

function set_sessioncookie($sessid, $cookietime, $cookiename, $cookiepath, $cookiedomain, $cookiesecure)
{
	$cookieexpire=time()+($cookietime*2);
	setcookie($cookiename,$sessid,$cookieexpire,$cookiepath,$cookiedomain,$cookiesecure);
}

function get_userid_from_session($sessid, $cookietime, $ip, $db)
{
	global $tableprefix;
	$mintime = time() - $cookietime;
	$sql = "SELECT usernr FROM ".$tableprefix."_session WHERE (sessid = '$sessid') AND (starttime > $mintime) AND (remoteip = '$ip')";
	$result = faqe_db_query($sql, $db);
	if (!$result)
		db_die("<tr class=\"errorrow\"><td>Unable to connect to database ");
	$row = faqe_db_fetch_array($result);
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
	$result = faqe_db_query($sql, $db);
	if (!$result)
		db_die("<tr class=\"errorrow\"><td>Unable to connect to database ");
	$row = faqe_db_fetch_array($result);
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
	$result = faqe_db_query($sql, $db);
	if (!$result)
		db_die("<tr class=\"errorrow\"><td>Unable to connect to database ");
	if(!$sessid_url)
		set_sessioncookie($sessid, $sesscookietime, $sesscookiename, $cookiepath, $cookiedomain, $cookiesecure);
	return 1;
}

function get_userdata_by_id($userid, $db)
{
	global $tableprefix;
	$sql = "SELECT * FROM ".$tableprefix."_admins WHERE usernr = '$userid'";
	if(!$result = faqe_db_query($sql, $db)) {
		$userdata = array("error" => "1");
		return ($userdata);
	}
	if(!$myrow = faqe_db_fetch_array($result)) {
		$userdata = array("error" => "1");
		return ($userdata);
	}
	return($myrow);
}

function end_session($userid, $db)
{
	global $tableprefix;
	$sql = "DELETE FROM ".$tableprefix."_session WHERE (usernr = '$userid')";
	$result = faqe_db_query($sql, $db);
	if (!$result)
		db_die("<tr class=\"errorrow\"><td>Unable to connect to database ");
	return 1;
}

function isbanned($ipadr, $db)
{
	global $banprefix, $banreason, $tableprefix, $act_lang;

	$sql="select * from ".$banprefix."_banlist";
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Unable to connect to database (".$tableprefix."_banlist)");
	if (!$myrow = faqe_db_fetch_array($result))
	{
		return false;
		exit;
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
					db_die("<tr class=\"errorrow\"><td>Unable to connect to database (".$tableprefix."_texts)");
				if($tmprow=mysql_fetch_array($tmpresult))
				{
					$banreason=stripslashes($myrow["text"]);
					$banreason = undo_htmlspecialchars($banreason);
				}
			}
			return true;
		}
	}while($myrow = faqe_db_fetch_array($result));
	return false;
}

function log_failed($username, $password, $remoteip, $dateformat, $db, $tableprefix, $enablefailednotify, $faqemail, $extfailedlog)
{
	global $db_dateformat_full, $use_smtpmail, $failednopw;
	$actdate = date($db_dateformat_full);
	$displaydate = date($dateformat);
	if(((!$username) || (!$password)) && ($extfailedlog!=1))
		return;
	if(!$username)
		$username="none";
	if(!$password)
		$password="none";
	if($failednopw)
		$password="***";
	$sql = "INSERT INTO ".$tableprefix."_failed_logins (username, ipadr, logindate, usedpw) ";
	$sql .="values ('$username', '$remoteip', '$actdate', '$password')";
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Unable to connect to database ");
	if($enablefailednotify)
	{
		$sql = "select u.email, u.language from ".$tableprefix."_failed_notify fn, ".$tableprefix."_admins u where u.usernr=fn.usernr";
		if(!$result = faqe_db_query($sql, $db))
			db_die("<tr class=\"errorrow\"><td>Unable to connect to database ");
		if($myrow=faqe_db_fetch_array($result))
		{
			$usercount=0;
			$fromadr = "From:".$faqemail."\r\n";
			do{
				if(strlen($myrow["email"])>1)
				{
					include("./language/faqmail_".$myrow["language"].".php");
					$subject = $l_fm['floginsubj'];
					$mailmsg = $l_fm['floginbody'];
					$mailmsg = str_replace("{date}",$displaydate,$mailmsg);
					$mailmsg = str_replace("{remoteip}",$remoteip,$mailmsg);
					$mailmsg = str_replace("{username}",$username,$mailmsg);
					$mailmsg = str_replace("{password}",$password,$mailmsg);
					$receiver=$myrow["email"];
					if($use_smtpmail)
						mail_smtp($receiver,$subject,$mailmsg,$faqemail);
					else
						mail($receiver,$subject,$mailmsg,$fromadr);
				}
			}while($myrow=faqe_db_fetch_array($result));
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
	$result = faqe_db_query($sql, $db);
	if (!$result)
		db_die("<tr class=\"errorrow\"><td>Error counting sessions");
	$numsessions=faqe_db_num_rows($result);
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