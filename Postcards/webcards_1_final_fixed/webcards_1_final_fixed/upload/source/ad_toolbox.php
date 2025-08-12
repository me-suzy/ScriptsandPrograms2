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
// $Id: ad_toolbox.php,v 1.00 2005/07/26 19:56:00 chrisc Exp $

/*-----------------------------------------------
  ENSURE THE SCRIPT IS NOT BEING ACCESSED DIRECTLY
 ------------------------------------------------*/
if(!defined("LOADED"))
{
	die("Cannot access the script directly");
}

switch($HTTP_GET_VARS['what'])
{
		case 'lock_installer':
		$to_do = lock_installer();
		break;

		case 'verify':
		$to_do = verify_files("text");
		break;

		case 'view_cards':
		$to_do = view();
		break;

		case 'view_card_detail':
		$to_do = view_card_detail();
		break;
		// view cards from an admin perspective

		case 'delete_card':
		$to_do = delete_card();
		break;

		case 'dodelete_card':
		$to_do = dodelete_card();
		break;

		case 'expirer':
		$to_do = expire($output="text");
		break;

		case 'db_info':
		$to_do = db_info($HTTP_GET_VARS['type']);
		break;

		case 'php_config':
		ob_start();
		phpinfo();
		$to_do = ob_get_contents();
		ob_end_clean();
		break;

		case 'view_image':
		$to_do = view_image();
		break;

		case 'email_logs':
		$to_do = view_email_logs();
		break;

		case 'view_email':
		$to_do = view_email();
		break;

		case 'del_email_logs':
		$to_do = del_email_logs();
		break;

		case 'test_email':
		$to_do = test_email();
		break;
		
		case 'sql_info':
		$to_do = sql_info();
		break;
		
		case 'sql_tool':
		$to_do = sql_tool();
}

