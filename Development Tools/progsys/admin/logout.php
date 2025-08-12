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
include "../config.php";
include "../functions.php";
include "auth.php";
if(!isset($lang) || !$lang)
	$lang=$admin_lang;

$redirect="index.php?lang=$lang";		// Page to redirect after logout
if($sessid_url)
{
	if(isset($$sesscookiename))
	{
		$url_sessid=$$sesscookiename;
		$userid = get_userid_from_session($url_sessid, $sesscookietime, get_userip(), $db);
		if ($userid) {
			$userdata = get_userdata_by_id($userid, $db);
			end_session($userdata["usernr"], $db);
		}
	}
}
else
{
	if(isset($_COOKIE[$sesscookiename]))
	{
		$sessid = $_COOKIE[$sesscookiename];
		$userid = get_userid_from_session($sessid, $sesscookietime, get_userip(), $db);
		if ($userid)
		{
			$userdata = get_userdata_by_id($userid, $db);
			end_session($userdata["usernr"], $db);
		}
	}
}
echo "<META HTTP-EQUIV=\"refresh\" content=\"0.01; URL=$redirect\">";
?>