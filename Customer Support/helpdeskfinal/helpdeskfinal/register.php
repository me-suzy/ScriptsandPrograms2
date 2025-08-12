<?php
//
// Project: Help Desk support system
// Description: User registration page
//
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
$page_title = "User registration";

require_once "includes/auth.php";
require_once "includes/db.php";
require_once "includes/tpl.php";

//
// Start user authentication
if($hduser)
{
	// If user logged in, show message & redirect to index
	dialog("You are already registered. You need to log out to re-register.", 
				$page_title, "Index", "index.php", true, true);
}

// Load template files
$tpl_register = new tpl("tpl/register.tpl");

#
# User submitted the registration form, validate, create entry in the database and proceed --------------------------
#
if(isset($HTTP_POST_VARS['btn_register']))
{
	$user_name		= $_POST['user_name'];
	$user_firstname = $_POST['user_firstname'];
	$user_lastname 	= $_POST['user_lastname'];
	$user_password 	= $_POST['user_password'];
	$user_password2 = $_POST['user_password2'];
	$user_email		= $_POST['user_email'];
	$user_phone		= $_POST['user_phone'];
	
	
	//
	// Check if all fields are filled with right values
	//

	// Check if username field filled
	if(empty($user_name))
	{
		$error_username = "Field is required.";
		$halt = true;
	}
	else
	{
		// Check if username within required length
		if(strlen($user_name) < 3 || strlen($user_name) > 25)
		{
			$error_username = "Username length must be between 3 and 25 characters.";
			$halt = true;
		}
		else
		{
			// Check if provided username already exists
			$r_userexists = mysql_query("SELECT * FROM $TABLE_USERS WHERE user_name='$user_name'") or
								error("Cannot get user information.");

			if(mysql_num_rows($r_userexists))
			{
				$error_username = "Username already used by someone else.";
				$halt = true;
			}
		}
	}
	
	// Check if first name field is filled
	if(empty($user_firstname))
	{
		$error_firstname = "Field is required.";
		$halt = true;
	}
	
	// Check if last name field is filled
	if(empty($user_lastname))
	{
		$error_lastname = "Field is required.";
		$halt = true;
	}
	
	// Check if password field is filled
	if(empty($user_password))
	{
		$error_password = "You must type a password.";
		$halt = true;
	}
	else
	{
		// Check if password is within the required length
		if(strlen($user_password) < 3 || strlen($user_password) > 25)
		{
			$error_password = "Password length must be between 3 and 25 characters.";
			$halt = true;
		}
		else
		{
			// Check if password matches confirmation password
			if($user_password != $user_password2)
			{
				$error_password2 = "Passwords do not match.";
				$halt = true;
			}
		}
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
	
	// Check if phone field is filled
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
	
	//
	// If errors occured during the validation process, show them to user
	// else proceed to database user entry creation
	if($halt)
	{
		$tpl_register_tags = array( "error_username"	=> $error_username,
									"error_firstname"	=> $error_firstname,
									"error_lastname"	=> $error_lastname,
									"error_password"	=> $error_password,
									"error_password2"	=> $error_password2,
									"error_email"		=> $error_email,
									"error_phone"		=> $error_phone,
									
									"user_name"			=> $user_name,
									"user_firstname"	=> $user_firstname,
									"user_lastname"		=> $user_lastname,
									"user_email"		=> $user_email,
									"user_phone"		=> $user_phone );

		$tpl_register->parse($tpl_register_tags);

		echo build_page(content_box($tpl_register->parsed, $page_title), $page_title);
	}
	else
	{
		// Create database entry
		$sql_adduser = "INSERT INTO $TABLE_USERS
						(user_name, user_firstname, user_lastname, user_password, user_email, user_phone, user_level) VALUES
						('$user_name', '$user_firstname', '$user_lastname', md5('$user_password'), '$user_email', '$user_phone', 0)";
		$r_adduser = mysql_query($sql_adduser) or
						error("Cannot add user to the database.");
						
		// Show "registration completed" dialog
		dialog("The registration process has been completed.
				You can now log-in using the info you provided.", $page_title, "Log in", "login.php", true);
	}
}
#
# Show the user registration page -----------------------------------------------------------------------------------
# Loaded by default when file is called
else
{
	// Hide the form data error message on top of the table
	fragment_delete("form_error", $tpl_register->template);

	// Hide the error tags on each field and the tags inside the text boxes
	$tpl_register_tags = array( "error_username"	=> "",
								"error_firstname"	=> "",
								"error_lastname"	=> "",
								"error_password"	=> "",
								"error_password2"	=> "",
								"error_email"	 	=> "",
								"error_phone"		=> "",
								
								"user_name"			=> "",
								"user_firstname"	=> "",
								"user_lastname"		=> "",
								"user_email"		=> "",
								"user_phone"		=> "" );

	$tpl_register->parse($tpl_register_tags);

	echo build_page(content_box($tpl_register->parsed, $page_title), $page_title);
	// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
}
?>