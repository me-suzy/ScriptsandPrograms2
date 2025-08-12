<?php
//
// Project: Help Desk support system
// Description: 
// 1. Displays tickets
//

require_once "includes/auth.php";
require_once "includes/db.php";
require_once "includes/const.php";
require_once "includes/tpl.php";
require_once "includes/funcs.php";

//User authentication
if(!$hduser['logged_in'])
{
	// If user NOT logged in, show message & redirect to index
	dialog("You must be logged in to access this page.", 
				$page_title, "Log in", "login.php", true, true);
}

$page_title = "View ticket";
#
# Download attachment -----------------------------------------------------------------------------------------------
#
if(isset($_GET['fid']) && $_GET['action']=="getfile")
{
	$fid = intval($_GET['fid']);

	$r_file = mysql_query("SELECT * FROM $TABLE_ATTACHMENTS WHERE attach_id=$fid") or
					error("Cannot load attachment info from db.");
	$db_file = mysql_fetch_object($r_file);
	
	$file_path = "$PATH_ATTACHMENTS/$db_file->attach_file";

	if(file_exists($file_path))
		$file_size = @filesize($file_path);
	else
		dialog("Attached file does not exist.", $page_title);

	// Send file to browser
	header("Content-Type: application/octetstream; name=\"$db_file->attach_origname\"");
	header("Name: \"$db_file->attach_origname\"");
	header('Content-Disposition: inline; filename="' . $db_file->attach_origname . '"');
	//header("Content-length: $file_size");

	readfile($file_path);
	exit();
}
#
# Displays the ticket and all replies -------------------------------------------------------------------------------
#
if(isset($_GET['tid']))
{
	$tid 	= intval($_GET['tid']);
	$config	= config_load();
	$page	= (intval($_GET['page'])) ? (intval($_GET['page'])) : (1);
	$item_start = ($page * $config['replies_pp']) - $config['replies_pp'];	// Start loading from
	$item_limit = $config['replies_pp'];
	
	$tpl_show = new tpl("tpl/ticket_show.tpl");
	
	// Get HTML code fragments
	$html_reply_row		= fragment_get("row_reply", $tpl_show->template);
	$html_editbtn		= fragment_get("btn_edit", $tpl_show->template);
	$html_attach_row	= fragment_get("row_attachment", $tpl_show->template);
	$html_reply_row		= fragment_replace("btn_edit", "{btn_edit}", $html_reply_row);
	$html_reply_row		= fragment_replace("row_attachment", "{row_attachment}", $html_reply_row);

	
	//
	// Get total replies count to calculate pagination
	//
	$r_creplies = mysql_query("SELECT count(reply_id) AS reply_count FROM $TABLE_REPLIES
							   WHERE ticket_id = $tid") or
					error("Cannot count ticket replies.");
	$db_creplies = mysql_fetch_object($r_creplies);
	$count_replies = $db_creplies->reply_count;

	$pagination = pagination($page, ceil($count_replies/$config['replies_pp']), "tid=$tid");
	
	//
	// Load ticket replies and show them
	//
	$r_ticket = mysql_query("SELECT replies.*, attach.*, tickets.*, users.*, 
							 replies2.reply_author AS ticket_author
							 FROM $TABLE_REPLIES AS replies
							 LEFT JOIN $TABLE_ATTACHMENTS AS attach ON (attach_id = replies.reply_attachment)
							 LEFT JOIN $TABLE_TICKETS AS tickets ON (tickets.ticket_id = replies.ticket_id)
							 LEFT JOIN $TABLE_USERS AS users ON (users.user_id = replies.reply_author)
							 LEFT JOIN $TABLE_REPLIES AS replies2 ON (replies2.reply_id = tickets.ticket_firstreply)
							 WHERE replies.ticket_id=$tid
							 ORDER BY replies.reply_time
							 LIMIT $item_start, $item_limit") or
					error("Cannot view ticket.");
	
	// Check if ticket exists
	if(mysql_num_rows($r_ticket) < 1)
		dialog("This ticket does not exist");
	$db_ticket = mysql_fetch_object($r_ticket);
	//
	// Check if user is allowed to post in this ticket
	//
	$user_id = ($hduser['user_id']) ? ($hduser['user_id']) : (0); 

	if($hduser['user_level'] == RANK_SPECMANAGER)
	{
		$user_ticket_priority	= $hduser['user_ticket_priority'];
		$user_ticket_cat		= $hduser['user_ticket_cat'];

		// If ticket is not assigned to viewer
		if($db_ticket->ticket_tech != $hduser['user_id'])
		{
			// If manager can view only tickets assigned to him
			if($hduser['user_self_only'])
				dialog("You cannot view this ticket", $page_title);
			// If ticket fits in managers` allowed category
			if($user_ticket_priority && ($user_ticket_priority != $db_ticket->ticket_priority))
				dialog("You cannot view this ticket.", $page_title, "My Tickets", "mytickets.php");
			// If ticket fits in managers` allowed priority
			if($user_ticket_cat && $user_ticket_cat != $db_ticket->ticket_cat)
				dialog("You cannot view this ticket.", $page_title, "My Tickets", "mytickets.php");
		}
	}
	elseif($hduser['user_level'] == RANK_USER)
	{
		if($hduser['user_id'] != $db_ticket->ticket_author || $db_ticket->ticket_hide)
		{
			dialog("You cannot view this ticket.", $page_title, "My Tickets", "mytickets.php");
		}
		
		// Delete admin bar at the bottom of the page
		fragment_delete("admin_bar", $tpl_show->template);
	}
	
	mysql_data_seek($r_ticket, 0);
	// Build replies list
	while($db_ticket = mysql_fetch_object($r_ticket))
	{
		// If viewer is plain user, show edit button only on his replies
		if(($hduser['user_level']==0 && $db_ticket->reply_author == $hduser['user_id'])
			|| $hduser['user_level']== RANK_SPECMANAGER || $hduser['user_level'] == RANK_ADMIN)
			$btn_edit = replace_tags( array("reply_id" => $db_ticket->reply_id), $html_editbtn);
		else
			$btn_edit = "";

		// If reply contains attachment, show attachment row
		if($db_ticket->reply_attachment)
		{
			// Get filesize and convert to KB
			$attach_size	= @filesize("$PATH_ATTACHMENTS/$db_ticket->attach_file")/1024;
			$attach_size	= number_format($attach_size, 1);

			// If attachment is image, show it
			$attach_ext = strtolower(substr($db_ticket->attach_origname, -3, 3));
			if($config['attach_show_imgs']==1 && ($attach_ext == "jpg" || $attach_ext == "gif" || $attach_ext == "png" || $attach_ext == "bmp"))
				$attach_img = "<img src=\"$PATH_ATTACHMENTS/$db_ticket->attach_file\">";
			else
				$attach_img = "";
				
			$row_attach_tags = array( "attachment_filename" => $db_ticket->attach_origname,
									  "attachment_size"		=> $attach_size,
									  "attachment_image"	=> $attach_img,
									  "attachment_url"		=> "?action=getfile&fid=$db_ticket->attach_id" );

			$attachment_html = replace_tags($row_attach_tags, $html_attach_row);
		}
		else
			$attachment_html = "";

		//
		// Display user info
		//
		// If viewer is user or poster is NOT user, don`t show user info
		if($hduser['user_level'] == RANK_USER || $db_ticket->user_level != RANK_USER)
		{
			$reply_row_edited = fragment_replace("user_info", "", $html_reply_row);
		}
		else
			$reply_row_edited = $html_reply_row;
					
					
		// Set poster rank text
		switch($db_ticket->user_level)
		{
		case RANK_ADMIN:
			$user_rank = "Administrator";
			break;
		case RANK_SPECMANAGER:
			$user_rank = "Tech";
			break;
		default:
			$user_rank = "User";
			break;
		}
					
		$timezone = ($hduser['user_timezone']==NULL) ? ($config['helpdesk_timezone']) : ($hduser['user_timezone']);

		$reply_row_tags = array( "reply_username"	=> $db_ticket->user_name,
								 "reply_time"		=> gmdate("d M Y - H:i", $db_ticket->reply_time + (3600 * $timezone)),
								 "reply_content"	=> newline2br($db_ticket->reply_content),
								 "row_attachment"	=> $attachment_html,
								 "btn_edit"			=> $btn_edit,
								 "user_rank"		=> $user_rank,
								 
								 "user_fname"		=> $db_ticket->user_firstname,
								 "user_lname"		=> $db_ticket->user_lastname,
								 "user_phone"		=> $db_ticket->user_phone,
								 "user_email"		=> $db_ticket->user_email );
								 
		$html_replies_list .= replace_tags($reply_row_tags, $reply_row_edited);

		$ticket_subject = $db_ticket->ticket_subject;
	}
	
	//
	// Build mini administration panel
	//
	
	// Reset mysql pointer
	mysql_data_seek($r_ticket, 0);
	$db_ticket = mysql_fetch_object($r_ticket);

	$cur_cat[$db_ticket->ticket_cat]		= "selected";
	$cur_pr[$db_ticket->ticket_priority]	= "selected";
	$cur_status[$db_ticket->ticket_status]	= "selected";
	$cur_tech[$db_ticket->ticket_tech]	= "selected";
	
	// Load categories into a listbox
	$r_cats = mysql_query("SELECT * FROM $TABLE_CATS ORDER BY cat_orderby") or
					error("Cannot load ticket categories.");
	while($db_cats = mysql_fetch_object($r_cats))
	{
		$cat_id = $db_cats->cat_id;
		$lst_cats .= "<option value=\"$cat_id\" $cur_cat[$cat_id]>$db_cats->cat_name</option>\n";
	}
	
	// Load priorities into a listbox
	$r_prs = mysql_query("SELECT * FROM $TABLE_PRIORITIES ORDER BY priority_orderby") or
				error("Cannot load ticket priorities.");
	while($db_prs = mysql_fetch_object($r_prs))
	{
		$pr_id = $db_prs->priority_id;
		$lst_prs .= "<option value=\"$pr_id\" $cur_pr[$pr_id]>$db_prs->priority_name</option>\n";
	}
	
	// Load status into a listbox
	$r_status = mysql_query("SELECT * FROM $TABLE_STATUS ORDER BY status_orderby") or
					error("Cannot load ticket status.");
	while($db_status = mysql_fetch_object($r_status))
	{
		$status_id = $db_status->status_id;
		$lst_status .= "<option value=\"$status_id\" $cur_status[$status_id]>$db_status->status_name</option>\n";
	}

	// Load techs into a listbox
	$r_techs = mysql_query("SELECT * FROM $TABLE_USERS WHERE
							   user_level=" . RANK_SPECMANAGER . " OR
							   user_level=" . RANK_ADMIN . "
							   ORDER BY user_lastname, user_firstname") or
						error("Cannot load techs.");
	while($db_techs = mysql_fetch_object($r_techs))
	{
		$tech_id = ($db_techs->user_id) ? ($db_techs->user_id) : (0);
		
		$lst_techs .= "<option value=\"$tech_id\" $cur_tech[$tech_id]>$db_techs->user_lastname
						  $db_techs->user_firstname ($db_techs->user_name)</option>\n";
	}
	
	// Set proper caption for "show/hide ticket" button
	$btn_toggle_cap = ($db_ticket->ticket_hide) ? ("Show ticket") : ("Hide ticket");
	//
	// End building mini admin panel
	//
	
	// Replace marked reply row with reply list in tpl
	$tpl_show->template = fragment_replace("row_reply", $html_replies_list, $tpl_show->template);
	
	// Set page tags and build page
	$tpl_show_tags = array( "ticket_subject"	=> $ticket_subject,
							"ticket_id"			=> $tid,
							"pagination"		=> $pagination,
							"page"				=> $page,
							
							"lst_ticket_cat"		=> $lst_cats,
							"lst_ticket_priority"	=> $lst_prs,
							"lst_ticket_status"		=> $lst_status,
							"lst_ticket_techs"		=> $lst_techs,
							"btn_toggle_cap"		=> $btn_toggle_cap );
	
	$tpl_show->parse($tpl_show_tags);

	echo build_page(content_box($tpl_show->parsed, $page_title), $page_title);
}
?>