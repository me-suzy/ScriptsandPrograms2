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
// $Id: ad_lang.php,v 1.00 2003/09/09 20:04:31 chrisc Exp $

/*-----------------------------------------------
  ENSURE THE SCRIPT IS NOT BEING ACCESSED DIRECTLY
 ------------------------------------------------*/
if(!defined("LOADED"))
{
	die("Cannot access the script directly");
}

switch($HTTP_GET_VARS['what'])
{
		case 'new':
		$to_do = new_lang_form();
		break;

		case 'do_add':
		$to_do = do_add_lang();
		break;

		case 'edit':
		$to_do = edit_lang();
		break;

		case 'doedit':
		$to_do = do_edit_lang();
		break;

		case 'show_edit_file':
		$to_do = show_edit_file();
		break;

		case 'update_lang_file':
		$to_do = update_lang_file();
		break;

		case 'del':
		$to_do = del_lang();
		break;

		case 'final_del_lang':
		$to_do = final_del_lang();
		break;

		case 'import_export':
		$to_do = import_export_options();
		break;

		case 'import':
		$to_do = import();
		break;

		case 'export':
		$to_do = export();
		break;
}

function new_lang_form()
{
global $conf, $lang;

	$dp = opendir($conf['dir'] . "lang");
	if(!$dp)
	{
		return error($lang['no_open_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms']);
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!=".." && $file!="index.html")
		{
			$html .= "<option value=\"" . $file . "\">$file</option>";
		}
	}

	$basic = parse_basic_admin_template("./templates/admin/admin_add_lang.html");
	$data = preg_replace("/{{lang_select}}/", $html, $basic);

return $data;
}

function do_add_lang()
{
global $conf, $lang, $HTTP_POST_VARS;

	if($HTTP_POST_VARS['lang_template'] == "")
	{
		return error($lang['no_template_select'], $lang['back_choose_lang']);
	}

	if($HTTP_POST_VARS['new_name'] == "")
	{
		return error($lang['no_new_name'], $lang['back_choose_new_name']);
	}

	//Remove all the bad characters that people put in even though they have been told not to

	$bad_chars = array(
//				"#\\#",
				"#/#",
				"#:#",
				"#\*#",
				"#\?#",
				"#\"#",
				"#<#",
				"#>#",
				"#\|#",
				"# #",
					);

	$new_lang = preg_replace($bad_chars, "_", $HTTP_POST_VARS['new_name']);
	$new_lang = stripslashes(ucwords($new_lang));

	copy_dir($conf['dir'] . "lang/" . $HTTP_POST_VARS['lang_template'], $conf['dir'] . "lang/" . $new_lang);
	return "<table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\"><tr><td class=\"theader\">" . $lang['new_lang'] . "</td></tr><tr><td class=\"ad_row\">" . $lang['new_lang'] . " " . $new_lang . " sucesfully created</td></tr></table>";

}

function edit_lang()
{
global $conf, $lang;

	$dp = opendir($conf['dir'] . "lang");
	if(!$dp)
	{
		return error($lang['no_open_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms']);
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!=".." && $file!="index.html")
		{
			$html .= "<option value=\"" . $file . "\">$file</option>";
		}
	}

	$basic = parse_basic_admin_template("./templates/admin/admin_edit_lang.html");
	$data = preg_replace("/{{lang_select}}/", $html, $basic);

	return $data;

}

function do_edit_lang()
{
global $HTTP_POST_VARS, $lang, $conf;

	if($HTTP_POST_VARS['lang_select'] == "")
	{
		return error($lang['no_lang_selected'], $lang['back_select_lang']);
	}

	//Admin select form
	$dp = opendir($conf['dir'] . "lang/" . $HTTP_POST_VARS['lang_select'] . "/admin");
	if(!$dp)
	{
		return error($lang['no_open_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms'] . "|" . $lang['check_1_db_driver'] . "");
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!=".." && $file!="index.html" && (!is_dir($conf['dir'] . "lang/admin/" . $HTTP_POST_VARS['lang_select'] . "/" . $file)))
		{
			$admin_select .= "<option value=\"" . $file . "\">$file</option>";
		}
	}
	closedir($dp);


	//Public select form
	$dp = opendir($conf['dir'] . "lang/" . $HTTP_POST_VARS['lang_select']);
	if(!$dp)
	{
		return error($lang['no_open_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms'] . "|" . $lang['check_1_db_driver'] . "");
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!=".." && $file!="index.html" && (!is_dir($conf['dir'] . "lang/" . $HTTP_POST_VARS['lang_select'] . "/" . $file)))
		{
			$public_select .= "<option value=\"" . $file . "\">$file</option>";
		}
	}
	closedir($dp);

	$basic = parse_basic_admin_template("./templates/admin/admin_edit_lang_file.html");
	$data = preg_replace("/{{lang_select_public}}/", $public_select, $basic);
	$data = preg_replace("/{{lang_select_admin}}/", $admin_select, $data);
	$data = preg_replace("/{{lang_to_edit}}/", $HTTP_POST_VARS['lang_select'], $data);

	return $data;	
}

