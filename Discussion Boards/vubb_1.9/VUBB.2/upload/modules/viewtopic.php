<?php
/*
Copyright 2005 VUBB
*/
// make sure topic and forumroot is numeric
if (!ctype_digit($_GET['t']))
{
error($lang['title']['no_id'], $lang['text']['no_id']);
}

if (!ctype_digit($_GET['f']))
{
error($lang['title']['no_forum_id'], $lang['text']['no_forum_id']);
}

// permissions
$permissions = mysql_fetch_array(mysql_query("SELECT * FROM `permissions` WHERE `group` = '" . $stat['group'] . "' AND `forum` = '" . $_GET['f'] . "'")); 

// can this users group view the forum?
if ($permissions['cview'] == '0' || $permissions['cview'] == null)
{
message($lang['title']['no_view'],$lang['text']['no_view']);
}

// View Topic
if (isset($_GET['t']) && !isset($_GET['action']))
{
$topic_info = mysql_fetch_array(mysql_query("SELECT * FROM `forum_topics` WHERE `id` = '" . $_GET['t'] . "'"));
$post_body = mysql_fetch_array(mysql_query("SELECT * FROM `forum_topic_text` WHERE `topic_id` = '" . $_GET['t'] . "'"));
$forum_info = mysql_fetch_array(mysql_query("SELECT `name`,`id` FROM `forums` WHERE `id` = '" . $_GET['f'] . "'"));

if (empty ($topic_info['id']))
{
message($lang['title']['no_id'], $lang['text']['no_id']);
}

else
{
$info = mysql_fetch_assoc(mysql_query("SELECT * FROM `members` WHERE `id` = '" . $topic_info['starter_id'] . "'"));
$group_info = mysql_fetch_array(mysql_query("SELECT * FROM `groups` WHERE `id` = '" . $info['group'] . "'"));

// Set guest info
if (empty($info['id']))
{
$info['id'] = "0";
$info['avatar_link'] = $site_config['site_url'] . "images/noav.jpg";
$info['datereg'] = $lang['text']['unregistered'];
}

$topic_info['topic'] = stripslashes($topic_info['topic']);
$topic_info['starter'] = stripslashes($topic_info['starter']);
$post_body['body'] = stripslashes($post_body['body']);

// If admin or mod show topic tools
if (($stat['group'] == '4') || ($stat['group'] == '3') || ($topic_info['starter'] == $stat['user']))
{
if ($topic_info['locked'] == '1')
{
$locker_text = $lang['text']['unlock'];
$locker_link = "index.php?act=viewtopic&t=" . $_GET['t'] . "&f=" . $_GET['f'] . "&action=unlock";
}

else
{
$locker_text = $lang['text']['lock'];
$locker_link = "index.php?act=viewtopic&t=" . $_GET['t'] . "&f=" . $_GET['f'] . "&action=lock";
}

if ($topic_info['sticky'] == '1')
{
$sticky_text = $lang['text']['unstick'];
$sticky_link = "index.php?act=viewtopic&t=" . $_GET['t'] . "&f=" . $_GET['f'] . "&action=unstick";
}

else
{
$sticky_text = $lang['text']['stick'];
$sticky_link = "index.php?act=viewtopic&t=" . $_GET['t'] . "&f=" . $_GET['f'] . "&action=stick";
}

if ($topic_info['poll'] == '1')
{
$poll_id = mysql_fetch_array(mysql_query("SELECT `id` FROM `polls` WHERE `topic_id` = '" . $_GET['t'] . "'"));
$delete_link = "index.php?act=viewtopic&t=" . $_GET['t'] . "&f=" . $_GET['f'] . "&action=delete&ist&p=" . $poll_id['id'] . "";
}

else
{
$delete_link = "index.php?act=viewtopic&t=" . $_GET['t'] . "&f=" . $_GET['f'] . "&action=delete&ist";
}

$moderator_tools = "<a href='" . $locker_link . "' class='tlinks'>" . $locker_text . "</a> - <a href='" . $sticky_link . "' class='tlinks'>" . $sticky_text . "</a> - <a href='" . $delete_link . "' class='tlinks'>" . $lang['mix']['delete'] . "</a> - <a href='index.php?act=viewtopic&t=" . $_GET['t'] . "&f=" . $_GET['f'] . "&action=move' class='tlinks'>" . $lang['text']['move'] . "</a> -";

}

else
{
$moderator_tools = "&nbsp;";
}

if ($topic_info['starter_id'] == $stat['id'] || $stat['group'] == '4' || $stat['group'] == '3')
{
$topic_edit_link = "<a href='index.php?act=editpost&t=" . $_GET['t'] . "&f=" . $_GET['f'] . "&ist=1' class='tlinks'>" . $lang['text']['edit'] . "</a>";
}

else
{
$topic_edit_link = "&nbsp;";
}

if ($topic_info['poll'] == '0')
{
echo eval(get_template('viewtopic_topic_table'));
}

else
{
$poll_info = mysql_fetch_array(mysql_query("SELECT * FROM `polls` WHERE `topic_id` = '" . $_GET['t'] . "'"));
$poll_voted = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS `count` FROM `poll_voters` WHERE `poll_id` = '" . $poll_info['id'] . "' AND `user_id` = '" . $stat['id'] . "'"));

// if person viewing hasn't voted then show the viewtopic_poll template
if ($poll_voted['count'] == '0')
{
$select_choices = mysql_query("SELECT * FROM `poll_choices` WHERE `poll_id` = '" . $poll_info['id'] . "'");
while ($get_choices = mysql_fetch_array($select_choices))
{
$poll .= "<input type="radio" name="choice" value="" . $get_choices['id'] . "">" . $get_choices['choice'] . "<br />";
}

$poll .= "<br />" . "<input type="submit" name="Submit" value="" . $lang['submit']['vote'] . "">";
}

// if person viewing has voted then show the viewtopic_poll_results template
else
{
$select_choices = mysql_query("SELECT * FROM `poll_choices` WHERE `poll_id` = '" . $poll_info['id'] . "'");
while ($get_choices = mysql_fetch_array($select_choices))
{
$poll .= $get_choices['choice'] . ": " . $get_choices['votes'] . "<br />";
}

$poll .= "<br /><strong>" . $lang['text']['total_votes'] . ": " . $poll_info['totalvotes'] . "</strong>";
}

echo eval(get_template('viewtopic_poll_table'));
}

// Select the forum reply info
$rsel = mysql_query("SELECT * FROM `forum_replies` WHERE `topic_id` = '".$topic_info['id']."' ORDER BY `id` ASC");

// Get the forum reply info
while ($reply = mysql_fetch_array($rsel))
{
// Get the reply post body
$reply_body = mysql_fetch_array(mysql_query("SELECT * FROM `forum_reply_text` WHERE `reply_id` = '".$reply['id']."'"));

$reply_body['body'] = stripslashes($reply_body['body']);

// Select replying users info
$info2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `members` WHERE `id` = '".$reply['starter_id']."'"));
$group_info2 = mysql_fetch_array(mysql_query("SELECT * FROM `groups` WHERE `id` = '" . $info2['group'] . "'"));

if (($stat['group'] == '4') || ($stat['group'] == '3') || ($reply['starter'] == $stat['user']))
{
$delete_edit = "<a href='index.php?act=viewtopic&t=" . $_GET['t'] . "&f=" . $_GET['f'] . "&action=delete&isr&r=" . $reply['id'] . "' class='tlinks'>" . $lang['mix']['delete'] . "</a> - <a href='index.php?act=editpost&t=" . $_GET['t'] . "&f=" . $_GET['f'] . "&reply=" . $reply['id'] . "&ist=0' class='tlinks'>" . $lang['text']['edit'] . "</a>";
}

else
{
$delete_edit = "&nbsp;";
}

echo eval(get_template('viewtopic_reply_table'));
}
}

echo eval(get_template('viewtopic_end_table'));
}

