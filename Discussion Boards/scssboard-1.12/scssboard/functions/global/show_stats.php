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

$total_forums = @mysql_num_rows(@mysql_query("select * from $_CON[prefix]forums"));
$total_topics = @mysql_num_rows(@mysql_query("select * from $_CON[prefix]posts where posts_main = 'yes'"));
$total_posts = @mysql_num_rows(@mysql_query("select * from $_CON[prefix]posts"));
$total_replies = @mysql_num_rows(@mysql_query("select * from $_CON[prefix]posts where posts_main = 'no'"));
$total_members = @mysql_num_rows(@mysql_query("select * from $_CON[prefix]users"));
$newest_member = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users order by users_id desc"));

echo "
<div class='catheader' style='padding-top:5px; padding-bottom:5px;'>
	&nbsp; <strong> Board Statistics</strong>
</div>
<div class='statbox'>
	<strong>Total Forums:</strong> $total_forums<br />
	<strong>Total Posts:</strong> $total_posts ($total_topics topics, $total_replies replies)<br />
	<strong>Total Members:</strong> $total_members<br />
	<strong>Newest Member:</strong> <a href='?act=profile&amp;u=$newest_member[users_id]'>$newest_member[users_username]</a><br />
</div>";
?>