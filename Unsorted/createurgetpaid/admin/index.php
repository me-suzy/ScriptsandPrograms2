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
	
	$tml->RegisterVar("TITLE", "Administration");
	
	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Administration", "You can not access this page."));
	
	if($_GET["action"] == "switch")
	{
		header("Location: " . _ADMIN_URL . "/" . $_POST["page"]);
	}
	elseif($_GET["action"] == "query")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$_POST["query"]	= str_replace("\\", "", $_POST["query"]);
			
			$rows	= $db->Query($_POST["query"]);
			
			$rows	= is_numeric($rows) ? "<BR><BR>Matching Rows: <B>$rows</B>" : "";
			
			$main->WriteToLog("run_mysql_query", "MySQL query: \"" . $_POST["query"] . "\" - \"" . $db->queryHandle[1] . "\"");
			
			$main->printText("<B>Run MySQL Query</B><BR><BR>Your query has been executed successfully:<BR><BR><CODE>" . $db->SQLHighlight($_POST["query"]) . "</CODE>" . $rows);
		}
		else
		{
			$text	= "<FORM ACTION=\"" . _ADMIN_URL . "/index.php?sid=" . $session->ID . "&action=query\" METHOD=\"post\">\n"
					 ."<TABLE WIDTH=\"100%\"><TR><TD>Enter your SQL query here:</TD></TR>\n"
					 ."<TR><TD>&nbsp;</TD></TR>\n"
					 ."<TR><TD><TEXTAREA NAME=\"query\" COLS=\"50\" ROWS=\"5\"></TEXTAREA></TD></TR>\n"
					 ."<TR><TD>&nbsp;</TD></TR>\n"
					 ."<TR><TD><INPUT TYPE=\"submit\" VALUE=\"Execute\"></TD></TR></TABLE></FORM>\n";
			
			$main->printText($text);
		}
	}
	else
	{
		if(!$session->Get("admin_lastlogin"))
		{
			$session->Set("admin_lastlogin", $db->Fetch("SELECT admin_lastlogin FROM config"));
			
			$db->Query("UPDATE config SET admin_lastlogin='IP: " . $_SERVER["REMOTE_ADDR"] . " Hostname: " . gethostbyaddr($_SERVER["REMOTE_ADDR"]) . " User Agent: " . $_SERVER["HTTP_USER_AGENT"] . " on " . date("m/d/Y h:i") . " by " . $user->Get("email") . "'");
		}
		
		$db->Query("SHOW TABLE STATUS");
		
		while($row = $db->NextRow())
		{
			$records	+= $db->Fetch("SELECT COUNT(1) FROM " . $row["Name"], 2);
			$data		+= $row["Data_length"];
			$index		+= $row["Index_length"];
			
			$tables++;
		}
		
		$text	= "<TABLE WIDTH=\"100%\">\n"
				 ."<TR><TD WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\"><H1>Create Your GetPaid Administrator v" . _SYSTEM_VERSION . "</H1></TD></TR>\n"
				 ."<TR><TD WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\" BGCOLOR=\"#FBFBFB\"><FONT SIZE=\"1\">Last login: " . $session->Get("admin_lastlogin") . "</FONT></TD></TR>\n"
				 ."<TR><TD WIDTH=\"100%\" COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				 ."<TR><TD WIDTH=\"50%\" VALIGN=\"top\"><B>MySQL Database Statistics</B><BR><BR>\n<UL>\n"
				 ."<LI>Total data size: <B>" . number_format(($data/1024)/1024, 2) . " MB</B></LI>\n"
				 ."<LI>Total index size: <B>" . number_format(($index/1024)/1024, 2) . " MB</B></LI>\n"
				 ."<LI>Total database size: <B>" . number_format((($data+$index)/1024)/1024, 2) . " MB</B></LI>\n"
				 ."<LI>Total number of records: <B>" . number_format($records) . "</B></LI>\n"
				 ."<LI>Total number of tables: <B>" . number_format($tables) . "</B></LI></UL>\n"
				 ."<B>Advertising Manager</B>\n<UL TYPE=\"square\">\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=packages\">Add/Delete/Edit the Advertising Packages</A></LI>\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=orders\">Aprove/Disaprove Advertising Orders</A></LI>\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "\">Add/Delete/Edit the Rotating Banners</A></LI></UL>\n"
				 ."<B>Database Manager</B>\n<UL TYPE=\"square\">\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/db_backup.php?sid=" . $session->ID . "\">Backup/Restore the Database</A></LI>\n"
			 	 ."<LI><A HREF=\"" . _ADMIN_URL . "/db_optimize.php?sid=" . $session->ID . "\">Optimize the Database</A></LI>\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/index.php?sid=" . $session->ID . "&action=query\">Run MySQL Query</A></LI></UL>\n"
				 ."<B>PaidTo Manager</B>\n<UL TYPE=\"square\">\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/ptc.php?sid=" . $session->ID . "\">Add/Delete/Edit/Send the Paid Clicks</A></LI>\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/paidmails.php?sid=" . $session->ID . "\">Add/Delete/Edit/Send the Paid E-Mails</A></LI>\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/leads.php?sid=" . $session->ID . "\">Add/Check/Delete/Edit the Leads</A></LI>\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/sales.php?sid=" . $session->ID . "\">Add/Check/Delete/Edit the Sales</A></LI>\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "\">Add/Delete/Edit the Paid Sign-Ups</A></LI></UL>\n"
				 ."<B>Miscellaneous</B>\n<UL TYPE=\"square\">\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/advertisers.php?sid=" . $session->ID . "\">Add/Delete/Edit the advertisers</A></LI>\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/news.php?sid=" . $session->ID . "\">Add/Delete/Edit the newstopics</A></LI>\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/blocklist.php?sid=" . $session->ID . "\">Add/Delete/Edit from block-list</A></LI>\n";
		
		if(_MEMBER_POINTS == "YES")
			$text	.= "<LI><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=convert\">Convert all point earnings</A></LI>\n";
		
		$text	.="<LI><A HREF=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=queue\">Mass Mailer queuelist</A></LI>\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/redempts.php?sid=" . $session->ID . "\">Redemption Items</A></LI>\n"
				 ."<LI><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=referers\">Top 25 Referers</A></LI></UL>\n";
		
		if(_ADDON_AP == 1 && _AP_DEPOSIT == "YES")
		{
			$text	.= "<B>Deposits</B>\n<UL TYPE=\"square\">\n"
					  ."<LI><A HREF=\"" . _ADMIN_URL . "/deposits.php?sid=" . $session->ID . "&action=paid\">Delete/View all paid deposits</A></LI>\n"
					  ."<LI><A HREF=\"" . _ADMIN_URL . "/deposits.php?sid=" . $session->ID . "&action=unpaid\">Delete/View all unpaid deposits</A></LI></UL></TD>\n";
		}
		
		$text	.= "<TD VALIGN=\"top\" WIDTH=\"50%\"><B>User Manager</B>\n<UL TYPE=\"square\">\n"
				  ."<LI><B>Cheat Finders</B><UL>\n\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/cheaters.php?sid=" . $session->ID . "&action=findaccounts\">Find accounts that may belong to cheaters</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/cheaters.php?sid=" . $session->ID . "&action=computer\">List accounts that have been recently accessed by the same computer</A></LI></UL></LI><BR>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "\">Delete/Edit/View all members</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=viewinactive\">Delete/Edit/View (in)active members</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/memberships.php?sid=" . $session->ID . "\">Add/Delete/Edit/View premium member(ships)</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=stats\">Earnings list of all members</A></LI>\n";
		
		if(_MEMBER_TCENABLE == "YES")
			$text	.= "<LI><A HREF=\"" . _ADMIN_URL . "/cheaters.php?sid=" . $session->ID . "&action=tracker\">View last 25 tracker cheaters</A></LI>\n";
		
		$text	.= "<LI><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=search\">Search a specific user</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=export\">Download Memberlist</A></LI></UL>\n"
				  ."<B>Support Tickets</B>\n<UL TYPE=\"square\">\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=open\">View open tickets [" . $db->Fetch("SELECT COUNT(id) FROM s_tickets WHERE status='open'") . "]</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=closed\">View closed tickets [" . $db->Fetch("SELECT COUNT(id) FROM s_tickets WHERE status='closed'") . "]</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=pending\">View pending tickets [" . $db->Fetch("SELECT COUNT(id) FROM s_tickets WHERE status='pending'") . "]</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=search\">Search a specific ticket</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/tickets.php?sid=" . $session->ID . "&action=cats\">Change Ticket Categories</A></LI></UL>\n"
				  ."<B>Payment Manager</B>\n<UL TYPE=\"square\">\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending\">View pending payments [" . $db->Fetch("SELECT COUNT(id) FROM payments WHERE paid='no'") . "]</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=paid\">View processed payments [" . $db->Fetch("SELECT COUNT(id) FROM payments WHERE paid='yes'") . "]</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=user\">View payments of a specific user</A></LI>\n";
		
		if(_MEMBER_TRANSFER == "YES")
			$text	.= "<LI><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=transfers\">View last earning transfers</A></LI>\n";
		
		$text	.= "<LI><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=methods\">Add/Edit/Delete payment methods</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=paypal\">Download PayPal Mass Pay file</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=history\">Download Payment History</A></LI></UL>\n"
				  ."<B>System Tools</B>\n<UL TYPE=\"square\">\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/liveupdate.php?sid=" . $session->ID . "\">Check for Updates</A></LI>\n";
		
		if(_SITE_LOGS == "YES")
			$text	.= "<LI><A HREF=\"" . _ADMIN_URL . "/logs.php?sid=" . $session->ID . "\">View Log Files</A></LI>\n";
		
		$text	.= "<LI><A HREF=\"" . _ADMIN_URL . "/reportabug.php?sid=" . $session->ID . "\">Report a Bug</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/mailer.php?sid=" . $session->ID . "\">Send an E-Mail</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "\">Site Configuration</A></LI>\n"
				  ."<LI><A HREF=\"" . _ADMIN_URL . "/templates.php?sid=" . $session->ID . "\">Template Manager</A></LI>\n";
		
		if(_SITE_STATISTICS == "YES")
			$text	.= "<LI><A HREF=\"" . _ADMIN_URL . "/stats.php?sid=" . $session->ID . "\">Website Statistics</A></LI></UL>\n";
		
		$text	.= "</TD></TR></TABLE>\n";
		
		$main->printText($text, 0, 0);
	}

?>