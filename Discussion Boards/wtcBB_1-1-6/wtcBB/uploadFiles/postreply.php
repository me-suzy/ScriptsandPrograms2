<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############### //FRONT END - POST REPLY\\ ################ \\
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

check_wtcBB_forumPassword($forumid);

// we need to define a TIME for this file
// if we don't, it could result in attachments not working
define(NOW,time());

if(!$_REQUEST['hash']) {
	$theAttachmentHash = md5(NOW.$userinfo['userid'].$userinfo['username']);
} else {
	$theAttachmentHash = $_REQUEST['hash'];
}

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

// i query for threadinfo ($threadStuff) in the global.php for 
// style purposes ;)

// if there's no rows...
if(!mysql_num_rows($threadStuff)) {
	doError(
		"There is no thread found in the database with the corresponding link.",
		"Error Posting Reply",
		"Thread Doesn't Exist"
	);
}

// get moderator perms...
$moderator = hasModPermissions($forumid);

// make sure forum is active...
if(!isActive($forumid) OR $foruminfo[$forumid]['is_category'] OR !$foruminfo[$forumid]['is_open'] OR $foruminfo[$forumid]['link_redirect']) {
	doError(
		"The forum that this thread is located in, is not active, is a category, is not open, or not a valid forum.",
		"Error Posting Reply",
		"Forum isn't active or is a category"
	);
}

// make sure thread isn't deleted...
// we can't let anyone do this, it messes up "last post" and such.. -_-
if($threadinfo['deleted_thread']) {
	doError(
		"You may not post replies to a deleted thread.",
		"Error Posting Reply",
		"Thread is Deleted"
	);
}