else if (isset($_GET['action']) && $_GET['action'] == 'delete')
{

if ($stat['group'] == '4' || $stat['group'] == '3' || $delete_perm['starter'] == $stat['user'])
{
// topic deletion
if (isset($_GET['ist']))
{
$check_exist = mysql_fetch_array(mysql_query("SELECT COUNT(`id`) AS `count` FROM `forum_topics` WHERE `id` = '" . $_GET['t'] . "'"));
if ($check_exist['count'] >= '1')
{
// get the topic info
$topic_info = mysql_fetch_array(mysql_query("SELECT * FROM `forum_topics` WHERE `id` = '" . $_GET['t'] . "'"));

// if topic has a poll
if ($topic_info['poll'] == '1')
{
// delete all things relating to this topcis poll
mysql_query("DELETE FROM `poll_voters` WHERE `poll_id` = '" . $_GET['p'] . "'");
mysql_query("DELETE FROM `poll_choices` WHERE `poll_id` = '" . $_GET['p'] ."'");
mysql_query("DELETE FROM `polls` WHERE `topic_id` = '" . $_GET['t'] . "'");
}

mysql_query("UPDATE `forums` SET `topics` = (topics - 1) WHERE `id` = '" . $_GET['f'] . "'");

$count_replies = mysql_fetch_array(mysql_query("SELECT COUNT(`id`) AS `count` FROM `forum_replies` WHERE `topic_id` = '" . $_GET['t'] . "'"));
mysql_query("UPDATE `forums` SET `replies` = (replies - " . $count_replies['count'] . ") WHERE `id` = '" . $_GET['f'] . "'");

mysql_query("DELETE FROM `forum_topics` WHERE `id` = '" . $_GET['t'] . "'");
mysql_query("DELETE FROM `forum_topic_text` WHERE `topic_id` = '" . $_GET['t'] . "'");
mysql_query("DELETE FROM `forum_replies` WHERE `topic_id` = '" . $_GET['t'] . "'");
mysql_query("DELETE FROM `forum_reply_text` WHERE `topic_id` = '" . $_GET['t'] . "'");

message($lang['title']['deleted'], $lang['text']['topic_deleted']);
}

else
{
message($lang['title']['no_topic_id'], $lang['title']['no_topic_id']);
}
}
}
$delete_perm = mysql_fetch_array(mysql_query("SELECT * FROM `forum_replies` WHERE `id` = '" . $_GET['r'] . "'"));
if ($stat['group'] == '4' || $stat['group'] == '3' || $delete_perm['starter'] == $stat['user']){
if (isset($_GET['isr']))
{
mysql_query("UPDATE `forums` SET `replies` = (replies - 1) WHERE `id` = '" . $_GET['f'] . "'");
mysql_query("UPDATE `forum_topics` SET `replies` = (replies - 1) WHERE `id` = '" . $_GET['t'] . "'");
mysql_query("DELETE FROM `forum_replies` WHERE `id` = '" . $_GET['r'] . "'");
mysql_query("DELETE FROM `forum_reply_text` WHERE `reply_id` = '" . $_GET['r'] . "'");

message($lang['title']['deleted'], $lang['text']['reply_deleted']);
}else{
message($lang['title']['delete_no'], $lang['text']['delete_no']);
}
}

else
{
message($lang['title']['delete_no'], $lang['text']['delete_no'].'dd');
}
}

