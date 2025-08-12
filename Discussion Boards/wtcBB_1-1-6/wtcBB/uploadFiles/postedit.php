<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############### //FRONT END - EDIT POST\\ ################# \\
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
		"Error Editing Post",
		"Thread Doesn't Exist"
	);
}

$forumid = $threadinfo['forumid'];

// make sure forum is active...
if(!isActive($forumid) OR $foruminfo[$forumid]['is_category'] OR !$foruminfo[$forumid]['is_open'] OR $foruminfo[$forumid]['link_redirect']) {
	doError(
		"The forum that this thread is located in, is not active, is a category, is not open, or not a valid forum.",
		"Error Editing Post",
		"Forum isn't active or is a category"
	);
}

// get the post id
$postid = $_GET['p'];

// get post info...
$postStuff = query("SELECT * FROM posts LEFT JOIN user_info ON posts.userid = user_info.userid WHERE postid = '".$postid."' LIMIT 1");

// get moderator perms...
$moderator = hasModPermissions($forumid);

// make sure thread isn't deleted...
// we can't let anyone do this, it messes up "last post" and such.. -_-
if($threadinfo['deleted_thread']) {
	doError(
		"You may not edit posts in a deleted thread.",
		"Error Editing Post",
		"Thread is Deleted"
	);
}

// make sure post exists...
// if there's no rows...
if(!mysql_num_rows($postStuff)) {
	doError(
		"There is no post found in the database with the corresponding link.",
		"Error Editing Post",
		"Post Doesn't Exist"
	);
}

// safe to get postinfo...
$postinfo = mysql_fetch_array($postStuff);

// this next one is going to check permissions...
if($moderator !== true AND !$modinfo[$forumid][$moderator]['can_edit'] AND (($bboptions['edit_timeout'] AND (NOW - $bboptions['edit_timeout']) > $postinfo['date_posted']) OR !$forumPerms[$forumid]['can_view_board'] OR !$forumPerms[$forumid]['can_edit_own'] OR $threadinfo['closed'] OR !$userinfo['userid'] OR !$forumPerms[$forumid]['can_view_threads'])) {
	doError(
		"perms",
		"Error Editing Post"
	);
}

