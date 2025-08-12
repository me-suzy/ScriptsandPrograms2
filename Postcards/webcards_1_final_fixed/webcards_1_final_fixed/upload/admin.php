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
// $Id: admin.php,v 1.00 2005/07/31 18:59:30 chrisc Exp $

/*-----------------------------------------------
  USER CONFIGURABLE ELEMENTS
 ------------------------------------------------*/
 
// Do you want to check admin IP addresses?
// This can causes problems if your IP address changes whilst you are online
// bool 1 or 0

$check_ip = 1;

// Use output buffering in the ACP?
// bool 1 or 0

$buffer_acp = 1;

//print_r($HTTP_POST_VARS);
 
 
/*-----------------------------------------------
  NO USER EDITABLE SECTIONS BELOW
 ------------------------------------------------*/

/*-----------------------------------------------
  ENSURE CONTENT CANNOT BE ACCESSED DIRECTLY
 ------------------------------------------------*/
define("LOADED", 1);

/*-----------------------------------------------
  STRIP SLASHES FROM OUTPUT
 ------------------------------------------------*/
set_magic_quotes_runtime(0);

/*-----------------------------------------------
  IF WE ARE NOT IN SAFE MODE THEN ATTEMPT TO REMOVE SCRIPT TIME LIMIT
 ------------------------------------------------*/
if((bool) @ini_get('safe_mode') == 0)
{
	set_time_limit(0);
}


/*-----------------------------------------------
  REQUIRE CONFIGURATION SETTINGS
 ------------------------------------------------*/
require_once "./config.php";


/*-----------------------------------------------
  REQUIRE GLOBAL FUNCTIONS FILE
 ------------------------------------------------*/
require_once "./source/functions.php";


/*-----------------------------------------------
  BEGIN OUTPUT BUFFERING
 ------------------------------------------------*/
if($buffer_acp == 1)
{
	ob_start("ob_gzhandler");
}


/*-----------------------------------------------
  REQUIRE SPECIFIC LANG FILE
 ------------------------------------------------*/
$ad_lang_cookie = $HTTP_COOKIE_VARS['temp_ad_lang'];

if(!is_dir($conf['dir'] . "lang/" . $ad_lang_cookie) || !isset($HTTP_COOKIE_VARS['temp_ad_lang']))
{
	$ad_lang_cookie = "English";
}

if(isset($HTTP_GET_VARS['act']) && file_exists("./lang/" . $ad_lang_cookie . "/admin/ad_" . $HTTP_GET_VARS['act'] . ".php"))
{
	require_once "./lang/" . $ad_lang_cookie . "/admin/ad_" . $HTTP_GET_VARS['act'] . ".php";
}
else
{
	require_once "./lang/" . $ad_lang_cookie . "/admin/ad_misc.php";
}
	require_once "./lang/" . $ad_lang_cookie . "/global.php";


/*-----------------------------------------------
  REQUIRE DB DRIVER
 ------------------------------------------------*/
if(!file_exists("./source/drivers/" . $conf['db_driver'] . ".php"))
{
	die("Unable to include the driver file. Please manually check your database settings and folder names");
}
require_once "./source/drivers/" . $conf['db_driver'] . ".php";


/*-----------------------------------------------
  ATTEMPT DATABASE CONNECTION
 ------------------------------------------------*/
$DB = new DB($conf['dbhost'], $conf['dbuser'], $conf['dbpass'], $conf['dbname']);
if (!$DB->connect())
{
	die("Fatal error!<br /><br />Unable to connect to the database.<br />Check your database settings in the configuration section. Ensure that database is switched on.");
}


/*-----------------------------------------------
  REQUIRE SPECIFIC SOURCE FILE
 ------------------------------------------------*/
if(isset($HTTP_GET_VARS['act']) && file_exists("./source/ad_" . $HTTP_GET_VARS['act'] . ".php"))
{
	require_once "./source/ad_" . $HTTP_GET_VARS['act'] . ".php";
}
else
{
	require_once "./source/ad_misc.php";
}


/*-----------------------------------------------
  PROGRAM START
 ------------------------------------------------*/



