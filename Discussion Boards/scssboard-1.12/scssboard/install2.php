<?php

include("system/config.inc.php");

$step = $_GET[step];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="styles/grey.css">
<title>sCssBoard installer</title>
</head>
<body>

<?php
function SQLErrHandler($errNumber, $errDescript, $errFile, $errLine) {
	echo "The installer was unable to connect to the database. You may have entered something incorrectly on Step 1 of the installer. Please delete <em>/system/config.inc.php</em> and run install.php again. If you receive this error again, copy the information below and post it on the <a href='http://scssboard.if-hosting.com/forums/'>sCssBoard Support Forums</a>. <br><br>";
	echo "Number: [$errNumber]<br /> Error: [$errDescript]<br /> Line: [$errLine]<br /><br />";
	die();
}

if(!$step) {

if(!$_POST[i1complete]) {
	die("Access Denied");
}

set_error_handler("SQLErrHandler");
mysql_connect("$_CON[host]","$_CON[user]","$_CON[pass]");
mysql_select_db("$_CON[name]");

?><div class="catheader" style='width:520px; float:left;'>sCssBoard Installation - Step 1c</div>
<div class="msg_content" style="width:500px; float:left;">A connection to the MySQL database was successfully established.
<p align="center"><span class="main_button"><a href="?step=2">Continue >></a></span></p></div>


<?php } elseif($step == 2) { ?>

<div class="catheader" style='width:520px; float:left;'>sCssBoard Installation - Step 2</div>
<div class="msg_content" style='width:500px; float:left;'>Now, let's set up some basic settings and the primary admin account.<br /><br />
<form action="install2.php?step=3" method="post" name="settingsForm">
<table width='400' border='0' cellpadding='2' cellspacing='2' align='center'>
<tr>
	<td colspan='2' class='catheader' align='center' style='font-size:14px;'>
		<strong>Primary Settings</strong>
	</td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>
		Board Name: 
	</td>
	<td class='forum_name' width='200'>
		<input type="text" name="board_name" class="input" size="25">
	</td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>
		Admin Username: 
	</td>
	<td class='forum_name' width='200'>
		<input type="text" name="admin_name" class="input" size="25">
	</td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>
		Admin Password: 
	</td>
	<td class='forum_name' width='200'>
		<input type="password" name="admin_pass" class="input" size="25">
	</td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>
		Admin Password (retype): 
	</td>
	<td class='forum_name' width='200'>
		<input type="password" name="admin_pass2" class="input" size="25">
	</td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>
		Admin E-Mail: 
	</td>
	<td class='forum_name' width='200'>
		<input type="text" name="admin_email" class="input" size="25">
	</td>
</tr>
</table><br /><p align='center'>
Once you click Continue, the database will be populated.<br /><br />
<span class='main_button'><a href='javascript:document.settingsForm.submit();'>Continue >></a></span></p>
</form></div>


<?php } elseif($step == 3) { 

$admin_pass = $_POST[admin_pass];

if ($admin_pass !== $_POST[admin_pass2]) { die("You made a mistake typing your administrator password. Hit Back and retype it, please."); }

set_error_handler("SQLErrHandler");

mysql_connect("$_CON[host]","$_CON[user]","$_CON[pass]");
mysql_select_db("$_CON[name]");

mysql_query("CREATE TABLE `$_CON[prefix]categories` (
  `category_id` int(11) NOT NULL auto_increment,
  `category_order` int(11) NOT NULL default '0',
  `category_name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`category_id`)
	)");
mysql_query("INSERT INTO `$_CON[prefix]categories` VALUES (1, 0, 'Sample Category')");

mysql_query("CREATE TABLE `$_CON[prefix]forums` (
  `forums_id` int(11) NOT NULL auto_increment,
  `forums_order` int(11) NOT NULL default '0',
  `forums_category` int(11) NOT NULL default '0',
  `forums_name` varchar(100) NOT NULL default '',
  `forums_description` text NOT NULL,
  `forums_p_read` int(1) NOT NULL default '0',
  `forums_p_topic` int(1) NOT NULL default '1',
  `forums_p_reply` int(1) NOT NULL default '1',
  PRIMARY KEY  (`forums_id`)
)");

mysql_query("INSERT INTO `$_CON[prefix]forums` VALUES (1, 0, 1, 'Sample Forum', 'This is a sample forum.', 0, 1, 1)");

mysql_query("CREATE TABLE `$_CON[prefix]posts` (
  `posts_id` int(10) NOT NULL auto_increment,
  `posts_forum` int(11) NOT NULL default '0',
  `posts_main` char(3) NOT NULL default '',
  `posts_posted` int(10) NOT NULL default '0',
  `posts_topic` int(11) NOT NULL default '0',
  `posts_topic_lastpost` int(10) default NULL,
  `posts_topic_locked` int(1) NOT NULL default '0',
  `posts_views` int(11) NOT NULL default '0',
  `posts_starter` varchar(11) NOT NULL default '1',
  `posts_body` longtext NOT NULL,
  `posts_name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`posts_id`),
  FULLTEXT KEY `posts_body` (`posts_body`)
) ENGINE=MyISAM");

