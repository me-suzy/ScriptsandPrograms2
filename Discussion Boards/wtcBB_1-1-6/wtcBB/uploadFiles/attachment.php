<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############### //FRONT END - ATTACHMENT\\ ################ \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_forums.php");
include("./global.php");

// find attachment in DB...
$getAttachment = query("SELECT * FROM attachments WHERE attachmentid = '".$_GET['id']."' LIMIT 1");

// make sure attachment exists...
if(!mysql_num_rows($getAttachment)) {
	doError(
		"There is no attachment found in the database with the corresponding link.",
		"Attachment Doesn't Exist"
	);
}

// safe to get array
$attachinfo = mysql_fetch_array($getAttachment);

// get forumid...
$getForumID = query("SELECT forumid FROM threads WHERE threadid = '".$attachinfo['attachmentthread']."' LIMIT 1");

if(!mysql_num_rows($getForumID) AND !$attachinfo['isPM']) {
	doError(
		"There is no thread found in the database with the corresponding link.",
		"Thread Doesn't Exist"
	);
}

// fetch arr
$arr = mysql_fetch_array($getForumID);

// no perms?
if(!$forumPerms[$arr['forumid']]['can_attachments'] AND !$attachinfo['isPM']) {
	// deal with sessions
	$sessionInclude = doSessions("Error Viewing Attachment <img src=\"".$colors['images_folder']."/error.gif\" alt=\"Error\" />","Permissions Error");
	include("./includes/sessions.php");

	// create nav bar array
	$navbarArr = Array(
		"Error Viewing Attachment" => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// intialize templates
	eval("\$header = \"".getTemplate("header")."\";");
	eval("\$footer = \"".getTemplate("footer")."\";");

	// spit out content
	printTemplate($header);
	printStandardError("error_permissions");
	printTemplate($footer);

	exit;

	doError(
		"perms",
		"Error Viewing Attachment"
	);
}

// we're good to go!

// set header...
header("Content-type: ".$attachinfo['mime']);

if(strpos($attachine['mime'], 'image') === false) {
	header('Content-Disposition: attachment; filename="'.$attachinfo['attachmentname'].'"');
}

if($bboptions['general_attachments'] == 1) {
	print($attachinfo['contents']);
} else {
	print(file_get_contents($attachinfo['attachmenturl']));
}

?>