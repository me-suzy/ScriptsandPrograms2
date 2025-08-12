<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################ //FRONT END - EDIT POLL\\ ################ \\
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

$getPoll = query("SELECT * FROM poll WHERE pollid = '".addslashes($_GET['pid'])."' LIMIT 1");

$getThread = query("SELECT * FROM threads WHERE threadid = '".addslashes($_GET['t'])."' LIMIT 1");

// uh oh... no thread!
if(!mysql_num_rows($getThread) OR !mysql_num_rows($getPoll)) {
	doError(
		"Either the poll does not exist, or the thread does not exist.",
		"Error Editing Poll",
		"Poll or Thread Doesn't Exist"
	);
}

// it's safe to get threadinfo now...
$threadinfo = mysql_fetch_array($getThread);
$pollinfo = mysql_fetch_array($getPoll);

$forumid = $threadinfo['forumid'];

check_wtcBB_forumPassword($forumid);

// deleted thread.. pretend it doesn't exist
if($threadinfo['deleted']) {
	doError(
		"Either the poll does not exist, or the thread does not exist.",
		"Error Editing Poll",
		"Poll or Thread Doesn't Exist"
	);
}

// get moderator perms...
$moderator = hasModPermissions($forumid);

// no permissions
if(!$forumPerms[$forumid]['can_view_board'] OR !$moderator OR ($moderator AND !$modinfo[$forumid][$moderator]['can_edit_polls'] AND $moderator !== true)) {
	doError(
		"perms",
		"Error Editing Poll"
	);
}

// create nav bar array
$navbarArr = getForumNav($forumid);

// reverse it... if array exists
if(is_array($navbarArr)) {
	$navbarArr = array_reverse($navbarArr);
}

// add to it...
$navbarArr[$foruminfo[$forumid]['forum_name']] = "forum.php?f=".$forumid;
$navbarArr[$threadinfo['thread_name']] = "thread.php?t=".$threadinfo['threadid'];
$navbarArr['Editing Poll'] = "#";

$navbarText = getNavbarLinks($navbarArr);

// deal with sessions
$sessionInclude = doSessions("Editing a Poll","In Forum: ".$foruminfo[$forumid]['forum_name']);
include("./includes/sessions.php");

// get the current date...
$todaysDate = processDate($bboptions['date_formatted'],time(),0);

// get checked
if(($pollinfo['multiple'] AND !$_POST) OR $_POST['multipleChoice']) {
	$multiChecked = " checked=\"checked\"";
} else {
	$multiChecked = "";
}

if(($pollinfo['public'] AND !$_POST) OR $_POST['makePublic']) {
	$pubChecked = " checked=\"checked\"";
} else {
	$pubChecked = "";
}

if(($pollinfo['active'] AND !$_POST) OR $_POST['keepActive']) {
	$actChecked = " checked=\"checked\"";
} else {
	$actChecked = "";
}

if($_POST['deletePoll']) {
	$delChecked = " checked=\"checked\"";
} else {
	$delChecked = "";
}

if(!$_POST['pollCloseTimeout']) {
	$pollCloseTimeout = $pollinfo['timeout'];
}

// get all the poll options
$getPollOptions = query("SELECT * FROM poll_options WHERE pollid = '".$pollinfo['pollid']."' ORDER BY option_value ASC");

if(!$_POST['pollQuestion']) {
	$_POST['pollQuestion'] = $pollinfo['question'];
}

if(!$_REQUEST['numOpt']) {
	$_REQUEST['numOpt'] = $pollinfo['numberoptions'];
}

$_REQUEST['numOpt'] = ($_REQUEST['numOpt'] > $bboptions['maximum_poll_options']) ? $bboptions['maximum_poll_options'] : $_REQUEST['numOpt'];

// show some extra templates for editing...
eval("\$extraDirections = \"".getTemplate("poll_editing_extraDirections")."\";");
eval("\$deletePoll2 = \"".getTemplate("poll_editing_deletePoll")."\";");
eval("\$activePoll = \"".getTemplate("poll_editing_activePoll")."\";");
eval("\$backToDefault = \"".getTemplate("poll_editing_backToDefault")."\";");

// create array to hold original X values...
$origXvals = Array();

$x = 0;

while($optinfo = mysql_fetch_array($getPollOptions)) {
	$x++;

	$origXvals[$optinfo['poll_optionid']] = $x;

	// get the found value
	$temp = $_POST["option".$x];

	$temp2 = $_POST["newOptionVoters".$x];

	if(!$temp) {
		$foundValue = $optinfo['option_value'];
	} else {
		$foundValue = $temp;
	}

	if(!$_POST['voteCounts']) {
		$theVoteCount = $optinfo['votes'];
	} else {
		$theVoteCount = $_POST['voteCounts'][$x];
	}

	if(!$temp2) {
		$theOptVoters = $optinfo['voters'];
	} else {
		$theOptVoters = $temp2;
	}

	// get templates
	eval("\$optionVoters = \"".getTemplate("poll_editing_optionVoters")."\";");
	eval("\$voteCount = \"".getTemplate("poll_editing_voteCount")."\";");
	eval("\$allPollOptions .= \"".getTemplate("poll_optionbit")."\";");
}

