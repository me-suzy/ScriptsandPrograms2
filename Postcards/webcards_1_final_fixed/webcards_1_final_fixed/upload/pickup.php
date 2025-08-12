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
// $Id: pickup.php,v 1.00 2005/07/26 11:32:11 chrisc Exp $

/*-----------------------------------------------
  ENSURE CONTENT CANNOT BE ACCESSED DIRECTLY
 ------------------------------------------------*/
define("LOADED", 1);

//First of all, if lock.cgi doesn't exist we must exit
if(!file_exists("lock.cgi"))
{
	die("The program is not properly installed and has been halted as a precaution. Please contact the webmaster.");
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

set_magic_quotes_runtime(0); // Strip slashes from output

require "./config.php";
require "./source/functions.php";

/*-----------------------------------------------
  REQUIRE LANGUAGE FILES
 ------------------------------------------------*/
if(isset($HTTP_COOKIE_VARS['wc_lang']) && $HTTP_COOKIE_VARS['wc_lang'] != "")
{
	$lang_dir = $HTTP_COOKIE_VARS['wc_lang'];
}
else
{
	$lang_dir = $conf['default_pub_lang'];
}

$lang_dir = is_dir($conf['dir'] . "lang/" . $lang_dir) ? $lang_dir : "English";

	require_once "./lang/" . $lang_dir . "/pickup.php";
	require_once "./lang/" . $lang_dir . "/global.php";


/*-----------------------------------------------
  REQUIRE DB DRIVER
 ------------------------------------------------*/

if(!file_exists("./source/drivers/" . $conf['db_driver'] . ".php"))
{
	die("Unable to include the driver file. Please manually check your database settings and folder names");
}
require_once "./source/drivers/" . $conf['db_driver'] . ".php";

if($conf['buffer'] == "y")
{
	ob_start("ob_gzhandler");
}

$DB = new DB($conf['dbhost'], $conf['dbuser'], $conf['dbpass'], $conf['dbname']);
if (!$DB->connect())
{
	error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
}
//Connect to the database

if(check_ip(getip()) == "yes")
{
	output($conf['ban_message']);
}
	if (isset($HTTP_GET_VARS['act']))
	{
		switch($HTTP_GET_VARS['act'])
		{
			case 'pickup':
			$to_do = attempt_to_pickup();
			break;

			case 'show_resend_form':
			$to_do = show_resend_form();
			break;

			case 'resend':
			$to_do = do_resend();
			break;

			default:
			$to_do = show_pickup_form();
			break;
		}
	}
	else
	{
		$to_do = show_pickup_form();
	}

	if($conf['auto_expire'] == "y")
	{
		expire();
	}

function attempt_to_pickup()
{
global $DB, $HTTP_GET_VARS, $HTTP_SERVER_VARS, $conf, $lang;

	if (!isset($HTTP_GET_VARS['id']))
	{
		return show_pickup_form($lang['no_code']);
	}
	if (strlen($HTTP_GET_VARS['id']) != 32)
	{
		return show_pickup_form($lang['invalid_code']);
	}
	if (!is_string($HTTP_GET_VARS['id']))
	{
		return show_pickup_form($lang['invalid_code']);
	}

	if (!$DB->query("SELECT pic, title, from_email, from_name, bg_color, font_face, font_size, font_color, message, date, notify, email_sent FROM " . $conf['dbprefix'] . "sent_cards WHERE id=\"" . $HTTP_GET_VARS['id'] . "\" ORDER BY id DESC LIMIT 0,1"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if ($DB->num_rows() <= 0)
	{
		return show_pickup_form($lang['card_id_invalid']);
	}

	if(!$fp = @fopen("./templates/render_card.html", "r"))
	{
		return error($lang['cannot_open_file'] . " <b>./templates/render_card.html</b>", $lang['check_file_exists'] . "|" . $lang['cannot_file_perms']);
	}

	$row = $DB->fetch_array();

	$data = @fread($fp, filesize("./templates/render_card.html"));
	$output = preg_replace("/{{title}}/i", $row['title'], $data);
	$output = preg_replace("/{{message}}/i", parse_tags($row['message']), $output);
	$output = preg_replace("/{{date}}/i", date($conf['date_format'], $row['date']), $output);
	$output = preg_replace("/{{sender_name}}/i", $row['from_name'], $output);
	$output = preg_replace("/{{bg_colour}}/i", $row['bg_color'], $output);
	$output = preg_replace("/{{font_face}}/i", $row['font_face'], $output);
	$output = preg_replace("/{{font_size}}/i", $row['font_size'], $output);
	$output = preg_replace("/{{font_color}}/i", $row['font_color'], $output);


	$chosen_pic = $row['pic'];

	//Check if a) the sender wants a notification and b) a notification has already been sent
	if($row['notify'] == "1" && $row['email_sent'] == "0")
	{
	//array($row['sender_email'])
		send_mail("send_notification", array($row['from_email']), $row['from_name'], 0, 0, $row['date']);
		if (!$DB->query("UPDATE " . $conf['dbprefix'] . "sent_cards SET email_sent=\"1\" WHERE id=\"" . $HTTP_GET_VARS['id'] . "\""))
		{
			return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
		}
	}

	if (!$DB->query("SELECT img_type, url, name, width, height FROM " . $conf['dbprefix'] . "images WHERE id=\"" . $chosen_pic . "\" ORDER BY id DESC LIMIT 1"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	$row = $DB->fetch_array();

	$macro = getMacro(getExt($row['url']));
	$macro = preg_replace("/{{name}}/i", $row['name'], $macro);
	
	//It is important to set the width and height variables to 0 each time or they will be reused between images
	$width = "";
	$height = "";
	if($row['width'] != "" && $row['width'] != "0" && $row['width'] != "NULL")
	{
		$width = "width=\"" . $row['width'] . "\"";
	}
	if($row['height'] != "" && $row['height'] != "0" && $row['height'] != "NULL")
	{
		$height = "height=\"" . $row['height'] . "\"";
	}
	$macro = preg_replace("/{{width}}/i", $width, $macro);
	$macro = preg_replace("/{{height}}/i", $height, $macro);
	
	if($row['img_type'] == "upload")
	{
		$macro = preg_replace("/{{img}}/i", $conf['url'] . "images/" . $row['url'], $macro);
	}
	else
	{
		$macro = preg_replace("/{{img}}/i", $row['url'], $macro);
	}


	$output = preg_replace("/{{pic}}/i", $macro, $output);
	return $output;
}

function show_pickup_form($errors="")
{
global $conf, $lang;
	$html = "<span class=\"page_header\">" . $lang['pickup_a_webcard'] . "</span><br /><br />";
	if (isset($errors))
	{
		$html .= "<span class=\"warning\">" . $errors . "</span>";
	}
	$html .= "<form action=\"" . $conf['pickup_script'] . "\" method=\"get\">\n\n";
	$html .= "<input type=\"hidden\" name=\"act\" value=\"pickup\" />";
	$html .= $lang['fill_in_id'] . "<br>\n";
	$html .= "<input type=\"text\" name=\"id\" size=\"60\" class=\"chunky\" />\n<input type=\"submit\" value=\"" . $lang['go'] . "\" class=\"chunky\" /></form>";
	$html .= $lang['forgot_code'] . "<a href=\"" . $conf['pickup_script'] . "?act=show_resend_form\">" . $lang['click_forgot'] . "</a>.";
	return $html;
}

function show_resend_form($errors="")
{
global $conf, $lang;

	if($conf['enable_resends'] != "y")
	{
		return $lang['no_resends'];
	}

	$html = "<span class=\"page_header\">" . $lang['forgotten_pickup_code'] . "</span><br /><br />";
	if (isset($errors))
	{
		$html .= "<span class=\"warning\">" . $errors . "</span>";
	}
	$html .= "<form action=\"" . $conf['pickup_script'] . "?act=resend\" method=\"post\">\n\n";
	$html .= $lang['explain_pickup_procedure'] . "<br />\n";
	$html .= "<br \/><input type=\"text\" name=\"email\" size=\"60\" class=\"chunky\" />\n<input type=\"submit\" value=\"" . $lang['go'] . "\" class=\"chunky\"></form>";
	return $html;
}

function do_resend()
{
global $DB, $conf, $HTTP_POST_VARS, $lang;

	if($conf['enable_resends'] != "y")
	{
		return $lang['no_resends'];
	}

	if($HTTP_POST_VARS['email'] == "")
	{
		return show_resend_form($lang['no_email']);
	}

	if (!$DB->query("SELECT id, from_name, recip_email FROM " . $conf['dbprefix'] . "sent_cards ORDER BY date DESC"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	while($row = $DB->fetch_array())
	{
		$card_id = $row['id'];
		$mail_array = explode(",", strtolower($row['recip_email']));

		if(in_array(strtolower($HTTP_POST_VARS['email']), $mail_array))
		{
			//echo "send_mail(\"resend_validation\", array(" . $HTTP_POST_VARS['email'] . "), \"0\", \"0\", " . $row['id'] . ")<br />";
			send_mail("resend_validation", array($HTTP_POST_VARS['email']), "0", "0", $row['id']);
			if (!$DB->query("UPDATE " . $conf['dbprefix'] . "sent_cards SET num_resends=num_resends+1 WHERE id=\"" . $card_id . "\" LIMIT 1"))
			{
				return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
			}
			return ($lang['reminder_sent']);
		}
	}

	return $lang['no_cards_found'] . "\"" . $HTTP_POST_VARS['email'] . "\"." . $lang['webcard_may_be_deleted'];

}

function output($what)
{
global $HTTP_GET_VARS, $HTTP_COOKIE_VARS, $to_do, $cpy, $DB, $conf, $lang;

	$what = isset($what) ? $what : $to_do;

	$DB->disconnect();

	$stats = get_stats("p");

	if(!$fp = @fopen("./templates/template.html", "r"))
	{
		error($lang['cannot_open_file'] . " <b>./templates/template.html</b>", $lang['check_file_exists'] . "|" . $lang['cannot_file_perms']);
	}
	$data = @fread($fp, filesize("./templates/template.html"));

	
	/*===============================================
	Add our chosen stylehseet
	
	How to choose which stylehseet to use:
	1. If a sheet is being previewed, use that or
	2. Read the cookie to see if a choice has been made or
	3. Use the Default.css sheet
	4. Check if the chosen stylsheet exists. If not, use Default.css
	================================================*/
	
	if(isset($HTTP_GET_VARS['preview_style']) && $HTTP_GET_VARS['preview_style'] != "")
	{
		$sheet_to_use = $HTTP_GET_VARS['preview_style'];
	}
	else if(isset($HTTP_COOKIE_VARS['wc_style']) && $HTTP_COOKIE_VARS['wc_style'] != "")
	{
		$sheet_to_use = $HTTP_COOKIE_VARS['wc_style'];
	}
	else
	{
		$sheet_to_use = "Default.css";
	}
	$sheet_to_use = file_exists($conf['dir'] . "templates/styles/" . $sheet_to_use) ? $sheet_to_use : "Default.css";

	//List available stylesheets that the user can pick from
	$dp = opendir($conf['dir'] . "templates/styles");

	while($file = readdir($dp))
	{
		if($file!="." && $file!="..")
		{
			$filename_array = explode(".", $file);
			$title = $filename_array['0'];
			$style_list .= "<option value=\"" . $file . "\">" . $title . "</option>\n";
		}
	}
	
	//List available languages that the user can pick from
	$dp = opendir($conf['dir'] . "./lang");
	if(!$dp)
	{
		return error($lang['no_open_lang_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms']);
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!=".." && $file!="index.html")
		{
			$lang_list .= "<option value=\"" . $file . "\">" . $file . "</option>";
		}
	}

	$script_output = preg_replace("/{{content}}/i", $what, $data);
	$script_output = preg_replace("/{{chosen_style}}/i", $sheet_to_use, $script_output);
	$script_output = preg_replace("/{{style_list}}/i", $style_list, $script_output);
	$script_output = preg_replace("/{lang_list}}/i", $lang_list, $script_output);
	$script_output = preg_replace("/{{stats}}/i", $stats . $cpy, $script_output);

	$script_output = preg_replace("/\|\|(\w+)\|\|/ie", "stripslashes(\$lang['\$1'])", $script_output);

	echo $script_output;
	exit();
}

//If we have made it all the way to the end without any errors, output the final response
output($to_do);

?>