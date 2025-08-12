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
// $Id: ad_config.php,v 1.00 2003/03/01 15:02:33 chrisc Exp $

/*-----------------------------------------------
  ENSURE THE SCRIPT IS NOT BEING ACCESSED DIRECTLY
 ------------------------------------------------*/
if(!defined("LOADED"))
{
	die("Cannot access the script directly");
}

switch($HTTP_GET_VARS['what'])
{
		case 'ip':
		$to_do = ip_ban();
		break;

		case 'ip_tools':
		$to_do = parse_basic_admin_template("./templates/admin/admin_ip_tools.html");
		break;

		case 'use_ip_tools':
		$to_do = ip_search();
		break;

		case 'email':
		$to_do = email_ban();
		break;
}

function ip_ban($msg="")
{
global $conf, $lang, $HTTP_GET_VARS;

	$html .= "<form action=\"" . $conf['admin_script'] . "?act=config&amp;what=doconfig&amp;type=ip_ban&amp;s=" . $HTTP_GET_VARS['s'] . "\" method=\"post\">";

	$html .= "<span class=\"title\">" . $lang['update_ip_ban'] . "</span>" . help_popup("ip_filters") . "\n<br /><br />";

	$html .= "<table width=\"98%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">";
	if($msg != "")
	{
		$html .= "<tr><td colspan=\"2\" class=\"theader\">" . $msg . "</td></tr>";
	}

	$ip_data = "";
	$lines = explode(",", $conf['ip_ban']);

	foreach ($lines as $lines)
	{
		$ip_data .= "" . $lines . "\n";
	}
	$ip_data = rtrim($ip_data);
	$basic = parse_basic_admin_template("./templates/admin/admin_ip_ban.html");
	$data = preg_replace("/{{ip_ban_data}}/i", $ip_data, $basic);
	
	$html .= $data;
	$html .= "</table></form>";
	
	return $html;
}

function ip_search()
{
global $DB, $conf, $HTTP_POST_VARS, $HTTP_GET_VARS, $lang;

	if(isset($HTTP_GET_VARS['check_manual']) && $HTTP_GET_VARS['check_manual'] != "")
	{
		$HTTP_POST_VARS['ip'] = $HTTP_GET_VARS['check_manual'];
	}

	if($HTTP_POST_VARS['ip'] == "")
	{
		return error($lang['no_ip_entered'], $lang['back_enter_ip']);
	}

	$orig_terms = $HTTP_POST_VARS['ip'];

	if(preg_match("/\*/", $HTTP_POST_VARS['ip']))
	{
		$HTTP_POST_VARS['ip'] = preg_replace("/\*/", "", $HTTP_POST_VARS['ip']);
		$HTTP_POST_VARS['ip'] .= "%";
	}

	if (!$DB->query("SELECT * FROM " . $conf['dbprefix'] . "sent_cards WHERE sender_ip LIKE '" . $HTTP_POST_VARS[ip] . "'"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	$output .= "<span class=\"title\">" . $lang['ip_tools'] . "</span><br /><br /><table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\"><tr><td class=\"theader\" colspan=\"5\">" . $lang['matches_shown_below'] . " " . $orig_terms . "</td></tr>\n<tr>\n<td class=\"ad_row\" width=\"25%\"><b>" . $lang['date_sent'] . "</b></td>\n<td class=\"ad_row\" width=\"20%\"><b>" . $lang['sender'] . "</b></td><td class=\"ad_row\" width=\"20%\"><b>" . $lang['sender_ip'] . "</b></td>\n<td class=\"ad_row\" width=\"20%\"><b>" . $lang['card_title'] . "</b></td>\n<td align=\"center\" class=\"ad_row\" width=\"15%\">[ " . $lang['actions'] . " ]</td>\n</tr>\n";

	while ($row = $DB->fetch_array())
	{
		$output .= "<tr>\n<td valign=\"middle\" class=\"ad_row\">" . date("M-d-Y G:i:s", $row['date']) . "<td valign=\"middle\" class=\"ad_row\"><a href=\"mailto:" . $row['from_email'] . "\">" . $row['from_name'] . "</a></td>\n<td valign=\"middle\" class=\"ad_row\">" . $row['sender_ip'] . "</a></td>\n<td valign=\"middle\" class=\"ad_row\">" . $row['title'] . "</td><td valign=\"middle\" align=\"center\" class=\"ad_row\"><a href=\"" . $conf['admin_script'] . "?act=toolbox&amp;what=view_card_detail&amp;id=" . $row['id'] . "&amp;s=" . $HTTP_GET_VARS['s'] . "\"><img src=\"./site_images/view.gif\" border=\"0\" align=\"middle\" /></a> <a href=\"" . $conf['admin_script'] . "?act=toolbox&amp;what=delete_card&s=" . $HTTP_GET_VARS['s'] . "&id=" . $row['id'] . "\"><img src=\"./site_images/x.gif\" border=\"0\" align=\"middle\" /></a>\n</td>\n</tr>\n";
	}

	$output .= "</table>";

	return $output;

}


function email_ban($msg="")
{
global $conf, $lang, $HTTP_GET_VARS;

	$html .= "<form action=\"" . $conf['admin_script'] . "?act=config&amp;what=doconfig&amp;type=email_ban&amp;s=" . $HTTP_GET_VARS['s'] . "\" method=\"post\">";

	$html .= "<span class=\"title\">" . $lang['update_email_ban'] . "</span>" . help_popup("email_filters") . "\n<br /><br />";

	$html .= "<table width=\"98%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">";
	if($msg != "")
	{
		$html .= "<tr><td colspan=\"2\" class=\"theader\">" . $msg . "</td></tr>";
	}

	$email_data = "";
	$lines = explode(",", $conf['email_ban']);

	foreach ($lines as $lines)
	{
		$email_data .= "" . $lines . "\n";
	}
	$email_data = rtrim($email_data);
	$basic = parse_basic_admin_template("./templates/admin/admin_email_ban.html");
	$data = preg_replace("/{{email_ban_data}}/i", $email_data, $basic);
	
	$html .= $data;
	$html .= "</table></form>";
	
	return $html;
}

?>