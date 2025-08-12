<?php
//
// Project: Help Desk support system (Admin panel)
// Description: 
// 1. Users page - Add/edit/delete users
//
require_once "includes/auth.php";
require_once "includes/db.php";
require_once "includes/tpl.php";
require_once "includes/funcs.php";
require_once "includes/const.php";

$page_title = "Admin Panel - Users";

// User authentication
if($hduser['user_level'] != RANK_ADMIN)
{
	// If user NOT logged in, show message & redirect to index
	dialog("Your clearance is not high enough to access this section.", $page_title);
}

$tpl_users = new tpl("tpl/admin_users.tpl");

#
# Create user -------------------------------------------------------------------------------------------------------
#
if(isset($_POST['btn_createuser']))
{
	$user_name			= $_POST['user_name'];
	$user_firstname		= $_POST['user_firstname'];
	$user_lastname		= $_POST['user_lastname'];
	$user_password		= $_POST['user_password'];
	$user_password2 	= $_POST['user_password2'];
	$user_email			= $_POST['user_email'];
	$user_phone			= $_POST['user_phone'];
	$user_level			= intval($_POST['user_level']);
	$user_ticket_cat	= intval($_POST['user_ticket_cat']);
	$user_ticket_pr		= intval($_POST['user_ticket_priority']);
	$user_self_only		= intval($_POST['user_self_only']);

	//
	// Check if all fields are filled with right values
	//

	// Check if username field filled
	if(empty($user_name))
		$add_errors .= "Username field is required.<br>";
	else
	{
		// Check if username within required length
		if(strlen($user_name) < 3 || strlen($user_name) > 25)
			$add_errors .= "Username length must be between 3 and 25 characters.<br>";
		else
		{
			// Check if provided username already exists
			$r_userexists = mysql_query("SELECT * FROM $TABLE_USERS WHERE user_name='$user_name'") or
								error("Cannot get user information.");

			if(mysql_num_rows($r_userexists))
				$add_errors .= "Username already used by someone else.<br>";
		}
	}
	
	// Check if first name field is filled
	if(empty($user_firstname))
		$add_errors .= "First Name field is required.<br>";
	
	// Check if last name field is filled
	if(empty($user_lastname))
		$add_errors .= "Last Name field is required.<br>";
	
	// Check if password field is filled
	if(empty($user_password))
		$add_errors .= "You must type a password.<br>";
	else
	{
		// Check if password is within the required length
		if(strlen($user_password) < 3 || strlen($user_password) > 25)
			$add_errors .= "Password length must be between 3 and 25 characters.<br>";
		else
		{
			// Check if password matches confirmation password
			if($user_password != $user_password2)
			$add_errors .= "Passwords do not match.<br>";
		}
	}

	// Check if email field is filled
	if(empty($user_email))
		$add_errors .= "A valid e-mail address must be provided.<br>";
	else
	{
		// Check if email format is x@x.x
		if(!preg_match("/[a-zA-Z0-9-_\.]+@[a-zA-Z0-9-_\.]+\.[a-zA-Z0-9]+/", $user_email))
			$add_errors .= "Invalid e-mail address.<br>";
	}
	
	// Check if phone field is filled | USERS ONLY
	if($user_level == RANK_USER)
	{
		if(empty($user_phone))
		{
			$add_errors .= "Phone field is required.<br>";
		}
		else
		{
			// Check if phone number consits of digits and dashes only
			if(!preg_match("/^[0-9]([0-9]|[\-])*[0-9]$/", $user_phone))
				$add_errors .= "Phone number can consist only of numbers and dashes.<br>";
		}
	}

	// If there were errors during the checking process, show them
	if(!empty($add_errors))
		dialog("<b>Errors:</b><BR> $add_errors", $page_title);

	if($user_level==RANK_USER || $user_level==RANK_ADMIN)
	{
		$user_ticket_cat = 0;
		$user_ticket_pr	 = 0;
	}
		
	mysql_query("INSERT INTO $TABLE_USERS 
				 (user_name, user_firstname, user_lastname, user_password, user_email,
				  user_phone, user_level, user_ticket_priority, user_ticket_cat, user_self_only)
				 VALUES
				 ('$user_name', '$user_firstname', '$user_lastname', md5('$user_password'), '$user_email',
				  '$user_phone', '$user_level', '$user_ticket_pr', '$user_ticket_cat', '$user_self_only')") or
		error("Cannot create user.");
	
	dialog("User created.", $page_title);			 
}
#
# Delete user -------------------------------------------------------------------------------------------------------
#
elseif($_GET['action'] == "delete_user" && !empty($_GET['user_id']))
{
	$user_id = intval($_GET['user_id']);
	
	// Get user replies
	$r_replies = mysql_query("SELECT * FROM $TABLE_REPLIES WHERE reply_author=$user_id") or
					error("Cannot get user replies list.");

	while($db_replies = mysql_fetch_object($r_replies))
	{
		reply_delete($db_replies->reply_id);
	}
	
	// Delete user
	mysql_query("DELETE FROM $TABLE_USERS WHERE user_id=$user_id") or
		error("Cannot delete user.");
	
	dialog("User deleted.", $page_title, "Back to User Admin", "admin_users.php");
}
#
# Edit user: Search for user and build list -------------------------------------------------------------------------
#
elseif(isset($_GET['btn_finduser'])) 
{
	$user_name		= $_GET['user_name'];
	$user_firstname	= $_GET['user_firstname'];
	$user_lastname	= $_GET['user_lastname'];
	$user_email		= $_GET['user_email'];
	$user_phone		= $_GET['user_phone'];
	$user_level		= intval($_GET['user_level']);
	
	// Check filled fields and build query bits
	$sql_find_snip = "";
	if(!empty($user_name))
		$sql_find_snip .= "AND user_name LIKE '$user_name'";
	if(!empty($user_firstname))
		$sql_find_snip .= "AND user_firstname LIKE '$user_firstname'";
	if(!empty($user_lastname))
		$sql_find_snip .= "AND user_lastname LIKE '$user_lastname'";
	if(!empty($user_email))
		$sql_find_snip .= "AND user_email LIKE '$user_email'";
	if(!empty($user_phone))
		$sql_find_snip .= "AND user_phone LIKE '$user_phone'";
	if($user_level!=-1)
		$sql_find_snip .= "AND user_level = $user_level";

	$r_users = mysql_query("SELECT * FROM $TABLE_USERS WHERE 1 $sql_find_snip ORDER BY user_name, user_level") or
					error("Cannot search user entries.");
	if(mysql_num_rows($r_users)<1)
		dialog("No users matching your criteria were found.", $page_title);					
					
	while($db_users = mysql_fetch_object($r_users))
	{
		switch($db_users->user_level)
		{
		case RANK_ADMIN:
			$user_rank = "(admin)";
			break;
		case RANK_SPECMANAGER:
			$user_rank = "(spec. manager)";
			break;
		default:
			$user_rank = "(user)";
			break;
		}
		
		$lst_users .= "<option value=\"$db_users->user_id\">$db_users->user_name - 
					   $db_users->user_lastname $db_users->user_firstname $user_rank</option>\n";
	}
}
#
# Edit user: Show editable user info --------------------------------------------------------------------------------
#
elseif(isset($_GET['btn_edituser']) && isset($_GET['user_id']) && empty($_POST)) 
{
	$user_id = intval($_GET['user_id']);

	$html_edituser = fragment_get("tbl_edituser", $tpl_users->template);
	
	// Load selected user info
	$r_user = mysql_query("SELECT * FROM $TABLE_USERS WHERE user_id=$user_id") or
					error("Cannot load user info.");
					
	if(mysql_num_rows($r_user)<1)
		dialog("Cannot find user", $page_title);					
				
	$db_user = mysql_fetch_object($r_user);	
	
	// Load ticket categories, priorities and poster level into a listbox and set default vals
	$def_cat[$db_user->user_ticket_cat]		= "selected";
	$def_pr[$db_user->user_ticket_priority]	= "selected";
	$def_lvl[$db_user->user_level]			= "selected";

	$r_cats = mysql_query("SELECT * FROM $TABLE_CATS ORDER BY cat_orderby") or
					error("Cannot load ticket categories.");
	while($db_cats = mysql_fetch_object($r_cats))
	{
		$cat_id = $db_cats->cat_id;
		$lst_updcats .= "<option value=\"$db_cats->cat_id\" $def_cat[$cat_id] >$db_cats->cat_name</option>\n";
	}
	
	$r_prs = mysql_query("SELECT * FROM $TABLE_PRIORITIES ORDER BY priority_orderby") or
					error("Cannot load ticket priorities");
	while($db_prs = mysql_fetch_object($r_prs))
	{
		$priority_id = $db_prs->priority_id;
		$lst_updprs .= "<option value=\"$db_prs->priority_id\" $def_pr[$priority_id] >$db_prs->priority_name</option>\n";
	}
	
	$lst_userlevel = "<option value=\"" . RANK_USER . "\" $def_lvl[0]>User</option>\n
					<option value=\"" . RANK_SPECMANAGER . "\" $def_lvl[2]>Special Manager</option>\n
					<option value=\"" . RANK_ADMIN . "\" $def_lvl[1]>Admin\Manager</option>\n";
	// --------------
	$flg_selfonly = ($db_user->user_self_only) ? ("checked") : ("");
	
	$edituser_tags = array( "user_id"			=> $db_user->user_id,
							"user_name"			=> $db_user->user_name,
							"user_firstname"	=> $db_user->user_firstname,
							"user_lastname"		=> $db_user->user_lastname,
							"user_email"		=> $db_user->user_email,
							"user_phone"		=> $db_user->user_phone,
							"lst_userlevel"		=> $lst_userlevel,
							"lst_updcats"		=> $lst_updcats,
							"lst_updprs"		=> $lst_updprs,
							"flg_selfonly"		=> $flg_selfonly );
							
	$tpl_users->template = replace_tags($edituser_tags, $tpl_users->template);
}
#
# Edit user: Update user db entry -----------------------------------------------------------------------------------
#
elseif(isset($_POST['btn_upduser']) && isset($_GET['user_id'])) 
{
	$user_id = intval($_GET['user_id']);

	$user_name		= $_POST['user_name'];
	$user_firstname	= $_POST['user_firstname'];
	$user_lastname	= $_POST['user_lastname'];
	$user_email		= $_POST['user_email'];
	$user_phone		= $_POST['user_phone'];
	$user_password	= $_POST['user_password'];
	$user_level		= intval($_POST['user_level']);
	$user_ticket_cat= intval($_POST['user_ticket_cat']);
	$user_ticket_pr	= intval($_POST['user_ticket_priority']);
	$user_self_only = intval($_POST['user_self_only']);
	
	//
	// Check if all fields are filled with right values
	//

	// Check if username field filled
	if(empty($user_name))
		$upd_errors .= "Username field is required.<br>";
	else
	{
		// Check if username within required length
		if(strlen($user_name) < 3 || strlen($user_name) > 25)
			$upd_errors .= "Username length must be between 3 and 25 characters.<br>";
		else
		{
			// Check if provided username already exists
			$r_userexists = mysql_query("SELECT * FROM $TABLE_USERS WHERE user_name='$user_name' AND user_id!=$user_id") or
								error("Cannot get user information.");

			if(mysql_num_rows($r_userexists))
				$upd_errors .= "Username already used by someone else.<br>";
		}
	}
	
	// Check if first name field is filled
	if(empty($user_firstname))
		$upd_errors .= "First Name field is required.<br>";
	
	// Check if last name field is filled
	if(empty($user_lastname))
		$upd_errors .= "Last Name field is required.<br>";
	
	// Check if password field is filled
	if(!empty($user_password))
	{
		// Check if password is within the required length
		if(strlen($user_password) < 3 || strlen($user_password) > 25)
			$upd_errors .= "Password length must be between 3 and 25 characters.<br>";
			
		$sql_pass_snip = ",user_password=md5('$user_password')";
	}

	// Check if email field is filled
	if(empty($user_email))
		$upd_errors .= "A valid e-mail address must be provided.<br>";
	else
	{
		// Check if email format is x@x.x
		if(!preg_match("/[a-zA-Z0-9-_\.]+@[a-zA-Z0-9-_\.]+\.[a-zA-Z0-9]+/", $user_email))
			$upd_errors .= "Invalid e-mail address.<br>";
	}
	
	// Check if phone field is filled | USERS ONLY
	if($user_level == RANK_USER)
	{
		if(empty($user_phone))
			$upd_errors .= "Phone field is required.<br>";
		else
		{
			// Check if phone number consits of digits and dashes only
			if(!preg_match("/^[0-9]([0-9]|[\-])*[0-9]$/", $user_phone))
				$upd_errors .= "Phone number can consist only of numbers and dashes.<br>";
		}
	}

	// Admins and users cannot be restricted to cats/priorities
	if($user_level==RANK_USER || $user_level==RANK_ADMIN)
	{
		$user_ticket_cat = 0;
		$user_ticket_pr	 = 0;
	}
	
	// If there were errors during the checking process, show them
	if(!empty($upd_errors))
		dialog("<b>Errors:</b><BR> $upd_errors", $page_title);

	$sql_upduser = "UPDATE $TABLE_USERS SET
					user_name		= '$user_name',
					user_firstname	= '$user_firstname',
					user_lastname	= '$user_lastname',
					user_email		= '$user_email',
					user_phone		= '$user_phone',
					user_level		= $user_level,
					user_ticket_cat = $user_ticket_cat,
					user_ticket_priority = $user_ticket_pr,
					user_self_only	= $user_self_only
					$sql_pass_snip
					WHERE user_id=$user_id";
					
	mysql_query($sql_upduser) or
		error("Cannot update user info");
		
	dialog("User info updated.", $page_title, "Back to user administration", "admin_users.php");
}

// If not showing search results, remove row from tpl
if(!isset($_GET['btn_finduser']))
	fragment_delete("row_search_res", $tpl_users->template);
// If not in user edit mode, hide user editing window
if(!isset($_GET['btn_edituser']))
	fragment_delete("tbl_edituser", $tpl_users->template);

// Load ticket categories and priorities into a listbox
$r_cats = mysql_query("SELECT * FROM $TABLE_CATS ORDER BY cat_orderby") or
				error("Cannot load ticket categories.");
while($db_cats = mysql_fetch_object($r_cats))
{
	$lst_cats .= "<option value=\"$db_cats->cat_id\">$db_cats->cat_name</option>\n";
}

$r_prs = mysql_query("SELECT * FROM $TABLE_PRIORITIES ORDER BY priority_orderby") or
				error("Cannot load ticket priorities");
while($db_prs = mysql_fetch_object($r_prs))
{
	$lst_prs .= "<option value=\"$db_prs->priority_id\">$db_prs->priority_name</option>\n";
}
// -----------

$tpl_users_tags = array( "lst_users"		=> $lst_users,
						 "lst_cats"			=> $lst_cats,
						 "lst_priorities"	=> $lst_prs );
$tpl_users->parse($tpl_users_tags);

echo build_page(content_box($tpl_users->parsed, $page_title, true), $page_title);
?>