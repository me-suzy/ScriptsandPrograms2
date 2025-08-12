<?php
//***************************************************************************//
//                                                                           //
//  Copyright (c) 2004-2005 Jonathon J. Freeman                              //
//  All rights reserved.                                                     //
//                                                                           //
//  This program is free software. You may use, modify, and/or redistribute  //
//  it under the terms of the OvBB License Agreement v2 as published by the  //
//  OvBB Project at www.ovbb.org.                                            //
//                                                                           //
//***************************************************************************//

	// Initialize OvBB.
	require('includes/init.inc.php');

	// Is the user logged in?
	if(!$_SESSION['loggedin'])
	{
		// No, so they can't access private messages.
		Unauthorized();
	}

	// What section are they dealing with?
	switch(strtolower($_REQUEST['action']))
	{
		// View item
		case 'view':
		{
			// View what?
			switch($_REQUEST['item'])
			{
				// Folder
				case 'folder':
				{
					// Which folder?
					if($_REQUEST['id'] == 0)
					{
						// Inbox
						ViewInbox();
					}
					else if($_REQUEST['id'] == 1)
					{
						// Sent Items
						ViewSentItems();
					}
					else
					{
						// User-added folder
						ViewFolder();
					}
				}

				// Message
				case 'message':
				{
					ViewMessage();
				}
			}
		}

		// New message
		case 'newmessage':
		{
			NewMessage();
		}

		// Reply to message
		case 'reply':
		{
			NewMessage();
		}

		// Foward message
		case 'forward':
		{
			// They're sending a list; we only forward one message, not multiple ones.
			if(is_array($_REQUEST['id']))
			{
				// Only use the first one.
				$_REQUEST['id'] = $_REQUEST['id'][0];
			}

			NewMessage();
		}

		// Message tracking
		case 'track':
		{
			Tracking();
		}

		// Manage folders
		case 'editfolders':
		{
			Folders();
		}

		// Delete message(s)
		case 'delete':
		{
			Delete();
		}

		// Move message(s)
		case 'move':
		{
			Move();
		}

		// Inbox
		default:
		{
			ViewInbox();
		}
	}

// *************************************************************************** \\

