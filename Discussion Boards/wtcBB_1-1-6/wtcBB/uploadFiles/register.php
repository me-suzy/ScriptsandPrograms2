<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //FRONT END - REGISTER\\ ################ \\
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

// act-i-va-tion time.. c'mon!
if($_GET['do'] == "activate") {
	// select from db
	$user_q = query("SELECT * FROM user_info WHERE useridHash = '".$_GET['hash']."' LIMIT 1");

	// if no rows.. error
	if(!mysql_num_rows($user_q)) {
		doError(
			"There is no user in the database found with that activation code.",
			"Error Activating Account",
			"No Account Detected"
		);
	}

	// fetch array
	$user = mysql_fetch_array($user_q);

	// if not user, or already activated...
	if($user['userid'] != $userinfo['userid'] OR ($user['usergroupid'] != 3 AND !$user['is_coppa']) OR (!$user['is_coppa'] AND $user['usergroupid'] == $bboptions['usergroup_coppa_redirect'])) {
		doError(
			"You must be logged into the account you are trying to activate. If you are, then your account has already been activated.",
			"Error Activating Account",
			"Already Activated/Not Same User"
		);
	}

	// if coppa change "is_coppa" to 0 ;)
	if($user['is_coppa']) {
		query("UPDATE user_info SET is_coppa = 0 WHERE userid = '".$user['userid']."'");
	} else {
		// find user title...
		if($usergroupinfo[$bboptions['usergroup_redirect']]['usertitle']) {
			$theUserTitle = $usergroupinfo[$bboptions['usergroup_redirect']]['usertitle'];
		}

		// otherwise.. we must get the user title from the usertitles
		else {
			if(is_array($usertitles)) {
				// loop through user titles
				foreach($usertitles as $counter => $arr) {
					// make sure we have the next counter
					// if not.. simply give the usertitle the current.. there's nothin left!
					if(!is_array($usertitles[($counter + 1)])) {
						$theUserTitle = $arr['title'];
						break;
					}

					// now we compare...
					if($userinfo['posts'] >= $arr['minimumposts'] AND $userinfo['posts'] < $usertitles[($counter + 1)]['minimumposts']) {
						// yay!
						$theUserTitle = $arr['title'];
						break;
					}
				}
			}
		}

		// if it's still empty... set to "Registered Member".. since we have to be registered in order to be here
		if(!$theUserTitle) {
			$theUserTitle = "Registered Member";
		}

		// redirect to primary usergroup...
		query("UPDATE user_info SET usergroupid = '".$bboptions['usergroup_redirect']."' , usertitle = '".$theUserTitle."' WHERE userid = '".$user['userid']."'");
	}

	// send off emails...
	$username = $user['username'];
	eval("\$message = \"".getTemplate("mail_welcomeEmail")."\";");
	mail($user['email'],"wtcBB Mailer - Welcome to ".$bboptions['details_boardname']."!",$message,"From: ".$bboptions['details_contact']);

	doThanks(
		"Activation is complete. You may now post messages, if the Administrator has allowed it for your current usergroup.",
		"Activation Complete",
		"none",
		"index.php"
	);
}

// user already registered
if($userinfo['userid']) {
	doError(
		"It has been detected that you have already registered, ".$userinfo['username'].".",
		"Error Registering",
		"Already Registered"
	);
}

// registering disabled?
if(!$bboptions['allow_new_registrations']) {
	doError(
		"We are accepting no new registerations at this time, sorry.",
		"Error Registering",
		"Registering Disabled"
	);
}

if($coppa == "yes" AND ($bboptions['use_coppa'] == "Block registrations to users under 13" OR $bboptions['use_coppa'] == "Disable COPPA form")) {
	doError(
		"There has been an error while processing your registration. Please restart registration by clicking <a href=\"register.php\">here</a>.",
		"Error Registering",
		"Wrong Entrance"
	);
}

// deal with sessions
$sessionInclude = doSessions("Registering","none");
include("./includes/sessions.php");

// create nav bar array
$navbarArr = Array(
	"Registering" => "#"
);
$navbarText = getNavbarLinks($navbarArr);

