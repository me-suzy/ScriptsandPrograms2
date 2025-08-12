<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############## //FRONT END - FORUM DISPLAY\\ ############## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_forums.php");
include("./global.php");

// this function should take care of bad words!
function doCensors($text) {
	global $bboptions;

	// if censor disabled, or no censored words return the text untouched...
	if(!$bboptions['censor_enabled'] OR !$bboptions['censor_words']) {
		return $text;
	}

	// separate censored words..
	$censoredWords = split(" ",$bboptions['censor_words']);

	// loop through all censored words
	foreach($censoredWords as $index => $word) {
		// get length of word..
		$length = strlen($word);

		unset($replace);

		// make our censor
		for($x = 1; $x <= $length; $x++) {
			$replace .= $bboptions['censor_replace'];
		}

		// replace
		$text = preg_replace("|".$word."|i",$replace,$text);
	}

	// return the censored text
	return $text;
}

// get forum home stylesheet
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_forumhome")."\";");

// if no css file.. get internetl block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

$forumid = $_GET['f'];

check_wtcBB_forumPassword($forumid);

// link redirect?
if($foruminfo[$forumid]['link_redirect']) {
	header("Location: other.php?do=redirect&f=".$forumid."&uri=".$foruminfo[$forumid]['link_redirect']);
	exit;
}

// yikes! no forum exists!
if(!is_array($foruminfo[$forumid]) OR !isActive($forumid)) {
	doError(
		"There is no forum found in the database with the corresponding link, or this forum is not active.",
		"Error Viewing Forum",
		"Forum Doesn't Exist or isn't active"
	);
}

// check permissions...
if(!$forumPerms[$forumid]['can_view_board']) {
	doError(
		"perms",
		"Error Viewing Forum"
	);
}

// get moderator perms...
$moderator = hasModPermissions($forumid);

// remove redirects?
// basically.. it will just delete those "redirects"
// they have no standing or effect on a forum... so nothing too hard...
if($_GET['do'] == "remove_redirects") {
	// proper perms...
	if($moderator === true OR $modinfo[$forumid][$moderator]['can_edit_threads']) {
		// delete all redirects...
		query("DELETE FROM threads WHERE moved > 0 AND forumid = '".$forumid."'");

		doModLog("Removed Redirects in Forum: ".$foruminfo[$forumid]['forum_name']);

		doThanks(
			"You have successfully removed all redirects for the forum ".$foruminfo[$forumid]['forum_name'].". You will now be redirected back to your previously visited page.",
			"Moderating",
			"Removing Redirects",
			$_SERVER['HTTP_REFERER']
		);
	}

	// perms error
	else {
		doError(
			"perms",
			"Error Moderating"
		);
	}
}

// create nav bar array
$navbarArr = getForumNav($forumid);

// reverse it... if array exists
if(is_array($navbarArr)) {
	$navbarArr = array_reverse($navbarArr);
}

// add to it...
$navbarArr[$foruminfo[$forumid]['forum_name']] = "#";

$navbarText = getNavbarLinks($navbarArr);

// deal with sessions
$sessionInclude = doSessions("Viewing Forum","Viewing Forum");
include("./includes/sessions.php");

// globalize foruminfo vars.. for templates
foreach($foruminfo[$forumid] as $key => $value) {
	$$key = $value;
}

// only get subforums if there are any...
if($foruminfo[$forumid]['childlist']) {
	eval("\$forumbits = \"".addslashes(buildForum($foruminfo[$forumid]['forumid'],$foruminfo[$forumid]['depth']))."\";");

	if($forumbits) {
		eval("\$subforumtable = \"".getTemplate("forumdisplay_subforumtable")."\";");
	}
}

// de-htmlize prefix..
$bboptions['pre_sticky'] = unhtmlspecialchars($bboptions['pre_sticky']);
$bboptions['pre_closed'] = unhtmlspecialchars($bboptions['pre_closed']);
$bboptions['pre_moved'] = unhtmlspecialchars($bboptions['pre_moved']);
$bboptions['pre_poll'] = unhtmlspecialchars($bboptions['pre_poll']);

// get title for use in templates
$forumName = $foruminfo[$forumid]['forum_name'];

// if default_view for forum is 0
// revert to board default in wtcBBoptions
if(isset($_GET['viewAge'])) {
	$threadCutOff = $_GET['viewAge'];
} 