function view()
{
global $conf, $DB, $HTTP_GET_VARS, $lang;
	$output = "";
	
	$offset = isset($HTTP_GET_VARS['offset']) && is_numeric($HTTP_GET_VARS['offset']) ? $HTTP_GET_VARS['offset'] : 0;
	$limit = isset($HTTP_GET_VARS['limit']) && is_numeric($HTTP_GET_VARS['limit']) ? $HTTP_GET_VARS['limit'] : 20;
	$sort = isset($HTTP_GET_VARS['sort']) ? $HTTP_GET_VARS['sort'] : "date";
	$direction = isset($HTTP_GET_VARS['direction']) ? $HTTP_GET_VARS['direction'] : "desc";

	$numrows = $DB->row_count("SELECT count(id) FROM " . $conf['dbprefix'] . "sent_cards");

	$output .= "<span class=\"title\">" . $lang['view_sent_cards'] . "</span><br /><br />";
	$output .= $lang['are_currently'] . " <b>" . $numrows . "</b> " . $lang['webcards_present_click'];
	$output .= "<form action=\"" . $conf['admin_script'] . "\" method=\"get\">";
	$output .= "<input type=\"hidden\" name=\"act\" value=\"toolbox\" />";
	$output .= "<input type=\"hidden\" name=\"what\" value=\"view_cards\" />";
	$output .= "<input type=\"hidden\" name=\"sort\" value=\"" . $sort . "\" />";
	$output .= "<input type=\"hidden\" name=\"direction\" value=\"" . $direction . "\" />";
	$output .= "<input type=\"hidden\" name=\"s\" value=\"" . $HTTP_GET_VARS['s'] . "\" />";
	$output .= "<input type=\"hidden\" name=\"offset\" value=\"" . $offset . "\" />";
	$output .= $lang['viewing'] . " <select name=\"limit\">";
	$output .= "<option value=\"5\""; if ($limit == 5) { $output .= " selected"; } $output .= " />5";
	$output .= "<option value=\"10\""; if ($limit == 10) { $output .= " selected"; } $output .= " />10";
	$output .= "<option value=\"20\""; if ($limit == 20) { $output .= " selected"; } $output .= " />20";
	$output .= "<option value=\"50\""; if ($limit == 50) { $output .= " selected"; } $output .= " />50";
	$output .= "<option value=\"100\""; if ($limit == 100) { $output .= " selected"; } $output .= " />100";
	$output .= "</select> " . $lang['per_page'] . " <input type=\"submit\" value=\"" . $lang['update'] . "\" /></form>";

	if ($numrows < $limit)
	{
		$output .= $lang['1_page'];
	}
	else
	{
		$pages=intval($numrows/$limit);
		$totpages = $pages+1;
		$output .= "Pages: ";
    		if ($offset!=0)
		{
			$prevoffset=$offset-$limit;
			$output .= "<a href=\"" . $conf['admin_script'] . "?limit=$limit&offset=$prevoffset&act=toolbox&what=view_cards&s=" . $HTTP_GET_VARS[s] . "&sort=" . $sort . "&direction=" . $direction . "\"> " . $lang['prev'] . "</a>&nbsp;\n";
   		}
   
   		if ($numrows%$limit)
		{
			$pages++;
   		}
		for ($i=1;$i<=$pages;$i++)
		{
			$cpage = $offset + $limit;
			$cpage = $cpage/$limit;
			if ($cpage == "$i")
			{
				$output .= "<b>$i</b>&nbsp;\n";
			}
			else
			{
       				$newoffset=$limit*($i-1);
       				$output .= "<a href=\"" . $conf['admin_script'] . "?limit=$limit&offset=$newoffset&act=toolbox&what=view_cards&s=" . $HTTP_GET_VARS[s] . "&sort=" . $sort . "&direction=" . $direction . "\">$i</a>&nbsp;\n";
			}
		}
		$cpage = $offset + $limit;
		$cpage = $cpage/$limit;
   		if ((($cpage*$limit)<=$numrows) && $pages!=1)
		{
       			$newoffset=$offset+$limit;
       			$output .= "<a href=\"" . $conf['admin_script'] . "?limit=$limit&offset=$newoffset&act=toolbox&what=view_cards&s=" . $HTTP_GET_VARS[s] . "&sort=" . $sort . "&direction=" . $direction . "\">" . $lang['next'] . "</a>\n";
   		}    
	}

	if (!$DB->query("SELECT id, date, title, from_name, from_email, sender_ip FROM `" . $conf['dbprefix'] . "sent_cards` ORDER BY " . $sort . " " . $direction . " LIMIT " . $offset . ", " . $limit . ""))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	$output .= "<br><br>";
	$output .= "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"2\" class=\"config\">\n<tr>\n<td class=\"ad_row\"><b>". $lang['date_sent'] . "</b> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=view_cards&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=date&direction=desc\"><img src=\"./site_images/down.gif\" alt=\"" . $lang['sort_date_desc'] . "\" border=\"0\" /></a> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=view_cards&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=date&direction=asc\"><img src=\"./site_images/up.gif\" alt=\"" . $lang['sort_date_asc'] . "\" border=\"0\" /></a></td>\n
	<td class=\"ad_row\"><b>" . $lang['sender'] . "</b> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=view_cards&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=from_name&direction=desc\"><img src=\"./site_images/down.gif\" alt=\"" . $lang['sort_sender_desc'] . "\" border=\"0\" /></a> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=view_cards&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=from_name&direction=asc\"><img src=\"./site_images/up.gif\" alt=\"" . $lang['sort_sender_asc'] . "\" border=\"0\" /></a></td>\n
	<td class=\"ad_row\"><b>" . $lang['card_title'] . "</b> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=view_cards&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=title&direction=desc\"><img src=\"./site_images/down.gif\" alt=\"" . $lang['sort_title_desc'] . "\" border=\"0\" /></a> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=view_cards&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=title&direction=asc\"><img src=\"./site_images/up.gif\" alt=\"" . $lang['sort_title_asc'] . "\" border=\"0\" /></a></td>\n
	<td class=\"ad_row\"><b>" . $lang['sender_ip'] . "</b> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=view_cards&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=sender_ip&direction=desc\"><img src=\"./site_images/down.gif\" alt=\"" . $lang['sort_ip_desc'] . "\" border=\"0\" /></a> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=view_cards&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=sender_ip&direction=asc\"><img src=\"./site_images/up.gif\" alt=\"" . $lang['sort_ip_asc'] . "\" border=\"0\" /></a></td>\n
	<td align=\"center\" class=\"ad_row\">[ actions ]</td>\n</tr>\n";

	while ($row = $DB->fetch_array())
	{
		$output .= "<tr>\n<td valign=\"middle\" class=\"ad_row\">" . date($conf['date_format'], $row['date']) . "<td valign=\"middle\" class=\"ad_row\"><a href=\"mailto:" . $row['from_email'] . "\">" . $row['from_name'] . "</a></td>\n<td valign=\"middle\" class=\"ad_row\">" . $row['title'] . "</td>\n<td valign=\"middle\" class=\"ad_row\">" . $row['sender_ip'] . " <a href=\"" . $conf['admin_script'] . "?act=ban&what=use_ip_tools&check_manual=" . $row['sender_ip'] . "&s=" . $HTTP_GET_VARS['s'] . "\"><img src=\"./site_images/mglass.gif\" align=\"center\" valign=\"middle\" border=\"0\" alt=\"" . $lang['search_ip_from_click'] . "\" /></a></td>\n<td valign=\"middle\" align=\"center\" class=\"ad_row\"><a href=\"" . $conf['admin_script'] . "?act=toolbox&amp;what=view_card_detail&amp;id=" . $row['id'] . "&amp;s=" . $HTTP_GET_VARS['s'] . "\"><img src=\"./site_images/view.gif\" border=\"0\" align=\"middle\" /></a> <a href=\"" . $conf['admin_script'] . "?act=toolbox&amp;what=delete_card&s=" . $HTTP_GET_VARS['s'] . "&id=" . $row['id'] . "\"><img src=\"./site_images/x.gif\" border=\"0\" align=\"middle\" /></a>\n</td>\n</tr>\n";
	}
	$output .= "</table>";
	return $output;
}

