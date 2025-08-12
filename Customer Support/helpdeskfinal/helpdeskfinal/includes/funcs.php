<?php
//
// Project: Help Desk support system
// Description: General functions
//
require_once "config.php";
require_once "tpl.php";

#
# Load configuration into an array ----------------------------------------------------------------------------------
#
function config_load()
{
	global $TABLE_CONFIG;
	
	$r_config = mysql_query("SELECT * FROM $TABLE_CONFIG") or
					error("Cannot load configuration.");
					
	while($db_config = mysql_fetch_object($r_config))
	{
		$config[$db_config->config_name] = $db_config->config_value;
	}
	
	return $config;
}

#
# Create topic reply entry in db ------------------------------------------------------------------------------------
#
function reply_create($ticket_id, $reply_content, $reply_author, $reply_time, $reply_attachment)
{
	global $TABLE_REPLIES;

	// Various checks
	$content_len = strlen($reply_content);
	if(empty($reply_content))
		dialog("You cannot submit an empty reply.");
	elseif($content_len > 65535)
		dialog("Max text length is 65535 characters. Your text is $content_len characters long.");

	// Upload attachment and get id	
	if(!empty($reply_attachment))
		$reply_attachment = attach_handle($reply_attachment);	
	
	// Strip tags other than those used by the editor
	$reply_content = strip_tags($reply_content, "<b><i><u><div><a><img><ol><ul><li><font>");
	
	mysql_query("INSERT INTO $TABLE_REPLIES
				(ticket_id, reply_content, reply_author, reply_time, reply_attachment) VALUES
				('$ticket_id', '$reply_content', '$reply_author', '$reply_time', '$reply_attachment')") or
		error("Cannot add ticket reply.");

	return mysql_insert_id();
}
#
# Delete ticket replies and everything related to it (tickets, attachments)
#
function reply_delete($reply_id)
{
	global $TABLE_REPLIES, $TABLE_TICKETS;

	$reply_id = intval($reply_id);
	
	// Get ticket info
	$r_reply = mysql_query("SELECT tickets.ticket_subject, tickets.ticket_id, tickets.ticket_firstreply, count(replies2.reply_id) AS reply_count,
							replies2.reply_attachment, min(replies.reply_id) AS next_reply
							FROM $TABLE_REPLIES AS replies
							LEFT JOIN $TABLE_TICKETS AS tickets ON (tickets.ticket_id = replies.ticket_id)
							LEFT JOIN $TABLE_REPLIES AS replies2 ON (replies2.ticket_id = tickets.ticket_id)
							WHERE replies2.reply_id=$reply_id
							GROUP BY replies2.reply_id") or
					error("Cannot get ticket info.");
	$db_reply = mysql_fetch_object($r_reply);
	
	// If reply has attachment, delete it
	if($db_reply->reply_attachment > 0)
		attach_delete($db_reply->reply_attachment);

	// If this reply is the only one in a ticket, delete that ticket
	if($db_reply->reply_count <= 1)
	{
		mysql_query("DELETE FROM $TABLE_TICKETS WHERE ticket_id=$db_reply->ticket_id") or
			error("Cannot delete ticket.");
		
		$ticket_deleted = true;
	}
	elseif($db_reply->ticket_firstreply == $reply_id)
	{
		// If this reply is the first in the ticket, set the next reply as first
		mysql_query("UPDATE $TABLE_TICKETS SET ticket_firstreply=$db_reply->next_reply
					 WHERE ticket_id=$db_reply->ticket_id") or
			die("Cannot set first reply to next post.");
	}
	
	// Delete reply
	mysql_query("DELETE FROM $TABLE_REPLIES WHERE reply_id=$reply_id") or
		error("Cannot delete reply.");
		
	// If ticket was deleted return false
	if($ticket_deleted)
		return true;
	else
		return false;
}
#
# Handle uploaded file ----------------------------------------------------------------------------------------------
# Copies the file into the attach. dir and makes sure it has a unique name
function attach_handle($uploaded_file)
{
	global $PATH_ATTACHMENTS, $TABLE_ATTACHMENTS;

	$config = config_load();
	
	if(is_uploaded_file($uploaded_file['tmp_name']))
	{
		// Check for max attachment limit
		$uploaded_filesize = number_format($uploaded_file[size]/1024, 1);
		if(($uploaded_file['size'] / 1024) > $config['attach_max_size'])
			dialog("Your attachment is too big. <br>Max file size: $config[attach_max_size] KB
					<br>Your file size: $uploaded_filesize KB", $page_title);
			
		$file_name	= $uploaded_file['name'];
		$file_ext	= substr(strrchr($file_name, "."), 1);
		
		// Sets filename to md5 hash of current timestamp. Guaranteed to be unique
		$file_new_name	= md5(time()) . "." . $file_ext;
		
		// Everything fine, move uploaded file to the right dir
		move_uploaded_file($uploaded_file['tmp_name'], "$PATH_ATTACHMENTS/$file_new_name") or
			error("Cannot move uploaded file to attachments folder.");
		
		// Create database entry
		mysql_query("INSERT INTO $TABLE_ATTACHMENTS
					 (attach_file, attach_origname) VALUES
					 ('$file_new_name', '$file_name')") or
			error("Cannot add attachment to database.");

		return mysql_insert_id();
	}
	else
		return 0;
}

#
# Delete attachment file and db entry --------------------------------------------------------------------------------
#
function attach_delete($attach_id)
{
	global $TABLE_ATTACHMENTS, $TABLE_REPLIES, $PATH_ATTACHMENTS;

	// Get attachment filename
	$r_attach = mysql_query("SELECT * FROM $TABLE_ATTACHMENTS WHERE attach_id=$attach_id") or
					error("Cannot get attachment info.");
	$db_attach = mysql_fetch_object($r_attach);
	
	// Delete attachment from any reply it maybe used in
	mysql_query("UPDATE $TABLE_REPLIES SET reply_attachment=0 WHERE reply_attachment=$attach_id") or
		error("Cannot remove attachment references from ticket replies.");
		
	$file_path = "$PATH_ATTACHMENTS/$db_attach->attach_file";
	
	// Delete File
	@unlink($file_path);
	// Delete db entry
	mysql_query("DELETE FROM $TABLE_ATTACHMENTS WHERE attach_id=$attach_id") or
		error("Cannot delete attachment db entry.");
		
	return true;
}

#
# Redirects browser ------------------------------------------------------------------------------------------------
#
function redir($url, $delay=0)
{
	$tpl_redir = new tpl("tpl/redir.tpl");
	
	$tpl_redir_tags = array("redir_url"		=> $url,
							"redir_delay"	=> $delay);
							
	$tpl_redir->parse($tpl_redir_tags);
	
	echo $tpl_redir->parsed;
}

#
# Builds page links ------------------------------------------------------------------------------------------------
#
function pagination($current_page, $total_pages, $querystring="")
{
	$page_start = 1;				// Page to start counting from
	$page_range = 10;				// Range of pages to show
	$page_range = ($page_range < $total_pages) ? ($page_range) : ($total_pages);

	$querystring = (empty($querystring)) ? ("") : ("&$querystring");

	if($current_page > $page_range)
		$page_start = ($current_page - floor($page_range/2));
	
	for($i=$page_start; $i<$page_start + $page_range ; $i++)
	{
		$pagination_html .= ($i!=$current_page) ? ("<a href=\"?page=$i" . "$querystring\">$i</a>&nbsp;") : ("$i&nbsp;");
	}
	
	// Add next page link
	$next_page = $current_page + 1;
	if($current_page < $total_pages)
		$pagination_html .= "<a href=\"?page=$next_page" . "$querystring\">Next >></a>";
	
	// Add previous page link
	$prev_page = $current_page - 1;
	if($current_page > $page_start)
		$pagination_html = "<a href=\"?page=$prev_page" . "$querystring\"><< Previous&nbsp;</a>" . $pagination_html;
		
	return $pagination_html;
}

#
# Converts newlines to <br> elements for viewing on browsers --------------------------------------------------------
#
function newline2br($string)
{
	return str_replace("\n", "\n<br />", $string);
}

#
# Sends email to a user ---------------------------------------------------------------------------------------------
#
function user_mail($user_id, $mail_content, $mail_subject="Helpdesk notification message")
{
	global $TABLE_USERS;
	$config = config_load();
	
	// Strip html tags, for plain text
	$mail_content = strip_tags($mail_content);
	
	// Get user address
	$r_user = mysql_query("SELECT user_email FROM $TABLE_USERS WHERE user_id=$user_id") or
					error("Cannot get user`s email address.");
	$db_user = mysql_fetch_object($r_user);
	
	// Send mail to user
	//mail($db_user->user_email, $mail_subject, $mail_content, "From: $config[helpdesk_email]");
}
?>