function show_edit_file()
{
global $conf, $lang, $HTTP_POST_VARS, $HTTP_GET_VARS;

	//Array contains the lang variable names that should use textareas rather than textboxes
	$use_textarea = array("email_body_recip", "email_body_notification", "email_body_resend", "email_body_test_email");

	//Since we are editing a file we could be using we need to reset all the language variables

	//Problems are caused here when register_globals are On (they shouldn't be on anyway!) I will fix this later!
	foreach($lang as $k => $v)
	{
		$temp_lang[$k] = $v;
	}
	unset($lang);

	if($HTTP_POST_VARS['file_select'] == "")
	{
		return error($temp_lang['no_lang_edit_selected'], $temp_lang['back_select_lang_edit']);
	}

	$lang_to_edit = $HTTP_POST_VARS['lang_to_edit'];

	if($HTTP_POST_VARS['type'] == "public")
	{
		$lang_path = $conf['dir'] . "lang/" . $lang_to_edit . "/" . $HTTP_POST_VARS['file_select'];
	}
	else
	{
		$lang_path = $conf['dir'] . "lang/" . $lang_to_edit . "/admin/" . $HTTP_POST_VARS['file_select'];
	}

	require $lang_path;

	$output = "<form action=\"" . $conf['admin_script'] . "?act=lang&amp;what=update_lang_file&amp;s=" . $HTTP_GET_VARS['s'] . "\" method=\"post\">\n";
	$output .= "<span class=\"title\">Edit Language File</span><br /><br />";
	$output .= "<table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\"><tr><td class=\"theader\" colspan=\"2\">Currently editing <b>" . $HTTP_POST_VARS['file_select'] . "</b></td></tr>";
	$output .= "<input type=\"hidden\" name=\"type\" value=\"" . $HTTP_POST_VARS['type'] . "\">\n";
	$output .= "<input type=\"hidden\" name=\"lang_select\" value=\"" . $HTTP_POST_VARS['lang_to_edit'] . "\">\n";
	$output .= "<input type=\"hidden\" name=\"file_select\" value=\"" . $HTTP_POST_VARS['file_select'] . "\">\n";
	$output .= "<tr>\n<td class=\"ad_row\" width=\"20%\">Language Variable</td>\n<td class=\"ad_row\" width=\"80%\">Language Value</td>\n</tr>\n\n";

	$lang = preg_replace("/</", "&lt;", $lang);
	$lang = preg_replace("/>/", "&gt;", $lang);
	$lang = preg_replace("/\"/", "&quot;", $lang);

	foreach($lang as $k => $v)
	{
		//Remove our generic new line characters
		if($HTTP_POST_VARS['file_select'] == "email.php")
		{
			$v = preg_replace("/&lt;&lt;&gt;&gt;/", "\n", $v);
		}

		$v = stripslashes($v);
			
		//$v = preg_replace("/&/", "&#38;", $v);
		//$v = preg_replace("/</", "&#60;", $v);
		//$v = preg_replace("/>/", "&#62;", $v);
		//$v = preg_replace("/'/", "&#39;", $v);

		if(in_array($k, $use_textarea) && $HTTP_POST_VARS['file_select'] == "email.php")
		{
			$output .= "<tr><td class=\"ad_row\" width=\"20%\">" . $k . "</td>\n<td class=\"ad_row\" width=\"80%\"><textarea cols=\"50\" rows=\"10\" name=\"lang[" . $k . "]\" style=\"width:100%;\">" . stripslashes($v) . "</textarea></td></tr>\n\n";
		}
		else
		{
			$output .= "<tr><td class=\"ad_row\" width=\"20%\">" . $k . "</td>\n<td class=\"ad_row\" width=\"80%\"><input size=\"50\" type=\"text\" name=\"lang[" . $k . "]\" value=\"" . stripslashes($v) . "\" style=\"width:100%;\"></td></tr>\n\n";
		}
	}

	$output .= "<tr><td class=\"ad_row\" colspan=\"2\"><input type=\"submit\" value=\"" . $temp_lang['update_lang'] . "\"></td></tr></table></form>";

	return $output;

}

function update_lang_file()
{
global $conf, $lang, $HTTP_POST_VARS;

	$str = "<?php\n";

	foreach ($HTTP_POST_VARS['lang'] as $k => $v)
	// Take our submitted data, clean it up and add it to the $lang array
	{

		//Since email systems define so many new line symbols, lets make our own, generic version
		if($HTTP_POST_VARS['file_select'] == "email.php")
		{
			$v = preg_replace("/\n/", "<<>>", $v);
		}

//		$v = preg_replace("/'/", "&#39;", $v);
		$v = stripslashes($v);
		$new_lang[$k] = $v;
	}

	foreach ($new_lang as $k => $v)
	{
		$v = addslashes($v);
		$str .= "\$lang['" . $k . "'] = \"" . $v . "\";\n";
	}

	$str .= "?>";

	if($HTTP_POST_VARS['type'] == "public")
	{
		$lang_path = $conf['dir'] . "lang/" . $HTTP_POST_VARS['lang_select'] . "/" . $HTTP_POST_VARS['file_select'];
	}
	else
	{
		$lang_path = $conf['dir'] . "lang/" . $HTTP_POST_VARS['lang_select'] . "/admin/" . $HTTP_POST_VARS['file_select'];
	}

	if ($fp = @fopen($lang_path, "w"))
	{
		@fwrite($fp, $str, strlen($str));
		@fclose($fp);
		return basic_output($lang['lang_update_ok'], $lang['update_lang']);
	}
	else
	{
		return error($lang['lang_update_failed']);
	}
}