// info...
if($_GET['do'] == "info") {
	// add user account!
	if($_POST) {
		// addslashes...
		foreach($_POST as $key => $value) {
			$$key = htmlspecialchars(addslashes($value));
		}

		// check email unqiuness.. if it so requested
		if($bboptions['require_unique_email']) {
			$checkEmail = query("SELECT COUNT(*) AS counting FROM user_info WHERE email = '".$_POST['email']."'",1);
		}

		$error = processUsername($_POST['username']);

		// make sure referrer exists...
		$checkReferrer = query("SELECT * FROM user_info WHERE username = '".$referrer."' LIMIT 1");

		if(!$username OR !$password OR !$passwordConfirm OR !$email OR ($coppa == "yes" AND !$parentEmail)) {
			$theError = printStandardError("error_standard","You must fill out every field in the 'Required Registration Information' Section.",0);
		}

		// passwords don't match
		else if($_POST['password'] != $_POST['passwordConfirm']) {
			$theError = printStandardError("error_standard","You passwords do not match, please re-check your information.",0);
		}

		else if($bboptions['require_unique_email'] AND $checkEmail['counting'] > 0) {
			$theError = printStandardError("error_standard","Sorry, you may not use an email address that is already in use.",0);
		}

		else if(gettype($error) != "boolean") {
			$theError = printStandardError("error_standard",$error,0);
		}

		else if(!mysql_num_rows($checkReferrer) AND $referrer) {
			$theError = printStandardError("error_standard","The referrer you entered, does not exist at this message board.",0);
		}

		// yay finally...
		else {
			// is coppa?
			if($coppa == "yes") {
				// get the usergroup
				$usergroupid = $bboptions['usergroup_coppa_redirect'];
				$isCoppa = 1;
				$email_parent = $parentEmail;
			} else {
				$isCoppa = 0;
				$email_parent = "";
				
				if($bboptions['verify_email']) {
					$usergroupid = 3;
				} else {
					$usergroupid = $bboptions['usergroup_redirect'];
				}
			}

			if($usergroupinfo[$usergroupid]['usertitle']) {
				$theUserTitle = $usergroupinfo[$usergroupid]['usertitle'];
			}

			// otherwise.. we must get the user title from the usertitles
			else if(is_array($usertitles)) {
				$theUserTitle = $usertitles[$x]['title'];
			}

			// if it's still empty... set to "Registered Member".. since we are registering...
			else {
				$theUserTitle = "Registered Member";
			}

			// form insert query...
			$insertUser = "INSERT INTO user_info (username,password,email,usergroupid,date_joined,user_ip_address,referral_username,lastvisit,lastactivity,receive_emails,use_pm,view_posts,date_timezone,usertitle_option,parent_email,is_coppa,allow_html,ban_sig,dst,auto_threadsubscription,posts,threads,birthday,lastpost,usertitle,date_default_thread_age,display_order,popup_pm,warn) VALUES ('".$username."','".md5($password)."','".$email."','".$usergroupid."','".time()."','".$_SERVER['REMOTE_ADDR']."','".$referrer."','".time()."','".time()."','".$receive_emails."','".$receviePM."',-1,'".$gmtOffset."',0,'".$email_parent."','".$isCoppa."',0,0,'".$enableDST."',0,0,0,null,null,'".addslashes($theUserTitle)."',-1,'ASC',1,0)";

			// run query
			query($insertUser);

			$useridHash = md5(mysql_insert_id().time());

			// set cookie!
			setcookie("wtcBB_Userid",mysql_insert_id(),0,$bboptions['cookie_path'],$bboptions['cookie_domain']);
			setcookie("wtcBB_Password",md5($password),0,$bboptions['cookie_path'],$bboptions['cookie_domain']);

			// update user with hash
			query("UPDATE user_info SET useridHash = '".$useridHash."' WHERE userid = '".mysql_insert_id()."'");

			// send activation if it requires
			if($bboptions['verify_email'] AND $coppa == "no") {
				eval("\$message = \"".getTemplate("mail_activation")."\";");
				mail($email,"wtcBB Mailer - Activation Email",$message,"From: ".$bboptions['details_contact']);
			}

			else if($coppa == "yes") {
				eval("\$message = \"".getTemplate("mail_coppaActivation")."\";");
				mail($email_parent,"wtcBB Mailer - Parent Consent Email",$message,"From: ".$bboptions['details_contact']);
			}

			// send welcome email
			else if(!$bboptions['verify_email'] AND $coppa == "no") {
				eval("\$message = \"".getTemplate("mail_welcomeEmail")."\";");
				mail($email,"wtcBB Mailer - Welcome to ".$bboptions['details_boardname']."!",$message,"From: ".$bboptions['details_contact']);
			}

			// send new user notification
			if($bboptions['notify_email_new']) {
				eval("\$message2 = \"".getTemplate("mail_newUserNotification")."\";");
				mail($bboptions['notify_email_new'],"wtcBB Mailer - New Member Notification",$message2,"From: ".$bboptions['details_contact']);
			}

			// update referrer
			query("UPDATE user_info SET referrals = referrals + 1 WHERE username = '".$referrer."'");

			// the message.. depends on coppa and verification
			if($coppa == "yes") {
				$thanks = "You have successfully completed this part of registration. An email has been sent to your parent. You will not be able to post messages until your parent confirms consent. If your parent does not give consent within <strong>seven days</strong> all current information will be deleted.";
			}

			else {
				if($bboptions['verify_email']) {
					$thanks = "You have successfully completed this part of registration. An email has been sent to the address you provided. The email will have further directions to complete your registration process. You may also edit your <a href=\"usercp.php?do=profile\">profile</a> where you can fill out personal information, or you may edit your <a href=\"usercp.php?do=preferences\">preferences</a> to enhance your forum browsing experience. Both of these things, and more can be located in the <a href=\"usercp.php\">User Control Panel</a>.";
				}

				else {
					$thanks = "Your registration has successfully been completed. You may also edit your <a href=\"usercp.php?do=profile\">profile</a> where you can fill out personal information, or you may edit your <a href=\"usercp.php?do=preferences\">preferences</a> to enhance your forum browsing experience. Both of these things, and more can be located in the <a hre=\"usercp.php\">User Control Panel</a>.";
				}
			}

			doThanks(
				$thanks,
				"Registration Complete",
				"none",
				"index.php"
			);
		}
	}

	// generate GMT 
	$gmDate0 = gmdate("h");
	$minutes = gmdate(":i");

	for($x = -12; $x <= 12; $x++) {
		$theTime = $gmDate0 + $x;

		if($x == 0) {
			$x = "";
		}

		if($theTime > 12) {
			$theTime -= 12;
		} else if($theTime < 1) {
			$theTime += 12;
		}

		if($bboptions['date_timezone'] == $x) {
			$selected = ' selected="selected"';
		} else {
			$selected = "";
		}

		// plus sign?
		if($x > 0) {
			$plusSign = "+";
		} else {
			$plusSign = '';
		}

		$optionBits .= '<option value="'.$x.'"'.$selected.'>GMT '.$plusSign.$x.' ('.$theTime.$minutes.')</option>\n';
	}

	if($coppa == "yes") {
		eval("\$coppaParent = \"".getTemplate("register_info_coppa")."\";");
	} else {
		$coppaParent = "";
	}

	eval("\$register = \"".getTemplate("register_info")."\";");
}

