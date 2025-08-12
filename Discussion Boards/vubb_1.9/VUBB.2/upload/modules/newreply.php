<?php
/*
Copyright 2005 VUBB
*/
// permissions
$permissions = mysql_fetch_array(mysql_query("SELECT `cpost`,`cview` FROM `permissions` WHERE `group` = '" . $user_info['group'] . "' AND `forum` = '" . $_GET['f'] . "'"));
	
if ($permissions['creply'] == '0')
{
	message($lang['title']['no_post'],$lang['text']['no_post']);
}

else
{

	if (!isset($_GET['action']))
	{
		// Get the forum and category name from the url
		$forum_name = mysql_fetch_array(mysql_query("SELECT `name`,`category` FROM `forums` WHERE `id` = '".$_GET['f']."'")) or die(mysql_error());
		$category_name = mysql_fetch_array(mysql_query("SELECT `name` FROM `forums` WHERE `id` = '".$forum_name['category']."'"));
		
		// smilies and bbcode viewing
		display_clickable_smilies_bbcode();
		
		get_template('newreply');
	}
	
	// Add Reply
	if ($_GET['action'] == 'reply') 
	{
		$locker_exists = mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_topics` WHERE `id` = '".$_GET['t']."'"));
		
		// if post locked
		if ($locker_exists['locked'] == '1') 
		{
			message($lang['title']['topic_locked'], $lang['text']['locked']);
		}
		
		// if no topic id set
		else if (!isset($_GET['t']))
		{
			message($lang['title']['no_topic_id'], $lang['title']['no_topic_id']);
		}
		
		// if there is no locker meaning no post
		else if (empty($locker_exists)) 
		{
			message($lang['title']['no_topic_id'], $lang['title']['no_topic_id']);
		}
		
		// if post is empty
		else if (empty($_POST['body1'])) 
		{
			message($lang['title']['no_body'], $lang['text']['no_body']);
		}
		
		else
		{
			// post parsing
			main_post_parser();
			
			// Insert main reply info
			mysql_query("INSERT INTO `forum_replies` (starter, topic_id, starter_id, forumroot, date, time) VALUES('".addslashes($user_info['user'])."', '".$_GET['t']."', '".$user_info['id']."','".$_GET['f']."','".$fadyd."','".$fadyt."')") or die(mysql_error());
			
			// Update the last post time
			mysql_query("UPDATE `forum_topics` SET `lastdate` = '".$absolutetime."', `replies` = (replies + 1) WHERE `id` = '".$_GET['t']."'") or die(mysql_error());
			
			// Find that replys id
			$grab_next_id = mysql_fetch_array(mysql_query("SELECT `id` FROM `forum_replies` ORDER BY `id` DESC LIMIT 1")) or die(mysql_error());
			
			// Insert the reply body
			// Addlsashes done in main_bbcode() function
			mysql_query("INSERT INTO `forum_reply_text` set `reply_id` = '".$grab_next_id['id']."', `topic_id` = '".$_GET['t']."', `body` = '".$_POST['body1']."'") or die(mysql_error());
			
			// Now add 1 to reply count
			mysql_query("UPDATE `forums` SET `replies` = (replies + 1) WHERE id = '".$_GET['f']."'") or die(mysql_error());
			
			message($lang['title']['reply_added'], $lang['text']['reply_added']);
		}
	}
}
?>