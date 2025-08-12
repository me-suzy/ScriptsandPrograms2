<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############### //ADMIN PANEL UPDATE INFO\\ ############### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

set_time_limit(0);

// define a few variables
$fileAction = "Updating Info";
$permissions = "updateinfo";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


// update attachments...
if($_GET['do'] == "attachments") {
	query("UPDATE attachments SET frontURI = CONCAT('attachment.php?id=',attachmentid) , attachmenturl = CONCAT('attachments/',attachmentid,'.wtcbb')");

	// redirect to thankyou page...
	redirect("thankyou.php?message=You have successfully updated all attachments. You will now be redirected back.&uri=updateinfo.php");
}

// update user titles
if($_GET['do'] == "usertitles") {
	// get all users
	query("UPDATE user_info SET usertitle_option = 0");
	$allUsers = query("SELECT * FROM user_info");

	// loop if rows
	if(mysql_num_rows($allUsers) > 0) {
		while($user = mysql_fetch_array($allUsers)) {
			if(!empty($usergroupinfo[$user['usergroupid']]['usertitle'])) {
				$theUserTitle = $usergroupinfo[$user['usergroupid']]['usertitle'];
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
						if($user['posts'] >= $arr['minimumposts'] AND $user['posts'] < $usertitles[($counter + 1)]['minimumposts']) {
							// yay!
							$theUserTitle = $arr['title'];
							break;
						}
					}
				}
			}

			// if it's still empty... set to "Registered Member".. since we have to be registered in order to be here
			if(empty($theUserTitle)) {
				$theUserTitle = "Registered Member";
			}

			// update
			query("UPDATE user_info SET usertitle = '".addslashes($theUserTitle)."' WHERE userid = '".$user['userid']."'");
		}
	}

	// redirect to thankyou page...
	redirect("thankyou.php?message=You have successfully updated all usertitles. You will now be redirected back.&uri=updateinfo.php");
}

// reset who's online record...
if($_GET['do'] == "whosonline") {
	// just one update and we're done!
	query("UPDATE wtcBBoptions SET record_date = 0 , record_num = 0");

	// redirect to thankyou page...
	redirect("thankyou.php?message=You have successfully reset the Whos Online Record. You will now be redirected back.&uri=updateinfo.php");
}

// update usernames
if($_GET['do'] == "usernames") {
	// go through all users and update...
	$allUsers = query("SELECT * FROM user_info ORDER BY userid ASC");

	while($user = mysql_fetch_array($allUsers)) {
		$user['username'] = addslashes(htmlspecialchars($user['username']));

		// update stuff
		query("UPDATE threads SET threadUsername = '".$user['username']."' WHERE thread_starter = '".$user['userid']."'");
		query("UPDATE posts SET postUsername = '".$user['username']."' WHERE userid = '".$user['userid']."'");
		query("UPDATE logged_ips SET username = '".$user['username']."' WHERE userid = '".$user['userid']."'");
		query("UPDATE log_moderator SET username = '".$user['username']."' WHERE userid = '".$user['userid']."'");
		query("UPDATE log_admin SET username = '".$user['username']."' WHERE userid = '".$user['userid']."'");
		query("UPDATE announcements SET username = '".$user['username']."' WHERE userid = '".$user['userid']."'");
		query("UPDATE admin_permissions SET username = '".$user['username']."' WHERE userid = '".$user['userid']."'");
		query("UPDATE forums SET last_reply_username = '".$user['username']."' WHERE last_reply_userid = '".$user['userid']."'");
		query("UPDATE threads SET last_reply_username = '".$user['username']."' WHERE last_reply_userid = '".$user['userid']."'");
	} // all done!

	// redirect to thankyou page...
	redirect("thankyou.php?message=You have successfully updated all username information. You will now be redirected back.&uri=updateinfo.php");
}

