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
// $Id: ad_template.php,v 1.00 2004/06/08 18:11:36 chrisc Exp $

/*-----------------------------------------------
  ENSURE THE SCRIPT IS NOT BEING ACCESSED DIRECTLY
 ------------------------------------------------*/
if(!defined("LOADED"))
{
	die("Cannot access the script directly");
}

switch($HTTP_GET_VARS['what'])
{
		case 'template':
		$to_do = render_template();
		break;

		case 'dotemplate':
		$to_do = do_template();
		break;

		case 'template_editor':
		$to_do = template_editor();
		break;

		case 'do_template_editor':
		$to_do = do_template_editor();
		break;

		case 'save_template':
		$to_do = save_template();
		break;

		case 'css':
		$to_do = list_css();
		break;
		
		case 'new_css':
		$to_do = new_css();
		break;
		
		case 'open_css':
		$to_do = render_css();
		break;
		
		case 'docss':
		$to_do = do_css();
		break;
		
		case 'del_css':
		$to_do = del_css();
		break;

		case 'image_macros':
		$to_do = show_macros();
		break;

		case 'edit_macro':
		$to_do = edit_macro();
		break;

		case 'save_image_macro':
		$to_do = save_image_macro("update");
		break;

		case 'delete_macro':
		$to_do = delete_macro();
		break;

		case 'new_macro':
		$to_do = parse_basic_admin_template("./templates/admin/admin_new_macro.html");
		break;

		case 'save_new_macro':
		$to_do = save_image_macro("insert");
		break;
		
		default:
		echo "bad system call";
		break;
}

function render_template($msg="")
{
global $conf, $lang;

	$basic = parse_basic_admin_template("./templates/admin/admin_edit_template.html");
	if (!$fp = @fopen($conf['dir'] . "./templates/template.html", "r"))
	{
		return error($lang['cannot_open_template'], $lang['check_file_exists'] . "|" . $lang['check_file_perms']);
	}
	
	if($msg != "")
	{
		$msg = "<tr><td class=\"theader\">" . $msg . "</td></tr>";
	}
	
	$contents = fread($fp, filesize($conf['dir'] . "./templates/template.html"));
	$output = preg_replace("/{{template_content}}/i", $contents, $basic);
	$output = preg_replace("/{{msg}}/i", $msg, $output);

	return $output;
}

function do_template()
{
global $HTTP_POST_VARS, $conf, $lang;

	if(!preg_match("/{{content}}/i", $HTTP_POST_VARS['template_data']))
	{
		output(error($lang['template_var_removed']));
	}
	if ($fp = @fopen($conf[dir] . "./templates/template.html", "w"))
	{
		$to_put = $HTTP_POST_VARS['template_data'];
		$to_put = stripslashes($to_put);
		@fwrite($fp, $to_put, strlen($to_put));
		@fclose($fp);
		$output = $lang['main_template_updated'];
	}
	else
	{
		return error($lang['cannot_open_template'], $lang['check_file_exists'] . "|" . $lang['check_file_perms']);
	}
	return render_template($output);
}

function list_css($msg="")
{
global $conf, $lang, $HTTP_GET_VARS;
//This function lists all the available stylesheets for us to edit


	$dp = opendir($conf['dir'] . "./templates/styles");
	if(!$dp)
	{
		return error($lang['no_open_styles_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms']);
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!="..")
		{
			$css_list .= "<tr>\n<td class=\"ad_row\">" . $file . " [ <a href=\"" . $conf['admin_script'] . "?act=template&amp;what=open_css&amp;file=" . $file . "&amp;s=" . $HTTP_GET_VARS['s'] . "\">" . $lang['edit'] . "</a> ] [ <a href=\"" . $conf['script'] . "?preview_style=" . $file . "\" target=\"_blank\">" . $lang['preview'] . "</a> ] [ <a href=\"" . $conf['admin_script'] . "?act=template&amp;what=del_css&amp;file=" . $file . "&amp;s=" . $HTTP_GET_VARS['s'] . "\" onClick=\"javascript:if(confirm('||confirm_del_css||')) {return true;} else {return false;}\">" . $lang['del'] . "</a> ]</td></tr>";
			
			$css_droplist .= "<option value=\"" . $file . "\">" . $file . "</option>";
		}
	}

	if($msg != "")
	{
		$msg = "<tr><td class=\"theader\">" . $msg . "</td></tr>";
	}
	
	$basic = parse_basic_admin_template("./templates/admin/admin_list_css.html");

	$output = preg_replace("/{{css_list}}/i", $css_list, $basic);
	$output = preg_replace("/{{css_droplist}}/i", $css_droplist, $output);
	$output = preg_replace("/{{msg}}/i", $msg, $output);
	
	return $output;

}

