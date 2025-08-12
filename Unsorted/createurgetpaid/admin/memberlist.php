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
	
	$tml->RegisterVar("TITLE", "Memberlist");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Memberlist", "You can not access this page."));
	
	if($_GET["action"] == "referers")
	{
		if(!$_GET["limit"])
			$_GET["limit"]	= 25;
		
		if(!$_GET["ct"])
			$_GET["ct"]	= 3;
		
		if(!$_GET["ac"])
			$_GET["ac"]	= 3;
		
		if($_GET["ac"] == 1)
			$where	.= " AND refs.status='1'";
		elseif($_GET["ac"] == 3)
			$where	.= " AND refs.status='0'";
		
		if($_GET["ct"] == 1)
			$where	.= " AND refs.ct='1'";
		elseif($_GET["ct"] == 3)
			$where	.= " AND refs.ct='0'";
		
		$text	= "<B>Top " . $_GET["limit"] . " Referers</B><BR><BR>\n"
				 ."<TABLE WIDTH=\"100%\" WIDTH=\"100%\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\"><TD><B>Position</B></TD><TD><B>Name</B></TD><TD><B>E-Mail Address</B></TD><TD><B>Direct Referrals</TD></TD></TR>";
		
		$db->Query("SELECT COUNT(refs.uid) AS total, users.id, users.fname FROM refs LEFT JOIN users ON (refs.uid=users.id) WHERE refs.uid>='1' AND refs.rid>='1' ${where} GROUP BY refs.uid ORDER BY total DESC LIMIT " . $_GET["limit"]);
		
		$i	= 1;
		
		while($row = $db->NextRow())
		{
			if($row["id"] <= 0)
			{
				$db->Query("DELETE FROM refs WHERE uid='" . $row["id"] . "'", 2);
				$db->Query("DELETE FROM refs WHERE rid='" . $row["id"] . "'", 2);
			}
			else
			{
				$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD>$i</TD><TD>" . $row["fname"] . "</TD>\n";
				$text	.= "<TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["id"] . "\">" . $db->Fetch("SELECT email FROM users WHERE id='" . $row["id"] . "'", 2) . "</A></TD>\n";
				$text	.= "<TD>" . $row["total"] . "</TD></TR>\n";
				
				$i++;
			}
		}
		
		if($i == 1)
			$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD COLSPAN=\"4\">There are no referers yet.</TD></TR>\n";
		
		$text	.= "</TABLE><TABLE WIDTH=\"100%\">\n";
		$text	.= "<TR><TD COLSPAN=\"4\">&nbsp;</TD></TR>\n";
		$text	.= "<TR><TD COLSPAN=\"4\"><B>Extra Options</B></TD></TR>\n";
		$text	.= "<TR><TD COLSPAN=\"4\">\n";
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>Display the best..\n";
		$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/memberlist.php\" METHOD=\"get\">\n";
		$text	.= "<INPUT TYPE=\"hidden\" NAME=\"sid\" VALUE=\"" . $_GET["sid"] . "\">\n";
		$text	.= "<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"referers\">\n";
		$text	.= "<INPUT TYPE=\"text\" NAME=\"limit\" VALUE=\"" . $_GET["limit"] . "\" SIZE=\"6\"> referers</TD>\n";
		$text	.= "<TD><TABLE WIDTH=\"100%\"><TR><TD>Contest referrals:</TD><TD><SELECT NAME=\"ct\" SIZE=\"1\"><OPTION VALUE=\"1\">Only</OPTION><OPTION VALUE=\"2\" SELECTED>Show</OPTION><OPTION VALUE=\"3\">Hide</OPTION></SELECT></TD></TR>\n";
		$text	.= "<TR><TD>Active referrals:</TD><TD><SELECT NAME=\"ac\" SIZE=\"1\"><OPTION VALUE=\"1\">Only</OPTION><OPTION VALUE=\"2\" SELECTED>Show</OPTION><OPTION VALUE=\"3\">Hide</OPTION></SELECT></TD></TR></TABLE>\n";
		$text	.= "</TR><TR COLSPAN=\"2\"><TD>&nbsp;</TD></TR><TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" VALUE=\"Submit\"></TD></TR>\n";
		$text	.= "</TABLE></FORM></TD></TR>\n";
		$text	.= "</TABLE>\n";
		
		$main->printText($text);
	}
	elseif($_GET["action"] == "convert")
	{
		$db->Query("SELECT premium, points, dpoints, referral_data FROM users");
		
		$points				= 0;
		$dpoints			= 0;
		
		while($row = $db->NextRow())
		{
			$data		= unserialize($row["referral_data"]);
			
			$points		+= $row["points"];
			$dpoints	+= $row["dpoints"];
			
			for($i = 1; $i - 1 < $referrals->GetLevelData($row["premium"]); $i++)
			{
				$ref_earnings	+= $data["plevel_$i"];
			}
		}
		
		$total_points	= ($ref_earnings + $points) - $dpoints;
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$value	= ($_POST["cash"] / $total_points);
			
			$db->Query("SELECT id, premium, points, dpoints, referral_data FROM users WHERE active='yes'");
			
			while($row = $db->NextRow())
			{
				$data	= unserialize($row["referral_data"]);
				$points	= 0;
				
				for($i = 1; $i - 1 < $referrals->GetLevelData($row["premium"]); $i++)
				{
					$points	+= $data["plevel_$i"];
				}
				
				$points	+= $row["points"];
				$points	-= $row["dpoints"];
				
				$cash	= ($points * $value);
				
				$db->Query("UPDATE users SET credits=credits+'$cash', dpoints=dpoints+'$points' WHERE id='" . $row["id"] . "'", 2);
			}
			
			$main->printText("<B>Convert Point Earnings</B><BR><BR>All points have been converted. The value of one point was " . _ADMIN_CURRENCY . " $value.");
		}
		else
		{
			$text			= "<FORM ACTION=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=convert\" METHOD=\"post\">\n"
							 ."<TABLE WIDTH=\"100%\">\n"
							 ."<TR><TD COLSPAN=\"2\"><B>Convert Point Earnings</B></TD></TR>\n"
							 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
							 ."<TR><TD COLSPAN=\"2\">To calculate point value, enter the total cash amount you wish the total points to be converted to:</TD></TR>\n"
							 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
							 ."<TR><TD>Total Points:</TD><TD ALIGN=\"right\">" . number_format($total_points, 2) . " Pts</TD></TR>\n"
							 ."<TR><TD>Convert to:</TD><TD ALIGN=\"right\">" . _ADMIN_CURRENCY . " <INPUT TYPE=\"text\" NAME=\"cash\" SIZE=\"6\"" . ($total_points >= 0 ? "" : " disabled") . "></TD></TR>\n"
							 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
							 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Convert\"" . ($total_points >= 0 ? "" : " disabled") . "></TD></TR>\n"
							 ."</TABLE></FORM>\n";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "credit/debit")
	{
		if(!$_GET["uid"])
			exit($error->Report("Memberlist", "User ID is undefined"));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if($_POST["type"] == "credit")
			{
				if($_POST["ref_earnings"] == "on")
					$referrals->AddCreditsToUplines($_GET["uid"], $_POST["credits"], $_POST["c_type"]);
				
				$field	= $_POST["c_type"] == "points" ? "points" : "credits";
				
				$user->Add2Actions($_GET["uid"], 0, $field, $_POST["credits"]);
				
				$db->Query("UPDATE users SET $field=$field+'" . $_POST["credits"] . "' WHERE id='" . $_GET["uid"] . "'");
				
				$main->printText("<B>Memberlist</B><BR><BR>User succesfully credited!", 1);
			}
			elseif($_POST["type"] == "debit")
			{
				$field	= $_POST["c_type"] == "points" ? "dpoints" : "debits";
				
				$user->Add2Actions($_GET["uid"], 0, $field, $_POST["credits"]);
				
				$db->Query("UPDATE users SET $field=$field+'" . $_POST["credits"] . "' WHERE id='" . $_GET["uid"] . "'");
				
				$main->printText("<B>Memberlist</B><BR><BR>User succesfully debited!", 1);
			}
			else
				$error->Report("Memberlist", "Credit type is undefined.");
		}
		else
		{
			$email	= $db->Fetch("SELECT email FROM users WHERE id='" . $_GET["uid"] . "'");
			
			$text	= "<FORM ACTION=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=credit/debit&uid=" . $_GET["uid"] . "&psid=" . $_GET["psid"] . "\" METHOD=\"post\">\n"
					 ."<TABLE WIDTH=\"70%\">"
					 ."<TR><TD COLSPAN=\"2\"><B>Credit/Debit \"$email\"</B></TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					 ."<TR><TD>User</TD><TD ALIGN=\"right\">$email</TD></TR>\n"
					 ."<TR><TD>Action</TD><TD ALIGN=\"right\"><SELECT NAME=\"type\" SIZE=\"1\"><OPTION VALUE=\"credit\">Credit</OPTION><OPTION VALUE=\"debit\">Debit</OPTION></SELECT></TD></TR>\n"
					 ."<TR><TD>Credit Type</TD><TD ALIGN=\"right\"><SELECT NAME=\"c_type\" SIZE=\"1\"><OPTION VALUE=\"cash\">Cash</OPTION><OPTION VALUE=\"points\">Points</OPTION></SELECT></TD></TR>\n"
					 ."<TR><TD>Credits</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"credits\" VALUE=\"" . number_format($_GET["credits"], 4) . "\" SIZE=\"6\"></TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\"><B>Crediting Options</B></TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"checkbox\" NAME=\"ref_earnings\" CLASS=\"radio\"> Check to add referral earnings.</TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Submit\"></TD></TR>\n"
					 ."</TABLE></FORM>\n";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "stats")
	{
		$bonus			= $db->Fetch("SELECT SUM(bonus) FROM users WHERE active='yes'");
		$clickthrus		= $db->Fetch("SELECT SUM(clickthrus) FROM users WHERE active='yes'");
		$ptc			= $db->Fetch("SELECT SUM(ptc) FROM users WHERE active='yes'");
		$paidsignups	= $db->Fetch("SELECT SUM(paidsignups) FROM users WHERE active='yes'");
		$leads_sales	= $db->Fetch("SELECT SUM(leads_sales) FROM users WHERE active='yes'");
		$games			= $db->Fetch("SELECT SUM(games) FROM users WHERE active='yes'");
		$credits		= $db->Fetch("SELECT SUM(credits) FROM users WHERE active='yes'");
		$debits			= $db->Fetch("SELECT SUM(debits) FROM users WHERE active='yes'");
		$points			= $db->Fetch("SELECT SUM(points) FROM users WHERE active='yes'");
		$dpoints		= $db->Fetch("SELECT SUM(dpoints) FROM users WHERE active='yes'");
		
		$cashdata		= Array();
		$pointsdata		= Array();
		
		$db->Query("SELECT premium, referral_data FROM users WHERE active='yes'");
		
		while($row = $db->NextRow())
		{
			$data	= unserialize($row["referral_data"]);
			
			for($i = 1; $i - 1 < $referrals->GetLevelData($row["premium"]); $i++)
			{
				$cashdata["level_$i"]	+= $data["level_$i"];
				$pointsdata["level_$i"]	+= $data["plevel_$i"];
			}
			
		}
		
		$text	= "<TABLE WIDTH=\"100%\"><TR><TD COLSPAN=\"2\">Earnings list of all members</TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				 ."<TR><TD><B>Clickthrus</B></TD><TD ALIGN=\"right\">"		. _ADMIN_CURRENCY . " " . number_format($clickthrus,	4) . "</TD></TR>\n"
				 ."<TR><TD><B>Paid Clicks</B></TD><TD ALIGN=\"right\">"		. _ADMIN_CURRENCY . " " . number_format($ptc,			4) . "</TD></TR>\n"
				 ."<TR><TD><B>Paid Sign-Ups</B></TD><TD ALIGN=\"right\">"	. _ADMIN_CURRENCY . " " . number_format($paidsignups,	4) . "</TD></TR>\n"
				 ."<TR><TD><B>Leads & Sales</B></TD><TD ALIGN=\"right\">"	. _ADMIN_CURRENCY . " " . number_format($leads_sales,	4) . "</TD></TR>\n"
				 ."<TR><TD><B>Games</B></TD><TD ALIGN=\"right\">"			. _ADMIN_CURRENCY . " " . number_format($games,			4) . "</TD></TR>\n"
				 ."<TR><TD><B>Credits</B></TD><TD ALIGN=\"right\">"			. _ADMIN_CURRENCY . " " . number_format($credits,		4) . "</TD></TR>\n"
				 ."<TR><TD><B>Bonus</B></TD><TD ALIGN=\"right\">"			. _ADMIN_CURRENCY . " " . number_format($bonus,			4) . "</TD></TR>\n"
				 ."<TR><TD><B>Debits</B></TD><TD ALIGN=\"right\">"			. _ADMIN_CURRENCY . " " . number_format($debits,		4) . "</TD></TR>\n";
		
		if(_MEMBER_POINTS == "YES")
		{
			$text	.= "<TR><TD><B>Points Credits</B></TD><TD ALIGN=\"right\">"		. number_format($points,	0) . " Pts</TD></TR>\n";
			$text	.= "<TR><TD><B>Points Debits</B></TD><TD ALIGN=\"right\">"		. number_format($dpoints,	0) . " Pts</TD></TR>\n";
		}
		
		$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
		
		for($i = 1; $i - 1 < $referrals->GetLevelData($row["premium"]); $i++)
		{
			$text	.= "<TR><TD><B>Referrals level $i</B></TD><TD ALIGN=\"right\">";
			
			if(_MEMBER_POINTS == "YES")
				$text	.= number_format($pointsdata["level_$i"], 0) . " Pts / ";
			
			$text	.= _ADMIN_CURRENCY . " " . number_format($cashdata["level_$i"], 4) . "</TD></TR>\n";
		}
		
		$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
		$text	.= "<TR><TD><B>Total Cash</B></TD><TD ALIGN=\"right\"><B>" . _ADMIN_CURRENCY . " " . number_format((array_sum($cashdata) + $bonus + $clickthrus + $ptc + $paidsignups + $leads_sales + $credits + $games) - $debits, 4) . "</B></TD></TR>\n";
		
		if(_MEMBER_POINTS == "YES")
			$text	.= "<TR><TD><B>Total Points</B></TD><TD ALIGN=\"right\"><B>" . number_format((array_sum($pointsdata) + $points) - $dpoints, 2) . " Pts</B></TD></TR>\n";
		
		$text	.= "</TABLE>\n";
		
		$main->printText($text);
	}
	elseif($_GET["action"] == "search")
	{
		if($_GET["keyword"] != "" && $_GET["column"] != "")
		{
			$db->Query("SELECT id FROM users WHERE " . $_GET["column"] . " LIKE '" . $_GET["keyword"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Memberlist", "No matches were found."));
			
			$db->Query("SELECT id FROM users WHERE " . $_GET["column"] . " LIKE '" . $_GET["keyword"] . "'");
			
			$count	= $db->NumRows();
			
			$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
			
			$text	= "There are <B>$count</B> users that match your query!<BR><BR>\n"
					 ."<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
					 ."<TR BGCOLOR=\"#D3D3D3\"><TD><B>ID</B></TD><TD><B>Full Name</B></TD>\n"
					 ."<TD><B>E-Mail Address</B></TD><TD><B>Payment Method</B></TD><TD><B>Payment Account</B></TD></TR>\n";
			
			$db->Query("SELECT id, fname, sname, email, payment_method, payment_account FROM users WHERE " . $_GET["column"] . " LIKE '" . $_GET["keyword"] . "' LIMIT $start, 50");
			
			while($result = $db->NextRow())
			{
				$method	= $db->Fetch("SELECT method FROM payment_methods WHERE id='" . $result["payment_method"] . "'", 2);
				
				$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $result["id"] . "\">#" . $result["id"] . "</A></TD>\n"
						  ."<TD>" . $result["fname"] . " " . $result["sname"] . "</TD><TD>" . $result["email"] . "</TD><TD>$method</TD><TD>" . $result["payment_account"] . "</TD></TR>\n";
			}
			
			$text	.= "</TABLE>\n";
			
			$text	.= "<BR><TABLE WIDTH=\"100%\"><TR><TD ALIGN=\"center\">" . $main->GeneratePages(_ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=search&column=" . $_GET["column"] . "&keyword=" . $_GET["keyword"], $count, 50, $start) . "</TD></TR></TABLE>\n";
			
			$main->printText($text);
		}
		else
		{
			$text	= "<FORM ACTION=\"" . _ADMIN_URL . "/memberlist.php\" METHOD=\"get\">\n"
					 ."<INPUT TYPE=\"hidden\" NAME=\"sid\" VALUE=\"" . $_GET["sid"] . "\">\n"
					 ."<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"search\">\n"
					 ."<TABLE WIDTH=\"100%\"><TR><TD COLSPAN=\"2\">Search a specific user</TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD>Search by:</TD><TD><SELECT NAME=\"column\"><OPTION VALUE=\"address\">Address</OPTION>\n"
					 ."<OPTION VALUE=\"city\">City</OPTION><OPTION VALUE=\"email\" SIZE=\"1\" selected>"
					 ."E-Mail Address</OPTION><OPTION VALUE=\"remote_addr\">IP Address</OPTION><OPTION VALUE=\"fname\">First Name</OPTION>"
					 ."<OPTION VALUE=\"sname\">Last Name</OPTION><OPTION VALUE=\"password\">Password</OPTION><OPTION VALUE=\"payment_account\">Payment Account</OPTION>"
					 ."<OPTION VALUE=\"id\">User ID</OPTION><OPTION VALUE=\"zipcode\">Zipcode</OPTION></SELECT></TD></TR>\n"
					 ."<TR><TD>Search for:</TD><TD><INPUT TYPE=\"text\" NAME=\"keyword\"></TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD COLSPAN=\"2\">Use % as a wild card. Example: if you are looking for all hotmail addresses, enter this searchstring: \"%@hotmail.com\".</TD></TR>"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Search!\"></TD></TR>\n"
					 ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "delete")
	{
		$db->Query("SELECT id FROM users WHERE id='" . $_GET["uid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Memberlist", "The member does not exists."));
		
		$user->Remove($_GET["uid"]);
		
		$main->WriteToLog("members", "Member id \"" . $_GET["uid"] . "\" deleted");
		
		$main->printText("<B>Memberlist</B><BR><BR>Account Deleted.", 1);
	}
	elseif($_GET["action"] == "resend")
	{
		$db->Query("SELECT id FROM users WHERE id='" . $_GET["uid"] . "' AND active!='yes'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Memberlist", "The member does not exists or already is activated."));
		
		$data	= $db->Fetch("SELECT email, active FROM users WHERE id='" . $_GET["uid"] . "'");
		
		$user->SendActivationEmail($data["email"], $data["active"]);
		
		$main->printText("<B>Memberlist</B><BR><BR>An activation e-mail has been sent to " . $data["email"] . ".", 1);
	}
	elseif($_GET["action"] == "edit")
	{
		$db->Query("SELECT id FROM users WHERE id='" . $_GET["uid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Memberlist", "The member does not exists."));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(_MEMBER_ACTIVATION == "NO")
				$_POST["active"]	= "yes";
			
			$db->Query("UPDATE users SET email='" . $_POST["email"] . "', password='" . $_POST["password"] . "', fname='" . $_POST["fname"] . "', sname='" . $_POST["sname"] . "', address='" . $_POST["address"]
					  ."', city='" . $_POST["city"] . "', state='" . $_POST["state"] . "', zipcode='" . $_POST["zipcode"] . "', country='" . $_POST["country"] . "',birth_day='" . $_POST["birth_day"]
					  ."', birth_month='" . $_POST["birth_month"] . "', birth_year='" . $_POST["birth_year"] . "', additional='" . serialize($_POST["additional"]) . "', operator='" . $_POST["operator"]
					  ."', bonus='" . $_POST["bonus"] . "', clickthrus='" . $_POST["clickthrus"] . "', ptc='" . $_POST["ptc"] . "', paidsignups='" . $_POST["paidsignups"] . "', leads_sales='" . $_POST["leads_sales"]
					  ."', credits='" . $_POST["credits"] . "', games='" . $_POST["games"] . "', debits='" . $_POST["debits"] . "', points='" . $_POST["points"] . "', referral_data='" . serialize($_POST["referral_data"]) . "', payment_method='" . $_POST["payment_method"] . "'"
					  .", payment_account='" . $_POST["payment_account"] . "', interests='" . serialize($_POST["interests"]) . "', vacation='" . $_POST["vacation"] . "', notes='" . $_POST["notes"] . "', active='" . $_POST["active"] . "' WHERE id='" . $_GET["uid"] . "'");
			
			$main->printText("<B>Memberlist</B><BR><BR>Account Edited.", 1);
		}
		else
		{
			$data	= $main->Trim($db->Fetch("SELECT * FROM users WHERE id='" . $_GET["uid"] . "'"));
			
			$upline	= $db->Fetch("SELECT id, email FROM users WHERE id='" . $referrals->ParentID($data["id"]) . "'");
			
			$upline	= $upline["id"] >= 1 ? "<A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $upline["id"] . "\">" . $upline["email"] . "</A>" : "No Upline";
			
			$text	.= "<SCRIPT LANGUAGE=\"javascript\">\n  function confirmDelete()\n  {\n  	var answer = confirm (\"Are you sure that you want to delete " . $data["fname"] . " " . $data["sname"]
					  ."?\");\n  	\n  	if (answer)\n  		window.location = \"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=delete&uid=" . $_GET["uid"] . "\";\n  }\n</SCRIPT>\n"
					  ."<FORM ACTION=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $_GET["uid"] . "\" METHOD=\"POST\">\n"
					  ."<TABLE WIDTH=\"100%\">\n"
					  ."<TR><TD COLSPAN=\"2\" ALIGN=\"right\"><B>Shortcuts</B></TD></TR>"
					  ."<TR><TD COLSPAN=\"2\" ALIGN=\"right\"><A HREF=\"javascript:confirmDelete()\">Delete this member</A></TD></TR>\n";
			
			if($data["premium"] == 0)
				$text	.= "<TR><TD COLSPAN=\"2\" ALIGN=\"right\"><A HREF=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=view&sub=add&uid=" . $_GET["uid"] . "\"><B>Add</B> Gold Member status</A></TD></TR>\n";
			else
				$text	.= "<TR><TD COLSPAN=\"2\" ALIGN=\"right\"><A HREF=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "&action=view&sub=delete&uid=" . $_GET["uid"] . "\"><B>Remove</B> Gold Member status</A></TD></TR>\n";
			
			$text	.= "<TR><TD COLSPAN=\"2\" ALIGN=\"right\"><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=credit/debit&uid=" . $_GET["uid"] . "\">Credit/debit this member</A></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\" ALIGN=\"right\"><A HREF=\"" . _ADMIN_URL . "/blocklist.php?sid=" . $session->ID . "&action=add&uid=" . $_GET["uid"] . "\">Ban this member from website</A></TD></TR>\n";
			
			if(_MEMBER_ACTIVATION == "YES")
				$text		.= "<TR><TD COLSPAN=\"2\" ALIGN=\"right\"><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=resend&uid=" . $_GET["uid"] . "\">Resend member's activation mail</A></TD></TR>\n";
			
			$text	.= "<TR><TD COLSPAN=\"2\" ALIGN=\"right\"><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=user&uid=" . $_GET["uid"] . "\">View this member's payments</A></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\" ALIGN=\"right\"><A HREF=\"" . _ADMIN_URL . "/mailer.php?sid=" . $session->ID . "&to=" . $data["email"] . "\">Send this member an e-mail</A></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\" ALIGN=\"right\"><A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=search&column=uid&keyword=" . $_GET["uid"] . "\">View this member's tickets</A></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\" ALIGN=\"right\"><A HREF=\"" . _ADMIN_URL . "/referrals.php?sid=" . $session->ID . "&action=viewdownline&uid=" . $_GET["uid"] . "\">View this member's downline</A></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					  ."<TR><TD COLSPAN=\"2\"><B>Edit Account \"" . $data["email"] . "\"</B></TD></TR>"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					  ."<TR><TD>Account ID:</TD><TD>#" . $data["id"] . "</TD></TR>\n"
					  ."<TR><TD>IP Address:</TD><TD>" . $data["remote_addr"] . "</TD></TR>\n"
					  ."<TR><TD>Sign-Up Date/Time:</TD><TD>" . date(_SITE_DATESTAMP . " h:i", $data["regdate"]) . "</TD></TR>\n"
					  ."<TR><TD>Last Login:</TD><TD>" . date(_SITE_DATESTAMP . " h:i", $data["lastlogin"]) . "</TD></TR>\n"
					  ."<TR><TD>Last Active:</TD><TD>" . date(_SITE_DATESTAMP . " h:i", $data["lastactive"]) . "</TD></TR>\n"
					  ."<TR><TD>Logged-in:</TD><TD>" . $data["sessions"] . " times</TD></TR>\n"
					  ."<TR><TD>Upline:</TD><TD>$upline</TD></TR>\n"
					  ."<TR><TD>Referral-link:</TD><TD>" . $data["ref_hits"] . " hits</TD></TR>\n"
					  ."<TR><TD>E-Mail Address:</TD><TD><INPUT TYPE=\"text\" NAME=\"email\" VALUE=\"" . $data["email"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Password:</TD><TD><INPUT TYPE=\"text\" NAME=\"password\" VALUE=\"" . $data["password"] . "\" SIZE=\"30\"></TD></TR>\n";
			
			if(_MEMBER_ACTIVATION == "YES")
			{
				$active	= $data["active"] != "yes" ? $data["active"] : "no";
				
				$data["active"] == "yes" ? $var1 = "selected" : $var2 = "selected";
				
				$text	.= "<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\" ${var1}>Yes</OPTION><OPTION VALUE=\"$active\" ${var2}>No</OPTION></SELECT></TD></TR>\n";
			}
			
			$text	.= "<TR><TD>First Name:</TD><TD><INPUT TYPE=\"text\" NAME=\"fname\" VALUE=\"" . $data["fname"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Last Name:</TD><TD><INPUT TYPE=\"text\" NAME=\"sname\" VALUE=\"" . $data["sname"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Address:</TD><TD><INPUT TYPE=\"text\" NAME=\"address\" VALUE=\"" . $data["address"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>City:</TD><TD><INPUT TYPE=\"text\" NAME=\"city\" VALUE=\"" . $data["city"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>State:</TD><TD><INPUT TYPE=\"text\" NAME=\"state\" VALUE=\"" . $data["state"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Zipcode:</TD><TD><INPUT TYPE=\"text\" NAME=\"zipcode\" VALUE=\"" . $data["zipcode"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Country:</TD><TD><INPUT TYPE=\"text\" NAME=\"country\" VALUE=\"" . $data["country"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Birthdate:</TD><TD><INPUT TYPE=\"text\" NAME=\"birth_day\" VALUE=\"" . $data["birth_day"] . "\" SIZE=\"7\">\n"
					  ."<INPUT TYPE=\"text\" NAME=\"birth_month\" VALUE=\"" . $data["birth_month"] . "\" SIZE=\"7\">\n"
					  ."<INPUT TYPE=\"text\" NAME=\"birth_year\" VALUE=\"" . $data["birth_year"] . "\" SIZE=\"7\"></TD></TR>\n"
					  ."<TR><TD>Payment Method:</TD><TD><SELECT NAME=\"payment_method\" SIZE=\"1\">";
			
			$db->Query("SELECT id, method FROM payment_methods ORDER BY method ASC");
			
			while($row = $db->NextRow())
			{
				if($row["id"] == $data["payment_method"])
					$text	.= "<OPTION VALUE=\"" . $row["id"] . "\" selected>" . $row["method"] . "</OPTION>\n";
				else
					$text	.= "<OPTION VALUE=\"" . $row["id"] . "\">" . $row["method"] . "</OPTION>\n";
			}
			
			$text	.= "</SELECT></TD></TR>\n"
					  ."<TR><TD>Payment Account:</TD><TD><INPUT TYPE=\"text\" NAME=\"payment_account\" VALUE=\"" . $data["payment_account"] . "\" SIZE=\"30\"></TD></TR>\n";
			
			if($data["operator"] == "yes")
				$text	.= "<TR><TD>Operator:</TD><TD><SELECT NAME=\"operator\" SIZE=\"1\"><OPTION VALUE=\"yes\" selected>Yes</OPTION><OPTION VALUE=\"no\">No</OPTION></SELECT></TD></TR>\n";
			else
				$text	.= "<TR><TD>Operator:</TD><TD><SELECT NAME=\"operator\" SIZE=\"1\"><OPTION VALUE=\"yes\">Yes</OPTION><OPTION VALUE=\"no\" selected>No</OPTION></SELECT></TD></TR>\n";
			
			$vacation	= $data["vacation"] >= 1 ? $data["vacation"] : (time() + _MEMBER_VACLENGTH);
			
			$data["vacation"] >= 1 ? $var3 = " selected" : $var4 = " selected";
			
			$text	.= "<TR><TD>Status:</TD><TD><SELECT NAME=\"vacation\" SIZE=\"1\"><OPTION VALUE=\"$vacation\"${var3}>Enabled</OPTION><OPTION VALUE=\"0\"${var4}>Disabled</OPTION></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\"><B>Interests</B></TD></TR>\n";
			
			if(_MEMBER_INTERESTS == "")
				$text		.= "<TR><TD COLSPAN=\"2\">No interests defined</TD></TR>\n";
			else
			{
				$proginterests	= explode("|", _MEMBER_INTERESTS);
				$userinterests	= unserialize($data["interests"]);
				
				$j	= 1;
				
				for($i = 0; $i < count($proginterests); $i++)
				{
					$xVar	= eregi("on", $userinterests[$proginterests[$i]]) ? "checked" : "";
					
					if($j == 1)
						$text	.= "<TR><TD><INPUT TYPE=\"checkbox\" NAME=\"interests[" . $proginterests[$i] . "]\" $xVar> " .  $proginterests[$i] . "</TD>\n";
					else
					{
						$text	.= "<TD><INPUT TYPE=\"checkbox\" NAME=\"interests[" . $proginterests[$i] . "]\" $xVar> " .  $proginterests[$i] . "</TD></TR>\n";
						
						$j	= 0;
					}
					
					$j++;
				}
			}
			
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\"><B>Additional Fields</B></TD></TR>\n";
			
			if(_MEMBER_ADDITIONAL == "")
				$text	.= "<TR><TD COLSPAN=\"2\">No additional fields defined</TD></TR>\n";
			else
			{
				$progadditional	= explode("|", _MEMBER_ADDITIONAL);
				$useradditional	= unserialize($data["additional"]);
				
				for($i = 0; $i < count($progadditional); $i++)
				{
					$text	.= "<TR><TD>" . $progadditional[$i] . ":</TD><TD><INPUT TYPE=\"text\" NAME=\"additional[" . $progadditional[$i] . "]\" VALUE=\"" . $useradditional[$progadditional[$i]] . "\" SIZE=\"30\"></TD></TR>\n";
				}
			}
			
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\"><B>Account History</B></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TR>\n"
					  ."<TR><TD COLSPAN=\"2\">\n"
					  ."<TABLE WIDTH=\"100%\"><TR><TD><B>ID</B></TD><TD><B>Action</B></TD><TD><B>Credits</B></TD><TD><B>Date</B></TD></TR>\n";
			
			$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
			
			$db->Query("SELECT id, aid, uid, type, c_type, credits, dateStamp FROM actions WHERE uid='" . $_GET["uid"] . "' ORDER BY id DESC LIMIT $start, " . _MEMBER_LATESTACTPP);
			
			while($row = $db->NextRow())
			{
				$pts	= 0;
				$tp		= 0;
				
				if($row["type"] == "emails")
				{
					$type	= _LANG_STATS_STATMAILS;
					$table	= "paid_emails";
					$field	= "subject";
				}
				elseif($row["type"] == "clicks")
				{
					$type	= _LANG_STATS_STATCLICKS;
					$table	= "paid_clicks";
					$field	= "title";
				}
				elseif($row["type"] == "signup")
				{
					$type	= _LANG_STATS_STATSIGNUP;
					$table	= "paid_signups";
					$field	= "title";
				}
				elseif($row["type"] == "lead")
				{
					$type	= _LANG_STATS_STATLEAD;
					$table	= "leads";
					$field	= "name";
				}
				elseif($row["type"] == "sale")
				{
					$type	= _LANG_STATS_STATSALE;
					$table	= "sales";
					$field	= "name";
				}
				elseif($row["type"] == "credits")
					$type	= _LANG_STATS_STATCREDITS;
				elseif($row["type"] == "debits")
					$type	= _LANG_STATS_STATDEBITS;
				elseif($row["type"] == "refund")
					$type	= _LANG_STATS_STATREFUND;
				elseif($row["type"] == "points")
				{
					$type	= _LANG_STATS_STATPOINTS;
					$pts	= 1;
				}
				elseif($row["type"] == "dpoints")
				{
					$type	= _LANG_STATS_STATDPOINTS;
					$pts	= 1;
				}
				elseif($row["type"] == "transfer_to")
				{
					$type	= _LANG_STATS_STATTRANSFERTO;
					$tp		= 1;
					
					if($row["c_type"] == "points")
						$pts = 1;
				}
				elseif($row["type"] == "transfer_from")
				{
					$type	= _LANG_STATS_STATTRANSFERFROM;
					$tp		= 1;
					
					if($row["c_type"] == "points")
						$pts = 1;
				}
				elseif($row["type"] == "bubble_to")
					$type	= _LANG_STATS_STATBUBBLETO;
				elseif($row["type"] == "bubble_from")
					$type	= _LANG_STATS_STATBUBBLEFROM;
				elseif($row["type"] == "ht_won")
					$type	= _LANG_STATS_STATHTWON;
				elseif($row["type"] == "ht_lost")
					$type	= _LANG_STATS_STATHTLOST;
				elseif($row["type"] == "scratch_won")
					$type	= _LANG_STATS_STATSCRATCHWON;
				elseif($row["type"] == "scratch_paid")
					$type	= _LANG_STATS_STATSCRATCHPAID;
				elseif($row["type"] == "deposit")
					$type	= _LANG_STATS_STATDEPOSIT;
				elseif($row["type"] == "payout")
					$type	= _LANG_STATS_STATPAYOUT;
				else
					$type	= _LANG_STATS_STATUNKNOWN;
				
				if($table != "" && $row["aid"] != 0)
				{
					$db->Query("SELECT id FROM ${table} WHERE id='" . $row["aid"] . "'", 2);
					
					if($db->NumRows(2) == 1)
					{
						$pdata	= $db->Fetch("SELECT $field, c_type FROM $table WHERE id='" . $row["aid"] . "'", 2);
						
						$row["action"]	= $type . $pdata[$field];
					}
				}
				elseif($tp == 1)
				{
					$email	= $db->Fetch("SELECT email FROM users WHERE id='" . $row["aid"] . "'", 2);
					
					$row["action"]	= $type . $email;
				}
				else
					$row["action"]	= $type;
				
				$text	.= "<TR><TD>#" . $row["id"] . "</TD><TD>" . $row["action"] . "</TD>\n";
				$text	.= "<TD>" . (number_format($row["credits"], $pts == 1 || $pdata["c_type"] == "points" ? 2 : 4));
				$text	.= " " . ($pdata["c_type"] == "points" || $pts == 1 ? _LANG_STATS_POINTS : _LANG_STATS_CASH) . "</TD>";
				$text	.= "<TD>" . date(_SITE_DATESTAMP . " h:i", $row["dateStamp"]) . "</TD></TR>\n";
			}
			
			$db->Query("SELECT id FROM actions WHERE uid='" . $_GET["uid"] . "'");
			
			$text	.= "<TR><TD COLSPAN=\"4\">&nbsp;</TD></TR>\n"
					  ."<TR><TD COLSPAN=\"4\" ALIGN=\"center\">" . $main->GeneratePages(_ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $_GET["uid"], $db->NumRows(), _MEMBER_LATESTACTPP, $start) . "</TD></TR></TABLE></TD></TR>"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\"><B>Earnings</B></TD></TR>\n"
					  ."<TR><TD>Bonus:</TD><TD><INPUT TYPE=\"text\" NAME=\"bonus\" VALUE=\"" . number_format($data["bonus"], 4) . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Clickthrus:</TD><TD><INPUT TYPE=\"text\" NAME=\"clickthrus\" VALUE=\"" . number_format($data["clickthrus"], 4) . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Paid Clicks:</TD><TD><INPUT TYPE=\"text\" NAME=\"ptc\" VALUE=\"" . number_format($data["ptc"], 4) . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Paid Sign-Ups:</TD><TD><INPUT TYPE=\"text\" NAME=\"paidsignups\" VALUE=\"" . number_format($data["paidsignups"], 4) . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Leads & Sales:</TD><TD><INPUT TYPE=\"text\" NAME=\"leads_sales\" VALUE=\"" . number_format($data["leads_sales"], 4) . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Games:</TD><TD><INPUT TYPE=\"text\" NAME=\"credits\" VALUE=\"" . number_format($data["games"], 4) . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Credits:</TD><TD><INPUT TYPE=\"text\" NAME=\"credits\" VALUE=\"" . number_format($data["credits"], 4) . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Debits:</TD><TD><INPUT TYPE=\"text\" NAME=\"debits\" VALUE=\"" . number_format($data["debits"], 4) . "\" SIZE=\"30\"></TD></TR>\n";
			
			if(_MEMBER_POINTS == "YES")
			{
				$text	.= "<TR><TD>Point credits:</TD><TD><INPUT TYPE=\"text\" NAME=\"points\" VALUE=\"" . number_format($data["points"], 2) . "\" SIZE=\"30\"></TD></TR>\n";
				$text	.= "<TR><TD>Point debits:</TD><TD><INPUT TYPE=\"text\" NAME=\"dpoints\" VALUE=\"" . number_format($data["dpoints"], 2) . "\" SIZE=\"30\"></TD></TR>\n";
			}
			
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
			
			$ref_data	= unserialize($data["referral_data"]);
			
			$ref_earnings	= 0;
			$ref_pearnings	= 0;
			
			for($i = 1; $i - 1 < $referrals->GetLevelData($data["premium"]); $i++)
			{
				$text	.= "<TR><TD>Active Referrals level $i (" . count($referrals->GetNumReferrals($data["id"], $i, 1)) . "):</TD><TD>";
				
				if(_MEMBER_POINTS == "YES")
					$text	.= "<INPUT TYPE=\"text\" NAME=\"referral_data[plevel_$i]\" VALUE=\"" . number_format($ref_data["plevel_$i"], 2) . "\" SIZE=\"9\"> Pts / ";
				
				$text	.= _ADMIN_CURRENCY . " <INPUT TYPE=\"text\" NAME=\"referral_data[level_$i]\" VALUE=\"" . number_format($ref_data["level_$i"], 4) . "\" SIZE=\"9\"></TD></TR>\n";
				
				if((_REFERRAL_LOGGEDIN != 0 || _REFERRAL_EARNED != 0) && _REFERRAL_WITHIN != 0 && _REFERRAL_TYPE == "CREDITS")
				{
					$numI	= count($referrals->GetNumReferrals($data["id"], $i, 0));
					
					$text	.= "<TR><TD>Inactive Referrals level $i ($numI):</TD><TD>" . _ADMIN_CURRENCY . " " . number_format(($referrals->GetLevelData($data["premium"], $i) * $numI), 4) . "</TD></TR>\n";
				}
				
				$ref_earnings	+= $ref_data["level_$i"];
				$ref_pearnings	+= $ref_data["plevel_$i"];
			}
			
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					  ."<TR><TD>Total cash earnings:</TD><TD><B>" . _ADMIN_CURRENCY . " " . number_format(($ref_earnings+$data["bonus"]+$data["clickthrus"]+$data["ptc"]+$data["paidsignups"]+$data["leads_sales"]+$data["games"]+$data["credits"]-$data["debits"]), 4) . "</B></TD></TR>\n";
			
			if(_MEMBER_POINTS == "YES")
				$text	.= "<TR><TD>Total point earnings:</TD><TD><B>" . number_format(($ref_pearnings+$data["points"]) - $data["dpoints"], 2) . " Pts</B></TD></TR>\n";
			
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\"><B>Notes for \"" . $data["email"] . "\"</B></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\" ALIGN=\"center\"><TEXTAREA NAME=\"notes\" COLS=\"68\" ROWS=\"8\">" . htmlentities($data["notes"]) . "</TEXTAREA></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\" ALIGN=\"center\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Account\"></TD></TR>\n"
					  ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "export")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(!isset($_POST["fields"]) || !isset($_POST["filetype"]))
				exit($error->Report("Memberlist", "You have to fill out the form."));
			
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=memberlist." . $_POST["filetype"]);
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
			
			$content	= "";
			$sql		= "";
			$fields		= $_POST["fields"];
			$total		= count($fields);
			$i			= 1;
			
			foreach($fields AS $field => $value)
			{
				$content	.= "$field";
				$sql		.= "$field";
				
				if($content && $total != $i)
				{
					$content	.= $sep1;
					$sql		.= ",";
				}
				
				$i++;
			}
			
			$content	.= "\r\n";
			
			$db->Query("SELECT $sql FROM users ORDER BY regdate " . $_POST["direction"]);
			
			while($row = $db->NextRow())
			{
				$j	= 1;
				
				foreach($row AS $name => $value)
				{
					if(!is_numeric($name))
					{
						if($fields[$name])
						{
							if($name == "regdate" || $name == "lastlogin")
							{
								if($value == 0)
								{
									$value	= "-";
								}
								else
								{
									$value	= date(_SITE_DATESTAMP . " h:i", $value);
								}
							}
							elseif($name == "payment_method")
								$value	= $db->Fetch("SELECT method FROM payment_methods WHERE id='$value'", 2);
							elseif($name == "active")
								$value	= $value != "yes" ? "no" : "yes";
							
							$content	.= "\"$value\"";
							
							if($content && $total != $j)
								$content	.= $sep2;
							
							$j++;
						}
					}
				}
				
				$content	.= "\r\n";
			}
			
			echo $content;
		}
		else
		{
			$text		= "<FORM ACTION=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=export\" METHOD=\"post\">\n"
						 ."<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD COLSPAN=\"2\" ALIGN=\"center\"><B>Download Member List</B></TD></TR>"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD COLSPAN=\"2\">Check the boxes next to the fields you want to download. All checked fields will be included in your downloadable log.</TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD VALIGN=\"top\" WIDTH=\"50%\"><TABLE WIDTH=\"100%\">"
						 ."<TR><TD><B>Fields</B></TD></TR>"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[id]\" checked> Member ID</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[email]\" checked> E-Mail Address</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[fname]\" checked> First Name</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[sname]\" checked> Last Name</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[address]\" checked> Address</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[city]\" checked> City</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[state]\" checked> State</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[zipcode]\" checked> Zipcode</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[gender]\" checked> Gender</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[birth_month]\" checked> Birth Month</TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD><B>Direction</B></TD></TR>"
						 ."<TR><TD><INPUT TYPE=\"radio\" NAME=\"direction\" VALUE=\"asc\" checked> Ascending</TD></TR>"
						 ."<TR><TD><INPUT TYPE=\"radio\" NAME=\"direction\" VALUE=\"desc\"> Descending</TD></TR>"
						 ."</TABLE></TD><TD VALIGN=\"top\" WIDTH=\"50%\"><TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD><B>Fields</B></TD></TR>"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[birth_day]\" checked> Birth Day</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[birth_year]\" checked> Birth Year</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[payment_method]\" checked> Payment Method</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[payment_account]\" checked> Payment Account</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[sessions]\" checked> Sessions</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[active]\" checked> Active</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[remote_addr]\" checked> IP Address</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[lastlogin]\" checked> Last Login</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[regdate]\" checked> Sign-Up Date</TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>"
						 ."<TR><TD><B>Download File Types</B></TD></TR>"
						 ."<TR><TD><INPUT TYPE=\"radio\" NAME=\"filetype\" VALUE=\"csv\" checked> Comma delimited file (for use in any spreadsheet application)</TD></TR>"
						 ."<TR><TD><INPUT TYPE=\"radio\" NAME=\"filetype\" VALUE=\"txt\"> Tab delimited file</TD></TR>"
						 ."</TABLE></TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Download\"></TD></TR>\n"
						 ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "viewinactive")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(($_POST["order"] == "asc" && !$_POST["ll1"]) || ($_POST["order"] == "desc" && !$_POST["ll2"]) || !$_POST["order"])
				exit($error->Report("Memberlist", "You left a field empty."));
			
			header("Location: " . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&op=inactive&order=" . $_POST["order"] . "&ll1=" . $main->date2Stamp($_POST["ll1"]) . "&ll2=" . $main->date2Stamp($_POST["ll2"]));
		}
		else
		{
			$text	= "<FORM ACTION=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=viewinactive\" METHOD=\"POST\">\n"
					 ."<TABLE>\n"
					 ."<TR><TD COLSPAN=\"2\" ALIGN=\"center\"><B>List inactive members</B></TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					 ."<TR><TD><INPUT TYPE=\"radio\" NAME=\"order\" VALUE=\"asc\"> Logged in after:</TD><TD><INPUT TYPE=\"text\" NAME=\"ll1\"> (mm-dd-yyyy)</TD></TR>\n"
					 ."<TR><TD><INPUT TYPE=\"radio\" NAME=\"order\" VALUE=\"desc\"> Logged in before:</TD><TD><INPUT TYPE=\"text\" NAME=\"ll2\"> (mm-dd-yyyy)</TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"List Accounts\"></TD></TR>\n"
					 ."</TABLE>\n"
					 ."</FORM>\n";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "viewops")
	{
		$text	= "<SCRIPT LANGUAGE=\"javascript\">\n  function confirmDelete(uid)\n  {\n  	var answer = confirm (\"Are you sure that you want to delete this operator?"
				 ."\");\n  	\n  	if (answer)\n  		window.location = \"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=delete&uid=\" + uid;\n  }\n</SCRIPT>\n"
				 ."<FORM NAME=\"users\" ACTION=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=mass\" METHOD=\"post\">\n"
				 ."<SCRIPT SRC=\"" . _SITE_URL . "/inc/js/functions.js\" LANGUAGE=\"javascript\"></SCRIPT>\n"
				 ."<SCRIPT LANGUAGE=\"javascript\">var frm = document.users;</SCRIPT>\n"
				 ."<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Username</TD><TD>Password</TD><TD>Sign-Up Date</TD><TD>Logins</TD><TD>Active</TD><TD>Action</TD><TD><INPUT TYPE=\"checkbox\" NAME=\"allbox\" ONCLICK=\"CheckAll();\"></TD></TR>\n";
		
		$db->Query("SELECT id, email, password, sessions, regdate, active FROM users WHERE operator='yes' ORDER BY email ASC");
		
		while($row = $db->NextRow())
		{
			$row["active"]	= $row["active"] != "yes" ? "no" : "yes";
			
			$text			.= "<TR BGCOLOR=\"#EAEAEA\">\n"
							  ."<TD>" . $row["email"] . "</TD><TD>" . $row["password"] . "</TD><TD>" . date(_SITE_DATESTAMP, $row["regdate"]) . "</TD>" . $qVar3 . "<TD>" . $row["sessions"] . "</TD><TD>" . $row["active"] . "</TD>\n"
							  ."<TD><A HREF=\"javascript:confirmDelete(" . $row["id"] . ")\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A> "
							  ."<A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A></TD>\n"
							  ."<TD><INPUT TYPE=\"checkbox\" NAME=\"users[" . $row["id"] . "]\" ONCLICK=\"CheckItem(this);\"></TD></TR>\n";
		}
		
		$text		.= "</TABLE><BR>\n";
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD ALIGN=\"right\"><INPUT TYPE=\"submit\" NAME=\"deactivate\" VALUE=\"Deactivate\"> or <INPUT TYPE=\"submit\" NAME=\"delete\" VALUE=\"Delete\"> selected operators</TD></TR></TABLE></FORM>";
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "\">Go back to memberlist</A></TD></TR></TABLE>\n";
		
		$main->printText($text);
	}
	elseif($_GET["action"] == "mass")
	{
		if(!is_array($_POST["users"]))
			exit($error->Report("Memberlist", "You have to select at least one member to delete."));
		
		foreach($_POST["users"] AS $name => $value)
		{
			if($_POST["delete"])
				$user->Remove($name);
			
			if($_POST["deactivate"])
				$db->Query("UPDATE users SET active='no' WHERE id='$name'");
			
			if($ids)
				$ids	.= ", ";
			
			$ids	.= $name;
		}
		
		$word	= $_POST["delete"] ? "deleted" : "deactivated";
		
		$main->printText("<B>Memberlist</B><BR><BR>The following accounts have been $word:<BR><BR>$ids.", 1);
	}
	else
	{
		if($_GET["order"])
		{
			if($_GET["order"] == "asc")
			{
				$char	= ">";
				$stamp	= $_GET["ll1"];
			}
			else
			{
				$char	= "<";
				$stamp	= $_GET["ll2"];
			}
			
			$qSelect	= " WHERE lastactive" . $char . "'" . $stamp . "'";
			$qUrl		= "&order=" . $_GET["order"] . "&ll1=" . $_GET["ll1"] . "&ll2=" . $_GET["ll2"];
			
			$qVar1		= ", lastactive";
			$qVar2		= "<TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&sort=lastactive$qUrl\">Last Active</A></TD>";
		}
		
		if(!$_GET["sort"])
			$_GET["sort"]	= "email";
		
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	= "<SCRIPT LANGUAGE=\"javascript\">\n  function confirmDelete(uid)\n  {\n  	var answer = confirm (\"Are you sure that you want to delete this member?"
				 ."\");\n  	\n  	if (answer)\n  		window.location = \"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=delete&uid=\" + uid;\n  }\n</SCRIPT>\n"
				 ."<BR>" . $user->NumMembers() . " from the " . $user->NumMembers(1) . " registrered members are currently activated.<BR>\n"
				 ."<FORM NAME=\"users\" ACTION=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=mass\" METHOD=\"post\">\n"
				 ."<SCRIPT SRC=\"" . _SITE_URL . "/inc/js/functions.js\" LANGUAGE=\"javascript\"></SCRIPT>\n"
				 ."<SCRIPT LANGUAGE=\"javascript\">var frm = document.users;</SCRIPT>\n"
				 ."<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&sort=email$qUrl\">Username</A></TD><TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&sort=country$qUrl\">"
				 ."Country</A></TD><TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&sort=regdate$qUrl\">Sign-Up Date</A></TD>" . $qVar2 . "<TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&sort=sessions$qUrl\">Logins</A></TD>\n"
				 ."" . $qVar2 . "<TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&sort=active$qUrl\">Active</A></TD><TD><A HREF=\"#\">Action</A></TD><TD><INPUT TYPE=\"checkbox\" NAME=\"allbox\" ONCLICK=\"CheckAll();\"></TD></TR>\n";
		
		$db->Query("SELECT id, email, country, sessions, regdate, active" . $qVar1 . " FROM users" . $qSelect . " ORDER BY " . $_GET["sort"] . " ASC LIMIT $start, 100");
		
		while($row = $db->NextRow())
		{
			if($_GET["order"])
				$qVar3		= "<TD>" . date(_SITE_DATESTAMP, $row["lastactive"]) . "</TD>";
			
			$row["active"]	= $row["active"] != "yes" ? "no" : "yes";
			
			if($row["country"] == "")
				$country	= "Undefined";
			else
			{
				foreach($GLOBALS["countries"] AS $name => $value)
				{
					if($value == $row["country"])
					{
						$country	= $name;
						
						break;
					}
					else
						$country	= $row["country"];
				}
			}
			
			if(strlen($country) > 18)
				$country	= substr($country, 0, 16) . "..";
			
			if(strlen($row["email"]) > 18)
				$row["email"]	= substr($row["email"], 0, 16) . "..";
			
			$text			.= "<TR BGCOLOR=\"#EAEAEA\">\n"
							  ."<TD>" . $row["email"] . "</TD><TD>$country</TD><TD>" . date(_SITE_DATESTAMP, $row["regdate"]) . "</TD>" . $qVar3 . "<TD>" . $row["sessions"] . "</TD><TD>" . $row["active"] . "</TD>\n"
							  ."<TD><A HREF=\"javascript:confirmDelete(" . $row["id"] . ")\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A> "
							  ."<A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A> \n"
							  ."<A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=credit/debit&uid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/dollar.gif\" ALT=\"Credit/Debit\" BORDER=\"0\"></A> \n"
							  ."<A HREF=\"" . _ADMIN_URL . "/blocklist.php?sid=" . $session->ID . "&action=add&uid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/block.gif\" ALT=\"Block\" BORDER=\"0\"></A> \n"
							  ."<A HREF=\"" . _ADMIN_URL . "/referrals.php?sid=" . $session->ID . "&action=viewdownline&uid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/tree.gif\" ALT=\"View Direct Downline\" BORDER=\"0\"></A></TD>\n"
							  ."<TD><INPUT TYPE=\"checkbox\" NAME=\"users[" . $row["id"] . "]\" ONCLICK=\"CheckItem(this);\"></TD></TR>\n";
		}
		
		$text		.= "</TABLE><BR>\n";
		
		$db->Query("SELECT id FROM users" . $qSelect);
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD ALIGN=\"right\"><INPUT TYPE=\"submit\" NAME=\"deactivate\" VALUE=\"Deactivate\"> or <INPUT TYPE=\"submit\" NAME=\"delete\" VALUE=\"Delete\"> selected members</TD></TR></TABLE></FORM>";
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD ALIGN=\"center\">" . $main->GeneratePages(_ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&sort=" . $_GET["sort"] . $qUrl, $db->NumRows(), 100, $start) . "</TD></TR></TABLE>\n";
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=viewops\">View all operators</A></TD></TR></TABLE>\n";
		
		$main->printText($text);
	}

?>