$need_to_login = 1;
//By default, admin must login

	if (!isset($HTTP_GET_VARS['s']) && $HTTP_GET_VARS['what']!="login")
	{
		output(show_login($lang['no_ad_sess_found']));
		// Login if no admin session found
	}
	else
	{
		/*--------------------------------------
		 CHECK IF THE ADMIN SESSION EXISTS IN DATABASE
		---------------------------------------*/
		if (!$DB->query("SELECT login_time, ip, id FROM `" . $conf['dbprefix'] . "admin` WHERE session=\"" . $HTTP_GET_VARS['s'] . "\""))
		{
			die(error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']));
		}
		if ($DB->num_rows() <= 0)
		{
			$to_do = show_login($lang['ad_sess_no_exist']);
			$need_to_login = 1;
		}
		else
		{
			$row = $DB->fetch_array();

			/*-----------------------------------------------
 			 CHECK THE USER IP AGAINST WHAT WE HAVE STORED?
 			------------------------------------------------*/
			if($check_ip == 1)
			{
				if ($row['ip'] != getip())
				{
					output(show_login($lang['bad_ad_ip']));
					$need_to_login = 1;
					// Check the current IP against the one stored in the database
				}
			}

			/*-----------------------------------------------
 			 CHECK WHEN THE USER LOGGED IN
 			------------------------------------------------*/
			if (time() > $row['login_time'] + 60*60*2)
			{
				output(show_login($lang['ad_sess_expired']));
				$need_to_login = 1;
			}

			/*-----------------------------------------------
 			 CHECK IF THE USER HAS AT LEAST THE CORRECT COOKIE IDS
 			------------------------------------------------*/
			if(!isset($HTTP_COOKIE_VARS['admin_id']))
			{
				output(show_login($lang['admin_cookie_error']));
			}

			/*-----------------------------------------------
 			 NOW CHECK IF THE ADMIN ID MATCHES THAT IN THE DATABASE
 			------------------------------------------------*/
			if($row['id'] != $HTTP_COOKIE_VARS['admin_id'])
			{
				output(show_login($lang['admin_cookie_id_error']));
			}


			else
			{
				$need_to_login = 0;
			}
		}
	}

	if ($need_to_login == "0" && !isset($HTTP_GET_VARS['act']))
	{
		echo parse_basic_admin_template("./templates/admin/admin_frame.html");
	}

	if ($need_to_login == "0" && ($HTTP_GET_VARS['act'] == "left"))
	{
		echo main();
	}

	if($conf['auto_expire'] == "y")
	{
		if($HTTP_GET_VARS['act'] != "toolbox" && $HTTP_GET_VARS['what'] != "expirer")
		{
			expire();
		}
	}

//A quick function to print out the HTML to access the admin help popup system
function help_popup($topic)
{
global $HTTP_GET_VARS;

$topic = isset($topic) ? $topic : "index";

 return "<span align=\"right\"><a href=\"javascript:popHelp('" . $topic . "', '" . $HTTP_GET_VARS['s'] . "');\"><img border=\"0\" style=\"cursor:help;\" src=\"./site_images/help.gif\" /></a></span>";

}	
	
function output($what)
{
global $HTTP_GET_VARS, $conf, $need_to_login, $cpy, $to_do, $DB, $time, $lang;

$what = isset($what) ? $what : $to_do;

//Initialise array to decide when to output footer and copyright
$no_footer = array("left", "view_email", "view_image");
$no_cpy = array("left");

	if (isset($HTTP_GET_VARS['act']) || $need_to_login == "1")
	{
		if(!in_array($HTTP_GET_VARS['what'], $no_footer))
		{
			$stats = get_stats("a");
		}

		if(!$fp = fopen("./templates/admin/template.html", "r"))
		{
			die(error($lang['cannot_open_file'] . "<b>templates/admin/template.html</b>", $lang['check_file_exists'] . "|" . $lang['check_file_perms']));
		}
		$data = fread($fp, filesize("./templates/admin/template.html"));
	
	}

	$script_output = preg_replace("/{{admin_content}}/i", $what, $data);
	$script_output = preg_replace("/{{onload_stuff}}/i", $onload_stuff, $script_output);
	in_array($HTTP_GET_VARS['what'], $no_cpy) ? '' : $stats .= $cpy;
	$script_output = preg_replace("/{{admin_stats}}/i", $stats, $script_output);

	//Add language variables
	$script_output = preg_replace("/\|\|(\w+)\|\|/ie", "stripslashes(\$lang['\$1'])", $script_output);
	echo $script_output;
	exit();
}

//If we have made it all the way to the end without any errors, output the final response
output($to_do);

?>