<?php
//
// Project: Help Desk support system
// Description: Search page
//
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.

require_once "includes/auth.php";
require_once "includes/db.php";
require_once "includes/tpl.php";
require_once "includes/funcs.php";
require_once "includes/const.php";

// User authentication
if(!$hduser['logged_in'])
{
	// If user NOT logged in, show message & redirect to index
	dialog("You must be logged in to access this page.", 
				$page_title, "Log in", "login.php", true, true);
}

$config	= config_load();
$page = (intval($_GET['page'])) ? (intval($_GET['page'])) : (1);

if(isset($_GET['btn_search']))
{
	$page_title = "Search results";
	
	$search_query	= trim($_GET['search_query']);
	$search_type	= ($_GET['search_type']) ? (1) : (0);
	$search_cat		= intval($_GET['search_cat']);
	$search_priority= intval($_GET['search_priority']);
	$search_status	= intval($_GET['search_status']);
	$search_date	= (!empty($_GET['search_date'])) ? ($_GET['search_date']) : (0);
	
	$search_condition = "";
	
	if(empty($search_query))
		dialog("Your search query cannot be empty.", $page_title);
		
	// Build search query condition
	if(!$search_type)
	{
		// Search EXACT text
		$search_condition .= "replies.reply_content LIKE '%$search_query%' OR ticket_subject LIKE '%$search_query%'";
	}
	else
	{
		// Search for ALL WORDS
		preg_match_all("/[\.a-zA-Z0-9_-]{4,}\b/", $search_query, $search_words);
		$search_words = $search_words[0];

		foreach($search_words as $key => $word)
		{
			// Accept only 3+ chars words
			if(strlen($word) > 3)
			{
				$subject_words .= "ticket_subject LIKE '%$word%'";
				$content_words .= "replies.reply_content LIKE '%$word%'";
				
				// If not last word, add AND
				if($key != count($search_words)-1)
				{
					$subject_words .= " AND ";
					$content_words .= " AND ";
				}
			}
		}
		$search_condition = "($content_words) OR ($subject_words)";
	}
	$search_condition = "($search_condition)";
	
	if($search_cat > 0)
		$search_condition .= " AND ticket_cat = $search_cat";
	if($search_priority > 0)
		$search_condition .= " AND ticket_priority = $search_priority";
	if($search_status > 0)
		$search_condition .= " AND ticket_status = $search_status";

	$timezone = ($hduser['user_timezone']==NULL) ? ($config['helpdesk_timezone']) : ($hduser['user_timezone']);
	$cur_time = time() + (3600 * $timezone);
	if(!empty($search_date))
	{
		switch($search_date)
		{
		case "today":
			$date_start	= mktime(0, 0, 0, gmdate("m", $cur_time), gmdate("d", $cur_time), gmdate("y", $cur_time));
			break;
		case "this_week":
			$date_start	= mktime(0, 0, 0, gmdate("m", $cur_time), gmdate("d", $cur_time)-gmdate("w", $cur_time)+1, gmdate("y", $cur_time));
			break;
		case "this_month":
			$date_start	= mktime(0, 0, 0, gmdate("m", $cur_time), 1, gmdate("y", $cur_time));
			break;
		case "last_6months":
			$date_start = mktime(0, 0, 0, gmdate("m", $cur_time)-6, gmdate("d", $cur_time), gmdate("y", $cur_time));
			break;
		case "this_year":
			$date_start = mktime(0, 0, 0, 1, 1, gmdate("y", $cur_time));
			break;
		default:
			$date_start	= mktime(0, 0, 0, gmdate("m", $cur_time), gmdate("d", $cur_time), gmdate("y", $cur_time)); // Today
			break;
		}
		
		$search_condition .= " AND replies.reply_time >= $date_start ";
	}
	
	//
	// Build user query filter
	//
	$user_id = ($hduser['user_id']) ? ($hduser['user_id']) : (0); 
	$mode = $_GET['mode'];

	if($hduser['user_level'] == RANK_ADMIN)
	{
		// Views ALL tickets
		$user_condition = "";
	}
	elseif($hduser['user_level'] == RANK_SPECMANAGER)
	{
		// If user has access only to his tickets
		if($hduser['user_self_only'])
		{
			// Views ALL tickets this special manager is ALLOWED to view
	 		$user_ticket_priority	= $hduser['user_ticket_priority'];
	 		$user_ticket_cat		= $hduser['user_ticket_cat'];
	
	 		$priority_filter	= "ticket_priority = $user_ticket_priority";
	 		$cat_filter			= "ticket_cat = $user_ticket_cat";			
	 			
	 		if($user_ticket_cat && $user_ticket_priority)
	 			$user_condition = "AND (($priority_filter AND $cat_filter) || ticket_tech=$user_id)";
	 		else
	 		{	
	 			if($user_ticket_cat)
	 			{
	 				$user_condition = " AND ($cat_filter OR ticket_tech=$user_id)";
	 			}
	 			elseif($user_ticket_priority)
	 			{
	 				$user_condition = " AND ($priority_filter OR ticket_tech=$user_id)";
	 			}
	 			else
	 			{
	 				$user_condition = "";
	 			}
	 		}
		}
		else
		{
			$user_condition = " AND ticket_tech=$user_id ";
		}
	}
	else
	{
		// Views only his own tickets
		$user_condition = "AND replies2.reply_author = $user_id AND ticket_hide!=1";
	}


	//
	// Generate pagination
	//
	$r_ctickets = mysql_query("SELECT tickets.ticket_id AS tickets_count
							  FROM $TABLE_REPLIES AS replies
							  LEFT JOIN $TABLE_TICKETS AS tickets ON (tickets.ticket_id = replies.ticket_id)
							  LEFT JOIN $TABLE_REPLIES AS replies2 ON (replies2.reply_id = tickets.ticket_firstreply)
							  WHERE $search_condition $user_condition
							  GROUP BY tickets.ticket_id") or
						error("Cannot get total result items");
	$count_tickets = mysql_num_rows($r_ctickets);

	$item_start = ($page * $config['tickets_pp']) - $config['tickets_pp'];
	$item_limit	= $config['tickets_pp'];
	$pagination = pagination($page, ceil($count_tickets/$config['tickets_pp']),
							 "search_query=$search_query&search_type=$search_type&search_cat=$search_cat&search_priority=$search_priority&search_status=$search_status&search_date=$search_date&btn_search=$_GET[btn_search]");

	// Search database
	$r_res = mysql_query("SELECT tickets.ticket_id, tickets.ticket_subject, replies.reply_id,
						  replies.reply_content, cats.cat_name, prs.priority_name, status.status_name,
						  techs.user_name AS ticket_tech, techs.user_firstname, techs.user_lastname,
						  replies2.reply_time, replies2.reply_author
						  
						  FROM $TABLE_REPLIES AS replies
						  LEFT JOIN $TABLE_TICKETS AS tickets ON (tickets.ticket_id = replies.ticket_id)
						  LEFT JOIN $TABLE_REPLIES AS replies2 ON (replies2.reply_id = tickets.ticket_firstreply)
						  LEFT JOIN $TABLE_CATS AS cats ON (cats.cat_id = tickets.ticket_cat)
						  LEFT JOIN $TABLE_PRIORITIES AS prs ON (prs.priority_id = tickets.ticket_priority)
						  LEFT JOIN $TABLE_STATUS AS status ON (status.status_id = tickets.ticket_status)
						  LEFT JOIN $TABLE_USERS AS techs ON (techs.user_id = tickets.ticket_tech)
						  WHERE $search_condition $user_condition
						  GROUP BY tickets.ticket_id
						  ORDER BY replies2.reply_time DESC
						  LIMIT $item_start, $item_limit") or
					error("Cannot execute search query");

	if(mysql_num_rows($r_res) < 1)
		dialog("Your search returned no results.", $page_title, "Back", "search.php");					

	//
	// Build results list
	//

	// Load template
	$tpl_res = new tpl("tpl/search_results.tpl");
	
	// Get html code fragments
	$html_result_row = fragment_get("row_result", $tpl_res->template);

	while($db_res = mysql_fetch_object($r_res))
	{
		$ticket_tech = (empty($db_res->ticket_tech)) ? ("None") : ($db_res->user_firstname . " " . $db_res->user_lastname);
		$ticket_time = gmdate("d M Y - H:i", $db_res->reply_time + (3600 * $timezone));
		$reply_content = (strlen($db_res->reply_content) > 300) ? (substr($db_res->reply_content, 0, 300)."...") : ($db_res->reply_content);

		$result_row_tags = array( "ticket_id"		=> $db_res->ticket_id,
								  "ticket_subject"	=> $db_res->ticket_subject,
								  "ticket_cat"		=> $db_res->cat_name,
								  "ticket_priority"	=> $db_res->priority_name,
								  "ticket_tech"		=> $ticket_tech,
								  "ticket_status"	=> $db_res->status_name,
								  "ticket_time"		=> $ticket_time,
								  "reply_content"	=> $reply_content);

		$html_result_list .= replace_tags($result_row_tags, $html_result_row);
	}
	$tpl_res->template = fragment_replace("row_result", $html_result_list, $tpl_res->template);

	$tpl_res_tags = array("pagination" => $pagination);
	
	$tpl_res->parse($tpl_res_tags);
	
	echo build_page(content_box($tpl_res->parsed, $page_title), $page_title);
}
#
# Show search page ---------------------------------------------------------------------------------------------------
#
else
{
	$page_title = "Search tickets";

	$tpl_search = new tpl("tpl/search.tpl");

	//
	// Load ticket categories, priorities, status into a listbox
	//
	$r_cats = mysql_query("SELECT * FROM $TABLE_CATS ORDER BY cat_orderby") or
					error("Cannot get ticket categories.");
	$r_prs	= mysql_query("SELECT * FROM $TABLE_PRIORITIES ORDER BY priority_orderby") or
					error("Cannot get ticket priorities.");
	$r_status = mysql_query("SELECT * FROM $TABLE_STATUS ORDER BY status_orderby") or
					error("Cannot get ticket status.");
					
	while($db_cats = mysql_fetch_object($r_cats))
	{
		$lst_cats .= "<option value=\"$db_cats->cat_id\">$db_cats->cat_name</option>\n";
	}
	
	while($db_prs = mysql_fetch_object($r_prs))
	{
		$lst_prs .= "<option value=\"$db_prs->priority_id\">$db_prs->priority_name</option>\n";
	}
	
	while($db_status = mysql_fetch_object($r_status))
	{
		$lst_status .= "<option value=\"$db_status->status_id\">$db_status->status_name</option>\n";	
	}
	
	$tpl_search_tags = array("lst_search_cats"		=> $lst_cats,
							 "lst_search_prs"		=> $lst_prs,
							 "lst_search_status"	=> $lst_status);
							 
	$tpl_search->parse($tpl_search_tags);
	echo build_page(content_box($tpl_search->parsed, $page_title), $page_title);
	// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
}
?>