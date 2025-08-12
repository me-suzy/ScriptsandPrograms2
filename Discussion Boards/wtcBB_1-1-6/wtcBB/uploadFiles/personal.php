<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############ //FRONT END - PERSONAL MESSAGING\\ ########### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_bbcode.php");
include("./includes/functions_messages.php");
include("./includes/functions_usercp.php");
include("./global.php");

// we need to define a TIME for this file
// if we don't, it could result in attachments not working
define(NOW,time());

if(empty($hash)) {
	$theAttachmentHash = md5(NOW.$userinfo['userid'].$userinfo['username']);
} else {
	$theAttachmentHash = $hash;
}

// get forum home stylesheet and usercp stylesheet
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_threaddisplay")."\";");
eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_forumhome")."\";");
eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_usercp")."\";");

// if no css file.. get internetl block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

// make sure logged in...
if(!$userinfo['userid'] OR !$usergroupinfo[$userinfo['usergroupid']]['personal_max_messages']) {
	doError(
		"perms",
		"Error Viewing Personal Messages"
	);
}

// messaging disabled?
if(!$bboptions['personal_enabled']) {
	doError(
		"The administrator has disabled the Personal Messaging system.",
		"Error Viewing Personal Messages",
		"Messaging Disabled"
	);
}

$page = $_REQUEST['page'];

