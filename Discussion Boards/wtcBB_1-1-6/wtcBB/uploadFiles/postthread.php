<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############### //FRONT END - POST THREAD\\ ############### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_forums.php");
include("./includes/functions_messages.php");
include("./includes/functions_bbcode.php");
include("./global.php");

// we need to define a TIME for this file
// if we don't, it could result in attachments not working
define(NOW,time());

if(!$_REQUEST['hash']) {
	$theAttachmentHash = md5(NOW.$userinfo['userid'].$userinfo['username']);
} else {
	$theAttachmentHash = $_REQUEST['hash'];
}

$forumid = $_GET['f'];

// get message stylesheet
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_forumhome")."\";");
eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_messages")."\";");

// if no css file.. get internetl block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

check_wtcBB_forumPassword($forumid);

// yikes! no forum exists!
if(!is_array($foruminfo[$forumid]) OR !isActive($forumid) OR !$foruminfo[$forumid]['is_open']) {
	doError(
		"Either no forum was found in the database with the corresponding link, this forum is not active, or this forum is not open to new posts.",
		"Error Posting Thread",
		"Forum Doesn't Exist or Isn't Active"
	);
}

// check permissions...
if($userinfo['is_coppa'] OR !$forumPerms[$forumid]['can_view_board'] OR !$forumPerms[$forumid]['can_view_threads'] OR !$forumPerms[$forumid]['can_post_threads']) {
	doError(
		"perms",
		"Error Posting Thread"
	);
}

// check to make sure we aren't in a category...
if($foruminfo[$forumid]['is_category']) {
	doError(
		"You cannot make threads or posts in categories.",
		"Error Posting Thread",
		"Forum is a Category"
	);
}

// get moderator options...
$moderator = hasModPermissions($forumid);

$theError = false;

