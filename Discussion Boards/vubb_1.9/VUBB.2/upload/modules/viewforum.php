<?php
/*
Copyright 2005 VUBB
*/

get_template('viewforum');

// make sure topic and forumroot is numeric
if (!ctype_digit($_GET['f']))
{
	error($lang['title']['no_forum_id'],$lang['text']['no_forum_id']);
}

// Get the forum and category name from the url
$forum_name = mysql_fetch_array(mysql_query("SELECT `name`,`category` FROM `forums` WHERE `id` = '" . addslashes($_GET['f']) . "'")) or die(mysql_error());
$category_name = mysql_fetch_array(mysql_query("SELECT `name` FROM `forums` WHERE `id` = '" . $forum_name['category'] . "'"));

// permissions
$permissions = mysql_fetch_array(mysql_query("SELECT * FROM `permissions` WHERE `group` = '" . $stat['group'] . "' AND `forum` = '" . $_GET['f'] . "'"));

// can this users group view the forum?
if ($permissions['cview'] == '0' || $permissions['cview'] == null)
{
	message($lang['title']['no_view'],$lang['text']['no_view']);
}

else
{	
	table_start();
	
	$tsel = mysql_query("SELECT * FROM `forum_topics` WHERE `forumroot` = '" . $_GET['f'] . "' AND `sticky` = '1' ORDER BY `lastdate` DESC");
	while ($topic = mysql_fetch_assoc($tsel)) 
	{
		// to find out if a the sticky topic has a reply
		$find_topic = mysql_fetch_array(mysql_query("SELECT * FROM `forum_topics` WHERE `id` = '" . $topic['id'] . "' ORDER BY `lastdate` DESC LIMIT 1"));
		
		$find_replies = mysql_fetch_array(mysql_query("SELECT `replies` FROM `forum_topics` WHERE `id` = '" . $topic['id'] . "' ORDER BY `lastdate` DESC LIMIT 1"));
		if ($find_replies['replies'] >= '1')
		{
			$last_info = mysql_fetch_array(mysql_query("SELECT `starter_id` FROM `forum_replies` WHERE `topic_id` = '" . $find_topic['id'] . "' ORDER BY `id` DESC LIMIT 1"));
			$last_info2 = mysql_fetch_array(mysql_query("SELECT * FROM `members` WHERE `id` = '" . $last_info['starter_id'] . "'"));
			$last_info['topic'] = $find_topic['topic'];
			$last_info['id'] = $find_topic['id'];
		}
		
		else
		{
			$last_info = $find_topic;
			$last_info2 = mysql_fetch_array(mysql_query("SELECT * FROM `members` WHERE `id` = '" . $last_info['starter_id'] . "'"));
		}

		$replies = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS `count` FROM `forum_replies` WHERE `topic_id` = '" . $topic['id'] . "'"));
		$views = mysql_fetch_array(mysql_query("SELECT `views` FROM `forum_topics` WHERE `id` = '" . $topic['id'] . "'"));
		
		$topic['topic'] = stripslashes($topic['topic']);
		
		sticky_row();	
	}
	
	pagination_start("forum_topics", "WHERE `forumroot` = '" . $_GET['f'] . "' AND `sticky` != '1'", "ORDER BY `lastdate` DESC", "10", "index.php?act=viewforum", "&forumroot=" . $_GET['f'] . "");
	while ($topic = mysql_fetch_assoc($pagination_query)) 
	{
		// to find out if a the topic has a reply
		$find_topic = mysql_fetch_array(mysql_query("SELECT * FROM `forum_topics` WHERE `id` = '" . $topic['id'] . "' ORDER BY `lastdate` DESC LIMIT 1"));
		
		$find_replies = mysql_fetch_array(mysql_query("SELECT `replies` FROM `forum_topics` WHERE `id` = '" . $topic['id'] . "' ORDER BY `lastdate` DESC LIMIT 1"));
		if ($find_replies['replies'] >= '1')
		{
			$last_info = mysql_fetch_array(mysql_query("SELECT `starter_id` FROM `forum_replies` WHERE `topic_id` = '" . $find_topic['id'] . "' ORDER BY `id` DESC LIMIT 1"));
			$last_info2 = mysql_fetch_array(mysql_query("SELECT * FROM `members` WHERE `id` = '" . $last_info['starter_id'] . "'"));
			$last_info['topic'] = $find_topic['topic'];
			$last_info['id'] = $find_topic['id'];
		}
		
		else
		{
			$last_info = $find_topic;
			$last_info2 = mysql_fetch_array(mysql_query("SELECT * FROM `members` WHERE `id` = '" . $last_info['starter_id'] . "'"));
		}
		
		$replies = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS `count` FROM `forum_replies` WHERE `topic_id` = '" . $topic['id'] . "'"));
		$views = mysql_fetch_array(mysql_query("SELECT `views` FROM `forum_topics` WHERE `id` = '" . $topic['id'] . "'"));

		$topic['topic'] = stripslashes($topic['topic']);
		
		normal_row();	
	}
	
	end_table();
}
?>