$time = time();

mysql_query("INSERT INTO `$_CON[prefix]posts` VALUES (0, 1, 'yes', $time, 1, 1, 0, 0, 1, 'Welcome to sCssBoard Beta! To get started with customizing your message board, click the [ Admin CP ] link on the top menu bar.', 'Welcome')");

mysql_query("CREATE TABLE `$_CON[prefix]settings` (
  `setting_name` text NOT NULL,
  `setting_value` text NOT NULL
)");

mysql_query("INSERT INTO `$_CON[prefix]settings` VALUES ('admin_notes', 'You can use this space to save notes for yourself and other administrators.');");
mysql_query("INSERT INTO `$_CON[prefix]settings` VALUES ('board_name', '$_POST[board_name]');");
mysql_query("INSERT INTO `$_CON[prefix]settings` VALUES ('default_style', 'grey.css');");
mysql_query("INSERT INTO `$_CON[prefix]settings` VALUES ('cookie_path', '/');");
mysql_query("INSERT INTO `$_CON[prefix]settings` VALUES ('cookie_url', '');");
mysql_query("INSERT INTO `$_CON[prefix]settings` VALUES ('redir_method', 'meta');");
mysql_query("INSERT INTO `$_CON[prefix]settings` VALUES ('sig_bbcode', 'yes');");
mysql_query("INSERT INTO `$_CON[prefix]settings` VALUES ('debug_level', '1');");
mysql_query("INSERT INTO `$_CON[prefix]settings` VALUES ('date_format', 'F j, Y, g:i a');");
mysql_query("INSERT INTO `$_CON[prefix]settings` VALUES ('use_relative_dates', 'yes');");
mysql_query("INSERT INTO `$_CON[prefix]settings` VALUES ('allow_signups', 'yes');");

mysql_query("CREATE TABLE `$_CON[prefix]users` (
  `users_id` int(11) NOT NULL auto_increment,
  `users_username` varchar(16) NOT NULL default '',
  `users_password` varchar(32) NOT NULL default '',
  `users_email` varchar(50) NOT NULL default '',
  `users_private_email` int(1) NOT NULL default '1',
  `users_realname` varchar(30) NOT NULL default '',
  `users_location` varchar(50) NOT NULL default '',
  `users_signature` mediumtext NOT NULL,
  `users_level` tinyint(1) NOT NULL default '1',
  `users_style` varchar(15) NOT NULL default 'grey.css',
  `users_tpp` int(3) NOT NULL default '15',
  `users_rpp` int(3) NOT NULL default '15',
  PRIMARY KEY  (`users_id`)
)");

$admin_pw = md5($admin_pass);

mysql_query("INSERT INTO `$_CON[prefix]users` VALUES (1, '$_POST[admin_name]', '$admin_pw', '$_POST[admin_email]', '1', '', '', '', 4, 'grey.css', 15, 15)");
?>

<div class="catheader" style='width:520px; float:left;'>sCssBoard Installation - All Done!</div>
<div class="msg_content" style='width:500px; float:left;'>The database has been populated. And you're all done! <br /> <br />Please delete <em>install.php</em> and <em>install2.php</em> from your server.<br /><br /><p align="center"><span class="main_button"><a href="index.php">Continue >></a></span></p></div>


<?php } ?>

</body>
</html>