// we're in preview mode... then ummm... set up preview
if($_POST['postMessage']) {
	// get the post icon...
	if($_POST['postIcon']) {
		$postIconImage = "<img src=\"".$postIconInfo[$_POST['postIcon']]['filepath']."\" alt=\"".$postIconInfo[$_POST['postIcon']]['title']."\" />";
	} else {
		$postIconImage = "";
	}

	$threadTitle = $_POST['threadTitle'];
	$postMessage = $_POST['postMessage'];

	// count the number of images...
	$numOfImages = countImages($postMessage);

	// images error
	if($numOfImages > $bboptions['maximum_images']) {
		$theError = printStandardError("error_standard","Sorry, you have too many images in your post.",0);
	}

	// flood check...
	else if($forumPerms[$forumid]['flood_immunity'] == 0 AND (time() - $bboptions['floodcheck']) < $userinfo['lastpost'] AND $userinfo['userid'] != 0 AND !$_POST['preview']) {
		$theError = printStandardError("error_standard","The administrator has specified you may only make a new reply every ".$bboptions['floodcheck']." seconds.",0);
	}

	// thread title empty.. tsk tsk
	else if(empty($threadTitle)) {
		$theError = printStandardError("error_standard","You must have a title for your thread.",0);
	}

	else if(strlen($postMessage) < $bboptions['minimum_chars_post']) {
		$theError = printStandardError("error_standard","Sorry, your post is under the minimum character count.",0);
	}

	else if(strlen($postMessage) > $bboptions['maximum_chars_post']) {
		$theError = printStandardError("error_standard","Sorry, your post is over the maximum character count.",0);
	}

	else {
		// ok.. all errors effecting preview AND db inserts should have been processed
		// so... preview or not?
		if($_POST['preview']) {
			$postMessageCopy = parseMessage($postMessage,$_POST['parseBBcode'],$_POST['parseSmilies'],$foruminfo[$forumid]['allow_img'],(!$foruminfo[$forumid]['allow_html'] AND !$userinfo['allow_html']),$foruminfo[$forumid]['allow_wtcBB'],$foruminfo[$forumid]['allow_smilies'],$userinfo['username'],$userinfo,$_POST['defaultBBCode']);
			$threadTitleCopy = replaceReplacements(doCensors(htmlspecialchars($threadTitle)));

			eval("\$possiblePreview = \"".getTemplate("message_preview")."\";");
		}

		// now we're actually going to insert it into the DB...
		else {
			// we have to get the close and sticky.. just in case someone tries to cheat!
			if($moderator) {
				// close thread?
				if($_POST['openClose'] AND ($modinfo[$forumid][$moderator]['can_openClose_threads'] OR $moderator === true)) {
					$doubleCheckClose = 1;
				} else {
					$doubleCheckClose = 0;
				}

				// since it didn't return false... then we have some sort of mod
				// and all mods can make threads sticky, so nothin else...
				if($_POST['makeStick']) {
					$doubleCheckSticky = 1;
				} else {
					$doubleCheckSticky = 0;
				}
			} else {
				// just set both to 0...
				$doubleCheckClose = 0;
				$doubleCheckSticky = 0;
			}

			if($doubleCheckClose) {
				doModLog("Opened/Closed Thread: ".$threadTitle);
			}

			if($doubleCheckSticky) {
				doModLog("Stuck/Unstuck Thread: ".$threadTitle);
			}

			// insert thread...
			$insertThread = query("INSERT INTO threads (forumid,thread_name,threadUsername,thread_starter,thread_views,thread_replies,last_reply_username,last_reply_userid,last_reply_date,post_icon_thread,closed,sticky,deleted_thread,date_made,poll) VALUES ('".$forumid."','".addslashes($threadTitle)."','".$userinfo['username']."','".$userinfo['userid']."','0','0','".$userinfo['username']."','".$userinfo['userid']."','".time()."','".addslashes($postIconImage)."','".$doubleCheckClose."','".$doubleCheckSticky."','0','".time()."','0')");

			// get the id...
			$insertThreadID = mysql_insert_id();

			// should we log IP or not?
			if($bboptions['logip']) {
				$getIP = $_SERVER['REMOTE_ADDR'];
			} else {
				$getIP = null;
			}

			// now insert the post...
			$insertPost = query("INSERT INTO posts (threadid,userid,postUsername,message,title,ip_address,date_posted,deleted,forumid,post_icon,show_sig,parse_smilies,parse_bbcode,defBBCode) VALUES ('".$insertThreadID."','".$userinfo['userid']."','".addslashes($userinfo['username'])."','".addslashes($postMessage)."','".addslashes($threadTitle)."','".$getIP."','".time()."','0','".$forumid."','".addslashes($postIconImage)."','".$_POST['showSig']."','".$_POST['parseSmilies']."','".$_POST['parseBBcode']."','".$_POST['defaultBBCode']."')");

			$insertPostID = mysql_insert_id();

			// now we need to update the thread we just made
			// with the first post
			$updateThread = query("UPDATE threads SET first_post = '".$insertPostID."' , last_reply_postid = '".$insertPostID."' , last_reply_username = '".addslashes($userinfo['username'])."' , last_reply_userid = '".$userinfo['userid']."' , last_reply_date = '".time()."' WHERE threadid = '".$insertThreadID."'");

			// update this forum with last post information..
			$updateForum = query("UPDATE forums SET posts = posts + 1 , threads = threads + 1 , last_reply_username = '".addslashes($userinfo['username'])."' , last_reply_userid = '".$userinfo['userid']."' , last_reply_date = '".time()."' , last_reply_threadid = '".$insertThreadID."' , last_reply_threadtitle = '".addslashes($threadTitle)."' WHERE forumid = '".$forumid."'");

			// if forum counts posts.. then update posts and threads...
			if($foruminfo[$forumid]['count_posts'] AND $userinfo['userid']) {
				query("UPDATE user_info SET threads = threads + 1 , posts = posts + 1 , lastpost = '".time()."' , lastpostid = '".$insertPostID."' WHERE userid = '".$userinfo['userid']."'");
			}

			else if($userinfo['userid']) {
				query("UPDATE user_info SET lastpost = '".time()."' , lastpostid = '".$insertPostID."' WHERE userid = '".$userinfo['userid']."'");
			}

			// do we want to subscribe?... and only if we aren't subscribed already
			if($theSubscribe) {
				// insert subscription
				$insertSubscription = query("INSERT INTO thread_subscription (userid,threadid) VALUES ('".$userinfo['userid']."','".$insertThreadID."')");
			}

			// if attachments, add postids and crap
			$findAttachments = query("UPDATE attachments SET attachmentthread = '".$insertThreadID."' , attachmentpost = '".$insertPostID."' WHERE attachmenthash = '".$theAttachmentHash."'");

			// do thread subscriptions...
			// only if its enabled globally...
			if($bboptions['enable_email'] AND is_array($modinfo[$forumid])) {
				// email mods?
				foreach($modinfo[$forumid] as $moderatorid => $arr) {
					if($arr['receive_email_post'] OR $arr['receive_email_thread']) {
						$username = $arr['username'];
						$thread_title = $threadTitle;
						$forum_title = $foruminfo[$forumid]['forum_name'];
						eval("\$message = \"".getTemplate("mail_subscription_newThread")."\";");
						mail($arr['email'],"wtcBB Mailer - Thread Subscription Notification",$message,"From: ".$bboptions['details_contact']."\r\nReply-To: ".$bboptions['details_contact']);
					}
				}
			}

			// all done... i think.. redirect for thank you page...
			// our redirection depends on if we opted to make a poll or not..
			if($_POST['usePoll'] AND $usergroupinfo[$userinfo['usergroupid']]['can_post_polls'] AND $forumPerms[$forumid]['can_post_polls']) {
				$newURI = "makepoll.php?f=".$forumid."&t=".$insertThreadID."&numOpt=".$_GET['numOfPollOptions'];
			} else {
				$newURI = "thread.php?t=".$insertThreadID;
			}

			doThanks(
				"Your thread has successfully been processed. If you chose to make a poll, you will be redirected to the poll options. If not, you will be redirected to your newly made thread.",
				"Posting a Thread",
				"none",
				$newURI
			);
		}
	}

	$postMessage = htmlspecialchars(addslashes($postMessage));
}