if($_POST['delPosts'] AND is_array($_POST['delArr']) AND ($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_delete']))) {
	if(isset($_POST['delArr'][$threadinfo['first_post']])) {
		doError(
			'Sorry, you cannot delete the first post of a thread using this method',
			'Error Deleting Messages',
			'First Post of Thread'
		);
	}
	
	foreach($_POST['delArr'] as $postid => $bleh) {
		$postinfo = query('SELECT posts.date_posted, user_info.lastpost, user_info.userid, posts.postid FROM posts LEFT JOIN user_info ON posts.userid = user_info.userid WHERE posts.postid = "' . $postid . '";', 1);	
		
		if($foruminfo[$forumid]['count_posts']) {
			// last post?
			if($postinfo['lastpost'] == $postinfo['date_posted']) {
				// find last post for user...
				$lastPost = query("SELECT * FROM posts WHERE userid = '".$postinfo['userid']."' AND postid != '".$postid."' ORDER BY date_posted DESC LIMIT 1");
	
				// make sure of rows..
				if(mysql_num_rows($lastPost)) {
					// array
					$lastPostInfo = mysql_fetch_array($lastPost);
	
					query("UPDATE user_info SET posts = posts - 1 , lastpost = '".$lastPostInfo['date_posted']."' WHERE userid = '".$postinfo['userid']."'");
				}
	
				else {
					// set to null
					query("UPDATE user_info SET posts = posts - 1 , lastpost = null WHERE userid = '".$postinfo['userid']."'");
				}
			}
	
			else {
				query("UPDATE user_info SET posts = posts - 1 WHERE userid = '".$postinfo['userid']."'");
			}
		}
	
		else {
			// last post?
			if($postinfo['lastpost'] == $postinfo['date_posted']) {
				// find last post for user...
				$lastPost = query("SELECT * FROM posts WHERE userid = '".$postinfo['userid']."' AND date_posted != '".$postinfo['lastpost']."' ORDER BY date_posted DESC LIMIT 1");
	
				// make sure of rows..
				if(mysql_num_rows($lastPost)) {
					// array
					$lastPostInfo = mysql_fetch_array($lastPost);
	
					query("UPDATE user_info SET lastpost = '".$lastPostInfo['date_posted']."' WHERE userid = '".$postinfo['userid']."'");
				}
	
				// else set to null
				else {
					query("UPDATE user_info SET lastpost = null WHERE userid = '".$postinfo['userid']."'");
				}
			}
		}
	
		// what if latest post?
		if($threadinfo['last_reply_postid'] == $postinfo['postid'] AND $postinfo['postid'] != $threadinfo['first_post']) {
			// get post before this...
			$postBefore = query("SELECT * FROM posts LEFT JOIN user_info ON posts.userid = user_info.userid WHERE date_posted < '".$postinfo['date_posted']."' AND threadid = '".$threadinfo['threadid']."' AND deleted = 0 ORDER BY date_posted DESC LIMIT 1",1);
	
			// re-update thread with proper last reply stuff...
			query("UPDATE threads SET last_reply_date = '".$postBefore['date_posted']."' , last_reply_username = '".addslashes($postBefore['username'])."' , last_reply_userid = '".$postBefore['userid']."' , last_reply_postid = '".$postBefore['postid']."' , thread_replies = thread_replies - 1 WHERE threadid = '".$threadinfo['threadid']."'");
	
			// update forum stuff now too...
			if($foruminfo[$forumid]['last_reply_threadid'] == $threadinfo['threadid']) {
				// re-update...
				query("UPDATE forums SET last_reply_username = '".addslashes($postBefore['username'])."' , last_reply_userid = '".$postBefore['userid']."' , last_reply_date = '".$postBefore['date_posted']."' , posts = posts - 1 WHERE forumid = '".$forumid."'");
			}
		}
		
		query("UPDATE posts SET deleted = 1 , deleted_time = '".time()."' , deleted_by = '".$userinfo['username']."' WHERE postid = '" . $postid . "';");
	}
	
	// otherwise.. update post counts...
	query("UPDATE threads SET thread_replies = thread_replies - " . count($_POST['delArr']) . " WHERE threadid = '".$threadinfo['threadid']."'");
	query("UPDATE forums SET posts = posts - " . count($_POST['delArr']) . " WHERE forumid = '".$forumid."'");
		
	doModLog("Deleted <strong>" . count($_POST['delArr']) ." Posts in Thread: " . $threadinfo['thread_title']);
	
	doThanks(
		'You have successfully deleted <strong>' . count($_POST['delArr']) . '</strong> posts. You will now be redirected back to the thread.',
		'Deleting Posts',
		'none',
		'thread.php?t=' . $threadinfo['threadid']
	);
}

// this next one is going to check permissions...
if($userinfo['is_coppa'] OR !$forumPerms[$forumid]['can_view_board'] OR ($threadinfo['closed'] AND !$moderator) OR ($userinfo['userid'] != $threadinfo['thread_starter'] AND !$forumPerms[$forumid]['can_view_threads']) OR (!$forumPerms[$forumid]['can_reply_others'] AND $userinfo['userid'] != $threadinfo['thread_starter']) OR (!$forumPerms[$forumid]['can_reply_own'] AND $userinfo['userid'] == $threadinfo['thread_starter'])) {
	doError(
		"perms",
		"Error Posting Reply"
	);
}

// subscribed?
$isSubscribed = query("SELECT * FROM thread_subscription WHERE threadid = '".$threadinfo['threadid']."' AND userid = '".$userinfo['userid']."' LIMIT 1");

$theError = false;

if($_POST['postMessage']) {
	// get the post icon...
	if($_POST['postIcon']) {
		$postIconImage = "<img src=\"".$postIconInfo[$_POST['postIcon']]['filepath']."\" alt=\"".$postIconInfo[$_POST['postIcon']]['title']."\" />";
	} else {
		$postIconImage = "";
	}

	$threadTitle = $_POST['threadTitle'];
	$postMessage = $_POST['postMessage'];

	// if thread title is empty... use "Re: $threadinfo['thread_name']"
	if(!$threadTitle) {
		$threadTitle = "Re: ".$threadinfo['thread_name'];
	}

	// count the number of images...
	$numOfImages = countImages($postMessage);

	// images error
	if($numOfImages > $bboptions['maximum_images']) {
		$theError = printStandardError("error_standard","Sorry, you have too many images in your post.",0);
	}

	// flood check...
	else if($forumPerms[$forumid]['flood_immunity'] == 0 AND (NOW - $bboptions['floodcheck']) < $userinfo['lastpost'] AND $userinfo['userid'] != 0 AND !$_POST['preview']) {
		$theError = printStandardError("error_standard","The administrator has specified you may only make a new reply every ".$bboptions['floodcheck']." seconds.",0);
	}

	else if(strlen($postMessage) < $bboptions['minimum_chars_post']) {
		$theError = printStandardError("error_standard","Sorry, your post is under the minimum character count.",0);
	}

	else if(strlen($postMessage) > $bboptions['maximum_chars_post']) {
		$theError = printStandardError("error_standard","Sorry, your post is over the maximum character count.",0);
	}

	else {
		// get the quote...
		if(is_array($_REQUEST['quoteArr']) AND $_REQUEST['quickReply']) {
			// form query
			foreach($_REQUEST['quoteArr'] as $postid2 => $postid3) {
				$thePosts .= "OR posts.postid = ".$postid2." ";
			}

			// remove first "OR"
			$thePosts = preg_replace("|^OR|","",$thePosts);

			$quoteQuery = query("SELECT * FROM posts LEFT JOIN user_info ON user_info.userid = posts.userid WHERE ".$thePosts." ORDER BY posts.date_posted DESC");

			// make sure rows..
			if(mysql_num_rows($quoteQuery)) {
				// get array
				while($quoteinfo = mysql_fetch_array($quoteQuery)) {
					// censor message
					$quoteinfo['message'] = doCensors($quoteinfo['message']);

					// get rid of embedded quotes
					$quoteinfo['message'] = preg_replace("#\[quote=(.*)\](.*)\[/quote\]#eisU","",$quoteinfo['message']);
					$quoteinfo['message'] = preg_replace("|(\[quote\])(.*)(\[/quote\])|isU","",$quoteinfo['message']);

					// mmmmmhmmmm leftovers!
					$quoteinfo['message'] = preg_replace("|\[quote\]|","",$quoteinfo['message']);
					$quoteinfo['message'] = preg_replace("|\[/quote\]|","",$quoteinfo['message']);

					// get quote
					$postMessage = "[quote=".$quoteinfo['username']."]".$quoteinfo['message']."[/quote]\n\n".$postMessage;
				}
			}

			// trim whitespace
			$postMessage = preg_replace("#(\[quote=.*\])(.*)(\[/quote\])#eisU","trimQuote('$1','$2','$3')",$postMessage);
			$postMessage = preg_replace("|\n\n$|","\n",stripslashes($postMessage));
		}

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

			if($doubleCheckSticky) {
				doModLog("Stuck/Unstuck Thread: ".htmlspecialchars($threadinfo['thread_name']));
			}

			if($doubleCheckClose) {
				doModLog("Opened/Closed Thread: ".htmlspecialchars($threadinfo['thread_name']));
			}

			// should we log IP or not?
			if($bboptions['logip']) {
				$getIP = $_SERVER['REMOTE_ADDR'];
			} else {
				$getIP = null;
			}

			// now insert the post...
			$insertPost = query("INSERT INTO posts (threadid,userid,postUsername,message,title,ip_address,date_posted,deleted,forumid,post_icon,show_sig,parse_smilies,parse_bbcode,defBBCode) VALUES ('".$threadinfo['threadid']."','".$userinfo['userid']."','".addslashes($userinfo['username'])."','".addslashes($postMessage)."','".addslashes($threadTitle)."','".$getIP."','".NOW."','0','".$forumid."','".addslashes($postIconImage)."','".$_POST['showSig']."','".$_POST['parseSmilies']."','".$_POST['parseBBcode']."','".$_POST['defaultBBCode']."')");

			$insertPostID = mysql_insert_id();

			// now that we have double checked close and sticky... we want to see if we open or close it...
			if($doubleCheckClose) {
				if($threadinfo['closed']) {
					$open_or_close = 0;
				} else {
					$open_or_close = 1;
				}
			} else {
				$open_or_close = $threadinfo['closed'];
			}

			if($doubleCheckSticky) {
				if($threadinfo['sticky']) {
					$stick_or_unstick = 0;
				} else {
					$stick_or_unstick = 1;
				}
			} else {
				$stick_or_unstick = $threadinfo['sticky'];
			}

			$extra = " , sticky = '".$stick_or_unstick."' , closed = '".$open_or_close."'";

			$updateThread = query("UPDATE threads SET last_reply_postid = '".$insertPostID."'".$extra." , last_reply_username = '".addslashes($userinfo['username'])."' , last_reply_userid = '".$userinfo['userid']."' , last_reply_date = '".NOW."' , thread_replies = thread_replies + 1 WHERE threadid = '".$threadinfo['threadid']."'");

			// update this forum with last post information..
			$updateForum = query("UPDATE forums SET posts = posts + 1 , last_reply_username = '".addslashes($userinfo['username'])."' , last_reply_userid = '".$userinfo['userid']."' , last_reply_date = '".NOW."' , last_reply_threadid = '".$threadinfo['threadid']."' , last_reply_threadtitle = '".addslashes($threadinfo['thread_name'])."' WHERE forumid = '".$forumid."'");

			// if forum counts posts.. then update posts and threads...
			if($foruminfo[$forumid]['count_posts'] AND $userinfo['userid']) {
				query("UPDATE user_info SET posts = posts + 1 , lastpost = '".NOW."' , lastpostid = '".$insertPostID."' WHERE userid = '".$userinfo['userid']."'");
			}

			else if($userinfo['userid']) {
				query("UPDATE user_info SET lastpost = '".NOW."' , lastpostid = '".$insertPostID."' WHERE userid = '".$userinfo['userid']."'");
			}

			// if not quick reply
			if(!$_POST['quickReply']) {
				// do we want to subscribe?... and only if we aren't subscribed already
				if($theSubscribe AND !mysql_num_rows($isSubscribed)) {
					// insert subscription
					$insertSubscription = query("INSERT INTO thread_subscription (userid,threadid) VALUES ('".$userinfo['userid']."','".$threadinfo['threadid']."')");
				}

				// otherwise.. delete it.. because it should be checked if we are subscribed
				else if(mysql_num_rows($isSubscribed) AND !$theSubscribe) {
					query("DELETE FROM thread_subscription WHERE threadid = '".$threadinfo['threadid']."' AND userid = '".$userinfo['userid']."'");
				}
			}

			// if attachments, add postids and crap
			$findAttachments = query("UPDATE attachments SET attachmentthread = '".$threadinfo['threadid']."' , attachmentpost = '".$insertPostID."' WHERE attachmenthash = '".$theAttachmentHash."'");

			// do thread subscriptions...
			// only if its enabled globally...
			if($bboptions['enable_email']) {
				// send out the emails...
				// get subscriptions for this thread
				$getSubscriptions = query("SELECT * FROM thread_subscription LEFT JOIN user_info ON thread_subscription.userid = user_info.userid WHERE threadid = '".$threadinfo['threadid']."'");

				// if rows
				if(mysql_num_rows($getSubscriptions)) {
					while($subscribe = mysql_fetch_array($getSubscriptions)) {
						// only do it if it isn't the same user...
						if($subscribe['userid'] == $userinfo['userid']) {
							continue;
						}
						
						$username = $subscribe['username'];
						$thread_title = htmlspecialchars($threadinfo['thread_name']);
						eval("\$message = \"".getTemplate("mail_subscription")."\";");
						mail($subscribe['email'],"wtcBB Mailer - Thread Subscription Notification",$message,"From: ".$bboptions['details_contact']."\r\nReply-To: ".$bboptions['details_contact']);
					}
				}

				// email mods?
				if(is_array($modinfo[$forumid])) {
					foreach($modinfo[$forumid] as $moderatorid => $arr) {
						if($arr['receive_email_post']) {
							$username = $arr['username'];
							$thread_title = htmlspecialchars($threadinfo['thread_name']);
							eval("\$message = \"".getTemplate("mail_subscription")."\";");
							mail($arr['email'],"wtcBB Mailer - Thread Subscription Notification",$message,"From: ".$bboptions['details_contact']."\r\nReply-To: ".$bboptions['details_contact']);
						}
					}
				}
			}

			doThanks(
				"Your reply has successfully been processed. You will now be redirected to your post.",
				"Posting a Reply",
				"none",
				"thread.php?t=".$threadinfo['threadid']."&amp;p=".$insertPostID."#".$insertPostID
			);
		}
	}

	$postMessage = htmlspecialchars(addslashes($postMessage));
}

