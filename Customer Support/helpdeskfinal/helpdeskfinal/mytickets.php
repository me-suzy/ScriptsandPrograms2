<?php
//
// Project: Help Desk support system
// Description: 
// 1. Shows list of user`s tickets
//
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.

require_once "includes/auth.php";
require_once "includes/db.php";
require_once "includes/const.php";
require_once "includes/tpl.php";
require_once "includes/funcs.php";

// User authentication
if(!$hduser['logged_in'])
{
	// If user NOT logged in, show message & redirect to index
	dialog("You must be logged in to access this page.", 
				$page_title, "Log in", "login.php", true, true);
}

#
# Show tickets list ------------------------------------------------------------------------------------------------
#
	$mode = $_GET['mode'];
	$page_title = ($mode=="all") ? ("All tickets") : ("My Tickets");
	$config 	= config_load();
	$page 		= (intval($_GET['page'])) ? (intval($_GET['page'])) : (1);
	
	$tpl_list = new tpl("tpl/ticket_list.tpl");

	// Get HTML code fragments and then delete them from tpl
	$html_ticket_row = fragment_get("row_ticket", $tpl_list->template);
	$tpl_list->template = fragment_replace("row_ticket", "{list_tickets}", $tpl_list->template);
	
	//
	// Build user query filter
	//
	$user_id = ($hduser['user_id']) ? ($hduser['user_id']) : (0); 
	if($hduser['user_level'] == RANK_ADMIN)
	{
		// Views ALL tickets
		if($mode == "all")
			$filter_condition = "";
		else // Views tickets assigned to him
			$filter_condition = "WHERE ticket_tech = $user_id";
	}
	elseif($hduser['user_level'] == RANK_SPECMANAGER)
	{
		// Views ALL tickets this special manager is ALLOWED to view
		if($mode == "all" && !$hduser['user_self_only'])
		{	
			$user_ticket_priority	= $hduser['user_ticket_priority'];
			$user_ticket_cat		= $hduser['user_ticket_cat'];

			$priority_filter = "ticket_priority = $user_ticket_priority";
			$cat_filter = "ticket_cat = $user_ticket_cat";			
				
			if($user_ticket_cat && $user_ticket_priority)
				$filter_condition = "WHERE $priority_filter AND $cat_filter";
			else
			{	
				if($user_ticket_cat)
				{
					$filter_condition = "WHERE $cat_filter";
				}
				elseif($user_ticket_priority)
				{
					$filter_condition = "WHERE $priority_filter";
				}
				else
				{
					$filter_condition = "";
				}
			}
			$filter_condition = "$filter_condition OR ticket_tech = $user_id";
		}
		else // Views tickets assigned to him
		{
			$filter_condition = "WHERE ticket_tech = $user_id";
		}
	}
	else
	{
		// Views only his own tickets
		$filter_condition = "WHERE reply_author = $user_id AND ticket_hide!=1";
	}

	//
	// Get total tickets and generate pagination
	//
	$r_ctickets		= mysql_query("SELECT count(tickets.ticket_id) AS ticket_count FROM $TABLE_TICKETS AS tickets
								   LEFT JOIN $TABLE_REPLIES AS replies ON (replies.reply_id = tickets.ticket_firstreply)
								   $filter_condition") or
							error("Cannot get total ticket count.");
		
	$db_ctickets	= mysql_fetch_object($r_ctickets);
	$count_tickets	= $db_ctickets->ticket_count;

	$item_start = ($page * $config['tickets_pp']) - $config['tickets_pp'];
	$item_limit	= $config['tickets_pp'];

	$pagination = pagination($page, ceil($count_tickets/$config['tickets_pp']),"mode=$mode");
	
	//
	// Get sorting criteria
	//
	$sort_order = ($_GET['lst_sortorder'] == "asc" || $_GET['lst_sortorder'] == "desc") ? ($_GET['lst_sortorder']) : ("asc");

	switch($_GET['lst_sortfield'])
	{
	case "subject":
		$sort_field = "ticket_subject";
		break;
	case "category":
		$sort_field = "ticket_cat";
		break;
	case "priority":
		$sort_field = "ticket_priority";
		break;
	case "tech":
		$sort_field = "ticket_tech";
		break;
	case "status":
		$sort_field = "ticket_status";
		break;
	case "time":
		$sort_field = "reply_time";
		break;
	default:
		$sort_field = "reply_time";
		$sort_order	= "desc";
		break;
	}
	
	//
	// Load tickets from db
	//
	$sql_tickets = "SELECT *,tickets.ticket_id AS ticket_id FROM $TABLE_TICKETS AS tickets
					LEFT JOIN $TABLE_REPLIES AS replies ON (tickets.ticket_firstreply = replies.reply_id)
					LEFT JOIN $TABLE_CATS AS cats ON (tickets.ticket_cat = cats.cat_id)
					LEFT JOIN $TABLE_PRIORITIES AS priorities ON (tickets.ticket_priority = priorities.priority_id)
					LEFT JOIN $TABLE_STATUS AS status ON (status.status_id = tickets.ticket_status)
					LEFT JOIN $TABLE_USERS AS techs ON (techs.user_id = tickets.ticket_tech)
					$filter_condition
					ORDER BY $sort_field $sort_order
					LIMIT $item_start, $item_limit";
					
	$r_tickets = mysql_query($sql_tickets) or
					error("Cannot get tickets.");

	// If no tickets returned, show message
	if(mysql_num_rows($r_tickets) > 0)
		fragment_delete("txt_no_tickets", $tpl_list->template);
	else
		fragment_delete("ticket_list", $tpl_list->template);
	
	$timezone = ($hduser['user_timezone']==NULL) ? ($config['helpdesk_timezone']) : ($hduser['user_timezone']);
	while($db_tickets = mysql_fetch_object($r_tickets))
	{
		$ticket_tech = (!$db_tickets->ticket_tech) ? ("None") : ($db_tickets->user_firstname . " " . $db_tickets->user_lastname);
		$ticket_time = gmdate("d M Y - H:i", $db_tickets->reply_time + (3600 * $timezone));
		
		$ticket_row_tags = array("ticket_id"		=> $db_tickets->ticket_id,
								 "ticket_subject"	=> $db_tickets->ticket_subject,
								 "ticket_cat"		=> $db_tickets->cat_name,
								 "ticket_priority"	=> $db_tickets->priority_name,
								 "ticket_tech"		=> $ticket_tech,
								 "ticket_status"	=> $db_tickets->status_name,
								 "ticket_time"		=> $ticket_time);
								 
		$html_ticket_list .= replace_tags($ticket_row_tags, $html_ticket_row);
	}
	
	// Set page tags and build page
	$tpl_list_tags = array( "list_tickets"	=> $html_ticket_list,
							"pagination"	=> $pagination,
							"mode"			=> $mode );
	$tpl_list->parse($tpl_list_tags);
	
	echo build_page(content_box($tpl_list->parsed, $page_title), $page_title);
	// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.	
?>