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
// $Id: ad_category.php,v 1.00 2003/09/09 20:04:06 chrisc Exp $

/*-----------------------------------------------
  ENSURE THE SCRIPT IS NOT BEING ACCESSED DIRECTLY
 ------------------------------------------------*/
if(!defined("LOADED"))
{
	die("Cannot access the script directly");
}

switch($HTTP_GET_VARS['what'])
{
		case 'add':
		$to_do = parse_basic_admin_template("./templates/admin/admin_add_cat.html");
		break;

		case 'docat':
		$to_do = add_cat();
		break;

		case 'edit':
		$to_do = editcat();
		break;

		case 'doeditcat':
		$to_do = doeditcat();
		break;

		case 'finaleditcat':
		$to_do = finaleditcat();
		break;

		case 'delete':
		$to_do = deletecat();
		break;

		case 'dodeletecat':
		$to_do = dodeletecat();
		break;

		case 'finaldeletecat':
		$to_do = finaldeletecat();
		break;

		case 'viewcat':
		$to_do = viewcat();
		break;
}

function add_cat()
{
global $HTTP_POST_VARS, $conf, $DB, $lang;

	if ($HTTP_POST_VARS['name'] == "")
	{
		return error($lang['no_cat_name'], $lang['back_choose_cat_name']);
	}
	if ($HTTP_POST_VARS['description'] == "")
	{
		return error($lang['no_cat_desc'], $lang['back_add_desc']);
	}
	
	//Remove <script> tags even though an admin is doing this - just for safety
	$desc = preg_replace("/<script/i", "&#60;script", $HTTP_POST_VARS['description']);
	
	if (!$DB->query("INSERT INTO " . $conf['dbprefix'] . "categories (id, title, description) VALUES ('', \"" . $HTTP_POST_VARS['name'] . "\", \"" . $desc . "\")"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	else
	{
		return "Category was added successfully";
	}
}

function editcat()
{
global $conf, $DB, $lang;
	$data = "<select name=\"id\">\n";
	$data .= "<option value=\"\" selected>Choose a category\n";

	if (!$DB->query("SELECT id, title FROM " . $conf['dbprefix'] . "categories"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if ($DB->num_rows() <= 0)
	{
		return error($lang['no_cats_setup'], $lang['setup_cats']);
	}
	while($row = $DB->fetch_array())
	{
	$data .= "<option value=\"" . $row['id'] . "\">" . $row['title'] . "\n";
	}
	$data .= "</select>";
	$basic = parse_basic_admin_template("./templates/admin/admin_edit_cat.html");
	$output = preg_replace("/{{edit_data}}/i", $data, $basic);
	return $output;	
}

function doeditcat($msg="")
{
global $conf, $HTTP_POST_VARS, $DB, $lang;

	if ($HTTP_POST_VARS['id'] == "")
	{
		return error($lang['no_cat_edit_selected'], $lang['back_choose_cat_edit']);
	}
	if (!$DB->query("SELECT id, title, description FROM `" . $conf['dbprefix'] . "categories` WHERE id=\"" . $HTTP_POST_VARS['id'] . "\""))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	$row = $DB->fetch_array();
	
	if($msg !="")
	{
		$msg = "<tr>\n<td colspan=\"2\" class=\"theader\">" . $msg . "</td></tr>";
	}
	
	$basic = parse_basic_admin_template("./templates/admin/admin_do_edit_cat.html");
	$data = preg_replace("/{{cat_id}}/i", $row['id'], $basic);
	$data = preg_replace("/{{cat_name}}/i", $row['title'], $data);
	$data = preg_replace("/{{cat_description}}/i", $row['description'], $data);
	$data = preg_replace("/{{msg}}/i", $msg, $data);
	return $data;
}

function finaleditcat()
{
global $conf, $HTTP_POST_VARS, $DB, $lang;

	if($HTTP_POST_VARS['title'] == "")
	{
		return error($lang['no_cat_title'], $lang['back_choose_cat_title']);
	}
	if($HTTP_POST_VARS['description'] == "")
	{
		return error($lang['no_cat_desc'], $lang['back_add_desc']);
	}
	
	//Remove <script> tags even though an admin is doing this - just for safety
	$desc = preg_replace("/<script/i", "&#60;script", $HTTP_POST_VARS['description']);
	
	if (!$DB->query("UPDATE " . $conf['dbprefix'] . "categories SET title=\"" . $HTTP_POST_VARS['title'] . "\", description=\"" .$desc . "\" WHERE id=\"" . $HTTP_POST_VARS['id'] . "\""))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	return doeditcat($lang['cat_edited']);
}

function deletecat()
{
global $conf, $DB, $lang;

	$data = "<select name=\"id\">\n";
	$data .= "<option value=\"\" selected>" . $lang['choose_cat'] . "\n";
	if (!$DB->query("SELECT id, title FROM " . $conf['dbprefix'] . "categories"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if ($DB->num_rows() <= 0)
	{
		return error($lang['no_cats_setup'], $lang['setup_cats']);
	}
	while($row = $DB->fetch_array())
	{
	$data .= "<option value=\"" . $row['id'] . "\">" . $row['title'] . "\n";
	}
	$data .= "</select>";
	$basic = parse_basic_admin_template("./templates/admin/admin_delete_cat.html");
	$output = preg_replace("/{{delete_data}}/i", $data, $basic);
	return $output;	
}

function dodeletecat()
{
global $conf, $HTTP_POST_VARS, $DB, $lang;

	if ($HTTP_POST_VARS['id'] == "")
	{
		return error($lang['no_cat_del_selected'], $lang['back_sel_cat_del']);
	}

	if (!$DB->query("SELECT id, title FROM " . $conf['dbprefix'] . "categories"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	while($row = $DB->fetch_array())
	{
		if($row['id'] == $HTTP_POST_VARS['id'])
		{
			$cat_name = $row['title'];
		}
		if($row['id'] != $HTTP_POST_VARS['id'])
		{
			$select_list .= "<option value=\"" . $row['id'] . "\">" . $row['title'] . "\n";
		}
	}
	$basic = parse_basic_admin_template("./templates/admin/admin_do_delete_cat.html");
	$data = preg_replace("/{{cat_name}}/i", $cat_name, $basic);
	$data = preg_replace("/{{cat_num}}/i", $HTTP_POST_VARS['id'], $data);

	$row_count = $DB->row_count("SELECT count(id) FROM `" . $conf['dbprefix'] . "images` WHERE cat='$HTTP_POST_VARS[id]'");

	$data = preg_replace("/{{num_images}}/i", $row_count, $data);
	if($row_count <= 0)
	{
		$count_data = "<tr><td class=\"ad_row\" colspan=\"2\">" . $lang['cat_contains'] . " <b>0</b> " . $lang['img_safe_del'] . "</td></tr>";
	}
	else
	{
		$count_data = "<tr><td class=\"ad_row\" colspan=\"2\">" . $lang['cat_contains'] . " <b>" . $row_count . "</b> " . $lang['img_decide_before_del_cat'] . "</td></tr>\n";
		$count_data .= "<tr><td class=\"ad_row\" colspan=\"2\"><input class=\"clear\" type=\"radio\" name=\"to_do\" value=\"delete\" /> " . $lang['del_all_img'] . "</td></tr>\n";
		$count_data .= "<tr><td class=\"ad_row\" width=\"50%\"><input class=\"clear\" type=\"radio\" name=\"to_do\" value=\"move\" /> " . $lang['move_img_diff_cat'] . ": </td>\n";
		$count_data .= "<td class=\"ad_row\" width=\"50%\"><select name=\"cat_to_move\" onchange=\"javascript:window.document.del_form.to_do[1].checked='true';\">\n";
		$count_data .= "<option value=\"\" selected>" . $lang['choose_cat'] . "\n";
		$count_data .= $select_list;
		$count_data .= "</select></td></tr>";
	}
	$data = preg_replace("/{{delete_data}}/i", $count_data, $data);
	return $data;
}

function finaldeletecat()
{
global $conf, $HTTP_POST_VARS, $DB, $lang;

	$output = "<span class=\"title\">" . $lang['del_cat'] . "</span><br /><br />\n";
	$output .= "<table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">\n";
	$output .= "<tr><td class=\"theader\">" . $lang['del_results'] . "</td></tr>\n";
	$output .= "<tr><td class=\"ad_row\">\n";
	if ($HTTP_POST_VARS['num_images'] > 0 && !isset($HTTP_POST_VARS['to_do']))
	{
		return error($lang['not_chosen_img_option'], $lang['choose_img_action']);
	}
	if ($HTTP_POST_VARS['num_images'] > 0 && isset($HTTP_POST_VARS['to_do']) && $HTTP_POST_VARS['to_do'] == "move" && $HTTP_POST_VARS['cat_to_move'] == "")
	{
		return error($lang['chosen_move_img_no_cat'], $lang['back_choose_move_cat']);
	}
	if ($HTTP_POST_VARS['cat'] == "")
	{
		return error($lang['no_choose_cat_del'], $lang['back_choose_cat_del']);
	}
	if($HTTP_POST_VARS['num_images'] <= 0)
	{
		if (!$DB->query("DELETE FROM " . $conf['dbprefix'] . "categories WHERE id=\"" . $HTTP_POST_VARS['cat'] . "\""))
		{
			return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
		}
		else
		{
			return $lang['cat_deleted'];
		}
	}
	else
	{
		if(isset($HTTP_POST_VARS['to_do']) && $HTTP_POST_VARS['to_do'] == "delete")
		{
			if (!$DB->query("SELECT url, thumb FROM " . $conf['dbprefix'] . "images WHERE cat=\"" . $HTTP_POST_VARS['cat'] . "\""))
			{
				return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
			}
			while($row = $DB->fetch_array())
			{
				$delete_img = @unlink($conf['dir'] . "images/" . $row['url']);
				if ($delete_img)
				{
					$output .= $lang['success_del'] . ": " . $row['url'] . "<br />";
				}
				$delete_thumb = @unlink($conf['dir'] . "images/thumbs/" . $row['thumb']);
				if ($delete_thumb)
				{
					$output .= $lang['success_del'] . ": " . $row['thumb'] . "<br />";
				}
			}
			if (!$DB->query("DELETE FROM " . $conf['dbprefix'] . "images WHERE cat=\"" . $HTTP_POST_VARS['cat'] . "\""))
			{
				return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
			}
			else
			{
				$output .= "<br />" . $lang['img_removed_db'];
			}
			if (!$DB->query("DELETE FROM " . $conf['dbprefix'] . "categories WHERE id=\"" . $HTTP_POST_VARS['cat'] . "\""))
			{
				return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
			}
			else
			{
				$output .= "<br /><br />" . $lang['category_del'];
			}
		}
		if(isset($HTTP_POST_VARS['to_do']) && $HTTP_POST_VARS['to_do'] == "move")
		{
			if (!$DB->query("DELETE FROM " . $conf['dbprefix'] . "categories WHERE id=\"" . $HTTP_POST_VARS['cat'] . "\""))
			{
				return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
			}
			else
			{
				$output .= $lang['cat_remove_success'] . "<br /><br />";
			}
			if (!$DB->query("UPDATE " . $conf['dbprefix'] . "images SET cat=\"" . $HTTP_POST_VARS['cat_to_move'] . "\" WHERE cat=\"" . $HTTP_POST_VARS['cat'] . "\""))
			{
				return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
			}
			else
			{
				$output .= $lang['img_success_moved'];
			}
		}
	}
	$output .= "</td></tr></table>";
	
	return $output;
}

function viewcat()
{
global $conf, $DB, $lang;

	$count = array();
	if (!$DB->query("SELECT cat FROM " . $conf['dbprefix'] . "images"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	while($num_row = $DB->fetch_array())
	{
		$count[$num_row['cat']]++;
	}
	foreach($count as $keys => $values)
	{
		$num_count_ . $keys = $values;
	}


	if (!$DB->query("SELECT * FROM " . $conf['dbprefix'] . "categories"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if($DB->num_rows() <= 0)
	{
		return $lang['no_cats_setup'];
	}
	$output = "<span class=\"title\">" . $lang['cat_stats'] . "</span><br /><br />\n\n<table border=\"0\" width=\"100%\">\n<tr><td><b>" . $lang['id'] . "</b></td><td><b>" . $lang['cat_name'] . "</b></td><td><b>" . $lang['cat_desc'] . "</b></td><td><b>" . $lang['cat_img'] . "</b></td></tr>\n";
	while($row = $DB->fetch_array())
	{
		$output .= "<tr><td>" . $row['id'] . "</td><td>" . $row['title'] . "</td><td>" . $row['description'] . "</td><td>" . $num_count_ . $row['id'] . "</td></tr>\n";
	}

	$output .= "</table>";
	return $output;
}