<?php

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
// $Id: ad_users.php,v 1.00 2003/03/01 15:07:39 chrisc Exp $

switch($HTTP_GET_VARS['what'])
{
		case 'add_user':
		$to_do = add_user_form();
		break;

		case 'dousers':
		$to_do = do_users();
		break;

		case 'delete_user':
		$to_do = deleteusers();
		break;

		case 'dodeleteusers':
		$to_do = dodeleteusers();
		break;
}

function add_user_form()
{
global $conf, $lang;

	$basic = parse_basic_admin_template("./templates/admin/admin_add_user.html");

	$dp = opendir($conf['dir'] . "./lang");
	if(!$dp)
	{
		return error($lang['no_open_lang_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms']);
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!=".." && is_dir($conf['dir'] . "lang/" . $file))
		{
			$lang_select .= "<option value=\"" . $file . "\">" . $file . "</option>";
		}
	}

	$output = preg_replace("/{{lang_select}}/i", $lang_select, $basic);
	return $output;
}

function do_users()
{
global $conf, $HTTP_POST_VARS, $HTTP_GET_VARS, $DB, $lang;

	if ($HTTP_POST_VARS['username'] == "")
	{
		return error($lang['no_enter_username'], $lang['back_enter_username']);
	}
	if ($HTTP_POST_VARS['password1'] == "")
	{
		return error($lang['no_pw1'], $lang['back_enter_pw1']);
	}
	if ($HTTP_POST_VARS['password2'] == "")
	{
		return error($lang['no_pw2'], $lang['back_enter_pw2']);
	}
	if ($HTTP_POST_VARS['password1'] != $HTTP_POST_VARS['password2'])
	{
		return error($lang['pw_no_match'], $lang['back_enter_matching_pw']);
	}
	$pass_to_add = md5($HTTP_POST_VARS['password1']);
	if (!$DB->query("INSERT INTO " . $conf['dbprefix'] . "admin (user, password, lang) VALUES (\"" . $HTTP_POST_VARS[username] . "\", \"" . $pass_to_add . "\", \"" . $HTTP_POST_VARS['ad_lang'] . "\")"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	return basic_output($lang['new_user_ok'], $lang['add_admin']);
}

function deleteusers()
{
global $conf, $HTTP_GET_VARS, $DB, $lang;

	$data = "<select name=\"id\">\n";
	$data .= "<option value=\"\" selected>" . $lang['choose_admin'] . "\n";
	if (!$DB->query("SELECT id, user FROM " . $conf['dbprefix'] . "admin"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if ($DB->num_rows() <= 0)
	{
		return error($lang['no_admin_db'], $lang['add_some_admins']);
	}
	while($row = $DB->fetch_array())
	{
	$data .= "<option value=\"" . $row['id'] . "\">" . $row['user'] . "\n";
	}
	$data .= "</select>";
	$basic = parse_basic_admin_template("./templates/admin/admin_delete_user.html");
	$output = preg_replace("/{{delete_data}}/i", $data, $basic);
	return $output;	
}

function dodeleteusers()
{
global $conf, $HTTP_POST_VARS, $HTTP_GET_VARS, $DB, $lang;

	if ($HTTP_POST_VARS['id'] == "")
	{
		return error($lang['no_admin_spec'], $lang['back_choose_admin']);
	}
	if (!$DB->query("SELECT base FROM `" . $conf['dbprefix'] . "admin` WHERE id=\"" . $HTTP_POST_VARS['id'] . "\""))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	$row = $DB->fetch_array();
	if ($row['base'] == "y")
	{
		return error($lang['del_base_admin'], $lang['choose_other_admin']);
	}
	if (!$DB->query("DELETE FROM `" . $conf['dbprefix'] . "admin` WHERE id=\"" . $HTTP_POST_VARS['id'] . "\" LIMIT 1"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	else
	{
		return basic_output($lang['admin_deleted'], $lang['del_admin']);
	}
}

?>