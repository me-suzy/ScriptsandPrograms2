<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################## //FRONT END - INDEX\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_forums.php");
include("./global.php");

// get forum home stylesheet
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_forumhome")."\";");

// if no css file.. get internetl block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

// what if we view new posts?
if($_GET['do'] == "viewNewPosts") {
	// if no rows or guest.. spit out error
	if(!$userinfo['userid']) {
		doError(
			"You are a guest, we cannot track new messages for you.",
			"Error Viewing New Posts",
			"Viewer is a Guest"
		);
	}

	// get all threads that have a last reply day
	// after current user last visit
	$newThreads = query("SELECT * FROM threads LEFT JOIN user_info ON threads.thread_starter = user_info.userid LEFT JOIN posts ON threads.first_post = posts.postid WHERE last_reply_date > '".$userinfo['lastvisit']."' AND deleted_thread = 0 ORDER BY last_reply_date DESC");

	// if no rows or guest.. spit out error
	if(!mysql_num_rows($newThreads)) {
		doError(
			"There have been no new posts since your last visit.",
			"Error Viewing New Posts",
			"No New Posts"
		);
	}

	while($threadinfo = mysql_fetch_array($newThreads)) {
		// get the cookie name...
		$cookieName = "wtcBB_forumpass".$threadinfo['forumid'];

		if(($foruminfo[$threadinfo['forumid']]['fpassword'] AND !$_COOKIE['wtcBB_thread'][$threadinfo['threadid']]) OR $foruminfo[$threadinfo['forumid']]['is_category'] OR !$forumPerms[$threadinfo['forumid']]['can_view_board'] OR ($userinfo['userid'] != $threadinfo['thread_starter'] AND $userinfo['userid'] AND !$forumPerms[$threadinfo['forumid']]['can_view_threads']) OR ($threadinfo['deleted_thread'] AND !$forumPerms[$threadinfo['forumid']]['can_view_deletion'])) {
			continue;
		}

		if(($threadinfo['last_reply_date'] > $userinfo['lastvisit'] AND !$_COOKIE['wtcBB_thread'][$threadinfo['threadid']]) OR ($_COOKIE['wtcBB_thread'][$threadinfo['threadid']] AND $threadinfo['last_reply_date'] > $_COOKIE['wtcBB_thread'][$threadinfo['threadid']])) {
			// format username...
			if(!$threadinfo['username']) {
				$getUsername = $threadinfo['threadUsername'];
			} else {
				$getUsername = getHTMLUsername($threadinfo);
			}

			// threadid
			$threadid = $threadinfo['threadid'];

			// if not guest, no link...
			if($threadinfo['userid']) {
				$getUsername = '<a href="profile.php?u='.$threadinfo['userid'].'">'.$getUsername.'</a>';
			} else {
				$getUsername = $getUsername;
			}

			// get the dates for the last post...
			$lastPostDate = processDate($bboptions['date_formatted'],$threadinfo['last_reply_date']);
			$lastPostTime = processDate($bboptions['date_time_format'],$threadinfo['last_reply_date']);

			$prefix = "";

			if($threadinfo['sticky']) {
				$prefix .= unhtmlspecialchars($bboptions['pre_sticky'])." ";
			}

			if($threadinfo['closed']) {
				$prefix .= unhtmlspecialchars($bboptions['pre_closed'])." ";
			}

			// get the prefix..
			if($threadinfo['moved']) {
				$prefix .= unhtmlspecialchars($bboptions['pre_moved'])." ";

				// threadid
				$threadid = $threadinfo['moved'];

				$newPost = "";
			}

			if($threadinfo['poll']) {
				$prefix .= unhtmlspecialchars($bboptions['pre_poll'])." ";
			}

			$isSubscription = '';

			// create the thread preivew...
			$threadPreview = trimString(htmlspecialchars($threadinfo['message']),$bboptions['thread_preview_max']);

			// now we're going to take care of the folder icon... 
			// start the img src off...
			$folderIcon = "folder_";

			// add to foldericon...
			if($threadinfo['closed']) {
				$theAlt = "Thread is locked. ";
				$folderIcon .= "lock_";
			} else {
				$theAlt = "Thread is open. ";
				$folderIcon .= "nolock_";
			}

			// new?
			if(($stickyinfo['last_reply_date'] > $userinfo['lastvisit'] AND !$_COOKIE['wtcBB_thread'][$threadinfo['threadid']]) OR ($_COOKIE['wtcBB_thread'][$threadinfo['threadid']] AND $stickyinfo['last_reply_date'] > $_COOKIE['wtcBB_thread'][$threadinfo['threadid']])) {
				// so it's new.. add to folder icon...
				$theAlt .= "New posts. "; 
				$folderIcon .= "new_";

				// hot or not? (i'm hot ;))
				if($stickyinfo['thread_views'] >= $bboptions['hot_views'] OR $stickyinfo['thread_replies'] >= $bboptions['hot_replies']) {
					// add to folder icon...
					$theAlt .= "Hot thread.";
					$folderIcon .= "hot.gif";
				} else {
					// not hot! brrrrr
					$folderIcon .= "nohot.gif";
				}

				// while we're here.. we might as well get the new post icon...
				$newPost = '<a href="thread.php?t='.$stickyinfo['threadid'].'&amp;do=newestpost"><img src="'.$colors['images_folder'].'/newestpost.jpg" style="vertical-align: middle;" alt="Newest Post" /></a>';
			}

			// not new!
			else {
				$theAlt .= "No new posts. ";
				$folderIcon .= "nonew_";

				// hot or not? (i'm hot ;))
				if($stickyinfo['thread_views'] >= $bboptions['hot_views'] OR $stickyinfo['thread_replies'] >= $bboptions['hot_replies']) {
					// add to folder icon...
					$theAlt .= "Hot thread.";
					$folderIcon .= "hot.gif";
				} else {
					// not hot! brrrrr
					$folderIcon .= "nohot.gif";
				}

				$newPost = "";
			}

			// finish it up...
			$folderIcon = '<img src="'.$colors['images_folder'].'/'.$folderIcon.'" alt="'.$theAlt.'" />';

			// now get the amount of posts to show...
			if($bboptions['max_posts'] != $userinfo['view_posts'] AND $userinfo['view_posts'] > 0) {
				$postNum = $userinfo['view_posts'];
			} else {
				$postNum = $bboptions['max_posts'];
			}

			// get numOfPosts..
			$numOfPosts = $threadinfo['thread_replies'] + 1;

			if($numOfPosts % $postNum != 0) {
				$totalPages = ($numOfPosts / $postNum) + 1;
				settype($totalPages,"integer");
			} else {
				$totalPages = $numOfPosts / $postNum;
			}

			if($bboptions['multi_thread_links'] == 1) {
				// build the page links...
				$pageThreadID = $threadinfo['threadid'];
				$lastPostID = $threadinfo['last_reply_postid'];
				$pagelinks = buildPageLinks($totalPages,1,false,true,$threadinfo);
			} else {
				$pagelinks = "";
			}

			$forumName = $foruminfo[$threadinfo['forumid']]['forum_name'];

			// get the thread row template
			eval("\$threadrow .= \"".getTemplate("forumdisplay_threads_bit")."\";");
		}
	}

	if(!$threadrow) {
		// if no rows or guest.. spit out error
		doError(
			"There have been no new posts since your last visit.",
			"Error Viewing New Posts",
			"No New Posts"
		);
	}
	
	$pagelinks = '';

	// showthreads
	eval("\$showthreads = \"".getTemplate("search_threads")."\";");

	// intialize templates
	eval("\$header = \"".getTemplate("header")."\";");
	eval("\$footer = \"".getTemplate("footer")."\";");

	// spit it out
	printTemplate($header);
	printTemplate($showthreads);
	printTemplate($footer);

	// wrrrrrrrap it up!
	wrapUp();

	exit;
}

