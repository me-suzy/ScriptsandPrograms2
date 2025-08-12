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
// $Id: ad_config.php,v 1.00 2004/06/08 18:43:28 chrisc Exp $

/*-----------------------------------------------
  ENSURE THE SCRIPT IS NOT BEING ACCESSED DIRECTLY
 ------------------------------------------------*/
if(!defined("LOADED"))
{
	die("Cannot access the script directly");
}

switch($HTTP_GET_VARS['what'])
{
		case 'general':
		$to_do =  render_general_config();
		break;

		case 'database':
		$to_do =  render_database_config();
		break;

		case 'debug':
		$to_do =  render_debug_config();
		break;

		case 'email':
		$to_do =  render_email_config();
		break;

		case 'cardsettings':
		$to_do = render_card_config();
		break;

		case 'doconfig':
		$to_do = do_config();
		break;
}

function render_general_config($msg="")
{
global $conf, $HTTP_GET_VARS, $lang;

	$html = "<form name=\"config\" action=\"" . $conf['admin_script'] . "?act=config&amp;what=doconfig&amp;type=general&amp;s=" . $HTTP_GET_VARS['s'] . "\" method=\"post\">\n";
	$html .= "<span class=\"title\">" . $lang['general_settings'] . "</span>" . help_popup("general_config") . "\n";
	$html .= "<table width=\"98%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">";
	if($msg != "")
	{
		$html .= "<tr><td colspan=\"2\" class=\"theader\">" . $msg . "</td></tr>";
	}
	
	$html .= "<tr><td colspan=\"2\" class=\"theader\">" . $lang['layout_options']. "</td></tr>";
	
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['num_img_show'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"img_per_row\" value=\"" . $conf['img_per_row'] . "\" /></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['num_cat_show'] . ":</td><td width=\"50%\" class=\"ad_row\"><input size=\"50\" type=\"text\" name=\"cat_to_show\" value=\"" . $conf['cat_to_show'] . "\" /></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['num_new_pop_imgs'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"new_pop_imgs\" value=\"" . $conf['new_pop_imgs'] . "\" /></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['date_format'] . "</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"date_format\" value=\"" . $conf['date_format'] . "\" />\n</td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['ban_msg'] . ":</td><td class=\"ad_row\" width=\"50%\"><textarea name=\"ban_message\" cols=\"50\" rows=\"10\">" . stripslashes($conf['ban_message']) . "</textarea>\n</td></tr>";

	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['public_default_lang'] . "</td><td class=\"ad_row\" width=\"50%\">\n";
	$html .= "<select name=\"default_pub_lang\">";

	$dp = opendir($conf['dir'] . "./lang");
	if(!$dp)
	{
		return error($lang['no_open_lang_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms']);
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!=".." && is_dir($conf['dir'] . "lang/" . $file))
		{
			$html .= "<option value=\"" . $file . "\"";
			if($conf['default_pub_lang'] == $file)
			{
				$html .= " selected=\"selected\"";
			}
			$html .= ">" . $file . "</option>";
		}
	}
	$html .= "</select></td></tr>";
	
	$html .= "<tr><td colspan=\"2\" class=\"theader\">" . $lang['thumb_settings']. "</td></tr>";
	
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['thumbnail_dimensions'] . ":</td><td class=\"ad_row\" width=\"50%\">" . $lang['width'] . ": <input size=\"3\" type=\"text\" name=\"thumb_width\" value=\"" . $conf['thumb_width'] . "\" /><br /><br />" . $lang['height'] . ": <input size=\"3\" type=\"text\" name=\"thumb_height\" value=\"" . $conf['thumb_height'] . "\" />\n</td></tr>\n";
	
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['thumb_format'] . "</td><td class=\"ad_row\" width=\"50%\">\n";
	$html .= "<select name=\"thumb_format\">\n";
	$html .= "<option value=\"png\"";
	if ($conf['thumb_format'] == "png") { $html .= " selected"; }
	$html .= ">png</option>\n<option value=\"jpg\"";
	if ($conf['thumb_format'] == "jpg") { $html .= " selected"; }
	$html .= ">jpg</option>\n<option value=\"gif\"";
	if ($conf['thumb_format'] == "gif") { $html .= " selected"; }
	$html .= ">gif</options>\n</select>\n</td></tr>\n";
	$html .= "<tr><td class=\"ad_row\" colspan=\"2\"><input type=\"submit\" value=\"" . $lang['update_conf'] . "\">\n</td></tr>\n</form>";

	return $html;
}

