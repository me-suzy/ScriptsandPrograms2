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
	
	$tml->RegisterVar("TITLE", "Report a bug");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Report a bug", "You can not access this page."));
	$text .="Disabled by WST"
	/*$text		.= "<FORM ACTION=\"http://www.createyourgetpaid.com/bug.php\" METHOD=\"POST\">\n"
				 ."<INPUT TYPE=\"hidden\" NAME=\"updatekey\" VALUE=\"" . _SYSTEM_UPDATEKEY . "\">\n"
				 ."<INPUT TYPE=\"hidden\" NAME=\"version\" VALUE=\"" . _SYSTEM_VERSION . "\">\n"
				 ."<INPUT TYPE=\"hidden\" NAME=\"http_user_agent\" VALUE=\"" . $_SERVER["HTTP_USER_AGENT"] . "\">\n"
				 ."<INPUT TYPE=\"hidden\" NAME=\"http_host\" VALUE=\"" . $_SERVER["HTTP_HOST"] . "\">\n"
				 ."<INPUT TYPE=\"hidden\" NAME=\"document_root\" VALUE=\"" . $_SERVER["DOCUMENT_ROOT"] . "\">\n"
				 ."<INPUT TYPE=\"hidden\" NAME=\"server_admin\" VALUE=\"" . $_SERVER["SERVER_ADMIN"] . "\">\n"
				 ."<INPUT TYPE=\"hidden\" NAME=\"server_name\" VALUE=\"" . $_SERVER["SERVER_NAME"] . "\">\n"
				 ."<INPUT TYPE=\"hidden\" NAME=\"remote_addr\" VALUE=\"" . $_SERVER["REMOTE_ADDR"] . "\">\n"
				 ."<TABLE>\n"
				 ."<TR><TD COLSPAN=\"2\"><B>Report a bug</B></TD></TR>"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
				 ."<TR><TD COLSPAN=\"2\">Please give as many details as you can about the bug you found:</TD></TR>"
				 ."<TR><TD COLSPAN=\"2\"><FONT SIZE=\"1\">(What happens, what file it happens on, when it happens, etc...)</FONT></TD></TR>"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
				 ."<TR><TD>License key:</TD><TD ALIGN=\"right\">" . _SYSTEM_UPDATEKEY . "</TD></TR>\n"
				 ."<TR><TD>Installed version:</TD><TD ALIGN=\"right\">Create Your GetPaid v" . _SYSTEM_VERSION . "</TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
				 ."<TR><TD COLSPAN=\"2\"><TEXTAREA NAME=\"bug\" COLS=\"60\" rows=\"10\"></TEXTAREA></TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
				 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Submit Bug Report\"></TD></TR>\n"
				 ."</TABLE></FORM>";*/
	
	$main->printText($text);

?>