if(!$_POST) {
	$postMessage = "";
}

// get the quote...
if(is_array($_REQUEST['quoteArr'])) {
	// form query
	foreach($_REQUEST['quoteArr'] as $postid2 => $postid3) {
		$thePosts .= "OR posts.postid = ".$postid2." ";
	}

	// remove first "OR"
	$thePosts = preg_replace("|^OR|","",$thePosts);

	$quoteQuery = query("SELECT * FROM posts LEFT JOIN user_info ON user_info.userid = posts.userid WHERE ".$thePosts." ORDER BY posts.date_posted DESC");

	// make sure rows..
	if(mysql_num_rows($quoteQuery)) {
		// get array
		while($quoteinfo = mysql_fetch_array($quoteQuery)) {
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
			$postMessage = "[quote=".$quoteinfo['username']."]".htmlspecialchars($quoteinfo['message'])."[/quote]\n\n".$postMessage;
		}
	}

	// trim whitespace
	$postMessage = preg_replace("#(\[quote=.*\])(.*)(\[/quote\])#eisU","trimQuote('$1','$2','$3')",$postMessage);
	$postMessage = preg_replace("|\n\n$|","\n",$postMessage);
}

// create nav bar array..
$navbarArr = getForumNav($forumid);

// reverse it... if array exists
if(is_array($navbarArr)) {
	$navbarArr = array_reverse($navbarArr);
}