function new_css()
{
global $conf, $lang, $HTTP_POST_VARS;

	if($HTTP_POST_VARS['css_template'] == "")
	{
		error($lang['no_file_chosen'], $lang['back_choose_file']);
	}
	
	if($HTTP_POST_VARS['new_css_name'] == "")
	{
		error($lang['no_css_name'], $lang['back_choose_name']);
	}
	
	if($HTTP_POST_VARS['css_template'] == "blank")
	{
		$new_css_file = fopen($conf['dir'] . "templates/styles/" . $HTTP_POST_VARS['new_css_name'] . ".css", "w");
		
		if(!file_exists($conf['dir'] . "templates/styles/" . $HTTP_POST_VARS['new_css_name'] . ".css"))
		{
			error($lang['cannot_open_css'], $lang['check_file_folder_perms']);
		}
		
	}
	else
	{
		$src = "./templates/styles/" . $HTTP_POST_VARS['css_template'];
		$dest = "./templates/styles/" . $HTTP_POST_VARS['new_css_name'] . ".css";
		
		if(!copy($src, $dest))
		
		{
			error($lang['cannot_copy_css'] . " " . $src, $lang['check_file_folder_perms']);
		}
	}
	
	return render_css("", $HTTP_POST_VARS['new_css_name'] . ".css");
}

function render_css($msg="", $filename="")
{
global $conf, $lang, $HTTP_GET_VARS;
//This function must be given the name of the stylesheet to edit

	if($filename == "")
	{
		if($HTTP_GET_VARS['file'] != "")
		{
			$filename = $HTTP_GET_VARS['file'];
		}
		else
		{
			return error($lang['no_file_chosen']);
		}
	}

	$basic = parse_basic_admin_template("./templates/admin/admin_edit_css.html");
	if (!$fp = @fopen($conf['dir'] . "./templates/styles/" . $filename, "r"))
	{
		return error($lang['cannot_open_css'], $lang['check_file_exists'] . "|" . $lang['check_file_perms']);
	}
	
	if($msg != "")
	{
		$msg = "<tr><td class=\"theader\">" . $msg . "</td></tr>";
	}
	
	$contents = fread($fp, filesize($conf['dir'] . "./templates/styles/" . $filename));
	$output = preg_replace("/{{css_content}}/i", $contents, $basic);
	$output = preg_replace("/{{msg}}/i", $msg, $output);
	$output = preg_replace("/{{file}}/i", $filename, $output);
	return $output;
}

function do_css()
{
global $HTTP_POST_VARS, $conf, $lang;

	if($HTTP_POST_VARS['filename'] == "")
	{
		return error($lang['no_file_chosen']);
	}

	if ($fp = @fopen($conf['dir'] . "./templates/styles/" . $HTTP_POST_VARS['filename'], "w"))
	{
		$to_put = $HTTP_POST_VARS['css_data'];
		$to_put = stripslashes($to_put);
		@fwrite($fp, $to_put, strlen($to_put));
		@fclose($fp);
		$output = $lang['main_css_updated'];
	}
	else
	{
		return error($lang['cannot_open_css'], $lang['check_file_exists'] . "|" . $lang['check_file_perms']);
	}
	return list_css($output);
}

function del_css()
{
global $HTTP_GET_VARS, $conf, $lang;

	if($HTTP_GET_VARS['file'] == "")
	{
		return error($lang['no_file_chosen_del']);
	}
	
	if($HTTP_GET_VARS['file'] == "Default.css")
	{
		return error($lang['no_del_default']);
	}

	//Check that the file has a .css extension
	$file_array = explode(".", $HTTP_GET_VARS['file']);
	$extension_count = count($file_array) - 1;

	$extension = $file_array[$extension_count];

	if($extension != "css")
	{
		error($lang['only_del_css']);
	}

	if(!unlink($conf['dir'] . "templates/styles/" . $HTTP_GET_VARS['file']))
	{
		error($lang['cannot_del_css'], $lang['check_file_folder_perms']);
	}
	
	return list_css($lang['css_deleted'] . ": " . $HTTP_GET_VARS['file']);
}

function template_editor()
{
global $conf, $lang;

	$basic = parse_basic_admin_template("./templates/admin/admin_template_editor.html");
	$list = "";
	if($dh = @opendir($conf['dir'] . "templates/"))
	{
		while ($file = readdir($dh))
		{
			if((preg_match("/.html$/", $file)) && ($file != "index.html"))
			{
				$list .= "<option value=\"" . $file . "\">" . $file . "</option>\n";
			}
		}
		closedir($dh);
	}
	$output = preg_replace("/{{template_list}}/", $list, $basic);
	return $output;
}