function delete_card()
{
global $HTTP_GET_VARS, $lang;
	$basic = parse_basic_admin_template("./templates/admin/admin_delete_card.html");
	$data = preg_replace("/{{card_id}}/i", $HTTP_GET_VARS['id'], $basic);
	return $data;
}

function dodelete_card()
{
global $DB, $conf, $HTTP_POST_VARS, $lang;

	if ($HTTP_POST_VARS['id'] == "")
	{
		return error("No card ID was found.");
	}
	if (!$DB->query("DELETE FROM " . $conf['dbprefix'] . "sent_cards WHERE id=\"" . $HTTP_POST_VARS['id'] . "\""))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if ($DB->affected() <= 0)
	{
		return error($lang['cannot_delete_card']);
	}
	return "<span class=\"title\">" . $lang['card_deleted'] . "</span>\n<br /><br />\n" . $lang['card_req_del'];
}

function db_info($what)
{
global $conf, $DB, $lang;

//function to look at some DB variables
	$output = "<span class=\"title\">" . $lang['db_info'] . "</span><br /><br/><table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">\n<tr>\n<td width=\"50%\" class=\"theader\">" . $lang['var_name'] . "</td><td width=\"50%\" class=\"theader\">" . $lang['val'] . "\n</td>\n</tr>\n";
	switch($what)
	{
		case 'runtime':
		$query = "SHOW STATUS";
		break;

		case 'system':
		$query = "SHOW VARIABLES";
		break;

		default:
		return error($lang['bad_func_call']);
		break;
	}

	if (!$DB->query($query))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	while($row = $DB->fetch_row())
	{
		$output .= "<tr height=\"100%\">\n<td width=\"50%\" height=\"100%\" class=\"ad_row\">" . $row[0] . "\n</td><td width=\"50%\" height=\"100%\" class=\"ad_row\">" . $row[1] . "</td>\n</tr>\n";
	}
	$output .= "</table>";
	return $output;
}

function lock_installer()
{
global $lang;

	if(!($fp = @fopen("./lock.cgi", "w+")))
	{
		return error($lang['error_lock_installer'], $lang['check_dir_perms']);
	}
	else
	{
		@fputs($fp, "OOOOOOTZ");
	}
	if (file_exists("./lock.cgi"))
	{
		return basic_output($lang['installer_locked'], $lang['locking_installer']);
	}
	else
	{
		return error($lang['error_lock_installer'], $lang['check_dir_perms']);
	}
}


