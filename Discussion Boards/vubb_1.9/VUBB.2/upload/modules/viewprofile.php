<?php
/*
Copyright 2005 VUBB
*/
// Get info for member info display
$member_info = mysql_fetch_array(mysql_query("SELECT * FROM `members` WHERE `id` = '" . $_GET['m'] . "'"));

// Get rid of slashes from database for output
$member_info['user'] = stripslashes($member_info['user']);
$member_info['sig'] = stripslashes($member_info['sig']);
$member_info['avatar_link'] = stripslashes($member_info['avatar_link']);
$member_info['location'] = stripslashes($member_info['location']);
$member_info['website'] = stripslashes($member_info['website']);
$member_info['aim'] = stripslashes($member_info['aim']);
$member_info['msn'] = stripslashes($member_info['msn']);
$member_info['yahoo'] = stripslashes($member_info['yahoo']);
$member_info['icq'] = stripslashes($member_info['icq']);

// Correct for viewing
$member_info['sig'] = view_post($member_info['sig']);

get_template('viewprofile');
?>