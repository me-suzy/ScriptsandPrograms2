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
	
	$tml->RegisterVar("TITLE", "Trouble Tickets");
	
	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Trouble Tickets", "You can not access this page."));
	
	if($_GET["action"] == "view")
	{
		$db->Query("SELECT id FROM s_tickets WHERE id='" . $_GET["tid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Trouble Tickets", "This ticket doesn't exists"));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if($_POST["respond"])
			{
				header("Location: " . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=respond&tid=" . $_GET["tid"]);
			}
			else
			{
				$db->Query("UPDATE s_tickets SET cid='" . $_POST["cid"] . "', subject='" . $_POST["subject"] . "', text='" . $_POST["text"] . "', resolved='" . $_POST["resolved"] . "', status='" . $_POST["status"] . "' WHERE id='" . $_GET["tid"] . "'");
				
				$ticketdata	= $db->Fetch("SELECT uid, email FROM s_tickets WHERE id='" . $_GET["tid"] . "'");
				
				if($ticketdata["email"] == "YES")
				{
					$userdata	= $db->Fetch("SELECT fname, sname, email FROM users WHERE id='" . $ticketdata["uid"] . "'");
					
					$tml->RegisterVar("FNAME",	$userdata["fname"]);
					$tml->RegisterVar("SNAME",	$userdata["sname"]);
					$tml->RegisterVar("DATE",	date("l F d Y"));
					$tml->RegisterVar("TID",	$_GET["tid"]);
					
					$tml->loadFromFile("emails/tickets_change");
					$tml->Parse(1);
					
					$main->sendMail($userdata["email"], _LANG_TICKETS_TITLE, $tml->GetParsedContent(), _SITE_EMAIL);
				}
				
				$main->printText("<B>Trouble Tickets</B><BR><BR>The ticket has been saved" . ($ticketdata["email"] == "YES" ? " and an e-mail has been sent to " . $userdata["fname"] . " " . $userdata["sname"] : "") . ".", 1);
			}
		}
		else
		{
			$ticketdata	= $main->Trim($db->Fetch("SELECT id, uid, cid, subject, text, urgency, resolved, status, dateStamp FROM s_tickets WHERE id='" . $_GET["tid"] . "'"));
			$userdata	= $main->Trim($db->Fetch("SELECT fname, sname, email, country, premium, clickthrus, ptc, paidsignups, leads_sales, games, credits, bonus, debits, referral_data, regdate FROM users WHERE id='" . $ticketdata["uid"] . "'"));
			
			$data		= unserialize($userdata["referral_data"]);
			
			for($i = 1; $i - 1 < $referrals->GetLevelData($userdata["premium"]); $i++)
			{
				$referral_earnings	+= $data["level_$i"];
			}
			
			$earnings	= $userdata["clickthrus"] + $userdata["ptc"] + $userdata["paidsignups"] + $userdata["leads_sales"] + $userdata["games"] + $userdata["credits"] + $userdata["bonus"] + $referral_earnings - $userdata["debits"];
			
			if($userdata["country"] == "")
			{
				$country	= "Undefined";
			}
			else
			{
				foreach($GLOBALS["countries"] AS $name => $value)
				{
					if($value == $userdata["country"])
					{
						$country	= $name;
						
						break;
					}
					else
						$country	= $userdata["country"];
				}
			}
			
			$text		= "<FORM ACTION=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=view&tid=" . $_GET["tid"] . "\" METHOD=\"POST\">\n"
						 ."<TABLE WIDTH=\"100%\"><TR><TD><TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD COLSPAN=\"2\"><FONT COLOR=\"red\"><B>Ticket #" . $ticketdata["id"] . " Information</B></FONT></TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						 ."<TR><TD><B>Date/Time:</B></TD><TD>" . date(_SITE_DATESTAMP . " h:i:s", $ticketdata["dateStamp"]) . "</TD></TR>\n"
						 ."<TR><TD><B>Status:</B></TD><TD><SELECT NAME=\"status\"><OPTION" . ($ticketdata["status"] == "open" ? " selected" : "") . ">open</OPTION><OPTION" . ($ticketdata["status"] == "closed" ? " selected" : "") . ">closed</OPTION><OPTION" . ($ticketdata["status"] == "pending" ? " selected" : "") . ">pending</OPTION></SELECT></TD></TR>\n"
						 ."<TR><TD><B>Resolved:</B></TD><TD><SELECT NAME=\"resolved\"><OPTION VALUE=\"1\"" . ($ticketdata["resolved"] == 1 ? " selected" : "") . ">yes</OPTION><OPTION VALUE=\"0\"" . ($ticketdata["resolved"] == 0 ? " selected" : "") . ">no</OPTION></SELECT></TD></TR>\n"
						 ."<TR><TD><B>Category:</B></TD><TD><SELECT NAME=\"cid\" SIZE=\"1\">";
			
			$db->Query("SELECT id, category FROM s_cats");
			
			while($row = $db->NextRow())
			{
				$text	.= "<OPTION VALUE=\"" . $row["id"] . "\"" . ($row["id"] == $ticketdata["cid"] ? " selected" : "") . ">" . $row["category"] . "</OPTION>\n";
			}
			
			$text		.= "</SELECT></TD></TR>\n"
						  ."<TR><TD><B>Subject:</B></TD><TD><INPUT TYPE=\"text\" NAME=\"subject\" VALUE=\"" . $ticketdata["subject"] . "\"></TD></TR></TABLE>\n"
						  ."</TD><TD VALIGN=\"top\"><TABLE WIDTH=\"100%\">\n"
						  ."<TR><TD COLSPAN=\"2\"><FONT COLOR=\"red\"><B>Client Information</B></FONT></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						  ."<TR><TD><B>Full Name:</B></TD><TD>" . $userdata["fname"] . " " . $userdata["sname"] . "</TD></TR>\n"
						  ."<TR><TD><B>E-Mail:</B></TD><TD>" . $userdata["email"] . "</TD></TR>\n"
						  ."<TR><TD><B>Country:</B></TD><TD>$country</TD></TR>\n"
						  ."<TR><TD><B>Sign-Up Date:</B></TD><TD>" . date(_SITE_DATESTAMP, $userdata["regdate"]) . "</TD></TR>\n"
						  ."<TR><TD><B>Earnings:</B></TD><TD>" . _ADMIN_CURRENCY . number_format($earnings, 2) . "</TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">Click <A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $ticketdata["uid"] . "\"><B>here</B></A> for more info</TD></TR>\n"
						  ."</TD></TR></TABLE></TD></TR>"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\"><FONT COLOR=\"red\"><B>Problem description:</B></FONT></TD>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\"><TEXTAREA NAME=\"text\" COLS=\"65\" ROWS=\"10\">" . htmlentities($ticketdata["text"]) . "</TEXTAREA></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						  ."</TABLE><TABLE WIDTH=\"100%\">\n"
						  ."<TR><TD COLSPAN=\"2\"><FONT COLOR=\"red\"><B>Message Dialog:</B></FONT></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
			
			$db->Query("SELECT message, type, dateStamp FROM s_posts WHERE tid='" . $ticketdata["id"] . "'");
			
			if($db->NumRowS() == 0)
			{
				$text	.= "<TR><TD COLSPAN=\"2\">There are no messages in this ticket yet.</TD></TR>\n";
				$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>";
			}
			else
			{
				while($row = $db->NextRow())
				{
					$text	.= "<TR><TD WIDTH=\"15%\"><B>From:</B></TD><TD>" . ($row["type"] == "from" ? $userdata["fname"] . " " . $userdata["sname"] : "Admin") . "</TD></TR>";
					$text	.= "<TR><TD WIDTH=\"15%\"><B>Date/Time:</B></TD><TD>" . date(_SITE_DATESTAMP . " h:i:s", $row["dateStamp"]) . "</TD></TR>";
					$text	.= "<TR><TD WIDTH=\"15%\" VALIGN=\"top\"><B>Message:</B></TD><TD>" . nl2br(htmlentities($row["message"])) . "</TD></TR>";
					$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>";
				}
			}
			
			$text		.= "<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"respond\" VALUE=\"Respond\"> <INPUT TYPE=\"submit\" NAME=\"save\" VALUE=\"Change Ticket\"></TD></TR>\n"
						  ."</TABLE>\n"
						  ."</FORM>\n";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "respond")
	{
		$db->Query("SELECT id FROM s_tickets WHERE id='" . $_GET["tid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Trouble Tickets", "This ticket doesn't exists"));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("INSERT INTO s_posts (tid, message, type, dateStamp) VALUES ('" . $_GET["tid"] . "', '" . $_POST["message"] . "', 'to', '" . time() . "');");
			
			$db->Query("UPDATE s_tickets SET resolved='" . $_POST["resolved"] . "', status='" . $_POST["status"] . "' WHERE id='" . $_GET["tid"] . "'");
			
			$ticketdata	= $db->Fetch("SELECT uid, email FROM s_tickets WHERE id='" . $_GET["tid"] . "'");
			
			if($ticketdata["email"] == "YES")
			{
				$userdata	= $db->Fetch("SELECT fname, sname, email FROM users WHERE id='" . $ticketdata["uid"] . "'");
				
				$tml->RegisterVar("FNAME",	$userdata["fname"]);
				$tml->RegisterVar("SNAME",	$userdata["sname"]);
				$tml->RegisterVar("DATE",	date("l F d Y"));
				$tml->RegisterVar("TID",	$_GET["tid"]);
				
				$tml->loadFromFile("emails/tickets_response");
				$tml->Parse(1);
				
				$main->sendMail($userdata["email"], _LANG_TICKETS_TITLE, $tml->GetParsedContent(), _SITE_EMAIL);
			}
			
			$main->printText("<B>Trouble Tickets</B><BR><BR>Your message has been sent" . ($ticketdata["email"] == "YES" ? " and an e-mail has been sent to " . $userdata["fname"] . " " . $userdata["sname"] : "") . ".", 1);
		}
		else
		{
			$ticketdata	= $db->Fetch("SELECT resolved, status FROM s_tickets WHERE id='" . $_GET["tid"] . "'");
			
			$text		= "<FORM ACTION=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=respond&tid=" . $_GET["tid"] . "\" METHOD=\"POST\">\n"
						 ."<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD COLSPAN=\"2\"><FONT COLOR=\"red\"><B>Respond to Ticket #" . $_GET["tid"] . "</B></FONT></TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						 ."<TR><TD><B>Status:</B></TD><TD><SELECT NAME=\"status\"><OPTION" . ($ticketdata["status"] == "open" ? " selected" : "") . ">open</OPTION><OPTION" . ($ticketdata["status"] == "closed" ? " selected" : "") . ">closed</OPTION><OPTION" . ($ticketdata["status"] == "pending" ? " selected" : "") . ">pending</OPTION></SELECT></TD></TR>\n"
						 ."<TR><TD><B>Resolved:</B></TD><TD><SELECT NAME=\"resolved\"><OPTION VALUE=\"1\"" . ($ticketdata["resolved"] == 1 ? " selected" : "") . ">yes</OPTION><OPTION VALUE=\"0\"" . ($ticketdata["resolved"] == 0 ? " selected" : "") . ">no</OPTION></SELECT></TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						 ."<TR><TD VALIGN=\"top\"><B>Message:</B></TD><TD><TEXTAREA NAME=\"message\" COLS=\"35\" ROWS=\"10\"></TEXTAREA></TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Respond\"></TD></TR>\n"
						 ."</TABLE>\n"
						 ."</FORM>\n";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "delete")
	{
		$db->Query("SELECT id FROM s_tickets WHERE id='" . $_GET["tid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Trouble Tickets", "The ticket can not be found."));
		
		$db->Query("DELETE FROM s_tickets WHERE id='" . $_GET["tid"] . "'");
		
		$main->printText("<B>Trouble Tickets</B><BR><BR>The ticket has been deleted.", 1);
	}
	elseif($_GET["action"] == "open" || $_GET["action"] == "closed" || $_GET["action"] == "pending")
	{
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$A		= "ASC";
		$D		= "DESC";
		
		$dir["id"]			= $A;
		$dir["subject"]		= $A;
		$dir["dateStamp"]	= $A;
		$dir["urgency"]		= $D;
		$dir["resolved"]	= $A;
		
		if(!$_GET["sort"])
			$_GET["sort"]	= "dateStamp";
		
		if(!$_GET["pos"])
			$_GET["pos"]	= "ASC";
		
		$dir[$_GET["sort"]]	= $_GET["pos"] == $A ? $D : $A;
		
		$sort	= _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=" . $_GET["action"] . "&sort";
		
		$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD><A HREF=\"$sort=id&pos=" . $dir["id"] . "\">Ticket</A></TD><TD><A HREF=\"$sort=subject&pos=" . $dir["subject"] . "\">Subject</A></TD>\n"
				 ."<TD><A HREF=\"$sort=dateStamp&pos=" . $dir["dateStamp"] . "\">Date</A></TD><TD><A HREF=\"$sort=dateStamp&pos=" . $dir["dateStamp"] . "\">Time</A></TD>\n"
				 ."<TD><A HREF=\"$sort=urgency&pos=" . $dir["urgency"] . "\">Urgency</A></TD><TD><A HREF=\"$sort=resolved&pos=" . $dir["resolved"] . "\">Resolved</A></TD><TD></TD></TR>\n";
		
		$db->Query("SELECT id, subject, urgency, resolved, status, dateStamp FROM s_tickets WHERE status='" . $_GET["action"] . "' ORDER BY " . $_GET["sort"] . " " . $_GET["pos"] . " LIMIT $start, 50");
		
		while($row = $db->NextRow())
		{
			$uData	= $main->Urgency($row["urgency"]);
			
			$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
					  ."<TD>#" . $row["id"] . "</TD>"
					  ."<TD><A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=view&tid=" . $row["id"] . "\">" . $row["subject"] . "</A></TD><TD>" . date(_SITE_DATESTAMP, $row["dateStamp"]) . "</TD>"
					  ."<TD>" . date("h:i:s", $row["dateStamp"]) . "</TD><TD BGCOLOR=\"" . $uData["color"] . "\">" . $uData["urgency"] . "</TD><TD>" . ($row["resolved"] == 1 ? "Yes" : "No") . "</TD>"
					  ."<TD><A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=delete&tid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete Ticket\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text	.= "</TABLE>\n";
		
		$db->Query("SELECT id FROM s_tickets WHERE status='" . $_GET["action"] . "'");
		
		$text	.= "<BR><TABLE WIDTH=\"100%\"><TR><TD ALIGN=\"center\">" . $main->GeneratePages(_ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=" . $_GET["action"], $db->NumRows(), 50, $start) . "</TD></TR></TABLE>\n";
		
		$main->printText($text);
	}
	elseif($_GET["action"] == "search")
	{
		if($_GET["keyword"] != "" || $_GET["column"] != "")
		{
			$db->Query("SELECT id FROM s_tickets WHERE " . $_GET["column"] . " LIKE '" . $_GET["keyword"] . "'");
			
			$count	= $db->NumRows();
			
			$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
			
			$text	= "There are <B>" . $db->NumRows() . "</B> tickets that match your query!<BR><BR>\n"
					 ."<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
					 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Ticket</TD><TD>Subject</TD><TD>Date</TD><TD>Time</TD><TD>Urgency</TD><TD>Status</TD><TD></TD></TR>\n";
			
			$db->Query("SELECT id, subject, urgency, status, dateStamp FROM s_tickets WHERE " . $_GET["column"] . " LIKE '" . $_GET["keyword"] . "' LIMIT $start, 50");
			
			while($row = $db->NextRow())
			{
				$uData	= $main->Urgency($row["urgency"]);
				
				$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n<TD>#" . $row["id"] . "</TD>\n"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=view&tid=" . $row["id"] . "\">" . $row["subject"] . "</A></TD><TD>" . date(_SITE_DATESTAMP, $row["dateStamp"]) . "</TD>"
						  ."<TD>" . date("h:i:s", $row["dateStamp"]) . "</TD><TD BGCOLOR=\"" . $uData["color"] . "\">" . $uData["urgency"] . "</TD><TD>" . $row["status"] . "</TD>"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=delete&tid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete Ticket\" BORDER=\"0\"></A></TD></TR>\n";
			}
			
			$text	.= "</TABLE>\n";
			
			$text	.= "<BR><TABLE WIDTH=\"100%\"><TR><TD ALIGN=\"center\">" . $main->GeneratePages(_ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=search&column=" . $_GET["column"] . "&keyword=" . $_GET["keyword"], $count, 50, $start) . "</TD></TR></TABLE>\n";
			
			$main->printText($text);
		}
		else
		{
			$text	= "<FORM ACTION=\"" . _ADMIN_URL . "/tickets.php\" METHOD=\"get\">\n"
					 ."<INPUT TYPE=\"hidden\" NAME=\"sid\" VALUE=\"" . $_GET["sid"] . "\">\n"
					 ."<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"search\">\n"
					 ."<TABLE WIDTH=\"70%\"><TR><TD COLSPAN=\"2\">Search a specific ticket</TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD>Search by:</TD><TD ALIGN=\"right\"><SELECT NAME=\"column\">\n"
					 ."<OPTION VALUE=\"text\">Problem</OPTION>\n"
					 ."<OPTION VALUE=\"subject\" selected>Subject</OPTION><OPTION VALUE=\"id\">Ticket ID</OPTION><OPTION VALUE=\"uid\">Member ID</OPTION></SELECT></TD></TR>\n"
					 ."<TR><TD>Search for:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"keyword\"></TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD COLSPAN=\"2\">Use % as a wild card. Example: if you are looking for all hotmail addresses, enter this searchstring: \"%@hotmail.com\".</TD></TR>"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Search!\"></TD></TR>\n"
					 ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "cats")
	{
		if($_GET["op"] == "delete")
		{
			$db->Query("SELECT id FROM s_cats WHERE id='" . $_GET["cid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Trouble Tickets", "The category does not exists."));
			
			$db->Query("DELETE FROM s_cats WHERE id='" . $_GET["cid"] . "'");
			
			$main->printText("<B>Trouble Tickets</B><BR><BR>Category is deleted.", 1);
		}
		elseif($_GET["op"] == "edit")
		{
			$db->Query("SELECT id FROM s_cats WHERE id='" . $_GET["cid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Trouble Tickets", "The category doesn't exists."));
			
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$db->Query("UPDATE s_cats SET category='" . $_POST["category"] . "' WHERE id='" . $_GET["cid"] . "'");
				
				$main->printText("<B>Trouble Tickets</B><BR><BR>Category is edited.", 1);
			}
			else
			{
				$category	= $db->Fetch("SELECT category FROM s_cats WHERE id='" . $_GET["cid"] . "'");
				
				$text		.= "<FORM ACTION=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=cats&op=edit&cid=" . $_GET["cid"] . "\" METHOD=\"POST\">\n"
							  ."<TABLE WIDTH=\"100%\">\n"
							  ."<TR><TD COLSPAN=\"2\"><B>Edit Category \"$category\"</B></TD></TR>"
							  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
							  ."<TR><TD>Category:</TD><TD><INPUT TYPE=\"text\" NAME=\"category\" SIZE=\"30\" VALUE=\"$category\"></TD></TR>\n"
							  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
							  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Category\"></TD></TR>\n"
							  ."</TABLE></FORM>";
				
				$main->printText($text);
			}
		}
		elseif($_GET["op"] == "add")
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$db->Query("INSERT INTO s_cats (category) VALUES ('" . $_POST["category"] . "');");
				
				$main->printText("<B>Trouble Tickets</B><BR><BR>Category is added.", 1);
			}
			else
			{
				$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=cats&op=add\" METHOD=\"POST\">\n"
						  ."<TABLE WIDTH=\"100%\">\n"
						  ."<TR><TD COLSPAN=\"2\"><B>Add Category</B></TD></TR>"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD>Category:</TD><TD><INPUT TYPE=\"text\" NAME=\"category\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Category\"></TD></TR>\n"
						  ."</TABLE></FORM>";
				
				$main->printText($text);
			}
		}
		else
		{
			$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
					 ."<TR BGCOLOR=\"#D3D3D3\">\n"
					 ."<TD>Category</TD><TD>Action</TD></TR>\n";
			
			$db->Query("SELECT id, category FROM s_cats ORDER BY category ASC");
			
			while($row = $db->NextRow())
			{
				$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n<TD>" . $row["category"] . "</TD>\n"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=cats&op=delete&cid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A>\n"
						  ."<A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=cats&op=edit&cid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A></TD></TR>\n";
			}
			
			$text	.= "</TABLE><BR><TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=cats&op=add\">Add Category</A></TD></TR></TABLE>\n";
			
			$main->printText($text);
		}
	}
	else
	{
		header("Location: " . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=open");
	}

?>