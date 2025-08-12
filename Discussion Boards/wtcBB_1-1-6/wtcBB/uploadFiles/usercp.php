<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //FRONT END - USER CP\\ ################# \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_bbcode.php");
include("./includes/functions_usercp.php");
include("./global.php");

// get forum home stylesheet and usercp stylesheet
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_forumhome")."\";");
eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_usercp")."\";");

// if no css file.. get internal block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

// make sure logged in...
if(!$userinfo['userid']) {
	doError(
		"perms",
		"Error Viewing UserCP"
	);
}

// if no get.. show usercp main
if(!$_GET) {
	// delete subscriptions
	if(is_array($_POST['deleteSubs'])) {
		// loop through and delete subscriptions
		foreach($_POST['deleteSubs'] as $threadid => $va) {
			// delete
			query("DELETE FROM thread_subscription WHERE threadid = '".$threadid."' AND userid = '".$userinfo['userid']."'");
		}

		doThanks(
			"You have successfully deleted the selected thread subscriptions.",
			"User CP",
			"Deleting Thread Subscriptions",
			"usercp.php"
		);
	}

	// create nav bar array
	$navbarArr = Array(
		"User Control Panel" => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// deal with sessions
	$sessionInclude = doSessions("User Control Panel","none");
	include("./includes/sessions.php");

	// get thread subscriptions...
	$threadSubscriptions = query("SELECT * FROM thread_subscription LEFT JOIN threads ON thread_subscription.threadid = threads.threadid WHERE thread_subscription.userid = '".$userinfo['userid']."' ORDER BY threads.last_reply_date DESC");

	$bits = "";

	// if rows.. get template...
	if(mysql_num_rows($threadSubscriptions)) {
		// loop through and grab template
		while($subscribe = mysql_fetch_array($threadSubscriptions)) {
			$theThreadId = $subscribe['threadid'];
			$lastPostDate = processDate($bboptions['date_formatted'],$subscribe['last_reply_date']);
			$lastPostTime = processDate($bboptions['date_time_format'],$subscribe['last_reply_date']);

			eval("\$bits .= \"".getTemplate("usercp_main_subscriptions_bit")."\";");
		}

		// grab whole template
		eval("\$subscriptions = \"".getTemplate("usercp_main_subscriptions")."\";");
	}

	else {
		eval("\$subscriptions = \"".getTemplate("usercp_main_nosubscriptions")."\";");
	}

	// get whole template
	eval("\$content = \"".getTemplate("usercp_main")."\";");
}

// edit email
else if($_GET['do'] == "email") {
	// activate?
	if($_GET['action'] == "validate") { 
		// doesn't need validation?
		if(!$userinfo['newEmail'] OR $userinfo['newEmail'] == null) {
			doError(
				"According to our records, you have not filed a request to change your email address. Please do so by <a href=\"usercp.php?do=email\">editing your email address</a>.",
				"Error Validating Email Address",
				"No Request Filed"
			);
		}

		// make sure it hasn't been 24 hours...
		else if($userinfo['newEmailDate'] < (time() - 3600)) {
			doError(
				"It has been 24 hours since you have requested to change your email address. You can request to change it again by <a href=\"usercp.php?do=email\">editing your email address</a>.",
				"Error Validating Email Address",
				"It's Been 24 Hours"
			);
		}

		// good to go
		else {
			// update
			query("UPDATE user_info SET email = newEmail , newEmail = null , newEmailDate = null WHERE userid = '".$userinfo['userid']."'");

			doThanks(
				"You have successfully validated your email address.",
				"User CP",
				"Validating Email Address",
				"usercp.php"
			);
		}
	}

	if($_POST) {
		// check email unqiuness.. if it so requested
		if($bboptions['require_unique_email']) {
			$checkEmail = query("SELECT COUNT(*) AS counting FROM user_info WHERE email = '".$_POST['newEmail']."'",1);
		}

		// error!
		if(!$_POST['newEmail'] OR !$_POST['confirmNewEmail']) {
			$theError = printStandardError("error_standard","You must fill in all fields.",0);
		}

		else if($_POST['newEmail'] != $_POST['confirmNewEmail']) {
			$theError = printStandardError("error_standard","Sorry, those email addresses do not match.",0);
		}

		else if($bboptions['require_unique_email'] AND $checkEmail['counting'] > 0) {
			$theError = printStandardError("error_standard","The administrator has required that you use an unique email address.",0);
		}

		// good to go
		else {
			if($bboptions['verify_email']) {
				// update newEmail and newEmailDate
				query("UPDATE user_info SET newEmail = '".addslashes($_POST['newEmail'])."' , newEmailDate = '".time()."' WHERE userid = '".$userinfo['userid']."'");

				// send out activation email
				$username = $userinfo['username'];
				eval("\$message = \"".getTemplate("mail_emailValidation")."\";");
				mail($_POST['newEmail'],"wtcBB Mailer - Email Validation",$message,"From: ".$bboptions['details_contact']);

				doThanks(
					"You have successfully finished your request to change your email address. An email has been sent to the new email address you specified. Further instructions on how to finished your validation process will be included in the email.",
					"User CP",
					"Editing Email Address",
					"usercp.php"
				);
			}

			// just change it.. skip validation process
			else {
				query("UPDATE user_info SET email = '".addslashes($_POST['newEmail'])."' , newEmail = null , newEmailDate = null WHERE userid = '".$userinfo['userid']."'");

				doThanks(
					"You have successfully edited your email address.",
					"User CP",
					"Editing Email Address",
					"usercp.php"
				);
			}
		}
	}	

	if($bboptions['verify_email'] == 1) {
		eval("\$activationNotice = \"".getTemplate("usercp_email_activationNotice")."\";");
	} else {
		$activationNotice = "";
	}

	// get template
	eval("\$content = \"".getTemplate("usercp_email")."\";");
}

// edit default bb code
else if($_GET['do'] == "defaultBBCode") {
	if(!$bboptions['defaultBBCode'] OR !$usergroupinfo[$userinfo['usergroupid']]['can_default_bbcode']) {
		doError(
			"perms",
			"Error Viewing Default BB Code"
		);
	}

	if($_POST) {
		query("UPDATE user_info SET default_font = '".htmlspecialchars($_POST['default_font'])."' , default_color = '".htmlspecialchars($_POST['default_color'])."' , default_size = '".htmlspecialchars($_POST['default_size'])."' , useDefault = '".htmlspecialchars($_POST['useDefault'])."' WHERE userid = '".$userinfo['userid']."'");

		doThanks(
			"You have successfully saved your default BB Code settings.",
			"User CP",
			"Editing BB Code Settings",
			"usercp.php"
		);
	}
	
	// form all our bits...
	$fontsList = preg_split('#[\s]{2,}#is',$bboptions['defaultFontsList']);
	$colorsList = preg_split('#[\s]{2,}#is',$bboptions['defaultColorsList']);
	$sizeList = preg_split('#[\s]{2,}#is',$bboptions['defaultSizeList']);

	$defaultFontBits = ''; $defaultColorBits = ''; $defaultSizeBits = '';

	// form defaultFontBits...
	if(is_array($fontsList)) {
		foreach($fontsList as $fontName) {
			if($userinfo['default_font'] == $fontName) {
				$selected = ' selected="selected"';
			}

			else {
				$selected = '';
			}

			$defaultFontBits .= '<option value="' . $fontName . '"'. $selected . '">' . $fontName . '</option>' . "\n";
		}
	}

	// form defaultFontBits...
	if(is_array($colorsList)) {
		foreach($colorsList as $colorName) {
			if($userinfo['default_color'] == $colorName) {
				$selected = ' selected="selected"';
			}

			else {
				$selected = '';
			}

			$defaultColorBits .= '<option value="' . $colorName . '"'. $selected . '" style="background: ' . $colorName . ';">&nbsp;</option>' . "\n";
		}
	}

	// form defaultFontBits...
	if(is_array($sizeList)) {
		foreach($sizeList as $sizeName) {
			if($userinfo['default_size'] == $sizeName) {
				$selected = ' selected="selected"';
			}

			else {
				$selected = '';
			}

			$defaultSizeBits .= '<option value="' . $sizeName . '"'. $selected . '">' . $sizeName . '</option>' . "\n";
		}
	}

	if($userinfo['useDefault']) {
		$useDefaultChecked = ' checked="checked"';
	}

	else {
		$useDefaultChecked = '';
	}

	// get template
	eval("\$content = \"".getTemplate("usercp_bbcode")."\";");
}

// edit password
else if($_GET['do'] == "password") {
	if($_POST) {
		$checkConfirmation = confirmLogin($userinfo['username'],md5(addslashes($_POST['currPassword'])));

		// errors
		if(!$_POST['currPassword'] OR !$_POST['newPassword'] OR !$_POST['confirmNewPassword']) {
			$theError = printStandardError("error_standard","You must fill out all fields.",0);
		}

		else if($_POST['newPassword'] != $_POST['confirmNewPassword']) {
			$theError = printStandardError("error_standard","Your new password, and your confirmed new password do not match.",0);
		}
		
		else if(!$checkConfirmation) {
			$theError = printStandardError("error_standard","The password you entered for 'Current Password' is incorrect.",0);
		}

		// good to go
		else {
			// update DB.. we've already check confirmation
			// get rid of vB salt while we're at it
			query("UPDATE user_info SET password = '".md5(addslashes($_POST['newPassword']))."' , vBsalt = null WHERE userid = '".$userinfo['userid']."'");

			doThanks(
				"You have successfully edited your password.",
				"User CP",
				"Editing Password",
				"usercp.php"
			);
		}
	}

	eval("\$content = \"".getTemplate("usercp_password")."\";");
}

// edit avatar
else if($_GET['do'] == "avatar") {
	// make sure avatars are enabled
	if(!$bboptions['avatar_enabled']) {
		doError(
			"Sorry, the administrator has disabled the use of avatars.",
			"Error Editing Avatar",
			"Disabled"
		);
	}

	// perms error
	if(!$usergroupinfo[$userinfo['usergroupid']]['can_use_avatar']) {
		doError(
			"perms",
			"Error Editing Avatar"
		);
	}

	// submit.. finally
	if($_POST) {
		// URL
		$success = false;

		if(!$_POST['theAvatar']) {
			$theError = printStandardError("error_standard","You must chose at least one option.",0);
		}

		else if($_POST['theAvatar'] == "custom") {
			// get the contents of the image, write to avatars dir...
			if(!($fileContents = @file_get_contents($_POST['theURL']))) {
				$theError = printStandardError("error_standard","You have entered a bad URL.",0);
			}

			else {
				$theFile = "avatars/avatar".$userinfo['userid'].time();
				$opener = @fopen($theFile,"wb");
				@fwrite($opener, $fileContents);
				@fclose($opener);

				// make sure we're under restraints!
				$imageinfo = @getimagesize($theFile);

				$newFileName = "";

				// get extension
				if($imageinfo[2] == 1) {
					$newFileName = $theFile.".gif";
				} else if($imageinfo[2] == 2) {
					$newFileName = $theFile.".jpg";
				} else if($imageinfo[2] == 3) {
					$newFileName = $theFile.".png";
				} else {
					$newFileName = $theFile;
				}

				// copy then unlink
				@copy($theFile,$newFileName);
				@unlink($theFile);
			}

			if($imageinfo[0] > $usergroupinfo[$userinfo['usergroupid']]['avatar_width'] AND $usergroupinfo[$userinfo['usergroupid']]['avatar_width'] AND !$theError) {
				$theError = printStandardError("error_standard","Sorry, the avatar you selected is above the width requirement of ".$usergroupinfo[$userinfo['usergroupid']]['avatar_width']." pixels.",0);
				@unlink($newFileName);
			}

			else if($imageinfo[1] > $usergroupinfo[$userinfo['usergroupid']]['avatar_height'] AND $usergroupinfo[$userinfo['usergroupid']]['avatar_height'] AND !$theError) {
				$theError = printStandardError("error_standard","Sorry, the avatar you selected is above the height requirement of ".$usergroupinfo[$userinfo['usergroupid']]['avatar_height']." pixels.",0);
				@unlink($newFileName);
			}

			// check image
			else if($imageinfo[2] != 1 AND $imageinfo[2] != 2 AND $imageinfo[2] != 3 AND !$theError) {
				$theError = printStandardError("error_standard","Sorry, you have not entered a URL to a valid image.",0);
				@unlink($newFileName);
			}

			else if(strlen($fileContents) > $usergroupinfo[$userinfo['usergroupid']]['avatar_filesize'] AND $usergroupinfo[$userinfo['usergroupid']]['avatar_filesize'] AND !$theError) {
				$theError = printStandardError("error_standard","Sorry, the avatar you selected is above the filesize requirement of ".$usergroupinfo[$userinfo['usergroupid']]['avatar_filesize']." <strong>bytes</strong>.",0);
				@unlink($newFileName);
			}

			else if(!$theError) {
				// run query
				query("UPDATE user_info SET avatar_url = '".$newFileName."' WHERE userid = '".$userinfo['userid']."'");
				$success = true;
			}
		}

		// delete! this one's easy...
		else if($_POST['theAvatar'] == "delete") {
			// we aren't deleting anything from the DB.. just updating the avatar field to "none"
			//@unlink($userinfo['avatar_url']); ack! what if the avatar is a default? can't do this
			query("UPDATE user_info SET avatar_url = 'none' WHERE userid = '".$userinfo['userid']."'");
			$success = true;
		}

		// ug.. uploading!
		else if($_POST['theAvatar'] == "upload") {
			// set some vars
			$name = $_FILES['fupload']['name'];
			$tmp_name = $_FILES['fupload']['tmp_name'];
			$mime = $_FILES['fupload']['type'];
			$size = $_FILES['fupload']['size'];

			// is it uploaded?
			if(@is_uploaded_file($tmp_name)) {
				$imageinfo = @getimagesize($tmp_name);

				if($imageinfo[0] > $usergroupinfo[$userinfo['usergroupid']]['avatar_width'] AND $usergroupinfo[$userinfo['usergroupid']]['avatar_width']) {
					$theError = printStandardError("error_standard","Sorry, the avatar you selected is above the width requirement of ".$usergroupinfo[$userinfo['usergroupid']]['avatar_width']." pixels.",0);
				}

				else if($imageinfo[1] > $usergroupinfo[$userinfo['usergroupid']]['avatar_height'] AND $usergroupinfo[$userinfo['usergroupid']]['avatar_height']) {
					$theError = printStandardError("error_standard","Sorry, the avatar you selected is above the height requirement of ".$usergroupinfo[$userinfo['usergroupid']]['avatar_height']." pixels.",0);
				}

				else if($size > $usergroupinfo[$userinfo['usergroupid']]['avatar_filesize'] AND $usergroupinfo[$userinfo['usergroupid']]['avatar_filesize']) {
					$theError = printStandardError("error_standard","Sorry, the avatar you selected is above the filesize requirement of ".$usergroupinfo[$userinfo['usergroupid']]['avatar_filesize']." <strong>bytes</strong>.",0);
				}

				// not an image...
				else if(!preg_match("|^image|",$mime,$arr3) OR !is_array($imageinfo)) {
					$theError = printStandardError("error_standard","Sorry, your avatar must be a valid image.",0);
				}

				// yay!
				else {
					// first we need to move the file.. if it fails we kill the script, and the database isn't touched :)
					$checking_upload = @move_uploaded_file($tmp_name,"avatars/".$name);

					query("UPDATE user_info SET avatar_url = 'avatars/".$name."' WHERE userid = '".$userinfo['userid']."'");
					$success = true;
				}
			}
		}

		// otherwise it's an avatar that is in the DB
		else { // easy enough...
			query("UPDATE user_info SET avatar_url = '".addslashes($avatarinfo[$_POST['theAvatar']]['filepath'])."' WHERE userid = '".$userinfo['userid']."'");
			$success = true;
		}

		// if success go ahead
		if($success) {
			doThanks(
				"You have successfully updated your avatar.",
				"User CP",
				"Updating Avatar",
				"usercp.php"
			);
		}
	}

	// if we have pre-defined avatars
	if(is_array($avatarinfo)) {
		if(!$_GET['page']) {
			$page = 1;
		} else {
			$page = $_GET['page'];
		}

		$end = $bboptions['avatars_per_page'];

		// get start before REAL end
		$start = ($page - 1) * $end;

		// real end...
		$end *= $page;

		// start the limit counter
		$limitCounter = 0;

		$colspan = $bboptions['avartar_display_width'];

		$numOfAvatars = count($avatarinfo);

		if($numOfAvatars % $bboptions['avatars_per_page'] != 0) {
			$totalPages = ($numOfAvatars / $bboptions['avatars_per_page']) + 1;
			settype($totalPages,"integer");
		} else {
			$totalPages = $numOfAvatars / $bboptions['avatars_per_page'];
		}

		// build the page links...
		$pagelinks = buildPageLinks($totalPages,$page);

		$avatarbits = '';

		// loop through avatars
		foreach($avatarinfo as $avatarid => $arr) {
			// first we must do the limit counter.. 
			// make sure we're supposed to be showing this thread...
			$limitCounter++;
			
			if($limitCounter <= $start) {
				// we could still have threads to show so press on!
				continue;
			}

			if($limitCounter > $end) {
				// umm.. no where else to go.. so end
				break;
			}

			if($limitCounter < $bboptions['avatars_per_page'] AND $limitCounter == $numOfAvatars AND $limitCounter % $bboptions['avartar_display_width'] != 0) {
				$extraColspan = ' colspan="'.(($limitCounter % $bboptions['avartar_display_width'] == 1) ? (2) : ($limitCounter % $bboptions['avartar_display_width'])).'"';
			} else {
				$extraColspan = '';
			}

			// get template
			eval("\$avatarbits .= \"".getTemplate("usercp_avatars_preDefined_bits")."\";");

			// get divider?
			if(!($limitCounter % $bboptions['avartar_display_width'])) {
				eval("\$avatarbits .= \"".getTemplate("usercp_avatars_preDefined_bits_divider")."\";");
			}
		}

		eval("\$preDefined = \"".getTemplate("usercp_avatars_preDefined")."\";");
	}

	// current avatar?
	if($userinfo['avatar_url'] != "none") {
		eval("\$currentAvatar = \"".getTemplate("usercp_avatars_current")."\";");
	} else {
		$currentAvatar = '';
	}

	// can upload?
	if($usergroupinfo[$userinfo['usergroupid']]['can_upload_avatar']) {
		eval("\$uploadAvatar = \"".getTemplate("usercp_avatars_custom_upload")."\";");
	} else {
		$uploadAvatar = '';
	}

	eval("\$customAvatar = \"".getTemplate("usercp_avatars_custom")."\";");

	eval("\$content = \"".getTemplate("usercp_avatars")."\";");
}

// edit signature
else if($_GET['do'] == "signature") {
	// make sure of perms
	if(!$usergroupinfo[$userinfo['usergroupid']]['can_sig'] AND !$userinfo['ban_sig'] AND $bboptions['allow_signatures']) {
		doError(
			"perms",
			"Error Editing Signature"
		);
	}

	include("./includes/functions_messages.php");

	if($_POST) {
		if(strlen($_POST['postMessage']) > $bboptions['maximum_signature'] AND $bboptions['maximum_signature']) {
			$theError = printStandardError("error_standard","Sorry, you are over the signature maximum character limit.",0);
		}

		else {
			// so... preview or not?
			if($_POST['preview']) {
				// make a copy of $postMessage to use in preiview... and thread title
				$postMessageCopy = $_POST['postMessage'];

				$postMessageCopy = parseMessage($postMessageCopy,$bboptions['allow_wtcBB_sig'],$bboptions['allow_smilies_sig'],$bboptions['allow_img_sig'],(!$bboptions['allow_html_sig'] AND !$userinfo['allow_html']),true,true,$userinfo['username']);

				eval("\$possiblePreview = \"".getTemplate("usercp_signature_preview")."\";");
			}

			// insert
			else {
				// update DB...
				query("UPDATE user_info SET signature = '".addslashes($_POST['postMessage'])."' WHERE userid = '".$userinfo['userid']."'");

				doThanks(
					"You have successfully updated your signature.",
					"User CP",
					"Updating Signature",
					"usercp.php"
				);
			}
		}
	}

	// get all the posting rules...
	if($bboptions['allow_wtcBB_sig']) {
		$wtcBBcode = "may";
	} else {
		$wtcBBcode = "may not";
	}

	if($bboptions['allow_smilies_sig']) {
		$wtcBBsmilies = "may";
	} else {
		$wtcBBsmilies = "may not";
	}

	if($bboptions['allow_img_sig']) {
		$wtcBBimg = "may";
	} else {
		$wtcBBimg = "may not";
	}

	if($bboptions['allow_html_sig'] OR $userinfo['allow_html']) {
		$wtcBBhtml = "may";
	} else {
		$wtcBBhtml = "may not";
	}

	// make sure they want smilies...
	if($bboptions['clickable_smilies_total'] > 0 AND $bboptions['allow_smilies_sig']) {
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

	if(!$_POST AND $userinfo['signature']) {
		// make a copy of $postMessage to use in preiview... and thread title
		$postMessageCopy = $userinfo['signature'];

		$postMessageCopy = parseMessage($postMessageCopy,$bboptions['allow_wtcBB_sig'],$bboptions['allow_smilies_sig'],$bboptions['allow_img_sig'],(!$bboptions['allow_html_sig'] AND !$userinfo['allow_html']),true,true,$userinfo['username']);

		eval("\$possiblePreview = \"".getTemplate("usercp_signature_preview")."\";");

		$postMessage = htmlspecialchars($userinfo['signature']);
	} else {
		$postMessage = htmlspecialchars(addslashes($_POST['postMessage']));
	}

	// get template
	eval("\$content = \"".getTemplate("usercp_signature")."\";");
}

// edit preferences
else if($_GET['do'] == "preferences") {
	// update information
	if($_POST) {
		if($_POST['enableDST']) {
			$dst = 1;
		} else {
			$dst = 0;
		}

		if($_POST['receiveAdminEmails']) {
			$admin_send_email = 1;
		} else {
			$admin_send_email = 0;
		}

		if($_POST['receiveUserEmails']) {
			$receive_emails = 1;
		} else {
			$receive_emails = 0;
		}

		if($_POST['receivePersonalMessages']) {
			$use_pm = 1;
		} else {
			$use_pm = 0;
		}

		if($_POST['receivePMNotifications']) {
			$send_email_pm = 1;
		} else {
			$send_email_pm = 0;
		}

		if($_POST['popupPMNotification']) {
			$popup_pm = 1;
		} else {
			$popup_pm = 0;
		}

		if($_POST['viewSignature']) {
			$view_signature = 1;
		} else {
			$view_signature = 0;
		}

		if($_POST['viewAvatar']) {
			$view_avatar = 1;
		} else {
			$view_avatar = 0;
		}

		if($_POST['viewAttachments']) {
			$view_attachment = 1;
		} else {
			$view_attachment = 0;
		}

		if($_POST['enableToolbar']) {
			$toolbar = 1;
		} else {
			$toolbar = 0;
		}

		if($_POST['autoSubscription']) {
			$auto_threadsubscription = 1;
		} else {
			$auto_threadsubscription = 0;
		}

		if($_POST['invisibility'] AND $usergroupinfo[$userinfo['usergroupid']]['can_invisible']) {
			$invisible = 1;
		} else {
			$invisible = 0;
		}

		if($bboptions['allow_change_styles']) {
			$theStyleID = $_POST['theStyle'];
		} else {
			$theStyleID = $userinfo['original_style'];
		}

		// form query...
		$updatePreferences = "UPDATE user_info SET dst = '".$dst."' , admin_send_email = '".$admin_send_email."' , receive_emails = '".$receive_emails."' , use_pm = '".$use_pm."' , send_email_pm = '".$send_email_pm."' , popup_pm = '".$popup_pm."' , view_signature = '".$view_signature."' , view_avatar = '".$view_avatar."' , view_attachment = '".$view_attachment."' , toolbar = '".$toolbar."' , auto_threadsubscription = '".$auto_threadsubscription."' , invisible = '".$invisible."' , view_posts = '".$_POST['viewPosts']."' , date_default_thread_age = '".$_POST['defaultThreadAge']."' , date_timezone = '".$_POST['gmtOffset']."' , display_order = '".$_POST['postOrder']."' , style_id = '".$theStyleID."' , enableGuestbook = '".$_POST['enableGuestbook']."' WHERE userid = '".$userinfo['userid']."'";

		query($updatePreferences);

		doThanks(
			"You have successfully updated your user preferences.",
			"User CP",
			"Updating Preferences",
			"usercp.php"
		);
	}

	// create nav bar array
	$navbarArr = Array(
		"User Control Panel" => "usercp.php",
		"Updating User Preferences" => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// deal with sessions
	$sessionInclude = doSessions("User Control Panel","none");
	include("./includes/sessions.php");
		
	// generate GMT 
	$gmDate0 = gmdate("h");
	$minutes = gmdate(":i");

	for($x = -12; $x <= 12; $x++) {
		$theTime = $gmDate0 + $x;

		if($x == 0) {
			$x = "";
		}

		if($theTime > 12) {
			$theTime -= 12;
		} else if($theTime < 1) {
			$theTime += 12;
		}

		if($userinfo['date_timezone'] == $x) {
			$selected = ' selected="selected"';
		} else {
			$selected = "";
		}

		// plus sign?
		if($x > 0) {
			$plusSign = "+";
		} else {
			$plusSign = '';
		}

		$optionBits .= '<option value="'.$x.'"'.$selected.'>GMT '.$plusSign.$x.' ('.$theTime.$minutes.')</option>\n';
	}

	// split the user settable posts per page...
	$option_select = split(",",$bboptions['user_set_max_posts']);

	// default?
	if($userinfo['view_posts'] == -1) {
		$defaultSelect = ' selected="selected"';
	} else {
		$defaultSelect = '';
	}

	// default option... and "selected"
	$postsAgeBits = "<option value=\"-1\"".$defaultSelect.">Use Forum Default</option>\n";

	foreach($option_select as $option_key => $option_value) {
		if($userinfo['view_posts'] == $option_value) {
			$selected = ' selected="selected"';
		} else {
			$selected = '';
		}

		$postsAgeBits .= "<option value=\"".$option_value."\"".$selected.">".$option_value."</option>\n";
	}

	// do threadage select
	$threadAge0 = '';
	$threadAge1 = '';
	$threadAge2 = '';
	$threadAge3 = '';
	$threadAge4 = '';
	$threadAge5 = '';
	$threadAge6 = '';
	$threadAge7 = '';
	$threadAge8 = '';
	$threadAge9 = '';
	$threadAge10 = '';
	$threadAge11 = '';
	$threadAge12 = '';

	if($userinfo['date_default_thread_age'] == -1) {
		$threadAge0 = ' selected="selected"';
	} else if($userinfo['date_default_thread_age'] == 1) {
		$threadAge1 = ' selected="selected"';
	} else if($userinfo['date_default_thread_age'] == 2) {
		$threadAge2 = ' selected="selected"';
	} else if($userinfo['date_default_thread_age'] == 3) {
		$threadAge3 = ' selected="selected"';
	} else if($userinfo['date_default_thread_age'] == 4) {
		$threadAge4 = ' selected="selected"';
	} else if($userinfo['date_default_thread_age'] == 5) {
		$threadAge5 = ' selected="selected"';
	} else if($userinfo['date_default_thread_age'] == 6) {
		$threadAge6 = ' selected="selected"';
	} else if($userinfo['date_default_thread_age'] == 7) {
		$threadAge7 = ' selected="selected"';
	} else if($userinfo['date_default_thread_age'] == 8) {
		$threadAge8 = ' selected="selected"';
	} else if($userinfo['date_default_thread_age'] == 9) {
		$threadAge9 = ' selected="selected"';
	} else if($userinfo['date_default_thread_age'] == 10) {
		$threadAge10 = ' selected="selected"';
	} else if($userinfo['date_default_thread_age'] == 11) {
		$threadAge11 = ' selected="selected"';
	} else if($userinfo['date_default_thread_age'] == 12) {
		$threadAge12 = ' selected="selected"';
	}

	// dst
	if($userinfo['dst']) {
		$enableDSTChecked = ' checked="checked"';
	} else {
		$enableDSTChecked = '';
	}

	// receive admin emails
	if($userinfo['admin_send_email']) {
		$receiveAdminEmailsChecked = ' checked="checked"';
	} else {
		$receiveAdminEmailsChecked = '';
	}

	// receive user emails
	if($userinfo['receive_emails']) {
		$receiveUserEmailsChecked = ' checked="checked"';
	} else {
		$receiveUserEmailsChecked = '';
	}

	// receive PM's
	if($userinfo['use_pm']) {
		$receviePersonalMessagesChecked = ' checked="checked"';
	} else {
		$receviePersonalMessagesChecked = '';
	}

	// enable guestbook
	if($userinfo['enableGuestbook']) {
		$enableGuestbookChecked = ' checked="checked"';
	} else {
		$enableGuestbookChecked = '';
	}

	// receive PM notification
	if($userinfo['send_email_pm']) {
		$receviePMNotificationsChecked = ' checked="checked"';
	} else {
		$receviePMNotificationsChecked = '';
	}

	// receive popup notification
	if($userinfo['popup_pm']) {
		$popupPMNotificationChecked = ' checked="checked"';
	} else {
		$popupPMNotificationChecked = '';
	}

	// view sig
	if($userinfo['view_signature']) {
		$viewSignatureChecked = ' checked="checked"';
	} else {
		$viewSignatureChecked = '';
	}

	// view avatar
	if($userinfo['view_avatar']) {
		$viewAvatarChecked = ' checked="checked"';
	} else {
		$viewAvatarChecked = '';
	}

	// view attachment
	if($userinfo['view_attachment']) {
		$viewAttachmentsChecked = ' checked="checked"';
	} else {
		$viewAttachmentsChecked = '';
	}

	// enable toolbar
	if($userinfo['toolbar']) {
		$enableToolbarChecked = ' checked="checked"';
	} else {
		$enableToolbarChecked = '';
	}

	// auto thread subscription
	if($userinfo['auto_threadsubscription']) {
		$autoSubscriptionChecked = ' checked="checked"';
	} else {
		$autoSubscriptionChecked = '';
	}

	$postOrderSelect1 = '';
	$postOrderSelect2 = '';

	// post display order
	if($userinfo['display_order'] == "ASC") {
		$postOrderSelect1 = ' selected="selected"';
	} else {
		$postOrderSelect2 = ' selected="selected"';
	}

	// get styles...
	if($bboptions['allow_change_styles']) {
		if(!$userinfo['original_style']) {
			$defaultSelected = ' selected="selected"';
		} else {
			$defaultSelected = '';
		}

		$styleBits = '<option value="0"'.$defaultSelected.'>Use Forum Default</option>';

		// get *selectable* styles...
		$selectableStyles = query("SELECT * FROM styles WHERE user_selection = 1 ORDER BY display_order ASC");

		// if rows, proceed
		if(mysql_num_rows($selectableStyles)) {
			while($styleinfo2 = mysql_fetch_array($selectableStyles)) {
				if(!$styleinfo2['enabled'] AND !$usergroupinfo[$userinfo['usergroupid']]['is_admin']) {
					continue;
				}
				
				if($userinfo['original_style'] == $styleinfo2['styleid']) {
					$isSelected = ' selected="selected"';
				} else {
					$isSelected = '';
				}

				$styleBits .= '<option value="'.$styleinfo2['styleid'].'"'.$isSelected.'>'.$styleinfo2['title'].'</option>';
			}

			// fetch pick style template
			eval("\$pickStyle = \"".getTemplate("usercp_preferences_style")."\";");
		}

		else {
			$pickStyle = '';
		}
	}

	else {
		$pickStyle = '';
	}

	// get invisible?
	if($usergroupinfo[$userinfo['usergroupid']]['can_invisible']) {
		if($userinfo['invisible']) {
			$invisibilityChecked = ' checked="checked"';
		} else {
			$invisibilityChecked = '';
		}

		eval("\$beInvisible = \"".getTemplate("usercp_preferences_invisible")."\";");
	} else {
		$beInvisible = '';
	}

	// preferences
	eval("\$content = \"".getTemplate("usercp_preferences")."\";");
}

// edit profile
else if($_GET['do'] == "profile") {
	// make sure user can edit profile...
	if(!$usergroupinfo[$userinfo['usergroupid']]['edit_own_profile']) {
		doError(
			"perms",
			"Error Editing Profile"
		);
	}

	// if submit
	if($_POST) {
		$_POST = array_map("addslashes",$_POST);
		$_POST = array_map("htmlspecialchars",$_POST);

		// do errors first... basically just usertitle
		$error = processUserTitle($_POST['usertitle'],$userinfo['userid']);

		if(gettype($error) != "boolean" AND canUserTitle($userinfo) AND $_POST['usertitle']) {
			$theError = printStandardError("error_standard",$error,0);
		}

		// all set...
		else {
			// get birthday...
			if($_POST['month'] AND $_POST['day']) {
				if(!$_POST['year']) {
					$_POST['year'] = "0000";
				}

				// just make sure nothing is above 31... no biggy
				if($_POST['day'] > 31) {
					$_POST['day'] = 31;
				}

				$birthday = $_POST['month']."-".$_POST['day']."-".$_POST['year'];
			}

			else {
				$birthday = "";
			}

			// lets get the usertitle...
			if($_POST['resetTitle'] AND canUserTitle($userinfo)) {
				query("UPDATE user_info SET usertitle_option = 0 WHERE userid = '".$userinfo['userid']."'");
				
				if($usergroupinfo[$userinfo['usergroupid']]['usertitle']) {
					$theUserTitle = $usergroupinfo[$userinfo['usergroupid']]['usertitle'];
				}

				// otherwise.. we must get the user title from the usertitles
				else {
					if(is_array($usertitles)) {
						// loop through user titles
						foreach($usertitles as $counter => $arr) {
							// make sure we have the next counter
							// if not.. simply give the usertitle the current.. there's nothin left!
							if(!is_array($usertitles[($counter + 1)])) {
								$theUserTitle = $arr['title'];
								break;
							}

							// now we compare...
							if($userinfo['posts'] >= $arr['minimumposts'] AND $userinfo['posts'] < $usertitles[($counter + 1)]['minimumposts']) {
								// yay!
								$theUserTitle = $arr['title'];
								break;
							}
						}
					}
				}

				// if it's still empty... set to "Registered Member".. since we have to be registered in order to be here
				if(!$theUserTitle) {
					$theUserTitle = "Registered Member";
				}
			}

			// use the usertitle given.. HTML disallowed
			else if($_POST['usertitle'] AND canUserTitle($userinfo)) {
				// use the title given...
				$theUserTitle = $_POST['usertitle'];
				query("UPDATE user_info SET usertitle_option = 2 WHERE userid = '".$userinfo['userid']."'");
			}

			// other wise set it to what it is now...
			else {
				$theUserTitle = $userinfo['usertitle'];
			}

			// censor the usertitle...
			$theUserTitle = doCensors($theUserTitle);

			// form query (addslashes are added above)
			$updateUserInfo = "UPDATE user_info SET locationUser = '".$_POST['location']."' , occupation = '".$_POST['occupation']."' , biography = '".$_POST['biography']."' , homepage = '".$_POST['homepage']."' , interests = '".$_POST['interests']."' , aim = '".$_POST['aim']."' , msn = '".$_POST['msn']."' , icq = '".$_POST['icq']."' , yahoo = '".$_POST['yahoo']."' , birthday = '".$birthday."' , usertitle = '".$theUserTitle."' WHERE userid = '".$userinfo['userid']."'";

			// run query and redirect
			query($updateUserInfo);

			doThanks(
				"You have successfully updated your profile.",
				"User CP",
				"Updating Profile",
				"usercp.php"
			);
		}
	}

	// create nav bar array
	$navbarArr = Array(
		"User Control Panel" => "usercp.php",
		"Editing Profile" => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// deal with sessions
	$sessionInclude = doSessions("User Control Panel","none");
	include("./includes/sessions.php");

	// should we show CT field?
	if(canUserTitle($userinfo)) {
		eval("\$userCT = \"".getTemplate("usercp_profile_usertitle")."\";");
	} else {
		$userCT = "";
	}

	$bday0 = '';
	$bday1 = '';
	$bday2 = '';
	$bday3 = '';
	$bday4 = '';
	$bday5 = '';
	$bday6 = '';
	$bday7 = '';
	$bday8 = '';
	$bday9 = '';
	$bday10 = '';
	$bday11 = '';
	$bday12 = '';

	// separate the birthday.. this is unique..
	if($userinfo['birthday']) {
		$birthday = explode("-",$userinfo['birthday']);
		$month = $birthday[0];
		$day = $birthday[1];
		$year = $birthday[2];
	}

	else {
		$bday0 = ' selected="selected"';
	}

	// get month selection...
	if($month == 1) {
		$bday1 = ' selected="selected"';
	} else if($month == 2) {
		$bday2 = ' selected="selected"';
	} else if($month == 3) {
		$bday3 = ' selected="selected"';
	} else if($month == 4) {
		$bday4 = ' selected="selected"';
	} else if($month == 5) {
		$bday5 = ' selected="selected"';
	} else if($month == 6) {
		$bday6 = ' selected="selected"';
	} else if($month == 7) {
		$bday7 = ' selected="selected"';
	} else if($month == 8) {
		$bday8 = ' selected="selected"';
	} else if($month == 9) {
		$bday9 = ' selected="selected"';
	} else if($month == 10) {
		$bday10 = ' selected="selected"';
	} else if($month == 11) {
		$bday11 = ' selected="selected"';
	} else if($month == 12) {
		$bday12 = ' selected="selected"';
	}

	if($year == "0000") {
		$year = "";
	}

	// get template
	eval("\$content = \"".getTemplate("usercp_profile")."\";");
}

else if($_GET['do'] == "resend") {
	// make sure we aren't already activated...
	if($userinfo['is_coppa'] != 1 AND $userinfo['usergroupid'] != 3) {
		doError(
			"Sorry, it appears that your account is already activated.",
			"Sending Activation Email",
			"Already Activated"
		);
	}

	// resend validation email...
	// get hash
	$useridHash = $userinfo['useridHash'];

	// if coppa send to parent...
	if($userinfo['is_coppa']) {
		eval("\$message = \"".getTemplate("mail_coppaActivation")."\";");
		mail($userinfo['parent_email'],"wtcBB Mailer - Parent Consent Email",$message,"From: ".$bboptions['details_contact']);
	}

	// normal activation
	else {
		eval("\$message = \"".getTemplate("mail_activation")."\";");
		mail($userinfo['email'],"wtcBB Mailer - Activation Email",$message,"From: ".$bboptions['details_contact']);
	}

	doThanks(
		"You have successfully resent your validation email. Please allow it time to reach your inbox.",
		"User CP",
		"Resending Validation Email"
	);
}

// resend validation email link???
if($userinfo['usergroupid'] == 3 OR $userinfo['is_coppa']) {
	eval("\$validationLink = \"".getTemplate("usercp_resendValidationEmail")."\";");
} else {
	$validationLink = "";
}

if($usergroupinfo[$userinfo['usergroupid']]['personal_max_messages'] > 0 AND $bboptions['personal_enabled']) {
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