else if(!$foruminfo[$forumid]['default_view'] AND $userinfo['date_default_thread_age'] != -1) {
	if($userinfo['date_default_thread_age'] == 1) {
		$threadCutOff = 1;
	} else if($userinfo['date_default_thread_age'] == 2) {
		$threadCutOff = 2;
	} else if($userinfo['date_default_thread_age'] == 3) {
		$threadCutOff = 7;
	} else if($userinfo['date_default_thread_age'] == 4) {
		$threadCutOff = 14;
	} else if($userinfo['date_default_thread_age'] == 5) {
		$threadCutOff = 30;
	} else if($userinfo['date_default_thread_age'] == 6) {
		$threadCutOff = 45;
	} else if($userinfo['date_default_thread_age'] == 7) {
		$threadCutOff = 60;
	} else if($userinfo['date_default_thread_age'] == 8) {
		$threadCutOff = 75;
	} else if($userinfo['date_default_thread_age'] == 9) {
		$threadCutOff = 100;
	} else if($userinfo['date_default_thread_age'] == 10) {
		$threadCutOff = 180;
	} else if($userinfo['date_default_thread_age'] == 11) {
		$threadCutOff = 365;
	} else {
		$threadCutOff = "";
	}
}

else if($userinfo['date_default_thread_age'] == -1 AND !$foruminfo[$forumid]['default_view']) {
	if(!$bboptions['date_default_thread_age']) {
		$threadCutOff = 1;
	} else if($bboptions['date_default_thread_age'] == 1) {
		$threadCutOff = 2;
	} else if($bboptions['date_default_thread_age'] == 2) {
		$threadCutOff = 7;
	} else if($bboptions['date_default_thread_age'] == 3) {
		$threadCutOff = 14;
	} else if($bboptions['date_default_thread_age'] == 4) {
		$threadCutOff = 30;
	} else if($bboptions['date_default_thread_age'] == 5) {
		$threadCutOff = 45;
	} else if($bboptions['date_default_thread_age'] == 6) {
		$threadCutOff = 60;
	} else if($bboptions['date_default_thread_age'] == 7) {
		$threadCutOff = 75;
	} else if($bboptions['date_default_thread_age'] == 8) {
		$threadCutOff = 100;
	} else if($bboptions['date_default_thread_age'] == 9) {
		$threadCutOff = 180;
	} else if($bboptions['date_default_thread_age'] == 10) {
		$threadCutOff = 365;
	} else {
		$threadCutOff = "";
	}
} 

else {
	$threadCutOff = $foruminfo[$forumid]['default_view'];
}

$viewAge1 = "";
$viewAge2 = "";
$viewAge3 = "";
$viewAge4 = "";
$viewAge5 = "";
$viewAge6 = "";
$viewAge7 = "";
$viewAge8 = "";
$viewAge9 = "";
$viewAge10 = "";
$viewAge11 = "";
$viewAge12 = "";

// get the viewage selection...
if($threadCutOff == 1) {
	$viewAge1 = ' selected="selected"';
} else if($threadCutOff >= 2 AND $threadCutOff < 7) {
	$viewAge2 = ' selected="selected"';
} else if($threadCutOff >= 7 AND $threadCutOff < 14) {
	$viewAge3 = ' selected="selected"';
} else if($threadCutOff >= 14 AND $threadCutOff < 30) {
	$viewAge4 = ' selected="selected"';
} else if($threadCutOff >= 30 AND $threadCutOff < 45) {
	$viewAge5 = ' selected="selected"';
} else if($threadCutOff >= 45 AND $threadCutOff < 60) {
	$viewAge6 = ' selected="selected"';
} else if($threadCutOff >= 60 AND $threadCutOff < 75) {
	$viewAge7 = ' selected="selected"';
} else if($threadCutOff >= 75 AND $threadCutOff < 100) {
	$viewAge8 = ' selected="selected"';
} else if($threadCutOff >= 100 AND $threadCutOff < 180) {
	$viewAge9 = ' selected="selected"';
} else if($threadCutOff >= 180 AND $threadCutOff < 365) {
	$viewAge10 = ' selected="selected"';
} else if($threadCutOff == 365 OR $threadCutOff == 366) {
	$viewAge11 = ' selected="selected"';
} else {
	$viewAge12 = ' selected="selected"';
}

