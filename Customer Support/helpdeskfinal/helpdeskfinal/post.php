<?php
//
// Project: Help Desk support system
// Description: 
// 1. Creates new ticket
// 2. Handles replies to existing tickets
// 3. Edits tickets
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.

require_once "includes/auth.php";
require_once "includes/db.php";
require_once "includes/const.php";
require_once "includes/tpl.php";
require_once "includes/funcs.php";

$config = config_load();

// User authentication
if(!$hduser['logged_in'])
{
	// If user NOT logged in, show message & redirect to index
	dialog("You must be logged in to access this page.", 
				$page_title, "Log in", "login.php", true, true);
}

#
# Common code required by all post modes ----------------------------------------------------------------------------
#
$tid = intval($_GET['tid']);

if($tid)
{
	// Get ticket properties
	$r_props = mysql_query("SELECT tickets.ticket_cat, tickets.ticket_priority, tickets.ticket_status,
							tickets.ticket_notify, tickets.ticket_tech, tickets.ticket_hide, reply_author
							FROM $TABLE_TICKETS AS tickets
							LEFT JOIN $TABLE_REPLIES AS replies ON (replies.reply_id = tickets.ticket_firstreply)
							WHERE tickets.ticket_id=$tid") or
					error("Cannot load ticket category/priority.");
	$db_props = mysql_fetch_object($r_props);

	// If ticket does not exist show error
	if(mysql_num_rows($r_props)<1)
		dialog("Ticket does not exist", $page_title, "Back to My Tickets", "mytickets.php");
		
	//
	// Check if user is allowed to post in this ticket
	//
	$user_id = ($hduser['user_id']) ? ($hduser['user_id']) : (0); 

	if($hduser['user_level'] == RANK_SPECMANAGER)
	{
		$user_ticket_priority	= $hduser['user_ticket_priority'];
		$user_ticket_cat		= $hduser['user_ticket_cat'];
		
		// If ticket is not assigned to viewer
		if($db_props->ticket_tech != $hduser['user_id'])
		{
			// If manager can view only tickets assigned to him
			if($hduser['user_self_only'])
				dialog("You cannot post in this ticket", $page_title);
			// If ticket fits in managers` allowed priority
			if($user_ticket_priority && $user_ticket_priority != $db_props->ticket_priority)
				dialog("You are not allowed to post in this ticket.", $page_title, "My Tickets", "mytickets.php");
			// If ticket fits in managers` allowed category
			if($user_ticket_cat && $user_ticket_cat != $db_props->ticket_cat)
				dialog("You are not allowed to post in this ticket.", $page_title, "My Tickets", "mytickets.php");
		}
	}
	elseif($hduser['user_level'] == RANK_USER)
	{
		if($hduser['user_id'] != $db_props->reply_author || $db_props->ticket_hide)
		{
			dialog("You cannot post in this ticket.", $page_title, "My Tickets", "mytickets.php");
		}

		if($db_props->ticket_status == STATUS_CLOSED)
		{
			dialog("This ticket is closed.", $page_title, "My Tickets", "index.php");
		}
	}

	$cur_cat[$db_props->ticket_cat]				= "selected";
	$cur_priority[$db_props->ticket_priority]	= "selected";
	$flg_ticket_notify = ($db_props->ticket_notify) ? ("checked") : ("");
}
else
{
	// Use defaults, cat = Other and priority = Normal
	$cur_cat[1] = "selected";
	$cur_priority[3] = "selected";
}

// Load ticket categories
$r_cats = mysql_query("SELECT * FROM $TABLE_CATS WHERE cat_orderby > 0 ORDER BY cat_orderby") or
			error("Cannot load ticket categories.");

while($db_cats = mysql_fetch_object($r_cats))
{
	$cid = $db_cats->cat_id;
	$lst_cats .= "<option value=\"$db_cats->cat_id\" $cur_cat[$cid] >$db_cats->cat_name</option>\n";
}

// Load ticket priorities
$r_priorities = mysql_query("SELECT * FROM $TABLE_PRIORITIES
							 WHERE priority_orderby > 0 ORDER BY priority_orderby") 
					or error("Cannot load ticket priorities");

while($db_priorities = mysql_fetch_object($r_priorities))
{
	$cid = $db_priorities->priority_id;
	$lst_priorities .= "<option value=\"$db_priorities->priority_id\" $cur_priority[$cid]>
						$db_priorities->priority_name</option>\n";
}

#
# Edit reply/ticket: Update ticket db entry -------------------------------------------------------------------------
#
if($_POST['action'] == "edit_reply" && isset($_POST['rid']) && isset($_POST['tid']))
{
	$tid = intval($_POST['tid']);
	$rid = intval($_POST['rid']);
	
	$ticket_subject		= $_POST['ticket_subject'];
	$reply_content		= $_POST['reply_content'];
	$reply_attachment	= $_FILES['reply_attachment'];

	$ticket_cat			= ($_POST['ticket_cat']>0) ? ($_POST['ticket_cat']) : (1);
	$ticket_priority	= ($_POST['ticket_priority']>0) ? ($_POST['ticket_priority']) : (3);
	$ticket_notify		= (isset($_POST['ticket_notify'])) ? (1) : (0);

	$delete_reply		= isset($_POST['chk_del_reply']) ? (1) : (0);
	$delete_attach		= isset($_POST['chk_del_attach']) ? (1) : (0);
	
	// Get reply author
	$r_author = mysql_query("SELECT reply_author FROM $TABLE_REPLIES WHERE reply_id=$rid") or
					error("Cannot get reply author.");
					
	$db_author = mysql_fetch_object($r_author);
	
	// If editor is USER and not author of the reply
	if($hduser['user_level'] == RANK_USER && $db_author->reply_author != $hduser['user_id'])
		dialog("Access denied.");
					
	// If delete attachment or delete reply is checked OR user uploads new attachment
	if($delete_attach || $delete_reply || !empty($reply_attachment['name']))
	{
		// Get attachment id
		$r_attach = mysql_query("SELECT attach_id FROM $TABLE_ATTACHMENTS as attach
								 LEFT JOIN $TABLE_REPLIES AS replies ON (replies.reply_attachment = attach.attach_id)
								 WHERE reply_id = $rid") or
						error("Cannot get attachment id.");
						
		$db_attach = mysql_fetch_object($r_attach);

		// If reply contains attachment, delete attachment
		if(!empty($db_attach->attach_id))
			attach_delete($db_attach->attach_id);
	}	
	
	// If user chose to delete the reply
	if($delete_reply)
	{
		$ticket_deleted = reply_delete($rid);
		
		// If ticket deleted, redirect to index, else show ticket
		if($ticket_deleted)
			dialog("The ticket has been deleted.", $page_title, "Back to My Tickets", "mytickets.php");
		else
			dialog("The reply has been deleted.", $page_title, "View ticket", "showticket.php?tid=$tid");
	}

	// If user decided to upload new attachment
	if(!empty($reply_attachment['name']) && !$delete_attach)
	{
		// Upload new attachment
		$attach_new_id = attach_handle($reply_attachment);
		
		// Add to reply update query
		$attach_sql_snip = ",reply_attachment = '$attach_new_id'";
	}
	else
		$attach_sql_snip = "";
	
	// If poster not user, don`t update ticket notification status
	if($hduser['user_level'] != RANK_USER)
		$sql_notify_snip = "";
	else
		$sql_notify_snip = ", ticket_notify	= $ticket_notify";
		
	// Update ticket info
	mysql_query("UPDATE $TABLE_TICKETS SET
				 ticket_subject		= '$ticket_subject',
				 ticket_cat			= $ticket_cat,
				 ticket_priority	= $ticket_priority
				 $sql_notify_snip
				 WHERE ticket_id=$tid") or
		error("Cannot update ticket info.");
				 
	mysql_query("UPDATE $TABLE_REPLIES SET
				 reply_content		= '$reply_content'
				 $attach_sql_snip
				 WHERE reply_id=$rid") or
		error("Cannot update reply info.");
		
	// Show dialog and redirect to ticket
	dialog("Your reply has been updated.", $page_title, "Back to ticket", "showticket.php?tid=$tid");
}
#
# Edit reply/ticket: Show edit page ---------------------------------------------------------------------------------
#
elseif($_GET['action']=="edit" && isset($_GET['rid']))
{
	$page_title = "Edit ticket reply";
	
	$rid = intval($_GET['rid']);
	
	$tpl_edit			= new tpl("tpl/ticket_edit.tpl");
	$tpl_txtformattb	= new tpl("tpl/toolbar_txtformat.tpl");

	$tpl_txtformattb->parse( array("text_box" => "reply_content") );

	// Load reply info to be edited
	$r_reply = mysql_query("SELECT * FROM $TABLE_REPLIES AS replies
							LEFT JOIN $TABLE_TICKETS AS tickets ON (tickets.ticket_id = replies.ticket_id)
							LEFT JOIN $TABLE_ATTACHMENTS AS attach ON (attach.attach_id = replies.reply_attachment)
							WHERE reply_id=$rid") or
					error("Cannot load selected reply.");
	$db_reply = mysql_fetch_object($r_reply);
	
	// If editor is USER and not author of the reply
	if($hduser['user_level'] == RANK_USER && $db_reply->reply_author != $hduser['user_id'])
		dialog("Access denied.");
	
	$ticket_subject		= $db_reply->ticket_subject;
	$reply_content		= $db_reply->reply_content;
	$reply_cur_attach	= ($db_reply->reply_attachment) ? ($db_reply->attach_origname) : ("None");
	
	// Remove "Delete attachment" checkbox if reply contains no attachment
	if(!$db_reply->reply_attachment)
		fragment_delete("chk_del_attachment", $tpl_edit->template);
		
	// If viewer is not plain user, don`t show notifications checkbox
	if($hduser['user_level'] != RANK_USER)
		fragment_delete("ticket_notifications", $tpl_edit->template);
		
	$tpl_edit_tags = array( "toolbar_txtformat"		=> $tpl_txtformattb->parsed,
							"ticket_subject"		=> $ticket_subject,
							"reply_content"			=> $reply_content,
							"reply_cur_attach"		=> $reply_cur_attach,
							"lst_ticket_cats"		=> $lst_cats,
							"lst_ticket_priorities"	=> $lst_priorities,
							"flg_ticket_notify"		=> $flg_ticket_notify,
							"max_attach_size"		=> $config['attach_max_size'] . "KB",
							"maxb_attach_size"		=> $config['attach_max_size'] * 1024,
							
							"action"				=> "edit_reply",
							"ticket_id"				=> $tid,
							"reply_id"				=> $rid );
	$tpl_edit->parse($tpl_edit_tags);
	
	echo build_page(content_box($tpl_edit->parsed, $page_title), $page_title);
}
#
# Reply to ticket: Add reply to db ---------------------------------------------------------------------------------
#
elseif($_POST['action'] == "reply" && isset($_POST['tid']) && isset($_POST['btn_submitreply']))
{
	$tid = intval($_POST['tid']);

	$reply_content  = $_POST['reply_content'];
	$reply_author	= $hduser['user_id'];
	$reply_time		= time();
	$reply_attachment = $_FILES['reply_attachment'];

	$ticket_cat			= ($_POST['ticket_cat']>0) ? ($_POST['ticket_cat']) : (1);
	$ticket_priority	= ($_POST['ticket_priority']>0) ? ($_POST['ticket_priority']) : (3);
	$ticket_notify		= (isset($_POST['ticket_notify'])) ? (1) : (0);

	reply_create($tid, $reply_content, $reply_author, $reply_time, $reply_attachment);

	// If poster not user, don`t update ticket notification status
	if($hduser['user_level'] != RANK_USER)
		$sql_notify_snip = "";
	else
		$sql_notify_snip = ", ticket_notify	= $ticket_notify";
	
	// Update ticket cat, priority and notification status
	mysql_query("UPDATE $TABLE_TICKETS SET
				 ticket_cat = $ticket_cat,
				 ticket_priority = $ticket_priority
				 $sql_notify_snip
				 WHERE ticket_id=$tid") or
		error("Cannot update ticket options.");
		
	//
	// Get page of the last reply in this ticket
	//
	$r_creplies = mysql_query("SELECT count(reply_id) AS reply_count FROM $TABLE_REPLIES WHERE ticket_id=$tid") or
						error("Cannot get ticket reply count.");
	$db_creplies = mysql_fetch_object($r_creplies);
	$last_page = ceil($db_creplies->reply_count/$config['replies_pp']);
	
	//
	// Notify users
	//
	$tpl_mail = new tpl("tpl/mail_newreply.tpl");
	
	$hd_dir = dirname($_SERVER['SCRIPT_NAME']);
	$ticket_url = "http://" . $_SERVER['SERVER_NAME'] . $hd_dir . "/showticket.php?tid=$tid&page=$last_page";	
	
	// Get all the users involved in this ticket
	$r_posters = mysql_query("SELECT * FROM $TABLE_REPLIES AS replies
							 LEFT JOIN $TABLE_TICKETS AS tickets ON (tickets.ticket_id = replies.ticket_id)
							 LEFT JOIN $TABLE_USERS AS users ON (users.user_id = replies.reply_author)
							 WHERE replies.ticket_id = $tid AND reply_author != $hduser[user_id]
							 AND replies.reply_author != ticket_tech
							 GROUP BY replies.reply_author") or
					error("Cannot get ticket posters.");
	
	// If poster is not the author of the ticket
	while($db_posters = mysql_fetch_object($r_posters))
	{
		if($db_posters->user_level == RANK_USER)
		{
			if($db_posters->ticket_notify || $db_posters->user_notify)
			{
				$tpl_mail_tags = array( "user_name"		 => $db_posters->user_name,
										"ticket_subject" => $db_posters->ticket_subject,
										"url_ticket"	 => $ticket_url );

				$html_mail = replace_tags($tpl_mail_tags, $tpl_mail->template);				
				
				user_mail($db_posters->user_id, $html_mail, "New ticket reply");
			}
		}
		else
		{
			if($db_posters->user_notify)
			{
				$tpl_mail_tags = array( "user_name"		 => $db_posters->user_name,
										"ticket_subject" => $db_posters->ticket_subject,
										"url_ticket"	 => $ticket_url );

				$html_mail = replace_tags($tpl_mail_tags, $tpl_mail->template);		

				user_mail($db_posters->user_id, $html_mail, "New ticket reply");
			}
		}
	}
	
	// Notify ticket tech even if he didn`t post in ticket
	$r_tech = mysql_query("SELECT * FROM $TABLE_TICKETS AS tickets
						   LEFT JOIN $TABLE_USERS AS users ON (users.user_id = tickets.ticket_tech)
						   WHERE ticket_id=$tid") or
					error("Cannot get ticket tech info.");
	$db_tech = mysql_fetch_object($r_tech);
	if($db_tech->user_notify && $hduser['user_id'] != $db_tech->user_id)
	{
		$tpl_mail_tags = array( "user_name"		 => $db_tech->user_name,
								"ticket_subject" => $db_tech->ticket_subject,
								"url_ticket"	 => $ticket_url );

		$html_mail = replace_tags($tpl_mail_tags, $tpl_mail->template);		

		user_mail($db_tech->user_id, $html_mail, "New ticket reply");
	}

	header("Location: showticket.php?page=$last_page&tid=$tid");
}
#
# Reply to ticket: Show reply page ---------------------------------------------------------------------------------
#
elseif($_GET['action'] == "reply" && isset($_GET['tid']))
{
	$page_title = "Reply to ticket";
	
	$tid = intval($_GET['tid']);
	
	// Load template files
	$tpl_reply			= new tpl("tpl/ticket_reply.tpl");
	$tpl_txtformattb	= new tpl("tpl/toolbar_txtformat.tpl");
	
	$tpl_txtformattb->parse( array("text_box" => "reply_content") );

	// Get ticket notify status and check if ticket exists
	$r_ticket = mysql_query("SELECT ticket_notify FROM $TABLE_TICKETS WHERE ticket_id=$tid") or
					die("Cannot check if ticket exists.");
	if(mysql_num_rows($r_ticket) < 1)
		dialog("This ticket does not exist.", $page_title);	

	// If viewer is not plain user, don`t show notifications checkbox
	if($hduser['user_level'] != RANK_USER)
		fragment_delete("ticket_notifications", $tpl_reply->template);
		
	// Set page tags and build page
	$tpl_reply_tags = array( "toolbar_txtformat"		=> $tpl_txtformattb->parsed,
							 "lst_ticket_cats"			=> $lst_cats,
							 "lst_ticket_priorities"	=> $lst_priorities,
							 "flg_ticket_notify"		=> $flg_ticket_notify,
							 "max_attach_size"			=> $config['attach_max_size'] . "KB",
							 "maxb_attach_size"			=> $config['attach_max_size'] * 1024,
							 "action"					=> "reply",
							 "ticket_id"				=> $tid );
								 
	
	$tpl_reply->parse($tpl_reply_tags);
	echo build_page(content_box($tpl_reply->parsed, $page_title), $page_title);
}
#
# Create new ticket: User clicks submit, add ticket to db -----------------------------------------------------------
#
elseif($_POST['action'] == "new_ticket" && isset($_POST['btn_submitticket']))
{
	$page_title = "Post new ticket";
	
	// Check if X seconds passed before last post
	$delay_time = time() - $config['wait_before_repost']; // X seconds ago
	$r_lastpost = mysql_query("SELECT reply_id, reply_time FROM $TABLE_REPLIES WHERE reply_time>$delay_time") or
					error("Cannot get last post time.");
	
	if(mysql_num_rows($r_lastpost)>0)
	{
		dialog("You must wait $config[wait_before_repost] seconds before posting again.<br>
				This is to prevent accidental double posting.");
	}
	
	
			
	$ticket_subject		= strip_tags($_POST['ticket_subject']);
	$ticket_cat			= ($_POST['ticket_cat']) ? ($_POST['ticket_cat']) : (1);			// If not set, set to "Other"
	$ticket_priority	= ($_POST['ticket_priority']) ? ($_POST['ticket_priority']) : (3);	// If not set, set to "Normal"	
	$ticket_notify		= ($_POST['ticket_notify']) ? (true) : (false);

	$reply_content		= $_POST['ticket_content'];
	$reply_author		= $hduser['user_id'];
	$reply_time			= time();
	$reply_attachment	= $_FILES['ticket_attachment'];

	// Check if subject field filled
	if(empty($ticket_subject))
		dialog("Subject field is required.", $page_title);	

	// Create first reply entry in db
	$ticket_firstreply = reply_create(0, $reply_content, $reply_author, $reply_time, $reply_attachment);

	// Create ticket entry in db
	mysql_query("INSERT INTO $TABLE_TICKETS
				 (ticket_subject, ticket_firstreply, ticket_cat, ticket_priority, ticket_status, ticket_notify) VALUES
				 ('$ticket_subject', $ticket_firstreply, '$ticket_cat', '$ticket_priority'," . STATUS_OPEN. ", '$ticket_notify')") or
		error("Cannot create ticket.");
		
	$reply_ticketid = mysql_insert_id();	// The id of the newly created ticket

	// Update the ticket id in firstreply of the ticket
	mysql_query("UPDATE $TABLE_REPLIES
				 SET ticket_id = $reply_ticketid
				 WHERE reply_id = $ticket_firstreply") or
		error("Cannot update ticket.");
		
	// Show dialog and redirect to ticket
	dialog("Your ticket has been submitted.", $page_title, "View my ticket", "showticket.php?tid=$reply_ticketid");
}
#
# Admin: Update ticket category/priority/status ---------------------------------------------------------------------
#
elseif(isset($_POST['btn_updpriority']))
{
	if($hduser['user_level'] == RANK_USER)
		dialog("Access denied.");	
		
	$tid = intval($_POST['tid']);

	$new_cat	= intval($_POST['new_ticket_cat']);
	$new_pr		= intval($_POST['new_ticket_priority']);
	$new_status	= intval($_POST['new_ticket_status']);
	
	mysql_query("UPDATE $TABLE_TICKETS SET
				 ticket_cat			= $new_cat,
				 ticket_priority	= $new_pr,
				 ticket_status		= $new_status
				 WHERE ticket_id=$tid") or
		error("Cannot update ticket properties.");
	
	dialog("Ticket properties updated.", $page_title);
}
#
# Admin: Assign tech to ticket -------------------------------------------------------------------------------------
#
elseif(isset($_POST['btn_assigntech']) || isset($_POST['btn_assignmyself']))
{
	if($hduser['user_level'] == RANK_USER)
		dialog("Access denied.");
		
	$tid = intval($_POST['tid']);
	
	// If user chose to assign HIMSELF
	if(isset($_POST['btn_assignmyself']))
	{
		$tech_id = $hduser['user_id'];
	}
	else
	{
		$tech_id = intval($_POST['new_ticket_tech']);
		
		// If user wants to be notified
		if($hduser['user_notify'])
		{
			// Get ticket info
			$r_ticket = mysql_query("SELECT tickets.ticket_subject, cat_name, priority_name, user_name
									 FROM $TABLE_TICKETS AS tickets
									 LEFT JOIN $TABLE_CATS AS cats ON (cats.cat_id = tickets.ticket_cat)
									 LEFT JOIN $TABLE_PRIORITIES AS priorities ON (priorities.priority_id = tickets.ticket_priority)
									 LEFT JOIN $TABLE_USERS AS users ON (users.user_id=$tech_id)
									 WHERE ticket_id=$tid") or
							error("Cannot get ticket info.");

			$db_ticket = mysql_fetch_object($r_ticket);
						
			$hd_dir = dirname($_SERVER['SCRIPT_NAME']);
			
			$ticket_url = "http://" . $_SERVER['SERVER_NAME'] . $hd_dir . "/showticket.php?tid=$tid";
			
			$tpl_mail = new tpl("tpl/mail_assignticket.tpl");
			
			$tpl_mail_tags = array( "user_name"			=> $db_ticket->user_name,
									"ticket_subject"	=> $db_ticket->ticket_subject,
									"ticket_category"	=> $db_ticket->cat_name,
									"ticket_priority"	=> $db_ticket->priority_name,
									"url_ticket"		=> $ticket_url );
			$tpl_mail->parse($tpl_mail_tags);

			user_mail($tech_id, $tpl_mail->parsed, "New ticket assigned");
		}
	}
	
	mysql_query("UPDATE $TABLE_TICKETS SET
				 ticket_tech=$tech_id
				 WHERE ticket_id=$tid") or
		error("Cannot update ticket tech.");
		
	dialog("Ticket properties updated.", $page_title);
}
#
# Admin: Hide ticket -------------------------------------------------------------------------------------------------
#
elseif(isset($_POST['btn_toggleticket']))
{
	if($hduser['user_level'] == RANK_USER)
		dialog("Access denied.");
		
	$tid	= intval($_POST['tid']);
	$page	= intval($_POST['page']);
	
	// Get current status
	$r_status = mysql_query("UPDATE $TABLE_TICKETS SET
							 ticket_hide = abs(ticket_hide-1)
							 WHERE ticket_id=$tid") or
					error("Cannot change ticket visibility.");
					
	header("Location: showticket.php?page=$page&tid=$tid");
}
#
# Admin: Delete ticket ----------------------------------------------------------------------------------------------
#
elseif($_GET['action']=="delete_ticket" && !empty($_GET['tid']))
{
	if($hduser['user_level'] == RANK_USER)
		dialog("Access denied.");
		
	$tid = intval($_GET['tid']);
	
	// Get ticket replies and delete them
	$r_replies = mysql_query("SELECT reply_id FROM $TABLE_REPLIES WHERE ticket_id=$tid") or
					error("Cannot get ticket replies list.");
	
	while($db_replies = mysql_fetch_object($r_replies))
	{
		reply_delete($db_replies->reply_id);
	}

	dialog("Ticket deleted.", "Ticket deletion", "My Tickets", "mytickets.php");
}
#
# Create new ticket: Show post NEW ticket page ----------------------------------------------------------------------
#
else
{
	$page_title = "Post new ticket";

	// Only users can post new tickest
	if($hduser['user_level'] != RANK_USER)
		dialog("You cannot make new tickets.", $page_title);
	
	$tpl_newticket		= new tpl("tpl/ticket_new.tpl");
	$tpl_txtformattb	= new tpl("tpl/toolbar_txtformat.tpl");
	
	$tpl_txtformattb->parse( array("text_box" => "ticket_content") );
	
	// Set page tags and build page
	$tpl_newticket_tags = array( "toolbar_txtformat"		=> $tpl_txtformattb->parsed,
								 "lst_ticket_cats"			=> $lst_cats,
								 "lst_ticket_priorities"	=> $lst_priorities,
								 "max_attach_size"			=> $config['attach_max_size'] . "KB",
								 "maxb_attach_size"			=> $config['attach_max_size'] * 1024,
								 "action"					=> "new_ticket",
								 "ticket_id"				=> 0);
	
	$tpl_newticket->parse($tpl_newticket_tags);

	echo build_page(content_box($tpl_newticket->parsed, "Post new ticket"), "Post new ticket");
}
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.
?>