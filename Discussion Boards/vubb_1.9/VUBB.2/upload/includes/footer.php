<?php 
/*
Copyright 2005 VUBB
*/
// Run the users update function
update_users_online();

// Run the guest update function
update_guests_online();

// Run the counting for stats
$last_member = mysql_fetch_array(mysql_query("SELECT `id`,`user` FROM `members` ORDER BY `id` DESC LIMIT 1"));
$t_count = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS `count` FROM `forum_topics`"));
$r_count = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS `count` FROM `forum_replies`"));
$user_count = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS `count` FROM `members` WHERE `id` != '-1'"));
$guests_online = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS `count` FROM `guests_online`"));

$fetch_users = mysql_query("SELECT `user`, `id` FROM `members` WHERE `online` = '1' AND `id` != '-1'");
while ($echo_users = mysql_fetch_array($fetch_users))
{
	$users_online .= " <a href='index.php?act=viewprofile&m=" . $echo_users['id'] . "'>" . $echo_users['user'] . "</a> ";
}

get_template('footer');
?>