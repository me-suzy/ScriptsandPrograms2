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
	
	$tml->RegisterVar("TITLE", "Block List");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Blocklist", "You can not access this page."));
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$db->Query("UPDATE blocklist SET email='" . $_POST["email"] . "', remote_addr='" . $_POST["remote_addr"] . "', payment_account='" . $_POST["payment_account"] . "'");
		
		$main->WriteToLog("global", "Blocklist updated");
		
		$main->PrintText("<B>Blocklist</B><BR><BR>The new blocklist has been saved.", 1);
	}
	else
	{
		$data	= $main->Trim($db->Fetch("SELECT email, remote_addr, payment_account FROM blocklist"));
		
		if($_GET["uid"])
		{
			$userdata					= $db->Fetch("SELECT email, payment_account, remote_addr FROM users WHERE id='" . $_GET["uid"] . "'");
			
			$data["email"]				.= $data["email"] == "" ? $userdata["email"] : in_array($userdata["email"], explode("|", $data["email"])) ? "" : "|" . $userdata["email"];
			$data["payment_account"]	.= $data["payment_account"] == "" ? $userdata["payment_account"] : in_array($userdata["payment_account"], explode("|", $data["payment_account"])) ? "" : "|" . $userdata["payment_account"];
			$data["remote_addr"]		.= $data["remote_addr"] == "" ? $userdata["remote_addr"] : in_array($userdata["remote_addr"], explode("|", $data["remote_addr"])) ? "" : "|" . $userdata["remote_addr"];
		}
		
		$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/blocklist.php?sid=" . $session->ID . "\" METHOD=\"post\">\n"
				."<TABLE WIDTH=\"100%\">\n"
				."<TR><TD COLSPAN=\"2\"><B>Blocklist</B></TR>\n"
				."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				."<TR><TD VALIGN=\"top\">E-Mail Addresses:<BR><FONT SIZE=\"1\">Seperate with \"|\"</FONT></TD><TD><TEXTAREA NAME=\"email\" COLS=\"40\" ROWS=\"6\">" . $data["email"] . "</TEXTAREA></TD></TR>\n"
				."<TR><TD VALIGN=\"top\">IP Addresses:<BR><FONT SIZE=\"1\">Seperate with \"|\"</FONT></TD><TD><TEXTAREA NAME=\"remote_addr\" COLS=\"40\" ROWS=\"6\">" . $data["remote_addr"] . "</TEXTAREA></TD></TR>\n"
				."<TR><TD VALIGN=\"top\">Payment Accounts:<BR><FONT SIZE=\"1\">Seperate with \"|\"</FONT></TD><TD><TEXTAREA NAME=\"payment_account\" COLS=\"40\" ROWS=\"6\">" . $data["payment_account"] . "</TEXTAREA></TD></TR>\n"
				."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				."<TR><TD></TD><TD><INPUT TYPE=\"submit\" VALUE=\"Save Blocklist\"></TD></TR>\n"
				."</TABLE>"
				."</FORM>";
		
		$main->printText($text);
	}

?>