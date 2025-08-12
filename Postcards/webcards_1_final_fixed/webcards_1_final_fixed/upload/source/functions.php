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
// $Id: functions.php,v 1.00 2004/09/03 00:37:44 chrisc Exp $

/*-----------------------------------------------
  ENSURE THE SCRIPT IS NOT BEING ACCESSED DIRECTLY
 ------------------------------------------------*/
if(!defined("LOADED"))
{
	die("Cannot access the script directly");
}

$stimer = explode( ' ', microtime() );
$stimer = $stimer[1] + $stimer[0];

function starttime()
{
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $starttime = $mtime;

    return $starttime;
}

// Returns the time that execution ended
function endtime($starttime)
{
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $endtime = $mtime;
    $totaltime = ($endtime - $starttime);

    $totaltime = round($totaltime, 3);

    return $totaltime;
}

$starttime = starttime();

    /*-------------------------------------------------------------------------------------------*/
    // This function (idea taken from IBF) takes all out POST and GET data and makes it websafe            
    /*-------------------------------------------------------------------------------------------*/
    
function clean_incoming_data()
    {
    	global $HTTP_GET_VARS, $HTTP_POST_VARS;
    	$return = array();
    	
		if(is_array($HTTP_GET_VARS))
		{
			while(list($k, $v) = each($HTTP_GET_VARS))
			{
				if(is_array($HTTP_GET_VARS[$k]))
				{
					while(list($k2, $v2) = each($HTTP_GET_VARS[$k]))
					{
						$return[$k][clean_key($k2)] = clean_value($v2);
					}
				}
				else
				{
					$return[$k] = clean_value($v);
				}
			}
		}
		
		// Overwrite GET data with post data
		
		if(is_array($HTTP_POST_VARS))
		{
			while(list($k, $v) = each($HTTP_POST_VARS))
			{
				if (is_array($HTTP_POST_VARS[$k]))
				{
					while(list($k2, $v2) = each($HTTP_POST_VARS[$k]))
					{
						$return[$k][clean_key($k2)] = clean_value($v2);
					}
				}
				else
				{
					$return[$k] = clean_value($v);
				}
			}
		}
		
		return $return;
	}
	
    // Key Cleaner
    
function clean_key($key) {
    
    	if ($key == "" && $key !="0")
    	{
    		return "";
    	}
    	$key = preg_replace( "/\.\./"           , ""  , $key );
    	$key = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $key );
    	$key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
    	return $key;
    }
    
function clean_value($val, $remove_crlf='0') {
    
    	if ($val == "")
    	{
    		return "";
    	}
    	
    	$val = str_replace( "&#032;", " ", $val );
    	$val = str_replace( chr(0xCA), "", $val );  //Remove sneaky spaces
    	
    	$val = str_replace( "&"            , "&amp;"         , $val );
    	$val = str_replace( "<!--"         , "&#60;&#33;--"  , $val );
    	$val = str_replace( "-->"          , "--&#62;"       , $val );
    	$val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
    	$val = str_replace( ">"            , "&gt;"          , $val );
    	$val = str_replace( "<"            , "&lt;"          , $val );
    	$val = str_replace( "\""           , "&quot;"        , $val );
	if($remove_crlf == "0")
	{
    		$val = preg_replace( "/\n/"        , "<br>"          , $val ); // Convert literal newlines
		$val = preg_replace( "/(\&lt;br\&gt;|\&lt;br \/\&gt;)/i", "<br>", $val); // Put back <br>
	}
    	$val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
    	$val = preg_replace( "/\r/"        , ""              , $val ); // Remove literal carriage returns
    	$val = str_replace( "!"            , "&#33;"         , $val );
    	$val = str_replace( "'"            , "&#39;"         , $val ); // IMPORTANT: It helps to increase sql query safety.

	// Strip slashes if not already done so.

    	if ( get_magic_quotes_gpc() )
    	{
		$val = stripslashes($val);
    	}

    	// Swop user inputted backslashes
    	$val = preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $val ); 
    	
    	return $val;
    }

