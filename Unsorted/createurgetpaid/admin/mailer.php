<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	// This script is copyrighted to CreateYourGetPaid©       \\
	// Duplication, selling, or transferring of this script   \\
	// is a violation of the copyright and purchase agreement.\\
	// Alteration of this script in any way voids any         \\
	// responsibility CreateYourGetPaid© has towards the      \\
	// functioning of the script. Altering the script in an   \\
	// attempt to unlock other functions of the program that  \\
	// have not been purchased is a violation of the          \\
	// purchase agreement and forbidden by CreateYourGetPaid© \\
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\

	$GLOBALS["adminpage"] = "yes";
	
	include "../lib/.htconfig.php";
	
	$tml->RegisterVar("TITLE", "Send an E-Mail");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Send an E-Mail", "You can not access this page."));
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$main->sendMail($_POST["to"], $main->Trim($_POST["subject"]), $main->Trim($_POST["body"]), $_POST["from"], $_POST["priority"], "plain");
		
		$main->PrintText("<B>Send an E-Mail</B><BR><BR>Your e-mail has been sent.", 1);
	}
	else
	{
		$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/mailer.php?sid=" . $session->ID . "\" METHOD=\"post\">\n"
				  ."<TABLE WIDTH=\"100%\">\n"
				  ."<TR><TD COLSPAN=\"2\"><B>Send an E-Mail</B></TR>\n"
				  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				  ."<TR><TD>From:</TD><TD><INPUT TYPE=\"text\" NAME=\"from\" VALUE=\"" . ($_GET["from"] == "" ? _SITE_EMAIL : $_GET["from"]) . "\" SIZE=\"30\"></TD></TR>\n"
				  ."<TR><TD>To:</TD><TD><INPUT TYPE=\"text\" NAME=\"to\" VALUE=\"" . $_GET["to"] . "\" SIZE=\"30\"></TD></TR>\n"
				  ."<TR><TD>Subject:</TD><TD><INPUT TYPE=\"text\" NAME=\"subject\" SIZE=\"30\"></TD></TR>\n"
				  ."<TR><TD>Priority:</TD><TD><SELECT NAME=\"priority\" SIZE=\"1\"><OPTION VALUE=\"5\">Low</OPTION><OPTION VALUE=\"3\" SELECTED>Normal</OPTION><OPTION VALUE=\"1\">High</OPTION></SELECT></TD></TR>\n"
				  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				  ."<TR><TD COLSPAN=\"2\" ALIGN=\"center\"><TEXTAREA NAME=\"body\" COLS=\"67\" ROWS=\"8\"></TEXTAREA></TD></TR>\n"
				  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" VALUE=\"Send E-Mail\"></TD></TR>\n"
				  ."</TABLE>"
				  ."</FORM>";
		
		$main->printText($text);
	}

?>