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
// $Id: ad_index.php,v 1.00 2003/09/09 20:04:25 chrisc Exp $

/*-----------------------------------------------
  ENSURE THE SCRIPT IS NOT BEING ACCESSED DIRECTLY
 ------------------------------------------------*/
if(!defined("LOADED"))
{
	die("Cannot access the script directly");
}

switch($HTTP_GET_VARS['what'])
{
	case 'left':
	$to_do = parse_basic_admin_template("./templates/admin/admin_left.html");
	break;

	case 'main':
	$to_do = main();
	break;
}

function main()
{
global $conf, $DB, $HTTP_GET_VARS, $lang, $version;

	//To save number of queries, only remove expired admins when viewing the main frame.
	if (!$DB->query("UPDATE `" . $conf['dbprefix'] . "admin` SET login_time=NULL, ip=NULL, session=NULL WHERE " . time() . " > (login_time + 60*60*2)"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	//Count number of cards in DB
	$numrows = $DB->row_count("SELECT count(id) FROM `" . $conf['dbprefix'] . "sent_cards`");

	if (!$DB->query("SELECT user, base, login_time, ip FROM `" . $conf['dbprefix'] . "admin` WHERE ip IS NOT NULL ORDER BY login_time DESC"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	while($row = $DB->fetch_array())
	{
		$current_time = time();
		$logged_in_time = $current_time - $row['login_time'];
		if($logged_in_time > 59)
		{
			$logged_in_time = sprintf("%0d", ($logged_in_time / 60) ) . $lang['mins_ago'];
		}
		else
		{
			$logged_in_time = sprintf("%0d", $logged_in_time ) . $lang['secs_ago'];
		}
		if ($row['base'] == "y")
		{
			$admin_present .= "<li>" . $row['user'] . " <span style=\"color:red\">(" . $lang['super_admin'] . ")</span> - [ IP: " . $row['ip'] . " ] - " . $lang['logged_in'] . $logged_in_time . "</li>";
		}
		else
		{
			$admin_present .= "<li>" . $row['user'] . " - [ IP: " . $row['ip'] . " ] - " . $lang['logged_in'] . $logged_in_time . "</li>";
		}
	}

	if(verify_files("error_count") > 0)
	{
		$verify_files = "<span class=\"warning\">" . $lang['errors_found'] . "</span><br />&nbsp;<a href=\"" . $conf['admin_script'] . "?act=toolbox&amp;what=verify&amp;s=" . $HTTP_GET_VARS['s'] . "\">" . $lang['click_to_fix'] . "</a>";
	}
	else
	{
		$verify_files = $lang['no_errors'];
	}

	$basic = parse_basic_admin_template("./templates/admin/admin_main.html");
	$output = preg_replace("/{{folder_size}}/i", upload_folder_size(), $basic);
	$output = preg_replace("/{{num_images}}/i", number_of_files("images/"), $output);
	$output = preg_replace("/{{num_thumbs}}/i", number_of_files("images/thumbs/"), $output);
	$output = preg_replace("/{{num_cards}}/i", $numrows, $output);
	$output = preg_replace("/{{verify_files}}/i", $verify_files, $output);
	$output = preg_replace("/{{admin_present}}/i", $admin_present, $output);
	$output = preg_replace("/{{php_version}}/i", phpversion(), $output);
	$output = preg_replace("/{{sql_version}}/i", sql_version(), $output);
	$output = preg_replace("/{{wc_version}}/i",$version, $output);
	return $output;
}

function upload_folder_size()
// This function adapted from the Invision Board script.
{
global $conf, $lang;

	$dir_size = 0;
	if ($dh = @opendir($conf['dir'] . "images/"))
	{
		while ($file = readdir($dh))
		{
			if (!preg_match("/^..?$|^index/i", $file))
			{
				$dir_size += @filesize($conf['dir'] . "images/" . $file);
			}
		}
		closedir($dh);
	}
	if ($dh = @opendir($conf['dir'] . "images/thumbs/"))
	{
		while ($file = readdir($dh))
		{
			if (!preg_match("/^..?$|^index|^thumbs.db/i", $file))
			{
				$dir_size += @filesize($conf['dir'] . "images/thumbs/" . $file);
			}
		}
		closedir($dh);
	}
	
	// This piece of code from Jesse's (jesse@jess.on.ca) contribution
	// to the PHP manual @ php.net
	
	if ($dir_size >= 1048576)
	{
		$dir_size = round($dir_size / 1048576 * 100 ) / 100 . $lang['megabytes'];
	}
	else if ($dir_size >= 1024)
	{
		$dir_size = round($dir_size / 1024 * 100 ) / 100 . $lang['kilobytes'];
	}
	else
	{
		$dir_size = $dir_size . $lang['bytes'];
	}
	return $dir_size;
}

function number_of_files($dir)
//A function to count the number of files (excluding index.*, thumbs.db on windows xp and directories) in any given folder
{
global $conf;

	$num_files = 0;

	if ($dh = @opendir($dir))
	{
		while ($file = readdir($dh))
		{
			if (!preg_match("/^..?$|^index|^thumbs.db/i", $file) && (@filesize($conf['dir'] . $dir . $file) > 0) && !is_dir($conf['dir'] . $dir . $file))
			{
				$num_files++;
			}
		}
		closedir($dh);
	}
	return $num_files;
}

?>