<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //FRONT END - SEARCH\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_forums.php");
include("./includes/functions_bbcode.php");
include("./global.php");

// get forum home stylesheet
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_forumhome")."\";");
eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_threaddisplay")."\";");

// if no css file.. get internetl block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

// memberlist disabled?
if(!$bboptions['search_enabled']) {
	doError(
		"The Administrator has disabled the search function on this message board.",
		"Error Searching",
		"Search Disabled"
	);
}

if(!$usergroupinfo[$userinfo['usergroupid']]['can_search'] OR ($_GET['f'] AND is_array($foruminfo[$_GET['f']]) AND !$forumPerms[$_GET['f']]['can_search'])) {
	doError(
		"perms",
		"Error Searching"
	);
}

// deal with sessions
$sessionInclude = doSessions("Searching Forums","Searching");
include("./includes/sessions.php");

// create nav bar array
$navbarArr = Array(
	"Searching Forums" => "#"
);
$navbarText = getNavbarLinks($navbarArr);

$page = $_REQUEST['page'];

// get start and end...
if(!isset($page)) {
	$page = 1;
}

$end = $bboptions['num_of_search_page'];

// get start before REAL end
$start = ($page - 1) * $end;

// real end...
$end *= $page;

// start the limit counter
$limitCounter = 0;

if($numOfThreads % $bboptions['maximum_threads'] != 0) {
	$totalPages = ($numOfThreads / $bboptions['maximum_threads']) + 1;
	settype($totalPages,"integer");
} else {
	$totalPages = $numOfThreads / $bboptions['maximum_threads'];
}

// build the page links...
$pagelinks = buildPageLinks($totalPages,$page);