else if (isset($_GET['action']) && $_GET['action'] == 'lock')
{
if ($stat['group'] == '4' || $stat['group'] == '3')
{
mysql_query("UPDATE `forum_topics` SET `locked` = '1' WHERE `id` = '".$_GET['t']."'") or die("Could not lock!");

message($lang['title']['topic_locked'],$lang['text']['topic_locked']);
}

else
{
message($lang['title']['topic_lock_no'],$lang['text']['topic_lock_no']);
}
}

else if (isset($_GET['action']) && $_GET['action'] == 'unlock')
{
if ($stat['group'] == '4' || $stat['group'] == '3')
{
mysql_query("UPDATE `forum_topics` SET `locked` = 'N' WHERE `id` = '".$_GET['t']."'") or die("Could not unlock!");

message($lang['title']['topic_unlocked'],$lang['text']['topic_unlocked']);
}

else
{
message($lang['title']['topic_unlock_no'],$lang['text']['topic_unlock_no']);
}
}

else if (isset($_GET['action']) && $_GET['action'] == 'stick')
{
if ($stat['group'] == '4' || $stat['group'] == '3')
{
mysql_query("UPDATE `forum_topics` SET `sticky` = '1' WHERE `id` = '" . $_GET['t'] . "'") or die("Could not lock!");

message($lang['title']['stick_topic'],$lang['text']['topic_stickied']);
}

else
{
message($lang['title']['topic_stick_no'],$lang['text']['topic_stick_no']);
}
}

