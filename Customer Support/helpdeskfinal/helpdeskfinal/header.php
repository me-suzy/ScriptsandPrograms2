<?php
//
// Project: Help Desk support system
// Description: Page Header
//
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
require_once "includes/auth.php";
require_once "includes/funcs.php";

$tpl_header = new tpl("tpl/header.tpl");

global $hduser;
//
// Start user authentication
if($hduser['logged_in'])
{
	// Set 'welcome user' text on top-right side of the header
	switch($hduser['user_level'])
	{
	case 1: // Admin
		fragment_delete("new_ticket", $tpl_header->template);
		fragment_delete("create_account", $tpl_header->template);
		fragment_delete("log_in", $tpl_header->template);
		
		$user_rank = "Admin/Manager";
		break;
	case 2: // Special Manager
		fragment_delete("new_ticket", $tpl_header->template);
		fragment_delete("admin_panel", $tpl_header->template);
		fragment_delete("create_account", $tpl_header->template);
		fragment_delete("log_in", $tpl_header->template);
		
		$user_rank = "Special Manager";
		break;
	default:
		fragment_delete("all_tickets", $tpl_header->template);
		fragment_delete("admin_panel", $tpl_header->template);
		fragment_delete("create_account", $tpl_header->template);
		fragment_delete("log_in", $tpl_header->template);
		
		$user_rank = "User";
		break;
	}
	
	$helpdesk_time	= gmdate("d M Y H:i", time() + (3600 * $hduser['user_timezone']));
	$welcome_text	= "Welcome <b>$hduser[user_name]</b> ($user_rank)";
	
	// Check user level and hide buttons depending on clearance
	// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
}
else
{
	fragment_delete("all_tickets", $tpl_header->template);
	fragment_delete("admin_panel", $tpl_header->template);
	fragment_delete("log_out", $tpl_header->template);
}
// End user authentication
//

$tpl_header_tags = array("helpdesk_time"	=> $helpdesk_time,
						 "welcome_text"		=> $welcome_text);
$tpl_header->parse($tpl_header_tags);

echo $tpl_header->parsed;
?>