// update forum information
if($_GET['do'] == "forums") {
	// loop through forums
	foreach($foruminfo as $forumid => $arr) {
		// update post count
		$numOfPosts = query("SELECT COUNT(*) AS posts FROM posts WHERE forumid = '".$forumid."'",1);

		// update thread count
		$numOfThreads = query("SELECT COUNT(*) AS threads FROM threads WHERE forumid = '".$forumid."'",1);

		// find the latest thread, and re-insert info...
		$latestThread = query("SELECT * FROM threads WHERE forumid = '".$forumid."' AND (deleted_thread = 0 OR deleted_thread IS NULL) AND (moved = 0 OR moved IS NULL) ORDER BY last_reply_date DESC LIMIT 1");

		// if rows...
		if(mysql_num_rows($latestThread) > 0) {
			$lastThreadInfo = mysql_fetch_array($latestThread);

			// re-update...
			query("UPDATE forums SET last_reply_username = '".addslashes(htmlspecialchars($lastThreadInfo['last_reply_username']))."' , last_reply_userid = '".$lastThreadInfo['last_reply_userid']."' , last_reply_date = '".$lastThreadInfo['last_reply_date']."' , last_reply_threadtitle = '".addslashes(htmlspecialchars($lastThreadInfo['thread_name']))."' , last_reply_threadid = '".$lastThreadInfo['threadid']."' , threads = '".$numOfThreads['threads']."' , posts = '".$numOfPosts['posts']."' WHERE forumid = '".$forumid."'");
		} else {
			// set values to null...
			query("UPDATE forums SET last_reply_username = null , last_reply_userid = null , last_reply_date = null , last_reply_threadtitle = null , last_reply_threadid = null , threads = '".$numOfThreads['threads']."' , posts = '".$numOfPosts['posts']."' WHERE forumid = '".$forumid."'");
		}
	}

	// redirect to thankyou page...
	redirect("thankyou.php?message=You have successfully updated all forum information. You will now be redirected back.&uri=updateinfo.php");
}

// update thread information
if($_GET['do'] == "threads") {
	// get all threads
	$allThreads = query("SELECT * FROM threads");

	// loop if rows...
	if(mysql_num_rows($allThreads) > 0) {
		while($threadinfo = mysql_fetch_array($allThreads)) {
			// post count
			$numOfPosts = query("SELECT COUNT(*) AS numOfPosts FROM posts WHERE deleted = 0 AND threadid = '".$threadinfo['threadid']."'",1);

			$postBefore = query("SELECT * FROM posts LEFT JOIN user_info ON posts.userid = user_info.userid WHERE threadid = '".$threadinfo['threadid']."' AND deleted = 0 ORDER BY date_posted DESC LIMIT 1",1);

			$numOfPosts['numOfPosts'] = ($numOfPosts['numOfPosts'] == 0) ? 0 : $numOfPosts['numOfPosts'] - 1;

			// update
			query("UPDATE threads SET last_reply_date = '".$postBefore['date_posted']."' , last_reply_username = '".addslashes(htmlspecialchars($postBefore['username']))."' , last_reply_userid = '".$postBefore['userid']."' , last_reply_postid = '".$postBefore['postid']."' , thread_replies = '".$numOfPosts['numOfPosts']."' WHERE threadid = '".$threadinfo['threadid']."'");
		}
	}

	// redirect to thankyou page...
	redirect("thankyou.php?message=You have successfully updated all thread information. You will now be redirected back.&uri=updateinfo.php");
}

if($_GET['do'] == "threadPreviews") {
	$allThreads = query("SELECT * FROM threads");

	if(mysql_num_rows($allThreads) > 0) {
		while($threadinfo = mysql_fetch_array($allThreads)) {
			// get first post...
			$firstPost = query("SELECT postid,threadid,date_posted FROM posts WHERE threadid = '".$threadinfo['threadid']."' ORDER BY date_posted ASC LIMIT 1",1);

			// update
			query("UPDATE threads SET first_post = '".$firstPost['postid']."' WHERE threadid = '".$threadinfo['threadid']."'");
		}
	}

	// redirect to thankyou page...
	redirect("thankyou.php?message=You have successfully updated all thread preview information. You will now be redirected back.&uri=updateinfo.php");
}

