<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //FRONT END - OTHERS\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_forums.php");
include("./includes/functions_messages.php");
include("./includes/functions_bbcode.php");
include("./global.php");

eval("\$stylesheets_sub = \"".getTemplate("stylesheets_forumhome")."\";");
eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_forumhome")."\";");
eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_messages")."\";");

// if no css file.. get internetl block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

$theError = '';

// receipt confirm
if($_GET['do'] == "receipt") {
	if($_GET['action'] == "confirm") {
		query("UPDATE personal_receipt SET confirmed = 1 , checked = 1 , receipt_received = '".time()."' WHERE receiptid = '".$_GET['receiptid']."' AND receipt_pid = '".$_GET['pid']."' AND receipt_sentTo = '".$userinfo['userid']."'");
	}

	if($_GET['action'] == "check") {
		query("UPDATE personal_receipt SET checked = 1 WHERE receiptid = '".$_GET['receiptid']."' AND receipt_pid = '".$_GET['pid']."' AND receipt_sentTo = '".$userinfo['userid']."'");
	}

	// close
	print('<script type="text/javascript">window.close();</script>');
	exit;
}

// password retrieval form
if($_GET['do'] == "password") {
	// reset password?
	if(!empty($_GET['hash'])) {
		// make sure we have a valid hash
		$findUser = query("SELECT * FROM user_info WHERE useridHash = '".$_GET['hash']."' LIMIT 1");

		// uh oh!
		if(!mysql_num_rows($findUser)) {
			// deal with sessions
			$sessionInclude = doSessions("Error Resetting Password <img src=\"".$colors['images_folder']."/error.gif\" alt=\"Error\" />","No user found with hash");
			include("./includes/sessions.php");

			// create nav bar array
			$navbarArr = Array(
				"User Control Panel" => "usercp.php",
				"Error Resetting Password" => "#"
			);
			$navbarText = getNavbarLinks($navbarArr);

			// intialize templates
			eval("\$header = \"".getTemplate("header")."\";");
			eval("\$footer = \"".getTemplate("footer")."\";");

			// spit out content
			printTemplate($header);
			printStandardError("error_standard","Sorry, there is no user in the database with that hash.");
			printTemplate($footer);

			exit;
		}

		// fetch array
		$user = mysql_fetch_array($findUser);

		// make sure they've requested a reset password
		if(!$user['passwordDate'] OR $user['passwordDate'] == null OR $user['passwordDate'] < 10) {
			doError(
				"We have not received a request for you to change your password. You can request to change it again by <a href=\"other.php?do=password\">resetting your password</a>.",
				"Error Resetting Password",
				"No Request on File"
			);
		}

		// been 24 hours...
		if($user['passwordDate'] < (time() - 3600)) {
			// while we're here, update to null
			query("UPDATE user_info SET passwordDate = null WHERE userid = '".$user['userid']."'");

			doError(
				"It has been 24 hours since you have requested to reset your password. You can request to change it again by <a href=\"other.php?do=password\">resetting your password</a>.",
				"Error Resetting Password",
				"It's Been 24 Hours"
			);
		}

		// reset password...
		if($_POST) {
			// make sure no empty fields
			if(!$_POST['password'] OR !$_POST['confirmPassword']) {
				$theError = printStandardError("error_standard","You must fill out all fields.",0);
			}

			// not equal
			else if($_POST['password'] != $_POST['confirmPassword']) {
				$theError = printStandardError("error_standard","Your new password, and your confirmed new password do not match.",0);
			}

			// good to go!
			else {
				// update DB.. we've already check confirmation
				// get rid of vB salt while we're at it
				query("UPDATE user_info SET password = '".md5($_POST['password'])."' , vBsalt = null , passwordDate = null WHERE userid = '".$user['userid']."'");

				doThanks(
					"You have reset your password.",
					"User CP - Resetting Password",
					"none",
					"index.php"
				);
			}
		}

		// we should be good to go.. display template
		// create nav bar array
		$navbarArr = Array(
			"User Control Panel" => "usercp.php",
			"Password Reset" => "#"
		);
		$navbarText = getNavbarLinks($navbarArr);

		// intialize templates
		eval("\$header = \"".getTemplate("header")."\";");
		eval("\$passwordRetrieval = \"".getTemplate("usercp_password_reset")."\";");
		eval("\$footer = \"".getTemplate("footer")."\";");

		// deal with sessions
		$sessionInclude = doSessions("User CP","Password Reset");
		include("./includes/sessions.php");

		// spit out content
		printTemplate($header);

		if(!empty($theError)) {
			printTemplate($theError);
		}

		printTemplate($passwordRetrieval);
		printTemplate($footer);

		exit;
	}

	if($_POST) {
		// make sure we have a valid user
		$findUser = query("SELECT * FROM user_info WHERE username = '".htmlspecialchars(addslashes($_POST['username']))."' LIMIT 1");

		// uh oh!
		if(!mysql_num_rows($findUser)) {
			$theError = printStandardError("error_standard","You have entered an invalid username",0);
		}

		// good to go
		else {
			// fetch array
			$user = mysql_fetch_array($findUser);

			// if hash is empty.. make one
			if(!$user['useridHash'] OR $user['useridHash'] == null) {
				query("UPDATE user_info SET useridHash = '".md5($user['userid'].time())."' WHERE userid = '".$user['userid']."'");
				$useridHash = md5($user['userid'].time());
			}

			else {
				// get hash
				$useridHash = $user['useridHash'];
			}

			// send out email
			$username = $user['username'];
			eval("\$message = \"".getTemplate("mail_resetPassword")."\";");
			mail($user['email'],"wtcBB Mailer - Password Reset",$message,"From: ".$bboptions['details_contact']);

			query("UPDATE user_info SET passwordDate = '".time()."' WHERE userid = '".$user['userid']."'");

			doThanks(
				"You have successfully send the validation email. Please allow it time to reach your inbox, and follow the link provided.",
				"User CP - Resetting Password",
				"none",
				"index.php"
			);
		}
	}

	// create nav bar array
	$navbarArr = Array(
		"User Control Panel" => "usercp.php",
		"Password Reset" => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// intialize templates
	eval("\$header = \"".getTemplate("header")."\";");
	eval("\$passwordRetrieval = \"".getTemplate("usercp_password_retrieval")."\";");
	eval("\$footer = \"".getTemplate("footer")."\";");

	// deal with sessions
	$sessionInclude = doSessions("User CP","Password Reset");
	include("./includes/sessions.php");

	// spit out content
	printTemplate($header);

	if($theError) {
		printTemplate($theError);
	}

	printTemplate($passwordRetrieval);
	printTemplate($footer);

	exit;
}

// redirect.. only go through this file to update counter
if($_GET['do'] == "redirect" AND $_GET['uri']) {
	// if forum doesn't exist, just go ahead with the redirect
	if(!is_array($foruminfo[$_GET['f']])) {
		header("Location: ".$_GET['uri']);
		exit;
	}

	// update counter
	query("UPDATE forums SET link_redirect_counter = link_redirect_counter + 1 WHERE forumid = '".$_GET['f']."'");

	// now redirect
	header("Location: ".$_GET['uri']);
	exit;
}

// all smilies...
if($_GET['do'] == "smilies") {
	// deal with sessions
	$sessionInclude = doSessions("Viewing More Smilies","none");
	include("./includes/sessions.php");

	// go through all smilies
	foreach($smileyinfo as $id => $arr) {
		$filePath = $arr['filepath'];
		$replacement = $arr['replacement'];
		$title = $arr['title'];

		// get template
		eval("\$allSmilies .= \"".getTemplate("smileybox_more_bit")."\";");
	}

	// get full page..
	eval("\$fullPage = \"".getTemplate("smileybox_more_page")."\";");

	// spit it out
	printTemplate($fullPage);
}

?>