// if no get.. show PM main
if(!$_GET OR ($_GET['page'] AND !$_GET['pid'] AND !$_GET['folder'] AND !$_GET['do'])) {
	// delete subscriptions
	if(is_array($_POST['deletePms'])) {
		$which = "";
		$which2 = "";

		// which one?
		if($_POST['moveSubmit']) {
			// loop through and move pms
			foreach($_POST['deletePms'] as $pid => $va) {
				query("UPDATE personal_msg SET folderid = '".$_POST['folder']."' WHERE pid = '".$pid."'");
			}

			$which = "moved";
			$which2 = "Moving";
		}

		else {
			// loop through and delete pms
			foreach($_POST['deletePms'] as $pid => $va) {
				// delete
				query("DELETE FROM personal_msg WHERE pid = '".$pid."' LIMIT 1");
			}

			$which = "deleted";
			$which2 = "Deleting";
		}

		doThanks(
			"You have successfully ".$which." the selected personal messages.",
			"Personal Messages",
			$which2." Personal Messages",
			$_SERVER['HTTP_REFERER']
		);
	}

	// create nav bar array
	$navbarArr = Array(
		"User Control Panel" => "usercp.php",
		"Personal Messaging" => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// deal with sessions
	$sessionInclude = doSessions("Personal Messaging","none");
	include("./includes/sessions.php");

	// get the unread personal messages
	$unread = query("SELECT * FROM personal_msg LEFT JOIN personal_folder ON personal_msg.folderid = personal_folder.folderid LEFT JOIN user_info ON user_info.userid = personal_msg.userid WHERE personal_msg.isRead = 0 AND personal_msg.folderid != 2 AND personal_msg.sentTo = '".$userinfo['userid']."' ORDER BY personal_msg.date_sent DESC");

	if(!mysql_num_rows($unread)) {
		eval("\$newPms = \"".getTemplate("personal_main_nonew")."\";");
	}

	// loop through
	else {
		// now get the amount of posts to show...
		$pmNum = $bboptions['personal_messages_per_page'];
		$numOfPms = mysql_num_rows($unread);

		// grab page...
		if(!isset($page)) {
			$page = 1;
		}

		$end = $pmNum;

		// get start before REAL end
		$start = ($page - 1) * $end;

		// real end...
		$end *= $page;

		// intiate post counter...
		$pmCounter = 0;

		if($numOfPms % $pmNum != 0) {
			$totalPages = ($numOfPms / $pmNum) + 1;
			settype($totalPages,"integer");
		} else {
			$totalPages = $numOfPms / $pmNum;
		}

		// build the page links...
		$pagelinks = buildPageLinks($totalPages,$page);

		while($pminfo = mysql_fetch_array($unread)) {
			// increment
			$pmCounter++;

			// make sure we're in right place...
			if($pmCounter <= $start) {
				// move on...
				continue;
			}

			if($pmCounter > $end) {
				// not going to be showing anymore...
				// so break out!
				break;
			}

			$thePmId = $pminfo['pid'];
			$fromUsername = getHTMLUsername($pminfo);
			$sentDate = processDate($bboptions['date_formatted'],$pminfo['date_sent']);
			$sentTime = processDate($bboptions['date_time_format'],$pminfo['date_sent']);

			eval("\$bits .= \"".getTemplate("personal_main_new_bit")."\";");
		}

		// grab whole template
		eval("\$newPms = \"".getTemplate("personal_main_new")."\";");
	}

	// get whole template
	eval("\$content = \"".getTemplate("personal_main")."\";");
}

// send PM 
else if($_GET['do'] == "send") {
	$smileyChecked = '';
	$bbcodeChecked = '';
	$defaultBBCodeChecked = '';
	$sigChecked = '';

	if($_POST) {
		$subject = $_POST['subject'];
		$recipients = $_POST['recipients'];

		if($_POST['showSig']) {
			$sigChecked = ' checked="checked"';
		} else {
			$sigChecked = '';
		}

		if($_POST['parseSmilies']) {
			$smileyChecked = ' checked="checked"';
		} else {
			$smileyChecked = '';
		}

		if($_POST['parseBBcode']) {
			$bbcodeChecked = ' checked="checked"';
		} else {
			$bbcodeChecked = '';
		}

		if($_POST['defaultBBCode']) {
			$defaultBBCodeChecked = ' checked="checked"';
		}

		if($_POST['receipt']) {
			$receiptChecked = ' checked="checked"';
		} else {
			$receiptChecked = '';
		}

		$_POST['postMessage'] = trim($_POST['postMessage']);

		// do error messages
		if(strlen($_POST['postMessage']) > $bboptions['personal_max_chars']) {
			$theError = printStandardError("error_standard","Sorry, you are over the personal message maximum character limit.",0);
		}

		// empty
		else if(!$_POST['postMessage'] OR !$_POST['recipients'] OR !$_POST['subject']) {
			$theError = printStandardError("error_standard","You must fill out all fields in order to continue.",0);
		}

		// flood check...
		else if(!$usergroupinfo[$userinfo['usergroupid']]['flood_immunity'] AND (NOW - $bboptions['floodcheck']) < $userinfo['lastPM']) {
			$theError = printStandardError("error_standard","The administrator has specified you may only send a message every ".$bboptions['floodcheck']." seconds.",0);
		}

		else {
			// so... preview or not?
			if($_POST['preview']) {
				// make a copy of $_POST['postMessage'] to use in preiview... and thread title
				$subjectCopy = $_POST['subject'];
				$postMessageCopy = $_POST['postMessage'];

				$postMessageCopy = parseMessage($postMessageCopy,$bboptions['allow_wtcBB_sig'],$bboptions['allow_smilies_sig'],$bboptions['allow_img_sig'],(!$bboptions['allow_html_sig'] AND !$userinfo['allow_html']),$_POST['parseBBcode'],$_POST['parseSmilies'],$userinfo['username'],$userinfo,$_POST['defaultBBCode']);
				$subjectCopy = replaceReplacements(doCensors(htmlspecialchars($subjectCopy)));

				eval("\$possiblePreview = \"".getTemplate("personal_sendMessage_preview")."\";");
			}

			// insert
			else {
				// split the recipients
				$recipArr = split(",",$recipients);

				// loop
				$x = 1;
				foreach($recipArr as $key => $username) {
					if($x > $usergroupinfo[$userinfo['usergroupid']]['personal_max_users']) {
						$theErrors[$username]['error'] = "You have already sent it to ".$usergroupinfo[$userinfo['usergroupid']]['personal_max_users']." users.";
						break;
					}

					// rebuild
					$newRecips .= ", ".htmlspecialchars(addslashes(trim($username)));

					// get user info, and message rules
					$user_q = query("SELECT * FROM user_info WHERE user_info.username = '".addslashes(htmlspecialchars(trim($username)))."' LIMIT 1");

					if(!mysql_num_rows($user_q)) {
						$theErrors[$username]['error'] = "The user does not exist.";
						continue;
					}

					// fetch arr
					$user = mysql_fetch_array($user_q);

					// make sure user wants PMs
					if(!$user['use_pm'] OR !$usergroupinfo[$user['usergroupid']]['personal_max_messages'] OR $user['is_coppa']) {
						$theErrors[$username]['error'] = "The user does not accept personal messages.";
						continue;
					}

					// make sure user can fit it...
					$allMessages = query("SELECT COUNT(*) AS numOfMessages FROM personal_msg WHERE sentTo = '".$user['userid']."' OR (sentTo = 0 AND userid = '".$user['userid']."')",1);

					// yikes!
					if($allMessages['numOfMessages'] >= $usergroupinfo[$user['usergroupid']]['personal_max_messages']) {
						$theErrors[$username]['error'] = "The user's Personal Message quota has reached its limit, and this user cannot receive any PM's until room has been made.";
						
						// email alert?
						if($bboptions['enable_email']) {
							$username = $user['username'];
							$quota = $usergroupinfo[$user['usergroupid']]['personal_max_messages'];
							eval("\$message = \"".getTemplate("mail_pmFullQuota")."\";");
							mail($user['email'],"wtcBB Mailer - You have reached your Quota",$message,"From: ".$bboptions['details_contact']);
						}
						
						continue;
					}

					if($_POST['defaultBBCode']) {
						$_POST['postMessage'] = addDefaultBBCode($_POST['postMessage'],$userinfo);
					}

					// insert PM
					$insertPM = query("INSERT INTO personal_msg (title,message,userid,sentTo,ip_address,date_sent,alert,isRead,folderid,pmHash,show_sig,parse_smilies,parse_bbcode,defBBCode) VALUES ('".htmlspecialchars(addslashes(trim($_POST['subject'])))."','".addslashes(trim($_POST['postMessage']))."','".$userinfo['userid']."','".$user['userid']."','".$_SERVER['REMOTE_ADDR']."','".NOW."',1,0,1,'".$theAttachmentHash."','".$_POST['showSig']."','".$_POST['parseSmilies']."','".$_POST['parseBBcode']."','".$_POST['defaultBBCode']."')");

					$insertPMID = mysql_insert_id();

					// message rules?
					$msgRules_q = query("SELECT * FROM personal_rules WHERE userid = '".$user['userid']."' ORDER BY exec_order ASC");

					// if rows, loop
					if(mysql_num_rows($msgRules_q)) {
						while($rules = mysql_fetch_array($msgRules_q)) {
							if($rules['userORgroup'] == "user" AND strtolower($userinfo['username']) == strtolower($rules['value'])) {
								if($rules['moveORdelete'] == "move") {
									query("UPDATE personal_msg SET folderid = '".$rules['value2']."' WHERE pid = '".$insertPMID."'");
								}
								
								if($rules['moveORdelete'] == "delete") {
									query("DELETE FROM personal_msg WHERE pid = '".$insertPMID."'");
									$isDelete = true;
								}
							}

							if($rules['userORgroup'] == "group" AND $userinfo['usergroupid'] == $rules['value']) {
								if($rules['moveORdelete'] == "move") {
									query("UPDATE personal_msg SET folderid = '".$rules['value2']."' WHERE pid = '".$insertPMID."'");
								}

								if($rules['moveORdelete'] == "delete") {
									query("DELETE FROM personal_msg WHERE pid = '".$insertPMID."'");
									$isDelete = true;
								}
							}
						}
					}

					// send a PM receipt?
					if($_POST['receipt'] AND !$isDelete AND $usergroupinfo[$userinfo['usergroupid']]['personal_receipts']) {
						query("INSERT INTO personal_receipt (receipt_pid,receipt_title,receipt_sentTo,confirmed,userid,receipt_sent) VALUES ('".$insertPMID."','".htmlspecialchars(addslashes(trim($_POST['subject'])))."','".$user['userid']."',0,'".$userinfo['userid']."','".NOW."')");
					}

					// email alert?
					if($bboptions['enable_email'] AND $user['send_email_pm']) {
						$username = $user['username'];
						$fromUsername = $userinfo['username'];
						$pmTitle = trim($_POST['subject']);
						eval("\$message = \"".getTemplate("mail_pmNotification")."\";");
						mail($user['email'],"wtcBB Mailer - PM Notification",$message,"From: ".$bboptions['details_contact']);
					}

					$x++;
				}

				$newRecips = preg_replace("|^,|","",$newRecips);

				// only add sent message if we won't go over the quota...
				$allMessages2 = query("SELECT COUNT(*) AS numOfMessages FROM personal_msg WHERE sentTo = '".$userinfo['userid']."' OR (sentTo = 0 AND userid = '".$userinfo['userid']."')",1);

				// yikes!
				if($allMessages2['numOfMessages'] < $usergroupinfo[$userinfo['usergroupid']]['personal_max_messages']) {
					// insert sent message
					$insertSentPM = query("INSERT INTO personal_msg (title,message,userid,sentTo,ip_address,date_sent,alert,isRead,folderid,pmHash,show_sig,parse_smilies,parse_bbcode,recipients,defBBCode) VALUES ('".htmlspecialchars(addslashes(trim($_POST['subject'])))."','".addslashes(trim($_POST['postMessage']))."','".$userinfo['userid']."',0,'".$_SERVER['REMOTE_ADDR']."','".NOW."',0,1,2,'".$theAttachmentHash."','".$_POST['showSig']."','".$_POST['parseSmilies']."','".$_POST['parseBBcode']."','".trim($newRecips)."','".$_POST['defaultBBCode']."')");
				}

				// update last pm...
				query("UPDATE user_info SET lastPM = '".NOW."' WHERE userid = '".$userinfo['userid']."'");

				if(!is_array($theErrors)) {
					$msg = "You have successfully sent your message to the recipient(s): ".trim($newRecips).".";
				}

				else {
					$errorMessage = "";

					foreach($theErrors as $username => $arr) {
						$errorMessage .= "<p style=\"margin-bottom: 10px;\">There was a problem sending your personal message to ".$username.". ".$arr['error']."</p>\n\n";
					}

					$msg = $errorMessage."<p>Even though there were errors, your personal messages were still sent to users that didn't return an error.</p>";
				}

				doThanks(
					$msg,
					"Sending Personal Message",
					trim($newRecips),
					"personal.php",
					!is_array($theErrors)
				);
			}
		}
	}

	else {
		if(!$userinfo['signature'] OR !$bboptions['allow_signatures'] OR $userinfo['ban_sig'] OR !$userinfo['view_signature'] OR !$usergroupinfo[$userinfo['usergroupid']]['can_sig']) {
			$sigChecked = '';
		} else {
			$sigChecked = ' checked="checked"';
		}

		$smileyChecked = ' checked="checked"';
		$bbcodeChecked = ' checked="checked"';
		$defaultBBCodeChecked = '';

		if($userinfo['useDefault']) {
			$defaultBBCodeChecked = ' checked="checked"';
		}		
		
		$_POST['postMessage'] = '';
		$receiptChecked = ' checked="checked"';

		// what if we're "replying" to someone?
		if($_GET['u']) {
			$recipients = $_GET['u'];
		}

		// what if we're quoting?
		if($_GET['q']) {
			$quoteQuery = query("SELECT * FROM personal_msg LEFT JOIN user_info ON user_info.userid = personal_msg.userid WHERE pid = '".$_GET['q']."' LIMIT 1");

			// make sure rows..
			if(mysql_num_rows($quoteQuery)) {
				$quoteinfo = mysql_fetch_array($quoteQuery);

				// do replacements...
				$quoteinfo['message'] = replaceReplacements($quoteinfo['message']);

				// censor message
				$quoteinfo['message'] = doCensors($quoteinfo['message']);

				// get rid of embedded quotes
				$quoteinfo['message'] = preg_replace("#\[quote=(.*)\](.*)\[/quote\]#eisU","",$quoteinfo['message']);
				$quoteinfo['message'] = preg_replace("|(\[quote\])(.*)(\[/quote\])|isU","",$quoteinfo['message']);

				// mmmmmhmmmm leftovers!
				$quoteinfo['message'] = preg_replace("|\[quote\]|","",$quoteinfo['message']);
				$quoteinfo['message'] = preg_replace("|\[/quote\]|","",$quoteinfo['message']);

				// fix backslash thing...
				$quoteinfo['message'] = str_replace("\\","\\\\\\\\",$quoteinfo['message']);

				// get quote
				$_POST['postMessage'] = "[quote=".$quoteinfo['username']."]".htmlspecialchars($quoteinfo['message'])."[/quote]\n\n".htmlspecialchars(addslashes($_POST['postMessage']));

				$subject = "Re: ".$quoteinfo['title'];
			}

			// trim whitespace
			$_POST['postMessage'] = preg_replace("#(\[quote=.*\])(.*)(\[/quote\])#eisU","trimQuote('$1','$2','$3')",$_POST['postMessage']);
			$_POST['postMessage'] = preg_replace("|\n\n$|","\n",$_POST['postMessage']);
		}
	}

	// get all the posting rules...
	if($bboptions['allow_wtcBB_personal']) {
		$wtcBBcode = "may";

		if(!$_POST) {
			$bbcodeChecked = ' checked="checked"';
		}
	} else {
		$wtcBBcode = "may not";
	}

	if($bboptions['allow_smilies_personal']) {
		$wtcBBsmilies = "may";

		if(!$_POST) {
			$smileyChecked = ' checked="checked"';
		}
	} else {
		$wtcBBsmilies = "may not";
	}

	if($bboptions['allow_img_personal']) {
		$wtcBBimg = "may";
	} else {
		$wtcBBimg = "may not";
	}

	if($bboptions['allow_html_personal'] OR $userinfo['allow_html']) {
		$wtcBBhtml = "may";
	} else {
		$wtcBBhtml = "may not";
	}

	// make sure they want smilies...
	if($bboptions['clickable_smilies_total'] > 0 AND $bboptions['allow_smilies_personal']) {
		// get all smilies
		// limit to the total smilies
		$allSmilies = query("SELECT * FROM smilies ORDER BY display_order");

		$smilies = buildClickableSmilies();

		// more smilies?
		if($bboptions['clickable_smilies_total'] < mysql_num_rows($allSmilies)) {
			eval("\$moreSmilies = \"".getTemplate("smileybox_moresmilies")."\";");
		} else {
			$moreSmilies = "";
		}
		
		// grab smilies template
		eval("\$clickableSmilies = \"".getTemplate("smileybox")."\";");
	}

	// get colors and fonts for toolbar...
	$toolbarColors = buildToolbarColors();
	$toolbarFonts = buildToolbarFonts();

	// use metaRedirect var to sneek in a javascript...
	$metaRedirect = "<script type=\"text/javascript\" src=\"scripts/message.js\"></script>";

	// get toolbar..
	if($bboptions['toolbar'] AND $userinfo['toolbar']) {
		eval("\$toolBar = \"".getTemplate("usercp_signature_toolbar")."\";");
	} else {
		$toolBar = "";
	}

	// attachments?
	if($bboptions['allow_attachments'] AND $bboptions['attachments_per_post'] > 0 AND $usergroupinfo[$userinfo['usergroupid']]['can_upload_attachments']) {
		$attachURI = "editattach.php?pm=yes&amp;hash=".$theAttachmentHash;
		eval("\$attachmentOptions = \"".getTemplate("message_attach")."\";");
	} else {
		$attachmentOptions = "";
	}

	// get pm receipt...
	// only if usergroup allows message receipts
	if($usergroupinfo[$userinfo['usergroupid']]['personal_receipts']) {
		eval("\$pmReceipt = \"".getTemplate("personal_sendMessage_pmReceipt")."\";");
	} else {
		$pmReceipt = "";
	}

	$maxRecipients = $usergroupinfo[$userinfo['usergroupid']]['personal_max_users'];

	// get template
	eval("\$content = \"".getTemplate("personal_sendMessage")."\";");
}

// view PM
if($_GET['pid']) {
	// make sure of valid PM
	if($_GET['folder'] == 2) {
		$pm_q = query("SELECT * FROM personal_msg WHERE personal_msg.pid = '".$_GET['pid']."'");
	} else {
		$pm_q = query("SELECT * FROM personal_msg LEFT JOIN user_info ON user_info.userid = personal_msg.userid LEFT JOIN personal_folder ON personal_msg.folderid = personal_folder.folderid LEFT JOIN personal_receipt ON personal_receipt.receipt_pid = personal_msg.pid WHERE personal_msg.pid = '".$_GET['pid']."'");
	}

	// uh oh...
	if(!mysql_num_rows($pm_q)) {
		doError(
			"There is no personal message in the database with the given criteria.",
			"Error Viewing Personal Message",
			"Message Doesn't Exist"
		);
	}

	// good to!
	$pminfo = mysql_fetch_array($pm_q);

	// merge
	if($_GET['folder'] == 2) {
		$pminfo = array_merge($pminfo,$userinfo);
	}

	// make sure this user can see it!
	if(($pminfo['sentTo'] != $userinfo['userid'] AND (!$_GET['folder'] OR $_GET['folder'] != 2)) OR ($_GET['folder'] == 2 AND $pminfo['userid'] != $userinfo['userid'])) {
		doError(
			"perms",
			"Error Viewing Personal Messages"
		);
	}

	// marking read or unread?
	if($_GET['do']) {
		// mark read
		if($_GET['do'] == "read") {
			query("UPDATE personal_msg SET isRead = 1 WHERE pid = '".$pminfo['pid']."'");

			doThanks(
				"You have successfully marked the message entitled <strong>".$pminfo['title']."</strong> read.",
				"Marking Message Read",
				"none",
				$_SERVER['HTTP_REFERER']
			);
		}

		// mark unread
		if($_GET['do'] == "unread") {
			query("UPDATE personal_msg SET isRead = 0 WHERE pid = '".$pminfo['pid']."'");

			doThanks(
				"You have successfully marked the message entitled <strong>".$pminfo['title']."</strong> Unread.",
				"Marking Message Unread",
				"none",
				$_SERVER['HTTP_REFERER']
			);
		}
	}

	// deal with sessions
	$sessionInclude = doSessions("Viewing Personal Message","From: ".$pminfo['username']);
	include("./includes/sessions.php");

	if($_GET['folder'] == 2) {
		$navbarArr = Array(
			"User Control Panel" => "usercp.php",
			"Personal Messaging" => "personal.php",
			"sent items" => "personal.php?folder=".$pminfo['folderid'],
			$pminfo['title'] => "#"
		);
	}

	else {
		$navbarArr = Array(
			"User Control Panel" => "usercp.php",
			"Personal Messaging" => "personal.php",
			$pminfo['folderName'] => "personal.php?folder=".$pminfo['folderid'],
			$pminfo['title'] => "#"
		);
	}

	$navbarText = getNavbarLinks($navbarArr);

	$attachments = buildAttachments(0,$pminfo['pmHash']);

	// process some dats...
	$registered = processDate($bboptions['date_register_format'],$pminfo['date_joined']);
	$dateSent = processDate($bboptions['date_formatted'],$pminfo['date_sent']);
	$timeSent = processDate($bboptions['date_time_format'],$pminfo['date_sent']);

	// get username
	$theUsername = getHTMLUsername($pminfo);

	// get custom title
	$theCT = getCustomTitle($pminfo);
	
	// avatar
	if($pminfo['avatar_url'] != "none" AND $userinfo['view_avatar'] AND $bboptions['avatar_enabled']) {
		eval("\$theAV = \"".getTemplate("personal_postbit_avatar")."\";");
	} else {
		$theAV = "";
	}

	// posts
	// get posts per day...
	if((NOW - $pminfo['date_joined']) < 86400) {
		$postsPerDay = 1;
	} else {
		$postsPerDay = substr($pminfo['posts'] / ((NOW - $pminfo['date_joined']) / 86400),0,6);
	}
	
	$subTitle = "Posts:";
	$subValue = $pminfo['posts']." (".$postsPerDay." Per Day)";
	if($pminfo['userid']) eval("\$quickinfo .= \"".getTemplate("personal_postbit_quickinfo")."\";");

	// get location
	if(!empty($pminfo['locationUser'])) {
		$subTitle = "Location:";
		$subValue = $pminfo['locationUser'];
		eval("\$quickinfo .= \"".getTemplate("personal_postbit_quickinfo")."\";");
	} else {
		$location = "";
	}

	// join date...
	$subTitle = "Join Date:";
	$subValue = $registered;
	if($pminfo['userid']) eval("\$quickinfo .= \"".getTemplate("personal_postbit_quickinfo")."\";");

	if($bboptions['enableWarn'] AND (($userinfo['userid'] == $arr['userid'] AND $usergroupinfo[$userinfo['usergroupid']]['warn_viewOwn']) OR ($usergroupinfo[$userinfo['usergroupid']]['warn_viewOthers'] AND $arr['userid'] != $userinfo['userid']))) {
		$subTitle = "Warning Level:";
		$subValue = $pminfo['warn'];
		eval("\$quickinfo .= \"".getTemplate("personal_postbit_quickinfo")."\";");
	}

	$pminfo['message'] = parseMessage($pminfo['message'],$pminfo['parse_bbcode'],$pminfo['parse_smilies'],$bboptions['allow_img_personal'],(!$bboptions['allow_html_personal'] AND !$userinfo['allow_html']),$bboptions['allow_wtcBB_personal'],$bboptions['allow_smilies_personal'],$pminfo['username'],$pminfo);
	$pminfo['title'] = replaceReplacements(doCensors(htmlspecialchars($pminfo['title'])));

	if($pminfo['receive_emails'] AND $bboptions['enable_user_email']) {
		eval("\$emailLink = \"".getTemplate("personal_postbit_email")."\";");
	}

	// get the online status...
	eval("\$onlineOffline = \"".getTemplate(fetchOnlineStatus($pminfo['userid']))."\";");

	// do ip address...
	if($bboptions['logip'] != 0 AND $pminfo['ip_address'] AND ($bboptions['logip'] == 2 OR ($bboptions['logip'] == 1 AND ($usergroupinfo[$userinfo['usergroupid']]['is_admin'] OR $usergroupinfo[$userinfo['usergroupid']]['is_super_moderator'])))) {
		eval("\$ipLogged = \"".getTemplate("personal_postbit_loggedip")."\";");
	}

	// make attachments...
	if(is_array($attachments) AND $userinfo['view_attachment']) {
		// loop through it and form template...
		foreach($attachments as $attachid => $attachinfo) {
			eval("\$attachmentbits .= \"".getTemplate("personal_postbit_attach_bit")."\";");
		}

		eval("\$theAttachments = \"".getTemplate("personal_postbit_attach")."\";");
	} else {
		$theAttachments = "";
	}

	// format sig... only if use has set to display sig...
	if(!$pminfo['signature'] OR !$pminfo['show_sig'] OR !$bboptions['allow_signatures'] OR $pminfo['ban_sig'] OR !$userinfo['view_signature'] OR !$usergroupinfo[$pminfo['usergroupid']]['can_sig']) {
		$theSignature = "";
	}

	else {
		// one last thing... cut off sig to maximum amount..
		if($bboptions['maximum_signature'] > 0) {
			$pminfo['signature'] = trimString($pminfo['signature'],$bboptions['maximum_signature'],0);
		}

		$theSig = parseMessage($pminfo['signature'],$bboptions['allow_wtcBB_sig'],$bboptions['allow_smilies_sig'],$bboptions['allow_img_sig'],(!$bboptions['allow_html_sig'] AND !$userinfo['allow_html']),true,true,$pminfo['username']);

		eval("\$theSignature = \"".getTemplate("personal_postbit_signature")."\";");
	}

	// grab the template
	eval("\$content = \"".getTemplate("personal_postbit")."\";");

	if($_GET['folder'] != 2) {
		// update to is read and get rid of alerts
		query("UPDATE personal_msg SET isRead = 1 , alert = 0 WHERE pid = '".$pminfo['pid']."'");
		
		if($pminfo['receiptid'] AND !$pminfo['checked']) {
			// show message to deny?
			if($usergroupinfo[$userinfo['usergroupid']]['personal_deny_receipt']) {
				eval("\$javascript_onORun = ' onunload=\"pmReceipt($pminfo[receiptid],$pminfo[receipt_pid],\'$pminfo[username]\');\"';");
			}

			else {
				query("UPDATE personal_receipt SET confirmed = 1 , checked = 1 , receipt_received = '".NOW."' WHERE receiptid = '".$pminfo['receiptid']."'");
			}
		}
	}
}

// folders
else if($_GET['folder']) {
	// make sure folder exists
	$folder_q = query("SELECT * FROM personal_folder WHERE folderid = '".$_GET['folder']."' AND (userid = '".$userinfo['userid']."' OR userid = -1) LIMIT 1");

	// uh oh!
	if(!mysql_num_rows($folder_q)) {
		doError(
			"No folder with the given criteria exists.",
			"Error Viewing Message Folder",
			"Folder Doesn't Exist"
		);
	}

	// safe to fetch array
	$folder = mysql_fetch_array($folder_q);

	// deal with sessions
	$sessionInclude = doSessions("Viewing Message Folder",$folder['folderName']);
	include("./includes/sessions.php");

	// create nav bar array
	$navbarArr = Array(
		"User Control Panel" => "usercp.php",
		"Personal Messaging" => "personal.php",
		$folder['folderName'] => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// different query if we're in sent items...
	if($folder['folderid'] == 2) {
		$pm_q = query("SELECT * FROM personal_msg WHERE personal_msg.folderid = 2 AND personal_msg.userid = '".$userinfo['userid']."' ORDER BY date_sent DESC");
	} else {
		// make sure we have messages
		$pm_q = query("SELECT * FROM personal_msg LEFT JOIN user_info ON user_info.userid = personal_msg.userid LEFT JOIN personal_receipt ON personal_receipt.receipt_pid = personal_msg.pid WHERE personal_msg.folderid = '".$_GET['folder']."' AND personal_msg.sentTo = '".$userinfo['userid']."' ORDER BY date_sent DESC");
	}

	// uh oh!
	if(!mysql_num_rows($pm_q)) {
		eval("\$messages = \"".getTemplate("personal_folder_noMessages")."\";");
	}

	// good to go
	else {
		// now get the amount of posts to show...
		$pmNum = $bboptions['personal_messages_per_page'];
		$numOfPms = mysql_num_rows($pm_q);

		// grab page...
		if(!isset($page)) {
			$page = 1;
		}

		$end = $pmNum;

		// get start before REAL end
		$start = ($page - 1) * $end;

		// real end...
		$end *= $page;

		// intiate post counter...
		$pmCounter = 0;

		if($numOfPms % $pmNum != 0) {
			$totalPages = ($numOfPms / $pmNum) + 1;
			settype($totalPages,"integer");
		} else {
			$totalPages = $numOfPms / $pmNum;
		}

		// build the page links...
		$pagelinks = buildPageLinks($totalPages,$page);

		while($pminfo = mysql_fetch_array($pm_q)) {
			// increment
			$pmCounter++;

			// make sure we're in right place...
			if($pmCounter <= $start) {
				// move on...
				continue;
			}

			if($pmCounter > $end) {
				// not going to be showing anymore...
				// so break out!
				break;
			}

			$thePmId = $pminfo['pid'];
			if($folder['folderid'] != 2) $fromUsername = getHTMLUsername($pminfo);
			$sentDate = processDate($bboptions['date_formatted'],$pminfo['date_sent']);
			$sentTime = processDate($bboptions['date_time_format'],$pminfo['date_sent']);

			if($folder['folderid'] != 2) {
				// unread or read?
				if($pminfo['isRead']) {
					$unreadMarker = "";
					eval("\$MARK_readORunread = \"".getTemplate("personal_folder_messages_bit_unread")."\";");
				} else {
					// get an image while we're at it
					eval("\$unreadMarker = \"".getTemplate("personal_folder_messages_bit_newMarker")."\";");
					eval("\$MARK_readORunread = \"".getTemplate("personal_folder_messages_bit_read")."\";");
				}
			}

			// show link to confirm?
			if(!$pminfo['confirmed'] AND $folder['folderid'] != 2 AND $pminfo['receiptid']) {
				eval("\$confirmReceipt = \"".getTemplate("personal_folder_messages_bit_confirm")."\";");
			} else {
				$confirmReceipt = "";
			}

			if($folder['folderid'] == 2) {
				eval("\$bits .= \"".getTemplate("personal_sentItems_messages_bit")."\";");
			} else {
				eval("\$bits .= \"".getTemplate("personal_folder_messages_bit")."\";");
			}
		}

		// grab whole template
		if($folder['folderid'] == 2) {
			eval("\$messages = \"".getTemplate("personal_sentItems_messages")."\";");
		} else {
			// get move to folder bits
			$customFolders_q = query("SELECT * FROM personal_folder WHERE userid = '".$userinfo['userid']."'");
			if(mysql_num_rows($customFolders_q) > 0) {
				// form folder bits
				$folderBits = '<option value="1" selected="selected">inbox</option>';

				while($folderinfo = mysql_fetch_array($customFolders_q)) {
					$folderBits .= '<option value="'.$folderinfo['folderid'].'">'.$folderinfo['folderName'].'</option>';
				}

				eval("\$moveToFolder = \"".getTemplate("personal_folder_messages_move")."\";");
			} else {
				$moveToFolder = "";
			}

			eval("\$messages = \"".getTemplate("personal_folder_messages")."\";");
		}
	}

	eval("\$content = \"".getTemplate("personal_folder")."\";");
}

// do message receipts
else if($_GET['do'] == "receipts") {
	if(!$usergroupinfo[$userinfo['usergroupid']]['personal_receipts']) {
		doError(
			"perms",
			"Error Viewing Message Receipts"
		);
	}

	// delete subscriptions
	if(is_array($_POST['deleteReceipts'])) {
		// loop through and delete subscriptions
		foreach($_POST['deleteReceipts'] as $receiptid => $va) {
			// delete
			query("DELETE FROM personal_receipt WHERE receiptid = '".$receiptid."' LIMIT 1");
		}

		doThanks(
			"You have successfully deleted the selected message receipts.",
			"Personal Messages",
			"Deleting Message Receipts",
			"personal.php?do=receipts"
		);
	}

	// make sure we have receipts
	$receipt_q = query("SELECT * , user_info.userid AS user_userid FROM personal_receipt LEFT JOIN user_info ON user_info.userid = personal_receipt.receipt_sentTo WHERE personal_receipt.userid = '".$userinfo['userid']."' ORDER BY receipt_sent DESC");

	// deal with sessions
	$sessionInclude = doSessions("Viewing Message Folder",$folder['folderName']);
	include("./includes/sessions.php");

	// create nav bar array
	$navbarArr = Array(
		"User Control Panel" => "usercp.php",
		"Personal Messaging" => "personal.php",
		"Message Receipts" => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// uh oh...
	if(!mysql_num_rows($receipt_q)) {
		eval("\$messageReceipts = \"".getTemplate("personal_receipts_none")."\";");
	}

	// good to go!
	else {
		// now get the amount of posts to show...
		$receiptNum = $bboptions['personal_messages_per_page'];
		$numOfReceipts = mysql_num_rows($receipt_q);

		// grab page...
		if(!$page) {
			$page = 1;
		}

		$end = $receiptNum;

		// get start before REAL end
		$start = ($page - 1) * $end;

		// real end...
		$end *= $page;

		// intiate post counter...
		$receiptCounter = 0;

		if($numOfReceipts % $receiptNum != 0) {
			$totalPages = ($numOfReceipts / $receiptNum) + 1;
			settype($totalPages,"integer");
		} else {
			$totalPages = $numOfReceipts / $receiptNum;
		}

		// build the page links...
		$pagelinks = buildPageLinks($totalPages,$page);

		while($receipt = mysql_fetch_array($receipt_q)) {
			// increment
			$receiptCounter++;

			// make sure we're in right place...
			if($receiptCounter <= $start) {
				// move on...
				continue;
			}

			if($receiptCounter > $end) {
				// not going to be showing anymore...
				// so break out!
				break;
			}

			$theReceiptId = $receipt['receiptid'];
			$sentToUsername = getHTMLUsername($receipt);
			$sentDate = processDate($bboptions['date_formatted'],$receipt['receipt_sent']);
			$sentTime = processDate($bboptions['date_time_format'],$receipt['receipt_sent']);
			$receivedDate = processDate($bboptions['date_formatted'],$receipt['receipt_received']);
			$receivedTime = processDate($bboptions['date_time_format'],$receipt['receipt_received']);

			// is confirmed?
			if($receipt['confirmed'] == 1) {
				eval("\$confirmed = \"".getTemplate("personal_receipts_messages_bit_confirmed")."\";");
				eval("\$received = \"".getTemplate("personal_receipts_messages_bit_received")."\";");
			} else {
				eval("\$confirmed = \"".getTemplate("personal_receipts_messages_bit_unconfirmed")."\";");
				$received = "";
			}

			eval("\$bits .= \"".getTemplate("personal_receipts_messages_bit")."\";");
		}

		eval("\$messageReceipts = \"".getTemplate("personal_receipts_messages")."\";");
	}

	eval("\$content = \"".getTemplate("personal_receipts")."\";");
}

else if($_GET['do'] == "folders") {
	// empty?
	if($_GET['action'] == "empty") {
		// make sure of existing folder...
		$findFolder = query("SELECT COUNT(*) AS numOfFolders FROM personal_folder WHERE folderid = '".$_GET['fid']."' LIMIT 1",1);

		// uh oh!
		if($findFolder['numOfFolders'] == 0) {
			doError(
				"The folder you are trying to empty does not exist.",
				"Error Emptying Folder",
				"Folder Doesn't Exist"
			);
		}

		// good to go!
		// empty!
		query("DELETE FROM personal_msg WHERE folderid = '".$_GET['fid']."' AND (sentTo = '".$userinfo['userid']."' OR (sentTo = 0 AND userid = '".$userinfo['userid']."'))");

		doThanks(
			"You have successfully emptied the folder.",
			"Emptying Folder",
			"none",
			$_SERVER['HTTP_REFERER']
		);
	}

	if($_POST) {
		if($_POST['addSubmit']) {
			$addFolderName = htmlspecialchars(addslashes(trim($_POST['addFolderName'])));

			// check to make sure folder doesn't exist...
			$checkFolder = query("SELECT COUNT(*) AS folders FROM personal_folder WHERE folderName = '".$addFolderName."' AND (userid = '".$userinfo['userid']."' OR userid = -1)",1);

			// uh oh!
			if($checkFolder['folders'] > 0) {
				$addFolderError = printStandardError("error_standard","Sorry, you already have a folder by that name.",0);
			}

			// good to go!
			else {
				query("INSERT INTO personal_folder (folderName,userid) VALUES ('".$addFolderName."','".$userinfo['userid']."')");

				doThanks(
					"You have successfully added the folder <strong>".$addFolderName."</strong>.",
					"Adding Folder",
					$addFolderName,
					$_SERVER['HTTP_REFERER']
				);
			}
		}

		if($_POST['normSubmit']) {
			// update
			if(is_array($_POST['updateFolderNames'])) {
				foreach($_POST['updateFolderNames'] as $fid => $newName) {
					// update
					query("UPDATE personal_folder SET folderName = '".htmlspecialchars(addslashes(trim($newName)))."' WHERE folderid = '".$fid."' AND userid = '".$userinfo['userid']."'");
				}
			}

			doThanks(
				"You have successfully updated folder names.",
				"Updating Folder Names",
				"none",
				$_SERVER['HTTP_REFERER']
			);
		}

		// delete
		if($_POST['deleteSubmit']) {
			if(is_array($_POST['deleteFolders'])) {
				foreach($_POST['deleteFolders'] as $fid => $va) {
					// delete.. but first move current messages to inbox
					query("UPDATE personal_msg SET folderid = 1 WHERE folderid = '".$fid."' AND sentTo = '".$userinfo['userid']."'");
					query("DELETE FROM personal_folder WHERE folderid = '".$fid."' AND userid = '".$userinfo['userid']."' LIMIT 1");
				}
			}

			doThanks(
				"You have sucessfully deleted the selected folders.",
				"Deleting Folder",
				"none",
				$_SERVER['HTTP_REFERER']
			);
		}
	}

	// get folders
	$folders_q = query("SELECT * FROM personal_folder WHERE personal_folder.userid = '".$userinfo['userid']."' OR personal_folder.userid = -1 ORDER BY personal_folder.userid,personal_folder.folderName ASC");
	$folders_q_copy = query("SELECT * FROM personal_folder WHERE personal_folder.userid = '".$userinfo['userid']."' OR personal_folder.userid = -1 ORDER BY personal_folder.userid,personal_folder.folderName ASC");

	// this does the counts for each folder...
	$message_count_q = query("SELECT personal_folder.* , COUNT(personal_msg.pid) AS numOfMessages FROM personal_folder LEFT JOIN personal_msg ON personal_msg.folderid = personal_folder.folderid WHERE personal_folder.userid = '".$userinfo['userid']."' OR (personal_folder.userid = -1 AND ((personal_msg.sentTo = '".$userinfo['userid']."') OR (personal_msg.sentTo = 0 AND personal_msg.userid = '".$userinfo['userid']."'))) GROUP BY personal_folder.folderid ORDER BY personal_folder.userid,personal_folder.folderName ASC");

	// form arr
	if(mysql_num_rows($message_count_q) AND mysql_num_rows($folders_q)) {
		while($theCounts = mysql_fetch_array($folders_q)) {
			$count[$theCounts['folderid']]['message_count'] = 0;
		}

		while($theCounts2 = mysql_fetch_array($message_count_q)) {
			$count[$theCounts2['folderid']]['message_count'] = $theCounts2['numOfMessages'];
		}
	}

	else {
		$count[1]['message_count'] = 0;
		$count[2]['message_count'] = 0;
	}

	if(mysql_num_rows($folders_q_copy)) {
		while($folder = mysql_fetch_array($folders_q_copy)) {
			$theFolderId = $folder['folderid'];
			$messageCount = $count[$folder['folderid']]['message_count'];

			if(!$usergroupinfo[$userinfo['usergroupid']]['personal_folders']) {
				eval("\$bits .= \"".getTemplate("personal_editFolders_permBits")."\";");
			}

			else {
				// if it's inbox or sent, we can't edit OR delete
				if($folder['folderid'] == 1 OR $folder['folderid'] == 2) {
					eval("\$bits .= \"".getTemplate("personal_editFolders_inboxSentBits")."\";");
				} else {
					eval("\$bits .= \"".getTemplate("personal_editFolders_bit")."\";");
				}
			}
		}
	}

	// add folder.. if perms
	if($usergroupinfo[$userinfo['usergroupid']]['personal_folders']) {
		eval("\$addFolder = \"".getTemplate("personal_editFolders_add")."\";");
	}

	// good to go...
	if(!$usergroupinfo[$userinfo['usergroupid']]['personal_folders']) {
		eval("\$content = \"".getTemplate("personal_editFoldersPerms")."\";");
	} else {
		eval("\$content = \"".getTemplate("personal_editFolders")."\";");
	}
}

// message rules
else if($_GET['do'] == "messageRules") {
	// perms
	if(!$usergroupinfo[$userinfo['usergroupid']]['personal_rules']) {
		doError(
			"perms",
			"Error Viewing Message Rules"
		);
	}

	if($_POST) {
		// add rule
		if($_POST['addSubmit']) {
			$FIND_userORgroup = "";
			$FIND_value = "";
			$FIND_moveORdelete = "";
			$FIND_value2 = "";

			if($_POST['userORgroup'] == "user") {
				$FIND_userORgroup = "user";
				$FIND_value = htmlspecialchars(addslashes(trim($_POST['userORgroup_text'])));
			}

			else {
				$FIND_userORgroup = "group";
				$FIND_value = $_POST['userORgroup'];
			}

			if($_POST['moveORdelete'] == "delete") {
				$FIND_moveORdelete = "delete";
				$FIND_value2 = null;
			}

			else {
				$FIND_moveORdelete = "move";
				$FIND_value2 = $_POST['moveORdelete'];
			}

			if(!$_POST['exec_order'] OR $_POST['exec_order'] < 1) {
				$exec_order = 1;
			} else {
				$exec_order = $_POST['exec_order'];
			}

			// make the insert
			query("INSERT INTO personal_rules (userid,userORgroup,value,moveORdelete,value2,exec_order) VALUES ('".$userinfo['userid']."','".$FIND_userORgroup."','".$FIND_value."','".$FIND_moveORdelete."','".$FIND_value2."','".$exec_order."')");

			doThanks(
				"You have successfully added a message rule.",
				"Adding Message Rule",
				"none",
				$_SERVER['HTTP_REFERER']
			);
		}

		if($_POST['deleteSubmit']) {
			if(is_array($_POST['deleteRules'])) {
				// loop
				foreach($_POST['deleteRules'] as $ruleid => $va) {
					// delete
					query("DELETE FROM personal_rules WHERE ruleid = '".$ruleid."' LIMIT 1");
				}

				doThanks(
					"You have successfully deleted the selected message rules.",
					"Deleting Message Rules",
					"none",
					$_SERVER['HTTP_REFERER']
				);
			}
		}

		// update rules
		if($_POST['normSubmit']) {
			if(is_array($_POST['updateCriteria']) AND is_array($_POST['updateCriteriaText']) AND is_array($_POST['updateAction']) AND is_array($_POST['updateOrder'])) {
				foreach($_POST['updateCriteria'] as $ruleid => $va) {
					$FIND_userORgroup = "";
					$FIND_value = "";
					$FIND_moveORdelete = "";
					$FIND_value2 = "";
					$FIND_order = "";

					if($_POST['updateCriteria'][$ruleid] == "user") {
						$FIND_userORgroup = "user";
						$FIND_value = htmlspecialchars(addslashes(trim($_POST['updateCriteriaText'][$ruleid])));
					}

					else {
						$FIND_userORgroup = "group";
						$FIND_value = $_POST['updateCriteria'][$ruleid];
					}

					if($_POST['updateAction'][$ruleid] == "delete") {
						$FIND_moveORdelete = "delete";
						$FIND_value2 = null;
					}

					else {
						$FIND_moveORdelete = "move";
						$FIND_value2 = $_POST['updateAction'][$ruleid];
					}

					if(!$_POST['updateOrder'][$ruleid] OR $_POST['updateOrder'][$ruleid] < 1) {
						$FIND_order = 1;
					} else {
						$FIND_order = $_POST['updateOrder'][$ruleid];
					}

					// update
					query("UPDATE personal_rules SET userORgroup = '".$FIND_userORgroup."' , value = '".$FIND_value."' , moveORdelete = '".$FIND_moveORdelete."' , exec_order = '".$FIND_order."' , value2 = '".$FIND_value2."' WHERE ruleid = '".$ruleid."' AND userid = '".$userinfo['userid']."'");
				}

				doThanks(
					"You have successfully updated your message rules.",
					"Updating Message Rules",
					"none",
					$_SERVER['HTTP_REFERER']
				);
			}
		}
	}

	// get message rules
	$messageRules = query("SELECT * FROM personal_rules WHERE userid = '".$userinfo['userid']."' ORDER BY exec_order ASC");
	$customFolders_q = query("SELECT * FROM personal_folder WHERE userid = '".$userinfo['userid']."' ORDER BY folderName");

	// create folder arr
	if(mysql_num_rows($customFolders_q)) {
		while($theFolder = mysql_fetch_array($customFolders_q)) {
			$folders[$theFolder['folderid']] = $theFolder;
		}
	}

	if(mysql_num_rows($messageRules)) {
		while($rules = mysql_fetch_array($messageRules)) {
			unset($usergroups,
				$folderList);

			$theRuleId = $rules['ruleid'];

			if($rules['userORgroup'] == "user") {
				$theUsername = htmlspecialchars($rules['value']);
				$usernameSelected = ' selected="selected"';
			} else {
				$theUsername = "";
				$usernameSelected = '';
			}

			if($rules['moveORdelete'] == "delete") {
				$deletedSelect = ' selected="selected"';
				$inboxSelect = '';
			} else {
				if($rules['value2'] == 1) {
					$inboxSelect = ' selected="selected"';
				} else {
					$inboxSelect = '';
				}

				$deletedSelect = '';
			}

			// get usergrouplist...
			$usergroups = '<option value="user"'.$usernameSelected.'>Use Username</option>';

			// loop through already made usergroup arr
			foreach($usergroupinfo as $groupid => $arr) {
				if($groupid == 1) continue;
				
				if($rules['userORgroup'] == "group" AND $rules['value'] == $groupid) {
					$isSelected = ' selected="selected"';
				} else {
					$isSelected = '';
				}

				$usergroups .= '<option value="'.$groupid.'"'.$isSelected.'>'.$arr['name'].'</option>';
			}

			// form folder bits
			$folderList = '<option value="delete"'.$deletedSelect.'>Delete</option>';
			$folderList .= '<option value="1"'.$inboxSelect.'>Move to inbox</option>';
	
			if(is_array($folders)) {
				foreach($folders as $fid => $folderinfo) {
					if(empty($inboxSelect) AND empty($deletedSelect) AND $folderinfo['folderid'] == $rules['value2']) {
						$isSelected = ' selected="selected"';
					} else {
						$isSelected = '';
					}

					$folderList .= '<option value="'.$folderinfo['folderid'].'"'.$isSelected.'>Move to '.$folderinfo['folderName'].'</option>';
				}
			}

			eval("\$bits .= \"".getTemplate("personal_rules_list_bit")."\";");
		}

		eval("\$messageRulesList = \"".getTemplate("personal_rules_list")."\";");
	}

	// get add rule...
	if(mysql_num_rows($messageRules) < $usergroupinfo[$userinfo['usergroupid']]['personal_rules']) {
		// get usergrouplist...
		$addUsergroups = '<option value="user" selected="selected">Use Username</option>';
		// loop through already made usergroup arr
		foreach($usergroupinfo as $groupid => $arr) {
			if($groupid == 1) continue;
			$addUsergroups .= '<option value="'.$groupid.'">'.$arr['name'].'</option>';
		}

		// form folder bits
		$addFolderList = '<option value="delete" selected="selected">Delete</option>';
		$addFolderList .= '<option value="1">Move to inbox</option>';
		
		if(is_array($folders)) {
			foreach($folders as $fid => $folderinfo2) {
				$addFolderList .= '<option value="'.$folderinfo2['folderid'].'">Move to '.$folderinfo2['folderName'].'</option>';
			}
		}

		eval("\$addRule = \"".getTemplate("personal_rules_add")."\";");
	}

	eval("\$content = \"".getTemplate("personal_rules")."\";");
}

// resend validation email link???
if($userinfo['usergroupid'] == 3 OR $userinfo['is_coppa']) {
	eval("\$validationLink = \"".getTemplate("usercp_resendValidationEmail")."\";");
} else {
	$validationLink = "";
}

// this will get the visual representation of how
// much room the user is taking up with their messages
// get all messages sentTo user, and sent messages
$allMessages = query("SELECT COUNT(*) AS numOfMessages FROM personal_msg WHERE sentTo = '".$userinfo['userid']."' OR (sentTo = 0 AND userid = '".$userinfo['userid']."')",1);

// avoid division by 0 :-x
if($usergroupinfo[$userinfo['usergroupid']]['personal_max_messages'] > 0) {
	$percentage = 100 / $usergroupinfo[$userinfo['usergroupid']]['personal_max_messages'];
	$percentage = $allMessages['numOfMessages'] * $percentage;
	$percentage = ceil($percentage)."%";
	$realPercent = $percentage;

	if(!$allMessages['numOfMessages']) {
		$percentage = "0px; visibility: hidden;";
	}
} else {
	$percentage = "0px; visibility: hidden;";
	$realPercent = "0%";
}

eval("\$pmPercentages = \"".getTemplate("personal_percentages")."\";");

if($usergroupinfo[$userinfo['usergroupid']]['personal_max_messages'] > 0) {
	if($usergroupinfo[$userinfo['usergroupid']]['personal_receipts']) {
		eval("\$messageReceiptsNavigation = \"".getTemplate("personal_navigation_receipts")."\";");
	} else {
		$messageReceiptsNavigation = "";
	}

	// message rules
	if($usergroupinfo[$userinfo['usergroupid']]['personal_rules'] > 0) {
		eval("\$messageRulesNavigation = \"".getTemplate("personal_navigation_rules")."\";");
	} else {
		$messageRulesNavigation = "";
	}

	eval("\$personalMessagesNavigation = \"".getTemplate("personal_navigation")."\";");
}

// get templates
eval("\$header = \"".getTemplate("header")."\";");
eval("\$usercp = \"".getTemplate("usercp")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

printTemplate($header);

if($theError) {
	printTemplate($theError);
}

printTemplate($usercp);
printTemplate($footer);

wrapUp();

?>