function do_template_editor($msg="")
{
global $conf, $HTTP_POST_VARS, $lang;

	if(!isset($HTTP_POST_VARS['file']) || $HTTP_POST_VARS['file'] == "null")
	{
		return error($lang['no_file_chosen'], $lang['back_choose_file']);
	}
	$basic = parse_basic_admin_template("./templates/admin/admin_do_template_editor.html");
	if(!$fp = @fopen($conf['dir']."templates/".$HTTP_POST_VARS['file'], "r"))
        {
       	        return error($lang['error_opening_file'] . $HTTP_POST_VARS['file'], $lang['check_file_exists'] . "|" . $lang['check_file_perms']);
        }
        else
        {
		$content = @fread($fp, filesize($conf['dir']."templates/".$HTTP_POST_VARS['file']));
	}
	$to_find = array (	"/\"/",
				"/\|/",
        			"/</",
            			"/>/");
	$to_replace = array (	"&quot;",
				"&#124;",
               			"&lt;",
              			"&gt;");
	$content = preg_replace($to_find, $to_replace, $content);
	
	if($msg != "")
	{
		$msg = "<tr><td class=\"theader\">" . $msg . "</td></tr>";
	}

	$output = preg_replace("/{{file_name}}/", $HTTP_POST_VARS['file'], $basic);
	$output = preg_replace("/{{content}}/", stripslashes($content), $output);
	$output = preg_replace("/{{msg}}/", $msg, $output);
	
return $output;
}

function save_template()
{
global $conf, $HTTP_POST_VARS, $lang;

	if(!isset($HTTP_POST_VARS['file']))
	{
		return error($lang['no_filename_found'], $lang['check_file']);
	}
	if(!is_writeable($conf['dir']."templates/".$HTTP_POST_VARS['file']))
	{
		return error($lang['cannot_write_file'], $lang['check_file_folder_perms']);
	}
	if(isset($HTTP_POST_VARS['content']))
	{
		$to_put = stripslashes($HTTP_POST_VARS['content']);
	}
	else
	{
		$to_put = "";
	}
	if ($fp = @fopen($conf[dir] . "templates/".$HTTP_POST_VARS['file'], "w"))
	{
		@fwrite($fp, $to_put, strlen($to_put));
		@fclose($fp);
	}
	else
	{
		return error($lang['cannot_write_file'], $lang['check_file_folder_perms']);
	}
	
	return do_template_editor($lang['the_file'] . " <i>" . $HTTP_POST_VARS['file'] . "</i> " . $lang['has_been_edited']);
}

