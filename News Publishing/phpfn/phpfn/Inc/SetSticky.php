<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

$GetId = isset($_GET['id']) ? $_GET['id'] : '';
$ReturnText = ' Click <A href="' . $AdminScript . '?action=NewsList">here</A> to return to the news items';

// Sticky code goes here
$result = mysql_query("SELECT Sticky, Locked FROM news_posts WHERE ID = $GetId");
$newsrow = mysql_fetch_array($result);
$IsSticky = $newsrow['Sticky'];
$Locked = $newsrow['Locked'];

// Illegal attempt to edit a locked post?
if ($Locked == '1')
{
	$errormsg = "Illegal attempt to alter a locked post!";
	DisplayError($errormsg, 0);
	exit;
}

// Toggle the status
$IsSticky = ($IsSticky == 1 ? 0 : 1);

// Get the news headline
$Headline = GetHeadline($GetId);

// Update...
mysql_query("UPDATE news_posts SET Sticky = $IsSticky WHERE ID = $GetId");

// Send notification, if required
if ($EmailAddressNotifyStickyChanged != "")
	SendNewsNotificationEmail($EmailAddressNotifyStickyChanged, $GetId, 'made ' . ($IsSticky == 1 ? 'sticky' : 'un-sticky'));

// Write audit, if required
if ($EnableAudit == 1)
	WriteAuditEvent(AUDIT_TYPE_STICKY, 'C', $GetId, "News article has been made " . ($IsSticky == 1 ? 'sticky' : 'un-sticky') . ': ' . $Headline);

$_SESSION['Info'] = 'Selected News Article has been made ' . ($IsSticky == 1 ? 'sticky' : 'un-sticky') . '. ';
header('location:' . $AdminScript . '?action=NewsList');
exit;
?>