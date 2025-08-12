<?php
/*  tell-a-friend 2.1
    revision 7 4/18/04
    12-31-2003 (c) neoprogrammers.com

    Thanks go to the following for beta testing:
    -David Catt of CTS Innovations Inc. (www.standready.biz) (addressed register globals issue, textarea edit problem)
    -Abeer Ali (idea for send as html option and fixing problems w/ usage notification)
    -Roland Munyard (www.soundbyte.co.uk) (use of [REFPAGE] variable to show referring page in message)

    I've tried to make configuration easy below but am too lazy to make a true readme file so if you need help
    email drew@drew-phillips.com w/ questions.

    Enjoy and I hope this script does what you need. :)
*/


//Configuration Below

$numFriends = 5;
//how many form fields for friends' email addresses to show.

$webmasterEmail = "drew@drew-phillips.com";
//your email address, used for sending notifications

$returnPage = "http://www.site.com/returnpage.ext";
//after a person sends a message, a link will be shown for them to click in order to 
//return to a page on your site.  here is where you specify that page.

$sendNotification = 1;
//whether or not to notify you when someone completes the form. 1 for yes, 0 for no

$subject = "Site Recommendation from [SENDER_NAME] ([SENDER_EMAIL])";
//the subject line of sent messages.  [SENDER_NAME] will be replaced with the actual sender name
//and [SENDER_EMAIL] will be replaced with the senders email address.

$useHeader = 0;
$headerFile = "header.html";
//If $useHeader is set to 1, then the file in $headerFile will be included at the top
//of the script as html code.  Use this to add colors or include your website's layout
//with this script in the middle.  this is an absolute path and can be any file type (.php, .htm)

$useFooter = 0;
$footerFile = "header.html";
//Same as $useHeader except this will appear at the bottom of the recommend script.

$recommendMessage = <<<EOD
Hello,
Your friend thought you would like this site because it features...
Come by and check it out at HTTP://WWW.YOURSITE.COM today.
Your friend came from [REFPAGE] on our site.  
You can do lots of things there and read all about ...
We hope you stop by!
EOD;
//This is the message you, the site owner write that CANNOT be edited by the sender.
//This message will appear to all who receive messages from friends.  It is what will
//hopefully get the friend to click to your site (aside from a friend recommending it).
//Start editing on the line AFTER <<<EOD and stop editing before EOD;  be sure to leave
//those two lines the way they are or you will receive an error.

//NEW:  If you place [REFPAGE] in this text, it will be replaced with the page that the person
//clicked to the tell a friend page from.  Good for sites with many pages with different content.
//Keep in mind some browsers hide or change the referring page, so use this at your own risk.



$customMessage = <<<EOD
Enter A Personal Message To Your Friend Here If You Wish
EOD;
//This is a box that the sender can edit if they wish to enter a personal 
//message to their friend if they wish.  You can make it say whatever you want
//but make sure they know they can edit it for personalization.

$sendAsHtml = 0;
//Set to 1 to send messages as html (note some email clients can not display html emails and
//will result in the recipient seeing the html code with their message.

$htmlHeader = <<<EOD
<body bgcolor="#E0E0E0" text="#00ff00" link="#0000ff">
<center><b>Recommend Message</b></center><br>
<center><img src="http://www.yoursite.com/logo.png"></center>
<br><br>
EOD;
//the above html will show up at the top of the message to set up
//the colors and anything else you'd like to be in the email. if $sendAsHtml is set to 1
//this will show up in the message, otherwise it wont.


//thats it, nothing else needs to be done.  if you had trouble, feel free to email me (drew@drew-phillips.com)
//and ill do my best to help you.  if you have any comments or bugs to report, let me know there too please.


###############################################################################################
###############################################################################################
##                                                                                           ##
##     END CONFIGURATION - NO NEED TO GO BELOW UNLESS YOU REALLY KNOW WHAT YOU ARE DOING     ##
##                                                                                           ##
###############################################################################################
###############################################################################################

error_reporting(E_ERROR); // only stop for critical errors

