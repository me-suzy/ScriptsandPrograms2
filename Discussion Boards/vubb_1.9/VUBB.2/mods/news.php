<?php
// Get required files
require_once('./forum/includes/functions.php');
require_once('./forum/includes/post_parser.php');
require_once('./forum/config.php');

// How many to display
$limit = "3";

// Forum to grab topics from
$forumroot = "4";

// forum address
$forum_address = "http://vubb.com/forum/index.php?act=viewforum&f=" . $forumroot . "";

// include sticky topics?
$stickys = "no";

if ($stickys = "no")
{
	$stick = "0";
}

else
{
	$stick = "1";
}

// topic info querys
$select = mysql_query("SELECT * FROM `forum_topics` WHERE `forumroot` = '" . $forumroot . "' AND `sticky` = '" . $stick . "' order by `lastdate` DESC LIMIT " . $limit . "");
while ($get = mysql_fetch_array($select))
{
	// get topic text
	$post_body = mysql_fetch_array(mysql_query("SELECT * FROM `forum_topic_text` WHERE `topic_id` = '" . $get['id'] . "'"));
	
	$post_body['body'] = view_post($post_body['body']);
	
	// show the topics
	echo "<a href='" . $forum_address . "'><strong>" . $get['topic'] . "</strong></a><br />
	" . $post_body['body'] . "
	<br /><br />";
}
?>