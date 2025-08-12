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
	
	$tml->RegisterVar("TITLE", "Paid E-Mails Manager");
	
	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Paid E-Mails Manager","You can not access this page."));
	
	if($_GET["action"] == "delete")
	{
		$db->Query("SELECT id FROM paid_emails WHERE id='" . $_GET["mid"] . "'");

		if($db->NumRows() == 0)
			exit($error->Report("Paid E-Mails Manager","An error has occured."));
		
		$db->Query("DELETE FROM sent_emails WHERE mid='" . $_GET["mid"] . "'");
		$db->Query("DELETE FROM paid_emails WHERE id='" . $_GET["mid"] . "'");
		$db->Query("DELETE FROM massmailer WHERE mid='" . $_GET["mid"] . "'");
		$db->Query("DELETE FROM actions WHERE type='emails' AND aid='" . $_GET["mid"] . "'");
		
		$main->printText("<B>Paid E-Mails Manager</B><BR><BR>Paid E-Mail Deleted.", 1);
	}
	elseif($_GET["action"] == "add")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("SELECT id FROM users WHERE id='" . $_POST["advertiser"] . "' AND advertiser='yes'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Paid E-Mails Manager", "Advertiser account not found, please check account."));
			
			$db->Query("SELECT id FROM paid_emails WHERE subject='" . $_POST["subject"] . "'");
			
			if($db->NumRows() == 1)
				exit($error->Report("Paid E-Mails Manager", "There already is an email with the subject \"" . $_POST["subject"] . "\"."));
			
			$db->Query("INSERT INTO paid_emails (aid, subject, url, text, texttype, description, type, timer, priority, c_type, credits, ref_earnings, active) VALUES ('" . $_POST["advertiser"] . "', '" . $_POST["subject"] . "', '" . $_POST["url"] . "', '" . $_POST["text"] . "', '" . $_POST["texttype"] . "', '" . $_POST["description"] . "', '" . $_POST["type"] . "', '" . $_POST["timer"] . "', '" . $_POST["priority"] . "', '" . $_POST["c_type"] . "', '" . $_POST["credits"] . "', '" . $_POST["ref_earnings"] . "', '" . $_POST["active"] . "');");
			
			$main->printText("<B>Paid E-Mails Manager</B><BR><BR>Paid E-Mail Added.<BR><BR><A HREF=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=send&type=paidemail&id=" . $db->LastInsertID() . "\">Click here to send this email directly</A><BR><BR>or" , 1);
		}
		else
		{
			$order_data	= $db->Fetch("SELECT ad_url, ad_title, ad_text FROM ad_orders WHERE id='" . $_GET["oid"] . "'");
			
			$text	.= "<FORM NAME=\"addmail\" ACTION=\"" . _ADMIN_URL . "/paidmails.php?sid=" . $session->ID . "&action=add\" METHOD=\"POST\">\n";
			
			if(_MEMBER_POINTS == "NO")
				$text		.= "<INPUT TYPE=\"hidden\" NAME=\"c_type\" VALUE=\"cash\">";
			
			$text	.= "<SCRIPT LANGUAGE=\"javascript\" TYPE=\"text/javascript\">\n"
					  ."function f_mailtype(option){\n\n"
					  ."  form = document.addmail;\n"
					  ."  if(option == 'paid')\n"
					  ."  {\n"
					  ."    T1.style.display = '';\n"
					  ."    T2.style.display = '';\n"
					  ."    T3.style.display = '';\n"
					  ."    T4.style.display = '';\n"
					  ."    T5.style.display = '';\n"
					  ."    T6.style.display = '';\n"
					  ."    T7.style.display = '';\n"
					  ."    T8.style.display = '';\n"
					  ."    T9.style.display = '';\n"
					  ."    T10.style.display = '';\n"
					  ."  }\n"
					  ."  else\n"
					  ."  if(option == 'unpaid')\n"
					  ."  {\n"
					  ."    T1.style.display = 'none';\n"
					  ."    T2.style.display = 'none';\n"
					  ."    T3.style.display = 'none';\n"
					  ."    T4.style.display = 'none';\n"
					  ."    T5.style.display = 'none';\n"
					  ."    T6.style.display = 'none';\n"
					  ."    T7.style.display = 'none';\n"
					  ."    T8.style.display = 'none';\n"
					  ."    T9.style.display = 'none';\n"
					  ."    T10.style.display = 'none';\n"
					  ."  }\n"
					  ."}\n"
					  ."</SCRIPT>\n"
					  ."<TABLE WIDTH=\"100%\">\n"
					  ."<TR><TD COLSPAN=\"2\"><B>Special code you can use in e-mail text</B></TD></TR>"
					  ."<TR><TD COLSPAN=\"2\"><TABLE WIDTH=\"100%\">"
					  ."<TR><TD>&lt;EMAIL&gt;</TD><TD>&lt;PASSWORD&gt;</TD><TD>&lt;FNAME&gt;</TD><TD>&lt;SNAME&gt;</TD></TR>\n"
					  ."<TR><TD>&lt;ADDRESS&gt;</TD><TD>&lt;CITY&gt;</TD><TD>&lt;STATE&gt;</TD><TD>&lt;ZIPCODE&gt;</TD></TR>\n"
					  ."<TR><TD>&lt;COUNTRY&gt;</TD><TD COLSPAN=\"3\">&lt;PAYMENT_ACCOUNT&gt;</TD></TR>\n"
					  ."</TABLE></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					  ."<TR><TD>Subject:</TD><TD><INPUT TYPE=\"text\" NAME=\"subject\" SIZE=\"30\" VALUE=\"" . $order_data["ad_title"] . "\"></TD></TR>\n"
					  ."<TR><TD>Type:</TD><TD><SELECT NAME=\"type\" SIZE=\"1\" ONCHANGE=\"f_mailtype(this.value)\"><OPTION VALUE=\"paid\" selected>Paid E-Mail</OPTION><OPTION VALUE=\"unpaid\">Unpaid E-Mail</OPTION></SELECT></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T5\" STYLE=\"DISPLAY: \">Credit Type:</DIV></TD><TD><DIV ID=\"T6\" STYLE=\"DISPLAY: \"><SELECT NAME=\"c_type\" SIZE=\"1\"";
			
			if(_MEMBER_POINTS == "NO")
				$text		.= " disabled";
			
			$text	.= "><OPTION VALUE=\"cash\" selected>Cash</OPTION><OPTION VALUE=\"points\">Points</OPTION></SELECT></DIV></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T1\" STYLE=\"DISPLAY: \">Credits:</DIV></TD><TD><DIV ID=\"T2\" STYLE=\"DISPLAY: \"><INPUT TYPE=\"text\" NAME=\"credits\" SIZE=\"30\"></DIV></TD></TR>\n"
					  ."<TR><TD>Priority:</TD><TD><SELECT NAME=\"priority\" SIZE=\"1\"><OPTION VALUE=\"5\">Low</OPTION><OPTION VALUE=\"3\" SELECTED>Normal</OPTION><OPTION VALUE=\"1\">High</OPTION></SELECT></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T3\" STYLE=\"DISPLAY: \">Visit Time:</DIV></TD><TD><DIV ID=\"T4\" STYLE=\"DISPLAY: \"><INPUT TYPE=\"text\" NAME=\"timer\" SIZE=\"30\" VALUE=\"" . _MEMBER_EMAILREFRESH . "\"></DIV></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T7\" STYLE=\"DISPLAY: \">Url:</DIV></TD><TD><DIV ID=\"T8\" STYLE=\"DISPLAY: \"><INPUT TYPE=\"text\" NAME=\"url\" SIZE=\"30\" VALUE=\"" . $order_data["ad_url"] . "\"></DIV></TD></TR>\n"
					  ."<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\" selected>Yes</OPTION><OPTION VALUE=\"no\">No</OPTION></SELECT></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T9\" STYLE=\"DISPLAY: \">Referral Earnings:</DIV></TD><TD><DIV ID=\"T10\" STYLE=\"DISPLAY: \"><SELECT NAME=\"ref_earnings\" SIZE=\"1\"><OPTION VALUE=\"yes\" selected>Yes</OPTION><OPTION VALUE=\"no\">No</OPTION></SELECT></DIV></TD></TR>\n"
					  ."<TR><TD VALIGN=\"top\">Text Type:</TD><TD><SELECT NAME=\"texttype\" SIZE=\"1\"><OPTION VALUE=\"auto\">Auto</OPTION><OPTION VALUE=\"html\">HTML</OPTION><OPTION VALUE=\"plain\">Plain Text</OPTION></SELECT></TD></TR>\n"
					  ."<TR><TD VALIGN=\"top\">E-Mail Text: (HTML)</TD><TD><TEXTAREA NAME=\"text\" COLS=\"40\" ROWS=\"8\">" . htmlentities($order_data["ad_text"]) . "</TEXTAREA></TD></TR>\n"
					  ."<TR><TD VALIGN=\"top\">Description: (HTML)</TD><TD><TEXTAREA NAME=\"description\" COLS=\"40\" ROWS=\"8\"></TEXTAREA></TD></TR>\n"
					  ."<TR><TD>Advertiser:<BR><FONT SIZE=\"1\">account e-mail</FONT></TD>\n"
					  ."<TD><SELECT NAME=\"advertiser\" SIZE=\"1\">\n";
			
			$db->Query("SELECT id, email FROM users WHERE active='yes' AND advertiser='yes'");
			
			while($row = $db->NextRow())
				$text		.= "<OPTION VALUE=\"" . $row["id"] . "\" " . ($row["id"] == $_GET["aid"] ? "selected" : "") . ">" . $row["email"] . "</OPTION>\n";
			
			$text		.= "</SELECT></TD></TR>\n"
						  ."<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Paid E-Mail\"></TD></TR>\n"
						  ."</TABLE></FORM>";

			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "edit")
	{
		$db->Query("SELECT id FROM paid_emails WHERE id='" . $_GET["mid"] . "'");

		if($db->NumRows() == 0)
			exit($error->Report("Paid E-Mails Manager", "This paid e-mail doesn't exists."));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("SELECT id FROM users WHERE id='" . $_POST["advertiser"] ."' AND advertiser='yes'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Paid E-Mails Manager", "Advertiser account not found, please check account."));
			
			$db->Query("SELECT id FROM paid_emails WHERE subject='" . $_POST["subject"] . "' AND id!='" . $_GET["mid"] . "'");
			
			if($db->NumRows() == 1)
				exit($error->Report("Paid E-Mails Manager", "There already is an email with the subject \"" . $_POST["subject"] . "\"."));
			
			$db->Query("UPDATE paid_emails SET aid='" . $_POST["advertiser"] ."', subject='" . $_POST["subject"] . "', url='" . $_POST["url"] . "', text='" . $_POST["text"] . "', texttype='" . $_POST["texttype"] . "', description='" . $_POST["description"] . "', type='" . $_POST["type"] . "', timer='" . $_POST["timer"] . "', priority='" . $_POST["priority"] . "', c_type='" . $_POST["c_type"] . "', credits='" . $_POST["credits"] . "', ref_earnings='" . $_POST["ref_earnings"] . "', active='" . $_POST["active"] . "' WHERE id='" . $_GET["mid"] . "'");
			
			$main->printText("<B>Paid E-Mails Manager</B><BR><BR>Paid E-Mail Edited.", 1);
		}
		else
		{
			$data	= $main->Trim($db->Fetch("SELECT * FROM paid_emails WHERE id='" . $_GET["mid"] . "'"));
			
			$text	.= "<FORM NAME=\"editmail\" ACTION=\"" . _ADMIN_URL . "/paidmails.php?sid=" . $session->ID . "&action=edit&mid=" . $_GET["mid"] . "\" METHOD=\"POST\">\n";
			
			if(_MEMBER_POINTS == "NO")
				$text	.= "<INPUT TYPE=\"hidden\" NAME=\"c_type\" VALUE=\"cash\">";
			
			$text	.= "<SCRIPT LANGUAGE=\"javascript\" TYPE=\"text/javascript\">\n"
					  ."function f_mailtype(option){\n\n"
					  ."        form = document.editmail;\n"
					  ."        if(option == 'paid')\n"
					  ."        {\n"
					  ."           T1.style.display = '';\n"
					  ."           T2.style.display = '';\n"
					  ."           T3.style.display = '';\n"
					  ."           T4.style.display = '';\n"
					  ."           T5.style.display = '';\n"
					  ."           T6.style.display = '';\n"
					  ."           T7.style.display = '';\n"
					  ."           T8.style.display = '';\n"
					  ."           T9.style.display = '';\n"
					  ."           T10.style.display = '';\n"
					  ."        }\n"
					  ."        else\n"
					  ."        if(option == 'unpaid')\n"
					  ."        {\n"
					  ."           T1.style.display = 'none';\n"
					  ."           T2.style.display = 'none';\n"
					  ."           T3.style.display = 'none';\n"
					  ."           T4.style.display = 'none';\n"
					  ."           T5.style.display = 'none';\n"
					  ."           T6.style.display = 'none';\n"
					  ."           T7.style.display = 'none';\n"
					  ."           T8.style.display = 'none';\n"
					  ."           T9.style.display = 'none';\n"
					  ."           T10.style.display = 'none';\n"
					  ."        }\n"
					  ."}\n"
					  ."</SCRIPT>\n"
					  ."<TABLE WIDTH=\"100%\">\n"
					  ."<TR><TD COLSPAN=\"2\"><B>Special code you can use in e-mail text</B></TD></TR>"
					  ."<TR><TD COLSPAN=\"2\"><TABLE WIDTH=\"100%\">"
					  ."<TR><TD>&lt;EMAIL&gt;</TD><TD>&lt;PASSWORD&gt;</TD><TD>&lt;FNAME&gt;</TD><TD>&lt;SNAME&gt;</TD></TR>\n"
					  ."<TR><TD>&lt;ADDRESS&gt;</TD><TD>&lt;CITY&gt;</TD><TD>&lt;STATE&gt;</TD><TD>&lt;ZIPCODE&gt;</TD></TR>\n"
					  ."<TR><TD>&lt;COUNTRY&gt;</TD><TD COLSPAN=\"3\">&lt;PAYMENT_ACCOUNT&gt;</TD></TR>\n"
					  ."</TABLE></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					  ."<TR><TD>Subject:</TD><TD><INPUT TYPE=\"text\" NAME=\"subject\" VALUE=\"" . $data["subject"] . "\" SIZE=\"30\"></TD></TR>\n";
			
			if($data["type"] == "paid")
			{
				$text	.= "<TR><TD>Type:</TD><TD><SELECT NAME=\"type\" SIZE=\"1\" ONCHANGE=\"f_mailtype(this.value)\"><OPTION VALUE=\"paid\" selected>Paid E-Mail</OPTION><OPTION VALUE=\"unpaid\">Unpaid E-Mail</OPTION></SELECT></TD></TR>\n";
			}
			else
			{
				$tmp	= "none";
				
				$text	.= "<TR><TD>Type:</TD><TD><SELECT NAME=\"type\" SIZE=\"1\" ONCHANGE=\"f_mailtype(this.value)\"><OPTION VALUE=\"paid\">Paid E-Mail</OPTION><OPTION VALUE=\"unpaid\" selected>Unpaid E-Mail</OPTION></SELECT></TD></TR>\n";
			}
			
			if(_MEMBER_POINTS == "NO")
				$value	= " disabled";
			
			if(_MEMBER_POINTS == "NO" || $data["c_type"] == "cash")
				$text	.= "<TR><TD><DIV ID=\"T7\" STYLE=\"DISPLAY: $tmp\">Credit Type:</DIV></TD><TD><DIV ID=\"T8\" STYLE=\"DISPLAY: $tmp\"><SELECT NAME=\"c_type\" SIZE=\"1\"$value><OPTION VALUE=\"cash\" selected>Cash</OPTION><OPTION VALUE=\"points\">Points</OPTION></SELECT></DIV></TD></TR>\n";
			else
				$text	.= "<TR><TD><DIV ID=\"T7\" STYLE=\"DISPLAY: $tmp\">Credit Type:</DIV></TD><TD><DIV ID=\"T8\" STYLE=\"DISPLAY: $tmp\"><SELECT NAME=\"c_type\" SIZE=\"1\"><OPTION VALUE=\"cash\">Cash</OPTION><OPTION VALUE=\"points\" selected>Points</OPTION></SELECT></DIV></TD></TR>\n";
			
			$text	.= "<TR><TD><DIV ID=\"T1\" STYLE=\"DISPLAY: $tmp\">Credits:</DIV></TD><TD><DIV ID=\"T2\" STYLE=\"DISPLAY: $tmp\"><INPUT TYPE=\"text\" NAME=\"credits\" VALUE=\"".$data["credits"]."\" SIZE=\"30\"></DIV></TD></TR>\n"
					  ."<TR><TD>Priority:</TD><TD><SELECT NAME=\"priority\" SIZE=\"1\"><OPTION VALUE=\"5\"" . ($data["priority"] == "5" ? "selected" : "") . ">Low</OPTION><OPTION VALUE=\"3\"" . ($data["priority"] == "3" ? "selected" : "") . ">Normal</OPTION><OPTION VALUE=\"1\"" . ($data["priority"] == "1" ? "selected" : "") . ">High</OPTION></SELECT></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T3\" STYLE=\"DISPLAY: $tmp\">Visit Time:</DIV></TD><TD><DIV ID=\"T4\" STYLE=\"DISPLAY: $tmp\"><INPUT TYPE=\"text\" NAME=\"timer\" VALUE=\"".$data["timer"]."\" SIZE=\"30\"></DIV></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T5\" STYLE=\"DISPLAY: $tmp\">Url:</DIV></TD><TD><DIV ID=\"T6\" STYLE=\"DISPLAY: $tmp\"><INPUT TYPE=\"text\" NAME=\"url\" VALUE=\"" . $data["url"] . "\" SIZE=\"30\"></DIV></TD></TR>\n"
					  ."<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\"" . ($data["active"] == "yes" ? " selected" : "") . ">Yes</OPTION><OPTION VALUE=\"no\"" . ($data["active"] == "no" ? " selected" : "") . ">No</OPTION></SELECT></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T9\" STYLE=\"DISPLAY: $tmp\">Referral Earnings:</DIV></TD><TD><DIV ID=\"T10\" STYLE=\"DISPLAY: $tmp\"><SELECT NAME=\"ref_earnings\" SIZE=\"1\"><OPTION VALUE=\"yes\"" . ($data["ref_earnings"] == "yes" ? " selected" : "") . ">Yes</OPTION><OPTION VALUE=\"no\"" . ($data["ref_earnings"] == "no" ? " selected" : "") . ">No</OPTION></SELECT></DIV></TD></TR>\n"
					  ."<TR><TD VALIGN=\"top\">Text Type:</TD><TD><SELECT NAME=\"texttype\" SIZE=\"1\"><OPTION VALUE=\"auto\"" . ($data["texttype"] == "auto" ? " selected" : "") . ">Auto</OPTION><OPTION VALUE=\"html\"" . ($data["texttype"] == "html" ? " selected" : "") . ">HTML</OPTION><OPTION VALUE=\"plain\"" . ($data["texttype"] == "plain" ? " selected" : "") . ">Plain Text</OPTION></SELECT></TD></TR>\n"
					  ."<TR><TD VALIGN=\"top\">E-Mail Text: (HTML)</TD><TD><TEXTAREA NAME=\"text\" COLS=\"40\" ROWS=\"8\">" . $data["text"] . "</TEXTAREA></TD></TR>\n"
					  ."<TR><TD VALIGN=\"top\">Description: (HTML)</TD><TD><TEXTAREA NAME=\"description\" COLS=\"40\" ROWS=\"8\">" . $data["description"] . "</TEXTAREA></TD></TR>\n"
					  ."<TR><TD>Advertiser:</TD><TD><SELECT NAME=\"advertiser\" SIZE=\"1\">\n";
			
			$db->Query("SELECT id, email FROM users WHERE active='yes' AND advertiser='yes'");
			
			while($row = $db->NextRow())
			{
				$text	.= "<OPTION VALUE=\"" . $row["id"] . "\"" . ($row["id"] == $data["aid"] ? " selected" : "") . ">" . $row["email"] . "</OPTION>\n";
			}
			
			$text	.= "</SELECT></TD></TR>\n"
					  ."<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Paid E-Mail\"></TD></TR>\n"
					  ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "who")
	{
		$db->Query("SELECT id FROM paid_emails WHERE id='" . $_GET["mid"] . "'");

		if($db->NumRows() == 0)
			exit($error->Report("Paid E-Mails Manager", "This paid e-mail doesn't exists."));
		
		$db->Query("SELECT id FROM sent_emails WHERE mid='" . $_GET["mid"] . "'");
		
		$total	= $db->NumRows();
		
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Member</TD><TD>Status</TD><TD>Sent</TD></TR>\n";
		
		if($total == 0)
		{
			$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD COLSPAN=\"3\">No people clicked this link yet or the mailing has not been sent yet.</TD></TR>\n";
		}
		else
		{
			$db->Query("SELECT uid, status, dateStamp FROM sent_emails WHERE mid='" . $_GET["mid"] . "' ORDER BY status ASC, dateStamp DESC LIMIT $start, 50");
			
			while($row = $db->NextRow())
			{
				$email	= $db->Fetch("SELECT email FROM users WHERE id='" . $row["uid"] . "'", 2);
				
				$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["uid"] . "\">$email</A></TD>\n"
						  ."<TD>" . $row["status"] . "</TD><TD>" . date(_SITE_DATESTAMP, $row["dateStamp"]) . "</TD></TR>\n";
			}
		}
		
		$text	.= "</TABLE><BR>\n";
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/paidmails.php?sid=" . $session->ID . "&action=who&mid=" . $_GET["mid"], $total, 50, $start) . "</TD></TR></TABLE>\n";
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/paidmails.php?sid=" . $session->ID . "\">Click here to go back.</A></TD></TR></TABLE>\n";
		
		$main->printText($text);
	}
	else
	{
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Subject</TD><TD>Timer</TD><TD>Credits</TD><TD>Type</TD><TD>Active</TD><TD>Action</TD></TR>\n";
		
		$db->Query("SELECT id, subject, type, timer, c_type, credits, active FROM paid_emails ORDER BY id DESC LIMIT $start, 30");
		
		while($row = $db->NextRow())
		{
			$row	= $main->Trim($row);
			
			$who	= $row["type"] == "unpaid" ? "<IMG SRC=\"" . _SITE_URL . "/inc/img/who.gif\" ALT=\"Who Clicked?\" BORDER=\"0\">" : "<A HREF=\"" . _ADMIN_URL . "/paidmails.php?sid=" . $session->ID . "&action=who&mid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/who.gif\" ALT=\"Who Clicked?\" BORDER=\"0\"></A>";
			
			$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
					  ."<TD>" . $row["subject"] . "</TD><TD>" . $row["timer"] . " seconds</TD><TD>" . $row["credits"] . " " . $row["c_type"] . "</TD><TD>" . $row["type"] . "</TD><TD>" . $row["active"] . "</TD>\n"
					  ."<TD><A HREF=\"" . _ADMIN_URL . "/paidmails.php?sid=" . $session->ID . "&action=delete&mid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A> "
					  ."<A HREF=\"" . _ADMIN_URL . "/paidmails.php?sid=" . $session->ID . "&action=edit&mid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A> $who "
					  ."<A HREF=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=send&type=paidemail&id=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/send.gif\" ALT=\"Send\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text	.= "</TABLE><BR>\n";
		
		$db->Query("SELECT id FROM paid_emails");
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/paidmails.php?sid=" . $session->ID, $db->NumRows(), 30, $start) . "</TD></TR></TABLE>\n";
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/paidmails.php?sid=" . $session->ID . "&action=add\">Add Paid E-Mail</A></TD></TR></TABLE>\n";
		
		$main->printText($text);
	}

?>