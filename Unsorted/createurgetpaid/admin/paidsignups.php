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

	$tml->RegisterVar("TITLE", "Paid Signups Manager");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Paid Sign-Ups Manager", "You can not access this page."));
	
	if($_GET["action"] == "cdelete")
	{
		$db->Query("SELECT id FROM received_signups WHERE id='" . $_GET["cid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Paid Sign-Ups Manager", "An error has occured."));
		
		$db->Query("UPDATE received_signups SET checked='1' WHERE id='" . $_GET["cid"] . "'");
		
		$SID	= $db->Fetch("SELECT sid FROM received_signups WHERE id='" . $_GET["cid"] . "'");
		$data	= $db->Fetch("SELECT max, active FROM paid_signups WHERE id='" . $sdata["sid"] . "'");
		
		$db->Query("SELECT id FROM received_signups WHERE sid='" . $_GET["cid"] . "' AND NOT (checked='1' AND credited='no')");
		
		if($db->NumRows() < $data["max"] && $data["active"] == "no")
		{
			$db->Query("UPDATE paid_signups SET active='yes' WHERE id='$SID'");
			
			$qText	= "<BR><BR>Campaign has been automatically activated again.";
		}
		
		$main->printText("<B>Paid Sign-Ups Manager</B><BR><BR>Confirmation disapproved." . $qText, 1);
	}
	elseif($_GET["action"] == "credit")
	{
		$db->Query("SELECT id FROM received_signups WHERE id='" . $_GET["cid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Paid Sign-Ups Manager", "An error has occured."));
		
		$confdata	= $db->Fetch("SELECT uid, sid FROM received_signups WHERE id='" . $_GET["cid"] . "'");
		$signdata	= $db->Fetch("SELECT aid, title, c_type, credits, max, mail FROM paid_signups WHERE id='" . $confdata["sid"] . "'");
		
		$user->Add2Actions($confdata["uid"], $confdata["sid"], "signup", $signdata["credits"]);
		
		$field	= $signdata["c_type"] == "points" ? "points" : "paidsignups";
		
		$db->Query("UPDATE users SET $field=$field+'" . $signdata["credits"] . "' WHERE id='" . $confdata["uid"] . "'");
		$db->Query("UPDATE received_signups SET credited='yes', checked='1' WHERE id='" . $_GET["cid"] . "'");
		
		$db->Query("SELECT id FROM received_signups WHERE sid='" . $confdata["sid"] . "' AND checked='1' AND credited='yes'");
		
		if($db->NumRows() == $signdata["max"])
		{
			$db->Query("UPDATE paid_signups SET active='no' WHERE id='" . $confdata["sid"] . "'");
			
			if($signdata["mail"] == 1)
			{
				$userdata	= $db->Fetch("SELECT fname, sname, email FROM users WHERE id='" . $signdata["aid"] . "'");
				
				$tml->RegisterVar("TITLE",	$signdata["title"]);
				$tml->RegisterVar("FNAME",	$userdata["fname"]);
				$tml->RegisterVar("SNAME",	$userdata["sname"]);
				$tml->RegisterVar("EMAIL",	$userdata["email"]);
				$tml->RegisterVar("DATE",	date("l F d Y"));
				$tml->RegisterVar("MAX",	$signdata["max"]);
				
				$tml->loadFromFile("emails/signup_completed");
				$tml->Parse(1);
				
				$main->sendMail($userdata["email"], _LANG_PAIDSIGNUPS_CAMPAIGNCOMPLETED, $tml->GetParsedContent(), _EMAIL_ADVERTISE);
			}
		}
		
		$main->printText("<B>Paid Sign-Ups Manager</B><BR><BR>Member credited.", 1);
	}
	elseif($_GET["action"] == "view" && $_GET["siid"] >= 1)
	{
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>E-Mail</TD><TD>Confirmation</TD><TD>Date</TD><TD>Action</TD></TR>\n";
		
		$db->Query("SELECT id, uid, sid, confirmation, credited, dateStamp FROM received_signups WHERE sid='" . $_GET["siid"] . "' AND checked='0' ORDER BY credited DESC, dateStamp DESC LIMIT $start, 5");
		
		while($row = $db->NextRow())
		{
			$email	= $db->Fetch("SELECT email FROM users WHERE id='" . $row["uid"] . "'", 2);
			
			$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
					  ."<TD VALIGN=\"top\">$email</TD><TD>" . nl2br($row["confirmation"]) . "</TD><TD VALIGN=\"top\">" . date(_SITE_DATESTAMP, $row["dateStamp"]) . "</TD>\n"
					  ."<TD VALIGN=\"top\">\n";
			
			if($row["credited"] == "no")
				$text	.= "<A HREF=\"" . _ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "&action=credit&cid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/dollar.gif\" ALT=\"Approve\" BORDER=\"0\"></A> ";
			
			$text	.= "<A HREF=\"" . _ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "&action=cdelete&cid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Disapprove\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text	.= "</TABLE><BR>\n";

		$db->Query("SELECT id FROM received_signups WHERE sid='" . $_GET["siid"] . "' AND checked='0'");
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "&action=view&siid=" . $_GET["siid"], $db->NumRows(), 5, $start) . "</TD></TR></TABLE>";
		
		$main->printText($text);
	}
	elseif($_GET["action"] == "delete")
	{
		$db->Query("SELECT id FROM paid_signups WHERE id='" . $_GET["siid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Paid Sign-Ups Manager", "An error has occured."));
		
		$db->Query("DELETE FROM paid_signups WHERE id='" . $_GET["siid"] . "'");
		$db->Query("DELETE FROM received_signups WHERE sid='" . $_GET["siid"] . "'");
		$db->Query("DELETE FROM actions WHERE type='signup' AND aid='" . $_GET["siid"] . "'");
			
		$main->printText("<B>Paid Sign-Ups Manager</B><BR><BR>Paid Sign-Up Deleted.", 1);
	}
	elseif($_GET["action"] == "add")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("SELECT id FROM users WHERE id='" . $_POST["advertiser"] . "' AND advertiser='yes'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Paid Sign-Ups Manager", "Advertiser account not found, please check account."));
			
			$db->Query("INSERT INTO paid_signups (aid, title, text, url, c_type, credits, max, mail, active) VALUES ('" . $_POST["advertiser"] . "', '" . $_POST["title"] . "', '" . $_POST["text"] . "', '" . $_POST["url"] . "', '" . $_POST["c_type"] . "', '" . $_POST["credits"] . "', '" . $_POST["max"] . "', '" . $_POST["mail"] . "', '" . $_POST["active"] . "');");
			
			$main->printText("<B>Paid Sign-Ups Manager</B><BR><BR>Paid Sign-Up Added.", 1);
		}
		else
		{
			$order_data	= $main->Trim($db->Fetch("SELECT ad_url, ad_title, ad_text FROM ad_orders WHERE id='" . $_GET["oid"] . "'"));
			
			$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "&action=add\" METHOD=\"POST\">\n";
			
			if(_MEMBER_POINTS == "NO")
				$text	.= "<INPUT TYPE=\"hidden\" NAME=\"c_type\" VALUE=\"cash\">";
			
			$text	.= "<TABLE WIDTH=\"100%\">\n"
					  ."<TR><TH COLSPAN=\"2\"><B>Add Paid Sign-Up</B></TH></TR>"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					  ."<TR><TD>Title:</TD><TD><INPUT TYPE=\"text\" NAME=\"title\" SIZE=\"30\" VALUE=\"" . $order_data["ad_title"] . "\"></TD></TR>\n"
					  ."<TR><TD>Url:</TD><TD><INPUT TYPE=\"text\" NAME=\"url\" SIZE=\"30\" VALUE=\"" . $order_data["ad_url"] . "\"></TD></TR>\n"
					  ."<TR><TD>Credit Type:</TD><TD><SELECT NAME=\"c_type\" SIZE=\"1\"";
			
			if(_MEMBER_POINTS == "NO")
				$text	.= " disabled";
			
			$text	.= "><OPTION VALUE=\"cash\" selected>Cash</OPTION><OPTION VALUE=\"points\">Points</OPTION></SELECT></TD></TR>\n"
					  ."<TR><TD>Credits:</TD><TD><INPUT TYPE=\"text\" NAME=\"credits\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Maximal subscriptions:</TD><TD><INPUT TYPE=\"text\" NAME=\"max\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Send e-mail when completed:</TD><TD><SELECT NAME=\"mail\" SIZE=\"1\"><OPTION VALUE=\"1\">Yes</OPTION><OPTION VALUE=\"0\" selected>No</OPTION></SELECT></TD></TR>\n"
					  ."<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\" selected>Yes</OPTION><OPTION VALUE=\"no\">No</OPTION></SELECT></TD></TR>\n"
					  ."<TR><TD>Text:</TD><TD><TEXTAREA NAME=\"text\" COLS=\"40\" ROWS=\"8\">" . htmlentities($order_data["ad_text"]) . "</TEXTAREA></TD></TR>\n"
					  ."<TR><TD>Advertiser:<BR><FONT SIZE=\"1\">account e-mail</FONT></TD>\n"
					  ."<TD><SELECT NAME=\"advertiser\" SIZE=\"1\">\n";
			
			$db->Query("SELECT id, email FROM users WHERE active='yes' AND advertiser='yes'");
			
			while($row = $db->NextRow())
				$text		.= "<OPTION VALUE=\"" . $row["id"] . "\" " . ($row["id"] == $_GET["aid"] ? "selected" : "") . ">" . $row["email"] . "</OPTION>\n";
		
			$text	.= "</SELECT></TD></TR>\n"
					  ."<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Paid Sign-Up\"></TD></TR>\n"
					  ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "edit")
	{
		$db->Query("SELECT id FROM paid_signups WHERE id='" . $_GET["siid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Paid Sign-Ups Manager","An error has occured."));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("SELECT id FROM users WHERE id='" . $_POST["advertiser"] . "' AND advertiser='yes'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Paid Sign-Ups Manager", "Advertiser account not found, please check account."));
			
			$db->Query("UPDATE paid_signups SET aid='" . $_POST["advertiser"] . "', title='" . $_POST["title"] . "', text='" . $_POST["text"] . "', url='" . $_POST["url"] . "', c_type='" . $_POST["c_type"] . "', credits='" . $_POST["credits"] . "', max='" . $_POST["max"] . "', mail='" . $_POST["mail"] . "', active='" . $_POST["active"] . "' WHERE id='" . $_GET["siid"] . "'");
			
			$main->printText("<B>Paid Sign-Ups Manager</B><BR><BR>Paid Sign-Up Edited.", 1);
		}
		else
		{
			$data	= $main->Trim($db->Fetch("SELECT * FROM paid_signups WHERE id='" . $_GET["siid"] . "'"));
			
			$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "&action=edit&siid=" . $_GET["siid"] . "\" METHOD=\"POST\">\n";
			
			if(_MEMBER_POINTS == "NO")
				$text	.= "<INPUT TYPE=\"hidden\" NAME=\"c_type\" VALUE=\"cash\">";
			
			$text	.= "<TABLE WIDTH=\"100%\">\n"
					  ."<TR><TH COLSPAN=\"2\"><B>Edit Paid Sign-Up \"" . $data["title"] . "\"</B></TH></TR>"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					  ."<TR><TD>Title:</TD><TD><INPUT TYPE=\"text\" NAME=\"title\" VALUE=\"" . $data["title"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Url:</TD><TD><INPUT TYPE=\"text\" NAME=\"url\" VALUE=\"" . $data["url"] . "\" SIZE=\"30\"></TD></TR>\n";
			
			if(_MEMBER_POINTS == "NO")
				$value	= " disabled";
			
			if(_MEMBER_POINTS == "NO" || $data["c_type"] == "cash")
				$text	.= "<TR><TD>Credit Type:</TD><TD><SELECT NAME=\"c_type\" SIZE=\"1\"$value><OPTION VALUE=\"cash\" selected>Cash</OPTION><OPTION VALUE=\"points\">Points</OPTION></SELECT></TD></TR>\n";
			else
				$text	.= "<TR><TD>Credit Type:</TD><TD><SELECT NAME=\"c_type\" SIZE=\"1\"><OPTION VALUE=\"cash\">Cash</OPTION><OPTION VALUE=\"points\" selected>Points</OPTION></SELECT></TD></TR>\n";
			
			$text	.= "<TR><TD>Credits:</TD><TD><INPUT TYPE=\"text\" NAME=\"credits\" VALUE=\"" . $data["credits"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Maximal subscriptions:</TD><TD><INPUT TYPE=\"text\" NAME=\"max\" VALUE=\"" . $data["max"] . "\" SIZE=\"30\"></TD></TR>\n";
			
			if($data["active"] == "yes")
				$text	.= "<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\" selected>Yes</OPTION><OPTION VALUE=\"no\">No</OPTION></SELECT></TD></TR>\n";
			else
				$text	.= "<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\">Yes</OPTION><OPTION VALUE=\"no\" selected>No</OPTION></SELECT></TD></TR>\n";
						 
			if($data["mail"] == 1)
				$text	.= "<TR><TD>Send e-mail when completed:</TD><TD><SELECT NAME=\"mail\" SIZE=\"1\"><OPTION VALUE=\"1\" selected>Yes</OPTION><OPTION VALUE=\"0\">No</OPTION></SELECT></TD></TR>\n";
			else
				$text	.= "<TR><TD>Send e-mail when completed:</TD><TD><SELECT NAME=\"mail\" SIZE=\"1\"><OPTION VALUE=\"1\">Yes</OPTION><OPTION VALUE=\"0\" selected>No</OPTION></SELECT></TD></TR>\n";
						 
			$text	.= "<TR><TD>Text:</TD><TD><TEXTAREA NAME=\"text\" COLS=\"40\" ROWS=\"8\">" . $data["text"] . "</TEXTAREA></TD></TR>\n"
					  ."<TR><TD>Advertiser:</TD><TD><SELECT NAME=\"advertiser\" SIZE=\"1\">\n";
			
			$db->Query("SELECT id, email FROM users WHERE active='yes' AND advertiser='yes'");
			
			while($row = $db->NextRow())
			{
				$var	= $row["id"] == $data["aid"] ? "selected" : "";
				
				$text	.= "<OPTION VALUE=\"" . $row["id"] . "\" ${var}>" . $row["email"] . "</OPTION>\n";
			}
			
			$text	.= "</SELECT></TD></TR>\n"
					  ."<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Paid Sign-Up\"></TD></TR>\n"
					  ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "export")
	{
		$db->Query("SELECT id FROM paid_signups WHERE id='" . $_GET["siid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Paid Sign-Ups Manager", "An error has occured."));
		
		$data	= $db->Fetch("SELECT * FROM paid_signups WHERE id='" . $_GET["siid"] . "'");
		
		$db->Query("SELECT id FROM received_signups WHERE sid='" . $_GET["siid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Paid Sign-Ups Manager", "There has to be at least one submission."));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(!isset($_POST["filetype"]))
				exit($error->Report("Paid Sign-Ups Manager", "You have to fill out the form."));
			
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=download." . $_POST["filetype"]);
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Pragma: public");
			
			if($_POST["filetype"] == "csv")
			{
				$sep1	= ",";
				$sep2	= ",";
			}
			else
			{
				$sep1	= "\t ";
				$sep2	= "\t";
			}
			
			$content	= "Member Account${sep1}Confirmation${sep1}Date${sep1}Checked${sep1}Credited";
			
			$content	.= "\r\n";
			
			$db->Query("SELECT uid, confirmation, dateStamp FROM received_signups WHERE sid='" . $_GET["siid"] . "' ORDER BY checked");
			
			while($row = $db->NextRow())
			{
				$row["checked"]			= $row["checked"] == 1 ? "yes" : "no";
				
				if($_POST["filetype"] == "csv")
				{
					$row["confirmation"]	= str_replace(",", "[comma]", $row["confirmation"]);
				}
				else
					$row["confirmation"]	= str_replace("\t", "[tab]", $row["confirmation"]);
				
				$content				.= $db->Fetch("SELECT email FROM users WHERE id='" . $row["uid"] . "'", 2) . $sep2 . str_replace(",", "", $row["confirmation"]) . $sep2 . date(_SITE_DATESTAMP, $row["dateStamp"]) . $sep2 . $row["checked"] . $sep2 . $row["credited"] . "\r\n";
			}
			
			echo $content;
		}
		else
		{
			$text		= "<FORM ACTION=\"" . _ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "&action=export&siid=" . $_GET["siid"] . "\" METHOD=\"post\">\n"
						 ."<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD ALIGN=\"center\"><B>Export Paid Sign-Up \"" . $data["title"] . "\"</B></TD></TR>"
						 ."<TR><TD>&nbsp;</TD></TR>"
						 ."<TR><TD>Check the boxes next to the fields you want to download. All checked fields will be included in your downloadable log.</TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>"
						 ."<TR><TD><B>Download File Types</B></TD></TR>"
						 ."<TR><TD><INPUT TYPE=\"radio\" NAME=\"filetype\" VALUE=\"csv\"> Comma delimited file (for use in any spreadsheet application)</TD></TR>"
						 ."<TR><TD><INPUT TYPE=\"radio\" NAME=\"filetype\" VALUE=\"txt\"> Tab delimited file</TD></TR>"
						 ."<TR><TD>&nbsp;</TD></TR>"
						 ."<TR><TD><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Export\"></TD></TR>\n"
						 ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	else
	{
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Title</TD><TD>New</TD><TD>Total</TD><TD>Status</TD><TD>Credits</TD><TD>Active</TD><TD>Action</TD></TR>\n";
		
		$db->Query("SELECT id, title, c_type, credits, max, active FROM paid_signups ORDER BY id DESC LIMIT $start, 30");
		
		while($row = $db->NextRow())
		{
			$row	= $main->Trim($row);
			
			$db->Query("SELECT id FROM received_signups WHERE sid='" . $row["id"] . "' AND checked='0'", 2);
			
			$new	= $db->NumRows(2) == 0 ? "0" : "<B>" . $db->NumRows(2) . "</B>";
			
			$db->Query("SELECT id FROM received_signups WHERE sid='" . $row["id"] . "' AND checked='1' AND credited='yes'", 2);
			
			$text		.= "<TR BGCOLOR=\"#EAEAEA\">\n"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "&action=view&siid=" . $row["id"] . "\">" . $row["title"] . "</A></TD><TD>$new</TD><TD>" . $db->NumRows(2) . "/" . $row["max"] . "</TD>"
						  ."<TD>" . @round(($db->NumRows(2) / $row["max"]) * 100, 2) . "%</TD><TD>" . $row["credits"] . " " . $row["c_type"] . "</TD><TD>" . $row["active"] . "</TD>\n"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "&action=delete&siid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A> "
						  ."<A HREF=\"" . _ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "&action=edit&siid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A> "
						  ."<A HREF=\"" . _ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "&action=export&siid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/excel.gif\" ALT=\"Export\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text	.= "</TABLE><BR>\n";
		
		$db->Query("SELECT id FROM paid_signups");
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/paidsignups.php?sid=" . $session->ID, $db->NumRows(), 30, $start) . "</TD></TR></TABLE>"
				  ."<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "&action=add\">Add Paid Sign-Up</A></TD></TR></TABLE>\n";
		
		$main->printText($text);
	}

?>