function render_database_config($msg="")
{
global $conf, $HTTP_GET_VARS, $lang;

	$html = "<form name=\"config\" action=\"" . $conf['admin_script'] . "?act=config&amp;what=doconfig&amp;type=database&amp;s=" . $HTTP_GET_VARS['s'] . "\" method=\"post\">\n";
	$html .= "<span class=\"title\">" . $lang['database_conf'] . "</span>" . help_popup("db_config") . "\n<br /><br />";
	$html .= "<table width=\"98%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">";
	if($msg != "")
	{
		$html .= "<tr><td colspan=\"2\" class=\"theader\">" . $msg . "</td></tr>";
	}
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['available_drivers'] . "</td><td class=\"ad_row\" width=\"50%\"><select name=\"db_driver\" disabled=\"disabled\">";
	$dp = opendir($conf['dir'] . "source/drivers");
	if(!$dp)
	{
		return error($lang['no_open_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms'] . "|" . $lang['check_1_db_driver'] . "");
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!=".." && $file!="index.html")
		{
			$file_parts = explode(".", $file);
			$html .= "<option value=\"" . $file_parts[0] . "\"";
			if ($conf['db_driver'] == "$file_parts[0]") { $html .= " selected"; }
			$html .= ">$file_parts[0]</option>";
		}
	}
	$html .= "</select></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['db_tbl_prefix'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"dbprefix\" value=\"" . $conf['dbprefix'] . "\" /></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['db_host'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"dbhost\" value=\"" . $conf['dbhost'] . "\" /></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['db_username'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"dbuser\" value=\"" . $conf['dbuser'] . "\" /></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['db_password'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"dbpass\" value=\"" . $conf['dbpass'] . "\" /></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['db_name'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"dbname\" value=\"" . $conf['dbname'] . "\" /></td></tr>\n";
	$html .= "<tr><td class=\"ad_row\" colspan=\"2\"><input type=\"submit\" value=\"" . $lang['update_conf'] . "\">\n</td></tr>\n</table>\n</form>";
	return $html;
}

