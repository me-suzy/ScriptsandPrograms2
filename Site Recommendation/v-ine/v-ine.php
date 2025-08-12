<?

////////////////////////////////////////////////////////////
//
// v-ine v1.5 - a Web site referral service
//
////////////////////////////////////////////////////////////
//
// This script allows your visitors to send recommendations
// of your site to their friends.
//
// See readme.txt for more information.
//
// Author: Jon Thomas <http://www.fromthedesk.com/code>
// Last Modified: 11/08/2005
//
// You may freely use, modify, and distribute this script.
//
////////////////////////////////////////////////////////////

//
// SET VARIABLES
//

// subject of referral e-mail
$subject = "Your friend recommended a Web site";

// default URL for referrals
$defaultURL = "";

// text of message sent if user does not supply his own
$defaultMsg = "Hi FRIEND_NAME, check out this great site!\n\nYour friend, " . $_POST['userName'];

// base address of your Web site
$siteURL = "";

// URL of "thanks" Web page
$thanksURL = "thanks.html";

// your e-mail for error reports and optional referral notices
$webmasterEmail = "";

// to receive referral notices, set to 1; otherwise, set to 0
$receiveNotices = 0;


//
// VALIDATE SUBMISSION
//

// do not allow other Web sites to use this script
if (!ereg($siteURL, $HTTP_REFERER)) {
	// send webmaster an error report
	mail($webmasterEmail, "v-ine Error Report", "The following Web address attempted to use your installation of v-ine:\n\n<" . $HTTP_REFERER . ">\n\n.If this address is legitimate, you must modify the \"siteURL\" variable in v-ine.php to permit this address to use v-ine.");

	// print an error message and terminate script
	die("<p><b>Error:</b> You are not allowed to use this script.  The webmaster of this site has been notified about your attempted use.</p>");
}

// include is_email() function file
include("is_email.inc");

// validate referrer name and e-mail address
if ($_POST['userName'] == "" || !is_email($_POST['userEmail'])) {
	// print an error message and terminate script if some information is missing or invalid
	die("<b>Error:</b> You must supply your name and e-mail address.  Please use your browser's \"Back\" button to complete the referral form.");
}


//
// validate multiple referrals (i.e., when "friendEmail" and "friendName" are arrays)
//
if (is_array($_POST['friendEmail']) && is_array($_POST['friendName'])) {
	// save POST referral data under simpler array names
	$friendEmail = $_POST['friendEmail'];
	$friendName = $_POST['friendName'];

	// delete empty elements in friendEmail array
	foreach ($friendEmail as $key => $value) {
		if ($value == "") {
			unset($friendEmail[$key]);
		}
	}

	// validate e-mail address and name for each referral
	foreach ($friendEmail as $key => $value) {
		// validate each e-mail address
		if (!is_email($value)) {
			// print an error message and terminate script if e-mail address is invalid
			die("<p><b>Error: " . $value . "</b> is an invalid e-mail address.  Please use your browser's \"Back\" button to correct this information.</p>");
		}

		// check that each e-mail address is accompanied by a personal name
		if ($friendName[$key] == "") {
			// print an error message and terminate script if a name is missing for this e-mail address
			die("<p><b>Error:</b> You must not supply a personal name for the e-mail address <b>" . $value . "</b>.  Please use your browser's \"Back\" button to supply this information.</p>");
		}
	}
}


//
// validate single referral (i.e., when "friendEmail" and "friendName" are variables)
//
else {
	// print an error message and terminate script if e-mail address is invalid
	if (!is_email($friendEmail)) {
		die("<p><b>Error: $friendEmail</b> is an invalid e-mail address.  Please use your browser's \"Back\" button to correct this information.</p>");
	}

	// print an error message and terminate script if a name is missing for this e-mail address
	if ($friendName == "") {
		die("<p><b>Error:</b> You must supply a personal name for the e-mail address <b>$friendEmail</b>.  Please use your browser's \"Back\" button to supply this information.</p>");
	}
}


//
// SEND REFERRALS
//

// get date and time
$datetime = date('l, F j \a\t g:i A T');

// use default URL if a URL is not supplied in this submission
if (!isset($_POST['url']) || $_POST['url'] == "") {
	$url = $defaultURL;
}

// otherwise, save this POST data under a simpler variable name
else {
	$url = $_POST['url'];
}

// use default message if a message is not supplied in this submission
if ($_POST['message'] == "A default message will be sent if you do not enter your own message." || $_POST['message'] == "") {
	$message = $defaultMsg;
}

// otherwise, save this POST data under a simpler variable name
else {
	$message = $_POST['message'];
}

// send multiple referrals
if (is_array($friendEmail)) {
	// send a referral to each friend
	foreach ($friendEmail as $key => $value) {
		// replace "FRIEND_NAME" with this friend's name if found in message
		$customMessage = ereg_replace("FRIEND_NAME", $friendName[$key], $message);

		// send referral
		mail($value, $subject, "$customMessage\n\nURL: $url", "From: $userEmail");
	}
}

// send single referral
else {
	// replace "FRIEND_NAME" with this friend's name if found in message
	$customMessage = ereg_replace("FRIEND_NAME", $friendName, $message);

	// send referral
	mail($friendEmail, $subject, "$customMessage\n\nURL: $url", "From: $userEmail");
}

// send webmaster a referral notice if he requests them
if ($receiveNotices) {
	// send notice for multiple referrals
	if (is_array($friendEmail)) {
		// create list of names and e-mail addresses
		foreach ($friendEmail as $key => $value) {
			$friendsList .= "$friendName[$key] <$value>\n";
		}

		mail($webmasterEmail, "v-ine Referral Notice", "$userName <$userEmail> referred\n\n$friendsList\nto $url on $datetime with the following message:\n\n$message");
	}

	// send notice for single referral
	else {
		mail($webmasterEmail, "v-ine Referral Notice", "$userName <$userEmail> referred $friendName <$friendEmail> to $url on $datetime with the following message:\n\n$message");
	}
}

// redirect user to "thanks" page
header("Location: $thanksURL");

?>