<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############### //FRONT END - SEND EMAIL\\ ################ \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_bbcode.php");
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

// get the userinfo OF
$userinfoof_q = query("SELECT * FROM user_info WHERE userid = '".$_GET['u']."' AND userid > 0 LIMIT 1");

// if now rows...
if(!mysql_num_rows($userinfoof_q) AND !$_GET['do']) {
	doError(
		"Sorry, there is no member existing with the given userid.",
		"Error Sending Email",
		"Member Doesn't Exist"
	);
}

// fetch array
$userinfoof = mysql_fetch_array($userinfoof_q);

// email disabled?
if(!$_GET['do'] AND (!$bboptions['enable_user_email'] OR !$userinfoof['receive_emails'])) {
	doError(
		"The administrator has disabled the sending of emails from the forum, or this user does not want to receive emails.",
		"Error Sending Email",
		"Sending Email Disabled"
	);
}

// floodcheck
if((!$usergroupinfo[$userinfo['usergroupid']]['flood_immunity'] AND (time() - $bboptions['floodcheck']) < $userinfo['last_emailed']) AND $userinfo['userid']) {
	doError(
		"The administrator has specified you may only send an email every ".$bboptions['floodcheck']." seconds.",
		"Error Sending Email",
		"Flooding"
	);
}

// guest?
if(!$userinfo['userid']) {
	doError(
		"Guests cannot send emails from the forums.",
		"Error Sending Email",
		"Guests Can't Send Emails"
	);
}

// if report post.. make sure post exists
if($_GET['do'] == "report") {
	// get post
	$postinfo_q = query("SELECT * FROM posts WHERE postid = '".$_GET['p']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($postinfo_q)) {
		doError(
			"The post you are trying to report does not exist.",
			"Error Reporting Post",
			"Alleged Post Doesn't Exist"
		);
	}

	// safe to get array
	$postinfo = mysql_fetch_array($postinfo_q);

	// deal with sessions
	$sessionInclude = doSessions("Report Post",$postinfo['title']);
	include("./includes/sessions.php");

	// create nav bar array
	$navbarArr = Array(
		"Report Post" => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// send email time!
	if($_POST) {
		if(!$_POST['reason']) {
			$error = "<p style=\"width: ".$colors['inner_table_width']."; margin-left: auto; margin-right: auto; margin-bottom: 20px; color: #bb0000; font-weight: bold;\">You cannot leave any fields blank.</p>";
		}

		else {
			// all permissions should be set.. so just.. censor it, and send it off!
			$theReason = doCensors($_POST['reason']);
			$emailList = '';

			// select all admins/supa mods
			$adminsANDsupamods = query("SELECT * FROM user_info LEFT JOIN usergroups ON usergroups.usergroupid = user_info.usergroupid WHERE usergroups.is_admin = 1 OR usergroups.is_super_moderator = 1");

			// if rows, loop through...
			if(mysql_num_rows($adminsANDsupamods)) {
				while($admininfo = mysql_fetch_array($adminsANDsupamods)) {
					$emailList .= ",".$admininfo['email'];
				}
			}

			// now get all mods of this forum
			$modsOfForum = query("SELECT * FROM moderators LEFT JOIN user_info ON user_info.userid = moderators.userid WHERE forumid = '".$postinfo['forumid']."'");

			// if rows, loop through...
			if(mysql_num_rows($modsOfForum)) {
				while($theinfo = mysql_fetch_array($modsOfForum)) {
					$emailList .= ",".$theinfo['email'];
				}
			}

			// remove first comma
			$emailList = preg_replace("|^,|","",$emailList);

			eval("\$message = \"".getTemplate("mail_reportPost")."\";");
			mail($emailList,"wtcBB Mailer - Reported Post",$message,"From: noreply@webtrickscentral.com");

			// update userinfo
			query("UPDATE user_info SET last_emailed = '".time()."' WHERE userid = '".$userinfo['userid']."'");

			doThanks(
				"Email sent successfully. It was sent to all administrators, super moderators, and moderators of this forum. You will now be redirected to the forum index.",
				"Reporting Post",
				"none",
				"index.php"
			);
		}
	}

	// now grab all the templates...
	eval("\$header = \"".getTemplate("header")."\";");
	eval("\$sendemail = \"".getTemplate("sendemail_reportPost")."\";");
	eval("\$footer = \"".getTemplate("footer")."\";");

	// spit it out
	printTemplate($header);
	printTemplate($sendemail);
	printTemplate($footer);

	wrapUp();

	// exit!
	exit;
}

// deal with sessions
$sessionInclude = doSessions("Sending email",$userinfoof['username']);
include("./includes/sessions.php");

// create nav bar array
$navbarArr = Array(
	"Send Email" => "#"
);
$navbarText = getNavbarLinks($navbarArr);

// send email time!
if($_POST['message'] OR $_POST['subject']) {
	if(!$_POST['message'] OR !$_POST['subject']) {
		$error = "<p style=\"width: ".$colors['inner_table_width']."; margin-left: auto; margin-right: auto; margin-bottom: 20px; color: #bb0000; font-weight: bold;\">You cannot leave any fields blank.</p>";
	}

	else {
		// all permissions should be set.. so just.. censor it, and send it off!
		$_POST['message'] = doCensors($_POST['message']);
		$_POST['subject'] = doCensors($_POST['subject']);

		$_POST['message'] = "This is an email sent to you by ".$userinfo['username']." from ".$bboptions['details_boardname']." Board Mailer. This is *not* an official email from the administrator.\n\n---------------------------------------------------------\n\n".$_POST['message'];

		// mail!
		mail($userinfoof['email'],$_POST['subject'],$_POST['message'],"From: noreply@webtrickscentral.com");

		// update userinfo
		query("UPDATE user_info SET last_emailed = '".time()."' WHERE userid = '".$userinfo['userid']."'");

		doThanks(
			"Email sent successfully. You will now be redirected to the forum index.",
			"Sending Email",
			$userinfoof['username'],
			"index.php"
		);
	}
}

// now grab all the templates...
eval("\$header = \"".getTemplate("header")."\";");
eval("\$sendemail = \"".getTemplate("sendemail")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

// spit it out
printTemplate($header);
printTemplate($sendemail);
printTemplate($footer);

wrapUp();

?>