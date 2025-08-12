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
	
	$tml->RegisterVar("TITLE", "Premium Memberships");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Premium Memberships", "You can not access this page."));
	
	if($_GET["action"] == "view")
	{
		if($_GET["sub"] == "delete")
		{
			$db->Query("SELECT id FROM users WHERE id='" . $_GET["uid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Premium Members", "The member doesn't exists."));
			
			$db->Query("UPDATE users SET premium='0' WHERE id='" . $_GET["uid"] . "'");
			
			$main->printText("<B>Premium Members</B><BR><BR>Premium Member Status Removed.", 1);
		}
		elseif($_GET["sub"] == "add")
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$db->Query("UPDATE users SET premium='" . $_POST["mid"] . "' WHERE email='" . $_POST["email"] . "'");
				
				$main->printText("<B>Premium Members</B><BR><BR>Premium Member Status Added.", 1);
			}
			else
			{
				if($_GET["uid"])
					$email	= $db->Fetch("SELECT email FROM users WHERE id='" . $_GET["uid"] . "'");
				
				$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=view&mid=" . $_GET["mid"] . "&sub=add\" METHOD=\"POST\">\n"
						 ."<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD COLSPAN=\"2\"><B>Add Premium Member</B></TD></TR>"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD>Account:<BR><FONT SIZE=\"1\">e-mail address</FONT></TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"email\" SIZE=\"30\" VALUE=\"$email\"></TD></TR>\n"
						 ."<TR><TD>Membership:</TD><TD ALIGN=\"right\"><SELECT NAME=\"mid\" SIZE=\"1\">\n";
				
				$db->Query("SELECT id, title FROM memberships");
				
				while($row = $db->NextRow())
				{
					$text	.= "<OPTION VALUE=\"" . $row["id"] . "\"" . ($row["id"] == $_GET["mid"] ? " selected"  : "") . ">" . $row["title"] . "</OPTION>\n";
				}
				
				$text	.= "</TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Premium Member\"></TD></TR>\n"
						  ."</TABLE></FORM>";
				
				$main->printText($text);
			}
		}
		elseif($_GET["sub"] == "edit")
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$db->Query("UPDATE users SET premium='" . $_POST["mid"] . "' WHERE id='" . $_GET["uid"] . "'");
				
				$main->printText("<B>Premium Members</B><BR><BR>Premium Member Status Changed.", 1);
			}
			else
			{
				$userdata	= $db->Fetch("SELECT email, premium FROM users WHERE id='" . $_GET["uid"] . "'");
				
				$text		.= "<FORM ACTION=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=view&mid=" . $_GET["mid"] . "&sub=edit&uid=" . $_GET["uid"] . "\" METHOD=\"POST\">\n"
							 ."<TABLE WIDTH=\"100%\">\n"
							 ."<TR><TD COLSPAN=\"2\"><B>Edit Premium Member</B></TD></TR>"
							 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
							 ."<TR><TD>Account:<BR><FONT SIZE=\"1\">e-mail address</FONT></TD><TD ALIGN=\"right\">" . $userdata["email"] . "</TD></TR>\n"
							 ."<TR><TD>Membership:</TD><TD ALIGN=\"right\"><SELECT NAME=\"premium\" SIZE=\"1\">\n";
				
				$db->Query("SELECT id, title FROM memberships");
				
				while($row = $db->NextRow())
				{
					$text		.= "<OPTION VALUE=\"" . $row["id"] . "\"" . ($row["id"] == $userdata["premium"] ? " selected"  : "") . ">" . $row["title"] . "</OPTION>\n";
				}
				
				$text		.= "</SELECT></TD></TR>\n"
							  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
							  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Premium Member\"></TD></TR>\n"
							  ."</TABLE></FORM>";
				
				$main->printText($text);
			}
		}
		else
		{
			$start		= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
			
			$text		= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
						 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Member</TD><TD>Membership</TD><TD>Sign-Up Date</TD><TD>Action</TD></TR>\n";
			
			$membership	= $db->Fetch("SELECT title FROM memberships WHERE id='" . $_GET["mid"] . "'");
			
			$db->Query("SELECT id, email, regdate FROM users WHERE premium='" . $_GET["mid"] . "' LIMIT $start, 30");
			
			while($row = $db->NextRow())
			{
				$text		.= "<TR BGCOLOR=\"#EAEAEA\">\n"
							  ."<TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["id"] . "\">" . $row["email"] . "</A></TD>\n"
							  ."<TD>" . $membership . "</TD><TD>" . date(_SITE_DATESTAMP, $row["regdate"]) . "</TD>\n"
							  ."<TD><A HREF=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=view&mid=" . $_GET["mid"] . "&sub=delete&uid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Remove premium member Status\" BORDER=\"0\"></A> "
							  ."<A HREF=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=view&mid=" . $_GET["mid"] . "&sub=edit&uid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A></TD></TR>\n";
			}
			
			$text		.= "</TABLE><BR>\n";
			
			$db->Query("SELECT id FROM users WHERE premium='" . $_GET["mid"] . "'");
			
			$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=view&mid=" . $_GET["mid"], $db->NumRows(), 30, $start) . "</TD></TR></TABLE>"
					  ."<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=view&mid=" . $_GET["mid"] . "&sub=add\">Add Premium Member</A></TD></TR></TABLE>\n";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "delete")
	{
		$db->Query("SELECT id FROM memberships WHERE id='" . $_GET["mid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Premium Memberships", "The premium membership doesn't exists."));
		
		$db->Query("DELETE FROM memberships WHERE id='" . $_GET["mid"] . "'");
		
		$main->printText("<B>Premium Memberships</B><BR><BR>Premium Membership Deleted.", 1);
	}
	elseif($_GET["action"] == "add")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("INSERT INTO memberships (title, weight, ref_levels, advantages, price) VALUES ('" . $_POST["title"] . "', '" . $_POST["weight"] . "', '" . $_POST["ref_levels"] . "', '" . $_POST["advantages"] . "', '" . $_POST["price"] . "');");
			
			$main->printText("<B>Premium Memberships</B><BR><BR>Premium Membership Added", 1);
		}
		else
		{
			$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=add\" METHOD=\"POST\">\n"
					 ."<TABLE WIDTH=\"100%\">\n"
					 ."<TR><TD COLSPAN=\"2\"><B>Add Premium Membership</B></TD></TR>"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD>Title:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"title\" SIZE=\"30\"></TD></TR>\n"
					 ."<TR><TD>Weight:<BR><FONT SIZE=\"1\">standard is 1</FONT></TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"weight\" SIZE=\"30\" VALUE=\"1\"></TD></TR>\n"
					 ."<TR><TD>Referral Levels:<BR><FONT SIZE=1><B>- percentage</B>: \"NR OF LEVELS|LEVEL1|<BR>LEVEL2\" etc (e.g. 4|20|15|10|4)</FONT></TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"ref_levels\" SIZE=\"30\" VALUE=\"" . _REFERRAL_LEVELS . "\"></TD></TR>\n"
					 ."<TR><TD VALIGN=\"top\">Advantages:<BR><FONT SIZE=\"1\">(HTML)</FONT></TD><TD ALIGN=\"right\"><TEXTAREA NAME=\"advantages\" COLS=\"30\" ROWS=\"8\"></TEXTAREA></TD></TR>\n"
					 ."<TR><TD>Price:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"price\" SIZE=\"30\"></TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Membership\"></TD></TR>\n"
					 ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "edit")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("UPDATE memberships SET title='" . $_POST["title"] . "', weight='" . $_POST["weight"] . "', ref_levels='" . $_POST["ref_levels"] . "', advantages='" . $_POST["advantages"] . "', price='" . $_POST["price"] . "' WHERE id='" . $_GET["mid"] . "'");
			
			$main->printText("<B>Premium Memberships</B><BR><BR>Premium Membership Changed.", 1);
		}
		else
		{
			$data	= $main->Trim($db->Fetch("SELECT * FROM memberships WHERE id='" . $_GET["mid"] . "'"));
			
			$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=edit&mid=" . $_GET["mid"] . "\" METHOD=\"POST\">\n"
					 ."<TABLE WIDTH=\"100%\">\n"
					 ."<TR><TD COLSPAN=\"2\"><B>Edit Premium Membership</B></TD></TR>"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD>Title:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"title\" SIZE=\"30\" VALUE=\"" . $data["title"] . "\"></TD></TR>\n"
					 ."<TR><TD>Weight:<BR><FONT SIZE=\"1\">standard is 1</FONT></TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"weight\" SIZE=\"30\" VALUE=\"" . $data["weight"] . "\"></TD></TR>\n"
					 ."<TR><TD>Referral Levels:<BR><FONT SIZE=1><B>- percentage</B>: \"NR OF LEVELS|LEVEL1|<BR>LEVEL2\" etc (e.g. 4|20|15|10|4)</FONT></TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"ref_levels\" SIZE=\"30\" VALUE=\"" . $data["ref_levels"] . "\"></TD></TR>\n"
					 ."<TR><TD VALIGN=\"top\">Advantages:<BR><FONT SIZE=\"1\">(HTML)</FONT></TD><TD ALIGN=\"right\"><TEXTAREA NAME=\"advantages\" COLS=\"30\" ROWS=\"8\">" . htmlentities($data["advantages"]) . "</TEXTAREA></TD></TR>\n"
					 ."<TR><TD>Price:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"price\" SIZE=\"30\" VALUE=\"" . $data["price"] . "\"></TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Premium Member\"></TD></TR>\n"
					 ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	else
	{
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Title</TD><TD>Weight</TD><TD>Levels</TD><TD>Price</TD><TD>Action</TD></TR>\n";
		
		$db->Query("SELECT id, title, weight, ref_levels, price FROM memberships ORDER BY weight DESC LIMIT $start, 30");
		
		while($row = $db->NextRow())
		{
			$row		= $main->Trim($row);
			
			$levels		= explode("|", $row["ref_levels"]);
			
			$ref_levels	= "";
			
			foreach($levels AS $count => $percentage)
			{
				if($count >= 1)
				{
					if($ref_levels)
						$ref_levels	.= ", ";
					
					$ref_levels	.= $count . ": " . $percentage . "%";
				}
			}
			
			$text		.= "<TR BGCOLOR=\"#EAEAEA\">\n"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=view&mid=" . $row["id"] . "\">" . $row["title"] . "</A></TD>\n"
						  ."<TD>" . $row["weight"] . "</TD><TD>$ref_levels</TD><TD>" . _ADMIN_CURRENCY . number_format($row["price"], 2) . "</TD>\n"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=delete&mid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A> "
						  ."<A HREF=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=edit&mid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text		.= "</TABLE><BR>\n";
		
		$db->Query("SELECT id FROM memberships");
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/memberships.php?sid=" . $session->ID, $db->NumRows(), 30, $start) . "</TD></TR></TABLE>"
				  ."<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=add\">Add Premium Membership</A></TD></TR></TABLE>\n";
		
		$main->printText($text);
	}

?>