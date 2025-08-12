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
	if ($_GET[viewcat]) {
    $category_query = @mysql_query("select * from $_CON[prefix]categories where category_id = '$_GET[viewcat]'");
	} else {
	$category_query = @mysql_query("select * from $_CON[prefix]categories order by category_order");
	}
    while($cat_show = @mysql_fetch_array($category_query)) {
		$forum_query = @mysql_query("select * from $_CON[prefix]forums where forums_category = '$cat_show[category_id]' and forums_p_read <= '$current_user[users_level]' order by forums_order");
		if (@mysql_num_rows($forum_query) > 0) { ?>
        
<table width='100%' border='0' cellpadding='0' cellspacing='1' align='center'>
	<tr>
		<td class='catheader' height='30' width='100%' colspan='4'>
			&nbsp;<strong><a href='?viewcat=<?=$cat_show[category_id]?>'><?=$cat_show[category_name]?></a></strong>
		</td>
	</tr>
	<tr>
		<td height='20' width='60%' align='center' class='forum_stat_hd'><strong>Forum Name</strong></td>
		<td height='20' width='10%' align='center' class='forum_stat_hd'><strong>Topics</strong></td>
		<td height='20' width='10%' align='center' class='forum_stat_hd'><strong>Replies</strong></td>
		<td height='20' width='20%' align='center' class='forum_stat_hd'><strong>Last Post</strong></td>
	</tr>
					<?
                        while($forum_show = @mysql_fetch_array($forum_query)) {
                            $total_topics_forum = @mysql_num_rows(@mysql_query("select * from $_CON[prefix]posts where posts_forum = '$forum_show[forums_id]' and posts_main = 'yes'"));
                            $total_posts_forum = @mysql_num_rows(@mysql_query("select * from $_CON[prefix]posts where posts_forum = '$forum_show[forums_id]' and posts_main = 'no'"));
                            $last_post = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]posts where posts_forum = '$forum_show[forums_id]' and posts_main = 'yes' order by posts_topic_lastpost desc"));
                            $last_post_detail = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]posts where posts_id = '$last_post[posts_topic_lastpost]'"));
							$last_post_detail[posts_posted] = get_date($last_post_detail[posts_posted],$_MAIN[date_format],$_MAIN[use_relative_dates]);
                            ?>
							
	<tr>
		<td height='30' align='left' class='forum_name' width='450'>
			&nbsp; <strong><a href='index.php?&amp;act=showforum&amp;f=<?=$forum_show[forums_id]?>'><?=$forum_show[forums_name]?></a></strong>
			<? if ($forum_show[forums_description]) { echo "<br /><span class='forum_description'>$forum_show[forums_description]</span>"; } ?>
		</td>
		<td height='30' align='center' class='forum_name' width='75'><?=$total_topics_forum?></td>
		<td height='30' align='center' class='forum_name' width='75'><?=$total_posts_forum?></td>
		<td height='30' align='left' class='forum_name' width='200'>
			<? 
			if ($last_post) {
				echo "in <a href='index.php?&amp;act=showforum&amp;f=$forum_show[forums_id]&amp;t=$last_post_detail[posts_topic]'>$last_post[posts_name]</a><br />
				$last_post_detail[posts_posted]";
			} else {
				echo "Forum is Empty.";
			}
			?>						
		</td>
	</tr>
<? } ?>
</table><br />
<? } } ?>

	<? include("functions/global/show_stats.php"); ?>
<br />