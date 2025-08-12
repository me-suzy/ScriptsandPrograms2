<?php
/*
** sCssBoard, an extremely fast and flexible CSS-based message board system
** Copyright (CC) 2005 Elton Muuga
**
** This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike License. 
** To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/2.0/ or send 
** a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
*/
?>
<?php
    if($_GET[f] == "") {} else {
        $forum = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]forums where forums_id = $_GET[f]"));
		if ($forum[forums_p_read] <= $ulvl) {
        $topic = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]posts where posts_id = $_GET[t] and posts_forum = $_GET[f] and posts_main = 'yes'"));
            
		if ($forum[forums_p_topic] <= $ulvl)
			echo "<p align='right' style='margin-bottom:5px; margin-top:0px;'><span class='main_button'><a href='index.php?&amp;act=post&amp;type=topic&amp;f=$_GET[f]'>New Topic</a></span></p>";
        $get_cat = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]forums where forums_id = $_GET[f]"));
		$category_query = @mysql_query("select * from $_CON[prefix]categories where category_id = '$get_cat[forums_category]'");
		while($cat_show = @mysql_fetch_array($category_query)) {

                echo "
<table width='100%' border='0' cellpadding='0' cellspacing='1' align='center'>
	<tr>
		<td class='catheader' height='30' width='800' colspan='4'>&nbsp; <b>$get_cat[forums_name]</b></td>
	</tr>
	<tr>
		<td height='20' width='60%' align='center' class='topic_stat_hd'><strong>Topic Name</strong></td>
		<td height='20' width='10%' align='center' class='topic_stat_hd'><strong>Replies</strong></td>
		<td height='20' width='10%' align='center' class='topic_stat_hd'><strong>Views</strong></td>
		<td height='20' width='20%' align='center' class='topic_stat_hd'><strong>Latest Post</strong></td>
	</tr>";

				if (!$start)
					$start = 0;
				if (!$current_user)
					$tpp = 15;
				else
					$tpp = $current_user[users_tpp];
				$end = $start + $tpp; 

				$all_topics = @mysql_query("select * from $_CON[prefix]posts where posts_forum = $_GET[f] and posts_main = 'yes' order by posts_topic_lastpost desc");
				$topic_query = @mysql_query("select * from $_CON[prefix]posts where posts_forum = $_GET[f] and posts_main = 'yes' order by posts_topic_lastpost desc limit $start,$end");
				$num_topics = @mysql_num_rows($all_topics);
								
				while($topic_show = @mysql_fetch_array($topic_query)) {
					$total_replies_topic = @mysql_num_rows(@mysql_query("select * from $_CON[prefix]posts where posts_forum = $_GET[f] and posts_topic = '$topic_show[posts_topic]' and posts_main = 'no'"));
					$latest_reply = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]posts where posts_id = $topic_show[posts_topic_lastpost]"));
					$topic_poster = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users where users_id = '$topic_show[posts_starter]'"));
					if (!$topic_poster) $starter = "$topic_show[posts_starter] (Guest)"; 
					else $starter = $topic_poster[users_username];
					$latest_reply_poster = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users where users_id = '$latest_reply[posts_starter]'"));
					if ($latest_reply_poster)
						$lrp = $latest_reply_poster[users_username];
					else
						$lrp = "$latest_reply[posts_starter] (Guest)";
					if ($latest_reply) {
						$latest_reply[posts_starter] = $topic_poster[users_username];
						$latest_reply[posts_posted] = get_date($latest_reply[posts_posted],$_MAIN[date_format],$_MAIN[use_relative_dates]);
					}
					echo "
<tr>
	<td height='30' align='left' class='forum_name'>
		&nbsp; <span class='topic_name'>
		<a href='index.php?&amp;act=showforum&amp;f=$topic_show[posts_forum]&amp;t=$topic_show[posts_topic]'>$topic_show[posts_name]</a>
		</span> by $starter
	</td>
	<td height='30' align='center' class='forum_name'>$total_replies_topic</td>
	<td height='30' align='center' class='forum_name'>$topic_show[posts_views]</td>";

if ($latest_reply)
	echo "<td height='30' align='left' class='forum_name'>&nbsp;by $lrp<br />&nbsp;$latest_reply[posts_posted]</td>";
	
					echo "</tr>";
				}
				if ($num_topics > $tpp) { 
					echo "<tr><td>";
					$curr_page = 1; //Page that we start with is page 1
					$topics_to_go = $num_topics;
					echo "Pages: ";
					while ($topics_to_go > 0) {
						$topics_start = abs($topics_to_go - $num_topics);
						if ($topics_start != $start)
							echo "<a href='?act=showforum&amp;f=$_GET[f]&amp;start=$topics_start'>$curr_page</a>&nbsp;";
						else
							echo "$curr_page&nbsp;";
						$topics_to_go = $topics_to_go - $tpp; 
						$curr_page = $curr_page + 1;
					}
					echo "</td></tr>";
				}

				echo "</table>";

if ($forum[forums_p_topic] <= $ulvl)
	echo "<p align='right'style='margin-top:5px; margin-bottom:0px;'><span class='main_button'><a href='index.php?&amp;act=post&amp;type=topic&amp;f=$_GET[f]'>New Topic</a></span></p>";
				}

		} else {
			echo "You are not authorized to view this forum.";
		}
    }
 ?>