// if form is submitted...
if($_GET['keyword'] OR $_GET['username']) {
	$_GET['keyword'] = trim($_GET['keyword']);

	$find = Array("%27","%22","%60");
	$replace = Array("'",'"',"`");
	$_GET['keyword'] = str_replace($find,$replace,$_GET['keyword']);
	
	// produce some errors first!
	if(strlen(trim($_GET['keyword'])) < $bboptions['search_minimum'] AND $_GET['keyword']) {
		doError(
			"Your search keyword must have a minimum character count of <strong>".$bboptions['search_minimum']."</strong>.",
			"Error Searching",
			"Under Min. Char. Count"
		);
	}

	// produce some errors first!
	if(strlen(trim($_GET['keyword'])) > $bboptions['search_maximum'] AND $_GET['keyword']) {
		doError(
			"Your search keyword must have a maximum character count of <strong>".$bboptions['search_maximum'].".",
			"Error Searching",
			"Over Max. Char. Count"
		);
	}

	// slashes
	$_GET['keyword'] = addslashes($_GET['keyword']);
	$_GET['username'] = addslashes($_GET['username']);

	// start forming the "forum" part of the query...
	// if all forums is selected.. we're done...
	$allForums = false;

	if(is_array($_GET['forum'])) {
		foreach($_GET['forum'] as $key => $value) {
			if($value == -1) {
				$allForums = true;
				break;
			}
		}
	}

	if($allForums == true) {
		$forumQuery = "";
	} else {
		if(is_array($_GET['forum'])) {
			$x = 1;

			foreach($_GET['forum'] as $key => $value) {
				// form the query...
				if($x == 1) {
					$forumQuery = "AND (posts.forumid = '".$value."' ";
				} else {
					$forumQuery .= "OR posts.forumid = '".$value."' ";
				}

				$x++;
			}

			$forumQuery .= ") ";
		}
	}

	// username and/or keyword???
	if($_GET['keyword']) {
		if($_GET['searchWhere']) {
			$keyField = "posts.title";
		} else {
			$keyField = "posts.message";
		}
		
		$userKeyQuery = "AND ".$keyField." LIKE '%".$_GET['keyword']."%' ";
	} else {
		$userKeyQuery = false;
	}

	if($_GET['username']) {
		if($_GET['exactUsername']) {
			if($_GET['threadStarter']) {
				$userNameKeyQuery = "AND threads.threadUsername = '".$_GET['username']."' ";
			} else {
				$userNameKeyQuery = "AND posts.postUsername = '".$_GET['username']."' ";
			}
		} else {
			if($_GET['threadStarter']) {
				$userNameKeyQuery = "AND threads.threadUsername LIKE '%".$_GET['username']."%' ";
			} else {
				$userNameKeyQuery = "AND posts.postUsername LIKE '%".$_GET['username']."%' ";
			}
		}
	}

	// > or <?
	if($_GET['newerORolder'] == "newer") {
		$operator = ">";
	} else {
		$operator = "<";
	}

	// now do dating... oh this is going to be fun
	if($_GET['dating'] == -1) {
		$dateCond = "";
	}

	// last visit?
	else if($_GET['dating'] == "lastvisit") {
		$dateCond = "AND posts.date_posted ".$operator." '".$userinfo['lastvisit']."' ";
	}

	else {
		// number of seconds in a day
		$oneDay = 86400;

		if($_GET['dating'] == "yesterday") {
			$time = $oneDay;
		} else if($_GET['dating'] == "week1") {
			$time = $oneDay * 7;
		} else if($_GET['dating'] == "week2") {
			$time = $oneDay * 14;
		} else if($_GET['dating'] == "month1") {
			$time = $oneDay * 30;
		} else if($_GET['dating'] == "month6") {
			$time = $oneDay * 182;
		} else if($_GET['dating'] == "year1") {
			$time = $oneDay * 365;
		} else {
			$time = $oneDay;
		}

		$dateCond = "AND posts.date_posted ".$operator." '".(time() - $time)."' ";
	}

	// search just a specific thread?
	if($_GET['t']) {
		$searchThread = "AND threads.threadid = '".$_GET['t']."' ";
	} else {
		$searchThread = "";
	}

	$combinedConditions = $forumQuery.$userKeyQuery.$userNameKeyQuery.$dateCond.$searchThread."AND threads.deleted_thread = 0 AND posts.deleted = 0";

	// remove AND or OR
	$combinedConditions = preg_replace("|^AND|","",$combinedConditions);
	$combinedConditions = preg_replace("|^OR|","",$combinedConditions);

	$sortBy = $_GET['sortBy'];
	$orderBy = $_GET['orderBy'];

	// do sortBy
	if(!$sortBy) {
		if($postsORthreads == "posts") {
			$sortBy = "posts.date_posted";
		} else {
			$sortBy = "threads.last_reply_date";
		}
	}

	else {
		if($sortBy == "last_reply") {
			if($_GET['postsORthreads'] == "posts") {
				$sortBy = "posts.date_posted";
			} else {
				$sortBy = "threads.last_reply_date";
			}
		} 

		else if($sortBy == "starter") {
			if($_GET['postsORthreads'] == "posts") {
				$sortBy = "posts.postUsername";
			} else {
				$sortBy = "threads.threadUsername";
			}
		}

		else {
			$sortBy = "threads.thread_replies";
		}
	}

	// do orderby
	if($orderBy != "DESC" AND $orderBY != "ASC") {
		$orderBy = "DESC";
	}

	if(!$bboptions['maximum_search_results']) {
		$theLimit = "";
	} else {
		$theLimit = " LIMIT ".$bboptions['maximum_search_results'];
	}

	if($_GET['postsORthreads'] == "posts") {
		$totalQuery = "SELECT * FROM posts LEFT JOIN threads ON threads.threadid = posts.threadid LEFT JOIN user_info ON posts.userid = user_info.userid WHERE ".$combinedConditions." ORDER BY ".$sortBy." ".$orderBy.$theLimit;
	} else {
		$totalQuery = "SELECT * FROM threads LEFT JOIN posts ON posts.threadid = threads.threadid LEFT JOIN user_info ON threads.thread_starter = user_info.userid WHERE ".$combinedConditions." GROUP BY threads.threadid ORDER BY ".$sortBy." ".$orderBy.$theLimit;
	}

	// alright, now run the query...
	$results = query($totalQuery);
	$numResults = mysql_num_rows($results);

	// loop through if rows
	if(!$numResults) {
		doError(
			"Your search returned no results, please try different criteria.",
			"Error Searching",
			"No Results"
		);
	}
	
	if($_GET['postsORthreads'] == "threads") {
		// get start and end...
		if(!$page) {
			$page = 1;
		}

		$end = $bboptions['num_of_search_page'];

		// get start before REAL end
		$start = ($page - 1) * $end;

		// real end...
		$end *= $page;

		// start the limit counter
		$limitCounter = 0;

		// safe to loop now
		while($threadinfo = mysql_fetch_array($results)) {
			// make sure we can view
			$cookieName = "wtcBB_forumpass".$threadinfo['forumid'];

			if(($foruminfo[$threadinfo['forumid']]['fpassword'] AND !$_COOKIE[$cookieName]) OR $foruminfo[$threadinfo['forumid']]['is_category'] OR !$forumPerms[$threadinfo['forumid']]['can_view_board'] OR ($userinfo['userid'] != $threadinfo['thread_starter'] AND $userinfo['userid'] AND !$forumPerms[$threadinfo['forumid']]['can_view_threads']) OR ($threadinfo['deleted_thread'] AND !$forumPerms[$threadinfo['forumid']]['can_view_deletion'])) {
				continue;
			}

			// first we must do the limit counter.. 
			// make sure we're supposed to be showing this thread...
			$limitCounter++;
			
			if($limitCounter <= $start) {
				// we could still have threads to show so press on!
				continue;
			}

			if($limitCounter > $end) {
				continue;
			}

			// format username...
			$getUsername = getHTMLUsername($threadinfo);

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

			$prefix = unhtmlspecialchars($prefix);

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
				$pagelinks = buildPageLinks($totalPages,1,false,true);
			} else {
				$pagelinks = "";
			}

			// get the thread row template
			eval("\$threadrow .= \"".getTemplate("search_threads_bit")."\";");
		}

		// if nothing spit out error?
		if(!$limitCounter) {
			doError(
				"Your search returned no results, please try different criteria."
			);
		}

		$numOfThreads = $limitCounter;

		if($numOfThreads % $bboptions['num_of_search_page'] != 0) {
			$totalPages = ($numOfThreads / $bboptions['num_of_search_page']) + 1;
			settype($totalPages,"integer");
		} else {
			$totalPages = $numOfThreads / $bboptions['num_of_search_page'];
		}

		$pagelinks = buildPageLinks($totalPages,$page);

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

	else {
		// get start and end...
		if(!$page) {
			$page = 1;
		}

		$end = $bboptions['num_of_search_page'];

		// get start before REAL end
		$start = ($page - 1) * $end;

		// real end...
		$end *= $page;

		// start the limit counter
		$postCounter = 0;

		// loop through posts...
		while($arr = mysql_fetch_array($results)) {
			// make sure we can view
			$cookieName = "wtcBB_forumpass".$arr['forumid'];

			if(($foruminfo[$arr['forumid']]['fpassword'] AND !$_COOKIE[$cookieName]) OR $foruminfo[$arr['forumid']]['is_category'] OR !$forumPerms[$arr['forumid']]['can_view_board'] OR ($userinfo['userid'] != $arr['thread_starter'] AND $userinfo['userid'] AND !$forumPerms[$arr['forumid']]['can_view_threads']) OR ($arr['deleted_thread'] AND !$forumPerms[$threadinfo['forumid']]['can_view_deletion'])) {
				continue;
			}

			$postCounter++;

			// make sure we're in right place...
			if($postCounter <= $start) {
				// move on...
				continue;
			}

			if($postCounter > $end) {
				continue;
			}

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

			$arr['message'] = parseMessage($arr['message'],$arr['parse_bbcode'],$arr['parse_smilies'],$foruminfo[$arr['forumid']]['allow_img'],(!$userinfo['allow_html']),$foruminfo[$arr['forumid']]['allow_wtcBB'],$foruminfo[$arr['forumid']]['allow_smilies'],$arr['username'],$arr);

			// grab the template
			eval("\$postbits .= \"".getTemplate("search_postbit")."\";");
		}

		if(!$postCounter) {
			doError(
				"Your search returned no results, please try different criteria."
			);
		}

		$numOfPosts = $postCounter;

		if($numOfPosts % $bboptions['num_of_search_page'] != 0) {
			$totalPages = ($numOfPosts / $bboptions['num_of_search_page']) + 1;
			settype($totalPages,"integer");
		} else {
			$totalPages = $numOfPosts / $bboptions['num_of_search_page'];
		}

		// build the page links...
		$pagelinks = buildPageLinks($totalPages,$page);

		eval("\$showposts = \"".getTemplate("search_posts")."\";");

		// intialize templates
		eval("\$header = \"".getTemplate("header")."\";");
		eval("\$footer = \"".getTemplate("footer")."\";");

		// spit it out
		printTemplate($header);
		printTemplate($showposts);
		printTemplate($footer);

		// wrrrrrrrap it up!
		wrapUp();

		exit;
	}
}

// build the forumlist...
$forumsToSearch = buildForumSelection(-1);

// now grab all the templates...
eval("\$header = \"".getTemplate("header")."\";");
eval("\$search = \"".getTemplate("search")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

// spit it out
printTemplate($header);
printTemplate($search);
printTemplate($footer);

// wraaaaaappp it up!!
wrapUp();
?>