function getip()
{
global $HTTP_SERVER_VARS;

	if($HTTP_SERVER_VARS)
	{
		if($HTTP_SERVER_VARS[HTTP_X_FORWARDED_FOR])
		{
			$realip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		}
		elseif( $HTTP_SERVER_VARS["HTTP_CLIENT_IP"])
		{
			$realip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		}
		else
		{
			$realip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
		}

	}
	else
	{
		if(getenv('HTTP_X_FORWARDED_FOR'))
		{
			$realip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif(getenv('HTTP_CLIENT_IP'))
		{
			$realip = getenv('HTTP_CLIENT_IP');
		}
		else
		{
			$realip = getenv('REMOTE_ADDR');
		}
	}
	return $realip; 
}

function check_ip($ip_addy)
{
global $conf, $lang;
// Strangely enough, when the function returns true, the IP is banned

	if($ip_addy == "")
	{
		return false;
	}

	$fp = explode(",", $conf['ip_ban']);
	foreach ($fp as $check)
	{
		if ($ip_addy == $check)
		{
			return true;
		}
	}
	return false;
}

function check_email($email_addy)
{
global $conf, $lang;

	$fp = explode(",", $conf['email_ban']);

	foreach ($fp as $check)
	{
		if (strtolower($email_addy) == strtolower($check))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}

function check_email_format($email_addy)
{
	if(!preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,4})(\]?)$/", $email_addy))
	{
		return false;
	}
	else
	{
		return true;
	}
}

function show_login($message)
{
global $conf, $lang;

	$html = "<span class=\"title\">" . $lang['please_login'] . "</span><br /><br />";
	if ($message!="")
	{
		$html .= "<span class=\"sub_header\">" . $message . "</span><br /><br />";
	}
	$html .= "<form action=\"" . $conf['admin_script'] . "?act=misc&what=login\" target=\"_top\" method=\"post\">\n";
	$html .= $lang['username'] . ":<br /><input type=\"text\" name=\"user\" size=\"100%\" /><br><br />\n";
	$html .= $lang['password'] . ":<br /><input type=\"password\" name=\"password\" size=\"100%\" /><br /><br />\n";
	$html .= "Select a language:<br /><select name=\"temp_ad_lang\">\n";
	$html .= "<option value=\"def\" selected=\"selected\">" . $lang['default_lang'] . "</option>\n";

	$dp = opendir($conf['dir'] . "./lang");
	if(!$dp)
	{
		return error($lang['no_open_lang_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms']);
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!=".." && is_dir($conf['dir'] . "lang/" . $file))
		{
			$html .= "<option value=\"" . $file . "\">" . $file . "</option>";
		}
	}

	$html .= "</select><br /><br />";
	$html .= "<input type=\"submit\" value=\"" . $lang['login'] . "\" />";
	return $html;
}

function error($problems, $solutions=0)
{
global $lang;

	$html = "<span class=\"title\">" . $lang['error'] . "</span><br /><br />";
	$html .= "<table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">";
	$html .= "<tr><td class=\"theader\" width=\"100%\">";
	$html .= $lang['error_must_resolve'] . ":\n";
	$html .= "</td></tr>";
	$html .= "<tr><td class=\"ad_row\" width=\"100%\"><ul>\n<li>" . $problems . "</li>\n</ul>\n";
	
	if ($solutions!="0")
	{
		$html .= $lang['error_solutions'] . ":\n<ul>\n";
		$solutions = explode("|", $solutions);
		foreach($solutions as $val)
		{
			$html .= "<li>" . $val . "</li>\n";
		}
		$html .= "</ul>\n";
	}
	
	$html .= "</td></tr></table>";
	
	output($html);
}

function parse_basic_admin_template($template)
// Parses the admin template and put all our variable in
{
global $conf, $HTTP_GET_VARS, $lang;
	if(!$fp = @fopen("$template", "r"))
        {
       	        return error($lang['no_open_template'] . ": " . $template . "", $lang['check_file_exists'] . "|" . $lang['check_file_perms']);
        }
        else
        {
		$data = @fread($fp, filesize($template));
		$form_output = preg_replace("/{{admin_script}}/i", $conf['admin_script'], $data);
		$form_output = preg_replace("/{{sess}}/i", $HTTP_GET_VARS['s'], $form_output);

        }
	return $form_output;
}

function getExt($filename)
{
	$file_pieces = explode(".", $filename);
	return ($file_pieces[count($file_pieces) - 1]);
}

