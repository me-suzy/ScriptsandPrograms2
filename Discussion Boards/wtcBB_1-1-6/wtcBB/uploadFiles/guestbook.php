<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############### //FRONT END - GUEST BOOK\\ ################ \\
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

// get forum home stylesheet
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_forumhome")."\";");
eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_threaddisplay")."\";");

// if no css file.. get internal block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

// make sure user exists!
$user_q = query("SELECT * FROM user_info WHERE userid = '".$_GET['u']."' LIMIT 1");

if(!$bboptions['enableGuestbook']) {
	doError(
		"Guestbooks are currently disabled on this message board.",
		"Error Viewing Guestbook",
		"Guestbook Disabled"
	);
}

// uh ohh....
if(!mysql_num_rows($user_q) OR !$_GET['u']) {
	doError(
		"There is no user found in the database with the corresponding link.",
		"Error Viewing Guestbook",
		"User Doesn't Exist"
	);
}

$user = mysql_fetch_array($user_q);

if(!$user['enableGuestbook']) {
	doError(
		"Sorry, guestbooks are disabled for this user.",
		"Error Viewing Guestbook",
		"Guestbooks Disabled for User"
	);
}

if(!$usergroupinfo[$user['usergroupid']]['book_hidden']) {
	doError(
		"Sorry, guestbooks are disabled for this usergroup.",
		"Error Viewing Guestbook",
		"Guestbooks Disabled for Group"
	);
}

// perms?
if(($user['userid'] != $userinfo['userid'] AND !$usergroupinfo[$user['usergroupid']]['book_viewOthers']) OR ($user['userid'] == $userinfo['userid'] AND !$usergroupinfo[$user['usergroupid']]['book_viewOwn'])) {
	doError(
		"perms",
		"Error Viewing Guestbook"
	);
}

