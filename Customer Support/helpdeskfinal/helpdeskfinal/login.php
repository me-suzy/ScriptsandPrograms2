<?php
//
// Project: Help Desk support system
// Description: User log in page
//
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
$page_title = "Log in";

require_once "includes/auth.php";
require_once "includes/db.php";
require_once "includes/funcs.php";
require_once "includes/tpl.php";

// User authentication
if($hduser['logged_in'] && empty($HTTP_GET_VARS))
{
	// If user logged in, show message & redirect to index
	die(dialog("You are already logged in.", 
				$page_title, "Index", "index.php", true, true));
}

//
// User logs out ----------------------------------------------------------------------------------------------------
//
if($_GET['action'] == "logout")
{
	//
	// Check if user logged in, if not show error
	//
	if(!$hduser['logged_in'])
	{
		die(dialog("You are not logged in.", $page_title, "Index", "index.php", true, true));
	}
	else
	{
		setcookie("hd_userdata", "", time()-36000);
		die(dialog("You are now logged out.", $page_title, "Log In", "login.php", true, true));
	}
}
#
# Lost password: Show form ------------------------------------------------------------------------------------------
#
elseif($_GET['action'] == "lostpassword" && empty($_POST) && empty($_GET['key']))
{
	$page_title = "Lost password";

	$tpl_lost = new tpl("tpl/lost_password.tpl");

	// Delete, "wrong info" row
	fragment_delete("info_error", $tpl_lost->template);
	
	echo build_page(content_box($tpl_lost->template, $page_title), $page_title);
}
#
# Lost password: Check validity and mail instructions ---------------------------------------------------------------
#
elseif($_GET['action'] == "lostpassword" && isset($_POST['btn_submit']))
{
	$page_title = "Lost password";
	
	$user_name	= $_POST['user_name'];
	$user_email	= $_POST['user_email'];
	
	$r_user = mysql_query("SELECT * FROM $TABLE_USERS WHERE user_name='$user_name' AND user_email='$user_email' LIMIT 1") or
					error("Cannot check user info validity.");
					
	// If user does not exist, show error
	if(mysql_num_rows($r_user) < 1)
	{
		$tpl_lost = new tpl("tpl/lost_password.tpl");

		die(build_page(content_box($tpl_lost->template, $page_title)));
	}
	
	$db_user = mysql_fetch_object($r_user);
	
	//
	// Build and send mail to user
	//
	$key = md5($db_user->first_name . $db_user->last_name . $db_user->user_name . $db_user->user_password);
	$url_changepass = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . "?action=lostpassword&id=$db_user->user_id&key=$key";

	$tpl_mail = new tpl("tpl/mail_lostpassword.tpl");
	
	$tpl_mail_tags = array( "user_name"		 => $db_user->user_name,
							"url_changepass" => $url_changepass );
	$tpl_mail->parse($tpl_mail_tags);
	
	user_mail($db_user->user_id, $tpl_mail->parsed, "Password changing instructions");
	
	dialog("Your instructions have been mailed. Please check your mail account in a while.", $page_title, "Log In", "login.php");
}
//
// Lost password: Check new password and update db
//
//elseif(isset($_POST['btn_changepass']) && !empty($_GET['key']) && !empty($_GET['id']))
//{
//
//}
//
// Lost password: Show new password form ----------------------------------------------------------------------------
//
elseif($_GET['action'] == "lostpassword" && !empty($_GET['id']) && !empty($_GET['key']))
{
	$page_title = "Change password";

	$user_id = intval($_GET['id']);
	$key	 = $_GET['key'];
	
	// Get user info
	$r_user = mysql_query("SELECT * FROM $TABLE_USERS WHERE user_id=$user_id") or
					error("Cannot get user info.");
	$db_user = mysql_fetch_object($r_user);
	
	// Build user key and check for validity
	$user_key = md5($db_user->first_name . $db_user->last_name . $db_user->user_name . $db_user->user_password);
	if($user_key != $key)
		dialog("Wrong user key.", $page_title, "Log In", "login.php");
	
	// If user typed new pass
	if(isset($_POST['btn_changepass']))
	{
		$user_pass	= $_POST['user_pass'];
		$user_pass2 = $_POST['user_pass2'];

		// Check password length
		if(strlen($user_pass) < 3 || strlen($user_pass) > 25)
		{
			$error = "Your password length must be between 3 and 25 characters.";
		}
		else
		{
			// Check if password and retyped password match
			if($user_pass != $user_pass2)
				$error = "Passwords do not match.";
		}
		
		if($error)
		{
			$tpl_changepass = new tpl("tpl/lost_password_new.tpl");
			
			$tpl_changepass_tags = array("pass_error" => $error);
			
			$tpl_changepass->parse($tpl_changepass_tags);
			
			die(build_page(content_box($tpl_changepass->parsed, $page_title), $page_title));
		}
		
		$new_pass = md5($user_pass);
		
		// Update db
		mysql_query("UPDATE $TABLE_USERS SET user_password='$new_pass' WHERE user_id=$user_id") or
			error("Cannot change user password.");
			
		dialog("Your password has been changed successfully.", $page_title, "Log In", "login.php");
	}
	// Show form
	$tpl_changepass = new tpl("tpl/lost_password_new.tpl");
	
	fragment_delete("pass_error", $tpl_changepass->template);
	
	die(build_page(content_box($tpl_changepass->template, $page_title), $page_title));
}
//
// User submitted the login form, set the cookie and proceed to index -----------------------------------------------
//
elseif(isset($_POST['btn_login']))
{
	$user_name		= $_POST['user_name'];
	$user_password	= md5($_POST['user_password']);

	
	// Check if username and password are correct
	$r_user = mysql_query("SELECT user_id, user_password FROM $TABLE_USERS WHERE user_name='$user_name' AND user_password='$user_password'") or
					error("Cannot verify user info.");
					
	// if user info is correct, set cookie
	if(mysql_num_rows($r_user))
	{
		$cookie_expire = ($_POST['chk_autologin']) ? (time() + 60*60*24*30) : (0);

		$db_user = mysql_fetch_object($r_user);
		
		$user['user_id']	= $db_user->user_id;
		$user['auth']		= $db_user->user_password;
		
		setcookie("hd_userdata", serialize($user), $cookie_expire);
		
		// Redirect user to index
		header("Location: index.php");
	}
	else
	{
		// Show wrong password or username page
		$tpl_login = new tpl("tpl/login.tpl");
		
		echo build_page(content_box($tpl_login->template, $page_title));
	}

}
//
// Check if user logged in. If not show log in form else redirect to index-------------------------------------------
//
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
else
{
	if($user['logged'])
	{
		header("Location: index.php");
		exit();
	}
	else
	{
		$tpl_login = new tpl("tpl/login.tpl");
		
		// Delete the login error message
		fragment_delete("login_error", $tpl_login->template);
		
		echo build_page(content_box($tpl_login->template, $page_title), $page_title);
	}
}
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
?>
