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
		echo eval(get_template('newtopic'));
	}
	
	// Add Topic
	if ($_GET['action'] == "addtopic") 
	{
		if (!$_POST['title2'] || !$_POST['body1']) 
		{
			message($lang['title']['missing'],$lang['text']['missing']);
		}
		
		else if ($permissions['cpost'] == '0' || $permissions['cview'] == '0')
		{
			message($lang['title']['no_post'],$lang['text']['no_post']);
		}
		
		else
		{
			// post parsing
			main_post_parser();
			
			// Insert the topic main info
			mysql_query("INSERT INTO `forum_topics` SET `topic` = '".addslashes($_POST['title2'])."', `starter` = '".addslashes($user_info['user'])."', `starter_id` = '".$user_info['id']."', `forumroot` = '".$_GET['f']."', `date` = '".$fadyd."', `time` = '".$fadyt."', `lastdate` = '".$absolutetime."'") or die(mysql_error());
			
			// Find that topics id
			$grab_next_id = mysql_fetch_array(mysql_query("SELECT `id` FROM `forum_topics` ORDER BY `id` DESC LIMIT 1")) or die(mysql_error());		
			
			// Now insert the body of the post.
			// Addslashes for this done on main_bbcode() function
			mysql_query("INSERT INTO `forum_topic_text` SET `topic_id` = '".$grab_next_id['id']."', `body` = '".$_POST['body1']."'") or die(mysql_error());
			 
			// Now add 1 to topic count
			mysql_query("UPDATE `forums` SET `topics` = (topics + 1) WHERE `id` = '".$_GET['f']."'") or die(mysql_error());

			message($lang['title']['topic_added'],$lang['text']['topic_added']);
		}
	}
}
?>