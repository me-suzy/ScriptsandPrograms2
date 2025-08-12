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
	
	$tml->RegisterVar("TITLE", "Send");
	
	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Send", "You can not access this page."));
	
	function GetMMForm($num_mails)
	{
		$lastid	= $GLOBALS["db"]->Fetch("SELECT id FROM massmailer WHERE mid='" . $_GET["id"] . "' ORDER BY id LIMIT 1");
		
		$text	= "<SCRIPT LANGUAGE=\"JavaScript\">\n"
				 ."<!--\nfunction submitOnlyOnce(theform){\nif (document.all||document.getElementById) {\n"
				 ."  for (i=0;i<theform.length;i++){\n    var tempobj=theform.elements[i]\n"
				 ."    if(tempobj.type.toLowerCase()==\"submit\"||tempobj.type.toLowerCase()==\"reset\")\n"
				 ."      tempobj.disabled=true\n    }\n  }\n}\n// -->\n</SCRIPT>\n"
				 ."<FORM ACTION=\"" . _ADMIN_URL . "/send.php\" METHOD=\"get\" ONSUBMIT=\"submitOnlyOnce(this)\">\n"
				 ."<INPUT TYPE=\"hidden\" NAME=\"sid\" VALUE=\"" . $_GET["sid"] . "\">\n"
				 ."<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"massmailer\">\n"
				 ."<INPUT TYPE=\"hidden\" NAME=\"mid\" VALUE=\"" . $_GET["id"] . "\">\n"
				 ."<INPUT TYPE=\"hidden\" NAME=\"start\" VALUE=\"$lastid\">\n"
				 ."<TABLE WIDTH=\"100%\">\n"
				 ."<TR><TD COLSPAN=\"2\"><B>Mass Mailer</B></TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\">$num_mails e-mails have been placed in the queuelist. Please select your mailing options:</TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\">Send <INPUT TYPE=\"text\" NAME=\"session\" VALUE=\"250\" SIZE=\"5\" MAXLENGTH=\"4\"> e-mails at a time</TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" VALUE=\"Start the mailing!\"></TD></TR>\n"
				 ."</TABLE>\n"
				 ."</FORM>\n";
		
		return $text;
	}
	
	if($_GET["action"] == "queue")
	{
		$text	.= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Paid E-Mail</TD><TD>E-Mails</TD><TD>Action</TD></TR>\n";

		$db->Query("SELECT DISTINCT mid FROM massmailer ORDER BY id");
		
		while($row = $db->NextRow())
		{
			$subject	= $db->Fetch("SELECT subject FROM paid_emails WHERE id='" . $row["mid"] . "'", 2);
			$lastid		= $db->Fetch("SELECT id FROM massmailer WHERE mid='" . $row["mid"] . "' ORDER BY id LIMIT 1", 2);
			
			$db->Query("SELECT id FROM massmailer WHERE mid='" . $row["mid"] . "'", 2);
			
			$text		.= "<TR BGCOLOR=\"#EAEAEA\"><TD>$subject</TD><TD>" . $db->NumRows(2) . "</TD>";
			$text		.= "<TD>" . (_EMAIL_BACKGROUND == "NO" ? "<A HREF=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=massmailer&mid=" . $row["mid"] . "&start=$lastid&session=200\">" : "") . "<IMG SRC=\"" . _SITE_URL . "/inc/img/send.gif\" ALT=\"Send\" BORDER=\"0\">" . (_EMAIL_BACKGROUND == "NO" ? "</A>" : "");
			$text		.= "\n<A HREF=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=delete&mid=" . $row["mid"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete from Queue\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text	.= "</TABLE>\n";
	
		$main->printText($text);
	}
	elseif($_GET["action"] == "delete")
	{
		$db->Query("SELECT id, turingnr FROM massmailer WHERE mid='" . $_GET["mid"] . "'");
		
		while($row = $db->NextRow())
		{
			$db->Query("DELETE FROM sent_emails WHERE id='" . $row["turingnr"] . "'", 2);
			$db->Query("DELETE FROM massmailer WHERE id='" . $row["id"] . "'", 2);
		}
		
		$main->printText("<B>Mass Mailer</B><BR><BR>The mailing has been deleted from the queuelist.");
	}
	elseif($_GET["action"] == "massmailer")
	{
		if(!is_numeric($_GET["mid"]) || !is_numeric($_GET["start"]))
			exit($error->Report("Send", "The mail id or start record is incorrect."));
		
		$mail	= $main->Trim($db->Fetch("SELECT subject, url, text, texttype, type, priority, c_type, credits FROM paid_emails WHERE id='" . $_GET["mid"] . "'"));
		
		$db->query("SELECT id, mid, uid, email, fname, sname, turingnr FROM massmailer WHERE mid='" . $_GET["mid"] . "' AND id>=" . $_GET["start"] . " ORDER BY id LIMIT " . $_GET["session"]);
		
		$sent	= 0;
		
		while($row = $db->NextRow())
		{
			$tml->RegisterVar("TEXT",	$main->ParseMail($mail["text"], $row["uid"]));
			$tml->RegisterVar("FNAME",	$row["fname"]);
			$tml->RegisterVar("SNAME",	$row["sname"]);
			$tml->RegisterVar("URL",	$mail["url"]);
			
			if($mail["type"] == "paid")
			{
				$tml->RegisterVar("CREDITS",	$mail["credits"]);
				$tml->RegisterVar("C_TYPE",		$mail["c_type"] == "points" ? _LANG_STATS_POINTS : _LANG_STATS_CASH);
				$tml->RegisterVar("MID",		$_GET["mid"]);
				$tml->RegisterVar("UID",		$row["uid"]);
				$tml->RegisterVar("ID",			$row["turingnr"]);
				$tml->RegisterVar("SID",		0);
				
				$tml->loadFromFile("emails/paidmail");
			}
			else
				$tml->loadFromFile("emails/unpaidmail");
			
			$tml->Parse(1);
			
			$main->sendMail($row["email"], $mail["subject"], $tml->GetParsedContent(), _EMAIL_PAIDMAIL, $mail["priority"], $mail["texttype"]);
			
			$db->Query("DELETE FROM massmailer WHERE id='" . $row["id"] . "'", 2);
			
			$sent++;
		}
		
		$lastid	= $db->Fetch("SELECT id FROM massmailer WHERE mid='" . $_GET["mid"] . "' ORDER BY id LIMIT 1");
		
		$db->Query("UPDATE paid_emails SET sent=sent+$sent WHERE id='" . $_GET["mid"] . "'");
		
		$db->Query("SELECT id FROM massmailer WHERE mid='" . $_GET["mid"] . "' AND id>='$lastid'");
		
		if($db->NumRows() == 0)
			exit($main->printText("<B>Mass Mailer</B><BR><BR>The mailing has been done succesfully!"));
		
		$main->printText("<B>Mass Mailer</B><BR><BR>$sent e-mails have been sent, still " . $db->NumRows() . " to go, please wait!<BR><BR>(this page refreshes <b>automatically</b>!)"
				 		."<SCRIPT LANGUAGE=\"javascript\">\nfunction reload(){\n"
				 		."location = \"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=massmailer&mid=" . $_GET["mid"] . "&start=$lastid&session=" . $_GET["session"] . "\";\n"
				 		."}\nsetTimeout(\"reload()\", 5000);\n</SCRIPT>");
	}
	elseif($_GET["action"] == "send")
	{
		@set_time_limit(0);
		
		if($_GET["type"] == "paidemail")
			$db->Query("SELECT id FROM paid_emails WHERE id='" . $_GET["id"] . "'");
		elseif($_GET["type"] == "paidclick")
			$db->Query("SELECT id FROM paid_clicks WHERE id='" . $_GET["id"] . "'");
		else
			exit($error->Report("Send", "The type is unknown."));
		
		if($db->NumRows() == 0)
			exit($error->Report("Send", "The " . $_GET["type"] . " doesn't exists."));
		
		if($_GET["type"] == "paidemail")
			$data		= $db->Fetch("SELECT * FROM paid_emails WHERE id='" . $_GET["id"] . "'");
		
		$sent		= 0;
		
		if($_GET["target"])
		{
			$text	= "<SCRIPT LANGUAGE=\"JavaScript\">\n"
					 ."<!--\n"
					 ."function submitOnlyOnce(theform){\n"
					 ."if (document.all||document.getElementById) {\n"
					 ."  for (i=0;i<theform.length;i++){\n"
					 ."    var tempobj=theform.elements[i]\n"
					 ."    if(tempobj.type.toLowerCase()==\"submit\"||tempobj.type.toLowerCase()==\"reset\")\n"
					 ."      tempobj.disabled=true\n"
					 ."    }\n"
					 ."  }\n"
					 ."}\n"
					 ."// -->\n"
					 ."</SCRIPT>\n";
			
			$once	= $_GET["target"] != "other" ? " ONSUBMIT=\"submitOnlyOnce(this)\"" : "";
			$target	= $_GET["target"] == "other" ? " TARGET=\"_blank\"" : "";
			
			$text	.= "<FORM NAME=\"options\" ACTION=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=send&type=" . $_GET["type"] . "&id=" . $_GET["id"] . "&target=" . $_GET["target"] . "\" METHOD=\"post\"" . $once . $target . ">\n";
		}
		
		if($_GET["target"] == "random")
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				if(!$_POST["receivers"])
					exit($error->Report("Send", "You must enter the number of receivers."));
				elseif($_POST["receivers"] > $user->NumMembers())
					exit($error->Report("Send", "There are not enough members."));
				
				if($_GET["type"] == "paidemail")
				{
					$LoopRandomData	= $db->LoopRandomData("SELECT DISTINCT id, email, fname, sname FROM users WHERE active='yes' AND vacation='0' LIMIT " . $_POST["receivers"]);
					
					foreach($LoopRandomData AS $value)
					{
						$value	= $main->Trim($value, 1);
						
						if($data["type"] == "paid")
						{
							$db->Query("INSERT INTO sent_emails (mid, uid, dateStamp) VALUES ('" . $_GET["id"] . "', '" . $value["id"] . "', '" . time() . "');");
							
							$insert_id	= $db->LastInsertID();
						}
						else
						{
							$insert_id	= 0;
						}
						
						$db->Query("INSERT INTO massmailer (mid, uid, email, fname, sname, turingnr) VALUES ('" . $_GET["id"] . "', '" . $value["id"] . "', '" . $value["email"] . "', '" . addslashes($value["fname"]) . "', '" . addslashes($value["sname"]) . "', '$insert_id');");
						
						$sent++;
					}
					
					$message	= _EMAIL_BACKGROUND == "YES" && _CRONJOBS == "YES" ? "<B>Mass Mailer</B><BR><BR>Your mailing will be sent by the automated background mailer. This can take a few minutes." : GetMMForm($sent);
					
					$main->printText($message);
				}
				elseif($_GET["type"] == "paidclick")
				{
					$data	= $db->LoopRandomData("SELECT DISTINCT id FROM users WHERE active='yes' AND vacation='0' LIMIT " . $_POST["receivers"]);
					
					foreach($data AS $value)
					{
						$db->Query("INSERT INTO sent_clicks (cid, uid, dateStamp) VALUES ('" . $_GET["id"] . "','" . $value["id"] . "','" . time() . "');");
						
						$sent++;
					}
					
					$db->Query("UPDATE paid_clicks SET sent=sent+" . $sent . " WHERE id='" . $_GET["id"] . "'");
					
					$main->printText("<B>Send</B><BR><BR>" . $_POST["receivers"] . " members received your paidclick.", 1);
				}
				else
					$error->Fatal("Send", "The type is unknown.");
			}
			else
			{
				$text	.= "<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD>How much members have to receive the " . $_GET["type"] . "? (total: " . $user->NumMembers() . ")</TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>\n"
						 ."<TR><TD><INPUT TYPE=\"text\" NAME=\"receivers\"></TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>\n"
						 ."<TR><TD><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Send Now!\"></TD></TR>\n"
						 ."</TABLE>\n"
						 ."</FORM>\n";
				
				$main->printText($text);
			}
		}
		elseif($_GET["target"] == "id")
		{
			if($_GET["type"] == "paidemail")
				$data		= $db->Fetch("SELECT * FROM paid_emails WHERE id='" . $_GET["id"] . "'");
			
			$errors		= 0;
			$sent		= 0;
			
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				if(!is_numeric($_POST["start"]) || !is_numeric($_POST["stop"]))
					exit($error->Report("Send", "You left a field empty."));
				
				$db->Query("SELECT id, email, fname, sname FROM users WHERE id>= " . $_POST["start"] . " AND id<=" . $_POST["stop"] . " AND active='yes' AND vacation='0'");
				
				if($db->NumRows() < 0)
					exit($error->Report("Send", "There are not enough members."));
				
				while($userdata = $db->NextRow())
				{
					if($_GET["type"] == "paidemail")
					{
						if($data["type"] == "paid")
						{
							$db->Query("INSERT INTO sent_emails (`mid`, `uid`, `dateStamp`) VALUES ('" . $_GET["id"] . "', '" . $userdata["id"] . "', '" . time() . "');", 2);
							
							$insert_id	= $db->LastInsertID();
						}
						else
							$insert_id	= 0;
						
						$db->Query("INSERT INTO massmailer (mid, uid, email, fname, sname, turingnr) VALUES ('" . $_GET["id"] . "', '" . $userdata["id"] . "', '" . $userdata["email"] . "', '" . addslashes($userdata["fname"]) . "', '" . addslashes($userdata["sname"]) . "', '$insert_id');", 2);
						
						$sent++;
					}
					elseif($_GET["type"] == "paidclick")
					{
						$db->Query("INSERT INTO sent_clicks (cid, uid, dateStamp) VALUES ('" . $_GET["id"] . "', '" . $userdata["id"] . "', '" . time() . "');", 2);
						
						$sent++;
					}
				}
				
				if($sent == 0)
					exit($error->Report("Send", "There is no active member with vacation mode disabled in your targeting options."));
				
				if($_GET["type"] == "paidclick")
				{
					$db->Query("UPDATE paid_clicks SET sent=sent+'$sent' WHERE id='" . $_GET["id"] . "'");
					
					$main->printText("<B>Send</B><BR><BR>$sent members received your paidclick.", 1);
				}
				else
				{
					$message	= _EMAIL_BACKGROUND == "YES" && _CRONJOBS == "YES" ? "<B>Mass Mailer</B><BR><BR>Your mailing will be sent by the automated background mailer. This can take a few minutes." : GetMMForm($sent);
					
					$main->printText($message);
				}
			}
			else
			{
				$lastid	= $db->Fetch("SELECT id FROM users WHERE active='yes' AND vacation='0' ORDER BY id DESC LIMIT 1");
				
				$text	.= "<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD>What member ID's have to receive the " . $_GET["type"] . "? (last id: $lastid)</TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>\n"
						 ."<TR><TD>From ID <INPUT TYPE=\"text\" NAME=\"start\" SIZE=\"10\"> to ID <INPUT TYPE=\"text\" NAME=\"stop\" SIZE=\"10\"></TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>\n"
						 ."<TR><TD><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Send Now!\"></TD></TR>\n"
						 ."</TABLE>\n"
						 ."</FORM>\n";
				
				$main->printText($text);
			}
		}
		elseif($_GET["target"] == "clickthru" && $_GET["type"] == "paidclick")
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				if(!is_numeric($_POST["stopOnClickthru"]))
					exit($error->Report("Send", "You have to enter a \"stop on clickthru\" value."));
				elseif($_POST["stopOnClickthru"] > $user->numMembers())
					exit($error->Report("Send", "\"Stop on clickthru\" (" . $_POST["stopOnClickthru"] . ") can not be higher then the number of members, which is " . $user->numMembers() . "."));
				
				$db->Query("SELECT id FROM users WHERE vacation='0' AND active='yes'");
				
				$t	= time();
				
				while($row = $db->NextRow())
				{
					$db->Query("INSERT INTO sent_clicks (cid, uid, onClickthru, dateStamp) VALUES ('" . $_GET["id"] . "', '" . $row["id"] . "', '" . $_POST["stopOnClickthru"] . "', '" . $t . "');", 2);
				}
				
				$db->Query("UPDATE paid_clicks SET sent=sent+" . $_POST["stopOnClickthru"] . " WHERE id='" . $_GET["id"] . "'");
				
				$main->printText("<B>Send</B><BR><BR>The paid click was sent to all members and will be deactivated after " . $_POST["stopOnClickthru"] . " clicks. Members can click the paidclick only once.", 1);
			}
			else
			{
				$text	.= "<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD>How many clicks do you want for this paidclick? (members: " . $user->NumMembers() . ")</TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>\n"
						 ."<TR><TD>Clickthru: <INPUT TYPE=\"text\" NAME=\"stopOnClickthru\" SIZE=\"10\"></TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>\n"
						 ."<TR><TD><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Send Now!\"></TD></TR>\n"
						 ."</TABLE>\n"
						 ."</FORM>\n";
				
				$main->printText($text);
			}
		}
		elseif($_GET["target"] == "clickthru24" && $_GET["type"] == "paidclick")
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				if(!is_numeric($_POST["stopOnClickthru"]))
					exit($error->Report("Send", "You have to enter a \"stop on clickthru\" value."));
				elseif($_POST["stopOnClickthru"] > $user->numMembers())
					exit($error->Report("Send", "\"Stop on clickthru\" (" . $_POST["stopOnClickthru"] . ") can not be higher then the number of members, which is " . $user->numMembers() . "."));
				
				$db->Query("SELECT id FROM users WHERE vacation='0' AND active='yes'");
				
				while($row = $db->NextRow())
				{
					$db->Query("INSERT INTO sent_clicks (cid, uid, onClickthru, status, clickStamp, dateStamp) VALUES ('" . $_GET["id"] . "', '" . $row["id"] . "', '" . $_POST["stopOnClickthru"] . "', 'unlocked', '" . time() . "', '" . time() . "');", 2);
				}
				
				$db->Query("UPDATE paid_clicks SET sent=sent+" . $_POST["stopOnClickthru"] . " WHERE id='" . $_GET["id"] . "'");
				
				$main->printText("<B>Send</B><BR><BR>The paid click was sent to all members and will be deactivated after " . $_POST["stopOnClickthru"] . " clicks. Members can click the paidclick every 24 hours.", 1);
			}
			else
			{
				$text	.= "<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD>How many clicks do you want for this paidclick? (members: " . $user->numMembers() . ")</TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>\n"
						 ."<TR><TD>Clickthru: <INPUT TYPE=\"text\" NAME=\"stopOnClickthru\" SIZE=\"10\"></TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>\n"
						 ."<TR><TD><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Send Now!\"></TD></TR>\n"
						 ."</TABLE>\n"
						 ."</FORM>\n";
				
				$main->printText($text);
			}
		}
		elseif($_GET["target"] == "other")
		{
			$data		= $db->Fetch("SELECT * FROM paid_emails WHERE id='" . $_GET["id"] . "'");
			$sent		= 0;
			
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				if($_POST["receivers"] <= 0 && $_POST["calculate"] != "yes")
					exit($main->printText("<B>Send</B><BR><BR>You can't send it to 0 members.<BR><BR>Click <A HREF=\"javascript:window.close();\">here</A> to close this window."));
				
				$zvar	= $_POST["calculate"] == "yes" ? "" : "LIMIT " . $_POST["receivers"];
				
				if(is_array($_POST["countries"]))
				{
					foreach($_POST["countries"] AS $value)
					{
						if ($where1)
							$where1 .= " OR ";
						
						$where1 .= "(country='$value')";
					}
				}
				
				if(is_array($_POST["interests"]))
				{
					foreach($_POST["interests"] AS $interest => $value)
					{
						if ($where2)
							$where2 .= " OR ";
						
						$where2 .= "(interests LIKE '%:\"$interest\";%')";
					}
				}
				
				if(is_array($_POST["premium"]))
				{
					foreach($_POST["premium"] AS $key => $value)
					{
						if ($where3)
							$where3 .= " OR ";
						
						$where3 .= "(premium='$key')";
					}
				}
				
				if($_POST["gender"] == "both")
					$where4	= "(gender='male') OR (gender='female')";
				else
					$where4	= "(gender='" . $_POST["gender"] . "')";
				
				if($where1 != "")
					$qSelect1	= " AND ( $where1 )";
				
				if($where2 != "")
					$qSelect2	= " AND ( $where2 )";
				
				if($where3 != "")
					$qSelect3	= " AND ( $where3 )";
				
				$db->Query("SELECT id FROM users WHERE (vacation = '0') AND (active = 'yes')${qSelect1}${qSelect2}${qSelect3} AND ( $where4 ) AND ( birth_year>='" . $_POST["birthdate"]["from"] . "' AND birth_year<='" . $_POST["birthdate"]["to"] . "' )" . $zvar);
				
				if($_POST["calculate"] == "yes")
					exit($main->printText("<B>Send</B><BR><BR>There are " . $db->NumRows() . " members that match your targeting options.<BR><BR>Click <A HREF=\"javascript:history.go(-1)\">here</A> to go back."));
				
				if($_POST["receivers"] > $db->NumRows())
					exit($error->Report("Send", "There are not enough members that match your query. (there could be a member that hasn't selected it's country etc)"));
				
				if($_GET["type"] == "paidemail")
				{
					$LoopRandomData	= $db->LoopRandomData("SELECT DISTINCT id, email, fname, sname FROM users WHERE (vacation = '0') AND (active = 'yes')${qSelect1}${qSelect2}${qSelect3} AND ( $where4 ) AND ( birth_year>='" . $_POST["birthdate"]["from"] . "' AND birth_year<='" . $_POST["birthdate"]["to"] . "' )" . $zvar);
					
					foreach($LoopRandomData AS $value)
					{
						$value	= $main->Trim($value, 1);
						
						if($data["type"] == "paid")
						{
							$db->Query("INSERT INTO sent_emails (mid, uid, dateStamp) VALUES ('" . $_GET["id"] . "', '" . $value["id"] . "', '" . time() . "');");
							
							$insert_id	= $db->LastInsertID();
						}
						else
						{
							$insert_id	= 0;
						}
						
						$db->Query("INSERT INTO massmailer (mid, uid, email, fname, sname, turingnr) VALUES ('" . $_GET["id"] . "', '" . $value["id"] . "', '" . $value["email"] . "', '" . addslashes($value["fname"]) . "', '" . addslashes($value["sname"]) . "', '$insert_id');");
						
						$sent++;
					}
					
					$message	= _EMAIL_BACKGROUND == "YES" && _CRONJOBS == "YES" ? "<B>Mass Mailer</B><BR><BR>Your mailing will be sent by the automated background mailer. This can take a few minutes." : GetMMForm($sent);
					
					$main->printText($message);
				}
				elseif($_GET["type"] == "paidclick")
				{
					$data	= $db->LoopRandomData("SELECT DISTINCT id FROM users WHERE (vacation = '0') AND (active = 'yes')${qSelect1}${qSelect2}${qSelect3} AND ( $where4 ) AND ( birth_year>='" . $_POST["birthdate"]["from"] . "' AND birth_year<='" . $_POST["birthdate"]["to"] . "' )" . $zvar);
					
					foreach($data AS $value)
					{
						$db->Query("INSERT INTO sent_clicks (cid, uid, dateStamp) VALUES ('" . $_GET["id"] . "','" . $value["id"] . "','" . time() . "');");
						
						$sent++;
					}
					
					$db->Query("UPDATE paid_clicks SET sent=sent+" . $sent . " WHERE id='" . $_GET["id"] . "'");
					
					$main->printText("<B>Send</B><BR><BR>$sent members received your paidclick.", 1);
				}
				else
					$error->Fatal("Send", "The type is unknown.");
			}
			else
			{
				$db->Query("SELECT country, gender, interests FROM users WHERE active='yes' AND vacation='0'");
				
				$itot	= Array();
				$ctot	= Array();
				$gtot	= Array();
				
				while($row = $db->NextRow())
				{
					$data	= unserialize($row["interests"]);
					
					if(is_array($data))
					{
						foreach($data AS $name => $value)
						{
							$itot[$name]	+= 1;
						}
					}
					
					$ctot[$row["country"]]	+= 1;
					$gtot[$row["gender"]]	+= 1;
				}
				
				$text	.= "<SCRIPT SRC=\"" . _SITE_URL . "/inc/js/functions.js\" LANGUAGE=\"javascript\"></SCRIPT>\n"
						  ."<SCRIPT LANGUAGE=\"javascript\">var frm = document.options;</SCRIPT>\n"
						  ."<TABLE WIDTH=\"100%\" BORDER=\"0\">\n"
						  ."<TR><TD COLSPAN=3>Please select the targeting options for the " . $_GET["type"] . ".</TD></TR>\n"
						  ."<TR><TD COLSPAN=3>&nbsp;</TD></TR>\n"
						  ."<TR><TD COLSPAN=3><B>Location</B> (<INPUT TYPE=\"checkbox\" NAME=\"allbox\" ONCLICK=\"CheckAll();\"> (de)select all)</TD></TR>\n"
						  ."<TR><TD COLSPAN=3>&nbsp;</TD></TR>\n";
				
				$j		= 1;
				
				if(!is_array($GLOBALS["countries"]))
					exit($error->Fatal("Send", "The /lib/.countries.php file is incorrect."));
				
				foreach($GLOBALS["countries"] AS $country => $country_id)
				{
					if($country_id != "")
					{
						if($j == 1)
							$text	.= "<TR>\n<TD COLSPAN=1><INPUT TYPE=\"checkbox\" NAME=\"countries[]\" VALUE=\"$country_id\" CLASS=\"radio\" ONCLICK=\"CheckItem(this);\"> $country (" . (int) $ctot[$country_id] . ")</TD>\n";
						elseif($j == 2)
							$text	.= "<TD COLSPAN=1><INPUT TYPE=\"checkbox\" NAME=\"countries[]\" VALUE=\"$country_id\" CLASS=\"radio\" ONCLICK=\"CheckItem(this);\"> $country (" . (int) $ctot[$country_id] . ")</TD>\n";
						else
						{
							$text	.= "<TD COLSPAN=1><INPUT TYPE=\"checkbox\" NAME=\"countries[]\" VALUE=\"$country_id\" CLASS=\"radio\" ONCLICK=\"CheckItem(this);\"> $country (" . (int) $ctot[$country_id] . ")</TD>\n</TR>\n";
							
							$j	= 0;
						}
						
						$j++;
					}
				}
				
				$text	 .= "<TR><TD COLSPAN=3>&nbsp;</TD></TR>\n"
						 ."<TR><TD COLSPAN=3><B>Interests</B></TD></TR>\n";
				
				$proginterests	= explode("|", _MEMBER_INTERESTS);
				
				$j	=	1;
				
				for($i = 0; $i < count($proginterests); $i++)
				{
					if($j == 1)
						$text	.= "<TR><TD><INPUT TYPE=\"checkbox\" NAME=\"interests[" . $proginterests[$i] . "]\" CLASS=\"radio\"> " . $proginterests[$i] . " (" . (int) $itot[$proginterests[$i]] . ")</TD>\n";
					elseif($j == 2)
						$text	.= "<TD><INPUT TYPE=\"checkbox\" NAME=\"interests[" . $proginterests[$i] . "]\" CLASS=\"radio\"> " . $proginterests[$i] . " (" . (int) $itot[$proginterests[$i]] . ")</TD>\n";
					else
					{
						$text	.= "<TD><INPUT TYPE=\"checkbox\" NAME=\"interests[" . $proginterests[$i] . "]\" CLASS=\"radio\"> " . $proginterests[$i] . " (" . (int) $itot[$proginterests[$i]] . ")</TD></TR>\n";
						
						$j		= 0;
					}
					
					$j++;
				}
				
				$text	.= "</SELECT></TD></TR>\n";
				
				$text	 .= "<TR><TD COLSPAN=3>&nbsp;</TD></TR>\n"
						 ."<TR><TD COLSPAN=3><B>Premium Members</B></TD></TR>\n";
				
				$db->Query("SELECT DISTINCT premium AS weight FROM users WHERE active='yes' AND vacation='no' ORDER BY weight ASC");
				
				$j	= 1;
				
				while($row = $db->NextRow())
				{
					if($row["weight"] == 0)
						$input	= "<INPUT TYPE=\"checkbox\" NAME=\"premium[" . $row["weight"] . "]\" CLASS=\"radio\" CHECKED> non-premium members</B>";
					else
						$input	= "<INPUT TYPE=\"checkbox\" NAME=\"premium[" . $row["weight"] . "]\" CLASS=\"radio\"> with weight <B>" . $row["weight"] . "</B>";
					
					if($j == 1)
						$text	.= "<TR><TD>$input</TD>\n";
					elseif($j == 2)
						$text	.= "<TD>$input</TD>\n";
					else
					{
						$text	.= "<TD>$input</TD></TR>\n";
						
						$j		= 0;
					}
					
					$j++;
				}
				
				$text	.= "</SELECT></TD></TR>\n";
				
				$text	 .= "<TR><TD COLSPAN=3>&nbsp;</TD></TR>\n"
						 ."<TR><TD COLSPAN=3><B>Birthdate</B></TD></TR>\n"
						 ."<TR><TD COLSPAN=3>From year <INPUT TYPE=\"text\" NAME=\"birthdate[from]\" VALUE=\"1900\" SIZE=\"5\"> to year <INPUT TYPE=\"text\" NAME=\"birthdate[to]\" VALUE=\"2000\" SIZE=\"5\"></TD></TR>\n";
				
				$text	.= "<TR><TD COLSPAN=3>&nbsp;</TD></TR>\n"
						 ."<TR><TD COLSPAN=3><B>Gender</B></TD></TR>\n"
						 ."<TR>\n"
						 ."<TD COLSPAN=1><INPUT TYPE=\"radio\" NAME=\"gender\" VALUE=\"male\" CLASS=\"radio\"> Male (" . (int) $gtot["male"] . ")</TD>\n"
						 ."<TD COLSPAN=1><INPUT TYPE=\"radio\" NAME=\"gender\" VALUE=\"female\" CLASS=\"radio\"> Female (" . (int) $gtot["female"] . ")</TD>\n"
						 ."<TD COLSPAN=1><INPUT TYPE=\"radio\" NAME=\"gender\" VALUE=\"both\" CLASS=\"radio\" CHECKED> Both (" . (int) array_sum($gtot) . ")</TD>\n"
						 ."</TR>\n"
						 ."<TR><TD COLSPAN=3>&nbsp;</TD></TR>\n"
						 ."<TR><TD COLSPAN=3><B>How many members have to receive this " . $_GET["type"] . "?</B></TD></TR>\n"
						 ."<TR><TD COLSPAN=3><INPUT TYPE=\"text\" NAME=\"receivers\"></TD></TR>\n"
						 ."<TR><TD COLSPAN=3>&nbsp;</TD></TR>\n"
						 ."<TR><TD COLSPAN=1><INPUT TYPE=\"radio\" NAME=\"calculate\" VALUE=\"yes\" CHECKED> Calculate target</TD><TD COLSPAN=2><INPUT TYPE=\"radio\" NAME=\"calculate\" VALUE=\"no\"> Send the " . $_GET["type"] . "</TD></TR>\n"
						 ."<TR><TD COLSPAN=3>&nbsp;</TD></TR>\n"
						 ."<TR><TD COLSPAN=3><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Submit!\"></TD></TR>\n"
						 ."</TABLE>\n"
						 ."</FORM>\n";
				
				$main->printText($text);
			}
		}
		else
		{
			$text	.= "<TABLE WIDTH=\"100%\">\n"
					  ."<TR><TD>Please choose how you want to send the " . $_GET["type"] . ".</TD></TR>\n"
					  ."<TR><TD>&nbsp;</TD></TR>\n"
					  ."<TR><TD><A HREF=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=send&type=" . $_GET["type"] . "&id=" . $_GET["id"] . "&target=random\">Send random to members</A></TD></TR>\n"
					  ."<TR><TD><A HREF=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=send&type=" . $_GET["type"] . "&id=" . $_GET["id"] . "&target=id\">Send from member ID to member ID</A></TD></TR>\n";
			
			if($_GET["type"] == "paidclick")
			{
				$text	.= "<TR><TD><A HREF=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=send&type=" . $_GET["type"] . "&id=" . $_GET["id"] . "&target=clickthru\">Send to everyone, let members click once, stop when clickthru X is reached</A></TD></TR>\n";
				$text	.= "<TR><TD><A HREF=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=send&type=" . $_GET["type"] . "&id=" . $_GET["id"] . "&target=clickthru24\">Send to everyone, let members click every 24 hours, stop when clickthru X is reached</A></TD></TR>\n";
			}
			
			$text	.= "<TR><TD><A HREF=\"" . _ADMIN_URL . "/send.php?sid=" . $session->ID . "&action=send&type=" . $_GET["type"] . "&id=" . $_GET["id"] . "&target=other\">Send to members that qualify for targeting options</A></TD></TR>\n"
					  ."</TABLE>\n";
			
			$main->printText($text);
		}
	}
	else
		$error->Report("Send", "The action is unknown.");

?>