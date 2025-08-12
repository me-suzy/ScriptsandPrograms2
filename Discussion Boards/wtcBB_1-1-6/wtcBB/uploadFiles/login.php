<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################## //FRONT END - LOGIN\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./global.php");

// if no css file.. get internetl block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

if(strpos($_SERVER['HTTP_REFERER'],"login.php") !== false OR !$_SERVER['HTTP_REFERER']) {
	$_SERVER['HTTP_REFERER'] = "index.php";
}

// what if we want to logout??
if($_GET['do'] == "logout" AND $_COOKIE['wtcBB_Userid']) {
	// delete cookies and update last visit...
	foreach($_COOKIE as $name => $value) {
		// make sure we have right cookie...
		if(strpos($name,"wtcBB_") !== false AND $name != "wtcBB_prefs") {
			setcookie($name,"",time()-100000,$bboptions['cookie_path'],$bboptions['cookie_domain']);
		}
	}

	// update lastvisit and lastactivity...
	query("UPDATE user_info SET lastvisit = lastactivity , lastactivity = '".time()."' WHERE userid = '".$userinfo['userid']."'");

	doThanks(
		"Thank you for logging out. If you are not redirected, or you do not wish to wait, you can click the link below.",
		"Logging Out",
		"none",
		$_SERVER['HTTP_REFERER']
	);
}

// error.. already logged out!
else if($_GET['do'] == "logout") {
	doError(
		"Sorry, you are already logged out.",
		"Error Logging Out"
	);
}

// if cookie is already set.. print error and get out!
if($_COOKIE['wtcBB_Userid'] AND $_COOKIE['wtcBB_Password']) {
	// grab userinfo
	$userinfo_check_q = query("SELECT username,password FROM user_info WHERE userid = ".addslashes($_COOKIE['wtcBB_Userid']));

	if(mysql_num_rows($userinfo_check_q)) {
		$userinfo_check = mysql_fetch_array($userinfo_check_q);

		if($userinfo_check['password'] == $_COOKIE['wtcBB_Password']) {
			doError(
				"Sorry, you are already logged in.",
				"Error Logging In"
			);
		}
	}
}

// make sure we have set variables... and login is confirmed
if($_REQUEST['username'] AND $_REQUEST['password'] AND confirmLogin($_REQUEST['username'],md5(addslashes($_REQUEST['password'])))) {
	// get userid...
	$userinfo_userid = query("SELECT * FROM user_info WHERE username = '".htmlspecialchars(addslashes($_REQUEST['username']))."' LIMIT 1",1);

	// remember or not?
	if($_REQUEST['remember_me']) {
		$remember = time() + (60*60*24*365);
	} else {
		$remember = null;
	}

	// set cookie!
	setcookie("wtcBB_Userid",$userinfo_userid['userid'],$remember,$bboptions['cookie_path'],$bboptions['cookie_domain']);
	setcookie("wtcBB_Password",$userinfo_userid['password'],$remember,$bboptions['cookie_path'],$bboptions['cookie_domain']);

	if(strpos($_SERVER['HTTP_REFERER'],"postreply.php") !== false) {
		if($_REQUEST['quoteArr']) {
			foreach($_REQUEST['quoteArr'] as $key => $value) {
				$quoteInformation = "&amp;quoteArr[".$key."]=".$value;
			}
		}

		else {
			$quoteInformation = '';
		}

		$_SERVER['HTTP_REFERER'] = $_SERVER['HTTP_REFERER']."?t=".$_REQUEST['t'].$quoteInformation;
	}

	else if(strpos($_SERVER['HTTP_REFERER'],"postthread.php") !== false) {
		$_SERVER['HTTP_REFERER'] = $_SERVER['HTTP_REFERER']."?f=".$_REQUEST['f'];
	}

	doThanks(
		"Thank you for logging in. If you are not redirected, or you do not wish to wait, you can click the link below.",
		"Logging In",
		"none",
		$_SERVER['HTTP_REFERER']
	);
}

// error of some sort!
else {
	doError(
		"Your login was unsuccessful, please try again. If you have forgotten your password, please see the <a href=\"other.php?do=password\">password retrieval form</a>.",
		"Error Logging In"
	);
}

// wrrrrrrrap it up!
wrapUp();

?>