// loop through to get the option bit templates...
if($_REQUEST['numOpt'] > $pollinfo['numberoptions']) {
	for($x = $pollinfo['numberoptions'] + 1; $x <= $_REQUEST['numOpt']; $x++) {
		// get the found value..
		$foundValue = $_POST["option".$x];

		$temp2 = $_POST["newOptionVoters".$x];

		$theVoteCount = $_POST['voteCounts'][$x];

		$theOptVoters = $temp2;

		$origXvals2[$foundValue] = $x;
		
		// get templates
		eval("\$optionVoters = \"".getTemplate("poll_editing_optionVoters")."\";");
		eval("\$voteCount = \"".getTemplate("poll_editing_voteCount")."\";");
		eval("\$allPollOptions .= \"".getTemplate("poll_optionbit")."\";");
	}
} else {
	$_REQUEST['numOpt'] = $pollinfo['numberoptions'];
}

// put poll into db!
if($_POST['goSubmit']) {
	// if delete... DELETE!
	if($_POST['deletePoll']) {
		query("DELETE FROM poll_options WHERE pollid = '".$pollinfo['pollid']."'");
		query("DELETE FROM poll WHERE pollid = '".$pollinfo['pollid']."'");

		// update thread to say there IS NOT a poll...
		query("UPDATE threads SET poll = '0' WHERE threadid = '".$threadinfo['threadid']."'");

		doModLog("Deleted Poll: ".htmlspecialchars($pollinfo['question']));

		doThanks(
			"Your poll has successfully been deleted. You will now be redirected to the corresponding thread.",
			"Deleting Poll",
			"In Forum: ".$foruminfo[$forumid]['forum_name'],
			"thread.php?t=".$threadinfo['threadid']
		);
	}

	$totalFilledOpt = 0;
	$talliedVotes = 0;

	// get the total number of options...
	// that is, the ones that are filled in...
	for($x = 1; $x <= $_REQUEST['numOpt']; $x++) {
		// get the value
		$value = $_POST["option".$x];

		// if not empty, increment
		if($value) {
			$totalFilledOpt++;

			// increment
			$talliedVotes += $_POST['voteCounts'][$x];
		}

		// otherwise, delete from DB
		else if($x <= $pollinfo['numberoptions']) {
			$temp2 = "option2".$x;

			// delete
			query("DELETE FROM poll_options WHERE poll_optionid = '".$temp2."' LIMIT 1");
		}
	}

	if(!$_POST['pollQuestion']) {
		doError(
			"You must have a 'Question' for your poll.",
			"Error Making Poll",
			"No question"
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

	if($talliedVotes < 0) {
		$talliedVotes = 0;
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
	if($_POST['keepActive']) {
		$isActive = 1;
	} else {
		$isActive = 0;
	}

	// update poll
	$updatePoll = query("UPDATE poll SET question = '".addslashes(htmlspecialchars(doCensors($_POST['pollQuestion'])))."' , numberoptions = '".$totalFilledOpt."' , multiple = '".$isMulti."' , public = '".$isPublic."' , active = '".$isActive."' , timeout = '".addslashes($_POST['pollCloseTimeout'])."' , totalVotes = '".$talliedVotes."' WHERE pollid = '".$pollinfo['pollid']."'");
	
	$getPollOptions2 = query("SELECT * FROM poll_options WHERE pollid = '".$pollinfo['pollid']."' ORDER BY option_value ASC");

	while($optinfo2 = mysql_fetch_array($getPollOptions2)) {
		// get the found value
		$temp = $_POST["option".$origXvals[$optinfo2['poll_optionid']]];

		$temp2 = $_POST["newOptionVoters".$origXvals[$optinfo2['poll_optionid']]];

		if(!$temp) {
			$foundValue = $optinfo2['option_value'];
		} else {
			$foundValue = $temp;
		}

		if(!$_POST['voteCounts']) {
			$theVoteCount = $optinfo2['votes'];
		} else {
			$theVoteCount = $_POST['voteCounts'][$origXvals[$optinfo2['poll_optionid']]];
		}

		/*if(!$temp2) {
			$theOptVoters = $optinfo2['voters'];
		} else {*/
			$theOptVoters = $temp2;
		//}

		$updatePollOption = query("UPDATE poll_options SET option_value = '".addslashes(htmlspecialchars(doCensors($foundValue)))."' , votes = '".$theVoteCount."' , voters = '".addslashes($theOptVoters)."' WHERE poll_optionid = '".$optinfo2['poll_optionid']."'");
	}

	// loop through to get the rest...
	if($_REQUEST['numOpt'] > $pollinfo['numberoptions'] AND is_array($origXvals2)) {
		foreach($origXvals2 as $optionValue => $origX) {
			// get the found value..
			$foundValue = $_POST["option".$origX];

			$temp2 = $_POST["newOptionVoters".$origX];

			$theVoteCount = $_POST['voteCounts'][$origX];

			$theOptVoters = $temp2;

			if($foundValue) {
				$insertPollOption = query("INSERT INTO poll_options (pollid,option_value,votes,voters,threadid) VALUES ('".$pollinfo['pollid']."','".$foundValue."','".$theVoteCount."','".$theOptVoters."','".$threadinfo['threadid']."')");
			}
		}
	}

	doModLog("Updated Poll: ".$pollinfo['question']);

	doThanks(
		"Your poll has successfully been processed. You will now be redirected to the corresponding thread.",
		"Editing Poll",
		"In Forum: ".$foruminfo[$forumid]['forum_name'],
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

?>