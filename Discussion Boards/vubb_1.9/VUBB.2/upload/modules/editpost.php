<?php
/*
Copyright 2005 VUBB
*/
// permissions
$permissions = mysql_fetch_array(mysql_query("SELECT `cpost`,`cview` FROM `permissions` WHERE `group` = '" . $stat['group'] . "' AND `forum` = '" . $_GET['f'] . "'"));

if ($permissions['cpost'] == '0' || $permissions['cview'] == '0')
{
message($lang['title']['no_post'],$lang['text']['no_post']);
}


else if (!isset($_GET['action']))
{
// Get the forum and category name from the url
$forum_name = mysql_fetch_array(mysql_query("SELECT `name`,`category` FROM `forums` WHERE `id` = '" . $_GET['f'] . "'")) or die(mysql_error());
$category_name = mysql_fetch_array(mysql_query("SELECT `name` FROM `forums` WHERE `id` = '" . $forum_name['category'] . "'"));
display_clickable_smilies_bbcode();

// if is topic
if ($_GET['ist'] == '1')
{
$edit_body = mysql_fetch_array(mysql_query("SELECT `body` FROM `forum_topic_text` WHERE `topic_id` = '" . $_GET['t'] . "'"));
$edit_perm = mysql_fetch_array(mysql_query("SELECT * FROM `forum_topics` WHERE `id` = '" . $_GET['t'] . "'"));
}

// if is reply
else if ($_GET['ist'] == '0')
{
$edit_body = mysql_fetch_array(mysql_query("SELECT `body` FROM `forum_reply_text` WHERE `reply_id` = '" . $_GET['reply'] . "'"));
$edit_perm = mysql_fetch_array(mysql_query("SELECT * FROM `forum_replies` WHERE `id` = '" . $_GET['reply'] . "'"));
}

echo $category_name['starter'];

if ($edit_perm['starter'] == $stat['user'] || $stat['group'] == '4' || $stat['group'] == '3'){
// make post readable for editing
$edit_body['body'] = edit_post_parser($edit_body['body']);
echo eval(get_template('editpost'));
}else{
$edit_body['body'] = "You don't have permission to edit this post!";
message('No Permissions',$edit_body['body']);
}

}

// Edit Post
else if ($_GET['action'] == 'edit')
{

// if is topic
if ($_GET['ist'] == '1')
{
$edit_body = mysql_fetch_array(mysql_query("SELECT `body` FROM `forum_topic_text` WHERE `topic_id` = '" . $_GET['t'] . "'"));
$edit_perm = mysql_fetch_array(mysql_query("SELECT * FROM `forum_topics` WHERE `id` = '" . $_GET['t'] . "'"));
}

// if is reply
else if ($_GET['ist'] == '0')
{
$edit_body = mysql_fetch_array(mysql_query("SELECT `body` FROM `forum_reply_text` WHERE `reply_id` = '" . $_GET['reply'] . "'"));
$edit_perm = mysql_fetch_array(mysql_query("SELECT * FROM `forum_replies` WHERE `id` = '" . $_GET['reply'] . "'"));
}

if ($edit_perm['starter'] == $stat['user'] || $stat['group'] == '4' || $stat['group'] == '3'){

$locker_exists = mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_topics` WHERE `id` = '" . $_GET['t'] . "' AND `starter`='".$stat['user']."'"));

if ($locker_exists['locked'] == '1')
{
message($lang['title']['topic_locked'],$lang['text']['locked']);
}

else if (!isset($_GET['t']))
{
message($lang['title']['no_topic_id'],$lang['title']['no_topic_id']);
}

else if (empty($locker_exists))
{
message($lang['title']['no_topic_id'],$lang['title']['no_topic_id']);
}

else if (!isset($_POST['body1']))
{
message($lang['title']['no_body'],$lang['text']['no_body']);
}

else
{
if ($_GET['ist'] == '1')
{
// post parsing
main_post_parser();

// Insert the reply body
// Addslashes done in body1 file
mysql_query("UPDATE `forum_topic_text` set `body` = '" . $_POST['body1'] . "' WHERE `topic_id` = '" . $_GET['t'] . "'") or die(mysql_error());

message($lang['title']['post_editted'],$lang['text']['post_editted']);
}

else if ($_GET['ist'] == '0')
{
// post parsing
main_post_parser();

// Insert the reply body
// Addslashes done in body1 file
mysql_query("UPDATE `forum_reply_text` set `body` = '" . $_POST['body1'] . "' WHERE `reply_id` = '" . $_GET['reply'] . "'") or die(mysql_error());

message($lang['title']['post_editted'],$lang['text']['post_editted']);
}
}
}else{
$edit_body['body'] = "You don't have permission to edit this post!";
message('No Permissions',$edit_body['body']);
}
}
?>