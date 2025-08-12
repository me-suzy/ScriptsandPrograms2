<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

require('functions.inc.php');
session_start();
unp_indexInstallCheck(); // Redirect to install.php if UNP is not installed
$USER = unp_getUser(0);
unp_getsettings();

// +------------------------------------------------------------------+
// | Process Main Page                                                |
// +------------------------------------------------------------------+
if ($USER['groupid'] != 0)
{
	$user = $USER['username'];

	$totalnews = $DB->query("SELECT COUNT(*) AS NumNews FROM `unp_news`");
	$totalnews2 = $DB->fetch_array($totalnews);
	$totalnewsnum = $totalnews2['NumNews'];

	$totalusers = $DB->query("SELECT COUNT(*) AS NumUsers FROM `unp_user`");
	$totalusers2 = $DB->fetch_array($totalusers);
	$totalusersnum = $totalusers2['NumUsers'];

	$totaladmins = $DB->query("SELECT COUNT(*) AS NumAdmins FROM `unp_user` WHERE groupid='1'");
	$totaladmins2 = $DB->fetch_array($totaladmins);
	$totaladminsnum = $totaladmins2['NumAdmins'];

	$totalcomments = $DB->query("SELECT COUNT(*) AS NumComm FROM `unp_comments`");
	$totalcomments2 = $DB->fetch_array($totalcomments);
	$totalcommentsnum = $totalcomments2['NumComm'];

	$yournews = $DB->query("SELECT COUNT(*) AS NumYourNews FROM `unp_news` WHERE poster='$user'");
	$yournews2 = $DB->fetch_array($yournews);
	$yournewstotal = $yournews2['NumYourNews'];

	$getdbsize = $DB->query("SHOW TABLE STATUS");
	$dbsize = 0;
	while ($dbdata = $DB->fetch_array($getdbsize))
	{
		$dbsize = $dbsize + $dbdata['Data_length'] + $dbdata['Index_length'];
	}	
	$dbsize = $dbsize / 1024; // convert database size to KB from bytes
	$dbsize = round($dbsize, 2);
	
	$mysqlversion = $DB->query("SELECT VERSION() AS version");
	$mysqlversion2 = $DB->fetch_array($mysqlversion);
	$mysqlVersion = $mysqlversion2['version'];

	if (file_exists('install.php'))
	{
		$installer_warning = '<br /><br /><strong><span class="highlight">WARNING:</span></strong> Install.php still exists on the server. Delete it immediately.';
	}
	else
	{
		$installer_warning = '';
	}
	// ** DO OUTPUT ** //
	include('header.php');
	unp_openbox();
	echo '
	<strong>Welcome To The Utopia News Pro Administrator\'s Control Panel</strong><br />
	<span class="smallfont">Developed by <a href="http://www.utopiasoftware.net/" target="_blank">UtopiaSoftware</a></span><br /><br />
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
		<td>
	<strong>News</strong><br />
	<a href="postnews.php?action=post">Post News</a><br />
	<a href="editnews.php?action=edit">Edit Previous News</a><br />
	<a href="news.php">View Current News</a><br />
	<a href="newscache.php">News Cache</a><br />

	<strong>Users</strong><br />
	<a href="profile.php?action=edit">Edit Profile</a><br />
	<a href="users.php?action=main">Manage Users</a><br />

	<strong>Settings</strong><br />
	<a href="settings.php?action=edit">Edit Settings</a><br />
	<a href="styles.php?action=edit">Edit Style</a><br />
	<a href="templates.php?action=list">Edit Templates</a><br />
	
	<strong>Help &amp; Support</strong><br />
	<a href="faq.php" style="cursor: help">Internal FAQ</a><br />
	<a href="http://www.utopiasoftware.net" target="_blank">UtopiaSoft Website</a><br />
	<a href="http://www.utopiasoftware.net/forum" target="_blank">UtopiaSoft Forums</a><br />

	<strong>Logout</strong><br />
	<a href="login.php?action=logout">Logout</a><br /><br />
		</td>
		<td valign="top">';
	echo '
		<strong>Utopia News Pro Statistics</strong><br />
		Total News Items: '.$totalnewsnum.'<br />
		Total News By You: '.$yournewstotal.'<br />
		Total Comments: '.$totalcommentsnum.'<br />
		Total Users: '.$totalusersnum.'<br />
		Total Administrative Users: '.$totaladminsnum.'<br />
		<strong>Server Stats</strong><br />
		PHP Version: '.phpversion().'<br />
		MySQL Version: '.$mysqlVersion.'<br />
		Database Usage: '.$dbsize.' KB'.$installer_warning.'</td>
		</tr>
	</table>';

	echo '
	<strong>Current User:</strong> '.$user.'<br />
			<strong>Current Time:</strong> '.unp_date($dateformat).' at '.unp_date($timeformat);
	unp_closebox();
	include('footer.php');
}
// +------------------------------------------------------------------+
// | Process Log In Page Content                                      |
// +------------------------------------------------------------------+
else
{
	isset($_COOKIE['unp_user']) ? $user = $_COOKIE['unp_user'] : $user = '';
	include('header.php');
	unp_openbox();
	echo '
	<strong>Welcome To The Utopia News Pro Administrator\'s Control Panel</strong><br /></span>
	<span class="smallfont">Developed By <a href="http://www.utopiasoftware.net/" target="_blank">UtopiaSoftware</a></span><br /><br />
	You are not logged in. Please log in now.
	<form action="login.php" method="post">
	<table width="100%" border="0" cellpadding="1" cellspacing="0" valign="top">
	<tr>
		<td align="left" width="25%"><strong><u>U</u>ser name:</strong></td>
		<td align="left" width="75%"><strong><u>P</u>assword:</strong></td>
	</tr>
	<tr>
		<td align="left" width="25%"><input type="text" value="'.$user.'" name="username" size="25" accesskey="u" /></td>
		<td align="left" width="75%"><input type="password" name="password" size="25" accesskey="p" /></td>
	</tr>
	<tr>
		<td align="left" width="100%" colspan="2"><input type="submit" name="login" value="    Log In    " accesskey="s" /></td>
	</tr>
	</table>
	</form>';
	unp_closebox();
	include('footer.php');
}
?>