// what if we are marking forums read?
if($_GET['do'] == "markRead") {
	// update last visit...
	if($userinfo['userid']) {
		query("UPDATE user_info SET lastvisit = lastactivity , lastactivity = '".time()."' WHERE userid = '".$userinfo['userid']."'");

		// delete all cookies except ones having to do with login...
		foreach($_COOKIE as $name => $value) {
			// make sure we have right cookie...
			if(strpos($name,"wtcBB_") !== false AND $name != "wtcBB_Userid" AND $name != "wtcBB_Password" AND $name != "wtcBB_adminUsername" AND $name != "wtcBB_adminUserid" AND $name != "wtcBB_adminPassword" AND $name != "wtcBB_adminIsMod" AND $name != "wtcBB_prefs") {
				setcookie($name,"",time()-100000,$bboptions['cookie_path'],$bboptions['cookie_domain']);
			}
		}
	}

	doThanks(
		"All forums marked read. You will now be redirected to the page you viewed last.",
		"Marking Forums Read",
		"none",
		$_SERVER['HTTP_REFERER']
	);
}

// listed usergroups
if($_GET['do'] == "usergroups") {
	// loop through all usergroups...
	// just to make sure we have at least on valid usergroup...
	$usergroups = false;

	foreach($usergroupinfo as $usergroupid => $arr) {
		// if not viewable.. then move on
		if($arr['show_groups']) {
			$usergroups = true;
			break;
		}
	}

	// if it's still false.. then spit out error...
	if(!$usergroups) {
		doError(
			"There are no usergroups that have been allowed to be shown here.",
			"Error Viewing Listed Usergroups",
			"No usergroups available to show"
		);
	}

	// deal with sessions
	$sessionInclude = doSessions("Viewing Usergroups","Viewing Usergroups");
	include("./includes/sessions.php");

	// create nav bar array
	$navbarArr = Array(
		"Usergroups" => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	if($usergroupinfo[6]['show_groups']) {
		$allMods = query("
			SELECT * FROM moderators
			LEFT JOIN user_info
			ON moderators.userid = user_info.userid
			GROUP BY moderators.userid
			ORDER BY user_info.username ASC
		");

		if(mysql_num_rows($allMods)) {
			foreach($modinfo as $forumid => $mods) {
				foreach($mods as $modid => $mod) {
					$moderators[$mod['userid']][$modid . $forumid] = Array(
						'forum' => $foruminfo[$forumid]['forum_name'],
						'forumid' => $forumid
					);
				}
			}

			while($theuser = mysql_fetch_array($allMods)) {
				// if coppa move on
				if($theuser['is_coppa']) {
					continue;
				}

				$theusername = getHTMLUsername($theuser);
				$theCT = getCustomTitle($theuser);

				// get the online status...
				eval("\$onlineOffline = \"".getTemplate(fetchOnlineStatus($theuser['userid']))."\";");

				// do we want to use the separator?
				$userSep_1 = false;
				$userSep_2 = false;

				// display email?
				if($theuser['receive_emails'] AND $bboptions['enable_user_email']) {
					eval("\$sendEmail = \"".getTemplate("forumleaders_userbit_sendEmail")."\";");
					$userSep_1 = true;
				} else {
					$sendEmail = "";
				}

				// pm?
				if($theuser['use_pm'] AND $usergroupinfo[6]['personal_max_messages'] AND $bboptions['personal_enabled']) {
					eval("\$sendPM = \"".getTemplate("forumleaders_userbit_sendPM")."\";");
					$userSep_2 = true;
				} else {
					$sendPM = "";
				}

				if($userSep_1 AND $userSep_2) {
					eval("\$separator = \"".getTemplate("forumleaders_userbit_separator")."\";");
				} else {
					$separator = "";
				}

				$moderatedForums = '';

				foreach($moderators[$theuser['userid']] as $modid => $modd) {
					if(empty($modd['forum'])) {
						continue;
					}
					
					eval("\$moderatedForums .= \"".getTemplate("forumleaders_moderators_userbit_forum")."\";");
				}

				// get the user row...
				eval("\$userbits .= \"".getTemplate("forumleaders_moderators_userbit")."\";");
			}

			// get the usergroup template..
			eval("\$usergrouptables = \"".getTemplate("forumleaders_moderators")."\";");
		}
	}

	// movin' on!
	// loop through usergroups again...
	foreach($usergroupinfo as $usergroupid => $arr) {
		// if we don't want to show groups... move on...
		if(!$arr['show_groups'] OR $usergroupid == 6) {
			continue;
		}

		unset($userbits);
		
		// select all users belong to this usergroup...
		$users = query("SELECT * FROM user_info WHERE usergroupid = '".$usergroupid."' AND userid != 0 ORDER BY username");

		// if no rows... continue...
		if(!mysql_num_rows($users)) {
			continue;
		}

		while($theuser = mysql_fetch_array($users)) {
			// if coppa move on
			if($theuser['is_coppa']) {
				continue;
			}

			$theusername = getHTMLUsername($theuser);
			$theCT = getCustomTitle($theuser);

			// get the online status...
			eval("\$onlineOffline = \"".getTemplate(fetchOnlineStatus($theuser['userid']))."\";");

			// do we want to use the separator?
			$userSep_1 = false;
			$userSep_2 = false;

			// display email?
			if($theuser['receive_emails'] AND $bboptions['enable_user_email']) {
				eval("\$sendEmail = \"".getTemplate("forumleaders_userbit_sendEmail")."\";");
				$userSep_1 = true;
			} else {
				$sendEmail = "";
			}

			// pm?
			if($theuser['use_pm'] AND $usergroupinfo[$theuser['usergroupid']]['personal_max_messages'] AND $bboptions['personal_enabled']) {
				eval("\$sendPM = \"".getTemplate("forumleaders_userbit_sendPM")."\";");
				$userSep_2 = true;
			} else {
				$sendPM = "";
			}

			if($userSep_1 AND $userSep_2) {
				eval("\$separator = \"".getTemplate("forumleaders_userbit_separator")."\";");
			} else {
				$separator = "";
			}

			// get the user row...
			eval("\$userbits .= \"".getTemplate("forumleaders_userbit")."\";");
		}

		// get the usergroup template..
		eval("\$usergrouptables .= \"".getTemplate("forumleaders_usergroups")."\";");
	}

	// now grab all the templates...
	eval("\$header = \"".getTemplate("header")."\";");
	eval("\$forumleaders = \"".getTemplate("forumleaders")."\";");
	eval("\$footer = \"".getTemplate("footer")."\";");

	// spit it out
	printTemplate($header);
	printTemplate($forumleaders);
	printTemplate($footer);

	wrapUp();

	// exit!
	exit;
}

// deal with sessions
$sessionInclude = doSessions("Viewing Forum Index","Main Index");
include("./includes/sessions.php");

// deal with moderator column...
if($bboptions['show_mod_column']) {
	$colspan = 6;
	eval("\$moderator_column = \"".getTemplate("forumhome_moderator_column")."\";");
} else {
	$colspan = 5;
	$moderator_column = "";
}

// do who's online...
// theoretically, this could be place on any page
// and in the template use $whosonline as a variable to display it
// make sure $usergroupinfo and $userinfo arrays are set
// if they aren't, permissions can be screwed up!
// make sure it's enabled...
if($bboptions['display_loggedin_users']) {
	// record info
	// record info is selected from DB in sessions.php.. which is included in every file
	$recordOnline = $bboptions['record_num'];
	$recordDate = processDate($bboptions['date_formatted'],$bboptions['record_date']);
	$recordTime = processDate($bboptions['date_time_format'],$bboptions['record_date']);

	// buld array
	$sessions = buildSessionsArr();

	$totalRegisteredUsers = count($sessions);
	$totalGuests = count($sessArr) - $totalRegisteredUsers;

	if($totalGuests < 0) {
		$totalGuests = 0;
	}

	// get total users
	$totalUsers = $totalRegisteredUsers + $totalGuests;

	// get $onlineUsers
	if($sessions) {
		$x = 1;
		foreach($sessions as $userid => $onlineuserinfo) {
			// only if we have proper permissions
			if(!$onlineuserinfo['invisible'] OR $usergroupinfo[$userinfo['usergroupid']]['see_invisible'] OR $userinfo['userid'] == $onlineuserinfo['userid']) {
				// get colored username
				$onlineUsername = getHTMLUsername($onlineuserinfo);

				// get bit
				eval("\$onlineUsers .= \"".getTemplate("forumhome_whosonline_users")."\";");

				$x++;
			}
		}
	} else {
		// just give it a space...
		$onlineUsers = "&nbsp;";
	}

	eval("\$whosonline = \"".getTemplate("forumhome_whosonline")."\";");
}

$birthdays = '';

// birthdays
if($bboptions['display_birthdays']) {
	// get today's date...
	$todaysDate = date("n-d");
	$todaysDate2 = date("n-j");
	$userBirthBits = '';

	// get user's with birthdays today
	$todayBirthdays = query("SELECT * FROM user_info WHERE birthday LIKE '".$todaysDate."%' OR birthday LIKE '".$todaysDate2."%' ORDER BY username");

	// if rows, loop through and show template
	if(mysql_num_rows($todayBirthdays)) {
		$x = 1;

		while($birthdayinfo = mysql_fetch_array($todayBirthdays)) {
			if(!preg_match('#' . $todaysDate . '-#isU', $birthdayinfo['birthday'])) {
				continue;
			}

			// get colored username
			$birthdayUsername = getHTMLUsername($birthdayinfo);

			// figure out age using mktime
			// split
			$birthSplit = split("-",$birthdayinfo['birthday']);

			if($birthSplit[2]) {
				// subtract this year from that one
				if(strlen($birthSplit[2]) == 4) {
					$theAge = date("Y") - $birthSplit[2];
				}

				else if(strlen($birthSplit[2]) == 2) {
					$birthSplit[2] += 1900;
					$theAge = date("Y") - $birthSplit[2];
				}

				else {
					$theAge = null;
				}
			}

			else {
				$theAge = null;
			}					

			// get the bit
			eval("\$userBirthBits .= \"".getTemplate("forumhome_birthdays_bits")."\";");

			$x++;
		}

		$birthdaysToday = mysql_num_rows($todayBirthdays);

		if($x > 1) {
			// get whole template
			eval("\$birthdays = \"".getTemplate("forumhome_birthdays")."\";");
		}
	}
}

if($bboptions['forumStatsLevel']) {
	// total members
	$totalMembers = query("
	SELECT COUNT(*) AS total,
	username,
	userid
	FROM user_info
	WHERE username != 'Guest'
	GROUP BY username
	ORDER BY userid DESC
	");

	$totalThreads = query("
	SELECT COUNT(*)	AS total,
	SUM(IF(last_reply_date > '".$userinfo['lastvisit']."',1,0)) AS latestThreads
	FROM threads
	WHERE (deleted_thread = 0 OR deleted_thread IS NULL) AND (moved = 0 OR moved IS NULL)
	",1);

	$totalPosts = query("
	SELECT COUNT(*) AS total,
	SUM(IF(date_posted > '".$userinfo['lastvisit']."',1,0)) AS latestPosts
	FROM posts
	WHERE deleted = 0
	",1);

	$newUser = mysql_fetch_array($totalMembers);
	$totalUsers = mysql_num_rows($totalMembers);

	// hmmm this should fix an odd bug...
	if($totalThreads['latestThreads'] < 1 AND $totalPosts['latestPosts']) {
		$totalThreads['latestThreads'] = $totalPosts['latestPosts'];
	}

	eval("\$forumStats = \"".getTemplate("forumhome_stats_lite")."\";");
}

else {
	$forumStats = "";
}

// intialize variables
eval("\$header = \"".getTemplate("header")."\";");
eval("\$forumbits = \"".addslashes(buildForum(-1))."\";");
eval("\$forumhome = \"".getTemplate("forumhome")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

// spit out templates
printTemplate($header);
printTemplate($forumhome);
printTemplate($footer);

// wrrrrrrrap it up!
wrapUp();

?>