function show_macros()
{
global $DB, $conf,$HTTP_GET_VARS, $lang;

	if (!$DB->query("SELECT id, name, extensions FROM " . $conf['dbprefix'] . "macro"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	$output = "<span class=\"title\">" . $lang['edit_macros'] . "</span><br /><br />";
	$output .= "<a href=\"" . $conf['admin_script'] . "?act=template&amp;what=new_macro&amp;s=" . $HTTP_GET_VARS['s'] . "\"><img src=\"./site_images/plus.gif\" border=\"0\" valign=\"middle\" /></a>&nbsp;<a href=\"" . $conf['admin_script'] . "?act=template&amp;what=new_macro&amp;s=" . $HTTP_GET_VARS['s'] . "\">" . $lang['new_macro'] . "</a><br /><br />";
	$output .= "<table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">\n<tr><td class=\"ad_row\"><b>" . $lang['macro_id'] . "</b></td><td class=\"ad_row\"><b>" . $lang['macro_name'] . "</b></td><td class=\"ad_row\"><b>" . $lang['extensions'] . "</b></td><td class=\"ad_row\">[ " . $lang['actions'] . " ]</td></tr>\n";

	while($row = $DB->fetch_array())
	{
		$output .= "<tr><td class=\"ad_row\">" . $row['id'] . "</td><td class=\"ad_row\">" . $row['name'] . "</td><td class=\"ad_row\">";
		$extensions = explode(",", $row['extensions']);
		foreach($extensions as $ext)
		{
			$output .= " ." . $ext . "";
		}
	$output .= "</td><td class=\"ad_row\"><a href=\"" . $conf['admin_script'] . "?act=template&amp;what=edit_macro&amp;id=" . $row['id'] . "&amp;s=" . $HTTP_GET_VARS['s'] . "\"><img src=\"./site_images/edit.gif\" border=\"0\" alt=\"" . $lang['edit_macro'] . "\" align=\"middle\" /></a> <a href=\"" . $conf['admin_script'] . "?act=template&amp;what=delete_macro&amp;id=" . $row['id'] . "&amp;s=" . $HTTP_GET_VARS['s'] . "\" onClick=\"javascript:if(confirm('" . $lang['confirm_del_macro'] . "')) {return true;} else {return false;}\"><img src=\"./site_images/x.gif\" alt=\"" . $lang['del_macro'] . "\" border=\"0\" align=\"middle\" /></a></td></tr>\n";
	}
	$output .= "</table>";
return $output;
}

function edit_macro($msg="")
{
global $DB, $conf,$HTTP_GET_VARS, $HTTP_POST_VARS, $lang;

	if(!isset($HTTP_GET_VARS['id']) && isset($HTTP_POST_VARS['id']))
	{
		$HTTP_GET_VARS['id'] = $HTTP_POST_VARS['id'];
	}

	if (!$DB->query("SELECT name, extensions, macro FROM " . $conf['dbprefix'] . "macro WHERE id=\"" . $HTTP_GET_VARS['id'] . "\""))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	$output = "<span class=\"title\">" . $lang['edit_macros'] . "</span><br /><br />";
	$output .= "<form action=\"" . $conf['admin_script'] . "?act=template&amp;what=save_image_macro&amp;s=" . $HTTP_GET_VARS['s'] . "\" method=\"post\">";
	$output .= "<input type=\"hidden\" name=\"id\" value=\"" . $HTTP_GET_VARS['id'] . "\" />";
	$output .= "<table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">";
	if($msg != "")
	{
		$output .= "<tr><td class=\"theader\" colspan=\"2\">" . $msg . "</td></tr>";
	}
	while($row = $DB->fetch_array())
	{
		$output .= "<tr><td class=\"theader\" colspan=\"2\">" . $lang['macro_id'] . ": " . $HTTP_GET_VARS['id'] . "</td></tr>";
		$output .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['macro_name'] . ":</td><td class=\"ad_row\" width=\"50%\"><input type=\"text\" name=\"name\" value=\"" . $row['name'] . "\" size=\"50\" /></td></tr>\n";
		$output .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['list_img_extensions'] . ":</td>";
		$output .= "<td class=\"ad_row\" width=\"50%\"><input type=\"text\" name=\"extensions\" size=\"50\" value=\"" . $row['extensions'] . "\" /></td></tr>";
		$output .= "<tr><td class=\"ad_row\" width=\"50%\">" . $lang['macro_types'] . ": " . $lang['can_use_codes'] . ":<br /><b>{{img}}</b> - " . $lang['img_url'] . "<br /><b>{{name}}</b> - " . $lang['img_name'] . "<br /><b>{{width}}</b> - " . $lang['img_width'] . "<br /><b>{{height}}</b> - " . $lang['img_height'] . "</td>";
		$output .= "<td class=\"ad_row\" width=\"50%\"><textarea cols=\"60\" rows=\"5\" name=\"macro\" style=\"width:100%\">" . $row['macro'] . "</textarea></td></tr>";
	}
	$output .= "<tr><td class=\"ad_row\" colspan=\"2\">\n<input type=\"submit\" value=\"" . $lang['update_macro'] . "\"></td></tr></table>\n</form>";
	return $output;
}

function save_image_macro($type="insert")
{
global $DB, $HTTP_POST_VARS, $HTTP_GET_VARS, $conf, $lang;

	if($HTTP_POST_VARS['name'] == "")
	{
		return error($lang['no_macro_name'], $lang['fill_all_fields']);
	}
	if($HTTP_POST_VARS['extensions'] == "")
	{
		return error($lang['no_extensions_entered'], $lang['fill_all_fields']);
	}
	if($HTTP_POST_VARS['macro'] == "")
	{
		return error($lang['no_macro_text'], $lang['fill_all_fields']);
	}

	if($type == "update")
	{
		$sql = "UPDATE `" . $conf[dbprefix] . "macro` SET name='$HTTP_POST_VARS[name]', extensions='$HTTP_POST_VARS[extensions]', macro='$HTTP_POST_VARS[macro]' WHERE id='$HTTP_POST_VARS[id]'";
	}
	else
	{
		$sql = "INSERT INTO `" . $conf[dbprefix] . "macro` (id, name, extensions, macro) VALUES ('', '$HTTP_POST_VARS[name]', '$HTTP_POST_VARS[extensions]', '$HTTP_POST_VARS[macro]')";
	}

	if (!$DB->query($sql))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if($type == "insert")
	{
		//Set the new id as a post value to allow us to flip back to the edit form
		$HTTP_POST_VARS['id'] = $DB->next_value();
	}

	return edit_macro ($lang['macro_updated']);
}

function delete_macro()
{
global $DB, $HTTP_GET_VARS, $conf, $lang;

	if (!$DB->query("DELETE FROM `" . $conf['dbprefix'] . "macro` WHERE id=\"" . $HTTP_GET_VARS['id'] . "\" LIMIT 1"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	return "<span class=\"title\">" . $lang['del_macro'] . "</span><br /><br /><table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\"><tr><td class=\"ad_row\">" . $lang['macro_final_deleted'] . "</td></tr></table>";
}

?>