<?

////////////////////////////////////////////////////////////
//
// spiderMail v2.2 - a complex form mailer
//
////////////////////////////////////////////////////////////
//
// This script e-mails all the name and value pairs from
// a submitted form to the specified "to" address using HTML
// and e-mail templates.
//
// See readme.txt for more information.
//
// Author: Jon Thomas <http://www.fromthedesk.com/code>
// Last Modified: 10/15/2005
//
// You may freely use, modify, and distribute this script.
//
////////////////////////////////////////////////////////////

// define the variables
$defaultTo = "";
$defaultFrom = "";
$defaultSubj = "Form Submission";
$htmlTemplate = "tem_html.txt";
$emailTemplate = "tem_email.txt";
$noHTML = 1; // 1: strip HTML tags from form input; 0: do not strip HTML tags

// the URLs of the submission forms
$formURL[0] = "";
$formURL[1] = "";


// DO NOT EDIT BELOW THIS POINT UNLESS YOU KNOW PHP! //

// prevent off-site use
// try to match $HTTP_REFERER with one of the approved URLs in the $formURL array
for ($i = 0; $i < count($formURL); $i++) {
	// if there is a match, create a variable called $legal_use
	if (stristr($HTTP_REFERER, $formURL[$i]) != FALSE) {
		$legal_use = 1;
		break;
	}
}

// if $legal_use was not set, kill the script
if (!isset($legal_use)) {
	die("Illegal use.");
}

// include function files
include("is_email.inc");
include("replaceArrStrs.inc");
include("selectText.inc");

// if a "to" address is not provided, use the default
if (!isset($to) || $to == "") {
	// Set Default To Address
	$to = $defaultTo;
}

// if a "to" address is provided, validate it
else {
	// if the address is invalid, check for it in the address book
	if (!is_email($to)) {
		// get the address book entries
		$addressbook = file("addressbook.inc");
		$addressbook = join("", $addressbook);
		$entries = split("[\n ]", $addressbook);

		// for each entry
		for ($i = 0; $i < count($entries); $i = $i + 2) {
			// if the entry matches the "to" address
			if ($entries[$i] == $to) {
				// save alias name for HTML post
				$toAlias = $to;

				// replace the address with the entry address
				$n = $i + 1;
				$to = $entries[$n];

				// indicate a successful match
				$abook = 1;

				// stop scanning the entries
				break;
			}
		}

		// if there was not a successful match, print an error msg
		if (!$abook) {
			echo "<b>Error:</b> The \"to\" address, <b>$to</b>, is not a valid e-mail address or alias.";

			// and terminate the script
			exit();
		}

		// reset the match indicator
		unset($abook);
	}
}

// if a "from" address is not provided, use the default
if (!isset($from) || $from == "") {
	$from = $defaultFrom;
}

// if a "from" address is provided, validate it
else {
	// if the address is invalid, check for it in the address book
	if (!is_email($from)) {
		// get the address book entries
		$addressbook = file("addressbook.inc");
		$addressbook = join("", $addressbook);
		$entries = split("[\n ]", $addressbook);

		// for each entry
		for ($i = 0; $i < count($entries); $i = $i + 2) {
			// if the entry matches the "from" address
			if ($entries[$i] == $from) {
				// replace the address with the entry address
				$n = $i + 1;
				$from = $entries[$n];

				// indicate a successful match
				$abook = 1;

				// stop scanning the entries
				break;
			}
		}

		// if there was not a successful match, print an error msg
		if (!$abook) {
			echo "<b>Error:</b> The \"from\" address, <b>$from</b>, is not a valid e-mail or alias.";

			// and terminate the script
			exit();
		}
	}
}

// if a subject is not provided, use the default
if (!isset($subject)) {
	$subject = $defaultSubj;
}

// get the date and time
$datetime = date('l, F j \a\t g:i A T');

// get the complete templates
$html = file($htmlTemplate);
$email = file($emailTemplate);

// add header data to the templates
if (isset($toAlias)) {
	$html = preg_replace("/<!--TO-->/", $toAlias, $html);
	$email = preg_replace("/<!--TO-->/", $toAlias, $email);
}
else {
	$html = preg_replace("/<!--TO-->/", $to, $html);
	$email = preg_replace("/<!--TO-->/", $to, $email);
}

$html = preg_replace("/<!--FROM-->/", $from, $html);
$html = preg_replace("/<!--SUBJECT-->/", $subject, $html);
$html = preg_replace("/<!--FORM URL-->/", $HTTP_REFERER, $html);
$html = preg_replace("/<!--DATETIME-->/", $datetime, $html);
$email = preg_replace("/<!--FROM-->/", $from, $email);
$email = preg_replace("/<!--SUBJECT-->/", $subject, $email);
$email = preg_replace("/<!--FORM URL-->/", $HTTP_REFERER, $email);
$email = preg_replace("/<!--DATETIME-->/", $datetime, $email);

// add each name/value pair to the body
while(list($name, $value) = each($HTTP_POST_VARS)) {
	// do not include the script's internal data with the submission
	if ($name == "to" || $name == "from" || $name == "subject" || $name == "url") {
		continue;
	}

	// get the body pair templates
	$html_pair = selectText("tem_html.txt", "<!--BEGIN BODY-->", "<!--END BODY-->", 0);
	$email_pair = selectText("tem_email.txt", "<!--BEGIN BODY-->", "<!--END BODY-->", 0);	

	// add the name to this body pair
	$html_pair = preg_replace("/<!--NAME-->/", $name, $html_pair);
	$email_pair = preg_replace("/<!--NAME-->/", $name, $email_pair);

	// if the value is an array, print the values separated by commas
	if (is_array($value)) {
		// build a list of values
		for ($i = 0; $i < count($value); $i++)
		{
			// if this value is the last, don't print another comma
			if (count($value) == ($i + 1)) {
				$values .= "$value[$i]";
			}

			// otherwise, print a comma and space after this value
			else {
				$values .= "$value[$i], ";
			}
		}

		// add the values to this body pair
		$html_pair = preg_replace("/<!--VALUE-->/", $values, $html_pair);
		$email_pair = preg_replace("/<!--VALUE-->/", $values, $email_pair);
	}

	// otherwise, add the single value to this body pair
	else {
		// strip HTML tags
		if ($noHTML) {
			$value = strip_tags($value);
		}

		$html_pair = preg_replace("/<!--VALUE-->/", $value, $html_pair);
		$email_pair = preg_replace("/<!--VALUE-->/", $value, $email_pair);
	}

	// add this pair to the bodies
	$html_body .= $html_pair;
	$email_body .= $email_pair;
}

// add the bodies to the templates
$html = replaceArrStrs($html, "<!--BEGIN BODY-->", "<!--END BODY-->", 1, $html_body);
$email = replaceArrStrs($email, "<!--BEGIN BODY-->", "<!--END BODY-->", 1, $email_body);

// create strings from the template arrays
$html = join("", $html);
$email = join("", $email);

// if the submission fails, print an error msg
if(!mail($to, $subject, $email, "From: $from")) {
	echo "<b>Error:</b> Your mail was not sent.  Contact the webmaster for help.";

	// and terminate the script
	exit();
}

// if a url is set, take the user there
if (isset($url)) {
	header("Location: $url");
}

// otherwise, print the submission to the screen
else {
	echo $html;
}

?>