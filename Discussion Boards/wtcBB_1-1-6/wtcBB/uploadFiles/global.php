<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //FRONT END - GLOBAL\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// get start time
$startTime = microtime();
$oldStyleID = $setStyleID;

// start the session
define("SESSIONID",md5($userinfo['userid'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']));

// do not cache..
if(strpos($_SERVER['PHP_SELF'],"attachment.php") === false) {
	header('Expires: Mon, 5 Jul 1987 05:00:00 GMT'); 
	header('Pragma: no-cache'); 
}

if(isset($_COOKIE['wtcBB_thread'])) {
	$_COOKIE['wtcBB_thread'] = unserialize($_COOKIE['wtcBB_thread']);
}

if(isset($_COOKIE['wtcBB_forum'])) {
	$_COOKIE['wtcBB_forum'] = unserialize($_COOKIE['wtcBB_forum']);
}

// get the styleid
if(strpos($_SERVER['PHP_SELF'],"forum.php") !== false OR strpos($_SERVER['PHP_SELF'],"postthread.php") !== false) {
	if(is_array($foruminfo[$_GET['f']])) {
		if(!$foruminfo[$_GET['f']]['override_user_style'] AND $userinfo['original_style']) {
			$setStyleID = $userinfo['style_id'];
		} else if(!$foruminfo[$_GET['f']]['original_style']) {
			$setStyleID = getForumStyle($_GET['f']);
		} else {
			$setStyleID = $foruminfo[$_GET['f']]['original_style'];
		}
	}
} 

else if(strpos($_SERVER['PHP_SELF'],"thread.php") !== false OR strpos($_SERVER['PHP_SELF'],"postreply.php") !== false OR strpos($_SERVER['PHP_SELF'],"postedit.php") !== false OR strpos($_SERVER['PHP_SELF'],"editattach.php") !== false) {
	// if no threadid, but postid.. find threadid and redirect!
	if(!$_REQUEST['t'] AND $_REQUEST['p']) {
		$postinfo_q = query("SELECT * FROM posts WHERE postid = '".$_GET['p']."' LIMIT 1");

		// if now rows, throw error
		if(mysql_num_rows($postinfo_q)) {
			$postinfo2 = mysql_fetch_array($postinfo_q);

			header("Location: thread.php?t=".$postinfo2['threadid']."&p=".$postinfo2['postid']."#".$postinfo2['postid']);
		}
	}

	// get the threadid 
	// use $_REQUEST since it could be
	// $_POST or $_GET...
	$threadid = $_REQUEST['t'];

	// run the query to get all the 
	// info about this thread from DB
	$threadStuff = query("SELECT * FROM threads WHERE threadid = '".$threadid."' LIMIT 1");

	// it's safe to get the array now...
	if(mysql_num_rows($threadStuff)) {
		$threadinfo = mysql_fetch_array($threadStuff);

		// get the forumid...
		$forumid = $threadinfo['forumid'];

		if(!$foruminfo[$forumid]['override_user_style'] AND $userinfo['original_style']) {
			$setStyleID = $userinfo['style_id'];
		} else if(!$foruminfo[$forumid]['original_style']) {
			$setStyleID = getForumStyle($foruminfo[$forumid]['forumid']);
		} else {
			$setStyleID = $foruminfo[$forumid]['original_style'];
		}
	}

	else {
		$setStyleID = $userinfo['style_id'];
	}
} 

else {
	$setStyleID = $userinfo['style_id'];
}

if($_GET['styleid']) {
	$setStyleID = $_GET['styleid'];
}

// hmmmm... no styleid?
$style_q = query('SELECT enabled FROM styles WHERE styleid = "' . $setStyleID . '";');

if(!mysql_num_rows($style_q)) {
	$setStyleID = $bboptions['general_style'];
}

$style = mysql_fetch_array($style_q);

if(!$style['enabled'] AND !$usergroupinfo[$userinfo['usergroupid']]['is_admin']) {
	$setStyleID = $bboptions['general_style'];
}

// cache replacements
$replacements = buildReplacements();

// build the templates
$templateinfo = buildTemplateArr();

// get colors
$colors = getColors();

// get forumjump
$forumjump = buildForumJump(-1);

// process some variables
$lastVisitDate = processDate($bboptions['date_formatted'],$userinfo['lastvisit']);
$lastVisitTime = processDate($bboptions['date_time_format'],$userinfo['lastvisit']);

// get the current time
$nowTime = processDate($bboptions['date_time_format'],time());

// get global stylesheet
eval("\$stylesheets_header = \"".getTemplate("stylesheets_global")."\";");

$stylesheets_header = filterCss($stylesheets_header);

// only if it's in file..
if($bboptions['css_in_file']) {
	eval("\$stylesheets_sub = \"".getTemplate("stylesheets_threaddisplay")."\";");
	eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_forumhome")."\";");
	eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_messages")."\";");
	eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_usercp")."\";");
	$stylesheets_sub = filterCss($stylesheets_sub);
} else {
	$stylesheets_sub = "";
}