function view_card_detail()
{
global $HTTP_GET_VARS, $conf, $DB, $lang;

	if(!isset($HTTP_GET_VARS['id']))
	{
		return error("No card ID present");
	}

	if (!$DB->query("SELECT * FROM `" . $conf['dbprefix'] . "sent_cards` WHERE id=\"" . $HTTP_GET_VARS['id'] . "\" LIMIT 1"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	$row = $DB->fetch_array();

	$output = parse_basic_admin_template("./templates/admin/admin_view_card_details.html");

	$output = preg_replace("/{{card_id}}/i", $row['id'], $output);
	$output = preg_replace("/{{title}}/i", $row['title'], $output);
	$output = preg_replace("/{{img_id}}/i", $row['pic'], $output);
	$output = preg_replace("/{{message}}/i", $row['message'], $output);
	$output = preg_replace("/{{date}}/i", date($conf['date_format'], $row['date']), $output);
	$output = preg_replace("/{{sender_name}}/i", $row['from_name'], $output);
	$output = preg_replace("/{{sender_email}}/i", $row['from_email'], $output);
	$output = preg_replace("/{{sender_ip}}/i", $row['sender_ip'], $output);

	$recip_email = preg_replace("/,/", "<br \/>", $row['recip_email']);
	$output = preg_replace("/{{recip_email}}/i", $recip_email, $output);

	$notify = $row['notify'] == "1" ? $lang['yes'] : $lang['no'];
	$output = preg_replace("/{{notify}}/i", $notify, $output);

	$email_sent = $row['email_sent'] == "1" ? $lang['yes'] : $lang['no'];
	$output = preg_replace("/{{notify_sent}}/i", $email_sent, $output);

	$output = preg_replace("/{{num_resends}}/i", $row['num_resends'], $output);

	return $output;
}

function view_image()
{
global $conf, $DB, $HTTP_GET_VARS, $lang;

	if($HTTP_GET_VARS['img_id'] == "")
	{
		return error($lang['no_img_chosen']);
	}

	$img_type = $HTTP_GET_VARS['img_type'] == "thumb" ? "thumb" : "url";

	if (!$DB->query("SELECT * FROM " . $conf['dbprefix'] . "images WHERE id=\"" . $HTTP_GET_VARS['img_id'] . "\" LIMIT 0,1"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	$row=$DB->fetch_array();
	$label = "<span class=\"title\">" . $row['name'] . "</span><br /><br />";

	if($img_type == "thumb")
	{
		$image = getMacro(getExt($row['thumb']), "macro");
		$image = preg_replace("/{{name}}/i", $row['name'], $image);
		
		//It is important to set the width and height variables to 0 each time or they will be reused between images
		$width_thumb = "";
		$height_thumb = "";
		if($row['width_thumb'] != "" && $row['width_thumb'] != "0" && $row['width_thumb'] != "NULL")
		{
			$width_thumb = "width=\"" . $row['width_thumb'] . "\"";
		}
		if($row['height_thumb'] != "" && $row['height_thumb'] != "0" && $row['height_thumb'] != "NULL")
		{
			$height_thumb = "height=\"" . $row['height_thumb'] . "\"";
		}
		$image = preg_replace("/{{width}}/i", $width_thumb, $image);
		$image = preg_replace("/{{height}}/i", $height_thumb, $image);
		
		if($row['thumb_type'] == "upload")
		{
			$image = preg_replace("/{{img}}/i", $conf['url'] . "images/thumbs/" . $row['thumb'], $image);
		}
		else
		{
			$image = preg_replace("/{{img}}/i", $row['thumb'], $image);
		}
	}
	else
	{
		$image = getMacro(getExt($row['url']), "macro");
		$image = preg_replace("/{{name}}/i", $row['name'], $image);
		
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
		$image = preg_replace("/{{width}}/i", $width, $image);
		$image = preg_replace("/{{height}}/i", $height, $image);
		
		if($row['img_type'] == "upload")
		{
			$image = preg_replace("/{{img}}/i", $conf['url'] . "images/" . $row['url'], $image);
		}
		else
		{
			$image = preg_replace("/{{img}}/i", $row['url'], $image);
		}
	}
	return $label . $image;
}

function view_email_logs()
{
global $conf, $DB, $HTTP_GET_VARS, $lang;
	$output = "";
	
	$offset = isset($HTTP_GET_VARS['offset']) && is_numeric($HTTP_GET_VARS['offset']) ? $HTTP_GET_VARS['offset'] : 0;
	$limit = isset($HTTP_GET_VARS['limit']) && is_numeric($HTTP_GET_VARS['limit']) ? $HTTP_GET_VARS['limit'] : 20;
	$sort = isset($HTTP_GET_VARS['sort']) ? $HTTP_GET_VARS['sort'] : "date";
	$direction = isset($HTTP_GET_VARS['direction']) ? $HTTP_GET_VARS['direction'] : "desc";

	$numrows = $DB->row_count("SELECT count(id) FROM " . $conf['dbprefix'] . "email_logs");

	$output .= "<span class=\"title\">" . $lang['viewing_email_logs'] . "</span><br /><br />";
	$output .= $lang['are_currently'] . " <b>" . $numrows . "</b> " . $lang['email_logs_present_click'];
	$output .= "<br /><br /><form action=\"" . $conf['admin_script'] . "\" method=\"get\">";
	$output .= "<input type=\"hidden\" name=\"act\" value=\"toolbox\" />";
	$output .= "<input type=\"hidden\" name=\"what\" value=\"email_logs\" />";
	$output .= "<input type=\"hidden\" name=\"sort\" value=\"" . $sort . "\" />";
	$output .= "<input type=\"hidden\" name=\"direction\" value=\"" . $direction . "\" />";
	$output .= "<input type=\"hidden\" name=\"s\" value=\"" . $HTTP_GET_VARS['s'] . "\" />";
	$output .= "<input type=\"hidden\" name=\"offset\" value=\"" . $offset . "\" />";
	$output .= "<table class=\"noborder\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
	$output .= "<tr><td align=\"left\">\n" . $lang['viewing'] . " <select name=\"limit\">";
	$output .= "<option value=\"5\""; if ($limit == 5) { $output .= " selected"; } $output .= " />5";
	$output .= "<option value=\"10\""; if ($limit == 10) { $output .= " selected"; } $output .= " />10";
	$output .= "<option value=\"20\""; if ($limit == 20) { $output .= " selected"; } $output .= " />20";
	$output .= "<option value=\"50\""; if ($limit == 50) { $output .= " selected"; } $output .= " />50";
	$output .= "<option value=\"100\""; if ($limit == 100) { $output .= " selected"; } $output .= " />100";
	$output .= "</select> " . $lang['per_page'] . " <input type=\"submit\" value=\"" . $lang['update'] . "\" /></form><br /><br />";

	if ($numrows < $limit)
	{
		$output .= $lang['1_page'];
	}
	else
	{
		$pages=intval($numrows/$limit);
		$totpages = $pages+1;
		$output .= "Pages: ";
    		if ($offset!=0)
		{
			$prevoffset=$offset-$limit;
			$output .= "<a href=\"" . $conf['admin_script'] . "?limit=$limit&offset=$prevoffset&act=toolbox&what=email_logs&s=" . $HTTP_GET_VARS[s] . "&sort=" . $sort . "&direction=" . $direction . "\"> " . $lang['prev'] . "</a>&nbsp;\n";
   		}
   
   		if ($numrows%$limit)
		{
			$pages++;
   		}
		for ($i=1;$i<=$pages;$i++)
		{
			$cpage = $offset + $limit;
			$cpage = $cpage/$limit;
			if ($cpage == "$i")
			{
				$output .= "<b>$i</b>&nbsp;\n";
			}
			else
			{
       				$newoffset=$limit*($i-1);
       				$output .= "<a href=\"" . $conf['admin_script'] . "?limit=$limit&offset=$newoffset&act=toolbox&what=email_logs&s=" . $HTTP_GET_VARS[s] . "&sort=" . $sort . "&direction=" . $direction . "\">$i</a>&nbsp;\n";
			}
		}
		$cpage = $offset + $limit;
		$cpage = $cpage/$limit;
   		if ((($cpage*$limit)<=$numrows) && $pages!=1)
		{
       			$newoffset=$offset+$limit;
       			$output .= "<a href=\"" . $conf['admin_script'] . "?limit=$limit&offset=$newoffset&act=toolbox&what=email_logs&s=" . $HTTP_GET_VARS[s] . "&sort=" . $sort . "&direction=" . $direction . "\">" . $lang['next'] . "</a>\n";
   		}    
	}

	$output .= "</td>\n<td align=\"right\" valign=\"middle\"><a href=\"" . $conf['admin_script'] . "?act=toolbox&amp;what=del_email_logs&amp;s=" . $HTTP_GET_VARS['s'] . "\" onClick=\"javascript:if(confirm('" . $lang['confirm_del_email'] . "')) {return true;} else {return false;}\">" . $lang['del_email_logs'] . "</a>  <a href=\"" . $conf['admin_script'] . "?act=toolbox&amp;what=del_email_logs&amp;s=" . $HTTP_GET_VARS['s'] . "\" onClick=\"javascript:if(confirm('" . $lang['confirm_del_email'] . "')) {return true;} else {return false;}\"><img src=\"./site_images/delete.gif\" alt=\"" . $lang['del_email_logs'] . "\" border=\"0\" align=\"absmiddle\" /></a></td></tr>\n</table>";

	if (!$DB->query("SELECT * FROM " . $conf['dbprefix'] . "email_logs ORDER BY " . $sort . " " . $direction . " LIMIT " . $offset . ", " . $limit . ""))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	$output .= "<br><br>";
	$output .= "<table class=\"config\" width=\"100%\" cellpadding=\"5\" cellspacing=\"2\">\n<tr>\n<td class=\"ad_row\"><b>". $lang['date_sent'] . "</b> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=email_logs&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=date&direction=desc\"><img src=\"./site_images/down.gif\" alt=\"" . $lang['sort_date_desc'] . "\" border=\"0\" /></a> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=email_logs&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=date&direction=asc\"><img src=\"./site_images/up.gif\" alt=\"" . $lang['sort_date_asc'] . "\" border=\"0\" /></a></td>\n
	<td class=\"ad_row\"><b>" . $lang['recip_email_log'] . "</b> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=email_logs&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=recip_email&direction=desc\"><img src=\"./site_images/down.gif\" alt=\"" . $lang['sort_recip_email_desc'] . "\" border=\"0\" /></a> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=email_logs&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=recip_email&direction=asc\"><img src=\"./site_images/up.gif\" alt=\"" . $lang['sort_recip_email_asc'] . "\" border=\"0\" /></a></td>\n
	<td class=\"ad_row\"><b>" . $lang['recip_email_subject'] . "</b> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=email_logs&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=subject&direction=desc\"><img src=\"./site_images/down.gif\" alt=\"" . $lang['sort_title_desc'] . "\" border=\"0\" /></a> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=email_logs&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=subject&direction=asc\"><img src=\"./site_images/up.gif\" alt=\"" . $lang['sort_title_asc'] . "\" border=\"0\" /></a></td>\n
	<td class=\"ad_row\"><b>" . $lang['sender_ip'] . "</b> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=email_logs&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=sender_ip&direction=desc\"><img src=\"./site_images/down.gif\" alt=\"" . $lang['sort_ip_desc'] . "\" border=\"0\" /></a> <a href=\"" . $conf['admin_script'] . "?act=toolbox&what=email_logs&s=" . $HTTP_GET_VARS['s'] . "&offset=" . $offset . "&limit=" . $limit . "&sort=sender_ip&direction=asc\"><img src=\"./site_images/up.gif\" alt=\"" . $lang['sort_ip_asc'] . "\" border=\"0\" /></a></td>\n
	<td align=\"center\" class=\"ad_row\">[ actions ]</td>\n</tr>\n";

	while ($row = $DB->fetch_array())
	{
		$output .= "<tr>\n<td valign=\"middle\" class=\"ad_row\">" . date("M-d-Y G:i", $row['date']) . "<td valign=\"middle\" class=\"ad_row\"><a href=\"mailto:" . $row['recip_email'] . "\">" . $row['recip_email'] . "</a></td>\n<td valign=\"middle\" class=\"ad_row\">" . $row['subject'] . "</td>\n<td valign=\"middle\" class=\"ad_row\">" . $row['sender_ip'] . "</td>\n<td valign=\"middle\" align=\"center\" class=\"ad_row\"><a href=\"javascript:void(0);\" onClick=\"javascript:window.open('./admin.php?s=" . $HTTP_GET_VARS['s'] . "&act=toolbox&amp;what=view_email&amp;id=" . $row['id'] . "','email_pop','toolbars=0,menu=0,scrollbars=1,resizable=1,width=600,height=500');\"><img src=\"./site_images/email.gif\" border=\"0\" align=\"middle\" /></a>\n</td>\n</tr>\n";
	}
	$output .= "</table>";
	return $output;
}

function view_email()
{
global $DB, $conf, $lang, $HTTP_GET_VARS;

	$id = (int) $HTTP_GET_VARS['id'];
	if(!isset($id) || $id == "")
	{
		return $lang['error_no_email_id'];
	}

	if (!$DB->query("SELECT * FROM " . $conf['dbprefix'] . "email_logs WHERE id=" . $HTTP_GET_VARS['id'] . " LIMIT 0,1"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	$row = $DB->fetch_array();
	$output = parse_basic_admin_template("./templates/admin/admin_view_email.html");

	$output = preg_replace("/{{from}}/i", $row['sender_email'], $output);
	$output = preg_replace("/{{to}}/i", $row['recip_email'], $output);
	$output = preg_replace("/{{date}}/i", date("M-d-Y G:i:s", $row['date']), $output);
	$output = preg_replace("/{{trigger_ip}}/i", $row['sender_ip'], $output);
	$output = preg_replace("/{{subject}}/i", $row['subject'], $output);
	$content = str_replace("\n", "<br />", $row['content']);
	$output = preg_replace("/{{body}}/i", $content, $output);

	return $output;
}

function del_email_logs()
{
global $DB, $conf, $lang;

	if (!$DB->query("TRUNCATE TABLE `" . $conf['dbprefix'] . "email_logs`"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	return basic_output($lang['email_deleted']);

}

function test_email()
{
global $lang, $conf, $DB, $HTTP_GET_VARS;

	$output = "<span class=\"sub_header\">" . $lang['testing_email'] . "</span><br /><br />";

	//We are sending the admin name with the test email so the admin knows who sent it
	if (!$DB->query("SELECT user FROM " . $conf['dbprefix'] . "admin WHERE session=\"" . $HTTP_GET_VARS['s'] . "\""))
	{
		die(error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']));
	}
	
	$row = $DB->fetch_array();
	
	if(count($row['user']) <1)
	{
		show_login("FATAL ERROR!");
	}
	
	
	if(send_mail("test_email", array($conf['email_from']), $row['user'], 0, 0, time(), getip()))
	{
		$output .= $lang['email_test_send_success'];
	}
	else
	{
		$output .= $lang['email_test_send_fail'];
	}

	return $output;
}

function sql_info()
{
global $lang, $DB, $conf, $HTTP_GET_VARS;
//This function will display stats about the SQL tables

	//Firstly, declare which tables are to be shown. This means that on a shared database people cannot see non-webcards tables.
	$webcards_tables = array("admin", "categories", "email_logs", "images", "macro", "sent_cards");
		
		if (!$DB->query("SHOW TABLE STATUS FROM `" . $conf['dbname'] . "`"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	
	$html = "<span class=\"title\">" . $lang['sql_info'] . "</span><br /><br />";
	
	$html .= "[ <a href=\"" . $conf['admin_script'] . "?s=" . $HTTP_GET_VARS['s'] . "&act=toolbox&amp;what=db_info&amp;type=runtime\">" . $lang['runtime_info'] . "</a> | <a href=\"" . $conf['admin_script'] . "?s=" . $HTTP_GET_VARS['s'] . "&act=toolbox&amp;what=db_info&amp;type=system\">" . $lang['system_info'] . "</a> ]\n<br /><br />";
	
	$html .= "<form action=\"" . $conf['admin_script'] . "?act=toolbox&what=sql_tool&s=" . $HTTP_GET_VARS['s'] . "\" method=\"post\" name=\"sql_tool\">";
	$html .= "<table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">\n<tr>\n";
	$html .= "\n<td class=\"theader\"><b>" . $lang['tb_name'] . "</b></td>\n";
	$html .= "\n<td class=\"theader\"><b>" . $lang['tb_rows'] . "</b></td>\n";
	$html .= "\n<td class=\"theader\"><b>" . $lang['tb_update_time'] . "</b></td>\n";
	$html .= "\n<td class=\"theader\"><b>" . $lang['tb_data_length'] . "</b></td>\n";
	$html .= "\n<td class=\"theader\"><b>" . $lang['tb_index_length'] . "</b></td>\n";
	$html .= "\n<td class=\"theader\"><b>" . $lang['tb_total_length'] . "</b></td>\n";
	$html .= "\n<td align=\"center\" class=\"theader\"><input type=\"checkbox\" onClick=\"CUA(this);\"></td></tr>\n";
	
	$total_data_length = 0;
	$total_index_length = 0;
	$total_total_length = 0;
	
	while ($row = $DB->fetch_array())
	{
		
		//Make sure it is a legit table
		if(in_array($conf['dbprefix'] . $row['Name'], $webcards_tables))
		{
		
		$total_length = 0; //Re-initialise the total table length to 0 for each table
		$total_length = $row['Data_length'] + $row['Index_length'];
		
		//Keep the totals up to date
		$total_data_length += $row['Data_length'];
		$total_index_length += $row['Index_length'];
		$total_total_length += $total_length;
		
		$html .= "<tr>\n";
		$html .= "<td class=\"ad_row\">" . $row['Name'] . "</td>";
		$html .= "<td class=\"ad_row\">" . $row['Rows'] . "</td>";
		$html .= "<td class=\"ad_row\">" . $row['Update_time'] . "</td>";
		$html .= "<td class=\"ad_row\">" . round_size($row['Data_length']) . "</td>";
		$html .= "<td class=\"ad_row\">" . round_size($row['Index_length']) . "</td>";
		$html .= "<td class=\"ad_row\">" . round_size($total_length) . "</td>";
		$html .= "<td align=\"center\" class=\"ad_row\"><input type=\"checkbox\" name=\"table[]\" value=\"" . $row['Name'] . "\"></td>";
		$html .= "</tr>";
		
		}
		
	}
	
	$html .= "<tr><td class=\"stats\" colspan=\"3\"><b>" . $lang['tb_total'] . "</b></td>";
	$html .= "<td class=\"stats\"><b>" . round_size($total_data_length) . "</b></td>";
	$html .= "<td class=\"stats\"><b>" . round_size($total_index_length) . "</b></td>";
	$html .= "<td colspan=\"2\" class=\"stats\"><b>" . round_size($total_total_length) . "</b></td></tr>";
	$html .= "</table>\n<br />\n\n";
	
	$html .= $lang['with_tables'] . " <select name=\"action\">\n<option value=\"check\">CHECK Table\n<option value=\"analyze\">ANALYZE Table\n<option value=\"optimize\">OPTIMIZE Table<option value=\"repair\">REPAIR Table\n</select> <input type=\"submit\" value=\"" . $lang['do_action'] . "\"></form>";
	
	return $html;
}

function round_size($size)
{
//Size must be given in bytes
global $lang;

	if ($size >= 1048576)
	{
		$size = round($size / 1048576 * 100 ) / 100 . $lang['megabytes'];
	}
	else if ($size >= 1024)
	{
		$size = round($size / 1024 * 100 ) / 100 . $lang['kilobytes'];
	}
	else
	{
		$size = $size . $lang['bytes'];
	}
	
	return $size;
}

function sql_tool()
{
global $HTTP_GET_VARS, $HTTP_POST_VARS, $DB, $conf, $lang;

$table_list = array();

	if(count($HTTP_POST_VARS['table']) < 1)
	{
		return error($lang['no_tables_selected'], $lang['back_choose_tables']);
	}

	//Which tables are we allowed to modify?
	$webcards_tables = array("admin", "categories", "email_logs", "images", "macro", "sent_cards");
	
	foreach($HTTP_POST_VARS['table'] as $table_name)
	{
		if(in_array($conf['dbprefix'] . $table_name, $webcards_tables))
		{
			$table_list[] = $conf['dbprefix'] . $table_name;
		}
	}

	//Check how many tables are selected
	if(count($table_list) < 1)
	{
		return error($lang['no_tables_selected'], $lang['back_choose_tables']);
	}
	
	//Check the user has chosen an acceptable action
	if($HTTP_POST_VARS['action'] == "analyze")
	{
		$sql = "ANALYZE TABLE";
	}
	else if($HTTP_POST_VARS['action'] == "optimize")
	{
		$sql = "OPTIMIZE TABLE";
	}
	else if($HTTP_POST_VARS['action'] == "repair")
	{
		$sql = "REPAIR TABLE";
	}
	else if($HTTP_POST_VARS['action'] == "check")
	{
		$sql = "CHECK TABLE";
	}
	else
	{
		return error($lang['bad_action']);
	}
	
	//Build the full sql query
	foreach($table_list as $k => $t)
	{
		if($k == "0")
		{
			$sql .= " `" . $t . "`";
		}
		else
		{
			$sql .= ", `" . $t . "`";
		}
	}
	
	if (!$DB->query($sql))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	
	$html = "<span class=\"title\">" . $lang['sql_tools'] . "</span><br /><br />\n";
	$html .= "<span style=\"color:red; font-weight:bold;\">" . strtoupper($HTTP_POST_VARS['action']) . "</span>" . $lang['op_on'];
	
	foreach($table_list as $k => $t)
	{
		if($k == "0")
		{
			$html .= " <b>" . $t . "</b>";
		}
		else
		{
			$html .= ", <b>" . $t . "</b>";
		}
	}
	
	$html .= "<br /><br />\n<table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">\n";
	$html .= "<tr>\n";
	$html .= "<td class=\"theader\"><b>" . $lang['tb_name'] . "</b></td>";
	$html .= "<td class=\"theader\"><b>" . $lang['tb_op'] . "</b></td>";
	$html .= "<td class=\"theader\"><b>" . $lang['tb_msg_type'] . "</b></td>";
	$html .= "<td class=\"theader\"><b>" . $lang['tb_status'] . "</b></td>";
	$html .= "</tr>";	
	
	while($body = $DB->fetch_row())
	{
		$html .= "<tr>";
		foreach($body as $data)
		{
			$html .= "<td class=\"ad_row\">" . $data . "</td>";
		}
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	
	return $html;


}

?>