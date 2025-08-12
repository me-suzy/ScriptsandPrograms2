<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################ //FRONT END - WARN USER\\ ################ \\
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

if(!$bboptions['enableWarn']) {
	doError(
		"The Warning System is disabled on this message board.",
		"Error Warning Member",
		"System Disabled"
	);
}

// make sure user exists!
$user_q = query("SELECT * FROM user_info WHERE userid = '".$_GET['u']."' LIMIT 1");

// uh ohh....
if(!mysql_num_rows($user_q) OR !$_GET['u']) {
	doError(
		"There is no user found in the database with the corresponding link.",
		"Error Warning Member",
		"User Doesn't Exist"
	);
}

// find post?
$post_q = query("SELECT * FROM posts WHERE postid = '".$_GET['p']."' LIMIT 1");

if(!mysql_num_rows($post_q) OR !$_GET['p']) {
	doError(
		"There is no post found in the database with the corresponding link.",
		"Error Warning Member",
		"Post Doesn't Exist"
	);
}

// safe to fetch arrays...
$user = mysql_fetch_array($user_q);
$post = mysql_fetch_array($post_q);

// not owner of post? o_0
if($post['userid'] != $user['userid']) {
	doError(
		"Sorry, the owner of the post and the user you are trying to warn do not match.",
		"Error Warning Member",
		"Owner and user mismatch"
	);
}

// perms?
if(!$usergroupinfo[$userinfo['usergroupid']]['warn_others'] OR $usergroupinfo[$user['usergroupid']]['warn_protected'] OR strpos($super_administrator,$user['userid']) !== false) {
	doError(
		"perms",
		"Error Warning Member"
	);
}

// reason bits?
$reason_q = query("SELECT * FROM warn_type ORDER BY warnPoints");
$reasonBits = '';

if(!mysql_num_rows($reason_q)) {
	doError(
		"Sorry, there are no reasons for warning a member in the database.",
		"Error Warning Member",
		"No Reasons in Database"
	);
}

// create nav bar array
$navbarArr = Array(
	"Warn Member" => "#"
);
$navbarText = getNavbarLinks($navbarArr);

// deal with sessions
$sessionInclude = doSessions("Warning Member",$user['username']);
include("./includes/sessions.php");

if($_POST) {
	// insert into warn table...
	query("INSERT INTO warn (userid,typeid,whoWarned,note,warnDate,postid) VALUES ('".$user['userid']."','".$_POST['typeid']."','".$userinfo['userid']."','".htmlspecialchars(addslashes(trim($_POST['note'])))."','".time()."','".$post['postid']."')");

	$typeinfo = query("SELECT * FROM warn_type WHERE typeid = '".$_POST['typeid']."'",1);
	$warningLevel = $user['warn'] + $typeinfo['warnPoints'];

	// auto ban?
	if($bboptions['warnAutoBan'] AND $warningLevel >= $bboptions['warnAutoBan']) {
		query("UPDATE user_info SET usergroupid = '".$bboptions['autoBanGroup']."' , warn = '".$warningLevel."' WHERE userid = '".$user['userid']."'");
	}

	// no? just update warning level then...
	else {
		query("UPDATE user_info SET warn = '".$warningLevel."' WHERE userid = '".$user['userid']."'");
	}

	// send email?
	if($bboptions['sendWarnNotify']) {
		eval("\$message = \"".getTemplate("mail_warnUser")."\";");
		mail($user['email'],"wtcBB Mailer - You Have Been Warned",$message,"From: ".$bboptions['details_contact']);
	}

	doThanks(
		"You have successfully warned <strong>".$user['username']."</strong>, and given the user <strong>".$typeinfo['warnPoints']."</strong> warning points.",
		"Warning Member Complete",
		"none",
		"index.php"
	);
}

// form reason bits...
while($reason = mysql_fetch_array($reason_q)) {
	eval("\$reasonBits .= \"".getTemplate("warn_reasonbit")."\";");
}

// get whole template
eval("\$warn = \"".getTemplate("warn")."\";");

// get templates
eval("\$header = \"".getTemplate("header")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

printTemplate($header);
printTemplate($warn);
printTemplate($footer);

?>