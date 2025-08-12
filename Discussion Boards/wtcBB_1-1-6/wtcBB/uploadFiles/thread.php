<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############## //FRONT END - THREAD DISPLAY\\ ############# \\
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

// get thread display stylesheet
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_threaddisplay")."\";");
eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_forumhome")."\";");
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
if(!mysql_num_rows($threadStuff) OR $threadinfo['moved']) {
	doError(
		"There is no thread found in the database with the corresponding link.",
		"Error Viewing Thread",
		"Thread Doesn't Exist"
	);
}

// make sure forum is active...
if(!isActive($forumid) OR $foruminfo[$forumid]['is_category'] OR $foruminfo[$forumid]['link_redirect']) {
	doError(
		"The forum that this thread is located in, is not active, is a category, or is not a valid forum.",
		"Error Viewing Thread",
		"Invalid Forum (Category, not active)"
	);
}

// this next one is going to check permissions...
// what if thread is deleted?
if(!$forumPerms[$forumid]['can_view_board'] OR ($userinfo['userid'] != $threadinfo['thread_starter'] AND $userinfo['userid'] AND !$forumPerms[$forumid]['can_view_threads']) OR ($threadinfo['deleted_thread'] AND !$forumPerms[$forumid]['can_view_deletion'])) {
	doError(
		"perms",
		"Error Viewing Thread"
	);
}

// get moderator perms...
$moderator = hasModPermissions($forumid);

// if add poll.. redirect
if($_GET['do'] == "addPoll") {
	header("Location: makepoll.php?t=".$threadinfo['threadid']."&f=".$forumid);
	exit;
}

// voting on polls... yea we'll just do it right here in the thread.php
if($_POST['thePollOption'] OR is_array($_POST['pollOptions'])) {
	// select
	$pollinfo = query("SELECT * FROM poll WHERE pollid = '".$_POST['thePollID']."'",1);

	if(!$pollinfo['multiple']) {
		$optinfo = query("SELECT * FROM poll_options WHERE poll_optionid = '".$_POST['thePollOption']."'",1);
	}

	// make sure user hasn't voted before...
	$users = split(",",$pollinfo['voters']);

	$voted = false;

	foreach($users as $key => $username) {
		if(trim($username) == $userinfo['username']) {
			$voted = true;
			break;
		}
	}

	// make sure of perms...
	if($forumPerms[$forumid]['can_vote_polls'] AND $userinfo['userid'] AND !$threadinfo['closed'] AND $pollinfo['active'] AND !$voted) {
		if(!$pollinfo['multiple']) {
			if(!$optinfo['voters']) {
				$newVoters = $userinfo['username'];
			} else {
				$newVoters = $optinfo['voters'].",".$userinfo['username'];
			}

			$voteCounter = 1;

			// update the poll option...
			query("UPDATE poll_options SET votes = votes + 1 , voters = '".$newVoters."' WHERE poll_optionid = '".$_POST['thePollOption']."'");
		}

		else {
			// loop through selected poll options.. select... update
			if(is_array($_POST['pollOptions'])) {
				// start vote counter
				$voteCounter = 0;

				foreach($_POST['pollOptions'] as $optionid => $optionid2) {
					$optinfo = query("SELECT * FROM poll_options WHERE poll_optionid = '".$optionid."'",1);

					if(!$optinfo['voters']) {
						$newVoters = $userinfo['username'];
					} else {
						$newVoters = $optinfo['voters'].",".$userinfo['username'];
					}

					$voteCounter++;

					// update the poll option...
					query("UPDATE poll_options SET votes = votes + 1 , voters = '".$newVoters."' WHERE poll_optionid = '".$optionid."'");
				}
			}
		}

		if(!$pollinfo['voters']) {
			$wholePollVoters = $userinfo['username'];
		} else {
			$wholePollVoters = $pollinfo['voters'].",".$userinfo['username'];
		}

		// now update the poll itself
		query("UPDATE poll SET totalVotes = totalVotes + ".$voteCounter." , voters = '".$wholePollVoters."' WHERE pollid = '".$_POST['thePollID']."'");

		doThanks(
			"You have successfully placed your vote on the poll ".$pollinfo['pollid'].". You will now be redirected back to your previously visited page.",
			"Voting",
			"none",
			$_SERVER['HTTP_REFERER']
		);
	}

	// perms error
	else {
		doError(
			"perms",
			"Error Voting On Poll"
		);
	}
}

// moderator options...
if($_GET['do'] == "close") {
	// make sure proper permissions...
	if(($forumPerms[$forumid]['can_close_own'] AND $userinfo['userid'] == $threadinfo['thread_starter']) OR $moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_openClose_threads'])) {
		// close or open? 
		if($threadinfo['closed']) {
			query("UPDATE threads SET closed = 0 WHERE threadid = '".$threadinfo['threadid']."'");
			$open_or_close = "opened";
		} else {
			query("UPDATE threads SET closed = 1 WHERE threadid = '".$threadinfo['threadid']."'");
			$open_or_close = "closed";
		}

		doModLog("Opened/Closed Thread: ".htmlspecialchars($threadinfo['thread_name']));

		doThanks(
			"You have successfully ".$open_or_close." the thread entitled ".htmlspecialchars($threadinfo['thread_name']).". You will now be redirected back to your previously visited page.",
			"Moderating",
			"Opening/Closing Thread",
			$_SERVER['HTTP_REFERER']
		);
	}

	// permissions error!
	else {
		doError(
			"perms",
			"Error Moderating"
		);
	}
}