// if header.. get css!
if($bboptions['css_in_file']) {
	if($oldStyleID != $setStyleID) {
		$linkRel = '<link rel="stylesheet" type="text/css" href="style.php?styleid='.$setStyleID.'" />';
	}
	
	else {
		$linkRel = '<link rel="stylesheet" type="text/css" href="style.php" />';
	}
} else {
	$linkRel = '';
}

//$serverLoad = exec("uptime 2>&1");
//$serverLoad = split("load average: ",$serverLoad);

// get header buttons AND logged in/logged out
if(!$userinfo['userid']) {
	eval("\$header_images = \"".getTemplate("header_links_guest")."\";");
	eval("\$loginLogout = \"".getTemplate("loggedOut")."\";");
} else {
	eval("\$header_images = \"".getTemplate("header_links_user")."\";");
	eval("\$loginLogout = \"".getTemplate("loggedIn")."\";");
}

$sessArr = buildTotalSessions();

if($bboptions['server_sessionlimit'] AND !$usergroupinfo[$userinfo['usergroupid']]['is_admin'] AND !$usergroupinfo[$userinfo['usergroupid']]['is_super_moderator'] AND strpos($_SERVER['PHP_SELF'],"style.php") === false) {
	$count = 0;
	
	foreach($sessArr as $sessid => $tot) {
		if($tot['last_activity'] > $userinfo['lastactivity']) {
			$count++;
		}
	}

	if($count > $bboptions['server_sessionlimit']) {
		eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");

		doError(
			"Sorry, there are too many users on the message board currently. Please wait, then try again later."
		);
	}
}

// normally we would update last visit in sessions.php
// but do this here, so we can process the date variables...
if($userinfo['userid'] AND strpos($_SERVER['PHP_SELF'],"style.php") === false) {
	// now we're going to update last visit.. and last activity
	// if the last visit is past timeout.. update it with last activity
	if($userinfo['lastactivity'] < (time() - $bboptions['cookie_timeout'])) {
		query("UPDATE user_info SET lastvisit = lastactivity , lastactivity = '".time()."' WHERE userid = '".$userinfo['userid']."'");
		$userinfo['lastvisit'] = $userinfo['lastactivity'];
		$userinfo['lastactivity'] = time();
	} else {
		// just update last activity
		query("UPDATE user_info SET lastactivity = '".time()."' WHERE userid = '".$userinfo['userid']."'");
		$userinfo['lastactivity'] = time();
	}
}

// include IP & email ban with below...
$ipBanned = false;
$emailBanned = false;

if($bboptions['enable_banning'] AND strpos($_SERVER['PHP_SELF'],"style.php") === false) {
	if(!empty($bboptions['blocked_ip'])) {
		// split and loop
		$splitted = split(",",$bboptions['blocked_ip']);

		foreach($splitted as $key => $ip) {
			// do we have a match?
			$ip = trim($ip);

			if(preg_match("|^".$ip."|",$_SERVER['REMOTE_ADDR'],$arrs1)) {
				$ipBanned = true;
				break;
			}
		}
	}

	// email
	if(!empty($bboptions['blocked_email'])) {
		// split and loop
		$splitted2 = split(",",$bboptions['blocked_email']);

		foreach($splitted2 as $key2 => $email) {
			// do we have a match?
			$email = trim($email);

			if($userinfo['email'] == $email) {
				$emailBanned = true;
				break;
			}
		}
	}
}

// errors!
if((!$usergroupinfo[$userinfo['usergroupid']]['can_view_board'] OR $ipBanned OR $emailBanned) AND strpos($_SERVER['PHP_SELF'],"style.php") === false AND strpos($_SERVER['PHP_SELF'],"login.php") === false) {
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");

	doError(
		"perms",
		"Error Viewing Message Board"
	);
}

if(!$bboptions['active'] AND !$usergroupinfo[$userinfo['usergroupid']]['is_admin'] AND strpos($_SERVER['PHP_SELF'],"login.php") === false AND strpos($_SERVER['PHP_SELF'],"style.php") === false) {
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");

	doError(
		((empty($bboptions['active_reason'])) ? "This Message board is currently not active." : $bboptions['active_reason']),
		"Message Board is Not Active",
		"#"
	);
}

else if(!$bboptions['active'] AND $usergroupinfo[$userinfo['usergroupid']]['is_admin']) {
	// get warning message
	eval("\$shutDownWarning = \"".getTemplate("shutDownWarning")."\";");
} else {
	$shutDownWarning = "";
}

// delete coppa accounts older than seven days
if((time() - 604800) > $bboptions['lastCoppaCheck'] AND strpos($_SERVER['PHP_SELF'],"style.php") === false) {
	query("DELETE FROM user_info WHERE is_coppa = 1 AND date_joined < '".(time() - 604800)."'");
	query("UPDATE wtcBBoptions SET lastCoppaCheck = '".time()."'");
}

// new personal messages?
// make sure we can check
if($bboptions['personal_check'] AND $userinfo['userid'] AND $userinfo['popup_pm'] AND strpos($_SERVER['PHP_SELF'],"style.php") === false) {
	// yea!
	if($userinfo['newPms'] > 0) {
		eval("\$javascript_onORun = ' onload=\"pmNotify();\"';");
		query("UPDATE personal_msg SET alert = 0 WHERE sentTo = '".$userinfo['userid']."'");
	}
}

?>