//Note that the following function must be passed an extension without a "." to function properly
function getMacro($ext, $return="macro")
{
global $DB, $conf, $lang;

	if (!$query = mysql_query("SELECT id, name, extensions, macro FROM " . $conf['dbprefix'] . "macro"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	while($row = mysql_fetch_array($query))
	{
		$extensions = explode(",", $row['extensions']);
		foreach($extensions as $ex)
		{
			if(strtolower($ex) == strtolower($ext))
			{
				switch($return)
				{
					case "id":
					return $row['id'];
					break;

					case "name":
					return $row['name'];
					break;

					case "macro":
					return $row['macro'];
					break;
				}
			}
		}
	}
	return false;
}	

function verify_files($return="error_count")
{
global $error_array, $HTTP_GET_VARS, $conf, $lang;

$error_array = array();
$required_dirs_ok = 1;
$required_files_ok = 1;
$required_templs_ok = 1;
$required_admin_templs_ok = 1;
$required_write_files_ok = 1;
$required_write_dirs_ok = 1;
$installer_locked = 1;
$config_ok = 1;

	$output = "<span class=\"title\">" . $lang['verify_integ'] . "</span><br /><br />";
	$output .= "<table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">";

	//Look for required directories
	$required_dirs = array("./export", "./images", "./images/thumbs", "./lang", "./site_images", "./source", "./source/drivers", "./source/modules", "./templates", "./templates/admin", "./templates/styles");
	foreach ($required_dirs as $dir)
	{
		if(!@is_dir($dir))
		{
			$error_array[] = $lang['no_find_dir'] ." \"" . $dir . "\".";
			$required_dirs_ok = 0;
		}
	}

	//Look for required files
	$required_files = array("./admin.php", "./config.php", "./index.php", "./pickup.php", "./source/ad_ban.php",  "./source/ad_category.php", "./source/ad_config.php", "./source/ad_images.php", "./source/ad_index.php", "./source/ad_lang.php", "./source/ad_misc.php", "./source/ad_template.php", "./source/ad_toolbox.php", "./source/ad_users.php", "./source/functions.php", "./source/ad_help.php");
	foreach ($required_files as $file)
	{
		if(!file_exists($file))
		{
			$error_array[] = $lang['no_find_file'] . " \"" . $file . "\"";
			$required_files_ok = 0;
		}
	}

	//Look for required public templates
	$required_templates = array("adv_search.html", "main_form.html", "preview_card.html", "render_card.html", "select_img.html", "view_image_stats.html");
	foreach ($required_templates as $templ)
	{
		if(!file_exists("./templates/" . $templ))
		{
			$error_array[] = $lang['no_find_templ'] . "\" ./templates/" . $templ . "\"";
			$required_templs_ok = 0;
		}
	}

	//Look for required admin templates
	$required_admin_templates = array("add_cat", "add_image", "add_lang", "add_user", "confirm_delete_all_images", "del_lang", "delete_card", "delete_cat", "delete_img", "delete_img_results", "delete_user", "do_delete_cat", "do_edit_cat", "do_edit_img", "do_template_editor", "edit_cat", "edit_css", "edit_img", "edit_img_results", "edit_lang", "edit_lang_file", "edit_template", "email_ban", "font_settings", "frame", "image_uploaded", "ip_ban", "ip_tools", "left", "main", "new_macro", "template_editor", "view_card_details", "view_email", "view_import_export", "list_css");
	foreach ($required_admin_templates as $ad_templ)
	{
		if(!file_exists("./templates/admin/admin_" . $ad_templ . ".html"))
		{
			$error_array[] = $lang['no_find_templ'] . "\" ./templates/admin/admin_" . $ad_templ . ".html\"";
			$required_admin_templs_ok = 0;
		}
	}

	//Check if required files are writeable
	$required_write_files = array("./config.php", "./templates/styles/Default.css");
	foreach($required_write_files as $file)
	{
		if(!is_writeable($file))
		{
			$error_array[] = $file . $lang['no_writeable'];
			$required_write_files_ok = 1;
		}
	}

	//Check if required directories are writeable
	$writeable_dirs = array("./images", "./images/thumbs", "./lang", "./templates/styles");
	foreach ($writeable_dirs as $dir)
	{
		if(!is_writeable($dir))
		{
			$error_array[] = $dir . $lang['no_writeable'];
			$required_write_dirs_ok = 0;
		}
	}

	//Check if installer is locked
	if(!file_exists("./lock.cgi"))
	{
		$error_array[] = $lang['installer_no_locked'] . " <a href=\"" . $conf['admin_script'] . "?act=toolbox&amp;what=lock_installer&s=" . $HTTP_GET_VARS['s'] . "\">" . $lang['click_here'] . "</a> "  . $lang['attempt_lock'];
		$installer_locked = 0;
	}

	//Check the configuration information is OK
	if(!is_array($conf))
	{
		$error_array[] = $lang['config_corrupt'];
		$config_ok = 0;
	}

	//The front admin page only needs an error array count
	if($return == "error_count")
	{
		return count($error_array);
	}

	//Now analyse which error data we have recieved

	if($required_dirs_ok == 1)
	{
		$output .= "<tr><td class=\"ad_row\">" . $lang['required_dirs_passed'] . "</td></tr>";
	}
	else
	{
		$output .= "<tr><td class=\"ad_row\"><span class=\"warning\">" . $lang['required_dirs_failed'] . "</span></td></tr>";
	}

	if($required_files_ok == 1)
	{
		$output .= "<tr><td class=\"ad_row\">" . $lang['required_files_passed'] . "</td></tr>";
	}
	else
	{
		$output .= "<tr><td class=\"ad_row\"><span class=\"warning\">" . $lang['required_files_failed'] . "</span></td></tr>";
	}

	if($required_templs_ok == 1)
	{
		$output .= "<tr><td class=\"ad_row\">" . $lang['required_templs_passed'] . "</td></tr>";
	}
	else
	{
		$output .= "<tr><td class=\"ad_row\"><span class=\"warning\">" . $lang['required_templs_failed'] . "</span></td></tr>";
	}

	if($required_admin_templs_ok == 1)
	{
		$output .= "<tr><td class=\"ad_row\">" . $lang['required_admin_templs_passed'] . "</td></tr>";
	}
	else
	{
		$output .= "<tr><td class=\"ad_row\"><span class=\"warning\">" . $lang['required_admin_templs_failed'] . "</span></td></tr>";
	}

	if($required_write_files_ok == 1)
	{
		$output .= "<tr><td class=\"ad_row\">" . $lang['required_write_files_passed'] . "</td></tr>";
	}
	else
	{
		$output .= "<tr><td class=\"ad_row\"><span class=\"warning\">" . $lang['required_write_files_failed'] . "</span></td></tr>";
	}

	if($required_write_dirs_ok == 1)
	{
		$output .= "<tr><td class=\"ad_row\">" . $lang['required_write_dirs_passed'] . "</td></tr>";
	}
	else
	{
		$output .= "<tr><td class=\"ad_row\"><span class=\"warning\">" . $lang['required_write_dirs_failed'] . "</span></td></tr>";
	}

	if($installer_locked == 1)
	{
		$output .= "<tr><td class=\"ad_row\">" . $lang['installer_locked'] . "</td></tr>";
	}
	else
	{
		$output .= "<tr><td class=\"ad_row\"><span class=\"warning\">" . $lang['installer_no_locked'] . "</span></td></tr>";
	}

	if($config_ok == 1)
	{
		$output .= "<tr><td class=\"ad_row\">" . $lang['config_no_corrupt'] . "</td></tr>";
	}
	else
	{
		$output .= "<tr><td class=\"ad_row\"><span class=\"warning\">" . $lang['config_corrupt'] . "</span></td></tr>";
	}

	if(count($error_array) == 0)
	{
		$output .= "<tr><td class=\"theader\">" . $lang['no_webcards_errors'] . "</td></tr>";
	}
	else
	{
		$output .= "<tr><td class=\"theader\"><span class=\"warning\">" . $lang['webcards_errors_found'] . "</span>";
		$output .= "<ul>";
		foreach($error_array as $error)
		{
			$output .= "<li>" . $error . "</li>";
		}
		$output .= "</ul></td></tr>";
	}
	
	$output .= "</table>";

	if($return == "text")
	{
		return $output;
	}

}

$build_date = "09-11-05";
$version = "1.0";
$cpy = "<p align=\"center\">Powered by <a href=\"http://webcards.sourceforge.net/\" target=\"_blank\">WebCards</a> v" . $version . " <br /><a href=\"http://webcards.sourceforge.net/\" target=\"_blank\"><img src=\"./site_images/webcardslogo.gif\" border=\"0\" alt=\"WebCards logo\"></a></p>";

function expire($output='none')
{
global $DB, $conf, $lang;

	if($conf['expiry_time'] == "" || $conf['expiry_time'] == "0" || !isset($conf['expiry_time']) || $conf['expiry_units'] == "" || $conf['expiry_units'] == "0" || !isset($conf['expiry_units']))
	{
		if($output == "text")
		{
			return $lang['cards_not_exp_no_val'];
		}
		else
		{
			return false;
		}
	}

	$time = time();

	$die = $conf['expiry_time'] * $conf['expiry_units'];

	if (!$DB->query("DELETE FROM `" . $conf['dbprefix'] . "sent_cards` WHERE date + $die < $time"))
	{
		error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	if($output == "text")
	{
		return basic_output("" . $DB->affected() . $lang['cards_exp'], $lang['expirer']);
	}
}

function send_mail($what, $who, $sender_name=0, $sender_email=0, $code=0, $date=0, $ip=0)
{
global $conf, $lang, $version, $DB;

	require $conf['base_dir'] . "lang/" . $conf['default_pub_lang'] . "/email.php";

	if($conf['use_mail_smtp'] == "smtp")
	{
		require $conf['dir'] . "source/modules/smtp.php";
	}

	$sender_name = $sender_name != "0" ? $sender_name : $conf['email_from'];
	$sender_email = $sender_email != "0" ? $sender_email : $conf['email_from'];
	
	
	//We only require the $date variable for pickup and admin notifications
	if($date == "0")
	{
		unset($date);
	}
	
	//We only require the $ip variable for admin notifications
	if($ip == "0")
	{
		unset($ip);
	}

	foreach($who as $recip)
	{
		if($recip != "")
		{
			switch ($what)
			{
				case 'send_card':
				$subject = $lang['email_subject_recip'];
				$body = $lang['email_body_recip'];
				$body = preg_replace("/{{recip}}/i", $recip, $body);
				$body = preg_replace("/{{email}}/i", $conf['email_from'], $body);
				$body = preg_replace("/{{sender_name}}/i", $sender_name, $body);
				$body = preg_replace("/{{sender_email}}/i", $sender_email, $body);
				$body = preg_replace("/{{url}}/i", $conf['url'], $body);
				$body = preg_replace("/{{card_id}}/i", $code, $body);
				break;

				case 'send_notification':
				$subject = $lang['email_subject_notification'];
				$body = $lang['email_body_notification'];
				$body = preg_replace("/{{sender_email}}/i", $recip, $body);
				$body = preg_replace("/{{sender_name}}/i", $sender_name, $body);
				$body = preg_replace("/{{date}}/i", date($conf['date_format'], $date), $body);
				$body = preg_replace("/{{url}}/i", $conf['url'], $body);
				break;

				case 'resend_validation':
				$sender_name = $conf['email_from'];
				$subject = $lang['email_subject_resend'];
				$body = $lang['email_body_resend'];
				$body = preg_replace("/{{url}}/i", $conf['url'], $body);
				$body = preg_replace("/{{recip}}/i", $recip, $body);
				$body = preg_replace("/{{card_id}}/i", $code, $body);
				break;

				case 'test_email':
				//$sender_name = $conf['email_from'];
				$subject = $lang['email_subject_test_email'];
				$body = $lang['email_body_test_email'];
				$body = preg_replace("/{{recip}}/i", $conf['email_from'], $body);
				$body = preg_replace("/{{sender_name}}/i", $sender_name, $body);
				$body = preg_replace("/{{ip}}/i", $ip, $body);
				$body = preg_replace("/{{date}}/i", date($conf['date_format'], $date), $body);
				break;

				default:
				die("Invalid mail function call (<b>" . $what . "</b>) - mail not sent");
				break;
			}

			$body = preg_replace("/<<>>/", "\n\r", $body);
			$body = stripslashes($body);
			$body .= "\r\n\r\n" . str_repeat("-=", 20) . "\r\nPowered by WebCards v" . $version . "\r\n" . str_repeat("-=", 20) . "";

			//$headers = "From: \"WebCards Mailer <" . $sender_email . ">\"\r\n";
			
			$headers = "From: \"WebCards Mailer\" <" . $sender_email . ">\r\n";
			$headers .= "Content-Type: text/plain; charset=us-ascii\r\n";

			if($what == "send_card" && $conf['cc_address'] != "")
			{
				$headers .= "cc: " . $conf['cc_address'] . "\r\n";
			}

			$headers .= "X-Report-Abuse: " . $conf['email_from'] . "\r\n";
			$headers .= "X-Mailer: WebCards/" . $version . "\r\n";
			$headers .= "X-PHP-Version: PHP/" . phpversion();

			if($conf['use_mail_smtp'] == "smtp")
			{
				$smtp = new smtp_client($smtp_host, $smtp_port);
				$smtp->email($sender_name, $recip, $recip, $headers, $subject, $body);
				$smtp->send();
				//echo "<pre>$smtp->email(" . $sender_name . ", " . $recip . ", " . $recip . ", " . $headers . ", " . $subject . ", " . $body . ");</pre>";
			}
			else
			{

				//echo "<pre>mail(" . $recip . ", " . $subject . ", " . $body . ", " . $headers . ");</pre>";
				if(!mail($recip, $subject, $body, $headers))
				{
					return false;
				}
			}

			//Log the email in the database
			if (!$DB->query("INSERT INTO " . $conf['dbprefix'] . "email_logs (id, email_type, date, sender_email, sender_ip, recip_email, subject, content) VALUES (\"\", \"" . $what . "\", \"" . time() . "\", \"" . $sender_name . "\", \"" . getip() . "\", \"" . $recip . "\", \"" . $subject . "\", \"". $body . "\")"))
			{
				error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
			}
		}
	}

	return true;
}

function getServerLoad()
{
	if(get_cfg_var('safe_mode') || stristr(PHP_OS, 'WIN'))
	{
		return 0;
	}
	if(@file_exists('/proc/loadavg'))
	{
		$file = @fopen('/proc/loadavg', 'r');
		if(!$file)
		{
			return 0;
		}
		$load = fread($file, 6);
		@fclose($file);
		$loadavg = explode(' ', $load);
	}
	else
	{
		$load = exec('uptime');
		$load = split('load averages?: ', $load);
		$loadavg = explode(',', $load[1]);
	}
	return trim($loadavg[0]);
}

function copy_dir($oldname, $newname)
{
global $lang;

	if(is_dir($newname))
	{
		return error($lang['dir_exists'], $lang['back_choose_new_name']);
	}


	if(is_file($oldname))
	{
		$perms = fileperms($oldname);
		if(!copy($oldname, $newname))
		{
			return error($lang['cannot_copy_file'] . $old_name, $lang['check_dir_perms']);
		}
		if(!chmod($newname, $perms))
		{
			return error($lang['cannot_chmod'] . $new_name);
		}
	}
	else
	{
		if(is_dir($oldname))
		{
		     my_dir_copy($oldname, $newname);
   		}
		else
		{
			return error($lang['cannot_copy_dir'] . $old_name, $lang['check_dir_perms']);
		}
	}
}
     
function my_dir_copy($oldname, $newname) 
{
global $lang;

	if(!is_dir($newname))
	{
		mkdir($newname);
	}
	$dir = opendir($oldname);
	while($file = readdir($dir))
	{
		if($file == "." || $file == "..")
		{
			continue;
		}
		copy_dir("$oldname/$file", "$newname/$file");
	}
	closedir($dir);
}

function delete_file($file) {
global $lang;
//This is recursive and will delete files and folders

	chmod($file,0777);
	if (is_dir($file))
	{
		$handle = opendir($file); 
		while($filename = readdir($handle))
		{
			if ($filename != "." && $filename != "..")
			{
				delete_file($file."/".$filename);
			}
		}
	closedir($handle);
	rmdir($file);
	}
	else
	{
		unlink($file);
	}
}

function gd_version()
{
//Detect GD version - returns either (version) 1 or 2

	$gd_version = 1;
	if(@function_exists(gd_info)) // Only valid for PHP >= 4.3.0
	{
		$gd_info = gd_info();
		if(strpos($gd_info['GD Version'], "2.") === false)
		{
			$gd_version = 1;
		}
		else
		{
			$gd_version = 2;
		}
	}
	else // Do it the long-winded manual way
	{
		ob_start();
		phpinfo(INFO_MODULES);
		$php_data = strip_tags(ob_get_contents()); //We are dealing purely with text here, no layout
		@ob_end_clean();
		if(preg_match("/GD Version[\s].+\([\d].+\)/i", $php_data, $matches)) //Explanation of Regex - Find string "GD version", folowed by a space, the one or more of any characters, then an open bracket, then a digit (version number), then any characters repeated any number of times and finally a close bracket
		{
			if(strpos($matches['0'], "2.") === false) // Did not find the string "2."
			{
				$gd_version = 1;
			}
			else
			{
				$gd_version = 2;
			}
		}
		else
		{
			$gd_version = 1;
		}
	}
	return $gd_version;
}

function get_stats($area)
{
global $DB, $conf, $time, $version, $HTTP_POST_VARS, $HTTP_GET_VARS, $lang, $stimer;

	if ($conf['render_time'] == $area || $conf['render_time'] == "b")
	{
		$etimer = explode( ' ', microtime() );
		$etimer = $etimer[1] + $etimer[0];

		$debug_stats .= "[ " . $lang['ex_time'] . ": " . sprintf("%0.4f", ($etimer-$stimer)) . " ]&nbsp;&nbsp;";
	}
	if ($conf['query_count'] == $area || $conf['query_count'] == "b")
	{
		$debug_stats .= "[ " . $lang['query_count'] . ": " . $DB->finished() . " ]&nbsp;&nbsp;";
	}
	if(getServerLoad() > 0)
	{
		if ($conf['server_load'] == $area || $conf['server_load'] == "b")
		{
			$debug_stats .= "[ " . $lang['s_load'] . ": " . getServerLoad() . " ]&nbsp;&nbsp;";
		}
	}
	if($conf['buffer'] == "y")
	{
		$debug_stats .= "[ " . $lang['gzip'] . $lang['enabled'] . " ]&nbsp;&nbsp;";
	}
	else
	{
		$debug_stats .= "[  " . $lang['gzip'] . $lang['disabled'] . " ]&nbsp;&nbsp;";
	}
	$output = "<table class=\"stats\" width=\"100%\">
	<tr>
	<td class=\"stats\" align=\"center\">" . $debug_stats . "</td>
	</tr>
	</table>";

	if ($conf['sql_show'] == $area || $conf['sql_show'] == "b")
	{
		$DB->query_array = sql_highlight($DB->query_array);
		if(count($DB->query_array) > 0)
		{
			$sql_stats = "<table class=\"noborder\" cellpadding=\"3\" width=\"100%\">\n<tr>\n<td>" . $lang['queries_used'] . ":<br />";
			foreach($DB->query_array as $queries)
			{
				$sql_stats .=  "" . $queries . "<br />";
			}
			$sql_stats .= "</td>\n</tr>\n</table>";
		}
	}
	
	if ($conf['get_post_show'] == $area || $conf['get_post_show'] == "b")
	{
		$get_post_stats = "<table class=\"noborder\" cellpadding=\"8\" width=\"100%\">\n<tr>\n<td>" . $lang['get_post'] . ":<br />";
		$get_post_stats .= "GET:<br />";
		foreach($HTTP_GET_VARS as $k => $v)
		{
			//We need to go one level deeper otherwhise arrays would just display as "Array"
			if(!is_array($v))
			{
				$get_post_stats .=  "<b>" . $k . "</b> = " . stripslashes(htmlentities($v)) . "<br />";
			}
			else
			{
				$get_post_stats .= "<b>" . $k . "</b> = Array(";
				foreach($HTTP_GET_VARS[$k] as $l => $w)
				{
					$get_post_stats .= " <b>[" . $l . "]</b> = " . stripslashes(htmlentities($w)) . ",";
				}
				$get_post_stats .= ")<br />";
			}
				
		}
		$get_post_stats .= "<br />POST:<br />";
		foreach($HTTP_POST_VARS as $k => $v)
		{
			if(!is_array($v))
			{
				$get_post_stats .=  "<b>" . $k . "</b> = " . stripslashes(htmlentities($v)) . "<br />";
			}
			else
			{
				$get_post_stats .= "<b>" . $k . "</b> = Array(";
				foreach($HTTP_POST_VARS[$k] as $l => $w)
				{
					$get_post_stats .= " <b>[" . $l . "]</b> = " . stripslashes(htmlentities($w)) . ",";
				}
				$get_post_stats .= ")<br />";
			}
		}
		$sql_stats .= "</td>\n</tr>\n</table>";
	}
	
	
	return $output . $sql_stats . $get_post_stats;
}

function sql_highlight($sql)
{
	$sql = preg_replace("/(\+| &lt; |\-|=|'|==|\!=|LIKE|NOT LIKE)/i", "<span style=\"color:red;\">\\1</span>", $sql);
	$sql = preg_replace("/(SELECT|INSERT|UPDATE|DELETE|ALTER TABLE|DROP|SHOW|REPAIR|OPTIMIZE|CHECK|ANALYZE)/i", "<span style=\"color:red; font-weight:bold;\">\\1</span>", $sql);
	$sql = preg_replace("/(FROM|INTO)\s{1,}(\S+?)$/i", "<span style=\"color:blue; font-weight:bold;\">\\1</span> <span style=\"color:orange;\">\\2</span>", $sql);
	$sql = preg_replace("/(TRUNCATE TABLE)\s{1,}(\S+?)$/i", "<span style=\"color:red; font-weight:bold;\">\\1</span> <span style=\"color:orange;\">\\2</span>", $sql);
	$sql = preg_replace("/(FROM|INTO)\s{1,}(\S+?)\s{1,}/is", "<span style=\"color:blue; font-weight:bold;\">\\1</span> <span style=\"color:orange;\">\\2</span> ", $sql);
	$sql = preg_replace("/(\S+?)\s{1,}(SET)/i", "<span style=\"color:orange;\">\\1</span> <span style=\"color:blue; font-weight:bold;\">SET</span>", $sql);
	$sql = preg_replace("/(WHERE|MODIFY|CHANGE|\s{1,}AS\s{1,}|DISTINCT|\s{1,}IN\s{1,}|ORDER BY|VALUES)/i" , "<span style=\"color:green; font-weight:bold;\">\\1</span> ", $sql);
	$sql = preg_replace("/(\s{1,}ASC|\s{1,}DESC($|\s{1,}))/i", "<span style=\"color:purple;\">\\1</span> ", $sql);
	$sql = preg_replace("/count\((\S+?)\)/i", "<span style=\"color:purple;\">count(</span>\\1<span style=\"color:purple;\">)</span>", $sql);
	$sql = preg_replace("/LIMIT\s*(\d+)\s*,\s*(\d+)/i", "<span style=\"color:green\">LIMIT</span> <span style=\"color:purple\">\\1, \\2</span>", $sql);
	return $sql;
}

function clean_field($field)
{

	$to_find = array (	"/</",
				"/>/",
				"/<script[^>]*?>.*?<\/script>/si",
               			"/\"/"
					);
	$to_replace = array (	"&lt;",
              			"&gt;",
				"",
           			"&quot;"
  					);

	return stripslashes(preg_replace($to_find, $to_replace, $field));
}

function parse_tags($data)
{
	$data = preg_replace("#\[b\](.+?)\[/b\]#is", "<b>\\1</b>", $data);
	$data = preg_replace("#\[i\](.+?)\[/i\]#is", "<i>\\1</i>", $data);
	$data = preg_replace("#\[u\](.+?)\[/u\]#is", "<u>\\1</u>", $data);
	$data = preg_replace("#\[s\](.+?)\[/s\]#is", "<span style=\"text-decoration:line-through;\">\\1</span>", $data);

	return $data;
}

function sql_version()
{
global $DB, $lang;

	if (!$DB->query("SELECT VERSION() as version"))
	{
		error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	$row = $DB->fetch_array();
	
	$sql_version = $row['version'];
	
	return $sql_version;

}

function basic_output($text, $header="") //Function to give the basic table layout without having to do it by hand
{
	$output = "<table width=\"100%\" cellpadding=\"15\" cellspacing=\"2\" class=\"config\">";
	if($header != "")
	{
		$output .= "<tr>\n<td class=\"theader\">\n" . $header . "</td></tr>\n";
	}
	$output .= "<tr><td class=\"ad_row\">";
	$output .= $text;
	$output .= "</td></tr></table>\n";
	
return $output;
}

?>