function render_debug_config($msg="")
{
global $conf, $HTTP_GET_VARS, $lang;

	$html = "<form name=\"config\" action=\"" . $conf['admin_script'] . "?act=config&amp;what=doconfig&amp;type=debug&amp;s=" . $HTTP_GET_VARS['s'] . "\" method=\"post\">\n";
	$html .= "<span class=\"title\">" . $lang['debug_options'] . "</span>" . help_popup("debug_settings") . "<br /><br />\n";
	
	$html .= "<table width=\"98%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">";
	if($msg != "")
	{
		$html .= "<tr><td colspan=\"2\" class=\"theader\">" . $msg . "</td></tr>";
	}
	
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['auto_expire'] . "<br />" . $lang['manual_expire'] . " \"<a href=\"" . $conf['admin_script'] . "?act=toolbox&amp;what=expirer&amp;s=" . $HTTP_GET_VARS['s'] . "\">" . $lang['run_card_expirer'] . "</a>\" " . $lang['in_main_menu'] . "</td><td class=\"ad_row\" width=\"50%\">\n";
	$html .= "<select name=\"auto_expire\">";
	$html .= "<option value=\"y\""; if ($conf['auto_expire'] == "y") { $html .= " selected"; } $html .= " />" . $lang['expire_cards_auto'] . "\n";
	$html .= "<option value=\"n\""; if ($conf['auto_expire'] == "n") { $html .= " selected"; } $html .= " />" . $lang['no_expire_cards_auto'] . "\n";
	$html .= "</select></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['use_ob'] . "</td><td class=\"ad_row\" width=\"50%\">\n";
	$html .= "<select name=\"buffer\">";
	$html .= "<option value=\"y\""; if ($conf['buffer'] == "y") { $html .= " selected"; } $html .= " />" . $lang['ob_on'] . "\n";
	$html .= "<option value=\"n\""; if ($conf['buffer'] == "n") { $html .= " selected"; } $html .= " />" . $lang['ob_off'] . "\n";
	$html .= "</select></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['where_show_query_num'] . "</td><td class=\"ad_row\" width=\"50%\">\n";
	$html .= "<select name=\"query_count\">";
	$html .= "<option value=\"n\""; if ($conf['query_count'] == "n") { $html .= " selected"; } $html .= " />" . $lang['no_show'] . "\n";
	$html .= "<option value=\"p\""; if ($conf['query_count'] == "p") { $html .= " selected"; } $html .= " />" . $lang['only_public'] . "\n";
	$html .= "<option value=\"a\""; if ($conf['query_count'] == "a") { $html .= " selected"; } $html .= " />" . $lang['only_admin'] . "\n";
	$html .= "<option value=\"b\""; if ($conf['query_count'] == "b") { $html .= " selected"; } $html .= " />" . $lang['everywhere'] . "\n";
	$html .= "</select></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['where_show_queries'] . "</td><td class=\"ad_row\" width=\"50%\">\n";
	$html .= "<select name=\"sql_show\">";
	$html .= "<option value=\"n\""; if ($conf['sql_show'] == "n") { $html .= " selected"; } $html .= " />" . $lang['no_show'] . "\n";
	$html .= "<option value=\"p\""; if ($conf['sql_show'] == "p") { $html .= " selected"; } $html .= " />" . $lang['only_public'] . "\n";
	$html .= "<option value=\"a\""; if ($conf['sql_show'] == "a") { $html .= " selected"; } $html .= " />" . $lang['only_admin'] . "\n";
	$html .= "<option value=\"b\""; if ($conf['sql_show'] == "b") { $html .= " selected"; } $html .= " />" . $lang['everywhere'] . "\n";
	$html .= "</select></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['where_show_get_post'] . "</td><td class=\"ad_row\" width=\"50%\">\n";
	$html .= "<select name=\"get_post_show\">";
	$html .= "<option value=\"n\""; if ($conf['get_post_show'] == "n") { $html .= " selected"; } $html .= " />" . $lang['no_show'] . "\n";
	$html .= "<option value=\"p\""; if ($conf['get_post_show'] == "p") { $html .= " selected"; } $html .= " />" . $lang['only_public'] . "\n";
	$html .= "<option value=\"a\""; if ($conf['get_post_show'] == "a") { $html .= " selected"; } $html .= " />" . $lang['only_admin'] . "\n";
	$html .= "<option value=\"b\""; if ($conf['get_post_show'] == "b") { $html .= " selected"; } $html .= " />" . $lang['everywhere'] . "\n";
	$html .= "</select></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['where_show_ex_times'] . "</td><td class=\"ad_row\" width=\"50%\">\n";
	$html .= "<select name=\"render_time\">";
	$html .= "<option value=\"n\""; if ($conf['render_time'] == "n") { $html .= " selected"; } $html .= " />" . $lang['no_show'] . "\n";
	$html .= "<option value=\"p\""; if ($conf['render_time'] == "p") { $html .= " selected"; } $html .= " />" . $lang['only_public'] . "\n";
	$html .= "<option value=\"a\""; if ($conf['render_time'] == "a") { $html .= " selected"; } $html .= " />" . $lang['only_admin'] . "\n";
	$html .= "<option value=\"b\""; if ($conf['render_time'] == "b") { $html .= " selected"; } $html .= " />" . $lang['everywhere'] . "\n";
	$html .= "</select></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['where_show_server_load'] . "</td><td class=\"ad_row\" width=\"50%\">\n";
	$html .= "<select name=\"server_load\">";
	$html .= "<option value=\"n\""; if ($conf['server_load'] == "n") { $html .= " selected"; } $html .= " />" . $lang['no_show'] . "\n";
	$html .= "<option value=\"p\""; if ($conf['server_load'] == "p") { $html .= " selected"; } $html .= " />" . $lang['only_public'] . "\n";
	$html .= "<option value=\"a\""; if ($conf['server_load'] == "a") { $html .= " selected"; } $html .= " />" . $lang['only_admin'] . "\n";
	$html .= "<option value=\"b\""; if ($conf['server_load'] == "b") { $html .= " selected"; } $html .= " />" . $lang['everywhere'] . "\n";
	$html .= "</select></td></tr>";
	$html .= "<tr><td colspan=\"2\" class=\"ad_row\"><input type=\"submit\" value=\"" . $lang['update_conf'] . "\">\n</td></tr></table></form>";
	return $html;
}

