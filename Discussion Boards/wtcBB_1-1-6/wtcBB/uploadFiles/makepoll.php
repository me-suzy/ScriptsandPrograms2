<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################ //FRONT END - MAKE POLL\\ ################ \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_forums.php");
include("./includes/functions_bbcode.php");
include("./global.php");

// get stylesheets
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_forumhome")."\";");

// if no css file.. get internetl block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

// get the thread info..
// we should have already added it
// to the DB before we get here...

$getThread = query("SELECT * FROM threads WHERE threadid = '".$_GET['t']."' LIMIT 1");

// uh oh... no thread!
if(!mysql_num_rows($getThread)) {
	doError(
		"There is no thread existing with the given ID.",
		"Error Making Poll",
		"Thread Doesn't Exist"
	);
}

// it's safe to get threadinfo now...
$threadinfo = mysql_fetch_array($getThread);
$forumid = $threadinfo['forumid'];

check_wtcBB_forumPassword($forumid);

// no permissions
if(!$forumPerms[$forumid]['can_view_board'] OR !$forumPerms[$forumid]['can_post_polls']) {
	doError(
		"perms",
		"Error Making Poll"
	);
}

$moderator = hasModPermissions($forumid);

// thread time limit is up...
if($bboptions['poll_timeout'] AND ((time() - $threadinfo['date_made']) > $bboptions['poll_timeout']) AND ($moderator == false OR ($moderator !== true AND $moderator != false AND !$modinfo[$forumid][$moderator]['can_edit_polls']))) {
	doError(
		$bboptions['poll_timeout']." seconds have passed since your thread has been submitted. You may not add a poll.",
		"Error Making Poll",
		"Poll Timeout has expired"
	);
}

// only thread owner can make poll... uh oh!
if($threadinfo['thread_starter'] != $userinfo['userid'] AND $moderator == false) {
	doError(
		"You may not add a poll to a thread that you did not start.",
		"Error Making Poll",
		"User is not owner of thread"
	);
}

// mod log?
if($moderator != false AND $threadinfo['date_made'] < (time() - $threadinfo['poll_timeout'])) {
	doModLog("Adding Poll to thread: ".htmlspecialchars($threadinfo['thread_name']));
}

// create nav bar array
$navbarArr = getForumNav($forumid);

// reverse it... if array exists
if(is_array($navbarArr)) {
	$navbarArr = array_reverse($navbarArr);
}

// add to it...
$navbarArr[$foruminfo[$forumid]['forum_name']] = "forum.php?f=".$forumid;
$navbarArr[htmlspecialchars($threadinfo['thread_name'])] = "thread.php?t=".$threadinfo['threadid'];
$navbarArr['Making Poll'] = "#";

$navbarText = getNavbarLinks($navbarArr);

// deal with sessions
$sessionInclude = doSessions("Making a Poll","none");
include("./includes/sessions.php");

// get the current date...
$todaysDate = processDate($bboptions['date_formatted'],time(),0);

// get checked
if($_POST['multipleChoice']) {
	$multiChecked = " checked=\"checked\"";
} else {
	$multiChecked = "";
}

if($_POST['makePublic']) {
	$pubChecked = " checked=\"checked\"";
} else {
	$pubChecked = "";
}

// set $pollCloseTimeout to 0 if it isn't set..
if(!$_POST['pollCloseTimeout']) {
	$_POST['pollCloseTimeout'] = 0;
}

// fix the numOpt if user tries to screw around..
$_REQUEST['numOpt'] = (!$_REQUEST['numOpt']) ? 1 : $_REQUEST['numOpt'];
$_REQUEST['numOpt'] = ($_REQUEST['numOpt'] > $bboptions['maximum_poll_options']) ? $bboptions['maximum_poll_options'] : $_REQUEST['numOpt'];

// loop through to get the option bit templates...
for($x = 1; $x <= $_REQUEST['numOpt']; $x++) {
	// get the found value..
	$foundValue = $_POST["option".$x];

	// get the template..
	eval("\$allPollOptions .= \"".getTemplate("poll_optionbit")."\";");
}

// put poll into db!
if($_POST['goSubmit']) {
	$totalFilledOpt = 0;

	// get the total number of options...
	// that is, the ones that are filled in...
	for($x = 1; $x <= $_REQUEST['numOpt']; $x++) {
		// get the value
		$value = $_POST["option".$x];

		// if not empty, increment
		if($value) {
			$totalFilledOpt++;
		}
	}

	if(!$_POST['pollQuestion']) {
		doError(
			"You must have a 'Question' for your poll.",
			"Error Making Poll",
			"No Question"
		);
	}

	// make sure we have fields
	if(!$totalFilledOpt) {
		doError(
			"You must have at least one option filled in.",
			"Error Making Poll",
			"No options"
		);
	}

	// is multi?
	if($_POST['multipleChoice']) {
		$isMulti = 1;
	} else {
		$isMulti = 0;
	}

	// is public?
	if($_POST['makePublic']) {
		$isPublic = 1;
	} else {
		$isPublic = 0;
	}

	// set isActive to 1 for now..
	$isActive = 1;
	
	// submit the poll
	$insertPoll = query("INSERT INTO poll (threadid,question,date_made,numberoptions,multiple,public,active,timeout,totalVotes) VALUES ('".$threadinfo['threadid']."','".addslashes(htmlspecialchars(doCensors($_POST['pollQuestion'])))."','".time()."','".$totalFilledOpt."','".$isMulti."','".$isPublic."','".$isActive."','".addslashes($_POST['pollCloseTimeout'])."','0')");

	// get the poll id
	$insertPollID = mysql_insert_id();

	// now go through and insert the options...
	for($x = 1; $x <= $_REQUEST['numOpt']; $x++) {
		// get the value
		$value = $_POST["option".$x];

		// if not empty.. insert
		if($value) {
			$insertPollOption = query("INSERT INTO poll_options (pollid,option_value,votes,threadid) VALUES ('".$insertPollID."','".addslashes(htmlspecialchars(doCensors($value)))."','0','".$threadinfo['threadid']."')");
		}
	}

	// alright, so the poll has been submitted...
	// update thread to say there IS a poll...
	query("UPDATE threads SET poll = '1' WHERE threadid = '".$threadinfo['threadid']."'");

	doThanks(
		"Your poll has successfully been processed. You will now be redirected to the corresponding thread.",
		"Making a new Poll",
		"none",
		"thread.php?t=".$threadinfo['threadid']
	);
}

// that should cover all the errors...
// intialize templates
eval("\$header = \"".getTemplate("header")."\";");
eval("\$addPoll = \"".getTemplate("poll_add")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

// spit it all out!
printTemplate($header);
printTemplate($addPoll);
printTemplate($footer);

// wrrraaaaaaaap it up!
wrapUp();

?>