// move thread?
if($_GET['do'] == "move") {
	// make sure proper permissions...
	if($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_move'])) {
		// backend time.. Ug.. this one should be fun fun fun...
		if($_POST['moveOption']) {
			// what if forum is same as this one?
			if($_POST['f'] == $forumid) {
				doError(
					"You cannot Move/Copy a thread to the forum it exists in.",
					"Error Moving Thread",
					"Destination cannot be the current forum"
				);
			}			

			// make sure forum is active...
			if(!isActive($_POST['f']) OR $foruminfo[$_POST['f']]['is_category'] OR $foruminfo[$_POST['f']]['link_redirect']) {
				doError(
					"You cannot Move/Copy threads into an unactive forum or a category.",
					"Error Moving Thread",
					"Cannot move threads to an invalid forum"
				);
			}

			// copy... should be the easiest to do...
			if($_POST['moveOption'] == 1) {
				// do thread...
				query("INSERT INTO threads (forumid,thread_name,threadUsername,thread_starter,thread_views,thread_replies,last_reply_date,post_icon_thread,deleted_thread,closed,sticky,date_made,last_reply_username,moved,poll,deleted_by_thread,deleted_reason_thread,first_post,last_reply_userid,last_reply_postid,delete_time_thread) VALUES ('".$_POST['f']."','".addslashes($threadinfo['thread_name'])."','".$threadinfo['username']."','".$threadinfo['thread_starter']."','".$threadinfo['thread_views']."','".$threadinfo['thread_replies']."','".$threadinfo['last_reply_date']."','".$threadinfo['post_icon_thread']."','".$threadinfo['deleted_thread']."','".$threadinfo['closed']."','".$threadinfo['sticky']."','".$threadinfo['date_made']."','".$threadinfo['last_reply_username']."','".$threadinfo['moved']."','0','".$threadinfo['deleted_by_thread']."','".$threadinfo['deleted_reason_thread']."','".$threadinfo['first_post']."','".$threadinfo['last_reply_userid']."','".$threadinfo['last_reply_postid']."','".$threadinfo['delete_time_thread']."')");

				// new threadid...
				$newThreadID = mysql_insert_id();

				// do posts.. select them
				$postsToCopy = query("SELECT * FROM posts WHERE threadid = '".$threadinfo['threadid']."'");

				// now loop.. and reinsert with new threadid
				while($postinfo = mysql_fetch_array($postsToCopy)) {
					query("INSERT INTO posts (threadid,userid,postUsername,message,title,ip_address,date_posted,deleted,edited_by,forumid,post_icon,deleted_by,deleted_reason,show_sig,parse_smilies,parse_bbcode,edited_time,deleted_time) VALUES ('".$newThreadID."','".$postinfo['userid']."','".$postinfo['postUsername']."','".addslashes($postinfo['message'])."','".addslashes($postinfo['title'])."','".$postinfo['ip_address']."','".$postinfo['date_posted']."','".$postinfo['deleted']."','".$postinfo['edited_by']."','".$_POST['f']."','".$postinfo['post_icon']."','".$postinfo['deleted_by']."','".$postinfo['deleted_reason']."','".$postinfo['show_sig']."','".$postinfo['parse_smilies']."','".$postinfo['parse_bbcode']."','".$postinfo['edited_time']."','".$postinfo['deleted_time']."')");
				}

				// update threads first post...
				$findFirstPost = query("SELECT * FROM posts WHERE threadid = '".$newThreadID."' ORDER BY date_posted ASC",1);

				// re-update
				query("UPDATE threads SET first_post = '".$findFirstPost['postid']."' WHERE threadid = '".$newThreadID."'");

				// now check to update new forum information...
				if($foruminfo[$_POST['f']]['last_reply_date'] < $threadinfo['last_reply_date']) {
					// update
					query("UPDATE forums SET last_reply_username = '".$threadinfo['last_reply_username']."' , last_reply_userid = '".$threadinfo['last_reply_userid']."' , last_reply_date = '".$threadinfo['last_reply_date']."' , last_reply_threadid = '".$newThreadID."' , last_reply_threadtitle = '".addslashes($threadinfo['thread_name'])."' , threads = threads + 1 , posts = posts + '".($threadinfo['thread_replies'] + 1)."' WHERE forumid = '".$_POST['f']."'");
				} 

				// just update thread and post counts then...
				else {
					query("UPDATE forums SET threads = threads + 1 , posts = posts + '".($threadinfo['thread_replies'] + 1)."' WHERE forumid = '".$_POST['f']."'");
				}
			}

			// regular move thread... should actually be the easiest
			// actually i'm going to use this same block for use with redirects...
			else {
				// first update thread with new forum...
				query("UPDATE threads SET forumid = '".$_POST['f']."' WHERE threadid = '".$threadinfo['threadid']."'");

				// update post forum ids
				query("UPDATE posts SET forumid = '".$_POST['f']."' WHERE threadid = '".$threadinfo['threadid']."'");

				// decrement current forum count... and/or last reply crap
				if($foruminfo[$forumid]['last_reply_threadid'] == $threadinfo['threadid']) {
					// get latest thread for current forum...
					$latestThread = query("SELECT * FROM threads WHERE forumid = '".$forumid."' AND (deleted_thread = 0 OR deleted_thread IS NULL) AND threadid != '".$threadinfo['threadid']."' AND (moved = 0 OR moved IS NULL) ORDER BY last_reply_date DESC");

					// if no rows, set to null
					if(!mysql_num_rows($latestThread)) {
						query("UPDATE forums SET last_reply_date = null , last_reply_userid = null , last_reply_threadid = null , last_reply_threadtitle = null , last_reply_username = null , posts = posts - '".($threadinfo['thread_replies'] + 1)."' , threads = threads - 1 WHERE forumid = '".$forumid."'");
					}
					
					// else update with new info...
					else {
						// fetch arr
						$lThreadInfo = mysql_fetch_array($latestThread);

						query("UPDATE forums SET last_reply_date = '".$lThreadInfo['last_reply_date']."' , last_reply_userid = '".$lThreadInfo['last_reply_userid']."' , last_reply_threadid = '".$lThreadInfo['threadid']."' , last_reply_threadtitle = '".addslashes($lThreadInfo['thread_name'])."' , last_reply_username = '".$lThreadInfo['last_reply_username']."' , posts = posts - '".($threadinfo['thread_replies'] + 1)."' , threads = threads - 1 WHERE forumid = '".$forumid."'");
					}
				}

				// now just decrement counts.. as last reply crap isn't a factor
				else {
					query("UPDATE forums SET posts = posts - '".($threadinfo['thread_replies'] + 1)."' , threads = threads - 1 WHERE forumid = '".$forumid."'");
				}

				// now we need to edit the DESTINATION forum...
				if($foruminfo[$_POST['f']]['last_reply_date'] < $threadinfo['last_reply_date']) {
					// update...
					query("UPDATE forums SET last_reply_username = '".$threadinfo['last_reply_username']."' , last_reply_userid = '".$threadinfo['last_reply_userid']."' , last_reply_date = '".$threadinfo['last_reply_date']."' , last_reply_threadid = '".$threadinfo['threadid']."' , last_reply_threadtitle = '".addslashes($threadinfo['thread_name'])."' , threads = threads + 1 , posts = posts + '".($threadinfo['thread_replies'] + 1)."' WHERE forumid = '".$_POST['f']."'");
				}

				// just update thread and post counts then
				else {
					query("UPDATE forums SET threads = threads + 1 , posts = posts + '".($threadinfo['thread_replies'] + 1)."' WHERE forumid = '".$_POST['f']."'");
				}

				// ok... time for redirect...
				// we want to insert a thread, with CURRENT info.. only one change...
				// the "moved" field becomes the CURRENT threadid... so we can redirect
				// and also unsticky it, and unclose it...
				// only if we want redirect of course...
				if($_POST['moveOption'] == 3) {
					query("INSERT INTO threads (forumid,thread_name,threadUsername,thread_starter,thread_views,thread_replies,last_reply_date,post_icon_thread,deleted_thread,closed,sticky,date_made,last_reply_username,moved,poll,deleted_by_thread,deleted_reason_thread,first_post,last_reply_userid,last_reply_postid,delete_time_thread) VALUES ('".$forumid."','".addslashes($threadinfo['thread_name'])."','".$threadinfo['threadUsername']."','".$threadinfo['thread_starter']."','".$threadinfo['thread_views']."','".$threadinfo['thread_replies']."','".$threadinfo['last_reply_date']."','".$threadinfo['post_icon_thread']."','".$threadinfo['deleted_thread']."','0','0','".$threadinfo['date_made']."','".$threadinfo['last_reply_username']."','".$threadinfo['threadid']."','0','".$threadinfo['deleted_by_thread']."','".$threadinfo['deleted_reason_thread']."','".$threadinfo['first_post']."','".$threadinfo['last_reply_userid']."','".$threadinfo['last_reply_postid']."','".$threadinfo['delete_time_thread']."')");
				}


				// just to work with the redirect below...
				// don't get confused though, we don't change thread id's with this process...
				$newThreadID = $threadinfo['threadid'];
			}

			doThanks(
				"You have successfully Moved/Copied the thread entitled ".htmlspecialchars($threadinfo['thread_name']).". You will now be redirected to its new location.",
				"Moderating",
				"Moving/Copying Thread",
				"thread.php?t=".$newThreadID
			);
		}

		// GUI
		// deal with sessions
		$sessionInclude = doSessions("Moving/Copying Thread","Moving/Copying Thread");
		include("./includes/sessions.php");

		// create nav bar array
		$navbarArr = getForumNav($forumid);

		// reverse it... if array exists
		if(is_array($navbarArr)) {
			$navbarArr = array_reverse($navbarArr);
		}

		// add to it...
		$navbarArr[$foruminfo[$forumid]['forum_name']] = "forum.php?f=".$forumid;
		$navbarArr[htmlspecialchars($threadinfo['thread_name'])] = "thread.php?t=".$threadinfo['threadid'];
		$navbarArr['Move/Copy Thread'] = "#";

		$navbarText = getNavbarLinks($navbarArr);

		// build the forumlist...
		$forumList = buildForumSelection(-1);

		eval("\$threadMoveGUI = \"".getTemplate("threaddisplay_moderation_moveThread")."\";");

		// intialize templates
		eval("\$header = \"".getTemplate("header")."\";");
		eval("\$footer = \"".getTemplate("footer")."\";");

		// spit out content
		printTemplate($header);
		printTemplate($threadMoveGUI);
		printTemplate($footer);

		exit;
	}

	// perms error
	else {
		doError(
			"perms",
			"Error Moderating"
		);
	}
}