function render_email_config($msg="")
{
global $conf, $HTTP_GET_VARS, $lang;

	$html = "<form name=\"config\" action=\"" . $conf['admin_script'] . "?act=config&amp;what=doconfig&amp;type=email&amp;s=" . $HTTP_GET_VARS['s'] . "\" method=\"post\">\n";
	$html .= "<span class=\"title\">" . $lang['email_options'] . "</span>" . help_popup("email_config") . "\n<br /><br />";

	$html .= "<table width=\"98%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">";
	if($msg != "")
	{
		$html .= "<tr><td colspan=\"2\" class=\"theader\">" . $msg . "</td></tr>";
	}
	
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['admin_contact'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"email_from\" value=\"" . $conf['email_from'] . "\" /></td></tr>\n";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['cc_address'] . ":</td><td class=\"ad_row\" width=\"50%\"><input type=\"text\" size=\"50\" value=\"" . $conf['cc_address'] . "\" name=\"cc_address\"></td></tr>\n";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['use_mail_smtp'] . "<br />\n<input type=\"button\" onClick=\"self.location='" . $conf['admin_script'] . "?act=toolbox&amp;what=test_email&amp;s=" . $HTTP_GET_VARS['s'] . "'\" value=\"" . $lang['click_test_email'] . "\"></td><td class=\"ad_row\" width=\"50%\">\n";
	$html .= "<select name=\"use_mail_smtp\">";
	$html .= "<option value=\"mail\""; if ($conf['use_mail_smtp'] == "mail") { $html .= " selected"; } $html .= " />" . $lang['use_mail'] . "\n";
	$html .= "<option value=\"smtp\""; if ($conf['use_mail_smtp'] == "smtp") { $html .= " selected"; } $html .= " />" . $lang['use_smtp'] . "\n";
	$html .= "</select></td></tr>";
	$html .= "<tr><td colspan=\"2\" class=\"theader\">" . $lang['only_smtp_settings']. "</td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['smtp_host'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"smtp_host\" value=\"" . $conf['smtp_host'] . "\" /></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['smtp_port'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"smtp_port\" value=\"" . $conf['smtp_port'] . "\" /></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['smtp_user'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"smtp_user\" value=\"" . $conf['smtp_user'] . "\" /></td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['smtp_pass'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"smtp_pass\" value=\"" . $conf['smtp_pass'] . "\" /></td></tr>";
	$html .= "<tr><td colspan=\"2\" class=\"ad_row\"><input type=\"submit\" value=\"" . $lang['update_conf'] . "\" /></td></tr></table>\n</form>";
	return $html;
}

function render_card_config($msg="")
{
global $lang, $conf, $HTTP_GET_VARS;

	$html = "<form action=\"" . $conf['admin_script'] . "?act=config&amp;what=doconfig&amp;type=card&amp;s=" . $HTTP_GET_VARS['s'] . "\" method=\"post\">";
	$html .= "<span class=\"title\">" . $lang['cust_card_settings'] . "</span>" . help_popup("email_config") . "<br /><br />";
	
	$html .= "<table width=\"98%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">";

	if($msg != "")
	{
		$msg = "<tr><td colspan=\"2\" class=\"theader\">" . $msg . "</td></tr>";
	}

	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['max_recip'] . ":</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"max_recip\" value=\"" . $conf['max_recip'] . "\" />\n</td></tr>\n";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['cards_exp_time'] . "</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"expiry_time\" value=\"" . $conf['expiry_time'] . "\" />\n ";
	$html .= "<select name=\"expiry_units\">\n";
	$html .= "<option value=\"31536000\"";
	if ($conf['expiry_units'] == "31536000") { $html .= " selected"; }
	$html .= ">" . $lang['yrs'] . "</option>\n<option value=\"2592000\"";
	if ($conf['expiry_units'] == "2592000") { $html .= " selected"; }
	$html .= ">" . $lang['mnths'] . "</option>\n<option value=\"604800\"";
	if ($conf['expiry_units'] == "604800") { $html .= " selected"; }
	$html .= ">" . $lang['wks'] . "</option>\n<option value=\"86400\"";
	if ($conf['expiry_units'] == "86400") { $html .= " selected"; }
	$html .= ">" . $lang['days'] . "</options>\n<option value=\"3600\"";
	if ($conf['expiry_units'] == "3600") { $html .= " selected"; }
	$html .= ">" . $lang['hrs'] . "</option>\n<option value=\"60\"";
	if ($conf['expiry_units'] == "60") { $html .= " selected"; }
	$html .= ">" . $lang['mins'] . "</option>\n</select></td></tr>";

	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['max_msg_length'] . "</td><td class=\"ad_row\" width=\"50%\"><input size=\"50\" type=\"text\" name=\"max_message_length\" value=\"" . $conf['max_message_length'] . "\" />\n</td></tr>";
	
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['enable_resends'] . ":</td><td class=\"ad_row\" width=\"50%\"><input class=\"positive\" type=\"radio\" name=\"enable_resends\" value=\"y\"";
	if ($conf['enable_resends'] == "y") { $html .= " checked=\"checked\""; }
	$html .= "> " . $lang['enable_resends_yes'] . "\n<br />\n<input class=\"negative\" type=\"radio\" name=\"enable_resends\" value=\"n\"";
	if ($conf['enable_resends'] == "n") { $html .= " checked=\"checked\""; }
	$html .= "> " . $lang['enable_resends_no'] . "\n</td></tr>\n";
	$html .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['enable_notify'] . ":</td><td class=\"ad_row\" width=\"50%\"><input class=\"positive\" type=\"radio\" name=\"enable_notify\" value=\"y\"";
	if ($conf['enable_notify'] == "y") { $html .= " checked=\"checked\""; }
	$html .= "> " . $lang['enable_notify_yes'] . "\n<br />\n<input class=\"negtive\" type=\"radio\" name=\"enable_notify\" value=\"n\"";
	if ($conf['enable_notify'] == "n") { $html .= " checked=\"checked\""; }
	$html .= "> " . $lang['enable_notify_no'] . "\n</td></tr>\n";
	
	//List available font faces
	$font_face_data = "";
	$lines = explode(",", $conf['font_faces']);

	foreach ($lines as $lines)
	{
		$font_face_data .= "" . $lines . "\n";
	}
	$font_face_data = rtrim($font_face_data);

	//List available font colours
	$font_colour_data = "";
	$lines = explode(",", $conf['font_colours']);

	foreach ($lines as $lines)
	{
		$font_colour_data .= "" . $lines . "\n";
	}
	$font_colour_data = rtrim($font_colour_data);

	// List available font sizes
	$font_size_data = "";
	$lines = explode(",", $conf['font_sizes']);

	foreach ($lines as $lines)
	{
		$font_size_data .= "" . $lines . "\n";
	}
	$font_size_data = rtrim($font_size_data);

	//List available background colours
	$font_bg_colour_data = "";
	$lines = explode(",", $conf['font_bg_colours']);

	foreach ($lines as $lines)
	{
		$font_bg_colour_data .= "" . $lines . "\n";
	}
	$font_bg_colour_data = rtrim($font_bg_colour_data);


	$basic = parse_basic_admin_template("./templates/admin/admin_font_settings.html");
	
	$font_data = preg_replace("/{{font_face_data}}/i", $font_face_data, $basic);
	$font_data = preg_replace("/{{font_colour_data}}/i", $font_colour_data, $font_data);
	$font_data = preg_replace("/{{font_size_data}}/i", $font_size_data, $font_data);
	$font_data = preg_replace("/{{font_bg_colours_data}}/i", $font_bg_colour_data, $font_data);
	
	$html .= $font_data;
	
	$html .= "<tr><td class=\"ad_row\" colspan=\"2\">\n<input type=\"submit\" value=\"" . $lang['save_card_settings'] . "\" />";
	
	$html .= "</td></tr></table></form>";
	
	return $html;

}

function do_config()
{
global $HTTP_POST_VARS, $HTTP_GET_VARS, $conf, $lang, $ad_lang_cookie;

	$convert_to_string = array("font_faces", "font_colours", "font_sizes", "font_bg_colours", "ip_ban", "email_ban"); //This array contains all the textarea inputs that must be converted to string
	
	foreach($convert_to_string as $k)
	{
		if(isset($HTTP_POST_VARS[$k]))
		{
			$exploded = explode("\n", $HTTP_POST_VARS[$k]); //Creates an array of each item based around the newline character
			$imploded = implode(",", $exploded); //Turns the array into a string, spearated by a comma
			$tidy = preg_replace("/\s/", "", $imploded); // Removes any spaces
			$HTTP_POST_VARS[$k] = $tidy; //Our new data is part of the $conf array
		}
	}

	$str = "<?php\n";

	foreach ($HTTP_POST_VARS as $k => $v)
	// Take our submitted data, clean it up and add it to the $conf array
	{
		$v = preg_replace("/\n/", "", $v);
		$v = preg_replace("/'/", "&#39;", $v);
		$v = stripslashes($v);
		$conf[$k] = $v;
	}

	ksort($conf);

	foreach ($conf as $k => $v)
	{
		$v = addslashes($v);
		$str .= "\$conf['" . $k . "'] = \"" . $v . "\";\n";
	}

	$str .= "?>";
	if ($fp = @fopen($conf['dir'] . "config.php", "w"))
	{
		@fwrite($fp, $str, strlen($str));
		@fclose($fp);
		
		switch($HTTP_GET_VARS['type'])
		{
			case 'general':
			return render_general_config($lang['config_updated']);
			break;

			case 'database':
			return render_database_config($lang['config_updated']);
			break;

			case 'debug':
			return render_debug_config($lang['config_updated']);
			break;

			case 'email':
			return  render_email_config($lang['config_updated']);
			break;

			case 'card':
			return render_card_config($lang['config_updated']);
			break;

			case 'ip_ban':
			require_once("./source/ad_ban.php"); //We need this to define the correct functions
			require_once("./lang/" . $ad_lang_cookie . "/admin/ad_ban.php");
			return  ip_ban($lang['config_updated']);
			break;

			case 'email_ban':
			require_once("./source/ad_ban.php"); //We need this to define the correct functions
			require_once("./lang/" . $ad_lang_cookie . "/admin/ad_ban.php");
			return email_ban($lang['config_updated']);
			break;
		
			default:
			return $lang['config_updated'];
		}

	}
	else
	{
		return error($lang['config_create_error'], $lang['check_dir_perms']);
	}
}
?>