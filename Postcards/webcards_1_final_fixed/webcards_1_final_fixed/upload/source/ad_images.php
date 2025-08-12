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
// $Id: ad_images.php,v 1.00 2005/07/18 20:04:18 chrisc Exp $

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
		$to_do = add_image();
		break;

		case 'doimages':
		$to_do = upload_image();
		break;

		case 'edit':
		$to_do = editimg();
		break;

		case 'doeditimg':
		$to_do = doeditimg();
		break;

		case 'showeditimg':
		$to_do = showeditimg();
		break;

		case 'finaleditimg':
		$to_do = finaleditimg();
		break;

		case 'delete':
		$to_do = deleteimg();
		break;

		case 'delete_all_images':
		$to_do = delete_all_images();
		break;

		case 'dodeleteimg':
		$to_do = dodeleteimg();
		break;

		case 'finaldeleteimg':
		$to_do = finaldeleteimg();
		break;
}

function add_image()
{
global $conf, $DB, $HTTP_GET_VARS, $lang;

	if ((bool) ini_get('file_uploads') == 0)
	{
		$file_uploads = "<br /><span class=\"warning\">" . $lang['warning'] . "</span> " . $lang['uploads_unav'] . "
				\n<input type=\"hidden\" name=\"uploads\" value=\"unav\">";
		$disabled = " disabled=\"disabled\"";
		$must_link = " selected=\"selected\"";
	}
	else
	{
		$file_uploads = "<input type=\"hidden\" name=\"uploads\" value=\"av\">";
		$disabled = "";
		$must_link = "";
	}
	$data = "<select name=\"cat\">\n";
	$data .= "<option value=\"\" selected>" . $lang['choose_cat'] . "\n";
	if (!$DB->query("SELECT id, title FROM " . $conf['dbprefix'] . "categories"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if ($DB->num_rows() <= 0)
	{
       	        return error($lang['no_cats_setup'], $lang['setup_some_cats']);
	}
	while($row = $DB->fetch_array())
	{
		$data .= "<option value=\"" . $row['id'] . "\">" . $row['title'] . "\n";
	}
	$data .= "</select>";
	$basic = parse_basic_admin_template("./templates/admin/admin_add_image.html");

	$output = preg_replace("/{{cat_select}}/i", $data, $basic);
	$output = preg_replace("/{{uploads_disabled}}/i", $file_uploads, $output);
	$output = preg_replace("/{{disabled}}/i", $disabled, $output);
	$output = preg_replace("/{{must_link}}/i", $must_link, $output);
	return $output;	
}


function upload_image()
{
global $HTTP_POST_FILES, $HTTP_POST_VARS, $conf, $DB, $lang;

$status = "<span class=\"title\">" . $lang['adding_img_results'] . "</span>\n<br /><br />\n";
	if ($HTTP_POST_VARS['uploads'] == "unav")
	{
		$HTTP_POST_VARS['image_choose'] = "link";
		$HTTP_POST_VARS['thumb_choose'] = "link";
	}
	if ($HTTP_POST_VARS['name'] == "")
	{
		return error($lang['no_img_name'], $lang['back_enter_img_name']);
	}
	if ($HTTP_POST_VARS['cat'] == "")
	{
		return error($lang['no_img_cat'], $lang['back_choose_cat']);
	}
	if ($HTTP_POST_VARS['image_choose'] == "none" || $HTTP_POST_VARS['image_choose'] == "")
	{
		return error($lang['no_choose_upload_or_link'], $lang['back_choose_link_or_upload']);
	}
	if ($HTTP_POST_VARS['thumb_choose'] == "none" || $HTTP_POST_VARS['thumb_choose'] == "")
	{
		return error($lang['no_thumb_details'], $lang['back_link_or_upload_thumb']);
	}
	//Return an error if the user links and uploads an image
	if ($HTTP_POST_VARS['link_image'] != "" && $HTTP_POST_FILES['upload_image']['size'] > 0)
	{
		return error($lang['upload_and_link']);
	}
	if ($HTTP_POST_VARS['link_thumb'] != "" && $HTTP_POST_FILES['upload_thumb']['size'] > 0)
	{
		return error($lang['upload_and_link_thumb']);
	}

	if ($HTTP_POST_VARS['image_choose'] == "upload")
	// Upload an image
	{
		$img_type = "upload";
		$status .= "<ul>\n";
		if ($HTTP_POST_FILES['upload_image']['size'] <= 0) // Check if file was uploaded
		{
			return error($lang['no_file_uploaded'], $lang['back_select_valid_file']);
		}
		else
		{
			$status .= "<li>" . $lang['found_image'] . "</li>";
		}
		if (!$macro = getMacro(getExt($HTTP_POST_FILES['upload_image']['name']), "name")) //  Check if there is a macro for the image
		{
			return error($lang['file_format'] . " <b>\"" . getExt($HTTP_POST_FILES['upload_image']['name']) . "\"</b> " . $lang['invalid_no_macro'], $lang['choose_other_image'] . "|" . $lang['create_valid_macro']);
		}
		else
		{
			$status .= "<li>" . $lang['format_allowed'] . $macro . "</li>";
		}
		if(!is_writeable($conf['dir'] . "images"))
		{
			return error($lang['dest_dir'] . " <b>" . $conf['dir'] . "images</b> " . $lang['not_writeabel'], $lang['check_perms_dir']);
		}
		else
		{
			$file_name_image = preg_replace("/\s/", "", $HTTP_POST_FILES['upload_image']['name']);
			$short_copy_path = "image-" . md5(uniqid(microtime())) . "-" . $file_name_image;
			$copy_path = $conf['dir'] . "images/" . $short_copy_path;
			if(!@move_uploaded_file($HTTP_POST_FILES['upload_image']['tmp_name'], $copy_path))
			{
				return error($lang['error_moving_file'], $lang['check_perms_dir']);
			}
			else
			{
				$status .= "<li>" . $lang['image_moved'] . "</li>";
			}
		}
		$status .= "<li><i>" . $lang['image_added'] . "</i></li></ul>\n";
	}
	if ($HTTP_POST_VARS['image_choose'] == "link")
	// Link an image
	{
		$img_type = "link";
		$status .= "<ul>\n";
		if ($HTTP_POST_VARS['link_image'] == "")
		{
			return error($lang['no_url_image'], $lang['back_enter_url_image']);
		}
		else
		{
			$status .= "<li>" . $lang['found_image'] . "</li>";
		}
		if (!preg_match("/(http:\/\/|ftp:\/\/|https:\/\/)/i", $HTTP_POST_VARS['link_image']))
		{
			return error($lang['invalid_url_image'], $lang['back_full_url_image']);
		}
		else
		{
			$status .= "<li>" . $lang['link_url_valid'] . "</li>";
		}
		$status .= "<li>" . $lang['access_link_image'] . "</li>";
		$copy_path = $HTTP_POST_VARS['link_image'];
		$status .= "<li><i>" . $lang['image_success'] . "</i></li></ul>";
	}

	// Now perform actions for the thumbnail

	if ($HTTP_POST_VARS['thumb_choose'] == "upload")
	// Upload a thumbnail
	{
		$thumb_type = "upload";
		$status .= "<ul>";
		if ($HTTP_POST_FILES['upload_thumb']['size'] <= 0) // Check if file was uploaded
		{
			return error($lang['no_thumb_up'], $lang['select_valid_thumb']);
		}
		else
		{
			$status .= "<li>" . $lang['found_thumb'] . "</li>";
		}

		if (!getMacro(getExt($HTTP_POST_FILES['upload_thumb']['name']), "id")) //  Check if there is a macro for the thumbnail image
		{
			if ($HTTP_POST_VARS['image_choose'] == "upload")
			{
				@unlink($copy_path);
			}
			return error($lang['file_format'] . " <b>\"" . getExt($HTTP_POST_FILES['upload_thumb']['name']) . "\"</b> " . $lang['invalid_no_macro'], $lang['choose_other_image'] . "|" . $lang['create_valid_macro']);
		}
		else
		{
			$status .= "<li>" . $lang['thumb_format_ok'] . "</li>";
		}
		if(!is_writeable($conf['dir'] . "images/thumbs"))
		{
			if ($HTTP_POST_VARS['image_choose'] == "upload")
			{
				@unlink($copy_path);
			}
			return error($lang['dest_dir'] . " <b>" . $conf['dir'] . "images/thumbs</b> " . $lang['not_writeable'], $lang['check_perms_dir']);
		}
		else
		{
			$file_name_thumb = preg_replace("/\s/", "", $HTTP_POST_FILES['upload_thumb']['name']);
			$short_copy_path_thumb = "thumb-" . md5(uniqid(microtime())) . "-" . $file_name_thumb;
			$copy_path_thumb = $conf['dir'] . "images/thumbs/" . $short_copy_path_thumb;
			if(!@move_uploaded_file($HTTP_POST_FILES['upload_thumb']['tmp_name'], $copy_path_thumb))
			{
				if ($HTTP_POST_VARS['image_choose'] == "upload")
				{
					@unlink($copy_path);
				}
				return error($lang['error_move_thumb'], $lang['check_perms_dir']);
			}
			else
			{
				$status .= "<li>" . $lang['thumb_moved_ok'] . "</li>";
			}
		}
		$status .= "<li><i>" . $lang['thumb_success'] . "</i></li></ul>\n";
	}
	else if ($HTTP_POST_VARS['thumb_choose'] == "link")
	// Link a thumbnail
	{
		$thumb_type = "link";
		$status .= "<ul>";
		if ($HTTP_POST_VARS['link_thumb'] == "")
		{
			if ($HTTP_POST_VARS['image_choose'] == "upload")
			{
				@unlink($copy_path);
			}
			return error($lang['link_thumb_no_url'], $lang['enter_url_thumb']);
		}
		if (!preg_match("/(http:\/\/|ftp:\/\/|https:\/\/)/i", $HTTP_POST_VARS['link_thumb']))
		{
			if ($HTTP_POST_VARS['image_choose'] == "upload") //If we cannot link the thumbnail then delete the uploaded image
			{
				@unlink($copy_path);
			}
			return error($lang['invalid_url_thumb'], $lang['enter_full_url_thumb']);
		}
		else
		{
			$status .= "<li>" . $lang['link_url_valid'] . "</li>";
		}
		$status .= "<li>" . $lang['access_link_image'] . "</li>";
		$copy_path_thumb = $HTTP_POST_VARS['link_thumb'];
		$status .= "<li><i>" . $lang['thumb_success'] ."</i></li></ul>";
	}
	
	//Auto generate a thumbnail
	else if ($HTTP_POST_VARS['thumb_choose'] == "gen_thumb")
	{
		require_once("./source/modules/thumb.php");
		
		//Generate the filename
		$filename_short = "thumb-" . md5(uniqid(microtime())) . "." . $conf['thumb_format'];
		$filename_long = $conf['dir'] . "images/thumbs/" . $filename_short;
		
		if(!generate_thumb($copy_path, $filename_long, $conf['thumb_width'], $conf['thumb_height'], $conf['thumb_format']))
		{
			if ($HTTP_POST_VARS['image_choose'] == "upload")
			{
				@unlink($copy_path);
			}
			return error($lang['cannot_gen_thumb'], $lang['check_thumb_settings']);
		}
		else
		{
			//Ensure the image does really exist
			if(!file_exists($filename_long))
			{
				return error($lang['cannot_gen_thumb'], $lang['check_thumb_settings']);
			}
			
			$thumb_type = "upload"; //Thumb type isn't really upload but the display method is the same as with uploaded images
			
			$status .= "<ul>";
			$status .= "<li>" . $lang['thumb_gen_ok'] . "</li>";
			$status .= "<li><i>" . $lang['thumb_success'] ."</i></li></ul>";
		}
	}

	$db_path_img = $img_type=="upload" ? $short_copy_path : $HTTP_POST_VARS['link_image'];
	$db_path_thumb = $thumb_type=="upload" ? $short_copy_path_thumb : $HTTP_POST_VARS['link_thumb'];
	
	if($HTTP_POST_VARS['thumb_choose'] == "gen_thumb")
	{
		$db_path_thumb = $filename_short;
	}
	
	//For a bit of security make sure the (optional) dimensions are integers
	$width = (int) $HTTP_POST_VARS['width'];
	$height = (int) $HTTP_POST_VARS['height'];

	$thumb_width = (int) $HTTP_POST_VARS['thumb_width'];
	$thumb_height = (int) $HTTP_POST_VARS['thumb_height'];
	
	if (!$DB->query("INSERT INTO `" . $conf['dbprefix'] . "images` (id, cat, img_type, thumb_type, url, thumb, name, width, height, width_thumb, height_thumb) VALUES ('', '$HTTP_POST_VARS[cat]', '$img_type', '$thumb_type', '$db_path_img', '$db_path_thumb', '$HTTP_POST_VARS[name]', '$width', '$height', '$thumb_width', '$thumb_height')"))
	{
		if ($HTTP_POST_VARS['image_choose'] == "upload")
		{
			@unlink($copy_path);
		}
		if ($HTTP_POST_VARS['thumb_choose'] == "upload" || $HTTP_POST_VARS['thumb_choose'] == "gen_thumb")
		{
			@unlink($copy_path_thumb);
		}
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	/*
	else
	{
		$status .= "<b>" . $lang['image_added'] . "</b> [ <a href=\"javascript:popImage('" . $DB->next_value() . "', 'url', '" . $HTTP_GET_VARS['s'] . "');\">" . $lang['view_thumb']";
	}
	*/
	$basic = parse_basic_admin_template("./templates/admin/admin_image_uploaded.html");
	$output = preg_replace("/{{upload_results}}/i", $status, $basic);
	$output = preg_replace("/{{insert_id}}/i", $DB->next_value(), $output);
	return $output;
}


function editimg()
{
global $conf, $DB, $lang;

	$data = "<select name=\"cat\">\n";
	$data .= "<option value=\"\" selected>Choose a category\n";

	if (!$DB->query("SELECT id, name FROM `" . $conf['dbprefix'] . "images`"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if ($DB->num_rows() <= 0)
	{
		return error($lang['img_db_empty'], $lang['add_db_images']);
	}
	if (!$DB->query("SELECT id, title FROM " . $conf['dbprefix'] . "categories"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	while($row = $DB->fetch_array())
	{
	$data .= "<option value=\"" . $row['id'] . "\">" . $row['title'] . "\n";
	}
	$data .= "</select>";
	$basic = parse_basic_admin_template("./templates/admin/admin_edit_img.html");
	$output = preg_replace("/{{edit_data}}/i", $data, $basic);
	return $output;	
}


function doeditimg()
{
global $conf, $HTTP_POST_VARS, $HTTP_GET_VARS, $DB, $lang;

	if (!isset($HTTP_POST_VARS['type'])) //User did not select a way to choose the image
	{
		return error($lang['no_action'], $lang['back_choose_action']);
	}
	if ($HTTP_POST_VARS['type'] == "list_cat")
	{
		if ($HTTP_POST_VARS['cat'] == "")
		{
			return error($lang['no_cat_specif'], $lang['back_choose_cat']);
		}
		$sql = "SELECT id, name, url FROM `" . $conf['dbprefix'] . "images` WHERE cat=\"" . $HTTP_POST_VARS['cat'] . "\" ORDER by name ASC";
	}
	if ($HTTP_POST_VARS['type'] == "search")
	{
		if ($HTTP_POST_VARS['terms'] == "")
		{
			return error($lang['no_search_terms'], $lang['back_enter_terms']);
		}
		$sql = "SELECT id, name, url FROM `" . $conf['dbprefix'] . "images` WHERE name LIKE \"%" . $HTTP_POST_VARS['terms'] . "%\" ORDER by name ASC";
	}
	if ($HTTP_POST_VARS['type'] == "list_all")
	{
		$sql = "SELECT id, name, url FROM `" . $conf['dbprefix'] . "images` ORDER by name ASC";
	}
	if (!$DB->query($sql))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if ($DB->num_rows() <= 0)
	{
		$output = $lang['no_img_found'] . "<br /><br />";
	}
	else
	{
		$output = "";
		while($row=$DB->fetch_array())
		{
			$output .= "<tr><td class=\"ad_row\"><input class=\"clear\" type=\"radio\" name=\"id\" value=\"" . $row['id'] . "\" id=\"" . $row['id'] . "\"> <label for=\"" . $row['id'] . "\">" . $row['name'] . "</label> [ <a href=\"javascript:popImage('" . $row['id'] . "', 'url', '" . $HTTP_GET_VARS['s'] . "');\">" . $lang['view_img'] . "</a> | <a href=\"javascript:popImage('" . $row['id'] . "', 'thumb', '" . $HTTP_GET_VARS['s'] . "');\">" . $lang['view_thumb'] . "</a> ]</td></tr>";
		}
	}
	$basic = parse_basic_admin_template("./templates/admin/admin_edit_img_results.html");
	$output = preg_replace("/{{content}}/i", $output, $basic);
	return $output;
}


function showeditimg($msg="")
{
global $conf, $HTTP_POST_VARS, $DB, $lang;

	if ($HTTP_POST_VARS['id'] == "")
	{
		return error($lang['no_img_to_edit'], $lang['back_choose_img']);
	}
	if (!$DB->query("SELECT id, cat, thumb, name, width, height, width_thumb, height_thumb FROM `" . $conf[dbprefix] . "images` WHERE id=$HTTP_POST_VARS[id]"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	$row = $DB->fetch_array();
	
	if($msg !="")
	{
		$msg = "<tr>\n<td colspan=\"2\" class=\"theader\">" . $msg . "</td></tr>";
	}
	$basic = parse_basic_admin_template("./templates/admin/admin_do_edit_img.html");
	$data = preg_replace("/{{img_id}}/i", $row['id'], $basic);
	$data = preg_replace("/{{img_name}}/i", $row['name'], $data);
	$data = preg_replace("/{{width}}/i", $row['width'], $data);
	$data = preg_replace("/{{height}}/i", $row['height'], $data);
	$data = preg_replace("/{{width_thumb}}/i", $row['width_thumb'], $data);
	$data = preg_replace("/{{height_thumb}}/i", $row['height_thumb'], $data);
	$data = preg_replace("/{{msg}}/i", $msg, $data);
	define ("CURRENTID", $row[cat]);
	$select .= "<select name=\"cat\">\n";
	if (!$DB->query("SELECT id, title FROM " . $conf[dbprefix] . "categories"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	while($cat = $DB->fetch_array())
	{
	$select .= "<option value=\"" . $cat['id'] . "\"";
	if (CURRENTID == "$cat[id]") { $select .= " selected"; }
	$select .= ">" . $cat[title] . "\n";
	}
	$select .= "</select>";
	$data = preg_replace("/{{img_cat_select}}/i", $select, $data);
	return $data;
}


function finaleditimg()
{
global $conf, $HTTP_POST_VARS, $DB, $lang;

	if($HTTP_POST_VARS[name] =="")
	{
		return error($lang['no_img_name'], $lang['back_enter_img_name']);
	}

	if (!$DB->query("UPDATE `" . $conf[dbprefix] . "images` SET name='$HTTP_POST_VARS[name]', cat='$HTTP_POST_VARS[cat]', width='$HTTP_POST_VARS[width]', height='$HTTP_POST_VARS[height]', width_thumb='$HTTP_POST_VARS[width_thumb]', height_thumb='$HTTP_POST_VARS[height_thumb]' WHERE id=$HTTP_POST_VARS[id]"))
	{
			return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	else
	{
		return showeditimg($lang['img_edit_success']);
	}
}


function deleteimg()
{
global $conf, $DB, $lang;

	$data = "<select name=\"cat\">\n";
	$data .= "<option value=\"\" selected>" . $lang['choose_cat'] . "\n";

	if (!$DB->query("SELECT id, name FROM `" . $conf['dbprefix'] . "images`"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if ($DB->num_rows() <= 0)
	{
		return error($lang['img_db_empty'], $lang['add_db_images']);
	}
	if (!$DB->query("SELECT id, title FROM `" . $conf['dbprefix'] . "categories`"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	while($row = $DB->fetch_array())
	{
		$data .= "<option value=\"" . $row['id'] . "\">" . $row['title'] . "\n";
	}

	$data .= "</select>";
	$basic = parse_basic_admin_template("./templates/admin/admin_delete_img.html");
	$output = preg_replace("/{{edit_data}}/i", $data, $basic);
	return $output;	
}


function delete_all_images()
{
global $conf, $DB, $lang, $HTTP_GET_VARS, $HTTP_POST_VARS;

	//Make sure they have entered the correct admin password for confirmation
	if (!$DB->query("SELECT password FROM `" . $conf['dbprefix'] . "admin` WHERE session=\"" . $HTTP_GET_VARS['s'] . "\""))
	{
		die(error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']));
	}
	$row = $DB->fetch_array();
	if($row['password'] != md5($HTTP_POST_VARS['password']))
	{
		return error($lang['bad_password']);
	}

	if (!$DB->query("DELETE FROM `" . $conf[dbprefix] . "images`"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	
	$delete_count = 0;
	$delete_array = array();
	$dp = opendir($conf['dir'] . "images");
	if(!$dp)
	{
		return error($lang['error_open_img_dir'], $lang['check_perms_dir']);
	}
	while($file = readdir($dp))
	{
		if(!is_dir($conf['dir']."images/".$file) && $file != "index.html")
		{
			if(@unlink($conf['dir']."images/".$file))
			{
				$delete_count++;
				$delete_array[] = $file;
			}
		}
	}

	$delete_count_thumb = 0;
	$delete_array_thumb = array();
	$dp = opendir($conf['dir'] . "images/thumbs");
	if(!$dp)
	{
		return error($lang['error_open_thumb_dir'], $lang['check_perms_dir']);
	}
	while($file = readdir($dp))
	{
		if(!is_dir($conf['dir']."images/thumbs/".$file) && $file != "index.html")
		{
			if(@unlink($conf['dir']."images/thumbs/".$file))
			{
				$delete_count_thumb++;
				$delete_array_thumb[] = $file;
			}
		}
	}
	$output .= "<span class=\"title\">" . $lang['img_deletion'] . "</span><br /><br />\n" . $lang['success_del'] . $delete_count . $lang['img_and'] .  $delete_count_thumb . $lang['thumbs'];
	$output .= "<br /><br />" . $lang['files_deleted'] . "<br /><br /><b>Main Images</b><br />";
	foreach($delete_array as $f)
	{
		$output .= $f . "<br />";
	}
		$output .= "<br /><b>Thumbnails</b><br />";
	foreach($delete_array_thumb as $f)
	{
		$output .= $f . "<br />";
	}
	return $output;
}



function dodeleteimg()
{
global $conf, $HTTP_POST_VARS, $HTTP_GET_VARS, $DB, $lang;

	if (!isset($HTTP_POST_VARS['type']))
	{
		return error($lang['no_action'], $lang['back_choose_action']);
	}
	if ($HTTP_POST_VARS['type'] == "list_cat")
	{
		if ($HTTP_POST_VARS['cat'] == "")
		{
			return error($lang['no_cat_specif'], $lang['back_choose_cat']);
		}
		$sql = "SELECT id, name, url FROM `" . $conf['dbprefix'] . "images` WHERE cat=\"" . $HTTP_POST_VARS['cat'] . "\" ORDER BY name ASC";

	}
	if ($HTTP_POST_VARS['type'] == "search")
	{
		if ($HTTP_POST_VARS['terms'] == "")
		{
			return error($lang['no_search_terms'], $lang['back_enter_terms']);
		}
		$sql = "SELECT id, name, url FROM `" . $conf['dbprefix'] . "images` WHERE name LIKE \"%" . $HTTP_POST_VARS['terms'] . "%\" ORDER BY name ASC";
	}
	if ($HTTP_POST_VARS['type'] == "list_all")
	{
		$sql = "SELECT id, name, url FROM `" . $conf['dbprefix'] . "images` ORDER BY name ASC";
	}
	if ($HTTP_POST_VARS['type'] == "del_all")
	{
		return parse_basic_admin_template("./templates/admin/admin_confirm_delete_all_images.html");
	}
	if (!$DB->query($sql))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if ($DB->num_rows() <= 0)
	{
		$output = "<b>" . $lang['no_img_found'] . "</b><br /><br />";
	}
	else
	{
		while($row=$DB->fetch_array())
		{
			$output .= "<tr><td class=\"ad_row\"><input class=\"clear\" type=\"checkbox\" name=\"to_delete[]\" value=\"" . $row['id'] . "\" id=\"" . $row['id'] . "\"> <label for=\"" . $row['id'] . "\">" . $row['name'] . "</label> [ <a href=\"javascript:popImage('" . $row['id'] . "', 'url', '" . $HTTP_GET_VARS['s'] . "');\">" . $lang['view_img'] . "</a> | <a href=\"javascript:popImage('" . $row['id'] . "', 'thumb', '" . $HTTP_GET_VARS['s'] . "');\">" . $lang['view_thumb'] . "</a> ]</td></tr>\n\n";
		}
	}
	$basic = parse_basic_admin_template("./templates/admin/admin_delete_img_results.html");
	$output = preg_replace("/{{content}}/i", $output, $basic);
	return $output;
}


function finaldeleteimg()
{
global $HTTP_POST_VARS, $conf, $DB, $lang;

$img_count = 0;
$thumb_count = 0;
$drop_count = 0;
$drop_array = array();

	if(!isset($HTTP_POST_VARS['to_delete']) || count($HTTP_POST_VARS['to_delete']) <= 0)
	{
		return error($lang['no_img_del_specif'], $lang['back_specif_img_del']);
	}
	if (!$DB->query("SELECT id, url, thumb, img_type, thumb_type FROM `" . $conf['dbprefix'] . "images`"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	while($row = $DB->fetch_array())
	{
		if(in_array($row['id'], $HTTP_POST_VARS['to_delete']))
		{
			if($row['img_type'] == "upload")
			{
				if(@unlink($conf['dir'] . "images/" . $row['url']))
				{
					$img_count++;
				}
			}
			if($row['thumb_type'] == "upload")
			{
				if(@unlink($conf['dir'] . "images/thumbs/" . $row['thumb']))
				{
					$thumb_count++;
				}
			}
			$drop_array[] = $row['id'];
		}
	}
	$drop_query =  "DELETE FROM `" . $conf['dbprefix'] . "images` WHERE id=";
	foreach($drop_array as $key => $val)
	{
		if ($key + 1 == count($drop_array))
		{
			$drop_query .= "\"" . $val . "\"";
		}
		else
		{
			$drop_query .= "\"" . $val . "\" or id=";
		}
	}
	if (!$DB->query($drop_query))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	return "<span class=\"title\">" . $lang['img_deletion'] . "</span><br /><br />\n<table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\"><tr><td class=\"theader\">" . $lang['del_results'] . "</td></tr><tr><td class=\"ad_row\">" . $lang['success_del'] . " " . $img_count . $lang['img_and'] . $thumb_count . " " . $lang['thumbs'] . "<br />" . $lang['db_del'] . $DB->affected() . "</td></tr></table>";
}

?>