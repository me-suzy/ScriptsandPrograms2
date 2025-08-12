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
	
	$tml->RegisterVar("TITLE", "Deposits");
	
	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Deposits", "You can not access this page."));
	
	if($_GET["action"] == "delete")
	{
		$db->Query("SELECT id FROM deposits WHERE id='" . $_GET["did"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Deposits", "The ticket can not be found."));
		
		$db->Query("DELETE FROM deposits WHERE id='" . $_GET["did"] . "'");
		
		$main->printText("<B>Deposits</B><BR><BR>The deposit has been deleted.", 1);
	}
	else
	{
		$char	= $_GET["action"] == "paid" ? ">" : "=";
		
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>User ID</TD><TD>Amount</TD><TD>Method</TD>\n"
				 ."<TD>Paid By</TD><TD>Payment ID</TD><TD>Date</TD><TD></TD></TR>\n";
		
		$db->Query("SELECT id, uid, method, amount, payment_acct, dateStamp, payment_id FROM deposits WHERE payment_date${char}'0' ORDER BY dateStamp DESC LIMIT $start, 50");
		
		while($row = $db->NextRow())
		{
			$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
					  ."<TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["uid"] . "\">#" . $row["uid"] . "</A></TD>"
					  ."<TD>" . _ADMIN_CURRENCY . number_format($row["amount"], 2) . "</TD><TD>" . $row["method"] . "</TD><TD>" . (!$row["payment_acct"] ? "-" : $row["payment_acct"]) . "</TD>\n"
					  ."<TD>" . (!$row["payment_id"] ? "-" : $row["payment_id"]) . "</TD><TD>" . date(_SITE_DATESTAMP, $row["dateStamp"]) . "</TD>"
					  ."<TD><A HREF=\"" . _ADMIN_URL . "/deposits.php?sid=" . $session->ID . "&action=delete&did=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete Deposit\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text	.= "</TABLE>\n";
		
		$db->Query("SELECT id FROM deposits WHERE payment_date${char}'0'");
		
		$text	.= "<BR><TABLE WIDTH=\"100%\"><TR><TD ALIGN=\"center\">" . $main->GeneratePages(_ADMIN_URL . "/deposits.php?sid=" . $session->ID, $db->NumRows(), 50, $start) . "</TD></TR></TABLE>\n";
		
		$main->printText($text);
	}

?>