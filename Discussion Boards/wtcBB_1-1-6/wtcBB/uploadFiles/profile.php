<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //FRONT END - PROFILE\\ ################# \\
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

// make sure user exists!
$user_q = query("SELECT * FROM user_info WHERE userid = '".$_GET['u']."' LIMIT 1");

// uh ohh....
if(!mysql_num_rows($user_q) OR !$_GET['u']) {
	doError(
		"There is no user found in the database with the corresponding link.",
		"Error Viewing Member",
		"User Doesn't Exist"
	);
}

// safe to fetch array...
$user = mysql_fetch_array($user_q);

// make sure user can see other's profiles..
if(!$usergroupinfo[$userinfo['usergroupid']]['see_profile']) {
	doError(
		"perms",
		"Error Viewing Member"
	);
}

// create nav bar array
$navbarArr = Array(
	"Viewing Member Profile" => "#"
);
$navbarText = getNavbarLinks($navbarArr);

// deal with sessions
$sessionInclude = doSessions("Viewing Member Profile",$user['username']);
include("./includes/sessions.php");

$joinDate = processDate($bboptions['date_register_format'],$user['date_joined']);

if($user['lastpost'] > 0 AND $user['lastpostid'] > 0) {
	$lastPostDate = processDate($bboptions['date_formatted'],$user['lastpost']);
	$lastPostTime = processDate($bboptions['date_time_format'],$user['lastpost']);
} else {
	$lastPostDate = "Never";
}

$lastActDate = processDate($bboptions['date_formatted'],$user['lastactivity']);
$lastActTime = processDate($bboptions['date_time_format'],$user['lastactivity']);

if($user['birthday']) {
	$theBirthday = processBirthday($user['birthday']);
} else {
	$theBirthday = "";
}

if($user['avatar_url'] == "none") {
	$theAv = "";
} else {
	$theAv = '<img src="'.$user['avatar_url'].'" alt="'.$user['username'].'\'s Avatar" />';
}

$theCT = getCustomTitle($user);

// get guestbook entry total...
$guestbook = query("SELECT COUNT(*) AS total FROM guestbook WHERE ownUserid = ".$user['userid'],1);

// get whole template
eval("\$profile = \"".getTemplate("profile")."\";");

// get templates
eval("\$header = \"".getTemplate("header")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

printTemplate($header);
printTemplate($profile);
printTemplate($footer);

?>