function del_lang()
{
global $lang, $conf;

	$dp = opendir($conf['dir'] . "lang");
	if(!$dp)
	{
		return error($lang['no_open_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms']);
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!=".." && $file!="index.html")
		{
			$html .= "<option value=\"" . $file . "\">$file</option>";
		}
	}

	$basic = parse_basic_admin_template("./templates/admin/admin_del_lang.html");
	$data = preg_replace("/{{lang_select}}/", $html, $basic);

	return $data;
}

function final_del_lang()
{
global $conf, $lang, $HTTP_POST_VARS;

	if($HTTP_POST_VARS['lang_to_del'] == "")
	{
		return error($lang['no_lang_chosen']);
	}

	if($HTTP_POST_VARS['lang_to_del'] == "English")
	{
		return error($lang['lang_chosen_en']);
	}

	delete_file($conf['dir'] . "lang/" . $HTTP_POST_VARS['lang_to_del']);

	if(is_dir($conf['dir'] . "lang/" . $HTTP_POST_VARS['lang_to_del']))
	{
		return basic_output($lang['del_not_ok'], $lang['del_lang']);
	}
	else
	{
		return basic_output($lang['del_ok'], $lang['del_lang']);
	}
}

function import_export_options()
{
global $conf, $lang;

	$dir = $conf['dir'] . "export";
	if(is_dir($dir))
	{
		$handle = opendir($dir);
		while(($file = readdir($handle)) !== false)
		{
			if(($file != ".") && ($file != ".."))
			{
				if(preg_match("/^lang-.+?\.tar$/", $file))
				{
					$import_select_box .= "<option value=\"" . $file . "\">" . $file . "</option>";
				}
			}
		}
		closedir($handle);
	}


	$dp = opendir($conf['dir'] . "lang");
	if(!$dp)
	{
		return error($lang['no_open_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms']);
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!=".." && $file!="index.html")
		{
			$export_select_box .= "<option value=\"" . $file . "\">$file</option>";
		}
	}

	$basic = parse_basic_admin_template("./templates/admin/admin_view_import_export.html");
	$output = preg_replace("/{{import_select_box}}/", $import_select_box, $basic);
	$output = preg_replace("/{{export_select_box}}/", $export_select_box, $output);

	return $output;

}

function import()
{
global $lang, $conf, $HTTP_POST_VARS;

	if($HTTP_POST_VARS['import_tarball'] == "")
	{
		return error($lang['no_tarball_select']);
	}

	//Require the Tar module
	require "./source/modules/Tar.php";

	//Assigns a new name based on the tarball's name.
	//If the tarball is called lang-LangName.tar, the new language is LangName.
	$new_name = preg_replace("/^lang-(\S+)\.tar$/", "\\1", $HTTP_POST_VARS['import_tarball']);

	if(is_dir($conf['dir'] . "lang/" . $new_name))
	{
		return error($lang['lang_name_exists'], $lang['rename_tarball']);
	}

	$tar = new tar();
	$tar->new_tar($conf['dir'] . "export", $HTTP_POST_VARS['import_tarball']);

	if(!mkdir($conf['dir'] . "lang/" . ucwords($new_name)))
	{
		return error($lang['cannot_create_lang_folder'], $lang['check_dir_perms']);
	}

	$tar->extract_files($conf['dir'] . "lang/" . $new_name);

	return basic_output($lang['import_success'], $lang['import_lang']);

}

function export()
{
global $lang, $conf, $HTTP_POST_VARS;

	if($HTTP_POST_VARS['export_lang'] == "")
	{
		return error($lang['no_lang_selected'], $lang['back_select_lang']);
	}

	if(file_exists($conf['dir'] . "export/lang-" . $HTTP_POST_VARS['export_lang'] . ".tar"))
	{
		return error($lang['tarball_exists'], $lang['delete_current_tarball']);
	}

	//Require the Tar module
	require "./source/modules/Tar.php";

	//Now create the tarball
	$tar = new tar();
	$tar->new_tar($conf['dir'] . "export" , "lang-" . $HTTP_POST_VARS['export_lang'] . ".tar");

	//Add the chosen language dir
	$tar->add_directory($conf['dir'] . "lang/" . $HTTP_POST_VARS['export_lang']);
	$tar->write_tar();

	if(file_exists($conf['dir'] . "export/lang-" . $HTTP_POST_VARS['export_lang'] . ".tar"))
	{	
		return basic_output($lang['export_success'], $lang['export_lang']);
	}
	else
	{
		return error($lang['unknown_export_error']);
	}

}

?>