<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
include "../config.php";
include "../functions.php";
include "./auth.php";
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include('./language/lang_'.$act_lang.'.php');

$redirect="index.php?$langvar=$act_lang";		// Page to redirect after logout
if($enable_htaccess)
{
	echo "<script>";
	echo "alert(\"$l_notavail_htaccess2\");";
	echo "</script>";
	echo "<META HTTP-EQUIV=\"refresh\" content=\"0.01; URL=$redirect\">";
}
else
{
	if($sessid_url)
	{
		if(isset($$sesscookiename))
		{
			$url_sessid=$$sesscookiename;
			$userid = get_userid_from_session($url_sessid, $sesscookietime, get_user_ip(), $db);
			if ($userid) {
				$userdata = get_userdata_by_id($userid, $db);
				end_session($userdata["usernr"], $db);
			}
		}
	}
	else
	{
		$userid="";
		if($new_global_handling)
		{
			if(isset($_COOKIE[$sesscookiename]))
			{
				$sessid = $_COOKIE[$sesscookiename];
				$userid = get_userid_from_session($sessid, $sesscookietime, get_user_ip(), $db);
			}
		}
		else
		{
			if(isset($_COOKIE[$sesscookiename])) {
				$sessid = $_COOKIE[$sesscookiename];
				$userid = get_userid_from_session($sessid, $sesscookietime, get_user_ip(), $db);
			}
		}
		if ($userid)
		{
			$userdata = get_userdata_by_id($userid, $db);
			end_session($userdata["usernr"], $db);
		}
	}
	echo "<META HTTP-EQUIV=\"refresh\" content=\"0.01; URL=$redirect\">";
}

?>