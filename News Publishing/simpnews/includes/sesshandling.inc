<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function do_login($email, $pw, $db)
{
	global $cookiedomain, $tableprefix, $userdata, $sesscookietime, $cookiename, $cookiepath, $cookiesecure, $act_lang;

	if(!$email)
	{
		return -1; exit;
	}
	if(!$pw)
	{
		return -2; exit;
	}
	$pw=md5($pw);
	$sql = "select * from ".$tableprefix."_poster where (email = '$email') and (password = '$pw') and (pwconfirmed=1)";
	if(!$result = mysql_query($sql, $db))
	    die("Unable to connect to database".mysql_error());
	if (!$myrow = mysql_fetch_array($result))
		return 0;
	$userdata = get_userdata($email, $db);
	$sessid = new_session($userdata["entrynr"], get_userip(), $sesscookietime, $db);
	set_sessioncookie($sessid, $sesscookietime, $cookiename, $cookiepath, $cookiedomain, $cookiesecure);
	return 1;
}

function get_userdata($email, $db)
{
	global $tableprefix;
	$sql = "SELECT * FROM ".$tableprefix."_poster WHERE email = '$email'";
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
	$delsql = "DELETE FROM ".$tableprefix."_session2 WHERE (starttime < $expiretime)";
	$delresult = mysql_query($delsql, $db);
	if (!$delresult)
		die("Error deleting old sessions");
}

function new_session($userid, $ip, $lifetime, $db)
{
	global $tableprefix;
	mt_srand((double)microtime()*1000000);
	$sessid = mt_rand();
	$currtime = (string) (time());
	cleanup_old_sessions($lifetime,$db);
	$sql = "INSERT INTO ".$tableprefix."_session2 (sessid, usernr, starttime, remoteip) VALUES ($sessid, $userid, $currtime, '$ip')";
	$result = mysql_query($sql, $db);
	if ($result)
		return $sessid;
	else
		die("Unable to create new session");
}

function set_sessioncookie($sessid, $cookietime, $cookiename, $cookiepath, $cookiedomain, $cookiesecure)
{
	$cookieexpire=time()+(2*$cookietime);
	setcookie($cookiename."[session]",$sessid,$cookieexpire,$cookiepath,$cookiedomain,$cookiesecure);
}

function get_userid_from_session($sessid, $cookietime, $ip, $db)
{
	global $tableprefix;
	$mintime = time() - $cookietime;
	$sql = "SELECT usernr FROM ".$tableprefix."_session2 WHERE (sessid = $sessid) AND (starttime > $mintime) AND (remoteip = '$ip')";
	$result = mysql_query($sql, $db);
	if (!$result)
		die("Unable to get userid from session");
	$row = mysql_fetch_array($result);
	if (!$row)
		return 0;
	else
		return $row["usernr"];
}

function update_session($sessid, $db)
{
	global $tableprefix, $sesscookietime, $cookiename, $cookiepath, $cookiedomain, $cookiesecure, $sessid_url;
	$newtime = (string) time();
	$sql = "UPDATE ".$tableprefix."_session2 SET starttime=$newtime WHERE (sessid = $sessid)";
	$result = mysql_query($sql, $db);
	if (!$result)
		die("Unable to update session");
	if(!$sessid_url)
		set_sessioncookie($sessid, $sesscookietime, $cookiename, $cookiepath, $cookiedomain, $cookiesecure);
	return 1;
}

function get_userdata_by_id($userid, $db)
{
	global $tableprefix;
	$sql = "SELECT * FROM ".$tableprefix."_poster WHERE entrynr = $userid";
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
	$sql = "DELETE FROM ".$tableprefix."_session2 WHERE (usernr = $userid)";
	$result = mysql_query($sql, $db);
	if (!$result)
		die("Unable to terminate session");
	return 1;
}

function is_loggedin()
{
	global $new_global_handling, $userdata, $cookiename, $sesscookietime, $REMOTE_ADDR, $db;

	$userid=0;	
	if($new_global_handling)
	{
		if(isset($_COOKIE[$cookiename]))
			$cookiedata = $_COOKIE[$cookiename];
	}
	else
	{
		if(isset($_COOKIE[$cookiename])) {
			$cookiedata = $_COOKIE[$cookiename];
		}
	}
	if(isset($cookiedata))
	{
		if(sn_array_key_exists($cookiedata,"session"))
		{
			$sessid=$cookiedata["session"];
			$userid = get_userid_from_session($sessid, $sesscookietime, $REMOTE_ADDR, $db);
		}
	}
	if($userid)
	{
	   update_session($sessid, $db);
	   $userdata = get_userdata_by_id($userid, $db);
	   return true;
	}
	return false;
}
?>