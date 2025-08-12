<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

if ($LoggedInCanChangeLock != 1)
{
	$errormsg = 'Illegal attempt to change the Locked status of an article';
	DisplayError($errormsg, 0);
	exit;
}

$GetId = isset($_GET['id']) ? $_GET['id'] : '';
$ReturnText = ' Click <A href="' . $AdminScript . '?action=NewsList">here</A> to return to the news items';

// Locked code goes here
$result = mysql_query("SELECT Locked FROM news_posts WHERE ID = $GetId");
$newsrow = mysql_fetch_array($result);
$IsLocked = $newsrow['Locked'];

// Toggle the status
$IsLocked = ($IsLocked == 1 ? 0 : 1);

// Get the news headline
$Headline = GetHeadline($GetId);

// Update...
mysql_query("UPDATE news_posts SET Locked = $IsLocked WHERE ID = $GetId");

// Send notification, if required
if ($EmailAddressNotifyLockedChanged != "")
	SendNewsNotificationEmail($EmailAddressNotifyLockedChanged, $GetId, ($IsLocked == 1 ? 'locked' : 'unlocked'));

// Write audit, if required
if ($EnableAudit == 1)
	WriteAuditEvent(AUDIT_TYPE_LOCKED, 'X', $GetId, "News article has been " . ($IsLocked == 1 ? 'locked' : 'unlocked') . ': ' . $Headline);

$successmsg = 'This news article has been ' . ($IsLocked == 1 ? 'locked' : 'unlocked') . '. ' . $ReturnText;
$_SESSION['Info'] = 'Selected News Article has been made ' . ($IsLocked == 1 ? 'locked' : 'unlocked') . '. ';
header('location:' . $AdminScript . '?action=NewsList');
exit;
?>