// stick?
if($_GET['do'] == "stick") {
	// make sure proper permissions...
	if($moderator) {
		// unstick or stick? 
		if($threadinfo['sticky']) {
			query("UPDATE threads SET sticky = 0 WHERE threadid = '".$threadinfo['threadid']."'");
			$stick_or_unstick = "unstuck";
		} else {
			query("UPDATE threads SET sticky = 1 WHERE threadid = '".$threadinfo['threadid']."'");
			$stick_or_unstick = "stuck";
		}

		doModLog("Stuck/Unstuck Thread: ".htmlspecialchars($threadinfo['thread_name']));

		doThanks(
			"You have successfully ".$stick_or_unstick." the thread entitled ".htmlspecialchars($threadinfo['thread_name']).". You will now be redirected back to your previously visited page.",
			"Moderating",
			"Sticking/Unsticking Thread",
			$_SERVER['HTTP_REFERER']
		);
	}

	// permissions error!
	else {
		doError(
			"perms",
			"Error Moderating"
		);
	}
}

// deleting thread...
if($_GET['do'] == "delete") {
	// make sure we have proper perms
	if(!$threadinfo['deleted_thread'] AND ($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit_threads']))) {
		// update...
		if($_POST['deleteOption']) {
			// format reason...
			$_POST['deleteReason'] = htmlspecialchars(addslashes($_POST['deleteReason']));

			// are we doing a regular delete?
			if($_POST['deleteOption'] == 1) {
				// update forum stuff now too...
				if($foruminfo[$forumid]['last_reply_threadid'] == $threadinfo['threadid']) {
					// find the latest thread, and re-insert info...
					$latestThread = query("SELECT * FROM threads WHERE forumid = '".$forumid."' AND last_reply_date < '".$threadinfo['last_reply_date']."' AND threadid != '".$threadinfo['threadid']."' AND deleted_thread = 0 ORDER BY last_reply_date DESC LIMIT 1");

					// if rows...
					if(mysql_num_rows($latestThread)) {
						$lastThreadInfo = mysql_fetch_array($latestThread);

						// re-update...
						query("UPDATE forums SET last_reply_username = '".$lastThreadInfo['last_reply_username']."' , last_reply_userid = '".$lastThreadInfo['last_reply_userid']."' , last_reply_date = '".$lastThreadInfo['last_reply_date']."' , last_reply_threadtitle = '".$lastThreadInfo['thread_name']."' , last_reply_threadid = '".$lastThreadInfo['threadid']."' , threads = threads - 1 , posts = posts - ".($threadinfo['thread_replies'] + 1)." WHERE forumid = '".$forumid."'");
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
				query("UPDATE user_info SET threads = threads - 1 WHERE userid = '".$threadinfo['thread_starter']."'");

				// now "delete" thread...
				query("UPDATE threads SET sticky = 0 , deleted_thread = 1 , deleted_by_thread = '".$userinfo['username']."' , deleted_reason_thread = '".$_POST['deleteReason']."' , delete_time_thread = '".time()."' WHERE threadid = '".$threadinfo['threadid']."'");

				doModLog("Deleted Thread: ".htmlspecialchars($threadinfo['thread_name']));

				doThanks(
					"Your changes have successfully been processed. You will now be redirected to the thread.",
					"Deleting Thread",
					"none",
					"thread.php?t=".$threadinfo['threadid']
				);
			}

			// perm delete...
			else {
				// make sure we have perms....
				if($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_permanently_delete'])) {
					// update forum stuff now too...
					if($foruminfo[$forumid]['last_reply_threadid'] == $threadinfo['threadid']) {
						// find the latest thread, and re-insert info...
						$latestThread = query("SELECT * FROM threads WHERE forumid = '".$forumid."' AND last_reply_date < '".$threadinfo['last_reply_date']."' AND threadid != '".$threadinfo['threadid']."' AND deleted_thread = 0 ORDER BY last_reply_date DESC LIMIT 1");

						// if rows...
						if(mysql_num_rows($latestThread)) {
							$lastThreadInfo = mysql_fetch_array($latestThread);

							// re-update...
							query("UPDATE forums SET last_reply_username = '".$lastThreadInfo['last_reply_username']."' , last_reply_userid = '".$lastThreadInfo['last_reply_userid']."' , last_reply_date = '".$lastThreadInfo['last_reply_date']."' , last_reply_threadtitle = '".addslashes($lastThreadInfo['thread_name'])."' , last_reply_threadid = '".$lastThreadInfo['threadid']."' , threads = threads - 1 , posts = posts - ".($threadinfo['thread_replies'] + 1)." WHERE forumid = '".$forumid."'");
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
					query("UPDATE user_info SET threads = threads - 1 WHERE userid = '".$threadinfo['thread_starter']."'");

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

					doModLog("Permanently Deleted Thread: ".htmlspecialchars($threadinfo['thread_name']));

					doThanks(
						"Your changes have successfully been processed. You will now be redirected to ".$foruminfo[$forumid]['forum_name'].".",
						"Deleting Thread",
						"none",
						"forum.php?f=".$forumid
					);
				}

				// perm error
				else {
					doError(
						"perms",
						"Error Moderating"
					);
				}
			}
		}

		// GUI
		// deal with sessions
		$sessionInclude = doSessions("Deleting Thread","none");
		include("./includes/sessions.php");

		// create nav bar array
		$navbarArr = getForumNav($forumid);

		// reverse it... if array exists
		if(is_array($navbarArr)) {
			$navbarArr = array_reverse($navbarArr);
		}

		// add to it...
		$navbarArr[$foruminfo[$forumid]['forum_name']] = "forum.php?f=".$forumid;
		$navbarArr[htmlspecialchars($threadinfo['thread_name'])] = "thread.php?t=".$threadinfo['threadid'];
		$navbarArr['Deleting Thread'] = "#";

		$navbarText = getNavbarLinks($navbarArr);

		// perm delete too?
		// threaddisplay_moderation_deleteThread_permDelete
		if($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_permanently_delete'] AND $modinfo[$forumid][$moderator]['can_edit_threads'])) {
			eval("\$permDelete = \"".getTemplate("threaddisplay_moderation_deleteThread_permDelete")."\";");
		} else {
			$permDelete = "";
		}

		eval("\$threadDeletionGUI = \"".getTemplate("threaddisplay_moderation_deleteThread")."\";");

		// intialize templates
		eval("\$header = \"".getTemplate("header")."\";");
		eval("\$footer = \"".getTemplate("footer")."\";");

		// spit out content
		printTemplate($header);
		printTemplate($threadDeletionGUI);
		printTemplate($footer);

		exit;
	}

	// perms error
	else {
		// if already deleted.. error!
		if($threadinfo['deleted_thread']) {
			doError(
				"You cannot delete a thread that's already been deleted.",
				"Error Deleting Thread",
				"Already Deleted"
			);
		}

		doError(
			"perms",
			"Error Moderating"
		);
	}
}

// what if we want to "edit" thread?
// basically it's just changing the post icon, and title...
if($_GET['do'] == "edit_thread") {
	// make sure we have proper perms
	if($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit_threads'])) {
		// update info in DB?
		if($_POST['threadTitle']) {
			// addslashes and strip HTML from threadTitle...
			$_POST['threadTitle'] = addslashes(htmlspecialchars($_POST['threadTitle']));

			// get the post icon...
			if($_POST['postIcon']) {
				$postIconImage = "<img src=\"".$postIconInfo[$_POST['postIcon']]['filepath']."\" alt=\"".$postIconInfo[$_POST['postIcon']]['title']."\" />";
			} else {
				$postIconImage = "";
			}

			// update DB...
			query("UPDATE threads SET thread_name = '".$_POST['threadTitle']."' , post_icon_thread = '".$postIconImage."' WHERE threadid = '".$threadinfo['threadid']."'");
			query("UPDATE forums SET last_reply_threadtitle = '".$_POST['threadTitle']."' WHERE forumid = '".$threadinfo['forumid']."' AND last_reply_threadid = '".$threadinfo['threadid']."'");

			doThanks(
				"You have successfully edited the thread entitled ".htmlspecialchars($threadinfo['thread_name']).". You will now be redirected back to ".htmlspecialchars($threadinfo['thread_name']),
				"Moderating",
				"Editing Thread Information",
				"thread.php?t=".$threadinfo['threadid']
			);

			doModLog("Edited Thread: ".htmlspecialchars($threadinfo['thread_name']));
		}

		// deal with sessions
		$sessionInclude = doSessions("Editing Thread Information","none");
		include("./includes/sessions.php");

		// create nav bar array
		$navbarArr = getForumNav($forumid);

		// reverse it... if array exists
		if(is_array($navbarArr)) {
			$navbarArr = array_reverse($navbarArr);
		}

		// add to it...
		$navbarArr[$foruminfo[$forumid]['forum_name']] = "forum.php?f=".$forumid;
		$navbarArr[htmlspecialchars($threadinfo['thread_name'])] = "thread.php?t=".$threadinfo['threadid'];
		$navbarArr['Edit Thread Information'] = "#";

		$navbarText = getNavbarLinks($navbarArr);

		if(empty($threadinfo['post_icon_thread'])) {
			$checked2 = " checked=\"checked\"";
		} else {
			$checked2 = "";
		}

		// get the post icons...
		if($foruminfo[$forumid]['allow_posticons'] OR $threadinfo['post_icon_thread']) {
			$postIcons = buildPostIcons(true);
		}

		// if thread is deleted, give them a chance to change the reason
		if($threadinfo['deleted_thread'] AND $forumPerms[$forumid]['can_view_deletion']) {
			eval("\$deletedThreadReason = \"".getTemplate("threaddisplay_moderation_editThread_reason")."\";");
		} else {
			$deletedThreadReason = "";
		}

		eval("\$threadInformation = \"".getTemplate("threaddisplay_moderation_editThread")."\";");

		// intialize templates
		eval("\$header = \"".getTemplate("header")."\";");
		eval("\$footer = \"".getTemplate("footer")."\";");

		// spit out content
		printTemplate($header);
		printTemplate($threadInformation);
		printTemplate($footer);

		exit;
	}

	// error perms!
	else {
		doError(
			"perms",
			"Error Moderating"
		);
	}
}

// ugg.. restore the thread.. lots of crap to do!
if($_GET['do'] == "restore") {
	// make sure we're allowed...
	if($forumPerms[$forumid]['can_view_deletion'] AND $threadinfo['deleted_thread'] AND (($forumPerms[$forumid]['can_delete_threads_own'] AND $userinfo['userid'] == $threadinfo['thread_starter'] AND $userinfo['userid']) OR $moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit_threads']))) {
		// alright.. do this process...
		// check for last post in forum first...
		if($foruminfo[$forumid]['last_reply_date'] < $threadinfo['last_reply_date']) {
			// update forums...
			query("UPDATE forums SET posts = posts + ".($threadinfo['thread_replies'] + 1)." , threads = threads + 1 , last_reply_username = '".$threadinfo['last_reply_username']."' , last_reply_userid = '".$threadinfo['last_reply_userid']."' , last_reply_date = '".$threadinfo['last_reply_date']."' , last_reply_threadid = '".$threadinfo['threadid']."' , last_reply_threadtitle = '".$threadinfo['thread_name']."' WHERE forumid = '".$forumid."'");
		}

		// just update post counts and such...
		else {
			query("UPDATE forums SET posts = posts + ".($threadinfo['thread_replies'] + 1)." , threads = threads + 1 WHERE forumid = '".$forumid."'");
		}

		// if count posts and not guest.. re-update thread start post count
		// we take it away when we delete thread...
		// and thread count!
		query("UPDATE user_info SET posts = posts + 1 , threads = threads + 1 WHERE userid = '".$threadinfo['thread_starter']."'");

		// now update the thread...
		query("UPDATE threads SET deleted_thread = 0 WHERE threadid = '".$threadinfo['threadid']."'");

		doModLog("Restored Thread: ".htmlspecialchars($threadinfo['thread_name']));

		doThanks(
			"You have successfully restored the thread entitled ".htmlspecialchars($threadinfo['thread_name']).". You will now be redirected back to your previously visited page.",
			"Restoring Thread",
			"none",
			$_SERVER['HTTP_REFERER']
		);
	}

	// error perms!
	else {
		doError(
			"perms",
			"Error Restoring Thread"
		);
	}
}

// subscribe
if($_GET['do'] == "subscribe") {
	// make sure logged in.. or perms error
	if(!$userinfo['userid']) {
		doError(
			"perms",
			"Error Subscribing to Thread"
		);
	}

	// good to go!
	else {
		// insert subscription
		query("INSERT INTO thread_subscription (userid,threadid) VALUES ('".$userinfo['userid']."','".$threadinfo['threadid']."')");

		doThanks(
			"You have successfully subscribed to the thread entitled ".htmlspecialchars($threadinfo['thread_name']).". You will now be redirected back to your previously visited page.",
			"Subscribing to Thread",
			"none",
			$_SERVER['HTTP_REFERER']
		);
	}
}

// unsubscribe
if($_GET['do'] == "unsubscribe") {
	// make sure logged in.. or perms error
	if(!$userinfo['userid']) {
		doError(
			"perms",
			"Error Unsubscribing to Thread"
		);
	}

	// good to go!
	else {
		// make sure subscription exists!
		$checkSubscription = query("SELECT COUNT(*) AS counting FROM thread_subscription WHERE userid = '".$userinfo['userid']."' AND threadid = '".$threadinfo['threadid']."' LIMIT 1",1);

		// if no rows.. uh oh!
		if(!$checkSubscription['counting']) {
			doError(
				"There is no subscription found in the database to unsubscribe to with the corresponding link.",
				"Error Unsubscribing to Thread",
				"No Subscription Exists"
			);
		}

		// good to go!
		else {
			// delete subscription
			query("DELETE FROM thread_subscription WHERE userid = '".$userinfo['userid']."' AND threadid = '".$threadinfo['threadid']."' LIMIT 1");

			doThanks(
				"You have successfully unsubscribed to the thread entitled ".htmlspecialchars($threadinfo['thread_name']).". You will now be redirected back to your previously visited page.",
				"Unsubscribing to Thread",
				"none",
				$_SERVER['HTTP_REFERER']
			);
		}
	}
}

// create nav bar array
$navbarArr = getForumNav($forumid);

// reverse it... if array exists
if(is_array($navbarArr)) {
	$navbarArr = array_reverse($navbarArr);
}

// add to it...
$navbarArr[$foruminfo[$forumid]['forum_name']] = "forum.php?f=".$forumid;
$navbarArr[htmlspecialchars($threadinfo['thread_name'])] = "#";

$navbarText = getNavbarLinks($navbarArr);

// deal with sessions
$sessionInclude = doSessions("Viewing Thread","Viewing Thread");
include("./includes/sessions.php");

if($userinfo['display_order'] != "ASC" AND $userinfo['display_order'] != "DESC") {
	$userinfo['display_order'] = "ASC";
}

// now we're going to query for all posts in this thread...
$allPosts = query("SELECT * FROM posts LEFT JOIN user_info ON user_info.userid = posts.userid WHERE threadid = '".$threadid."' ORDER BY date_posted ".$userinfo['display_order']);

// we must get how many we're showing though.. AND which ones...
$secPostCount = 1;
while($postinfo2 = mysql_fetch_array($allPosts)) {
	$postinfo[$postinfo2['postid']] = $postinfo2;
	$postinfo[$postinfo2['postid']]['postCounter'] = $secPostCount;

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

// it's been viewed.. so set cookie!
// get cookie name
$cookieName = "wtcBB_thread";

if($_GET['do'] == "newestpost") {
	// find newest post...
	// if nothing new, just go to last...
	if(($threadinfo['last_reply_date'] < $userinfo['lastvisit'] AND !$_COOKIE['wtcBB_thread'][$threadid]) OR ($_COOKIE['wtcBB_thread'][$threadid] AND $threadinfo['last_reply_date'] < $_COOKIE['wtcBB_thread'][$threadid])) {
		$theNewestPost = $threadinfo['last_reply_postid'];

		header("Location: thread.php?t=".$threadid."&p=".$theNewestPost."#".$theNewestPost);
	}

	else {
		// loop through postinfo to find newest post...
		foreach($postinfo as $postid => $arr) {
			// if the date posted is AFTER last activity (or cookie) then here's our post...
			if(($arr['date_posted'] > $userinfo['lastvisit'] AND !$_COOKIE['wtcBB_thread'][$threadid]) OR ($arr['date_posted'] > $_COOKIE['wtcBB_thread'][$threadid] AND $_COOKIE['wtcBB_thread'][$threadid])) {
				$theNewestPost = $arr['postid'];

				// now redirect!
				header("Location: thread.php?t=".$threadid."&p=".$theNewestPost."#".$theNewestPost);

				// break!
				break;
			}
		}
	}
}

// now get the amount of posts to show...
if($bboptions['max_posts'] != $userinfo['view_posts'] AND $userinfo['view_posts'] > 0) {
	$postNum = $userinfo['view_posts'];
} else {
	$postNum = $bboptions['max_posts'];
}

$page = $_GET['page'];

if($_GET['p'] AND !$page) {
	// find what page we're on...
	if($postinfo[$_GET['p']]['postCounter'] % $postNum != 0) {
		$page = ($postinfo[$_GET['p']]['postCounter'] / $postNum) + 1;
		settype($page, "integer");
	} else {
		$page = ($postinfo[$_GET['p']]['postCounter'] / $postNum);
	}
}

// array with all posts AND user info made...
// grab page...
if(!$page) {
	$page = 1;
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

// cache attachments...
$attachments = buildAttachments($threadinfo['threadid']);

// loop through posts...
foreach($postinfo as $postid => $arr) {
	// make sure we're displaying posts that we should be...
	// increment post counter
	if(!$arr['deleted']) {
		$postCounter++;
	}

	// make sure we're in right place...
	if($postCounter <= $start) {
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

	// unset all the links... and quickinfo
	unset(
		$editLink,
		$onlineOffline,
		$onlineOffline,
		$theAttachments,
		$attachmentbits
		);

	$postid = $arr['postid'];

	// process some dats...
	$registered = processDate($bboptions['date_register_format'],$arr['date_joined']);
	$datePosted = processDate($bboptions['date_formatted'],$arr['date_posted']);
	$timePosted = processDate($bboptions['date_time_format'],$arr['date_posted']);

	// get username
	if(!$arr['userid']) {
		$theUsername = $arr['postUsername'];
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

	$arr['message'] = parseMessage($arr['message'],$arr['parse_bbcode'],$arr['parse_smilies'],$foruminfo[$forumid]['allow_img'],(!$foruminfo['allow_html'] AND !$arr['allow_html']),$foruminfo[$forumid]['allow_wtcBB'],$foruminfo[$forumid]['allow_smilies'],$arr['username'],$arr);
	$arr['title'] = replaceReplacements(doCensors(htmlspecialchars($arr['title'])));

	// do highlight
	if($_GET['highlight']) {
		$arr['message'] = str_replace($_GET['highlight'],'<span style="background-color: #ffff00;">'.$_GET['highlight'].'</span>',$arr['message']);
	}

	if($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit']) OR ($userinfo['userid'] == $arr['userid'] AND $usergroupinfo[$arr['usergroupid']]['can_edit_own'] AND ($bboptions['edit_timeout'] OR ((time() - $bboptions['edit_timeout']) > $postinfo['date_posted'])))) {
		// edit template
		eval("\$editLink = \"".getTemplate("threaddisplay_postlinks_edit")."\";");
	}

	// get the online status...
	eval("\$onlineOffline = \"".getTemplate(fetchOnlineStatus($arr['userid']))."\";");

	// make attachments...
	if(is_array($attachments[$postid]) AND $userinfo['view_attachment'] AND $usergroupinfo[$userinfo['usergroupid']]['can_attachments']) {
		// loop through it and form template...
		foreach($attachments[$postid] as $attachid => $attachinfo) {
			eval("\$attachmentbits .= \"".getTemplate("threaddisplay_attach_bit")."\";");
		}

		eval("\$theAttachments = \"".getTemplate("threaddisplay_attach")."\";");
	} else {
		$theAttachments = "";
	}

	// format sig... only if use has set to display sig...
	if(!$arr['signature'] OR !$arr['show_sig'] OR !$bboptions['allow_signatures'] OR $arr['ban_sig'] OR !$userinfo['view_signature'] OR !$usergroupinfo[$arr['usergroupid']]['can_sig']) {
		$theSignature = "";
	}

	else {
		// one last thing... cut off sig to maximum amount..
		if($bboptions['maximum_signature'] > 0) {
			$arr['signature'] = trimString($arr['signature'],$bboptions['maximum_signature'],0);
		}

		$theSig = parseMessage($arr['signature'],$bboptions['allow_wtcBB_sig'],$bboptions['allow_smilies_sig'],$bboptions['allow_img_sig'],(!$bboptions['allow_html_sig'] AND !$arr['allow_html']),true,true,$arr['username']);
		eval("\$theSignature = \"".getTemplate("threaddisplay_signature")."\";");
	}

	// what about edited message?
	if($arr['edited_by'] AND $usergroupinfo[$arr['usergroupid']]['show_edited_notice'] AND $bboptions['show_edit_message']) {
		// format time and date...
		$dateEdited = processDate($bboptions['date_formatted'],$arr['edited_time']);
		$timeEdited = processDate($bboptions['date_time_format'],$arr['edited_time']);
	}

	// grab the template
	// deleted or not?
	if(!$arr['deleted']) {
		eval("\$postbits .= \"".getTemplate("threaddisplay_postbit")."\";");
	} else {
		if($forumPerms[$forumid]['can_view_deletion']) {
			$deletedDate = processDate($bboptions['date_formatted'],$arr['deleted_time']);
			$deletedTime = processDate($bboptions['date_time_format'],$arr['deleted_time']);

			eval("\$postbits .= \"".getTemplate("threaddisplay_deleted")."\";");
		}
	}
}

//if($moderator) {
	// open or close?
	if(($forumPerms[$forumid]['can_close_own'] AND $userinfo['userid'] == $threadinfo['thread_starter']) OR $moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_openClose_threads'])) {
		eval("\$modoption_openClose = \"".getTemplate("threaddisplay_modoptions_openclose")."\";");
	} else {
		$modoption_openClose = "";
	}

	// edit thread?
	if($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit_threads'])) {
		eval("\$modoption_editThread = \"".getTemplate("threaddisplay_modoptions_editThread")."\";");
	} else {
		$modoption_editThread = "";
	}

	// move thread?
	if($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_move'])) {
		eval("\$modoption_moveThread = \"".getTemplate("threaddisplay_modoptions_moveThread")."\";");
	} else {
		$modoption_moveThread = "";
	}

	// add poll?
	if(!$threadinfo['poll'] AND ($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit_polls']))) {
		eval("\$modoption_addPoll = \"".getTemplate("threaddisplay_modoptions_addPoll")."\";");
	} else {
		$modoption_addPoll = "";
	}

	// delete or restore?
	if(!$threadinfo['deleted_thread'] AND ($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit_threads']))) {
		eval("\$modoption_deleteThread = \"".getTemplate("threaddisplay_modoptions_deleteThread")."\";");
		$modoption_restoreThread = "";
	} 
	
	else if($forumPerms[$forumid]['can_view_deletion'] AND $threadinfo['deleted_thread'] AND ($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit_threads']))) {
		$modoption_deleteThread = "";
		eval("\$modoption_restoreThread = \"".getTemplate("threaddisplay_modoptions_restoreThread")."\";");
	} 
	
	else {
		$modoption_deleteThread = "";
		$modoption_restoreThread = "";
	}
/*} else {
	$theModOptions = "";
}*/

// get closed button?
if($threadinfo['closed']) {
	$openCloseImage = "postclosed.jpg";
	$openCloseAlt = "Closed Thread";
} else {
	$openCloseImage = "postreply.jpg";
	$openCloseAlt = "Post Reply";
}

// update thread views...
query("UPDATE threads SET thread_views = thread_views + 1 WHERE threadid = '".$threadinfo['threadid']."'");

// it's been viewed.. so set cookie!
if($threadinfo['last_reply_date'] > $userinfo['lastvist'] AND !$_COOKIE['wtcBB_thread'][$threadid]) {
	$_COOKIE['wtcBB_thread'][$threadid] = time();
	$cookies = serialize($_COOKIE['wtcBB_thread']);
	
	// set a cookie...
	setcookie('wtcBB_thread',$cookies,0,$bboptions['cookie_path'],$bboptions['cookie_domain']);
}

else if($_COOKIE['wtcBB_thread'][$threadid] AND $threadinfo['last_reply_date'] > $_COOKIE['wtcBB_thread'][$threadid]) {
	$_COOKIE['wtcBB_thread'][$threadid] = time();
	$cookies = serialize($_COOKIE['wtcBB_thread']);

	// set a cookie...
	setcookie($cookieName,$cookies,0,$bboptions['cookie_path'],$bboptions['cookie_domain']);
}

// do quick reply...
if($foruminfo[$forumid]['is_open'] AND !$userinfo['is_coppa'] AND $bboptions['enable_quick_reply'] AND (($forumPerms[$forumid]['can_reply_others'] AND $userinfo['userid'] != $threadinfo['thread_starter']) OR ($forumPerms[$forumid]['can_reply_own'] AND $userinfo['userid'] == $threadinfo['thread_starter']))) {
	if($bboptions['toolbar'] AND $userinfo['toolbar']) {
		eval("\$toolBar = \"".getTemplate("threaddisplay_quickreply_toolbar")."\";");
	} else {
		$toolBar = "";
	}
	
	eval("\$quickReply = \"".getTemplate("threaddisplay_quickreply")."\";");
} else {
	$quickReply = "";
}

// now we're going to get the users browsing this forum 
// if waranted of course...
if($bboptions['show_users_browsing_thread']) {
	// get the sessions... along with userinfo...
	$grabSessions = query("SELECT * FROM sessions LEFT JOIN user_info ON user_info.userid = sessions.userid WHERE location LIKE '%t=".$threadinfo['threadid']."%' ORDER BY user_info.username");

	$totalUsers = mysql_num_rows($grabSessions);

	$totalGuests = 0;

	// now form the online users...
	while($onlineuserinfo = mysql_fetch_array($grabSessions)) {
		// make sure not guest...
		if($onlineuserinfo['userid']) {
			$onlineUsers .= ', <a href="profile.php?u='.$onlineuserinfo['userid'].'">'.getHTMLUsername($onlineuserinfo).'</a>';
		}

		// guest counter goes up
		else {
			$totalGuests++;
		}
	}

	// to get registered users.. simply subtract guests from total...
	$totalRegisteredUsers = $totalUsers - $totalGuests;

	// get rid of first comma...
	$onlineUsers = preg_replace("|^, |","",$onlineUsers,1);

	// grab the template
	eval("\$usersBrowsing = \"".getTemplate("threaddisplay_browsingusers")."\";");
}

// polls?
if($threadinfo['poll']) {
	// find poll
	$findPoll = query("SELECT * FROM poll WHERE threadid = '".$threadinfo['threadid']."' LIMIT 1");

	// if rows.. press on and fetch arr
	if(mysql_num_rows($findPoll)) {
		// fetch arr
		$pollinfo = mysql_fetch_array($findPoll);

		// should we close poll?
		// first convert the timeout to seconds... (timeout is in days)
		$TIMESTAMP_timeout = time() + (60 * 60 * 24 * $pollinfo['timeout']);

		$active = true;

		// if thatis less than current time... close poll
		if($TIMESTAMP_timeout < time()) {
			query("UPDATE poll SET active = 0 WHERE pollid = '".$pollinfo['pollid']."'");

			$active = false;
		}

		// has curr user voted?
		$users = split(",",$pollinfo['voters']);

		$voted = false;

		foreach($users as $key => $username) {
			if(trim($username) == $userinfo['username']) {
				$voted = true;
				break;
			}
		}

		// find poll options
		$findPollOpts = query("SELECT * FROM poll_options WHERE pollid = '".$pollinfo['pollid']."' ORDER BY option_value ASC");

		// loop through them and do some math.. and call template
		// if rows
		if(mysql_num_rows($findPollOpts)) {
			while($optinfo = mysql_fetch_array($findPollOpts)) {
				// if voted.. or guest.. or show results
				if($voted OR !$userinfo['userid'] OR $_GET['do'] == "show_poll_results" OR !$pollinfo['active'] OR $threadinfo['closed'] OR !$active) {
					// avoid division by 0 :-x
					if($pollinfo['totalVotes'] > 0) {
						$percentage = 100 / $pollinfo['totalVotes'];
						$percentage = $optinfo['votes'] * $percentage;
						$percentage = ceil($percentage)."%";
						$realPercent = $percentage;

						if(!$optinfo['votes']) {
							$percentage = "0; visibility: hidden;";
						}
					} else {
						$percentage = "0; visibility: hidden;";
						$realPercent = "0";
					}

					// if public grab template for users...
					if($pollinfo['public']) {
						eval("\$usersVoted = \"".getTemplate("threaddisplay_poll_usersVoted")."\";");
					} else {
						$usersVoted = "";
					}

					// grab opt template
					eval("\$optbits .= \"".getTemplate("threaddisplay_poll_optbits_voted")."\";");
				}

				// GUI for voting...
				else {
					// what if multiple choice?
					if($pollinfo['multiple']) {
						eval("\$oneOrMulti = \"".getTemplate("threaddisplay_poll_multiple")."\";");
					} else {
						eval("\$oneOrMulti = \"".getTemplate("threaddisplay_poll_single")."\";");
					}

					eval("\$optbits .= \"".getTemplate("threaddisplay_poll_optbits_novote")."\";");
				}
			}
		}

		$votedWarning = "";
		$guestWarning = "";
		$closedWarning = "";
		$voteButton = "";
		$showOrNoShow = "";

		// warning for people who have voted...
		if($voted) {
			eval("\$votedWarning = \"".getTemplate("threaddisplay_poll_votedWarning")."\";");
		}

		// warning for guests
		else if(!$userinfo['userid'] OR !$forumPerms[$forumid]['can_vote_polls']) {
			eval("\$guestWarning = \"".getTemplate("threaddisplay_poll_guestWarning")."\";");
		} 

		// closed warning
		else if(!$pollinfo['active'] OR $threadinfo['closed'] OR !$active) {
			eval("\$closedWarning = \"".getTemplate("threaddisplay_poll_closedWarning")."\";");
		}

		// get vote button if no warnings....
		else if($_GET['do'] != "show_poll_results") {
			eval("\$showOrNoShow = \"".getTemplate("threaddisplay_poll_showResults")."\";");
			eval("\$voteButton = \"".getTemplate("threaddisplay_poll_voteButton")."\";");
		}

		// show or no show?
		else {
			if($_GET['do'] == "show_poll_results") {
				eval("\$showOrNoShow = \"".getTemplate("threaddisplay_poll_showNoResults")."\";");
			} else {
				eval("\$showOrNoShow = \"".getTemplate("threaddisplay_poll_showResults")."\";");
			}

			eval("\$spacer = \"".getTemplate("threaddisplay_poll_spacer")."\";");
		}

		// if rows for opts.. get the final template
		if(mysql_num_rows($findPollOpts)) {
			// edit poll?
			if($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit_polls'])) {
				eval("\$modPollOptions = \"".getTemplate("threaddisplay_poll_modOptions")."\";");
			} else {
				$modPollOptions = "";
			}

			// show public warning message?
			if($pollinfo['public']) {
				eval("\$publicWarning = \"".getTemplate("threaddisplay_poll_publicWarning")."\";");
			} else {
				$publicWarning = "";
			}

			eval("\$thePoll = \"".getTemplate("threaddisplay_poll")."\";");
		} else {
			$thePoll = "";
		}
	}

	else {
		$thePoll = "";
	}
} else {
	$thePoll = "";
}

// unsubscirbe, or subscribe?
$checkSubscription = query("SELECT COUNT(*) AS counting FROM thread_subscription WHERE userid = '".$userinfo['userid']."' AND threadid = '".$threadinfo['threadid']."' LIMIT 1",1);

if($checkSubscription['counting']) {
	// subscription!
	eval("\$subUsub = \"".getTemplate("threaddisplay_unsubscribe")."\";");
} else {
	// no subscription!
	eval("\$subUsub = \"".getTemplate("threaddisplay_subscribe")."\";");
}

// add poll link?
if(!$threadinfo['poll'] AND ($moderator === true OR ($moderator AND $modinfo[$forumid][$moderator]['can_edit_polls']) OR (($threadinfo['date_made'] > (time() - $bboptions['poll_timeout']) OR !$bboptions['poll_timeout']) AND $threadinfo['thread_starter'] == $userinfo['userid'] AND $userinfo['userid']))) {
	eval("\$addPollLink = \"".getTemplate("threaddisplay_addPollLink")."\";");
} else {
	$addPollLink = "";
}

$threadinfo['thread_name'] = htmlspecialchars($threadinfo['thread_name']);

// grab templates
eval("\$header = \"".getTemplate("header")."\";");
eval("\$threaddisplay = \"".getTemplate("threaddisplay")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

// spit them out
printTemplate($header);
printTemplate($threaddisplay);
printTemplate($footer);

// wraaaaap it up!
wrapUp();

?>