// form the full text...
if($threadCutOff) {
	$threadCutOff = " AND last_reply_date >= '".(time() - (86400 * $threadCutOff))."'";
} else {
	$threadCutOff = "";
}

$page = $_REQUEST['page'];

if(!$page) {
	$page = 1;
}

// if it's less than 0, set end to 1.. someone messed up
if($bboptions['maximum_threads'] <= 0) {
	$bboptions['maximum_threads'] = 1;
}

$end = $bboptions['maximum_threads'];

// let's get the start... before we get the REAL end!
$start = ($page - 1) * $end;

// now get the REAL end...
$end *= $page;

// get the sort by
if(!$sortBy) {
	$sortBy = "last_reply_date";
}

// now get the selected
$sortBy1 = "";
$sortBy2 = "";
$sortBy3 = "";
$sortBy4 = "";
$sortBy5 = "";

if($sortBy == "threads.thread_name") {
	$sortBy2 = ' selected="selected"';
} else if($sortBy == "user_info.username") {
	$sortBy3 = ' selected="selected"';
} else if($sortBy == "threads.thread_views") {
	$sortBy4 = ' selected="selected"';
} else if($sortBy == "threads.thread_replies") {
	$sortBy5 = ' selected="selected"';
} else {
	$sortBy = "last_reply_date";
	$sortBy1 = ' selected="selected"';
}

// order
$descAsc1 = "";
$descAsc2 = "";

if($descAsc == "ASC") {
	$descAsc1 = ' selected="selected"';
} else {
	$descAsc2 = ' selected="selected"';
}

// set to descending if isn't set
if(!$descAsc) {
	$descAsc = "DESC";
}

// get ALLLLLLLL threads (except sticky)... we will deal with permissions and such 
// as we go through each thread... 
$allTheThreads = query("SELECT * FROM threads LEFT JOIN user_info ON threads.thread_starter = user_info.userid LEFT JOIN posts ON threads.first_post = posts.postid WHERE threads.forumid = '".$forumid."'".$threadCutOff." AND threads.sticky = 0 ORDER BY ".$sortBy." ".$descAsc);

// get stuck threads...
$stuckThreads = query("SELECT * FROM threads LEFT JOIN user_info ON threads.thread_starter = user_info.userid LEFT JOIN posts ON threads.first_post = posts.postid WHERE threads.forumid = '".$forumid."'".$threadCutOff." AND threads.sticky = 1 ORDER BY ".$sortBy." ".$descAsc);

// get all threads.. no limit
$numOfThreads = mysql_num_rows($allTheThreads) + mysql_num_rows($stuckThreads);

// cache subscriptions...
$getSubscriptions = query("SELECT * FROM thread_subscription WHERE userid = '".$userinfo['userid']."' ORDER BY threadid");

// put into array...
if(mysql_num_rows($getSubscriptions)) {
	while($cachesubscriptions = mysql_fetch_array($getSubscriptions)) {
		$subscribes[$cachesubscriptions['threadid']] = $cachesubscriptions;
	}
}

// start the limit counter
$limitCounter = 0;