// add to it...
$navbarArr[$foruminfo[$forumid]['forum_name']] = "forum.php?f=".$forumid;
$navbarArr[htmlspecialchars($threadinfo['thread_name'])] = "thread.php?t=".$threadinfo['threadid'];
$navbarArr['Post Reply'] = "#";

$navbarText = getNavbarLinks($navbarArr);

// deal with sessions
$sessionInclude = doSessions("Posting a Reply","none");
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
if($userinfo['auto_threadsubscription'] OR mysql_num_rows($isSubscribed)) {
	$subscriptCheck = " checked=\"checked\"";
} else {
	$subscriptCheck = "";
}

// get subscription template..
if($userinfo['userid'] != 0) {
	eval("\$subscriptionOptions = \"".getTemplate("message_subscribe")."\";");
} else {
	$subscriptionOptions = "";
}

// attachments?
if($bboptions['allow_attachments'] AND $bboptions['attachments_per_post'] AND $forumPerms[$forumid]['can_upload_attachments']) {
	$attachURI = "editattach.php?t=".$threadinfo['threadid']."&hash=".$theAttachmentHash;
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
eval("\$titleText = \"".getTemplate("message_title_reply")."\";");

$postMessage = stripslashes($postMessage);

// do we want topic review?
if($bboptions['topicReview']) {
	// query for posts...
	$getPosts = query("SELECT * FROM posts LEFT JOIN user_info ON posts.userid = user_info.userid WHERE threadid = '".$threadinfo['threadid']."' AND deleted = 0 ORDER BY date_posted DESC LIMIT 15");

	while($arr = mysql_fetch_array($getPosts)) {
		// if it's a guest... then we need to use the proper array...
		if(!$arr['userid']) {
			$arr = array_merge($arr, $guestinfo);
		}

		// process some dates...
		$registered = processDate($bboptions['date_register_format'],$arr['date_joined']);
		$datePosted = processDate($bboptions['date_formatted'],$arr['date_posted']);
		$timePosted = processDate($bboptions['date_time_format'],$arr['date_posted']);

		// get username
		$theUsername = getHTMLUsername($arr);

		// get custom title
		$theCT = getCustomTitle($arr);
		
		$arr['title'] = htmlspecialchars($arr['title']);

		$arr['message'] = parseMessage($arr['message'],$arr['parse_bbcode'],$arr['parse_smilies'],$foruminfo[$arr['forumid']]['allow_img'],(!$userinfo['allow_html']),$foruminfo[$arr['forumid']]['allow_wtcBB'],$foruminfo[$arr['forumid']]['allow_smilies'],$arr['username']);

		// grab the template
		eval("\$postbits .= \"".getTemplate("search_postbit")."\";");
	}
}

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