// create nav bar array...
$navbarArr = getForumNav($forumid);

// reverse it... if array exists
if(is_array($navbarArr)) {
	$navbarArr = array_reverse($navbarArr);
}

// add to it...
$navbarArr[$foruminfo[$forumid]['forum_name']] = "forum.php?f=".$forumid;
$navbarArr['Post Thread'] = "#";

$navbarText = getNavbarLinks($navbarArr);

// deal with sessions
$sessionInclude = doSessions("Posting a Thread","none");
include("./includes/sessions.php");

// open or close?
if($threadinfo['closed']) {
	$openClose = "Open";
} else {
	$openClose = "Close";
}

// stick or unstick?
if($threadinfo['sticky']) {
	$stickUnstick = "Unstick";
} else {
	$stickUnstick = "Stick";
}

// get all the posting rules...
if($foruminfo[$forumid]['allow_wtcBB']) {
	$wtcBBcode = "may";
} else {
	$wtcBBcode = "may not";
}

if($foruminfo[$forumid]['allow_smilies']) {
	$wtcBBsmilies = "may";
} else {
	$wtcBBsmilies = "may not";
}

if($foruminfo[$forumid]['allow_img']) {
	$wtcBBimg = "may";
} else {
	$wtcBBimg = "may not";
}

if($foruminfo[$forumid]['allow_html'] OR $userinfo['allow_html']) {
	$wtcBBhtml = "may";
} else {
	$wtcBBhtml = "may not";
}

if(!$_POST['postIcon']) {
	$checked2 = " checked=\"checked\"";
} else {
	$checked2 = "";
}

// check for checked
if($_POST['showSig'] OR !$_POST) {
	$sigChecked = ' checked="checked"';
} else {
	$sigChecked = "";
}

if($_POST['parseSmilies'] OR !$_POST) {
	$smileyChecked = ' checked="checked"';
} else {
	$smileyChecked = "";
}

if($_POST['parseBBcode'] OR !$_POST) {
	$bbcodeChecked = ' checked="checked"';
} else {
	$bbcodeChecked = "";
}

if($_POST['defaultBBCode'] OR (!$_POST AND $userinfo['useDefault'])) {
	$defaultBBCodeChecked = ' checked="checked"';
} else {
	$defaultBBCodeChecked = '';
}

// get the post icons...
if($foruminfo[$forumid]['allow_posticons']) {
	$postIcons = buildPostIcons();
}

// make sure they want smilies...
if($bboptions['clickable_smilies_total'] AND $foruminfo[$forumid]['allow_smilies']) {
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

// subscription?
if($userinfo['auto_threadsubscription']) {
	$subscriptCheck = " checked=\"checked\"";
} else {
	$subscriptCheck = "";
}

// get subscription template..
if($userinfo['userid']) {
	eval("\$subscriptionOptions = \"".getTemplate("message_subscribe")."\";");
} else {
	$subscriptionOptions = "";
}

// polls?
if($usergroupinfo[$userinfo['usergroupid']]['can_post_polls'] AND $forumPerms[$forumid]['can_post_polls']) {
	eval("\$pollOptions = \"".getTemplate("message_usepoll")."\";");
} else {
	$pollOptions = "";
}

// attachments?
if($bboptions['allow_attachments'] AND $bboptions['attachments_per_post'] AND $forumPerms[$forumid]['can_upload_attachments']) {
	$attachURI = "editattach.php?f=".$forumid."&hash=".$theAttachmentHash;
	eval("\$attachmentOptions = \"".getTemplate("message_attach")."\";");
} else {
	$attachmentOptions = "";
}

if($moderator) {
	// close thread?
	if($modinfo[$forumid][$moderator]['can_openClose_threads'] OR $moderator === true) {
		eval("\$canCloseThread = \"".getTemplate("message_mod_close")."\";");
	} else {
		$canCloseThread = "";
	}

	// now grab the whole template
	eval("\$moderatorOptions = \"".getTemplate("message_mod")."\";");
} else {
	$moderatorOptions = "";
}

// use metaRedirect var to sneek in a javascript...
$metaRedirect = "<script type=\"text/javascript\" src=\"scripts/message.js\"></script>";

// get toolbar..
if($bboptions['toolbar'] AND $userinfo['toolbar']) {
	eval("\$toolBar = \"".getTemplate("message_toolbar")."\";");
} else {
	$toolBar = "";
}

// get title text
eval("\$titleText = \"".getTemplate("message_title_thread")."\";");

$postMessage = stripslashes($postMessage);

// intialize templates
eval("\$header = \"".getTemplate("header")."\";");
eval("\$postthread = \"".getTemplate("message")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

printTemplate($header);
if($theError) {
	printTemplate($theError);
}
printTemplate($postthread);
printTemplate($footer);

// wrrrrrrrap it up!
wrapUp();

?>