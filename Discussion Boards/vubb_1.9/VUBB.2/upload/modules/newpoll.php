<?php
/*
Copyright 2005 VUBB
*/
// permissions
$permissions = mysql_fetch_array(mysql_query("SELECT `cpost`,`cview` FROM `permissions` WHERE `group` = '" . $user_info['group'] . "' AND `forum` = '" . $_GET['f'] . "'"));
	
if ($permissions['cpost'] == '0' || $permissions['cview'] == '0')
{
	message($lang['title']['no_post'],$lang['text']['no_post']);
}

else
{

	// Get the forum and category name from the url
	$forum_name = mysql_fetch_array(mysql_query("SELECT `name`,`category` FROM `forums` WHERE `id` = '" . $_GET['f'] . "'")) or die(mysql_error());
	$category_name = mysql_fetch_array(mysql_query("SELECT `name` FROM `forums` WHERE `id` = '" . $forum_name['category'] . "'"));
	display_clickable_smilies_bbcode();
	
	if (!isset($_GET['action']))
	{
		get_template('newpoll');
	}
	
	// Add Topic
	if ($_GET['action'] == "addpoll") 
	{
		if (!$_POST['poll_question'] || !$_POST['body1'] || !$_POST['poll_choices']) 
		{
			message($lang['title']['missing'],$lang['text']['missing']);
		}
		
		else
		{
			// post parsing
			main_post_parser();
			
			// Insert the topic main info
			mysql_query("INSERT INTO `forum_topics` SET `topic` = '" . addslashes($_POST['poll_question']) . "', `starter` = '" . addslashes($user_info['user']) . "', `starter_id` = '".$user_info['id']."', `forumroot` = '".$_GET['f']."', `date` = '".$fadyd."', `time` = '".$fadyt."', `lastdate` = '".$absolutetime."', `poll` = '1'") or die(mysql_error());
			
			// Find that topics id
			$grab_next_id = mysql_fetch_array(mysql_query("SELECT `id` FROM `forum_topics` ORDER BY `id` DESC LIMIT 1")) or die(mysql_error());		
			
			// Now insert the body of the post.
			// Addslashes for this done on main_bbcode() function
			mysql_query("INSERT INTO `forum_topic_text` SET `topic_id` = '" . $grab_next_id['id'] . "', `body` = '" . $_POST['body1'] . "'") or die(mysql_error());
			 
			// Insert the main poll info
			mysql_query("INSERT INTO `polls` SET `name` = '" . addslashes($_POST['poll_question']) . "', `topic_id` = '" . $grab_next_id['id'] . "'");
			
			// Find that polls id
			$grab_nextp_id = mysql_fetch_array(mysql_query("SELECT `id` FROM `polls` ORDER BY `id` DESC LIMIT 1")) or die(mysql_error());		

			// Insert poll choices
			$choices = explode("\n", $_POST['poll_choices']);
			
			foreach ($choices as $choice)
			{
				$choice = trim($choice);
				mysql_query("INSERT INTO `poll_choices` SET `poll_id` = '" . $grab_nextp_id['id'] . "', `choice` = '" . $choice . "'");
			}
			
			// Now add 1 to topic count
			mysql_query("UPDATE `forums` SET `topics` = (topics + 1) WHERE `id` = '".$_GET['f']."'") or die(mysql_error());

			
			message($lang['title']['topic_added'],$lang['text']['topic_added']);
		}
	}
}
?>