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
	
	$tml->RegisterVar("TITLE", "Leads Manager");
	
	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Leads Manager", "You can not access this page."));
	
	if($_GET["action"] == "delete")
	{
		$db->Query("SELECT id FROM leads WHERE id='" . $_GET["lid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Leads Manager", "An error has occured."));
		
		$db->Query("DELETE FROM lead_data WHERE lid='" . $_GET["lid"] . "'");
		$db->Query("DELETE FROM leads WHERE id='" . $_GET["lid"] . "'");
		$db->Query("DELETE FROM actions WHERE type='lead' AND aid='" . $_GET["lid"] . "'");
			
		$main->printText("<B>Leads Manager</B><BR><BR>Lead Deleted.", 1);
	}
	elseif($_GET["action"] == "add")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("SELECT id FROM users WHERE id='" . $_POST["advertiser"] . "' AND advertiser='yes'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Leads Manager", "Advertiser account not found, please check account."));
			
			$db->Query("INSERT INTO leads (aid, name, description, description2, html, url, max, type, c_type, credits, conf_mail, active) VALUES ('" . $_POST["advertiser"] . "', '" . $_POST["name"] . "', '" . $_POST["description"] . "', '" . $_POST["description2"] . "', '" . $_POST["html"] . "', '" . $_POST["url"] . "', '" . $_POST["max"] . "', '" . $_POST["type"] . "', '" . $_POST["c_type"] . "', '" . $_POST["credits"] . "', '" . $_POST["conf_mail"] . "', '" . $_POST["active"] . "');");
			
			$main->printText("<B>Leads Manager</B><BR><BR>Lead Added.", 1);
		}
		else
		{
			$order_data	= $db->Fetch("SELECT ad_url, ad_title, ad_text FROM ad_orders WHERE id='" . $_GET["oid"] . "'");
			
			$text		= "<FORM NAME=\"addlead\" ACTION=\"" . _ADMIN_URL . "/leads.php?sid=" . $session->ID . "&action=add\" METHOD=\"POST\">\n";
			
			if(_MEMBER_POINTS == "NO")
				$text		.= "<INPUT TYPE=\"hidden\" NAME=\"c_type\" VALUE=\"cash\">";
			
			$text		.= "<SCRIPT LANGUAGE=\"javascript\" TYPE=\"text/javascript\">\n"
						  ."function f_leadtype(option){\n\n"
						  ."        form = document.addlead;\n"
						  ."        if(option == 'url')\n"
						  ."        {\n"
						  ."           T1.style.display = 'none';\n"
						  ."           T2.style.display = 'none';\n"
						  ."           T3.style.display = '';\n"
						  ."           T4.style.display = '';\n"
						  ."        }\n"
						  ."        else\n"
						  ."        if(option == 'form')\n"
						  ."        {\n"
						  ."           T1.style.display = '';\n"
						  ."           T2.style.display = '';\n"
						  ."           T3.style.display = 'none';\n"
						  ."           T4.style.display = 'none';\n"
						  ."        }\n"
						  ."}\n"
						  ."</SCRIPT>\n"
						  ."<TABLE WIDTH=\"100%\">\n"
						  ."<TR><TD WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\"><B>Add Lead</B></TD></TR>"
						  ."<TR><TD WIDTH=\"100%\" COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD WIDTH=\"50%\">Name:</TD><TD><INPUT TYPE=\"text\" NAME=\"name\" SIZE=\"30\" VALUE=\"" . $order_data["ad_title"] . "\"></TD></TR>\n"
						  ."<TR><TD WIDTH=\"50%\">Maximum Submissions:</TD><TD><INPUT TYPE=\"text\" NAME=\"max\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD WIDTH=\"50%\">Type:</TD><TD><SELECT NAME=\"type\" SIZE=\"1\" ONCHANGE=\"f_leadtype(this.value)\"><OPTION VALUE=\"form\" selected>Form</OPTION><OPTION VALUE=\"url\">URL</OPTION></SELECT></TD></TR>\n"
						  ."<TR><TD WIDTH=\"50%\">Credit Type:</TD><TD><SELECT NAME=\"c_type\" SIZE=\"1\"";
			
			if(_MEMBER_POINTS == "NO")
				$text		.= " disabled";
			
			$text		.= "><OPTION VALUE=\"cash\" selected>Cash</OPTION><OPTION VALUE=\"points\">Points</OPTION></SELECT></TD></TR>\n"
						  ."<TR><TD WIDTH=\"50%\">Credits:</TD><TD><INPUT TYPE=\"text\" NAME=\"credits\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD WIDTH=\"50%\">Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\" selected>Yes</OPTION><OPTION VALUE=\"no\">No</OPTION></SELECT></TD></TR>\n"
						  ."<TR><TD WIDTH=\"50%\">Description:<BR><FONT SIZE=\"1\">Above subscribe form</FONT></TD><TD><TEXTAREA NAME=\"description\" COLS=\"40\" ROWS=\"8\">" . htmlentities($order_data["ad_text"]) . "</TEXTAREA></TD></TR>\n"
						  ."<TR><TD WIDTH=\"50%\">Description:<BR><FONT SIZE=\"1\">On " . _SITE_TITLE . " for your members</FONT></TD><TD><TEXTAREA NAME=\"description2\" COLS=\"40\" ROWS=\"8\"></TEXTAREA></TD></TR>\n"
						  ."<TR><TD WIDTH=\"50%\"><DIV ID=\"T1\" STYLE=\"DISPLAY: \">\n"
						  ."HTML-code (form):</DIV></TD><TD><DIV ID=\"T2\" STYLE=\"DISPLAY: \"><TEXTAREA NAME=\"html\" COLS=\"40\" ROWS=\"8\"><INPUT TYPE=\"text\" NAME=\"name\"><BR>\n"
						  ."<INPUT TYPE=\"text\" NAME=\"email\"><BR><BR>\n<INPUT TYPE=\"submit\" VALUE=\"Submit\"></TEXTAREA></DIV></TD></TR>\n"
						  ."<TR><TD WIDTH=\"50%\"><DIV ID=\"T3\" STYLE=\"DISPLAY: none\">URL:<BR><FONT SIZE=\"1\">#EMAIL = users email address<BR>#UID = users id</FONT></DIV></TD><TD>\n"
						  ."<DIV ID=\"T4\" STYLE=\"DISPLAY: none\"><INPUT TYPE=\"text\" NAME=\"url\" VALUE=\"" . $order_data["ad_url"] . "\" SIZE=\"30\"></DIV></TD></TR>\n"
						  ."<TR><TD WIDTH=\"50%\">Confirmation E-Mail:</TD><TD><SELECT NAME=\"conf_mail\" SIZE=\"1\"><OPTION VALUE=\"no\">No</OPTION><OPTION VALUE=\"yes\" selected>Yes</OPTION></SELECT></TD></TR>\n"
						  ."<TR><TD WIDTH=\"50%\">Advertiser:<BR><FONT SIZE=\"1\">account e-mail</FONT></TD>\n"
						  ."<TD><SELECT NAME=\"advertiser\" SIZE=\"1\">\n";
			
			$db->Query("SELECT id, email FROM users WHERE active='yes' AND advertiser='yes'");
			
			while($row = $db->NextRow())
				$text		.= "<OPTION VALUE=\"" . $row["id"] . "\" " . ($row["id"] == $_GET["aid"] ? "selected" : "") . ">" . $row["email"] . "</OPTION>\n";
			
			$text		.= "</SELECT></TD></TR>\n"
						 ."<TR><TD WIDTH=\"50%\"></TD><TD><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Lead\"></TD></TR>\n"
						 ."</TABLE></FORM>";
					
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "edit")
	{
		$db->Query("SELECT id FROM leads WHERE id='" . $_GET["lid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Leads Manager", "An error has occured."));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("SELECT id FROM users WHERE id='" . $_POST["advertiser"] . "' AND advertiser='yes'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Leads Manager", "Advertiser account not found, please check account."));
			
			$db->Query("UPDATE leads SET aid='" . $_POST["advertiser"] . "', name='" . $_POST["name"] . "', description='" . $_POST["description"] . "', description2='" . $_POST["description2"] . "', html='" . $_POST["html"] . "', url='" . $_POST["url"] . "', max='" . $_POST["max"] . "', type='" . $_POST["type"] . "', c_type='" . $_POST["c_type"] . "', credits='" . $_POST["credits"] . "', active='" . $_POST["active"] . "', conf_mail='" . $_POST["conf_mail"] . "' WHERE id='" . $_GET["lid"] . "'");
			
			$main->printText("<B>Leads Manager</B><BR><BR>Lead Edited.", 1);
		}
		else
		{
			$data	= $main->Trim($db->Fetch("SELECT * FROM leads WHERE id='" . $_GET["lid"] . "'"));
			
			$text	= "<FORM NAME=\"editlead\" ACTION=\"" . _ADMIN_URL . "/leads.php?sid=" . $session->ID . "&action=edit&lid=" . $_GET["lid"] . "\" METHOD=\"POST\">\n";
			
			if(_MEMBER_POINTS == "NO")
				$text	.= "<INPUT TYPE=\"hidden\" NAME=\"c_type\" VALUE=\"cash\">";
			
			$text	.= "<SCRIPT LANGUAGE=\"javascript\" TYPE=\"text/javascript\">\n"
					  ."function f_leadtype(option){\n\n"
					  ."        form = document.editlead;\n"
					  ."        if(option == 'url')\n"
					  ."        {\n"
					  ."           T1.style.display = 'none';\n"
					  ."           T2.style.display = 'none';\n"
					  ."           T3.style.display = '';\n"
					  ."           T4.style.display = '';\n"
					  ."        }\n"
					  ."        else\n"
					  ."        if(option == 'form')\n"
					  ."        {\n"
					  ."           T1.style.display = '';\n"
					  ."           T2.style.display = '';\n"
					  ."           T3.style.display = 'none';\n"
					  ."           T4.style.display = 'none';\n"
					  ."        }\n"
					  ."}\n"
					  ."</SCRIPT>\n"
					  ."<TABLE WIDTH=\"100%\">\n"
					  ."<TR><TD WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\"><B>Edit Lead \"" . $data["name"] . "\"</B></TD></TR>"
					  ."<TR><TD WIDTH=\"100%\" COLSPAN=\"2\">&nbsp;</TD></TR>"
					  ."<TR><TD WIDTH=\"50%\">Name:</TD><TD><INPUT TYPE=\"text\" NAME=\"name\" VALUE=\"" . $data["name"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD WIDTH=\"50%\">Maximum Submissions:</TD><TD><INPUT TYPE=\"text\" NAME=\"max\" VALUE=\"" . $data["max"] . "\" SIZE=\"30\"></TD></TR>\n";
			
			if(_MEMBER_POINTS == "NO")
				$value	= " disabled";
			
			if(_MEMBER_POINTS == "NO" || $data["c_type"] == "cash")
				$text	.= "<TR><TD WIDTH=\"50%\">Credit Type:</TD><TD><SELECT NAME=\"c_type\" SIZE=\"1\"$value><OPTION VALUE=\"cash\" selected>Cash</OPTION><OPTION VALUE=\"points\">Points</OPTION></SELECT></TD></TR>\n";
			else
				$text	.= "<TR><TD WIDTH=\"50%\">Credit Type:</TD><TD><SELECT NAME=\"c_type\" SIZE=\"1\"><OPTION VALUE=\"cash\">Cash</OPTION><OPTION VALUE=\"points\" selected>Points</OPTION></SELECT></TD></TR>\n";
			
			$text	.= "<TR><TD WIDTH=\"50%\">Credits:</TD><TD><INPUT TYPE=\"text\" NAME=\"credits\" VALUE=\"" . $data["credits"] . "\" SIZE=\"30\"></TD></TR>\n";
						 
			if($data["type"] == "form")
			{
				$tmp2	= "none";
				
				$text	.= "<TR><TD WIDTH=\"50%\">Type:</TD><TD><SELECT NAME=\"type\" SIZE=\"1\" ONCHANGE=\"f_leadtype(this.value)\"><OPTION VALUE=\"form\" selected>Form</OPTION><OPTION VALUE=\"url\">URL</OPTION></SELECT></TD></TR>\n";
			}
			else
			{
				$tmp1	= "none";
				
				$text	.= "<TR><TD WIDTH=\"50%\">Type:</TD><TD><SELECT NAME=\"type\" SIZE=\"1\" ONCHANGE=\"f_leadtype(this.value)\"><OPTION VALUE=\"form\">Form</OPTION><OPTION VALUE=\"url\" selected>URL</OPTION></SELECT></TD></TR>\n";
			}

			if($data["active"] == "yes")
				$text	.= "<TR><TD WIDTH=\"50%\">Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\" selected>Yes</OPTION><OPTION VALUE=\"no\">No</OPTION></SELECT></TD></TR>\n";
			else
				$text	.= "<TR><TD WIDTH=\"50%\">Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\">Yes</OPTION><OPTION VALUE=\"no\" selected>No</OPTION></SELECT></TD></TR>\n";
						 
			$text	.= "<TR><TD WIDTH=\"50%\">Description:<BR><FONT SIZE=\"1\">Above subscribe form</FONT></TD><TD><TEXTAREA NAME=\"description\" COLS=\"40\" ROWS=\"8\">" . htmlentities($data["description"]) . "</TEXTAREA></TD></TR>\n"
					  ."<TR><TD WIDTH=\"50%\">Description:<BR><FONT SIZE=\"1\">On " . _SITE_TITLE . " for your members</FONT></TD><TD><TEXTAREA NAME=\"description2\" COLS=\"40\" ROWS=\"8\">" . htmlentities($data["description2"]) . "</TEXTAREA></TD></TR>\n"
					  ."<TR><TD WIDTH=\"50%\"><DIV ID=\"T1\" STYLE=\"DISPLAY: $tmp1\">HTML-code (form):</TD><TD><DIV ID=\"T2\" STYLE=\"DISPLAY: $tmp1\"><TEXTAREA NAME=\"html\" COLS=\"40\" ROWS=\"8\">" . htmlentities($data["html"]) . "</TEXTAREA></TD></TR>\n"
					  ."<TR><TD WIDTH=\"50%\"><DIV ID=\"T3\" STYLE=\"DISPLAY: $tmp2\">URL:<BR><FONT SIZE=\"1\">#EMAIL = users email address<BR>#UID = users id</FONT></TD><TD><DIV ID=\"T4\" STYLE=\"DISPLAY: $tmp2\"><INPUT TYPE=\"text\" NAME=\"url\" VALUE=\"" . $data["url"] . "\" SIZE=\"30\"></TD></TR>\n";
			
			if($data["conf_mail"] == "yes")
				$text	.= "<TR><TD WIDTH=\"50%\">Confirmation E-Mail:</TD><TD><SELECT NAME=\"conf_mail\" SIZE=\"1\"><OPTION VALUE=\"no\">No</OPTION><OPTION VALUE=\"yes\" selected>Yes</OPTION></SELECT></TD></TR>\n";
			else
				$text	.= "<TR><TD WIDTH=\"50%\">Confirmation E-Mail:</TD><TD><SELECT NAME=\"conf_mail\" SIZE=\"1\"><OPTION VALUE=\"no\" selected>No</OPTION><OPTION VALUE=\"yes\">Yes</OPTION></SELECT></TD></TR>\n";
			
			$text	.= "<TR><TD>Advertiser:</TD><TD><SELECT NAME=\"advertiser\" SIZE=\"1\">\n";
			
			$db->Query("SELECT id, email FROM users WHERE active='yes' AND advertiser='yes'");
			
			while($row = $db->NextRow())
			{
				$text	.= "<OPTION VALUE=\"" . $row["id"] . "\"" . ($row["id"] == $data["aid"] ? " selected" : "") . ">" . $row["email"] . "</OPTION>\n";
			}
			
			$text	.= "</SELECT></TD></TR>\n"
					  ."<TR><TD WIDTH=\"50%\"></TD><TD><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Lead\"></TD></TR>\n"
					  ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "open")
	{
		if(is_numeric($_GET["credit"]) && $_GET["credit"] != 0)
		{
			$db->Query("SELECT id FROM lead_data WHERE id='" . $_GET["credit"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Leads Manager", "The affliate doesn't exists."));
			
			$submissiondata	= $db->Fetch("SELECT * FROM lead_data WHERE id='" . $_GET["credit"] . "'");
			
			$db->Query("SELECT id FROM users WHERE id='" . $submissiondata["uid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Leads Manager", "An error has occured."));
			
			$leaddata	= $db->Fetch("SELECT * FROM leads WHERE id='" . $submissiondata["lid"] . "'");
			
			$field	= $leaddata["c_type"] == "points" ? "points" : "leads_sales";
			
			$db->Query("UPDATE users SET $field=$field+'" . $leaddata["credits"] . "' WHERE id='" . $submissiondata["uid"] . "'");
			$db->Query("UPDATE lead_data SET status='checked' WHERE id='" . $_GET["credit"] . "'");
			
			if(_REFERRAL_TYPE == "PERCENTAGE")
				$referrals->AddCreditsToUplines($submissiondata["uid"], $leaddata["credits"], $leaddata["c_type"]);
			
			$user->Add2Actions($submissiondata["uid"], $submissiondata["lid"], "lead", $leaddata["credits"]);
			
			$main->printText("<B>Leads Manager</B><BR><BR>Affliate succesfully credited.", 1);
		}
		elseif(is_numeric($_GET["delete"]) && $_GET["delete"] != 0)
		{
			$db->Query("SELECT id FROM lead_data WHERE id='" . $_GET["delete"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Leads Manager","An error has occured."));
			
			$db->Query("DELETE FROM lead_data WHERE id='" . $_GET["delete"] . "'");
			
			$main->printText("<B>Leads Manager</B><BR><BR>Submission succesfully deleted.", 1);
		}
		else
		{
			$db->Query("SELECT id FROM leads WHERE id='" . $_GET["lid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Leads Manager", "An error has occured."));
			
			$data	= $db->Fetch("SELECT * FROM leads WHERE id='" . $_GET["lid"] . "'");
			
			if($data["type"] == "url")
				exit($error->Report("Leads Manager", "The lead type is 'url', no data is stored."));
			
			$text	= "<TABLE WIDTH=\"100%\"><TR><TD><B>Exploring lead <U>" . $data["name"] . "</U></B></TD></TR></TABLE><BR><BR>"
					 ."<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
					 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Affliate</TD><TD>Formdata</TD><TD>Confirmed</TD><TD>Date</TD><TD>Action</TD></TR>\n";
			
			$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
			
			$db->Query("SELECT id, uid, formdata, dateStamp, active FROM lead_data WHERE lid='" . $_GET["lid"] . "' AND active='yes' AND status='unchecked' ORDER BY id LIMIT $start, 30");
			
			while($row = $db->NextRow())
			{
				$email	= $db->Fetch("SELECT email FROM users WHERE id='" . $row["uid"] . "'", 2);
				$array	= unserialize($row["formdata"]);
				$email	= $email == "" ? "Unknown" : $email;
				
				foreach($array AS $field => $value)
				{
					if($field != "PHPSESSID")
					{
						$formdata	.= "<B>'$field'</B>: $value<BR>\n";
					}
				}
				
				$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
						  ."<TD VALIGN=\"top\">$email</TD><TD VALIGN=\"top\">" . $formdata . "</TD><TD VALIGN=\"top\">" . $row["active"] . "</TD><TD VALIGN=\"top\">" . date(_SITE_DATESTAMP . " h:i", $row["dateStamp"]) . "</TD>\n"
						  ."<TD VALIGN=\"top\"><A HREF=\"" . _ADMIN_URL . "/leads.php?sid=" . $session->ID . "&action=open&credit=" . $row["id"] . "\">Credit</A><BR>"
						  ."<A HREF=\"" . _ADMIN_URL . "/leads.php?sid=" . $session->ID . "&action=open&delete=" . $row["id"] . "\">Delete</A></TD></TR>\n";
			}
			
			$text	.= "</TABLE><BR>\n";
			
			$db->Query("SELECT id FROM lead_data WHERE lid='" . $_GET["lid"] . "' AND active='yes' AND status='unchecked'");
			
			$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/leads.php?sid=" . $session->ID . "&action=open&lid=" . $_GET["lid"], $db->NumRows(), 30, $start) . "</TD></TR></TABLE>\n";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "export")
	{
		$db->Query("SELECT id FROM leads WHERE id='" . $_GET["lid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Leads Manager", "An error has occured."));
		
		$leaddata	= $db->Fetch("SELECT * FROM leads WHERE id='" . $_GET["lid"] . "'");
		
		if($leaddata["type"] == "url")
			exit($error->Report("Leads Manager", "The lead type is 'url', no data is stored."));
		
		$db->Query("SELECT id FROM lead_data WHERE lid='" . $_GET["lid"] . "' AND active='yes' AND status='checked'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Leads Manager", "There has to be at least one (checked) submission."));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(!isset($_POST["fields"]) || !isset($_POST["filetype"]))
				exit($error->Report("Leads Manager", "You have to fill out the form."));
			
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
			
			$content	= "";
			$fields		= $_POST["fields"];
			$total		= count($fields);
			$i			= 1;
			
			foreach($fields AS $field => $value)
			{
				$content	.= "$field";
				
				if($content && $total != $i)
					$content	.= $sep1;
				
				$i++;
			}
			
			$content	.= "\r\n";
			
			$db->Query("SELECT formdata, remote_addr, dateStamp FROM lead_data WHERE lid='" . $_GET["lid"] . "' AND active='yes' AND status='checked'");
			
			while($row = $db->NextRow())
			{
				$data		= unserialize($row["formdata"]);
				
				$j			= 1;
				
				foreach($data AS $name => $value)
				{
					if($fields[$name])
					{
						$content	.= "\"$value\"";
						
						if($content && $total != $j)
							$content	.= $sep2;
						
						$j++;
					}
				}
				
				$content	.= "\r\n";
			}
			
			echo $content;
		}
		else
		{
			$text		= "<FORM ACTION=\"" . _ADMIN_URL . "/leads.php?sid=" . $session->ID . "&action=export&lid=" . $_GET["lid"] . "\" METHOD=\"post\">\n"
						 ."<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD ALIGN=\"center\"><B>Export Lead \"" . $leaddata["name"] . "\"</B></TD></TR>"
						 ."<TR><TD>&nbsp;</TD></TR>"
						 ."<TR><TD>Check the boxes next to the fields you want to download. All checked fields will be included in your downloadable log.</TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>"
						 ."<TR><TD><B>Form Fields</B></TD></TR>";
			
			$formdata	= $db->Fetch("SELECT formdata FROM lead_data WHERE lid='" . $_GET["lid"] . "' AND active='yes' AND status='checked'");
			
			$data		= unserialize($formdata);
			
			foreach($data AS $name => $value)
			{
				if($name != "PHPSESSID")
				{
					$text		.= "<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[$name]\"> $name</TD></TR>\n";
				}
			}
			
			$text		.= "<TR><TD>&nbsp;</TD></TR>\n"
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
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Name</TD><TD>Max Submissions</TD><TD>Type</TD><TD>Credits</TD><TD>Active</TD><TD>Action</TD></TR>\n";

		$db->Query("SELECT id, name, max, type, c_type, credits, active FROM leads ORDER BY name LIMIT $start, 30");
		
		while($row = $db->NextRow())
		{
			$row	= $main->Trim($row);
			
			$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
					  ."<TD><A HREF=\"" . _ADMIN_URL . "/leads.php?sid=" . $session->ID . "&action=open&lid=" . $row["id"] . "\">" . $row["name"] . "</A></TD><TD>" . $row["max"] . "</TD><TD>" . $row["type"] . "</TD>"
					  ."<TD>" . $row["credits"] . " " . $row["c_type"] . "</TD><TD>" . $row["active"] . "</TD>\n"
					  ."<TD><A HREF=\"" . _ADMIN_URL . "/leads.php?sid=" . $session->ID . "&action=delete&lid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A> "
					  ."<A HREF=\"" . _ADMIN_URL . "/leads.php?sid=" . $session->ID . "&action=edit&lid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A>&nbsp;\n"
					  ."<A HREF=\"" . _ADMIN_URL . "/leads.php?sid=" . $session->ID . "&action=export&lid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/excel.gif\" ALT=\"Export\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text	.= "</TABLE><BR>\n";

		$db->Query("SELECT id FROM leads");
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/leads.php?sid=" . $session->ID, $db->NumRows(), 30, $start) . "</TD></TR></TABLE>\n"
				  ."<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/leads.php?sid=" . $session->ID . "&action=add\">Add Lead</A></TD></TR></TABLE>\n";
		
		$main->printText($text);
	}

?>