// update user info
if($_GET['do'] == "users") {
	// get all users
	$allUsers = query("SELECT * FROM user_info");

	// loop if rows
	if(mysql_num_rows($allUsers) > 0) {
		while($user = mysql_fetch_array($allUsers)) {
			// new post count
			$numOfPosts = query("SELECT COUNT(*) AS numOfPosts FROM posts LEFT JOIN threads ON threads.threadid = posts.threadid LEFT JOIN forums ON threads.forumid = forums.forumid WHERE forums.count_posts = 1 AND posts.userid = '".$user['userid']."' AND posts.deleted = 0 AND threads.deleted_thread = 0",1);

			// new thread count
			$numOfThreads = query("SELECT COUNT(*) AS numOfThreads FROM threads LEFT JOIN forums ON threads.forumid = forums.forumid WHERE forums.count_posts = 1 AND threads.deleted_thread = 0 AND threads.thread_starter = '".$user['userid']."'",1);

			// get last post
			$lastPost = query("SELECT * FROM posts LEFT JOIN threads ON threads.threadid = posts.threadid WHERE threads.deleted_thread = 0 AND posts.userid = '".$user['userid']."' AND posts.deleted = 0 ORDER BY posts.date_posted DESC LIMIT 1");

			// if rows
			if(mysql_num_rows($lastPost)) {
				$lastPostInfo = mysql_fetch_array($lastPost);

				$extra = " , lastpost = '".$lastPostInfo['date_posted']."' , lastpostid = '".$lastPostInfo['postid']."'";
			}

			else {
				$extra = " , lastpost = null , lastpostid = null";
			}

			// run query to update
			query("UPDATE user_info SET posts = '".$numOfPosts['numOfPosts']."' , threads = '".$numOfThreads['numOfThreads']."'".$extra." WHERE userid = '".$user['userid']."'");
		}
	}

	// redirect to thankyou page...
	redirect("thankyou.php?message=You have successfully updated all user information. You will now be redirected back.&uri=updateinfo.php");
}

// do header
admin_header("wtcBB Admin Panel - Update Information");

construct_title("Update Information");

print('<div class="header" style="text-align: center;"><button type="submit" onclick="location.href=\'updateinfo.php?do=forums\';"'.$submitbg.'>Update Forum Information</button></div>');

print("<br /><br />");

print('<div class="header" style="text-align: center;"><button type="submit" onclick="location.href=\'updateinfo.php?do=threads\';"'.$submitbg.'>Update Thread Information</button></div>');

print("<br /><br />");

print('<div class="header" style="text-align: center;"><button type="submit" onclick="location.href=\'updateinfo.php?do=threadPreviews\';"'.$submitbg.'>Update Thread Preview Information</button></div>');

print("<br /><br />");

print('<div class="header" style="text-align: center;"><button type="submit" onclick="location.href=\'updateinfo.php?do=users\';"'.$submitbg.'>Update User Information</button><p class="small" style="margin-top: 0;"><span style="font-weight: bold; color: #bb0000;">Warning: </span>This will reset everyones post count to how many posts are currently in the database. It does the same for thread count.</p></div>');

print('<div class="header" style="text-align: center;"><button type="submit" onclick="location.href=\'updateinfo.php?do=usernames\';"'.$submitbg.'>Update Usernames</button></div>');

print("<br /><br />");

print('<div class="header" style="text-align: center;"><button type="submit" onclick="location.href=\'updateinfo.php?do=usertitles\';"'.$submitbg.'>Update Usertitles</button></div>');

print("<br /><br />");

print('<div class="header" style="text-align: center;"><button type="submit" onclick="location.href=\'updateinfo.php?do=attachments\';"'.$submitbg.'>Update Attachments</button></div>');

print("<br /><br />");

print('<div class="header" style="text-align: center;"><button type="submit" onclick="location.href=\'updateinfo.php?do=whosonline\';"'.$submitbg.'>Reset \'Whos Online\' Record</button></div>');

// do footer
admin_footer();

?>