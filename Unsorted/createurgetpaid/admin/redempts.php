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
	
	$tml->RegisterVar("TITLE", "Redemption Items");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Redemption Items", "You can not access this page."));
	
	if($_GET["action"] == "delete")
	{
		$db->Query("SELECT id FROM redempts WHERE id='" . $_GET["rid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Redemption Items", "An error has occured."));
		
		$db->Query("DELETE FROM redempts WHERE id='" . $_GET["rid"] . "'");
			
		$main->printText("<B>Redemption Items</B><BR><BR>Redemption Item Deleted.", 1);
	}
	elseif($_GET["action"] == "add")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(!is_array($_POST["weights"]))
				exit($error->Report("Redemption Items", "You have to select at least one weight."));
			
			$db->Query("INSERT INTO redempts (item, c_type, credits, description, weights, type) VALUES ('" . $_POST["item"] . "', '" . $_POST["c_type"] . "', '" . $_POST["credits"] . "', '" . $_POST["description"] . "', '" . serialize($_POST["weights"]) . "', '" . $_POST["type"] . "');");
			
			$main->printText("<B>Redemption Items</B><BR><BR>Redemption Item Added.", 1);
		}
		else
		{
			$types	= Array("ads", "emails", "clicks", "signups", "leads", "sales", "other");
			
			$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/redempts.php?sid=" . $session->ID . "&action=add\" METHOD=\"POST\">\n";
			
			if(_MEMBER_POINTS == "NO")
				$text	.= "<INPUT TYPE=\"hidden\" NAME=\"c_type\" VALUE=\"cash\">";
			
			$text	.= "<TABLE WIDTH=\"100%\">\n"
					  ."<TR><TD COLSPAN=\"2\"><B>Add Redemption Item</B></TD></TR>"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					  ."<TR><TD>Item:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"item\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Credit Type:</TD><TD ALIGN=\"right\"><SELECT NAME=\"c_type\" SIZE=\"1\"";
			
			if(_MEMBER_POINTS == "NO")
				$text	.= " disabled";
			
			$text	.= "><OPTION VALUE=\"cash\" selected>Cash</OPTION><OPTION VALUE=\"points\">Points</OPTION></SELECT></TD></TR>\n"
					  ."<TR><TD>Credits:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"credits\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Description:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"description\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Type</TD><TD ALIGN=\"right\"><SELECT NAME=\"type\" SIZE=\"1\">";
			
			foreach($types AS $type)
			{
				$text	.= "<OPTION VALUE=\"$type\">$type</OPTION>\n";
			}
			
			$text	.= "</SELECT></TD></TR>\n"
					  ."<TR><TD>Visible on Weight(s):<BR><FONT SIZE=\"1\">make option available for<BR>premium members or<BR>normal members</TD>\n"
					  ."<TD ALIGN=\"right\"><B>Non-premium members</B> <INPUT TYPE=\"checkbox\" NAME=\"weights[]\" VALUE=\"0\" CLASS=\"radio\" CHECKED><BR>";
			
			$db->Query("SELECT id, title FROM memberships ORDER BY weight ASC");
			
			while($row = $db->NextRow())
			{
				$text	.= "<B>" . $row["title"] . "</B> <INPUT TYPE=\"checkbox\" NAME=\"weights[]\" VALUE=\"" . $row["id"] . "\" CLASS=\"radio\" CHECKED><BR>";
			}
			
			$text	.= "</TD></TR><TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Redemption Item\"></TD></TR>\n"
					  ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "edit")
	{
		$db->Query("SELECT id FROM redempts WHERE id='" . $_GET["rid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Redemption Items","An error has occured."));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(!is_array($_POST["weights"]))
				exit($error->Report("Redemption Items", "You have to select at least one weight."));
			
			$db->Query("UPDATE redempts SET item='" . $_POST["item"] . "', c_type='" . $_POST["c_type"] . "', credits='" . $_POST["credits"] . "', description='" . $_POST["description"] . "', weights='" . serialize($_POST["weights"]) . "', type='" . $_POST["type"] . "' WHERE id='" . $_GET["rid"] . "'");
			
			$main->printText("<B>Redemption Items</B><BR><BR>Redemption Item Edited.", 1);
		}
		else
		{
			$data	= $main->Trim($db->Fetch("SELECT * FROM redempts WHERE id='" . $_GET["rid"] . "'"));
			$types	= Array("ads", "emails", "clicks", "signups", "leads", "sales", "other");
			
			$weight_array	= unserialize($data["weights"]);
			
			$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/redempts.php?sid=" . $session->ID . "&action=edit&rid=" . $_GET["rid"] . "\" METHOD=\"POST\">\n";
		
			if(_MEMBER_POINTS == "NO")
				$text	.= "<INPUT TYPE=\"hidden\" NAME=\"c_type\" VALUE=\"cash\">";
			
			$text	.= "<TABLE WIDTH=\"100%\">\n"
					  ."<TR><TD COLSPAN=\"2\"><B>Edit Redemption Item \"" . $data["item"] . "\"</B></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					  ."<TR><TD>Item:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"item\" VALUE=\"" . $data["item"] . "\" SIZE=\"30\"></TD></TR>\n";
			
			if(_MEMBER_POINTS == "NO")
				$value	= " disabled";
			
			if(_MEMBER_POINTS == "NO" || $data["c_type"] == "cash")
				$text	.= "<TR><TD>Credit Type:</TD><TD ALIGN=\"right\"><SELECT NAME=\"c_type\" SIZE=\"1\"$value><OPTION VALUE=\"cash\" selected>Cash</OPTION><OPTION VALUE=\"points\">Points</OPTION></SELECT></TD></TR>";
			else
				$text	.= "<TR><TD>Credit Type:</TD><TD ALIGN=\"right\"><SELECT NAME=\"c_type\" SIZE=\"1\"><OPTION VALUE=\"cash\">Cash</OPTION><OPTION VALUE=\"points\" selected>Points</OPTION></SELECT></TD></TR>";
			
			$text	.= "<TR><TD>Credits:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"credits\" VALUE=\"" . $data["credits"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Type</TD><TD ALIGN=\"right\"><SELECT NAME=\"type\" SIZE=\"1\">";
			
			foreach($types AS $type)
			{
				$text	.= "<OPTION VALUE=\"$type\"" . ($type == $data["type"] ? "selected" : "") . ">$type</OPTION>\n";
			}
			
			$text	.= "</SELECT></TD></TR>\n"
					  ."<TR><TD>Description:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"description\" VALUE=\"" . $data["description"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Visible on Weight(s):<BR><FONT SIZE=\"1\">make option available for<BR>premium members or<BR>normal members</TD>\n"
					  ."<TD ALIGN=\"right\"><B>Non-premium members</B> <INPUT TYPE=\"checkbox\" NAME=\"weights[]\" VALUE=\"0\" CLASS=\"radio\"" . (in_array(0, $weight_array) ? " CHECKED" : "") . "><BR>";
			
			$db->Query("SELECT id, title FROM memberships ORDER BY weight ASC");
			
			while($row = $db->NextRow())
			{
				$text	.= "<B>" . $row["title"] . "</B> <INPUT TYPE=\"checkbox\" NAME=\"weights[]\" VALUE=\"" . $row["id"] . "\" CLASS=\"radio\"" . (in_array($row["id"], $weight_array) ? " CHECKED" : "") . "><BR>";
			}
			
			$text		.= "</TD></TR><TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Redemption Item\"></TD></TR>\n"
						  ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	else
	{
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	.= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Item</TD><TD>Credits</TD><TD>Type</TD><TD>Action</TD></TR>\n";

		$db->Query("SELECT id, item, c_type, credits, type FROM redempts ORDER BY item LIMIT $start, 30");
		
		while($row = $db->NextRow())
		{
			$row	= $main->Trim($row);
			
			$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD>" . $row["item"] . "</TD><TD>" . $row["credits"] . " " . $row["c_type"] . "</TD><TD>" . $row["type"] . "</TD>"
					  ."<TD><A HREF=\"" . _ADMIN_URL . "/redempts.php?sid=" . $session->ID . "&action=delete&rid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A> "
					  ."<A HREF=\"" . _ADMIN_URL . "/redempts.php?sid=" . $session->ID . "&action=edit&rid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text	.= "</TABLE><BR>\n";

		$db->Query("SELECT id FROM redempts");
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/redempts.php?sid=" . $session->ID, $db->NumRows(), 30, $start) . "</TD></TR></TABLE>"
				  ."<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/redempts.php?sid=" . $session->ID . "&action=add\">Add Redemption Item</A></TD></TR></TABLE>\n";
		
		$main->printText($text);
	}

?>