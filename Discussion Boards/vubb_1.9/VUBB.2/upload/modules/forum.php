<?php
// bring in the template needed
get_template('forum');

// nav table
start_table();

// Get the categories
$get_cats = mysql_query("SELECT * FROM `forums` WHERE `is_cat` = '1' ORDER BY `order` ASC");
while ($display_cats = mysql_fetch_array($get_cats))
{
	$display_cats['name'] = stripslashes($display_cats['name']);
	
	// start the category table
	display_cats();

	$get = mysql_query("SELECT * FROM `forums` where `is_cat` = '0' AND `category` = '".$display_cats['id']."' ORDER BY `order` ASC");
	while ($display_forums = mysql_fetch_array($get))
	{
		$result = mysql_fetch_array(mysql_query("SELECT `topics`,`replies` FROM `forums` WHERE `id` = '".$display_forums['id']."'"));
		
		$display_forums['name'] = stripslashes($display_forums['name']);
		$display_forums['description'] = stripslashes($display_forums['description']);
		
		// to find out if a the latest topic has a reply
		$find_topic = mysql_fetch_array(mysql_query("SELECT * FROM `forum_topics` WHERE `forumroot` = '" . $display_forums['id'] . "' ORDER BY `lastdate` DESC LIMIT 1"));
		
		$find_replies = mysql_fetch_array(mysql_query("SELECT `replies` FROM `forum_topics` WHERE `forumroot` = '" . $display_forums['id'] . "' ORDER BY `lastdate` DESC LIMIT 1"));
		if ($find_replies['replies'] >= '1')
		{
			$last_info = mysql_fetch_array(mysql_query("SELECT * FROM `forum_replies` WHERE `topic_id` = '" . $find_topic['id'] . "' ORDER BY `id` DESC LIMIT 1"));
			$last_info2 = mysql_fetch_array(mysql_query("SELECT * FROM `members` WHERE `id` = '" . $last_info['starter_id'] . "'"));
			$last_info['topic'] = $find_topic['topic'];
			$last_info['id'] = $find_topic['id'];
		}
		
		else
		{
			$last_info = $find_topic;
			$last_info2 = mysql_fetch_array(mysql_query("SELECT * FROM `members` WHERE `id` = '" . $last_info['starter_id'] . "'"));
		}
		
		if ($display_forums['is_link'] == '1')
		{	
			// bring in the template for showing link forums		
			display_forums_link();
		}
		
		else
		{
			// bring in the template for showing the forums		
			display_forums();
		}
	}
	
	// end the category table
	end_cat_table();
}

// end it all, closes divs and anything else needed
end_all();
?>