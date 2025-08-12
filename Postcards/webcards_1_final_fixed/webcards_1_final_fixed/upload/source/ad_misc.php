<?php

//error_reporting(E_ALL);

/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------------------+
// | WebCards Version 1.0 - A powerful, easy to configure e-card system               |
// | Copyright (C) 2003  Chris Charlton (corbyboy@hotmail.com)                        |
// |                                                                                  |
// |     This program is free software; you can redistribute it and/or modify         |
// |     it under the terms of the GNU General Public License as published by         |
// |     the Free Software Foundation; either version 2 of the License, or            |
// |     (at your option) any later version.                                          |
// |                                                                                  |
// |     This program is distributed in the hope that it will be useful,              |
// |     but WITHOUT ANY WARRANTY; without even the implied warranty of               |
// |     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                |
// |     GNU General Public License for more details.                                 |
// |                                                                                  |
// |     You should have received a copy of the GNU General Public License            |
// |     along with this program; if not, write to the Free Software                  |
// |     Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    |
// |                                                                                  |
// | Authors: Chris Charlton <corbyboy@hotmail.com>                                   |
// | Official Homepage: http://webcards.sourceforge.net                               |
// | Project Homepage: http://www.sourceforge.net/projects/webcards                   |
// +----------------------------------------------------------------------------------+
//
// $Id: ad_misc.php,v 1.00 22004/06/07 23:00:44 chrisc Exp $

/*-----------------------------------------------
  ENSURE THE SCRIPT IS NOT BEING ACCESSED DIRECTLY
 ------------------------------------------------*/
if(!defined("LOADED"))
{
	die("Cannot access the script directly");
}

//Check the admin's username and password
if(isset($HTTP_GET_VARS['act']) && isset($HTTP_GET_VARS['what']) && $HTTP_GET_VARS['act'] == "misc" && $HTTP_GET_VARS['what'] == "login")
{
	$to_check = md5($HTTP_POST_VARS['password']);
	if (!$DB->query("SELECT id, ip, login_time, lang, session FROM " . $conf['dbprefix'] . "admin WHERE user=\"" . $HTTP_POST_VARS['user'] . "\" AND password=\"" . $to_check . "\""))
	{
		output(error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']));
	}
	if($DB->num_rows() <= 0)
	{
		output(show_login($lang['bad_user_pass']));
	}
	else
	{

		$row = $DB->fetch_array();

		$admin_id = $row['id'];
		$login_time = time();
		$ad_lang = $row['lang'];
		$ip = getip();
		$sess = md5(uniqid(microtime()));
		if (!$DB->query("UPDATE " . $conf['dbprefix'] . "admin SET login_time=\"" . $login_time . "\", ip=\"" . $ip . "\", session=\"" . $sess . "\" WHERE id=\"" . $admin_id . "\""))
		{
			output(error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']));
		}
	}

	//Set the temporary language cookie
	if($HTTP_POST_VARS['temp_ad_lang'] == "def")
	{
		$temp_ad_lang = $ad_lang;
	}
	else
	{
		$temp_ad_lang = $HTTP_POST_VARS['temp_ad_lang'];
	}
	// Set a cookie to define the administrator language for this session
	setcookie("temp_ad_lang", $temp_ad_lang, time() + 60*60*3);

	// Set another cookie for the admin id (for security)
	setcookie("admin_id", $admin_id, time() + 60*60*3);

	if($buffer_acp == 1)
	{
		$redir = $conf['admin_script'] . "?s=" . $sess;
		header("Location: ".$redir);
	}
	else
	{
		output("<span class=\"sub_header\">" . $lang['login_success'] . "</span><br /><br /><a href=\"" . $conf['admin_script'] . "?s=" . $sess . "\">" . $lang['click_proceed_acp'] . "</a>");
	}
}

if(isset($HTTP_GET_VARS['act']) && $HTTP_GET_VARS['act'] == "misc" && isset($HTTP_GET_VARS['what']) && $HTTP_GET_VARS['what'] == "logout")
{

	if (!$DB->query("UPDATE " . $conf['dbprefix'] . "admin SET login_time=NULL, ip=NULL, session=NULL WHERE session=\"" . $HTTP_GET_VARS['s'] . "\""))
	{
		output(error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']));
	}

	//Remove the admin language and id cookie
	setcookie("temp_ad_lang", "-1");
	setcookie("admin_id", "-1");

	output(show_login($lang['logged_out']));
}

?>