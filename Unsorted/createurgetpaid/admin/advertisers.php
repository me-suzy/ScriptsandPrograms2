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
	
	$tml->RegisterVar("TITLE", "Advertisers");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Advertisers", "You can not access this page."));
	
	if($_GET["action"] == "stats")
	{
		$db->Query("SELECT id FROM users WHERE id='" . $_GET["aid"] . "' AND advertiser='yes'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Advertisers", "An error has occured."));
		
		$email	= $db->Fetch("SELECT email FROM users WHERE id='" . $_GET["aid"] . "' AND advertiser='yes'");
		
		$db->Query("SELECT id FROM ads WHERE aid='" . $_GET["aid"] . "'");
		
		if($db->NumRows() == 0)
			$stat1	= "<TR><TD COLSPAN=\"2\">No Campaigns</TD></TR>\n<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
		else
		{
			$db->Query("SELECT * FROM ads WHERE aid='" . $_GET["aid"] . "'");
			
			while($row = $db->NextRow())
			{
				$stat1	.= "<TR><TD><B>URL</B></TD><TD ALIGN=\"right\">" . substr($row["url"], 0, 45) . "</TD></TR>\n";
				$stat1	.= "<TR><TD><B>Views</B></TD><TD ALIGN=\"right\">" . $row["views"] . "</TD></TR>\n";
				$stat1	.= "<TR><TD><B>Clicks</B></TD><TD ALIGN=\"right\">" . $row["clicks"] . "</TD></TR>\n";
				$stat1	.= "<TR><TD><B>Clickratio</B></TD><TD ALIGN=\"right\">" . round(($row["clicks"] / $row["views"] * 100), 3) . "%</TD></TR>\n";
				$stat1	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
			}
		}
		
		$db->Query("SELECT id FROM leads WHERE aid='" . $_GET["aid"] . "'");
		
		if($db->NumRows() == 0)
			$stat2	= "<TR><TD COLSPAN=\"2\">No Campaigns</TD></TR>\n<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
		else
		{
			$db->Query("SELECT * FROM leads WHERE aid='" . $_GET["aid"] . "'");
			
			while($row = $db->NextRow())
			{
				$stat2	.= "<TR><TD><B>Lead</B></TD><TD ALIGN=\"right\">" . $row["name"] . "</TD></TR>\n";
				$stat2	.= "<TR><TD><B>Type</B></TD><TD ALIGN=\"right\">" . $row["type"] . "</TD></TR>\n";
				
				if($row["type"] == "form")
				{
					$db->Query("SELECT id FROM lead_data WHERE lid='" . $row["id"] . "' AND status='checked'");
					
					$checked	= $db->NumRows();
					
					$db->Query("SELECT id FROM lead_data WHERE lid='" . $row["id"] . "' AND status='unchecked'");
					
					$stat2	.= "<TR><TD><B>Maximum submissions</B></TD><TD ALIGN=\"right\">" . $row["max"] . "</TD></TR>\n";
					$stat2	.= "<TR><TD><B>Unchecked submissions</B></TD><TD ALIGN=\"right\">" . $db->NumRows() . "</TD></TR>\n";
					$stat2	.= "<TR><TD><B>Checked submissions</B></TD><TD ALIGN=\"right\">" . $checked . "</TD></TR>\n";
					$stat2	.= "<TR><TD><B>Costs</B></TD><TD ALIGN=\"right\">" . $checked * $row["credits"] . " " . $row["c_type"] . " of " . $row["max"] * $row["credits"] . " " . $row["c_type"] . "</TD></TR>\n";
					$stat2	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
				}
				else
				{
					$stat2	.= "<TR><TD COLSPAN=\"2\">No data, type is URL.</TD></TR>\n";
					$stat2	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
				}
			}
		}
		
		$db->Query("SELECT id FROM paid_clicks WHERE aid='" . $_GET["aid"] . "'");
		
		if($db->NumRows() == 0)
			$stat3	= "<TR><TD COLSPAN=\"2\">No Campaigns</TD></TR>\n<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
		else
		{
			$db->Query("SELECT * FROM paid_clicks WHERE aid='" . $_GET["aid"] . "'");
			
			while($row = $db->NextRow())
			{
				$stat3	.= "<TR><TD><B>Title</B></TD><TD ALIGN=\"right\">" . $row["title"] . "</TD></TR>\n";
				$stat3	.= "<TR><TD><B>Clicks</B></TD><TD ALIGN=\"right\">" . $row["clicks"] . "</TD></TR>\n";
				$stat3	.= "<TR><TD><B>Sent</B></TD><TD ALIGN=\"right\">" . $row["sent"] . "</TD></TR>\n";
				$stat3	.= "<TR><TD><B>Clickthru</B></TD><TD ALIGN=\"right\">" . @round(($row["clicks"] / $row["sent"] * 100)) . "%</TD></TR>\n";
				$stat3	.= "<TR><TD><B>Costs</B></TD><TD ALIGN=\"right\">" . $row["clicks"] * $row["credits"] . " " . $row["c_type"] . " of " . $row["sent"] * $row["credits"] . " " . $row["c_type"] . "</TD></TR>\n";
				$stat3	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
			}
		}
		
		$db->Query("SELECT id FROM paid_emails WHERE aid='" . $_GET["aid"] . "'");
		
		if($db->NumRows() == 0)
			$stat4	= "<TR><TD COLSPAN=\"2\">No Campaigns</TD></TR>\n<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
		else
		{
			$db->Query("SELECT * FROM paid_emails WHERE aid='" . $_GET["aid"] . "'");
			
			while($row = $db->NextRow())
			{
				$stat4	.= "<TR><TD><B>Subject</B></TD><TD ALIGN=\"right\">" . $row["subject"] . "</TD></TR>\n";
				$stat4	.= "<TR><TD><B>Sent</B></TD><TD ALIGN=\"right\">" . $row["sent"] . "</TD></TR>\n";
				
				if($row["type"] == "paid")
				{
					$stat4	.= "<TR><TD><B>Clicks</B></TD><TD ALIGN=\"right\">" . $row["clicks"] . "</TD></TR>\n";
					$stat4	.= "<TR><TD><B>Clickthru</B></TD><TD ALIGN=\"right\">" . @round(($row["clicks"] / $row["sent"] * 100)) . "%</TD></TR>\n";
					$stat4	.= "<TR><TD><B>Costs</B></TD><TD ALIGN=\"right\">" . $row["clicks"] * $row["credits"] . " " . $row["c_type"] . " of " . $row["sent"] * $row["credits"] . " " . $row["c_type"] . "</TD></TR>\n";
				}
				
				$stat4	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
			}
		}
		
		$db->Query("SELECT id FROM paid_signups WHERE aid='" . $_GET["aid"] . "'");
		
		if($db->NumRows() == 0)
			$stat5	= "<TR><TD COLSPAN=\"2\">No Campaigns</TD></TR>\n<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
		else
		{
			$db->Query("SELECT * FROM paid_signups WHERE aid='" . $_GET["aid"] . "'");
			
			while($row = $db->NextRow())
			{
				$db->Query("SELECT id FROM received_signups WHERE sid='" . $row["id"] . "'", 2);
				
				$stat5	.= "<TR><TD><B>Title</B></TD><TD ALIGN=\"right\">" . $row["title"] . "</TD></TR>\n";
				$stat5	.= "<TR><TD><B>Url</B></TD><TD ALIGN=\"right\">" . $row["url"] . "</TD></TR>\n";
				$stat5	.= "<TR><TD><B>Credits</B></TD><TD ALIGN=\"right\">" . $row["credits"] . " " . $row["c_type"] . "</TD></TR>\n";
				$stat5	.= "<TR><TD><B>Maximum Sign-Ups</B></TD><TD ALIGN=\"right\">" . $row["max"] . "</TD></TR>\n";
				$stat5	.= "<TR><TD><B>Current Sign-Ups</B></TD><TD ALIGN=\"right\">" . $db->NumRows() . "</TD></TR>\n";
				$stat5	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
			}
		}
		
		$db->Query("SELECT id FROM sales WHERE aid='" . $_GET["aid"] . "'");
		
		if($db->NumRows() == 0)
			$stat6	= "<TR><TD COLSPAN=\"2\">No Campaigns</TD></TR>\n<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
		else
		{
			$db->Query("SELECT * FROM sales WHERE aid='" . $_GET["aid"] . "'");
			
			while($row = $db->NextRow())
			{
				$db->Query("SELECT id FROM sale_data WHERE sid='" . $row["id"] . "' AND status='checked'");
				
				$checked	= $db->NumRows();
				
				$db->Query("SELECT id FROM sale_data WHERE sid='" . $row["id"] . "' AND status='unchecked'");
				
				$stat6	.= "<TR><TD><B>Sale</B></TD><TD ALIGN=\"right\">" . $row["name"] . "</TD></TR>\n";
				$stat6	.= "<TR><TD><B>Type</B></TD><TD ALIGN=\"right\">" . $row["type"] . "</TD></TR>\n";
				
				if($row["type"] == "form")
				{
					$stat6	.= "<TR><TD><B>Maximum orders</B></TD><TD ALIGN=\"right\">" . $row["max"] . "</TD></TR>\n";
					$stat6	.= "<TR><TD><B>Unchecked orders</B></TD><TD ALIGN=\"right\">" . $db->NumRows() . "</TD></TR>\n";
					$stat6	.= "<TR><TD><B>Checked orders</B></TD><TD ALIGN=\"right\">" . $checked . "</TD></TR>\n";
					$stat6	.= "<TR><TD><B>Costs</B></TD><TD ALIGN=\"right\">" . $checked * $row["credits"] . " " . $row["c_type"] . " of " . $row["max"] * $row["credits"] . " " . $row["c_type"] . "</TD></TR>\n";
					$stat6	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
				}
				else
				{
					$stat6	.= "<TR><TD COLSPAN=\"2\">No data, type is URL.</TD></TR>\n";
					$stat6	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
				}
			}
		}
		
		$text	= "<BR><DIV ALIGN=\"center\"><CENTER>\n"
				 ."<TABLE WIDTH=\"90%\"><TR><TD COLSPAN=\"2\" ALIGN=\"center\"><B>Campaign stats for advertiser \"$email\"</B></TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\"><U>Ad-campaigns</U></TD></TR>\n$stat1"
				 ."<TR><TD COLSPAN=\"2\"><U>Lead-campaigns</U></TD></TR>\n$stat2"
				 ."<TR><TD COLSPAN=\"2\"><U>Paidclick-campaigns</U></TD></TR>\n$stat3"
				 ."<TR><TD COLSPAN=\"2\"><U>Paidemail-campaigns</U></TD></TR>\n$stat4"
				 ."<TR><TD COLSPAN=\"2\"><U>Paidsignup-campaigns</U></TD></TR>\n$stat5"
				 ."<TR><TD COLSPAN=\"2\"><U>Sale-campaigns</U></TD></TR>\n$stat6"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\"><A HREF=\"" . _ADMIN_URL . "/advertisers.php?sid=" . $session->ID . "\">Click here to go back</A></TD></TR>\n"
				 ."</TABLE>\n"
				 ."</CENTER></DIV>\n";
		
		$main->printText($text);
	}
	elseif($_GET["action"] == "add")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if($_POST["type"] == "new")
			{
				$db->Query("SELECT id FROM users WHERE email='" . $_POST["email"] . "'");
				
				if($db->NumRows() >= 1)
					exit($error->Report("Advertisers", "There already is an account with this e-mail address."));
				
				$db->Query("INSERT INTO users (email, password, fname, sname, address, city, state, zipcode, country, vacation, advertiser, active, regdate) VALUES ('" . $_POST["email"] . "', '" . $_POST["password"] . "', '" . $_POST["fname"] . "', '" . $_POST["sname"] . "', '" . $_POST["address"] . "', '" . $_POST["city"] . "', '" . $_POST["state"] . "', '" . $_POST["zipcode"] . "', '" . $_POST["country"] . "', '" . (time() + 31536000) . "', 'yes', 'yes', '" . time() . "');");
				
				$main->printText("<B>Advertisers</B><BR><BR>Advertiser Added.", 1);
			}
			elseif($_POST["type"] == "update")
			{
				$db->Query("SELECT id FROM users WHERE email='" . $_POST["email2"] . "' AND advertiser='no'");
				
				if($db->NumRows() == 0)
					exit($error->Report("Advertisers", "User doesn't exists or already is an advertiser."));
				
				$db->Query("UPDATE users SET advertiser='yes' WHERE email='" . $_POST["email2"] . "'");
				
				$main->printText("<B>Advertisers</B><BR><BR>User updated to advertiser.", 1);
			}
			else
				$error->Report("Advertisers", "An error has occured.");
		}
		else
		{
			$text		.= "<FORM ACTION=\"" . _ADMIN_URL . "/advertisers.php?sid=" . $session->ID . "&action=add\" METHOD=\"POST\">\n"
						 ."<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD COLSPAN=\"2\"><B>Add Advertiser</B></TD></TR>"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD VALIGN=\"top\"><INPUT TYPE=\"radio\" NAME=\"type\" VALUE=\"new\" CHECKED> Add as new member</TD>\n"
						 ."<TD><TABLE WIDTH=\"100%\"><TR><TD>E-Mail Address:</TD><TD><INPUT TYPE=\"text\" NAME=\"email\" SIZE=\"30\"></TD></TR>\n"
						 ."<TR><TD>Password:</TD><TD><INPUT TYPE=\"text\" NAME=\"password\" SIZE=\"30\"></TD></TR>\n"
						 ."<TR><TD>First Name:</TD><TD><INPUT TYPE=\"text\" NAME=\"fname\" SIZE=\"30\"></TD></TR>\n"
						 ."<TR><TD>Last Name:</TD><TD><INPUT TYPE=\"text\" NAME=\"sname\" SIZE=\"30\"></TD></TR>\n"
						 ."<TR><TD>Address:</TD><TD><INPUT TYPE=\"text\" NAME=\"address\" SIZE=\"30\"></TD></TR>\n"
						 ."<TR><TD>City:</TD><TD><INPUT TYPE=\"text\" NAME=\"city\" SIZE=\"30\"></TD></TR>\n"
						 ."<TR><TD>State:</TD><TD><INPUT TYPE=\"text\" NAME=\"state\" SIZE=\"30\"></TD></TR>\n"
						 ."<TR><TD>Zipcode:</TD><TD><INPUT TYPE=\"text\" NAME=\"zipcode\" SIZE=\"30\"></TD></TR>\n"
						 ."<TR><TD>Country:</TD><TD><INPUT TYPE=\"text\" NAME=\"country\" SIZE=\"30\"></TD></TR></TABLE></TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						 ."<TR><TD VALIGN=\"top\"><INPUT TYPE=\"radio\" NAME=\"type\" VALUE=\"update\"> Update from member</TD>\n"
						 ."<TD><TABLE WIDTH=\"100%\"><TR><TD>E-Mail Address:</TD><TD><INPUT TYPE=\"text\" NAME=\"email2\" SIZE=\"30\"></TD></TR></TABLE></TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Advertiser\"></TD></TR>\n"
						 ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "delete")
	{
		$db->Query("SELECT id FROM users WHERE id='" . $_GET["aid"] . "' AND advertiser='yes'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Advertisers", "An error has occured."));
		
		$db->Query("UPDATE users SET advertiser='no' WHERE id='" . $_GET["aid"] . "'");
			
		$main->printText("<B>Advertisers</B><BR><BR>Advertiser Status Removed.", 1);
	}
	elseif($_GET["action"] == "edit")
	{
		$db->Query("SELECT id FROM users WHERE id='" . $_GET["aid"] . "' AND advertiser='yes'");
		
		if($db->NumRows() == 1)
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$db->Query("UPDATE users SET email='" . $_POST["email"] . "', fname='" . $_POST["fname"] . "', sname='" . $_POST["sname"] . "', address='" . $_POST["address"] . "', city='" . $_POST["city"] . "', state='" . $_POST["state"] . "', zipcode='" . $_POST["zipcode"] . "', country='" . $_POST["country"] . "' WHERE id='" . $_GET["aid"] . "' AND advertiser='yes'");
				
				$main->printText("<B>Advertisers</B><BR><BR>Advertiser Edited.", 1);
			}
			else
			{
				$data	= $main->Trim($db->Fetch("SELECT id, fname, sname, email, address, city, state, zipcode, country FROM users WHERE id='" . $_GET["aid"] . "' AND advertiser='yes'"));
				
				$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/advertisers.php?sid=" . $session->ID . "&action=edit&aid=" . $_GET["aid"] . "\" METHOD=\"POST\">\n"
						  ."<TABLE WIDTH=\"100%\">\n"
						  ."<TR><TD COLSPAN=\"2\"><B>Edit Advertiser \"" . $data["email"] . "\"</B></TD></TR>"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD>Account ID:</TD><TD>" . $data["id"] . "</TD></TR>\n"
						  ."<TR><TD>E-Mail Address:</TD><TD><INPUT TYPE=\"text\" NAME=\"email\" VALUE=\"" . $data["email"] . "\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD>First Name:</TD><TD><INPUT TYPE=\"text\" NAME=\"fname\" VALUE=\"" . $data["fname"] . "\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD>Last Name:</TD><TD><INPUT TYPE=\"text\" NAME=\"sname\" VALUE=\"" . $data["sname"] . "\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD>Address:</TD><TD><INPUT TYPE=\"text\" NAME=\"address\" VALUE=\"" . $data["address"] . "\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD>City:</TD><TD><INPUT TYPE=\"text\" NAME=\"city\" VALUE=\"" . $data["city"] . "\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD>State:</TD><TD><INPUT TYPE=\"text\" NAME=\"state\" VALUE=\"" . $data["state"] . "\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD>Zipcode:</TD><TD><INPUT TYPE=\"text\" NAME=\"zipcode\" VALUE=\"" . $data["zipcode"] . "\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD>Country:</TD><TD><INPUT TYPE=\"text\" NAME=\"country\" VALUE=\"" . $data["country"] . "\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						  ."<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Advertiser\"></TD></TR>\n"
						  ."</TABLE></FORM>";
				
				$main->printText($text);
			}
		}
		else
			$error->Report("Advertisers", "An error has occured.");
	}
	else
	{
		if(!$_GET["sort"])
			$_GET["sort"]	= "email";
		
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n<TR BGCOLOR=\"#D3D3D3\">\n"
				 ."<TD>E-Mail</TD><TD>Country</TD><TD>Sign-Up Date</TD><TD>Action</TD></TR>\n";

		$db->Query("SELECT id, email, country, regdate FROM users WHERE advertiser='yes' ORDER BY email LIMIT $start, 30");
		
		while($row = $db->NextRow())
		{
			$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
					  ."<TD>".$row["email"]."</TD><TD>" . $row["country"] . "</TD><TD>" . date(_SITE_DATESTAMP, $row["regdate"]) . "</TD>\n"
					  ."<TD><A HREF=\"" . _ADMIN_URL . "/advertisers.php?sid=" . $session->ID . "&action=delete&aid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A>\n"
					  ."<A HREF=\"" . _ADMIN_URL . "/advertisers.php?sid=" . $session->ID . "&action=edit&aid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A>\n"
					  ."<A HREF=\"" . _ADMIN_URL . "/advertisers.php?sid=" . $session->ID . "&action=stats&aid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/dollar.gif\" ALT=\"View Campaign Stats\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text		.= "</TABLE><BR>\n";
		
		$db->Query("SELECT id FROM users WHERE advertiser='yes'");
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/advertisers.php?sid=" . $session->ID, $db->NumRows(), 30, $start) . "</TD></TR></TABLE>";
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/advertisers.php?sid=" . $session->ID . "&action=add\">Add an advertiser</A></TD></TR></TABLE>";
		
		$main->printText($text);
	}

?>