// disagree
else if($_GET['do'] == "disagree") {
	eval("\$register = \"".getTemplate("register_disagree")."\";");
}

// do the rules...
else if($_GET['do'] == "rules") {
	// if coppa get extra description
	if($_GET['coppa'] == "yes") {
		eval("\$coppaDesc = \"".getTemplate("register_rules_coppa")."\";");
	} else {
		$coppaDesc = "";
	}

	eval("\$register = \"".getTemplate("register_rules")."\";");
}

// check coppa
else if(!$_SERVER['QUERY_STRING'] AND ($bboptions['use_coppa'] == "Enable COPPA form" OR $bboptions['use_coppa'] == "Block registrations to users under 13")) {
	// handle the birthday
	if($_POST['check']) {
		// if any are empty.. then print error
		if(!$_POST['month'] OR !$_POST['day'] OR !$_POST['year']) {
			doError(
				"You must fill in all fields in order to continue."
			);
		}

		// now lets get the timestamp...
		$theStamp = mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']);

		// thirteen years ago...
		$thirteenAgo = mktime(0,0,0,date("m"),date("d"),(date("Y") - 13));

		// coppa or no?
		if($thirteenAgo >= $theStamp) {
			// no coppa
			header("Location: register.php?do=rules&coppa=no");
		} else {
			// display an error if we block coppa registrars
			if($bboptions['use_coppa'] == "Block registrations to users under 13") {
				doError(
					"You must be at least <strong>13 years old</strong> in order to register at this message board."
				);
			}

			// coppa!
			header("Location: register.php?do=rules&coppa=yes");
		}
	}

	// get the template
	eval("\$register = \"".getTemplate("register_DOB")."\";");
}

else if(!$_SERVER['QUERY_STRING']) {
	header("Location: register.php?do=rules&coppa=no");
}

// now grab all the templates...
eval("\$header = \"".getTemplate("header")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

// spit it out
printTemplate($header);

if($theError) {
	printTemplate($theError);
}

printTemplate($register);
printTemplate($footer);

wrapUp();

// exit!
exit;

?>