if($_REQUEST['do'] == "add") {
	// perms??
	if(($user['userid'] == $userinfo['userid'] AND !$usergroupinfo[$userinfo['usergroupid']]['book_addOwn']) OR ($user['userid'] != $userinfo['userid'] AND !$usergroupinfo[$userinfo['usergroupid']]['book_addOthers'])) {
		doError(
			"perms",
			"Error Adding Entry to Guestbook"
		);
	}

	if($_POST['postMessage']) {
		$threadTitle = $_POST['threadTitle'];
		$postMessage = $_POST['postMessage'];

		// if thread title is empty... use "Re: $user['username']"
		if(!$threadTitle) {
			$threadTitle = "Re: ".$user['username'];
		}

		// count the number of images...
		$numOfImages = countImages($postMessage);

		// images error
		if($numOfImages > $bboptions['maximum_images']) {
			$theError = printStandardError("error_standard","Sorry, you have too many images in your entry.",0);
		}

		// flood check...
		else if(!$usergroupinfo[$userinfo['usergroupid']]['flood_immunity'] AND (time() - $bboptions['floodcheck']) < $userinfo['lastpost'] AND $userinfo['userid'] != 0) {
			$theError = printStandardError("error_standard","The administrator has specified you may only make a new reply every ".$bboptions['floodcheck']." seconds.",0);
		}

		else if(strlen($postMessage) < $bboptions['minimum_chars_post']) {
			$theError = printStandardError("error_standard","Sorry, your entry is under the minimum character count.",0);
		}

		else if(strlen($postMessage) > $bboptions['maximum_chars_post']) {
			$theError = printStandardError("error_standard","Sorry, your entry is over the maximum character count.",0);
		}

		else {
			// ok.. all errors effecting preview AND db inserts should have been processed
			// so... preview or not?
			if($_POST['preview']) {
				$postMessageCopy = parseMessage($postMessage,$_POST['parseBBcode'],$_POST['parseSmilies'],$bboptions['allow_img_personal'],(!$bboptions['allow_html_personal'] AND !$userinfo['allow_html']),$bboptions['allow_wtcBB_personal'],$bboptions['allow_smilies_personal'],$userinfo['username'],$userinfo,$_POST['defaultBBCode']);
				$threadTitleCopy = replaceReplacements(doCensors(htmlspecialchars($threadTitle)));

				eval("\$possiblePreview = \"".getTemplate("message_preview")."\";");
			}

			// now we're actually going to insert it into the DB...
			else {
				// should we log IP or not?
				if($bboptions['logip']) {
					$getIP = $_SERVER['REMOTE_ADDR'];
				} else {
					$getIP = null;
				}

				// now insert the post...
				$insertPost = query("INSERT INTO guestbook (ownUserid,userid,title,ip_address,deleted,message,date_posted,show_sig,parse_smilies,parse_bbcode,defBBCode) VALUES ('".$user['userid']."','".$userinfo['userid']."','".addslashes($threadTitle)."','".$getIP."',0,'".addslashes($postMessage)."','".time()."','".$_POST['showSig']."','".$_POST['parseSmilies']."','".$_POST['parseBBcode']."','".$_POST['defaultBBCode']."')");

				// mail...
				// only if its enabled globally...
				if($bboptions['enable_email'] AND $user['guestbookSubscribe'] AND $bboptions['guestbookNotify']) {
					eval("\$message = \"".getTemplate("mail_guestbookSubscribe")."\";");
					mail($user['email'],"wtcBB Mailer - Guestbook Entry Notification",$message,"From: ".$bboptions['details_contact']);
				}

				doThanks(
					"Your entry has successfully been processed. You will now be redirected to <strong>".$user['username']."</strong>'s guestbook.",
					"Adding Entry to Guestbook",
					$user['username'],
					"guestbook.php?u=".$user['userid']
				);
			}
		}

		$postMessage = htmlspecialchars(addslashes($postMessage));
	}

	if(!$_POST) {
		$postMessage = "";
	}

	// get the quote...
	if(is_array($_REQUEST['quoteArr']) AND !$_POST['preview']) {
		// form query
		foreach($_REQUEST['quoteArr'] as $postid2 => $postid3) {
			$thePosts .= "OR guestbook.bookid = ".$postid2." ";
		}

		// remove first "OR"
		$thePosts = preg_replace("|^OR|","",$thePosts);

		$quoteQuery = query("SELECT guestbook.message , user_info.username FROM guestbook LEFT JOIN user_info ON user_info.userid = guestbook.userid WHERE ".$thePosts." ORDER BY guestbook.date_posted DESC");

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

	// create nav bar array
	$navbarArr = Array(
		$user['username']."'s Guestbook" => "guestbook.php?u=".$user['userid'],
		"Adding an Entry" => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// deal with sessions
	$sessionInclude = doSessions("Adding Entry in Guestbook",$user['username']);
	include("./includes/sessions.php");

	// get all the posting rules...
	if($bboptions['allow_wtcBB_personal']) {
		$wtcBBcode = "may";
	} else {
		$wtcBBcode = "may not";
	}

	if($bboptions['allow_smilies_personal']) {
		$wtcBBsmilies = "may";
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

	// make sure they want smilies...
	if($bboptions['clickable_smilies_total'] AND $bboptions['allow_smilies_personal']) {
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
		eval("\$toolBar = \"".getTemplate("message_toolbar")."\";");
	} else {
		$toolBar = "";
	}

	$postMessage = stripslashes($postMessage);

	eval("\$guestbook = \"".getTemplate("guestbook_addEdit")."\";");
}

else if($_GET['b']) {
	// perms??
	if(($user['userid'] == $userinfo['userid'] AND !$usergroupinfo[$userinfo['usergroupid']]['book_editOwn']) OR ($user['userid'] != $userinfo['userid'] AND !$usergroupinfo[$userinfo['usergroupid']]['book_editOthers'])) {
		doError(
			"perms",
			"Error Editing Entry in Guestbook"
		);
	}

	// restore a post?
	if($_GET['do'] == "restore") {
		// make sure permisions...
		if($usergroupinfo[$userinfo['usergroupid']]['can_view_deletion'] OR $userinfo['userid'] == $user['userid']) {
			// go ahead.. restore post!
			query("UPDATE guestbook SET deleted = 0 WHERE bookid = '".$_GET['b']."'");

			doThanks(
				"You have successfully restored your entry. You will now be redirected back to your previously visited page.",
				"Restoring Entry",
				"none",
				$_SERVER['HTTP_REFERER']
			);
		}

		else {
			doError(
				"perms",
				"Error Restoring Entry in Guestbook"
			);
		}
	}

	// get the post id
	$postid = $_GET['b'];

	// get post info...
	$postStuff = query("SELECT * FROM guestbook LEFT JOIN user_info ON guestbook.userid = user_info.userid WHERE bookid = '".$postid."' LIMIT 1");

	// make sure post exists...
	// if there's no rows...
	if(!mysql_num_rows($postStuff)) {
		doError(
			"There is no entry found in the database with the corresponding link.",
			"Error Editing Entry in Guestbook",
			"Entry Doesn't Exist"
		);
	}

	// safe to get postinfo...
	$postinfo = mysql_fetch_array($postStuff);

	if($_POST['postMessage']) {
		// perm delete?
		if($_POST['deleteOption'] == 2 AND (($user['userid'] == $userinfo['userid'] AND $usergroupinfo[$userinfo['usergroupid']]['book_permDeleteOwn']) OR ($user['userid'] != $userinfo['userid'] AND $usergroupinfo[$userinfo['usergroupid']]['book_permDeleteOthers']))) {
			query("DELETE FROM guestbook WHERE bookid = ".$postid);

			doThanks(
				"Your entry has successfully been deleted. You will now be redirected to <strong>".$user['username']."</strong>'s guestbook.",
				"Deleting Entry in Guestbook",
				$user['username'],
				"guestbook.php?u=".$user['userid']
			);
		}

		$threadTitle = $_POST['threadTitle'];
		$postMessage = $_POST['postMessage'];

		// if thread title is empty... use "Re: $user['username']"
		if(!$threadTitle) {
			$threadTitle = "Re: ".$user['username'];
		}

		// count the number of images...
		$numOfImages = countImages($postMessage);

		// images error
		if($numOfImages > $bboptions['maximum_images']) {
			$theError = printStandardError("error_standard","Sorry, you have too many images in your entry.",0);
		}

		// flood check...
		else if(!$usergroupinfo[$userinfo['usergroupid']]['flood_immunity'] AND (time() - $bboptions['floodcheck']) < $userinfo['lastpost'] AND $userinfo['userid'] != 0) {
			$theError = printStandardError("error_standard","The administrator has specified you may only make a new reply every ".$bboptions['floodcheck']." seconds.",0);
		}

		else if(strlen($postMessage) < $bboptions['minimum_chars_post']) {
			$theError = printStandardError("error_standard","Sorry, your entry is under the minimum character count.",0);
		}

		else if(strlen($postMessage) > $bboptions['maximum_chars_post']) {
			$theError = printStandardError("error_standard","Sorry, your entry is over the maximum character count.",0);
		}

		else {
			// ok.. all errors effecting preview AND db inserts should have been processed
			// so... preview or not?
			if($_POST['preview']) {
				$postMessageCopy = parseMessage($postMessage,$_POST['parseBBcode'],$_POST['parseSmilies'],$bboptions['allow_img_personal'],(!$bboptions['allow_html_personal'] AND !$userinfo['allow_html']),$bboptions['allow_wtcBB_personal'],$bboptions['allow_smilies_personal'],$userinfo['username'],$userinfo,$_POST['defaultBBCode']);
				$threadTitleCopy = replaceReplacements(doCensors(htmlspecialchars($threadTitle)));

				eval("\$possiblePreview = \"".getTemplate("message_preview")."\";");
			}

			// now we're actually going to insert it into the DB...
			else {
				// should we log IP or not?
				if($bboptions['logip']) {
					$getIP = $_SERVER['REMOTE_ADDR'];
				} else {
					$getIP = null;
				}

				// now update the post...
				$updatePost = query("UPDATE guestbook SET title = '".addslashes($threadTitle)."' , ip_address = '".$getIP."' , deleted = '".$_POST['deleteOption']."' , message = '".addslashes($postMessage)."' , show_sig = '".$_POST['showSig']."' , parse_smilies = '".$_POST['parseSmilies']."' , parse_bbcode = '".$_POST['parseBBcode']."' , defBBCode = '".$_POST['defaultBBCode']."' , edited_by = '".$userinfo['username']."' , edited_time = '".time()."' , edited_reason = '".$_POST['theReason']."' WHERE bookid = ".$postid);

				doThanks(
					"Your entry has successfully been edited. You will now be redirected to <strong>".$user['username']."</strong>'s guestbook.",
					"Editing Entry in Guestbook",
					$user['username'],
					"guestbook.php?u=".$user['userid']
				);
			}
		}

		$postMessage = htmlspecialchars(addslashes($postMessage));
	}

	if(!$_POST) {
		$postMessage = htmlspecialchars(addslashes($postinfo['message']));
		$threadTitle = htmlspecialchars($postinfo['title']);
	}

	// create nav bar array
	$navbarArr = Array(
		$user['username']."'s Guestbook" => "guestbook.php?u=".$user['userid'],
		"Adding an Entry" => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// deal with sessions
	$sessionInclude = doSessions("Adding Entry in Guestbook",$user['username']);
	include("./includes/sessions.php");

	// get all the posting rules...
	if($bboptions['allow_wtcBB_personal']) {
		$wtcBBcode = "may";
	} else {
		$wtcBBcode = "may not";
	}

	if($bboptions['allow_smilies_personal']) {
		$wtcBBsmilies = "may";
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

	// check for checked
	if($_POST['showSig'] OR (!$_POST AND $postinfo['show_sig'])) {
		$sigChecked = ' checked="checked"';
	} else {
		$sigChecked = "";
	}

	if($_POST['parseSmilies'] OR (!$_POST AND $postinfo['parse_smilies'])) {
		$smileyChecked = ' checked="checked"';
	} else {
		$smileyChecked = "";
	}

	if($_POST['parseBBcode'] OR (!$_POST AND $postinfo['parse_bbcode'])) {
		$bbcodeChecked = ' checked="checked"';
	} else {
		$bbcodeChecked = "";
	}

	if($_POST['defaultBBCode'] OR (!$_POST AND $postinfo['defBBCode'])) {
		$defaultBBCodeChecked = ' checked="checked"';
	} else {
		$defaultBBCodeChecked = '';
	}

	// make sure they want smilies...
	if($bboptions['clickable_smilies_total'] AND $bboptions['allow_smilies_personal']) {
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
		eval("\$toolBar = \"".getTemplate("message_toolbar")."\";");
	} else {
		$toolBar = "";
	}

	$postMessage = stripslashes($postMessage);

	eval("\$guestbook = \"".getTemplate("guestbook_addEdit")."\";");
}

else {
	// now we're going to query for all posts in this thread...
	$allPosts = query("SELECT * FROM guestbook LEFT JOIN user_info ON user_info.userid = guestbook.userid WHERE guestbook.ownUserid = '".$user['userid']."' ORDER BY date_posted DESC");

	$postbits = '';

	// error!?!
	if(mysql_num_rows($allPosts)) {
		// we must get how many we're showing though.. AND which ones...
		$secPostCount = 1;
		while($postinfo2 = mysql_fetch_array($allPosts)) {
			$postinfo[$postinfo2['bookid']] = $postinfo2;
			$postinfo[$postinfo2['bookid']]['postCounter'] = $secPostCount;

			if(!$postinfo2['deleted']) {
				$secPostCount++;
			}
		}

		// count deleted
		foreach($postinfo as $postid => $arr) {
			if($arr['deleted']) {
				$numOfDeleted++;
			}
		}

		$numOfPosts = (mysql_num_rows($allPosts) - $numOfDeleted);
		$postNum = $bboptions['guestbookPerPage'];

		// error!?!
		if(!$numOfPosts AND !$numOfDeleted) {
			doError(
				"Sorry, no guestbook entries were found.",
				"Error Viewing ".$user['username']."'s Guestbook",
				"No Entries"
			);
		}

		// grab page...
		if(!$_GET['page']) {
			$page = 1;
		} else {
			$page = $_GET['page'];
		}

		// get the start
		$start = ($page - 1) * $postNum;
		$end = $start + $postNum;

		// intiate post counter...
		$postCounter = 0;

		if($numOfPosts % $postNum != 0) {
			$totalPages = ($numOfPosts / $postNum) + 1;
			settype($totalPages,"integer");
		} else {
			$totalPages = $numOfPosts / $postNum;
		}

		// build the page links...
		$pagelinks = buildPageLinks($totalPages,$page);

		// loop through posts...
		foreach($postinfo as $postid => $arr) {
			// make sure we're displaying posts that we should be...
			// increment post counter
			if(!$arr['deleted']) {
				$postCounter++;
			}

			// make sure we're in right place...
			if($postCounter <= $start AND $postCounter) {
				// move on...
				continue;
			}

			if($postCounter > $end) {
				// not going to be showing anymore...
				// so break out!
				break;
			}

			// if it's a guest... then we need to use the proper array...
			if(!$arr['userid']) {
				$arr = array_merge($arr, $guestinfo);
			}

			// unset all the links...
			unset(
				$editLink,
				$onlineOffline
				);

			$postid = $arr['bookid'];

			// process some dats...
			$registered = processDate($bboptions['date_register_format'],$arr['date_joined']);
			$datePosted = processDate($bboptions['date_formatted'],$arr['date_posted']);
			$timePosted = processDate($bboptions['date_time_format'],$arr['date_posted']);

			// get username
			if(!$arr['userid']) {
				$theUsername = "Guest";
			} else {
				$theUsername = getHTMLUsername($arr);
			}

			// get custom title
			$theCT = getCustomTitle($arr);

			// posts
			// get posts per day...
			if((time() - $arr['date_joined']) < 86400) {
				$postsPerDay = $arr['posts'];
			} else {
				$postsPerDay = substr($arr['posts'] / ((time() - $arr['date_joined']) / 86400),0,6);
			}

			$arr['message'] = parseMessage($arr['message'],$arr['parse_bbcode'],$arr['parse_smilies'],(!$bboptions['allow_html_personal'] AND !$userinfo['allow_html']),$bboptions['allow_img_personal'],$bboptions['allow_wtcBB_personal'],$bboptions['allow_smilies_personal'],$arr['username'],$arr);
			$arr['title'] = replaceReplacements(doCensors(htmlspecialchars($arr['title'])));

			if(($userinfo['userid'] == $user['userid'] AND $usergroupinfo[$user['usergroupid']]['book_editOwn']) OR ($userinfo['userid'] != $user['userid'] AND $usergroupinfo[$user['usergroupid']]['book_editOthers'])) {
				// edit template
				eval("\$editLink = \"".getTemplate("guestbook_editLink")."\";");
			}

			// get the online status...
			eval("\$onlineOffline = \"".getTemplate(fetchOnlineStatus($arr['userid']))."\";");

			// format sig... only if use has set to display sig...
			if(!$arr['signature'] OR !$arr['show_sig'] OR !$bboptions['allow_signatures'] OR $arr['ban_sig'] OR !$userinfo['view_signature'] OR !$usergroupinfo[$arr['usergroupid']]['can_sig']) {
				$theSignature = "";
			}

			else {
				// one last thing... cut off sig to maximum amount..
				if($bboptions['maximum_signature'] > 0) {
					$arr['signature'] = trimString($arr['signature'],$bboptions['maximum_signature'],0);
				}

				$theSig = parseMessage($arr['signature'],$bboptions['allow_wtcBB_sig'],$bboptions['allow_smilies_sig'],$bboptions['allow_img_sig'],(!$bboptions['allow_html_sig'] AND !$userinfo['allow_html']),true,true,$arr['username']);
				eval("\$theSignature = \"".getTemplate("threaddisplay_signature")."\";");
			}

			// what about edited message?
			if($arr['edited_by'] AND $usergroupinfo[$arr['usergroupid']]['show_edited_notice'] AND $bboptions['show_edit_message']) {
				// format time and date...
				$dateEdited = processDate($bboptions['date_formatted'],$arr['edited_time']);
				$timeEdited = processDate($bboptions['date_time_format'],$arr['edited_time']);
			}
			
			$arr['title'] = htmlspecialchars($arr['title']);

			// grab the template
			// deleted or not?
			if(!$arr['deleted']) {
				eval("\$postbits .= \"".getTemplate("guestbook_postbit")."\";");
			} else {
				if($usergroupinfo[$userinfo['usergroupid']]['can_view_deletion'] OR ($user['userid'] == $userinfo['userid'] AND $usergroupinfo[$userinfo['usergroupid']]['book_editOwn'])) {
					$deletedDate = processDate($bboptions['date_formatted'],$arr['edited_time']);
					$deletedTime = processDate($bboptions['date_time_format'],$arr['edited_time']);

					eval("\$postbits .= \"".getTemplate("guestbook_deleted")."\";");
				}
			}
		}
	}

	// create nav bar array
	$navbarArr = Array(
		"Viewing ".$user['username']."'s Guestbook" => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// deal with sessions
	$sessionInclude = doSessions("Viewing Guestbook",$user['username']);
	include("./includes/sessions.php");

	// get whole template
	eval("\$guestbook = \"".getTemplate("guestbook")."\";");
}

// get templates
eval("\$header = \"".getTemplate("header")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

printTemplate($header);

if($theError) {
	printTemplate($theError);
}

printTemplate($guestbook);
printTemplate($footer);

?>