function ViewInbox()
{
	global $CFG;

	// Get the thread icons installed.
	require('includes/posticons.inc.php');

	// Get all messages in the Inbox.
	$sqlResult = sqlquery("SELECT id, datetime, author, subject, icon, beenread, readtime, tracking, replied FROM pm WHERE owner={$_SESSION['userid']} AND recipient={$_SESSION['userid']} AND parent=0 ORDER BY datetime DESC");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Store the message in the master table.
		$iMessageID = $aSQLResult['id'];
		$aMaster[$iMessageID][DATETIME] = $aSQLResult['datetime'];
		$aMaster[$iMessageID][AUTHOR] = $aSQLResult['author'];
		$aMaster[$iMessageID][SUBJECT] = htmlspecialchars($aSQLResult['subject']);
		$aMaster[$iMessageID][ICON2][URL] = 'images/'.$aPostIcons[$aSQLResult['icon']]['filename'];
		$aMaster[$iMessageID][ICON2][ALT] = $aPostIcons[$aSQLResult['icon']]['title'];
		$aMaster[$iMessageID][BEENREAD] = (bool)$aSQLResult['beenread'];
		$aMaster[$iMessageID][READTIME] = $aSQLResult['readtime'];
		$aMaster[$iMessageID][TRACKING] = $aSQLResult['tracking'];
		$aMaster[$iMessageID][REPLIED] = (bool)$aSQLResult['replied'];

		// Set the icon for the message.
		if($aMaster[$iMessageID][REPLIED])
		{
			// We've replied to the message.
			$aMaster[$iMessageID][ICON1][URL] = 'images/message_replied.png';
			$aMaster[$iMessageID][ICON1][ALT] = 'Replied To Message';
		}
		else if($aMaster[$iMessageID][BEENREAD])
		{
			// We haven't replied to it, but we've read it.
			$aMaster[$iMessageID][ICON1][URL] = 'images/message_old.png';
			$aMaster[$iMessageID][ICON1][ALT] = 'Message';
		}
		else
		{
			// We haven't even read it yet.
			$aMaster[$iMessageID][ICON1][URL] = 'images/message_new.png';
			$aMaster[$iMessageID][ICON1][ALT] = 'Unread Message';
		}

		// Add the author to our list of users to get names for.
		$aUserIDs[] = $aSQLResult['author'];
	}

	if(is_array($aUserIDs))
	{
		// Remove duplicates from our user ID list.
		$aUserIDs = array_unique($aUserIDs);

		// Query the MySQL database to get the usernames.
		$sqlResult = sqlquery("SELECT id, username FROM member WHERE id IN (".implode(', ', $aUserIDs).")");
		while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
		{
			// Store the username in the usernames array.
			$aUsernames[$aSQLResult['id']] = htmlspecialchars($aSQLResult['username']);
		}
	}

	// Header.
	$strPageTitle = ' :: Private Messages :. Inbox';
	require('includes/header.inc.php');

	// The beef.
	require('includes/pm/inbox.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

// *************************************************************************** \\

function ViewSentItems()
{
	global $CFG;

	// Get the thread icons installed.
	require('includes/posticons.inc.php');

	// Get all messages in the Sent Items folder.
	$sqlResult = sqlquery("SELECT id, datetime, recipient, subject, icon FROM pm WHERE owner={$_SESSION['userid']} AND author={$_SESSION['userid']} AND parent=1 ORDER BY datetime DESC");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Store the message in the master table.
		$iMessageID = $aSQLResult['id'];
		$aMaster[$iMessageID][DATETIME] = $aSQLResult['datetime'];
		$aMaster[$iMessageID][RECIPIENT] = $aSQLResult['recipient'];
		$aMaster[$iMessageID][SUBJECT] = htmlspecialchars($aSQLResult['subject']);
		$aMaster[$iMessageID][ICON2][URL] = 'images/'.$aPostIcons[$aSQLResult['icon']]['filename'];
		$aMaster[$iMessageID][ICON2][ALT] = $aPostIcons[$aSQLResult['icon']]['title'];
		$aMaster[$iMessageID][BEENREAD] = (bool)$aSQLResult['beenread'];
		$aMaster[$iMessageID][READTIME] = $aSQLResult['readtime'];
		$aMaster[$iMessageID][REPLIED] = (bool)$aSQLResult['replied'];
		$aMaster[$iMessageID][ICON1][URL] = 'images/message_old.png';
		$aMaster[$iMessageID][ICON1][ALT] = 'Message';

		// Add the recipient to our list of users to get names for.
		$aUserIDs[] = $aSQLResult['recipient'];
	}

	if(is_array($aUserIDs))
	{
		// Remove duplicates from our user ID list.
		$aUserIDs = array_unique($aUserIDs);

		// Query the MySQL database to get the usernames.
		$sqlResult = sqlquery("SELECT id, username FROM member WHERE id IN (".implode(", ", $aUserIDs).")");
		while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
		{
			// Store the username in the usernames array.
			$aUsernames[$aSQLResult['id']] = htmlspecialchars($aSQLResult['username']);
		}
	}

	// Header.
	$strPageTitle = ' :: Private Messages :. Sent Items';
	require('includes/header.inc.php');

	// The beef.
	require('includes/pm/sentitems.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

// *************************************************************************** \\

function ViewFolder()
{
	global $CFG;

	// What folder do they want?
	$iFolderID = (int)$_REQUEST['id'];

	// Get the thread icons installed.
	require('includes/posticons.inc.php');

	// Get all messages in the folder.
	$sqlResult = sqlquery("SELECT id, datetime, author, recipient, subject, icon, beenread, readtime, tracking, replied FROM pm WHERE owner={$_SESSION['userid']} AND parent=$iFolderID ORDER BY datetime DESC");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Store the message in the master table.
		$iMessageID = $aSQLResult['id'];
		$aMaster[$iMessageID][DATETIME] = $aSQLResult['datetime'];
		$aMaster[$iMessageID][AUTHOR] = $aSQLResult['author'];
		$aMaster[$iMessageID][RECIPIENT] = $aSQLResult['recipient'];
		$aMaster[$iMessageID][SUBJECT] = htmlspecialchars($aSQLResult['subject']);
		$aMaster[$iMessageID][ICON2][URL] = 'images/'.$aPostIcons[$aSQLResult['icon']]['filename'];
		$aMaster[$iMessageID][ICON2][ALT] = $aPostIcons[$aSQLResult['icon']]['title'];
		$aMaster[$iMessageID][BEENREAD] = (bool)$aSQLResult['beenread'];
		$aMaster[$iMessageID][READTIME] = $aSQLResult['readtime'];
		$aMaster[$iMessageID][TRACKING] = $aSQLResult['tracking'];
		$aMaster[$iMessageID][REPLIED] = (bool)$aSQLResult['replied'];

		// Set the icon for the message.
		if($aMaster[$iMessageID][REPLIED])
		{
			// We've replied to the message.
			$aMaster[$iMessageID][ICON1][URL] = 'images/message_replied.png';
			$aMaster[$iMessageID][ICON1][ALT] = 'Replied To Message';
		}
		else if(($aMaster[$iMessageID][BEENREAD]) || ($aMaster[$iMessageID][AUTHOR] == $_SESSION['userid']))
		{
			// We haven't replied to it, but we've read it.
			$aMaster[$iMessageID][ICON1][URL] = 'images/message_old.png';
			$aMaster[$iMessageID][ICON1][ALT] = 'Message';
		}
		else
		{
			// We haven't even read it yet.
			$aMaster[$iMessageID][ICON1][URL] = 'images/message_new.png';
			$aMaster[$iMessageID][ICON1][ALT] = 'Unread Message';
		}

		// Add the author and recipient to our list of users to get names for.
		$aUserIDs[] = $aSQLResult['author'];
		$aUserIDs[] = $aSQLResult['recipient'];
	}

	if(is_array($aUserIDs))
	{
		// Remove duplicates from our user ID list.
		$aUserIDs = array_unique($aUserIDs);

		// Query the MySQL database to get the usernames.
		//echo("SELECT a.id, a.username, b.pmfolders FROM member AS a LEFT JOIN member AS b ON (b.id={$_SESSION['userid']} AND b.id=a.id) WHERE a.id IN (".implode(", ", $aUserIDs).")");
		$sqlResult = sqlquery("SELECT a.id, a.username, b.pmfolders FROM member AS a LEFT JOIN member AS b ON (b.id={$_SESSION['userid']} AND b.id=a.id) WHERE a.id IN (".implode(', ', $aUserIDs).")");
		while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
		{
			// Store the username in the usernames array.
			$aUsernames[$aSQLResult['id']] = htmlspecialchars($aSQLResult['username']);

			// Is it us?
			if($aSQLResult['id'] == $_SESSION['userid'])
			{
				// Yes, so grab our folder list.
				$aFolders = unserialize($aSQLResult['pmfolders']);
			}
		}
	}
	else
	{
		// Make an explicit call to get the folder list.
		$sqlResult = sqlquery("SELECT pmfolders FROM member WHERE id={$_SESSION['userid']}");
		$strFolders = mysql_fetch_row($sqlResult);
		$aFolders = unserialize($strFolders[0]);
	}

	// Set the folder name.
	$strFolder = htmlspecialchars($aFolders[$iFolderID]);

	// Header.
	$strPageTitle = " :: Private Messages :. $strFolder";
	require('includes/header.inc.php');

	// The beef.
	require('includes/pm/viewfolder.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

// *************************************************************************** \\

function ViewMessage()
{
	global $CFG, $aGroup;

	// What message do they want?
	$iMessageID = (int)$_REQUEST['id'];

	// Get the message information.
	$sqlResult = sqlquery("SELECT pm.datetime, pm.author, pm.recipient, pm.subject, pm.body, pm.parent, pm.icon, pm.dsmilies, pm.beenread, member.pmfolders FROM pm, member WHERE pm.id=$iMessageID AND pm.owner={$_SESSION['userid']} AND member.id={$_SESSION['userid']}");
	if(!(list($tDateTime, $iAuthorID, $iRecipientID, $strSubject, $strBody, $iParentID, $iIconID, $bDisableSmilies, $bRead, $strFolders) = mysql_fetch_row($sqlResult)))
	{
		Msg("Invalid message specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}

	// Get the name of the folder this message is in.
	if($iParentID == 0)
	{
		$strParent = 'Inbox';
	}
	else if($iParentID == 1)
	{
		$strParent = 'Sent Items';
	}
	else
	{
		$aFolders = unserialize($strFolders);
		$strParent = htmlspecialchars($aFolders[$iParentID]);
	}

	// Sterilize the message.
	$strSubject = htmlspecialchars($strSubject);
	$strBody = ParseMessage($strBody, $bDisableSmilies);

	// Get the author information.
	$sqlResult = sqlquery("SELECT * FROM member WHERE id=$iAuthorID");
	$aAuthorInfo = mysql_fetch_array($sqlResult, MYSQL_ASSOC);

	// Sterilize the author information.
	$strAuthor = htmlspecialchars($aAuthorInfo['username']);
	if($aAuthorInfo['title'])
	{
		$strAuthorTitle = htmlspecialchars($aAuthorInfo['title']);
	}
	else
	{
		$strAuthorTitle = htmlspecialchars($aGroup[$aAuthorInfo['usergroup']]['usertitle']);
	}
	$tAuthorJoined = strtotime($aAuthorInfo['datejoined']);
	$strAuthorLocation = htmlspecialchars($aAuthorInfo['location']);
	$iAuthorPostCount = $aAuthorInfo['postcount'];
	$tLastActive = $aAuthorInfo['lastactive'];
	if($aUsers[$iPostAuthor][AVATAR] == NULL)
	{
		$strAuthorAvatar = 'blank.png';
	}
	else
	{
		$strAuthorAvatar = $aUsers[$iPostAuthor][AVATAR];
	}
	$strAuthorSignature = htmlspecialchars($aUsers[$iPostAuthor][SIGNATURE]);
	$strAuthorWebsite = htmlspecialchars($aUsers[$iPostAuthor][WWW]); // FIXME!!!
	$dateAuthorLastActive = $aUsers[$iPostAuthor][LASTACTIVE];

	// Deflower the message if its virgin and we're the recipient.
	if((!$bRead) && ($iRecipientID == $_SESSION['userid']))
	{
		sqlquery("UPDATE pm SET beenread=1, readtime=${CFG['globaltime']} WHERE id=$iMessageID");
	}

	if($_REQUEST['noreceipt'])
	{
		sqlquery("UPDATE pm SET tracking=0 WHERE id=$iMessageID");
	}

	// Get the thread icons installed.
	require('includes/posticons.inc.php');

	// Header.
	$strPageTitle = " :: Private Messages :. $strSubject";
	require('includes/header.inc.php');

	// The beef.
	require('includes/pm/viewmessage.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

// *************************************************************************** \\

function NewMessage()
{
	global $CFG;

	// Default values.
	$bParseURLs = FALSE;
	$bParseEMails = TRUE;
	$bDisableSmilies = FALSE;
	$bSaveCopy = TRUE;
	$bTrack = TRUE;

	// Are they submitting? If so, take care of that now so we don't
	// accumulate a superfluous amount of queries.
	if($_REQUEST['submit'] == 'Send Message')
	{
		// Sending message.
		$aError = SendMessage();
	}

	// Are they forwarding?
	if(strtolower($_REQUEST['action']) == 'forward')
	{
		// Yes, get the message they want to forward.
		$iMessageID = mysql_real_escape_string((int)$_REQUEST['id']);
		$sqlResult = sqlquery("SELECT pm.datetime, pm.author, pm.subject, pm.body, member.username FROM pm JOIN member ON (member.id = pm.author) WHERE pm.id=$iMessageID AND pm.owner={$_SESSION['userid']}");
		$aSQLResult = mysql_fetch_row($sqlResult);

		// Change the subject and add a copy of the message being forwarded.
		$strSubject = htmlspecialchars("Fw: $aSQLResult[2]");
		$strMessage = "\n\n[quote][i]{$aSQLResult[4]} wrote on [dt={$aSQLResult[0]}]:[/i]\n[b]{$aSQLResult[3]}[/b][/quote]";
	}
	// Are they replying?
	if($_REQUEST['action'] == 'reply')
	{
		// Yes, get the message they want to reply to.
		$iMessageID = mysql_real_escape_string((int)$_REQUEST['id']);
		$sqlResult = sqlquery("SELECT pm.datetime, pm.author, pm.subject, pm.body, member.username FROM pm JOIN member ON (member.id = pm.author) WHERE pm.id=$iMessageID AND pm.owner={$_SESSION['userid']}");
		$aSQLResult = mysql_fetch_row($sqlResult);

		// Set the recipient & subject, and add a copy of the message being forwarded.
		$strRecipient = htmlspecialchars($aSQLResult[4]);
		$strSubject = htmlspecialchars("Re: $aSQLResult[2]");
		$strMessage = "\n\n[quote][i]{$aSQLResult[4]} wrote on [dt={$aSQLResult[0]}]:[/i]\n[b]{$aSQLResult[3]}[/b][/quote]";
	}

	// Are they specifying a user ID?
	if(isset($_REQUEST['userid']))
	{
		// Yes, so get the username of the user whose ID was specified.
		$iUserID = mysql_real_escape_string($_REQUEST['userid']);
		$sqlResult = sqlquery("SELECT username FROM member WHERE id=$iUserID");
		list($strRecipient) = mysql_fetch_row($sqlResult);
		$strRecipient = htmlspecialchars($strRecipient);
	}

	// Get the smilies installed.
	require('includes/smilies.inc.php');

	// Get the thread icons installed.
	require('includes/posticons.inc.php');

	// Header
	$strPageTitle = ' :: Private Messages :. New Message';
	require('includes/header.inc.php');

	// The beef.
	require('includes/pm/newmessage.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

// *************************************************************************** \\

// The user hit the Send Message button, so that's what we'll try to do.
function SendMessage()
{
	global $CFG;

	// Get the values from the user.
	$strRecipient = mysql_real_escape_string($_REQUEST['recipient']);
	$strSubject = $_REQUEST['subject'];
	$iPostIcon = (int)$_REQUEST['icon'];
	$strMessage = $_REQUEST['message'];
	$bDisableSmilies = (int)(bool)$_REQUEST['dsmilies'];
	$bTracking = (int)(bool)$_REQUEST['track'];

	// Recipient
	$sqlResult = sqlquery("SELECT id FROM member WHERE username='$strRecipient'");
	list($iRecipientID) = mysql_fetch_row($sqlResult);
	if($iRecipientID === NULL)
	{
		// The user whose name they specified does not exist.
		$aError[] = 'The user you specified does not exist.';
	}
	else if($iRecipientID == $_SESSION['userid'])
	{
		// They're trying to send themself a message!
		$aError[] = 'You cannot send private messages to yourself.';
	}

	// Subject
	if(trim($strSubject) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a subject.';
	}
	else if(strlen($strSubject) > 64)
	{
		// The subject they specified is too long.
		$aError[] = 'The subject you specified is longer than 64 characters.';
	}
	$strSubject = mysql_real_escape_string($strSubject);

	// Icon
	if(($iPostIcon < 0) || ($iPostIcon > 14))
	{
		// They don't know what icon they want. We'll give them none.
		$iPostIcon = 0;
	}

	// Message
	if(trim($strMessage) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a message.';
	}
	else if(strlen($strMessage) > 5000)
	{
		// The message they specified is too long.
		$aError[] = 'The message you specified is longer than 5000 characters.';
	}
	if($_REQUEST['parseemails'])
	{
		$strMessage = ParseEMails($strMessage);
	}
	$strMessage = mysql_real_escape_string($strMessage);

	// If there was an error, let's return it.
	if(is_array($aError))
	{
		return $aError;
	}

	// Calculate the IP address of the user.
	$iAuthorIP = ip2long($_SERVER['REMOTE_ADDR']);

	// Add the message to the database.
	sqlquery("INSERT INTO pm(owner, datetime, author, recipient, subject, body, parent, ipaddress, icon, dsmilies, beenread, tracking) VALUES($iRecipientID, {$CFG['globaltime']}, {$_SESSION['userid']}, $iRecipientID, '$strSubject', '$strMessage', 0, $iAuthorIP, $iPostIcon, $bDisableSmilies, 0, $bTracking)");

	// Did they want to save a copy?
	if($_REQUEST['savecopy'])
	{
		// Yes, so do so.
		sqlquery("INSERT INTO pm(owner, datetime, author, recipient, subject, body, parent, ipaddress, icon, dsmilies, beenread) VALUES({$_SESSION['userid']}, {$CFG['globaltime']}, {$_SESSION['userid']}, $iRecipientID, '$strSubject', '$strMessage', 1, $iAuthorIP, $iPostIcon, 0, $bDisableSmilies)");
	}

	// Was this message a reply to another one?
	if($_REQUEST['action'] == 'reply')
	{
		// Yes, mark the original message as been replied.
		$iMessageID = (int)$_REQUEST['id'];
		sqlquery("UPDATE pm SET replied=1 WHERE id=$iMessageID AND owner={$_SESSION['userid']}");
	}

	// Render the page.
	Msg("<b>Your message has been successfully sent.</b><br><br><font class=\"smaller\">You should be redirected momentarily. Click <a href=\"private.php\">here</A> if you do not want to wait any longer or if you are not redirected.</font>", 'private.php', 'center');
}

// *************************************************************************** \\

function Tracking()
{
	global $CFG;

	// Get the thread icons installed.
	require('includes/posticons.inc.php');

	// Get all messages that we've sent that have tracking enabled.
	$sqlResult = sqlquery("SELECT id, datetime, recipient, subject, icon, beenread, readtime FROM pm WHERE author={$_SESSION['userid']} AND tracking=1 ORDER BY datetime DESC");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Has this message been read or unread?
		if($aSQLResult['beenread'])
		{
			// Read.
			$iMessageID = $aSQLResult['id'];
			$aRead[$iMessageID][DATETIME] = $aSQLResult['datetime'];
			$aRead[$iMessageID][RECIPIENT] = $aSQLResult['recipient'];
			$aRead[$iMessageID][SUBJECT] = htmlspecialchars($aSQLResult['subject']);
			$aRead[$iMessageID][ICON1][URL] = 'images/message_old.png';
			$aRead[$iMessageID][ICON1][ALT] = 'Read Message';
			$aRead[$iMessageID][ICON2][URL] = 'images/'.$aPostIcons[$aSQLResult['icon']]['filename'];
			$aRead[$iMessageID][ICON2][ALT] = $aPostIcons[$aSQLResult['icon']]['title'];
			$aRead[$iMessageID][READTIME] = $aSQLResult['readtime'];
		}
		else
		{
			// Unread.
			$iMessageID = $aSQLResult['id'];
			$aUnread[$iMessageID][DATETIME] = $aSQLResult['datetime'];
			$aUnread[$iMessageID][RECIPIENT] = $aSQLResult['recipient'];
			$aUnread[$iMessageID][SUBJECT] = htmlspecialchars($aSQLResult['subject']);
			$aUnread[$iMessageID][ICON1][URL] = 'images/message_new.png';
			$aUnread[$iMessageID][ICON1][ALT] = 'Unread Message';
			$aUnread[$iMessageID][ICON2][URL] = 'images/'.$aPostIcons[$aSQLResult['icon']]['filename'];
			$aUnread[$iMessageID][ICON2][ALT] = $aPostIcons[$aSQLResult['icon']]['title'];
		}

		// Add the author to our list of users to get names for.
		$aUserIDs[] = $aSQLResult['recipient'];
	}

	if(is_array($aUserIDs))
	{
		// Remove duplicates from our user ID list.
		$aUserIDs = array_unique($aUserIDs);

		// Query the MySQL database to get the usernames.
		$sqlResult = sqlquery("SELECT id, username FROM member WHERE id IN (".implode(", ", $aUserIDs).")");
		while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
		{
			// Store the username in the usernames array.
			$aUsernames[$aSQLResult['id']] = htmlspecialchars($aSQLResult['username']);
		}
	}

	// Header.
	$strPageTitle = ' :: Private Messages :. Message Tracking';
	require('includes/header.inc.php');

	// The beef.
	require('includes/pm/tracking.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

// *************************************************************************** \\

function Folders()
{
	global $CFG;

	// Get a list of our custom folders.
	$sqlResult = sqlquery("SELECT pmfolders FROM member WHERE id={$_SESSION['userid']}");
	list($strFolders) = mysql_fetch_row($sqlResult);
	$aFolders = unserialize($strFolders);

	// Are they submitting?
	if($_REQUEST['submit'] == 'Save Changes')
	{
		// Yes.
		EditFolders($aFolders);
	}

	// Header.
	$strPageTitle = ' :: Private Messages :. Manage Folders';
	require('includes/header.inc.php');

	// The beef.
	require('includes/pm/folders.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

function EditFolders($aCurrentFolders)
{
	global $CFG;

	// Insert placeholders for the Inbox and Sent Items folders.
	$aFolders = array(0 => 'Inbox', 1 => 'Sent Items');

	// Process the submitted current folders.
	foreach((array)$_REQUEST['curfolders'] as $k => $v)
	{
		if(array_key_exists($k, $aCurrentFolders) && (trim($v) != ''))
		{
			$aFolders[$k] = $v;
		}
	}

	// Process the submitted new folders.
	foreach((array)$_REQUEST['newfolders'] as $k => $v)
	{
		if(trim($v) != '')
		{
			$aFolders[] = $v;
		}
	}

	// Remove the dummy entries.
	unset($aFolders[0]);
	unset($aFolders[1]);

	// Are we left with any folders?
	if(count($aFolders))
	{
		// Serialize and sterilize our folder list.
		$strFolders = mysql_real_escape_string(serialize($aFolders));

		// Save the updated folder list.
		sqlquery("UPDATE member SET pmfolders='$strFolders' WHERE id={$_SESSION['userid']}");

		// Make a list of folders to be deleted.
		if(is_array($aCurrentFolders))
		{
			$aToDelete = array_values(array_diff(array_flip($aCurrentFolders), array_flip($aFolders)));
		}
	}
	else
	{
		// Set the folder list to NULL.
		sqlquery("UPDATE member SET pmfolders=NULL WHERE id={$_SESSION['userid']}");

		// Make a list of folders to be deleted.
		if(is_array($aCurrentFolders))
		{
			$aToDelete = array_values(array_flip($aCurrentFolders));
		}
	}

	// Are there any folders to delete?
	if(is_array($aToDelete) && count($aToDelete))
	{
		// Yes, put the list into a string for SQL.
		$strFolders = implode(', ', $aToDelete);

		// Move any messages that were in deleted folders to the Inbox.
		sqlquery("UPDATE pm SET parent=0 WHERE parent IN ($strFolders)");
	}

	// Header
	$strPageTitle = ' :: Private Messages';
	$strRedirect = 'usercp.php';
	require('includes/header.inc.php');

	// Render the page.
	Msg("<b>Your folders were successfully updated. Any messages in folders you deleted have been moved into your Inbox.</b><br><br><font class=\"smaller\">You should be redirected to the User Control Panel momentarily. Click <A href=\"usercp.php\">here</a><br>if you do not want to wait any longer or if you are not redirected.</font>", 'usercp.php', 'center');
}

// *************************************************************************** \\

function Delete()
{
	global $CFG;

	// Get the list of messages to be deleted.
	$aMessages = $_REQUEST['id'];

	// Delete the messages.
	if(is_array($aMessages))
	{
		$strMessages = mysql_real_escape_string(implode(', ', $aMessages));
		sqlquery("DELETE FROM pm WHERE id IN ($strMessages) AND owner={$_SESSION['userid']}");
	}

	// Render the page.
	Msg("<b>The message(s) were successfully deleted.</b><br><br><font class=\"smaller\">You should be redirected momentarily. Click <a href=\"private.php\">here</a> if you do not want to wait any longer or if you are not redirected.</font>", 'private.php', 'center');
}

// *************************************************************************** \\

function Move()
{
	global $CFG;

	// Get the list of messages to be moved.
	$aMessages = $_REQUEST['id'];

	// Get the destination.
	$iDestinationID = (int)$_REQUEST['dest'];

	// Get a list of our custom folders.
	$sqlResult = sqlquery("SELECT pmfolders FROM member WHERE id={$_SESSION['userid']}");
	list($strFolders) = mysql_fetch_row($sqlResult);
	$aFolders = unserialize($strFolders);

	// Move the messages.
	if(is_array($aMessages) && ($aFolders[$iDestinationID]))
	{
		$strMessages = mysql_real_escape_string(implode(', ', $aMessages));
		sqlquery("UPDATE pm SET parent=$iDestinationID WHERE id IN ($strMessages) AND owner={$_SESSION['userid']}");
	}

	// Render the page.
	Msg("<b>The message(s) were successfully moved.</b><br><br><font class=\"smaller\">You should be redirected momentarily. Click <a href=\"private.php\">here</A> if you do not want to wait any longer or if you are not redirected.</font>", 'private.php', 'center');
}

// *************************************************************************** \\

function PrintCPMenu()
{
	global $CFG;
?>

<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%">
<TR>
	<TD align=center bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller><B><A href="usercp.php">My OvBB Home</A></B></TD>
	<TD align=center bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller><B><A href="usercp.php?section=profile">Edit Profile</A></B></TD>
	<TD align=center bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller><B><A href="usercp.php?section=options">Edit Options</A></B></TD>
	<TD align=center bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller><B><A href="usercp.php?section=password">Edit Password</A></B></TD>
	<TD align=center bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller><B><A href="usercp.php?section=buddylist">Edit Buddy List</A></B></TD>
	<TD align=center bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller><B><A href="usercp.php?section=ignorelist">Edit Ignore List</A></B></TD>
	<TD align=center bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=smaller><B><A href="private.php">Private Messages</A></B></TD>
</TR>
</TABLE>

<?php
}
?>