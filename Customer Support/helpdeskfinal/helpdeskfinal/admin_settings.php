<?php
//
// Project: Help Desk support system (Admin panel)
// Description: 
// 1. Settings page - Changes helpdesk configuration
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
require_once "includes/auth.php";
require_once "includes/db.php";
require_once "includes/tpl.php";
require_once "includes/funcs.php";
require_once "includes/const.php";

$page_title = "Admin Panel - Helpdesk Configuration";

// User authentication
if($hduser['user_level'] != RANK_ADMIN)
{
	// If user NOT logged in, show message & redirect to index
	dialog("Your clearance is not high enough to access this section.", $page_title);
}

#
# Update helpdesk settings -----------------------------------------------------------------------------------------
#
if(isset($_POST['btn_updsettings']))
{
	$helpdesk_email		= $_POST['helpdesk_email'];
	$helpdesk_timezone	= $_POST['helpdesk_timezone'];
	$tickets_pp			= intval($_POST['tickets_pp']);
	$replies_pp			= intval($_POST['replies_pp']);
	$wait_before_repost	= intval($_POST['wait_before_repost']);
	$attach_max_size	= intval($_POST['attach_max_size']);
	$attach_show_imgs	= intval($_POST['attach_show_imgs']);
	
	//
	// Check if all fields are filled with proper values
	//
	// Check if email format is x@x.x
	
	if(!preg_match("/[a-zA-Z0-9-_\.]+@[a-zA-Z0-9-_\.]+\.[a-zA-Z0-9]+/", $helpdesk_email))
		$errors .= "Invalid e-mail address.<br>";
	
	if($tickets_pp<1)
		$errors .= "Tickets per page value must be 1 or higher.<br>";
		
	if($replies_pp<1)
		$errors .= "Replies per page value must be 1 or higher.<br>";
		
	if(!$attach_max_size)
		$errors .= "Max attachment size must 1KB or larger.<br>";

	if(!empty($errors))
		dialog("Errors: <BR>$errors", $page_title);	
		
	// Update database
	mysql_query("UPDATE $TABLE_CONFIG SET config_value = '$helpdesk_email' WHERE config_name='helpdesk_email'") or error("Cannot update settigns");
	mysql_query("UPDATE $TABLE_CONFIG SET config_value = '$helpdesk_timezone' WHERE config_name='helpdesk_timezone'") or error("Cannot update settigns");
	mysql_query("UPDATE $TABLE_CONFIG SET config_value = '$tickets_pp' WHERE config_name='tickets_pp'")  or error("Cannot update settigns");
	mysql_query("UPDATE $TABLE_CONFIG SET config_value = '$replies_pp' WHERE config_name='replies_pp'") or error("Cannot update settigns");
	mysql_query("UPDATE $TABLE_CONFIG SET config_value = '$wait_before_repost' WHERE config_name='wait_before_repost'") or error("Cannot update settigns");
	mysql_query("UPDATE $TABLE_CONFIG SET config_value = '$attach_max_size' WHERE config_name='attach_max_size'") or error("Cannot update settigns");
	mysql_query("UPDATE $TABLE_CONFIG SET config_value = '$attach_show_imgs' WHERE config_name='attach_show_imgs'") or error("Cannot update settigns");
	
	
	header("Location: admin_settings.php");				 
}
$tpl_settings = new tpl("tpl/admin_settings.tpl");

$config = config_load();

// Build the "Show attachment images" list box
$cur_asi[$config['attach_show_imgs']] = "selected";
$attach_show_imgs = "<option value=1 $cur_asi[1]>Yes</option>\n
					 <option value=0 $cur_asi[0]>No</option>";
$tag_timezone = "$config[helpdesk_timezone]";

$tpl_settings_tags = array("helpdesk_email"		=> $config['helpdesk_email'],
						   "$tag_timezone"		=> "selected",
						   "gmt_time"			=> gmdate("d M Y H:i"),
						   "tickets_pp"			=> $config['tickets_pp'],
						   "replies_pp"			=> $config['replies_pp'],
						   "wait_before_repost"	=> $config['wait_before_repost'],
						   "attach_max_size"	=> $config['attach_max_size'],
						   "lst_show_imgs"		=> $attach_show_imgs,
						   "upload_max_size"	=> ini_get("upload_max_filesize"));
$tpl_settings->parse($tpl_settings_tags);

echo build_page(content_box($tpl_settings->parsed, $page_title, true), $page);
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
?>