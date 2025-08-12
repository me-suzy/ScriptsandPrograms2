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
	
	$tml->RegisterVar("TITLE", "Paid Clicks Manager");
	
	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Paid Clicks Manager", "You can not access this page."));
	
	if($_GET["action"] == "delete")
	{
		$db->Query("SELECT id FROM paid_clicks WHERE id='" . $_GET["cid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Paid Clicks Manager", "This click doesn't exists."));
		
		$db->Query("DELETE FROM sent_clicks WHERE cid='" . $_GET["cid"] . "'");
		$db->Query("DELETE FROM paid_clicks WHERE id='" . $_GET["cid"] . "'");
		$db->Query("DELETE FROM actions WHERE type='clicks' AND aid='" . $_GET["cid"] . "'");
			
		$main->printText("<B>Paid Clicks Manager</B><BR><BR>Paid Click Deleted.", 1);
	}
	elseif($_GET["action"] == "add")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("SELECT id FROM users WHERE id='" . $_POST["advertiser"] . "' AND advertiser='yes'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Paid Clicks Manager", "Advertiser account not found, please check account."));
			
			$db->Query("SELECT id FROM paid_clicks WHERE title='" . $_POST["title"] . "'");
			
			if($db->NumRows() == 1)
				exit($error->Report("Paid Clicks Manager", "There already is a click with the title \"" . $_POST["title"] . "\"."));
			
			$db->Query("INSERT INTO paid_clicks (aid, title, text, banner, url, timer, type, c_type, credits, ref_earnings, active) VALUES ('" . $_POST["advertiser"] . "', '" . $_POST["title"] . "', '" . $_POST["text"] . "', '" . $_POST["banner"] . "', '" . $_POST["url"] . "', '" . $_POST["timer"] . "', '" . $_POST["type"] . "', '" . $_POST["c_type"] . "', '" . $_POST["credits"] . "', '" . $_POST["ref_earnings"] . "', '" . $_POST["active"] . "');");
			
			$main->printText("<B>Paid Clicks Manager</B><BR><BR>Paid Click Added.<BR><BR><A HREF=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=send&type=paidclick&id=" . $db->LastInsertID() . "\">Click here to send this click directly</A><BR><BR>or", 1);
		}
		else
		{
			$order_data	= $db->Fetch("SELECT ad_url, ad_title, ad_text FROM ad_orders WHERE id='" . $_GET["oid"] . "'");
			
			$text		.= "<FORM NAME=\"addclick\" ACTION=\"" . _ADMIN_URL . "/ptc.php?sid=" . $session->ID . "&action=add\" METHOD=\"POST\">\n";
			
			if(_MEMBER_POINTS == "NO")
				$text		.= "<INPUT TYPE=\"hidden\" NAME=\"c_type\" VALUE=\"cash\">";
			
			$text		.= "<SCRIPT LANGUAGE=\"javascript\" TYPE=\"text/javascript\">\n"
						  ."function f_clicktype(option){\n\n"
						  ."        form = document.addclick;\n"
						  ."        if(option == 'text')\n"
						  ."        {\n"
						  ."           T1.style.display = 'none';\n"
						  ."           T2.style.display = 'none';\n"
						  ."        }\n"
						  ."        else\n"
						  ."        if(option == 'banner')\n"
						  ."        {\n"
						  ."           T1.style.display = '';\n"
						  ."           T2.style.display = '';\n"
						  ."        }\n"
						  ."}\n"
						  ."</SCRIPT>\n"
						  ."<TABLE WIDTH=\"100%\">\n"
						  ."<TR><TH COLSPAN=\"2\"><B>Add Paid Click</B></TH></TR>"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD>Title:</TD><TD><INPUT TYPE=\"text\" NAME=\"title\" SIZE=\"30\" VALUE=\"" . $order_data["ad_title"] . "\"></TD></TR>\n"
						  ."<TR><TD>Type:</TD><TD><SELECT NAME=\"type\" SIZE=\"1\" ONCHANGE=\"f_clicktype(this.value)\"><OPTION VALUE=\"banner\">Banner</OPTION><OPTION VALUE=\"text\" selected>Text</OPTION></SELECT></TD></TR>\n"
						  ."<TR><TD><DIV ID=\"T1\" STYLE=\"DISPLAY: none;\">Url to banner:</DIV></TD><TD><DIV ID=\"T2\" STYLE=\"DISPLAY: none;\"><INPUT TYPE=\"text\" NAME=\"banner\" SIZE=\"30\"></DIV></TD></TR>\n"
						  ."<TR><TD>Url to website:</TD><TD><INPUT TYPE=\"text\" NAME=\"url\" SIZE=\"30\" VALUE=\"" . $order_data["ad_url"] . "\"></TD></TR>\n"
						  ."<TR><TD>Credit Type:</TD><TD><SELECT NAME=\"c_type\" SIZE=\"1\"";
			
			if(_MEMBER_POINTS == "NO")
				$text		.= " disabled";
			
			$text		.= "><OPTION VALUE=\"cash\" selected>Cash</OPTION><OPTION VALUE=\"points\">Points</OPTION></SELECT></TD></TR>\n"
						  ."<TR><TD>Credits:</TD><TD><INPUT TYPE=\"text\" NAME=\"credits\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD>Visit Time:</TD><TD><INPUT TYPE=\"text\" NAME=\"timer\" SIZE=\"30\" VALUE=\"" . _MEMBER_CLICKREFRESH . "\"></TD></TR>\n"
						  ."<TR><TD>Referral Earnings:</TD><TD><SELECT NAME=\"ref_earnings\" SIZE=\"1\"><OPTION VALUE=\"yes\" selected>Yes</OPTION><OPTION VALUE=\"no\">No</OPTION></SELECT></TD></TR>\n"
						  ."<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\" selected>Yes</OPTION><OPTION VALUE=\"no\">No</OPTION></SELECT></TD></TR>\n"
						  ."<TR><TD>Text:</TD><TD><TEXTAREA NAME=\"text\" COLS=\"40\" ROWS=\"8\">" . $order_data["ad_text"] . "</TEXTAREA></TD></TR>\n"
						  ."<TR><TD>Advertiser:<BR><FONT SIZE=\"1\">account e-mail</FONT></TD>\n"
						  ."<TD><SELECT NAME=\"advertiser\" SIZE=\"1\">\n";
			
			$db->Query("SELECT id, email FROM users WHERE active='yes' AND advertiser='yes'");
			
			while($row = $db->NextRow())
				$text		.= "<OPTION VALUE=\"" . $row["id"] . "\" " . ($row["id"] == $_GET["aid"] ? "selected" : "") . ">" . $row["email"] . "</OPTION>\n";
			
			$text		.= "</SELECT></TD></TR>\n"
						 ."<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Paid Click\"></TD></TR>\n"
						 ."</TABLE></FORM>";
					
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "edit")
	{
		$db->Query("SELECT id FROM paid_clicks WHERE id='" . $_GET["cid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Paid Clicks Manager", "An error has occured."));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("SELECT id FROM users WHERE id='" . $_POST["advertiser"] . "' AND advertiser='yes'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Paid Clicks Manager", "Advertiser account not found, please check account."));
			
			$db->Query("SELECT id FROM paid_clicks WHERE title='" . $_POST["title"] . "' AND id!='" . $_GET["cid"] . "'");
			
			if($db->NumRows() == 1)
				exit($error->Report("Paid Clicks Manager", "There already is a click with the title \"" . $_POST["title"] . "\"."));
			
			$db->Query("UPDATE paid_clicks SET aid='" . $_POST["advertiser"] . "', title='" . $_POST["title"] . "', banner='" . $_POST["banner"] . "', url='" . $_POST["url"] . "', timer='" . $_POST["timer"] . "', type='" . $_POST["type"] . "', c_type='" . $_POST["c_type"] . "', credits='" . $_POST["credits"] . "', ref_earnings='" . $_POST["ref_earnings"] . "', active='" . $_POST["active"] . "', text='" . $_POST["text"] . "' WHERE id='" . $_GET["cid"] . "'");
			
			$main->printText("<B>Paid Clicks Manager</B><BR><BR>Paid Click Edited.", 1);
		}
		else
		{
			$data	= $main->Trim($db->Fetch("SELECT * FROM paid_clicks WHERE id='" . $_GET["cid"] . "'"));
			
			$text	.= "<FORM NAME=\"changeclick\" ACTION=\""._ADMIN_URL."/ptc.php?sid=" . $session->ID . "&action=edit&cid=" . $_GET["cid"] . "\" METHOD=\"POST\">\n";
			
			if(_MEMBER_POINTS == "NO")
				$text		.= "<INPUT TYPE=\"hidden\" NAME=\"c_type\" VALUE=\"cash\">";
			
			$text		.= "<SCRIPT LANGUAGE=\"javascript\" TYPE=\"text/javascript\">\n"
						  ."function f_clicktype(option){\n\n"
						  ."        form = document.changeclick;\n"
						  ."        if(option == 'text')\n"
						  ."        {\n"
						  ."           T1.style.display = 'none';\n"
						  ."           T2.style.display = 'none';\n"
						  ."        }\n"
						  ."        else\n"
						  ."        if(option == 'banner')\n"
						  ."        {\n"
						  ."           T1.style.display = '';\n"
						  ."           T2.style.display = '';\n"
						  ."        }\n"
						  ."}\n"
						  ."</SCRIPT>\n"
						  ."<TABLE WIDTH=\"100%\">\n"
						  ."<TR><TH COLSPAN=\"2\"><B>Edit Paid Click \"" . $data["title"] . "\"</B></TH></TR>"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD>Title:</TD><TD><INPUT TYPE=\"text\" NAME=\"title\" VALUE=\"" . $data["title"] . "\" SIZE=\"30\"></TD></TR>\n";
			
			if($data["type"] == "banner")
			{
				$text		.="<TR><TD>Type:</TD><TD><SELECT NAME=\"type\" SIZE=\"1\" ONCHANGE=\"f_clicktype(this.value)\"><OPTION VALUE=\"banner\" selected>Banner</OPTION><OPTION VALUE=\"text\">Text</OPTION></SELECT></TD></TR>\n";
			}
			else
			{
				$tmp		= "none";
				
				$text		.="<TR><TD>Type:</TD><TD><SELECT NAME=\"type\" SIZE=\"1\" ONCHANGE=\"f_clicktype(this.value)\"><OPTION VALUE=\"banner\">Banner</OPTION><OPTION VALUE=\"text\" selected>Text</OPTION></SELECT></TD></TR>\n";
			}
			
			$text		.= "<TR><TD><DIV ID=\"T1\" STYLE=\"DISPLAY: $tmp;\">Url to banner:</DIV></TD><TD><DIV ID=\"T2\" STYLE=\"DISPLAY: $tmp;\"><INPUT TYPE=\"text\" NAME=\"banner\" SIZE=\"30\" VALUE=\"" . $data["banner"] . "\"></DIV></TD></TR>\n"
						  ."<TR><TD>Url to website:</TD><TD><INPUT TYPE=\"text\" NAME=\"url\" VALUE=\"" . $data["url"] . "\" SIZE=\"30\"></TD></TR>\n";
			
			if(_MEMBER_POINTS == "NO")
				$value	= " disabled";
			
			if(_MEMBER_POINTS == "NO" || $data["c_type"] == "cash")
				$text		.= "<TR><TD>Credit Type:</TD><TD><SELECT NAME=\"c_type\" SIZE=\"1\"$value><OPTION VALUE=\"cash\" selected>Cash</OPTION><OPTION VALUE=\"points\">Points</OPTION></SELECT></TD></TR>";
			else
				$text		.= "<TR><TD>Credit Type:</TD><TD><SELECT NAME=\"c_type\" SIZE=\"1\"><OPTION VALUE=\"cash\">Cash</OPTION><OPTION VALUE=\"points\" selected>Points</OPTION></SELECT></TD></TR>";
			
			$text		.= "<TR><TD>Credits:</TD><TD><INPUT TYPE=\"text\" NAME=\"credits\" VALUE=\"" . $data["credits"] . "\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD>Visit Time:</TD><TD><INPUT TYPE=\"text\" NAME=\"timer\" VALUE=\"" . $data["timer"] . "\" SIZE=\"30\"></TD></TR>\n";
			
			$text		.= "<TR><TD>Referral Earnings:</TD><TD><SELECT NAME=\"ref_earnings\" SIZE=\"1\"><OPTION VALUE=\"yes\"" . ($data["ref_earnings"] == "yes" ? " selected" : "") . ">Yes</OPTION><OPTION VALUE=\"no\"" . ($data["ref_earnings"] == "no" ? " selected" : "") . ">No</OPTION></SELECT></TD></TR>\n"
						  ."<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\"" . ($data["active"] == "yes" ? " selected" : "") . ">Yes</OPTION><OPTION VALUE=\"no\"" . ($data["active"] == "no" ? " selected" : "") . ">No</OPTION></SELECT></TD></TR>\n"
						  ."<TR><TD>Text:</TD><TD><TEXTAREA NAME=\"text\" COLS=\"40\" ROWS=\"8\">" . $data["text"] . "</TEXTAREA></TD></TR>\n"
						  ."<TR><TD>Advertiser:</TD><TD><SELECT NAME=\"advertiser\" SIZE=\"1\">\n";
			
			$db->Query("SELECT id, email FROM users WHERE active='yes' AND advertiser='yes'");
			
			while($row = $db->NextRow())
			{
				$var		= $row["id"] == $data["aid"] ? "selected" : "";
				
				$text		.= "<OPTION VALUE=\"" . $row["id"] . "\" ${var}>" . $row["email"] . "</OPTION>\n";
			}
			
			$text		.= "</SELECT></TD></TR>\n"
						  ."<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Paid Click\"></TD></TR>\n"
						  ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "queue")
	{
		if($_GET["op"] == "delete")
		{
			$db->Query("SELECT id FROM sent_queue WHERE cid='" . $_GET["cid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Paid Clicks Manager", "This click is not in queue."));
			
			$db->Query("DELETE FROM sent_queue WHERE cid='" . $_GET["cid"] . "'");
			
			$main->printText("<B>Paid Clicks Manager</B><BR><BR>Paid Click deleted from queue.", 1);
		}
		elseif($_GET["op"] == "edit")
		{
			$db->Query("SELECT id FROM sent_queue WHERE cid='" . $_GET["cid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Paid Clicks Manager", "This click is not in queue."));
			
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$db->Query("UPDATE sent_queue SET queue='" . $_POST["queue"] . "' WHERE cid='" . $_GET["cid"] . "'");
				
				$main->printText("<B>Paid Clicks Manager</B><BR><BR>The Paid Click's queue has been edited.", 1);
			}
			else
			{
				$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/ptc.php?sid=" . $session->ID . "&action=queue&op=edit&cid=" . $_GET["cid"] .  "\" METHOD=\"POST\">\n"
						  ."<TABLE WIDTH=\"100%\">\n"
						  ."<TR><TD COLSPAN=\"2\"><B>Edit Paid Click's Queue</B></TD></TR>"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD>Paid Click:</TD><TD>" . $db->Fetch("SELECT title FROM paid_clicks WHERE id='" . $_GET["cid"] . "'") .  "</TD></TR>\n"
						  ."<TR><TD>Clickthru:</TD><TD><INPUT TYPE=\"text\" NAME=\"queue\" SIZE=\"22\" VALUE=\"" . $db->Fetch("SELECT queue FROM sent_queue WHERE cid='" . $_GET["cid"] . "'") . "\"></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Queue\"></TD></TR>\n"
						  ."</TABLE></FORM>";
				
				$main->printText($text);
			}
		}
		elseif($_GET["op"] == "add")
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$db->Query("INSERT INTO sent_queue (cid, queue) VALUES ('" . $_POST["cid"] . "', '" . $_POST["queue"] . "');");
				
				$main->printText("<B>Paid Clicks Manager</B><BR><BR>Paid Click has been added to queue.", 1);
			}
			else
			{
				$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/ptc.php?sid=" . $session->ID . "&action=queue&op=add\" METHOD=\"POST\">\n"
						  ."<TABLE WIDTH=\"100%\">\n"
						  ."<TR><TD COLSPAN=\"2\"><B>Add Paid Click to Queue</B></TD></TR>"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD>Paid Click:</TD><TD><SELECT NAME=\"cid\">\n";
				
				$db->Query("SELECT id, title FROM paid_clicks WHERE active='yes'");
				
				while($row = $db->NextRow())
				{
					$text	.= "<OPTION VALUE=\"" . $row["id"] . "\">" . $row["title"] . "</OPTION>\n";
				}
				
				$text	.= "</SELECT></TD></TR>\n"
						  ."<TR><TD>Clickthru:</TD><TD><INPUT TYPE=\"text\" NAME=\"queue\" SIZE=\"22\"></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add to Queue\"></TD></TR>\n"
						  ."</TABLE></FORM>";
				
				$main->printText($text);
			}
		}
		else
		{
			$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
			
			$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
					 ."<TR COLSPAN=\"3\">&nbsp;</TR>\n"
					 ."<TR COLSPAN=\"3\">This list contains paid clicks that will be automatically sent to new members.</TR>\n"
					 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Paid Click</TD><TD>Clicks in Queue</TD><TD>Action</TD></TR>\n";
			
			$db->Query("SELECT id, cid, queue FROM sent_queue ORDER BY id DESC LIMIT $start, 30");
			
			while($row = $db->NextRow())
			{
				$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
						  ."<TD>" . $db->Fetch("SELECT title FROM paid_clicks WHERE id='" . $row["cid"] . "'", 2) . "</TD><TD>" . $row["queue"] . " clicks</TD>\n"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/ptc.php?sid=" . $session->ID . "&action=queue&op=delete&cid=" . $row["cid"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete from Queue\" BORDER=\"0\"></A> "
						  ."<A HREF=\"" . _ADMIN_URL . "/ptc.php?sid=" . $session->ID . "&action=queue&op=edit&cid=" . $row["cid"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A></TD></TR>\n";
			}
			
			$text	.= "</TABLE><BR>\n";
			
			$db->Query("SELECT id FROM sent_queue");
			
			$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/ptc.php?sid=" . $session->ID . "&action=queue", $db->NumRows(), 30, $start) . "</TD></TR></TABLE>\n";
			$text	.= "<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/ptc.php?sid=" . $session->ID . "&action=queue&op=add\">Add Click to Queue</A></TD></TR></TABLE>\n";
			
			$main->printText($text);
		}
	}
	else
	{
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Title</TD><TD>Timer</TD><TD>Credits</TD><TD>Active</TD><TD>Action</TD></TR>\n";
		
		$db->Query("SELECT id, title, timer, c_type, credits, active FROM paid_clicks ORDER BY id DESC LIMIT $start, 30");
		
		while($row = $db->NextRow())
		{
			$row	= $main->Trim($row);
			
			$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
					  ."<TD>" . $row["title"] . "</TD><TD>" . $row["timer"] . " seconds</TD><TD>" . $row["credits"] . " " . $row["c_type"] . "</TD><TD>" . $row["active"] . "</TD>\n"
					  ."<TD><A HREF=\"" . _ADMIN_URL . "/ptc.php?sid=" . $session->ID . "&action=delete&cid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A> "
					  ."<A HREF=\"" . _ADMIN_URL . "/ptc.php?sid=" . $session->ID . "&action=edit&cid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A> "
					  ."<A HREF=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=send&type=paidclick&id=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/send.gif\" ALT=\"Send\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text	.= "</TABLE><BR>\n";
		
		$db->Query("SELECT id FROM paid_clicks");
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/ptc.php?sid=" . $session->ID, $db->NumRows(), 30, $start) . "</TD></TR></TABLE>\n";
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/ptc.php?sid=" . $session->ID . "&action=add\">Add Paid Click</A></TD></TR></TABLE>\n";
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/ptc.php?sid=" . $session->ID . "&action=queue\">Clicks in Queue</A></TD></TR></TABLE>\n";
		
		$main->printText($text);
	}

?>