// restore a post?
if($_GET['do'] == "restore") {
	// make sure permisions...
	if($forumPerms[$forumid]['can_view_deletion'] AND $postinfo['deleted'] AND (($forumPerms[$forumid]['can_delete_own'] AND $postinfo['userid'] == $userinfo['userid'] AND $userinfo['userid']) OR $moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_delete']))) {
		// go ahead.. restore post!
		// check for last post?
		if($threadinfo['last_reply_date'] < $postinfo['date_posted']) {
			// update it...
			query("UPDATE threads SET thread_replies = thread_replies + 1 , last_reply_userid = '".$postinfo['userid']."' , last_reply_postid = '".$postinfo['postid']."' , last_reply_username = '".addslashes($postinfo['username'])."' , last_reply_date = '".$postinfo['date_posted']."' WHERE threadid = '".$threadinfo['threadid']."'");

			// now that last reply date has changed.. check for forum updates...
			if($foruminfo[$forumid]['last_reply_date'] < $postinfo['date_posted']) {
				// update!
				query("UPDATE forums SET posts = posts + 1 , last_reply_username = '".addslashes($postinfo['username'])."' , last_reply_userid = '".$postinfo['userid']."' , last_reply_date = '".$postinfo['date_posted']."' , last_reply_threadid = '".$threadinfo['threadid']."' , last_reply_threadtitle = '".addslashes($threadinfo['thread_name'])."' WHERE forumid = '".$forumid."'");
			}
		}

		else {
			// post counts...
			query("UPDATE threads SET thread_replies = thread_replies + 1 WHERE threadid = '".$threadinfo['threadid']."'");
			query("UPDATE forums SET posts = posts + 1 WHERE forumid = '".$forumid."'");
		}

		// now update the actual post...
		query("UPDATE posts SET deleted = 0 WHERE postid = '".$postinfo['postid']."'");

		// update user post count... if not guest..
		if($postinfo['userid'] AND $foruminfo[$forumid]['count_posts']) {
			query("UPDATE user_info SET posts = posts + 1 WHERE userid = '".$postinfo['userid']."'");
		}

		doThanks(
			"You have successfully restored your post. You will now be redirected back to your previously visited page.",
			"Restoring Post",
			"none",
			$_SERVER['HTTP_REFERER']
		);
	}

	else {
		doError(
			"perms",
			"Error Restoring Post"
		);
	}
}

// subscribed?
$isSubscribed = query("SELECT * FROM thread_subscription WHERE threadid = '".$threadinfo['threadid']."' AND userid = '".$userinfo['userid']."' LIMIT 1");

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

	$theError = false;

	// images error
	if($numOfImages > $bboptions['maximum_images']) {
		$theError = printStandardError("error_standard","Sorry, you have too many images in your post.",0);
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
			$postMessageCopy = parseMessage($postMessage,$_POST['parseBBcode'],$_POST['parseSmilies'],$foruminfo[$forumid]['allow_img'],(!$foruminfo[$forumid]['allow_html'] AND !$postinfo['allow_html']),$foruminfo[$forumid]['allow_wtcBB'],$foruminfo[$forumid]['allow_smilies'],$postinfo['username'],$postinfo,$_POST['defaultBBCode']);
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

			if($stick_or_unstick != $threadinfo['sticky']) {
				doModLog("Stuck/Unstuck Thread: ".htmlspecialchars($threadinfo['thread_name']));
			}

			if($open_or_close != $threadinfo['closed']) {
				doModLog("Opened/Closed Thread: ".htmlspecialchars($threadinfo['thread_name']));
			}

			query("UPDATE threads SET sticky = '".$stick_or_unstick."' , closed = '".$open_or_close."' WHERE threadid = '".$threadinfo['threadid']."'");

			// should we log IP or not?
			if($bboptions['logip']) {
				$getIP = $_SERVER['REMOTE_ADDR'];
			} else {
				$getIP = null;
			}

			// regular delete?
			if($_POST['deleteOption'] == 1 AND (($forumPerms[$forumid]['can_delete_own'] AND $postinfo['userid'] == $userinfo['userid'] AND $userinfo['userid'] != 0) OR $moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_delete']))) {
				// what if first post.. and no perms? spit out error
				if($postinfo['postid'] == $threadinfo['first_post'] AND (($forumPerms[$forumid]['can_delete_threads_own'] AND $userinfo['userid'] == $postinfo['userid'] AND $userinfo['userid']) OR $moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit_threads']))) {
					// meh don't feel like writing opposite...
				} else if($postinfo['postid'] == $threadinfo['first_post']) {
					doError(
						"perms",
						"Error Deleting Thread"
					);
				}

				// decrement user post count...
				if($foruminfo[$forumid]['count_posts']) {
					// last post?
					if($postinfo['lastpost'] == $postinfo['date_posted']) {
						// find last post for user...
						$lastPost = query("SELECT * FROM posts WHERE userid = '".$postinfo['userid']."' ORDER BY date_posted DESC LIMIT 1");

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

				// otherwise.. update post counts...
				else if($postinfo['postid'] != $threadinfo['first_post']) {
					query("UPDATE threads SET thread_replies = thread_replies - 1 WHERE threadid = '".$threadinfo['threadid']."'");
					query("UPDATE forums SET posts = posts - 1 WHERE forumid = '".$forumid."'");
				}

				// if first post... delete whole thread O_o
				if($postinfo['postid'] == $threadinfo['first_post']) {
					// update forum stuff now too...
					if($foruminfo[$forumid]['last_reply_threadid'] == $threadinfo['threadid']) {
						// find the latest thread, and re-insert info...
						$latestThread = query("SELECT * FROM threads WHERE forumid = '".$forumid."' AND last_reply_date < '".$threadinfo['last_reply_date']."' AND threadid != '".$threadinfo['threadid']."' AND deleted_thread = 0 ORDER BY last_reply_date DESC LIMIT 1");

						// if rows...
						if(mysql_num_rows($latestThread)) {
							$lastThreadInfo = mysql_fetch_array($latestThread);

							// re-update...
							query("UPDATE forums SET last_reply_username = '".addslashes($lastThreadInfo['last_reply_username'])."' , last_reply_userid = '".$lastThreadInfo['last_reply_userid']."' , last_reply_date = '".$lastThreadInfo['last_reply_date']."' , last_reply_threadtitle = '".addslashes($lastThreadInfo['thread_name'])."' , last_reply_threadid = '".$lastThreadInfo['threadid']."' , threads = threads - 1 , posts = posts - ".($threadinfo['thread_replies'] + 1)." WHERE forumid = '".$forumid."'");
						} else {
							// set values to null...
							query("UPDATE forums SET last_reply_username = null , last_reply_userid = null , last_reply_date = null , last_reply_threadtitle = null , last_reply_threadid = null , threads = threads - 1 , posts = posts - ".($threadinfo['thread_replies'] + 1)." WHERE forumid = '".$forumid."'");
						}
					}

					else {
						// update forum count...
						query("UPDATE forums SET threads = threads - 1 , posts = posts - ".($threadinfo['thread_replies'] + 1)." WHERE forumid = '".$forumid."'");
					}

					// decrement user THREAD count...
					query("UPDATE user_info SET threads = threads - 1 WHERE userid = '".$postinfo['userid']."'");

					// now "delete" thread...
					query("UPDATE threads SET sticky = 0 , deleted_thread = 1 , deleted_by_thread = '".$userinfo['username']."' , deleted_reason_thread = '".addslashes(htmlspecialchars($_POST['theReason']))."' , delete_time_thread = '".NOW."' WHERE threadid = '".$threadinfo['threadid']."'");

					$extra = "";
				}

				else {
					$extra = " , deleted = 1 , deleted_time = '".NOW."' , deleted_by = '".$userinfo['username']."' , deleted_reason = '".addslashes(htmlspecialchars($_POST['theReason']))."'";
				}

				if($moderator) {
					doModLog("Deleted Post: ".$postinfo['title']);
				}
			}

			else if($_POST['deleteOption'] == 2 AND (($forumPerms[$forumid]['can_perm_delete'] AND $postinfo['userid'] == $userinfo['userid'] AND $userinfo['userid']) OR $moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_permanently_delete']))) {
				// what if first post.. and no perms? spit out error
				if($postinfo['postid'] == $threadinfo['first_post'] AND (($forumPerms[$forumid]['can_delete_threads_own'] AND $userinfo['userid'] == $postinfo['userid'] AND $userinfo['userid']) OR $moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit_threads']))) {
					// meh don't feel like writing opposite...
				} else if($postinfo['postid'] == $threadinfo['first_post']) {
					doError(
						"perms",
						"Error Deleting Thread"
					);
				}

				// decrement user post count...
				if($foruminfo[$forumid]['count_posts']) {
					// last post?
					if($postinfo['lastpost'] == $postinfo['date_posted']) {
						// find last post for user...
						$lastPost = query("SELECT * FROM posts WHERE userid = '".$postinfo['userid']."' ORDER BY date_posted DESC LIMIT 1");

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

				// otherwise.. update post counts...
				else if($postinfo['postid'] != $threadinfo['first_post']) {
					query("UPDATE threads SET thread_replies = thread_replies - 1 WHERE threadid = '".$threadinfo['threadid']."'");
					query("UPDATE forums SET posts = posts - 1 WHERE forumid = '".$forumid."'");
				}

				// if first post... delete whole thread O_o
				// make sure user is ALLOWED to.. too ;)
				if($postinfo['postid'] == $threadinfo['first_post'] AND (($forumPerms[$forumid]['can_delete_threads_own'] AND $userinfo['userid'] == $postinfo['userid'] AND $userinfo['userid']) OR $moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit_threads']))) {
					// update forum stuff now too...
					if($foruminfo[$forumid]['last_reply_threadid'] == $threadinfo['threadid']) {
						// find the latest thread, and re-insert info...
						$latestThread = query("SELECT * FROM threads WHERE forumid = '".$forumid."' AND last_reply_date < '".$threadinfo['last_reply_date']."' AND threadid != '".$threadinfo['threadid']."' AND deleted_thread = 0 ORDER BY last_reply_date DESC LIMIT 1");

						// if rows...
						if(mysql_num_rows($latestThread)) {
							$lastThreadInfo = mysql_fetch_array($latestThread);

							// re-update...
							query("UPDATE forums SET last_reply_username = '".addslashes($lastThreadInfo['last_reply_username'])."' , last_reply_userid = '".$lastThreadInfo['last_reply_userid']."' , last_reply_date = '".$lastThreadInfo['last_reply_date']."' , last_reply_threadtitle = '".addslashes($lastThreadInfo['thread_name'])."' , last_reply_threadid = '".$lastThreadInfo['threadid']."' , threads = threads - 1 , posts = posts - ".($threadinfo['thread_replies'] + 1)." WHERE forumid = '".$forumid."'");
						} else {
							// set values to null...
							query("UPDATE forums SET last_reply_username = null , last_reply_userid = null , last_reply_date = null , last_reply_threadtitle = null , last_reply_threadid = null , threads = threads - 1 , posts = posts - ".($threadinfo['thread_replies'] + 1)." WHERE forumid = '".$forumid."'");
						}
					}

					else {
						// update forum count...
						query("UPDATE forums SET threads = threads - 1 , posts = posts - ".($threadinfo['thread_replies'] + 1)." WHERE forumid = '".$forumid."'");
					}

					// decrement user THREAD count...
					query("UPDATE user_info SET threads = threads - 1 WHERE userid = '".$postinfo['userid']."'");

					// delete attachments
					query("DELETE FROM attachments WHERE attachmentthread = '".$threadinfo['threadid']."'");

					// delete polls
					query("DELETE FROM poll WHERE threadid = '".$threadinfo['threadid']."'");
					query("DELETE FROM poll_options WHERE threadid = '".$threadinfo['threadid']."'");

					// delete thread subscriptions
					query("DELETE FROM thread_subscription WHERE threadid = '".$threadinfo['threadid']."'");

					// delete posts
					query("DELETE FROM posts WHERE threadid = '".$threadinfo['threadid']."'");

					// finally.. delete thread...
					query("DELETE FROM threads WHERE threadid = '".$threadinfo['threadid']."' LIMIT 1");

					doThanks(
						"Your changes have successfully been processed. You will now be redirected to ".$foruminfo[$forumid]['forum_name'].".",
						"Editing a Post",
						"none",
						"forum.php?f=".$forumid
					);
				}

				// delete attachment(s)
				query("DELETE FROM attachments WHERE attachmentpost = '".$postinfo['postid']."'");

				// just delete the friggin post already!
				query("DELETE FROM posts WHERE postid = '".$postinfo['postid']."' LIMIT 1");

				doThanks(
					"Your changes have successfully been processed. You will now be redirected to the thread.",
					"Editing a Post",
					"none",
					"thread.php?t=".$threadinfo['threadid']
				);
			}

			else {
				$extra = " , deleted_reason = '".addslashes(htmlspecialchars($_POST['theReason']))."'";
			}

			if($usergroupinfo[$userinfo['usergroupid']]['show_edited_notice']) {
				$showEdited = ", edited_by = '".$userinfo['username']."' , edited_time = '".NOW."' ";
			} else {
				$showEdited = ", edited_by = NULL , edited_time = NULL ";
			}

			// update post...
			$updatePost = query("UPDATE posts SET message = '".addslashes($postMessage)."' , title = '".addslashes($threadTitle)."' , deleted = '".$_POST['isDeleted']."' ".$showEdited.", post_icon = '".addslashes($postIconImage)."' , defBBCode = '".$_POST['defaultBBCode']."' , show_sig = '".$_POST['showSig']."' , parse_smilies = '".$_POST['parseSmilies']."' , parse_bbcode = '".$_POST['parseBBcode']."'".$extra."  WHERE postid = '".$postinfo['postid']."'");

			// do we want to subscribe?... and only if we aren't subscribed already
			if($theSubscribe AND !mysql_num_rows($isSubscribed)) {
				// insert subscription
				$insertSubscription = query("INSERT INTO thread_subscription (userid,threadid) VALUES ('".$userinfo['userid']."','".$threadinfo['threadid']."')");
			}

			// otherwise.. delete it.. because it should be checked if we are subscribed
			else if(mysql_num_rows($isSubscribed) AND !$theSubscribe) {
				query("DELETE FROM thread_subscription WHERE threadid = '".$threadinfo['threadid']."' AND userid = '".$userinfo['userid']."'");
			}

			doThanks(
				"Your changes have successfully been processed. You will now be redirected to your post.",
				"Editing a Post",
				"none",
				"thread.php?t=".$threadinfo['threadid']."&amp;p=".$postinfo['postid']."#".$postinfo['postid']
			);
		}
	}

	$postMessage = htmlspecialchars(addslashes($postMessage));
}

else {
	$postMessage = htmlspecialchars(addslashes($postinfo['message']));
	$threadTitle = htmlspecialchars($postinfo['title']);
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
$navbarArr['Edit Reply'] = "#";

$navbarText = getNavbarLinks($navbarArr);

// deal with sessions
$sessionInclude = doSessions("Editing a Reply","none");
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

// get the post icons...
if($foruminfo[$forumid]['allow_posticons']) {
	$postIcons = buildPostIcons();
}

// check for checked
if(($postinfo['show_sig'] AND !$_POST) OR $_POST['showSig']) {
	$sigChecked = ' checked="checked"';
} else {
	$sigChecked = "";
}

if(($postinfo['parse_smilies'] AND !$_POST) OR $_POST['parseSmilies']) {
	$smileyChecked = ' checked="checked"';
} else {
	$smileyChecked = "";
}

if(($postinfo['parse_bbcode'] AND !$_POST) OR $_POST['parseBBcode']) {
	$bbcodeChecked = ' checked="checked"';
} else {
	$bbcodeChecked = "";
}

if(($postinfo['defBBCode'] AND !$_POST) OR $_POST['defaultBBCode']) {
	$defaultBBCodeChecked = ' checked="checked"';
}

else {
	$defaultBBCodeChecked = '';
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
if($userinfo['userid']) {
	eval("\$subscriptionOptions = \"".getTemplate("message_subscribe")."\";");
} else {
	$subscriptionOptions = "";
}

// attachments?
if($bboptions['allow_attachments'] AND $bboptions['attachments_per_post'] AND $forumPerms[$forumid]['can_upload_attachments']) {
	$attachURI = "editattach.php?t=".$threadinfo['threadid']."&p=".$postinfo['postid'];
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

// get editing options...
if(($forumPerms[$forumid]['can_delete_own'] AND $postinfo['userid'] == $userinfo['userid']) OR ($moderator AND $modinfo[$forumid][$moderator]['can_delete']) OR $moderator === true) {
	// regular delete...
	eval("\$deleteRegular = \"".getTemplate("message_delete_regular")."\";");
}

// permanently delete..
if(($forumPerms[$forumid]['can_perm_delete'] AND $postinfo['userid'] == $userinfo['userid']) OR ($moderator AND $modinfo[$forumid][$moderator]['can_permanently_delete']) OR ($moderator === true AND $forumPerms[$forumid]['can_perm_delete'])) {
	// permanent delete..
	eval("\$deletePermanently = \"".getTemplate("message_delete_permanently")."\";");
}

// get regular editing options...
eval("\$editingOptions = \"".getTemplate("message_edited")."\";");

// get title text
eval("\$titleText = \"".getTemplate("message_title_edit")."\";");

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
		
		$arr['title'] = htmlspecialchars($arr['title']);

		// get custom title
		$theCT = getCustomTitle($arr);

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