// loop through them...
if(mysql_num_rows($stuckThreads)) {
	while($stickyinfo = mysql_fetch_array($stuckThreads)) {
		// first we must do the limit counter.. 
		// make sure we're supposed to be showing this thread...
		// basically, same exact method used in online.php
		$limitCounter++;
		
		// this part is neglegent if we want sticky's on all pages...
		if(!$bboptions['show_sticky_all']) {
			if($limitCounter <= $start) {
				// we could still have threads to show so press on!
				continue;
			}

			if($limitCounter > $end) {
				// umm.. no where else to go.. so end
				break;
			}
		}

		// format username...
		if(!$stickyinfo['username']) {
			$getUsername = $stickyinfo['threadUsername'];
		} else {
			$getUsername = getHTMLUsername($stickyinfo);
		}

		// if not guest, no link...
		if($stickyinfo['userid']) {
			$getUsername = '<a href="profile.php?u='.$stickyinfo['userid'].'">'.$getUsername.'</a>';
		} else {
			$getUsername = $getUsername;
		}

		// get the dates for the last post...
		$lastPostDate = processDate($bboptions['date_formatted'],$stickyinfo['last_reply_date']);
		$lastPostTime = processDate($bboptions['date_time_format'],$stickyinfo['last_reply_date']);

		$prefix = "";

		if($stickyinfo['closed']) {
			$prefix .= $bboptions['pre_closed']." ";
		}

		// get the prefix..
		if($stickyinfo['moved']) {
			$prefix .= $bboptions['pre_moved']." ";
		}

		if($stickyinfo['poll']) {
			$prefix .= $bboptions['pre_poll']." ";
		}

		// create the thread preivew...
		$threadPreview = trimString(htmlspecialchars(doCensors($stickyinfo['message'])),$bboptions['thread_preview_max']);

		// now we're going to take care of the folder icon... 
		// start the img src off...
		$folderIcon = "folder_";

		// add to foldericon...
		if($stickyinfo['closed']) {
			$theAlt = "Thread is locked. ";
			$folderIcon .= "lock_";
		} else {
			$theAlt = "Thread is open. ";
			$folderIcon .= "nolock_";
		}

		// new?
		if(($stickyinfo['last_reply_date'] > $userinfo['lastvisit'] AND !$_COOKIE['wtcBB_thread'][$stickyinfo['threadid']]) OR ($_COOKIE['wtcBB_thread'][$stickyinfo['threadid']] AND $stickyinfo['last_reply_date'] > $_COOKIE['wtcBB_thread'][$stickyinfo['threadid']])) {
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
		$numOfPosts = $stickyinfo['thread_replies'] + 1;

		if($numOfPosts % $postNum != 0) {
			$totalPages = ($numOfPosts / $postNum) + 1;
			settype($totalPages,"integer");
		} else {
			$totalPages = $numOfPosts / $postNum;
		}

		if($bboptions['multi_thread_links'] == 1) {
			// build the page links...
			$pageThreadID = $stickyinfo['threadid'];
			$lastPostID = $stickyinfo['last_reply_postid'];
			$pagelinks = buildPageLinks($totalPages,1,false,true,$stickyinfo);
		} else {
			$pagelinks = "";
		}

		$stickyinfo['thread_name'] = htmlspecialchars(doCensors($stickyinfo['thread_name']));

		// get the thread row template
		eval("\$threadrowStuck .= \"".getTemplate("forumdisplay_threads_stickybit")."\";");
	}
}

// loop through them...
if(mysql_num_rows($allTheThreads)) {
	while($threadinfo = mysql_fetch_array($allTheThreads)) {
		// first we must do the limit counter.. 
		// make sure we're supposed to be showing this thread...
		// basically, same exact method used in online.php
		$limitCounter++;
		
		if($limitCounter <= $start) {
			// we could still have threads to show so press on!
			continue;
		}

		if($limitCounter > $end) {
			// umm.. no where else to go.. so end
			break;
		}

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

		if($threadinfo['closed']) {
			$prefix .= $bboptions['pre_closed']." ";
		}

		// get the prefix..
		if($threadinfo['moved']) {
			$prefix .= $bboptions['pre_moved']." ";

			// threadid
			$threadid = $threadinfo['moved'];

			$newPost = "";
		}

		if($threadinfo['poll']) {
			$prefix .= $bboptions['pre_poll']." ";
		}

		// create the thread preivew...
		preg_match('/(.+?)(\r|\r\n|\n)/s', htmlspecialchars(doCensors(stripBBCode($threadinfo['message']))), $matches);
		$threadPreview = $matches[1];

		// now we're going to take care of the folder icon... 
		// start the img src off...
		$folderIcon = "folder_";

		// add to foldericon...
		if($threadinfo['closed'] == 1) {
			$theAlt = "Thread is locked. ";
			$folderIcon .= "lock_";
		} else {
			$theAlt = "Thread is open. ";
			$folderIcon .= "nolock_";
		}

		// new?
		if(($threadinfo['last_reply_date'] > $userinfo['lastvisit'] AND !$_COOKIE['wtcBB_thread'][$threadinfo['threadid']]) OR ($_COOKIE['wtcBB_thread'][$threadinfo['threadid']] AND $threadinfo['last_reply_date'] > $_COOKIE['wtcBB_thread'][$threadinfo['threadid']])) {
			// so it's new.. add to folder icon...
			$theAlt .= "New posts. "; 
			$folderIcon .= "new_";

			// hot or not? (i'm hot ;))
			if($threadinfo['thread_views'] >= $bboptions['hot_views'] OR $threadinfo['thread_replies'] >= $bboptions['hot_replies']) {
				// add to folder icon...
				$theAlt .= "Hot thread.";
				$folderIcon .= "hot.gif";
			} else {
				// not hot! brrrrr
				$folderIcon .= "nohot.gif";
			}

			// while we're here.. we might as well get the new post icon...
			$newPost = '<a href="thread.php?t='.$threadid.'&amp;do=newestpost"><img src="'.$colors['images_folder'].'/newestpost.jpg" style="vertical-align: middle;" alt="Newest Post" /></a>';
		}

		// not new!
		else {
			$theAlt .= "No new posts. ";
			$folderIcon .= "nonew_";

			// hot or not? (i'm hot ;))
			if($threadinfo['thread_views'] >= $bboptions['hot_views'] OR $threadinfo['thread_replies'] >= $bboptions['hot_replies']) {
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
		
		$threadinfo['thread_name'] = htmlspecialchars(doCensors($threadinfo['thread_name']));

		// is it deleted?
		if($threadinfo['deleted_thread']) {
			if($forumPerms[$forumid]['can_view_deletion']) {
				// different template than normal...
				eval("\$threadrow .= \"".getTemplate("forumdisplay_threads_deleted")."\";");
			}
		} else {
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

			// get the thread row template
			eval("\$threadrow .= \"".getTemplate("forumdisplay_threads_bit")."\";");
		}
	}
}

if($numOfThreads % $bboptions['maximum_threads'] != 0) {
	$totalPages = ($numOfThreads / $bboptions['maximum_threads']) + 1;
	settype($totalPages,"integer");
} else {
	$totalPages = $numOfThreads / $bboptions['maximum_threads'];
}

// build the page links...
$pagelinks = buildPageLinks($totalPages,$page);

// build the moderator list...
// we already have an array filled with ALL mods..
// just loop through, and get the ones belonging to this forum
if(is_array($modinfo[$forumid])) {
	foreach($modinfo[$forumid] as $id => $arr) {
		// form the list...
		$modList .= ', <a href="profile.php?u='.$arr['userid'].'">'.getHTMLUsername($arr).'</a>';
	}
}

// now get rid of the first comma
$modList = preg_replace("|^, |","",$modList,1);

if($numOfThreads) {
	// do announcements
	// should we show all, or just the most recent?
	if(!$bboptions['show_all_announcements']) {
		// get the most recent announcement...
		$getAnnouncements = query("SELECT * FROM announcements LEFT JOIN user_info ON announcements.userid = user_info.userid WHERE start_date < '".time()."' AND end_date > '".time()."' AND forum = '".$forumid."' OR forum = -1 ORDER BY date_addedUpdated DESC LIMIT 1");

		// ok, we have our most recent announcement... along with the userinfo of the user who made it...
		// make sure there are rows, and fetch the array...
		if(mysql_num_rows($getAnnouncements)) {
			// get the announcement header template...
			eval("\$announceheader = \"".getTemplate("forumdisplay_announceheader")."\";");

			$announceinfo = mysql_fetch_array($getAnnouncements);

			// get the username
			$postedUsername = getHTMLUsername($announceinfo);

			// get the usertitle
			$postedUsertitle = getCustomTitle($announceinfo);

			// now get the date
			$theDate = processDate($bboptions['date_formatted'],$announceinfo['date_addedUpdated']);

			// call the template
			eval("\$announcebit = \"".getTemplate("forumdisplay_announcebit")."\";");
		}
	}

	// otherwise, we're going to be showing ALL announcements...
	else {
		// get the announcements... sort by most recent...
		$getAnnouncements = query("SELECT * FROM announcements LEFT JOIN user_info ON announcements.userid = user_info.userid WHERE start_date < '".time()."' AND end_date > '".time()."' AND (forum = '".$forumid."' OR forum = -1) ORDER BY date_addedUpdated DESC");

		// ok, we have all our announcements... along with the userinfo of the user who made it...
		// make sure there are rows, and fetch the array...
		if(mysql_num_rows($getAnnouncements)) {
			// get the announcement header template...
			eval("\$announceheader = \"".getTemplate("forumdisplay_announceheader")."\";");

			while($announceinfo = mysql_fetch_array($getAnnouncements)) {
				// get the username
				$postedUsername = getHTMLUsername($announceinfo);

				// get the usertitle
				$postedUsertitle = getCustomTitle($announceinfo);

				// now get the date
				$theDate = processDate($bboptions['date_formatted'],$announceinfo['date_addedUpdated']);

				// form the bits together
				eval("\$announcebit .= \"".getTemplate("forumdisplay_announcebit")."\";");
			}
		}
	}

	// now get the show thread template.. if this forum isn't a cat
	if(!$foruminfo[$forumid]['is_category']) {
		eval("\$showthreads = \"".getTemplate("forumdisplay_threads")."\";");
	}
}

// now we're going to get the users browsing this forum 
// if waranted of course...
if($bboptions['show_users_browsing_forum']) {
	// get the sessions... along with userinfo...
	$grabSessions = query("SELECT * FROM sessions LEFT JOIN user_info ON user_info.userid = sessions.userid WHERE location LIKE '%f=".$forumid."%' ORDER BY user_info.username");

	$totalUsers = mysql_num_rows($grabSessions);

	$totalGuests = 0;

	// now form the online users...
	while($onlineuserinfo = mysql_fetch_array($grabSessions)) {
		// make sure not guest...
		if($onlineuserinfo['userid'] != 0) {
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
	eval("\$usersBrowsing = \"".getTemplate("forumdisplay_browsingusers")."\";");
}

// now do the permissions rules.. just a crap load of IFs
if($foruminfo[$forumid]['is_category'] OR !$foruminfo[$forumid]['is_active'] OR !$foruminfo[$forumid]['is_open'] OR !$forumPerms[$forumid]['can_post_threads']) {
	$canPostThreads = "may not";
} else {
	$canPostThreads = "may";
}

if($foruminfo[$forumid]['is_category'] OR !$foruminfo[$forumid]['is_active'] OR !$foruminfo[$forumid]['is_open'] OR !$forumPerms[$forumid]['can_edit_own']) {
	$canEditOwnPosts = "may not";
} else {
	$canEditOwnPosts = "may";
}

if($foruminfo[$forumid]['is_category'] OR !$foruminfo[$forumid]['is_active'] OR !$foruminfo[$forumid]['is_open'] OR !$forumPerms[$forumid]['can_upload_attachments']) {
	$canUploadAttachments = "may not";
} else {
	$canUploadAttachments = "may";
}

if($foruminfo[$forumid]['is_category'] OR !$foruminfo[$forumid]['is_active'] OR !$foruminfo[$forumid]['is_open'] OR !$forumPerms[$forumid]['can_attachments']) {
	$canDownloadAttachments = "may not";
} else {
	$canDownloadAttachments = "may";
}

if($foruminfo[$forumid]['is_category'] OR !$foruminfo[$forumid]['is_active'] OR !$foruminfo[$forumid]['is_open'] OR !$forumPerms[$forumid]['can_reply_own']) {
	$canReplyOwn = "may not";
} else {
	$canReplyOwn = "may";
}

if($foruminfo[$forumid]['is_category'] OR !$foruminfo[$forumid]['is_active'] OR !$foruminfo[$forumid]['is_open'] OR !$forumPerms[$forumid]['can_reply_others'] ) {
	$canReplyOthers = "may not";
} else {
	$canReplyOthers = "may";
}

// do the post indicators.. with cookies...
if($foruminfo[$forumid]['last_reply_date'] > $userinfo['lastvist'] AND !$_COOKIE['wtcBB_forum'][$forumid]) {
	$_COOKIE['wtcBB_forum'][$forumid] = time();
	$cookies = serialize($_COOKIE['wtcBB_forum']);
	
	// set a cookie...
	setcookie('wtcBB_forum',$cookies,0,$bboptions['cookie_path'],$bboptions['cookie_domain']);
}

else if($_COOKIE['wtcBB_forum'][$forumid] AND $foruminfo[$forumid]['last_reply_date'] > $_COOKIE['wtcBB_forum'][$forumid]) {
	$_COOKIE['wtcBB_forum'][$forumid] = time();
	$cookies = serialize($_COOKIE['wtcBB_forum']);
	
	// set a cookie...
	setcookie('wtcBB_forum',$cookies,0,$bboptions['cookie_path'],$bboptions['cookie_domain']);
}

// get the forumdisplay
eval("\$forumdisplay = \"".getTemplate("forumdisplay")."\";");

// intialize templates
eval("\$header = \"".getTemplate("header")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

// spit it out
printTemplate($header);
printTemplate($forumdisplay);
printTemplate($footer);

// wrrrrrrrap it up!
wrapUp();

?>