else if (isset($_GET['action']) && $_GET['action'] == 'unstick')
{
if ($stat['group'] == '4' || $stat['group'] == '3')
{
mysql_query("UPDATE `forum_topics` SET `sticky` = '0' WHERE `id` = '" . $_GET['t'] . "'") or die("Could not unlock!");

message($lang['title']['unstick_topic'],$lang['text']['topic_unstickied']);
}

else
{
message($lang['title']['topic_unstick_no'],$lang['text']['topic_unstick_no']);
}
}

else if (isset($_GET['action']) && $_GET['action'] == 'move')
{
if (!isset($_GET['go']))
{
if ($stat['group'] == '4' || $stat['group'] == '3')
{
echo "
<div align='center'>
<table width='90%' cellpadding='2' cellspacing='0'>
<tr>
<td width='99%' valign='top' class='head_block'>
" . $lang['title']['move_topic'] . "
</td>
</tr>
<tr>
<td width='99%' valign='top' class='contentbox1'>
<form name='form1' method='post' action='index.php?act=viewtopic&t=" . $_GET['t'] . "&f=" . $_GET['f'] . "&action=move&go'>
<select name='forum' size='1'>";

$find_forums = mysql_query("SELECT * FROM `forums` WHERE `is_cat` = '0' ORDER BY `id` ASC");
while ($get_forums = mysql_fetch_array($find_forums))
{
echo "<option value='" . $get_forums['id'] . "'>" . $get_forums['name'] . "</option>";
}

echo "
</select><br />
<input type='submit' name='Submit' value='" . $lang['submit']['move_topic'] . "'>
</form>
</td>
</tr>
</table>
</div>
";
}

else
{
message($lang['title']['topic_move_no'],$lang['text']['topic_move_no']);
}
}

if(isset($_GET['go']))
{
if ($stat['group'] == '4' || $stat['group'] == '3')
{
mysql_query("UPDATE `forum_topics` SET `forumroot` = '".$_POST['forum']."' WHERE `id` = '".$_GET['t']."'");
mysql_query("UPDATE `forum_replies` SET `forumroot` = '".$_POST['forum']."' WHERE `id` = '".$_GET['t']."'");

mysql_query("UPDATE `forums` SET `topics` = (topics - 1) WHERE `id` = '".$_GET['f']."'");
$count_replies = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS `count` FROM `forum_replies` WHERE `topic_id` = '".$_GET['t']."'"));
mysql_query("UPDATE `forums` SET `replies` = (replies - ".$count_replies['count'].") WHERE `id` = '".$_GET['f']."'");

mysql_query("UPDATE `forums` SET `topics` = (topics + 1) WHERE `id` = '".$_POST['forum']."'");
mysql_query("UPDATE `forums` SET `replies` = (replies + ".$count_replies['count'].") WHERE `id` = '".$_POST['forum']."'");

message($lang['title']['topic_moved'],$lang['text']['topic_moved']);
}

else
{
message($lang['title']['topic_move_no'],$lang['text']['topic_move_no']);
}
}
}

else if (isset($_GET['action']) && $_GET['action'] == 'vote')
{
mysql_query("INSERT INTO `poll_voters` SET `user_id` = '" . $stat['id'] . "', `poll_id` = '" . $_GET['p'] . "'");
mysql_query("UPDATE `poll_choices` SET `votes` = (votes + 1) WHERE `id` = '" . $_POST['choice'] . "'");
mysql_query("UPDATE `polls` SET `totalvotes` = (totalvotes + 1) WHERE `id` = '" . $_GET['p'] . "'");

message($lang['title']['voted'],$lang['text']['voted']);
}
?>