/* start main */
if(!isset($_POST["action"])) {
	if($useHeader) include($headerFile);
	show_form();
	if($useFooter) include($footerFile);
} else {
	//begin error checking
	if(trim($_POST['senderName']) == "") {
		$error[] = "You did not enter your name.";
	}

	$_POST['senderEmail'] = trim($_POST['senderEmail']);

	if(!isValid($_POST['senderEmail'])) {
		$error[] = "Your email address appears to be invalid.";
		unset($_POST['senderEmail']);
	}

	$validRecips = check_valid($_POST['friend']);

	if($validRecips == FALSE) {
		$error[] = "No recipient email addresses were valid.";
	}
	//end error checking

	if($useHeader) include($headerFile);

	if($error) {
		echo "<center>\n";
		foreach($error as $oneError) {
			echo "$oneError<br>\n";
		}
		echo "</center>\n";

		echo show_form();

		if($useFooter) include($footerFile);

	} else { //no error
		$subject = str_replace("[SENDER_NAME]", $_POST['senderName'], $subject);
		$subject = str_replace("[SENDER_EMAIL]", $_POST['senderEmail'], $subject);


		$message = trim($_POST['recommendMessage']) . "\n\n" . $_POST['customMessage'] . "\n\n\n_________________________________________\n"
			   ."Note: This is not spam.  A friend sent you this message from ". $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] .""
		         ."  If you feel that you received this in error, contact $webmasterEmail with the sender's IP " . $_SERVER[REMOTE_ADDR] . ", otherwise delete and disreguard this email.  By receiving this "
			   ."email, you have NOT been added to any list and your email address has NOT been recorded in any way.";

		echo "<!-- Powered By - Tell A Friend 2.0 (http://www.drew-phillips.com) -->\n\n";
		//Invisible HTML Comment.  Take out or leave as you desire.

		echo "<center>\n";

		if($_POST['toself'] == TRUE) {
			array_push($validRecips, $_POST['senderEmail']);
		}

		$content = ($sendAsHtml == TRUE ? "text/html" : "text/plain");
		$message = stripslashes($message);
		if($sendAsHtml == 1) $message = $htmlHeader . nl2br(trim($message));


		$headers = "From: " . $_POST['senderName'] . " <" . $_POST['senderEmail'] . ">\r\n";
		$headers .= "X-Mailer: Tell-A-Friend 2.0 (neoprogrammers.com)\r\n";
		$headers .= "Content-Type: $content";

		foreach($validRecips as $recip) {
			$friends .= $recip . " ";
			@mail($recip, $subject, $message, $headers);
			echo "Message sent to $recip<br>\n";
		}
		
		echo "</center>\n<br>\n<center><b>Thank you for referring this website.</b><br><br>\nClick <a href=\"$returnPage\" target=\"_self\">here</a> to "
		    ."return to the site or <a href=\"".$_SERVER['PHP_SELF']."?sn=".urlencode($_POST['senderName'])."&se=".urlencode($_POST['senderEmail'])
		    ."\">here</a> to refer more friends.</center><br><br>\n\n";

		if($useFooter) include($footerFile);

		if($sendNotification) {
			//build message string here.

			$nMessage = $_POST['senderName'] . " (" . $_POST['senderEmail'] . ") " . $_SERVER['REMOTE_ADDR'] . " filled out your recommend form on your site and sent to " . count($validRecips) . " friends ($friends).";
			@mail($webmasterEmail, "Your site was recommended.", $nMessage, "From: $webmasterEmail");
		}
	}
}
/* end main */
		

	
	



/* mixed */ function check_valid($list) 
{
	global $errorCode;
	
	$numElements = sizeof($list);
	$goodEmails = array();

	foreach($list as $single) {
		if(isValid($single)) {
			$goodEmails[] = $single;
		}
	}

	if(!empty($goodEmails)) {
		return array_unique($goodEmails);
	} else {
		return FALSE;
	}
}


/* void */ function show_form()
{
	global $numFriends, $validRecips, $recommendMessage, $customMessage;

	$senderName  = (!isset($_GET['sn']) ? $_POST['senderName'] : urldecode($_GET['sn']));
	$senderEmail = (!isset($_GET['se']) ? $_POST['senderEmail'] : urldecode($_GET['se']));
	//just assign the sender vars either their get value(if set) otherwise the post val, even if blank

	echo "<!-- Powered By - Tell A Friend 2.0 (http://www.neoprogrammers.com) -->\n\n";
	//Invisible HTML Comment.  Take out or leave as you desire.

	echo "<form method=\"POST\" action=\"".$_SERVER['PHP_SELF']."\">\n"
	    ."<input type=\"hidden\" name=\"action\" value=\"submit\">\n"
	    ."<table border=0 align=\"center\" cellpadding=5 cellspacing=0>\n"
	    ."\t<tr>\n"
	    ."\t\t<td>Your Name:</td><td><input type=\"text\" name=\"senderName\" value=\"$senderName\" size=30></td>\n"
	    ."\t</tr>\n"
	    ."\t<tr>\n"
	    ."\t\t<td>Your Email:</td><td><input type=\"text\" name=\"senderEmail\" value=\"$senderEmail\" size=30></td>\n"
	    ."\t</tr>\n";

	for($i = 1; $i <= $numFriends; $i++) {
			echo "\t<tr>\n\t\t<td>Friend $i:</td><td><input type=\"text\" name=\"friend[]\" value=\"".$validRecips[$i - 1]."\" size=30></td>\n\t</tr>\n";
	}

	if (strpos($recommendMessage, "[REFPAGE]") !== FALSE) {
		if (!isset($_SERVER['HTTP_REFERER']))
			$recommendMessage = str_replace("[REFPAGE]", "(Referrer Unavailable)", $recommendMessage);
		else
			$recommendMessage = str_replace("[REFPAGE]", $_SERVER['HTTP_REFERER'], $recommendMessage);
	}


	echo "\t<tr>\n"
	    ."\t\t<td colspan=2><textarea name=\"recommendMessage\" cols=37 rows=5 readonly onFocus=\"this.blur()\">$recommendMessage</textarea></td>\n"
          ."\t</tr>\n"
	    ."\t<tr>\n"
	    ."\t\t<td colspan=2><textarea name=\"customMessage\" cols=37 rows=5>$customMessage</textarea></td>\n"
	    ."\t</tr>\n"
	    ."\t<tr>\n\t\t<td colspan=2><input type=\"checkbox\" name=\"toself\">&nbsp;&nbsp;Check this to send a copy to yourself</td>\n\t</tr>\n"
	    ."\t<tr>\n\t\t<td colspan=2><input type=\"submit\" value=\"Send Message\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"reset\" value=\"Clear Form\"></td>\n\t</tr>\n"
	    ."\t<tr>\n\t\t<td colspan=2><font size=1>Note: For your privacy, neither your email or your friends' email addresses are stored in any way.</font></td>\n\t</tr>\n"
	    ."</table>\n"
	    ."</form>\n\n";
}



/* bool */ function isValid($email)
{
	return eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,4}$", $email);
}


?>


