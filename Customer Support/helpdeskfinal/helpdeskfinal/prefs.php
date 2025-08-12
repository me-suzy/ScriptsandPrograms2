<?php
//
// Project: Help Desk support system
// Description: User preferences page
//
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.

require_once "includes/auth.php";
require_once "includes/db.php";
require_once "includes/tpl.php";
require_once "includes/funcs.php";
require_once "includes/const.php";

$page_title = "User Preferences";

// User authentication
if(!$hduser['logged_in'])
{
	// If user logged in, show message & redirect to index
	dialog("You must be logged in to access this page.", 
				$page_title, "Log In", "login.php", true, true);
}

$config = config_load();
$tpl_prefs = new tpl("tpl/prefs.tpl");
#
# Update user preferences ------------------------------------------------------------------------------------------
#
if(isset($_POST['btn_updprefs']))
{
	$user_id = $hduser['user_id'];
	
	$user_firstname = $_POST['user_firstname'];
	$user_lastname	= $_POST['user_lastname'];
	$user_email		= $_POST['user_email'];
	$user_phone		= $_POST['user_phone'];
	$user_password	= $_POST['user_password'];
	$user_password2	= $_POST['user_password2'];
	$user_notify	= intval($_POST['user_notify']);
	$user_timezone	= $_POST['user_timezone'];

	//
	// Check if all fields filled with right values
	//
	
	// Check if first name is filled
	if(empty($user_firstname))
	{
		$error_firstname = "Field is required.";
		$halt = true;
	}
	
	// Check if last name is filled
	if(empty($user_lastname))
	{
		$error_lastname = "Field is required.";
		$halt = true;
	}
	
	// Check if email field is filled
	if(empty($user_email))
	{
		$error_email = "A valid e-mail address must be provided.";
		$halt = true;
	}
	else
	{
		// Check if email format is x@x.x
		if(!preg_match("/[a-zA-Z0-9-_\.]+@[a-zA-Z0-9-_\.]+\.[a-zA-Z0-9]+/", $user_email))
		{
			$error_email = "Invalid e-mail address.";
			$halt = true;
		}
	}
	
	// Check if phone field is filled | USERS ONLY
	if($hduser['user_level'] == RANK_USER)
	{
		if(empty($user_phone))
		{
			$error_phone = "Field is required.";
			$halt = true;
		}
		else
		{
			// Check if phone number consits of digits and dashes only
			if(!preg_match("/^[0-9]([0-9]|[\-])*[0-9]$/", $user_phone))
			{
				$error_phone = "Phone number can consist only of numbers and dashes.";
				$halt = true;
			}
		}
	}

	// Check if user wants to change the password
	if(!empty($user_password) || !empty($user_password2))
	{
		// Check if password is within the required length
		if(strlen($user_password) < 3 || strlen($user_password) > 25)
		{
			$error_password = "Password length must be between 3 and 25 characters.";
			$halt = true;
		}
		if(empty($user_password2))
		{
			$error_password2 = "You must provide your current password.";
			$halt = true;
		}

		// Check if user typed the right password
		if(empty($error_password) && empty($error_password2))
		{
			$r_pass = mysql_query("SELECT user_id FROM $TABLE_USERS WHERE
								   user_password = md5('$user_password2') AND
								   user_id = '$user_id'") or
							error("Cannot check user password");

			if(mysql_num_rows($r_pass)<1)
			{
				$error_password2 = "Wrong password.";
				$halt = true;
			}
			else
			{
				$sql_pass_snip =",user_password = md5('$user_password')";	
			}
		}	

	}

	// If there were any errors, show them to user
	if($halt)
	{
		$tpl_prefs_tags = array("error_firstname"	=> $error_firstname,
								"error_lastname"	=> $error_lastname,
								"error_email"		=> $error_email,
								"error_phone"		=> $error_phone,
								"error_password"	=> $error_password,
								"error_password2"	=> $error_password2);
		$tpl_prefs->template = replace_tags($tpl_prefs_tags, $tpl_prefs->template);
	}
	else
	{
		// Update user database entry
		mysql_query("UPDATE $TABLE_USERS SET
					 user_firstname	= '$user_firstname',
					 user_lastname	= '$user_lastname',
					 user_email		= '$user_email',
					 user_phone		= '$user_phone',
					 user_notify	= '$user_notify',
					 user_timezone	= '$user_timezone'
					 $sql_pass_snip
					 WHERE user_id=$user_id") or
			error("Cannot update user info.");
			
		// If user changed his password, update cookie info
		// so he doesn`t get logged out
		if(!empty($sql_pass_snip))
		{
			$hdcookie	= unserialize(stripslashes($_COOKIE['hd_userdata']));

			$hdcookie['auth'] = md5($user_password);

			// Now set the new cookie		
			setcookie("hd_userdata", serialize($hdcookie));
		}

		dialog("Preferences have been updated successfully.", $page_title,"Index", "index.php");
	}
}
#
# Show preferences -------------------------------------------------------------------------------------------------
#

// Load user info
$user_id = intval($hduser['user_id']);

$r_user = mysql_query("SELECT * FROM $TABLE_USERS WHERE user_id=$user_id") or
				error("Cannot load user info.");
				
$db_user = mysql_fetch_object($r_user);

$cur_notify[$db_user->user_notify] = "selected";
$lst_notify = "<option value=\"1\" $cur_notify[1]>Yes</option>\n
			   <option value=\"0\" $cur_notify[0]>No</option>";
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
$user_timezone = ($db_user->user_timezone!=NULL) ? ($db_user->user_timezone) : ($config['helpdesk_timezone']);
			   
$tpl_prefs_tags = array("user_name"			=> $db_user->user_name,
						"user_firstname"	=> $db_user->user_firstname,
						"user_lastname"		=> $db_user->user_lastname,
						"user_email"		=> $db_user->user_email,
						"user_phone"		=> $db_user->user_phone,
						"user_notify"		=> $lst_notify,
						"$user_timezone"	=> "selected",
						"gmt_time"			=> gmdate("d M Y H:i"),
						"error_firstname"	=> "",
						"error_lastname"	=> "",
						"error_email"		=> "",
						"error_phone"		=> "",
						"error_password"	=> "",
						"error_password2"	=> "" );
$tpl_prefs->parse($tpl_prefs_tags);

echo build_page(content_box($tpl_prefs->parsed, $page_title), $page_title);
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
?>