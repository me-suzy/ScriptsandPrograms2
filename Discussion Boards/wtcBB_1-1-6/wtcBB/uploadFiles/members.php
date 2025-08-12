<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //FRONT END - MEMBERS\\ ################# \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
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

$sortBy = $_GET['sortBy'];
$orderBy = $_GET['orderBy'];

// do sortBy and orderBy
if(!$sortBy) {
	$sortBy = "posts";
}

if($orderBy != "DESC" AND $orderBy != "ASC") {
	$orderBy = "DESC";
}

// selections
$select1 = "";
$select2 = "";
$select3 = "";
$select4 = "";
$select5 = "";
$descAsc1 = "";
$descAsc2 = "";

if($sortBy == "date_joined") {
	$select2 = ' selected="selected"';
} else if($sortBy == "username") {
	$select3 = ' selected="selected"';
} else if($sortBy == "lastvisit") {
	$select4 = ' selected="selected"';
} else if($sortBy == "lastpost") {
	$select5 = ' selected="selected"';
} else {
	$select1 = ' selected="selected"';
	$sortBy = "posts";
}

if($orderBy == "ASC") {
	$descAsc2 = ' selected="selected"';;
} else {
	$descAsc1 = ' selected="selected"';
}

// select all users
$allUsers = query("SELECT * FROM user_info ORDER BY ".$sortBy." ".$orderBy);

// memberlist disabled?
if(!$bboptions['memberlist_enabled']) {
	doError(
		"The administrator has disabled the viewing of the member's list.",
		"Error Viewing Members List",
		"Members List Disabled"
	);
}

// deal with sessions
$sessionInclude = doSessions("Viewing Member's List","Members");
include("./includes/sessions.php");

// create nav bar array
$navbarArr = Array(
	"Member's List" => "#"
);
$navbarText = getNavbarLinks($navbarArr);

$page = $_REQUEST['page'];

// get start and end...
if(!$page) {
	$page = 1;
}

$end = $bboptions['members_per_page'];

// get start before REAL end
$start = ($page - 1) * $end;

// real end...
$end *= $page;

// start the limit counter
$limitCounter = 0;

// loop
while($theuser = mysql_fetch_array($allUsers)) {
	// make sure they're supposed be on this list
	if(!$theuser['userid'] OR !$usergroupinfo[$theuser['usergroupid']]['show_memberlist'] OR $theuser['is_coppa']) {
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
		// umm.. no where else to go.. so end
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
		eval("\$sendEmail = \"".getTemplate("members_userbit_sendEmail")."\";");
		$userSep_1 = true;
	} else {
		$sendEmail = "";
	}

	// pm?
	if($theuser['use_pm'] AND $usergroupinfo[$theuser['usergroupid']]['personal_max_messages'] AND $bboptions['personal_enabled']) {
		eval("\$sendPM = \"".getTemplate("members_userbit_sendPM")."\";");
		$userSep_2 = true;
	} else {
		$sendPM = "";
	}

	if($userSep_1 AND $userSep_2) {
		eval("\$separator = \"".getTemplate("members_userbit_separator")."\";");
	} else {
		$separator = "";
	}

	unset($joinDate,$lastPostDate,$lastPostTime);

	$joinDate = processDate($bboptions['date_register_format'],$theuser['date_joined']);

	if($theuser['lastpost'] > 0 AND $theuser['lastpostid'] > 0) {
		$lastPostDate = processDate($bboptions['date_formatted'],$theuser['lastpost']);
		$lastPostTime = processDate($bboptions['date_time_format'],$theuser['lastpost']);
	} else {
		$lastPostDate = "Never";
		$lastPostTime = "";
	}

	// get the user row...
	eval("\$userbits .= \"".getTemplate("members_userbit")."\";");
}

$numOfUsers = $limitCounter;

if($numOfUsers % $bboptions['members_per_page'] != 0) {
	$totalPages = ($numOfUsers / $bboptions['members_per_page']) + 1;
	settype($totalPages,"integer");
} else {
	$totalPages = $numOfUsers / $bboptions['members_per_page'];
}

// build the page links...
$pagelinks = buildPageLinks($totalPages,$page);

// now grab all the templates...
eval("\$header = \"".getTemplate("header")."\";");
eval("\$members = \"".getTemplate("members")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

// spit it out
printTemplate($header);
printTemplate($members);
printTemplate($footer);

wrapUp();

?>