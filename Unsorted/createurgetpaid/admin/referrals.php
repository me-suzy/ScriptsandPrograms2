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
	
	$tml->RegisterVar("TITLE", "Downline Browser");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Downline Browser", "You can not access this page."));
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["do"] == "a")
	{
		if(isset($_POST["add"]))
		{
			header("Location: " . _ADMIN_URL . "/referrals.php?sid=" . $session->ID . "&action=addtodownline&uid=" . $_POST["uid"]);
		}
		else
		{
			if(!is_array($_POST["refs"]))
				exit($error->Report("Downline Browser", "You have to select at least one referral."));
			elseif(isset($_POST["move"]) && !is_numeric($_POST["nuid"]))
				exit($error->Report("Downline Browser", "Please enter the \"move to\" member id."));
			elseif(isset($_POST["move"]) && $_POST["uid"] == $_POST["nuid"])
				exit($error->Report("Downline Browser", "You can not move referrals to the same member."));
			
			if(isset($_POST["move"]))
			{
				$db->Query("SELECT id FROM users WHERE id='" . $_POST["nuid"] . "'");
				
				if($db->NumRows() == 0)
					exit($error->Report("Downline Browser", "The \"move to\" member doesn't exists."));
			}
			
			$i	= 0;
			
			foreach($_POST["refs"] AS $name => $value)
			{
				$db->Query("SELECT id FROM refs WHERE uid='" . $_POST["uid"] . "' AND rid='$name'");
				
				if($db->NumRows() == 1)
				{
					if(isset($_POST["delete"]))
					{
						$db->Query("DELETE FROM refs WHERE uid='" . $_POST["uid"] . "' AND rid='$name'");
						
						$word	= "deleted";
					}
					elseif(isset($_POST["move"]))
					{
						$db->Query("UPDATE refs SET uid='" . $_POST["nuid"] . "' WHERE uid='" . $_POST["uid"] . "' AND rid='$name'");
						
						$word	= "moved";
					}
				}
				
				$i++;
			}
			
			$main->printText("<B>Downline Browser</B><BR><BR>$i members have been $word from downline.", 1);
		}
	}
	elseif($_GET["action"] == "viewdownline")
	{
		$db->Query("SELECT id FROM users WHERE id='" . $_GET["uid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Downline Browser", "User id " . $_GET["uid"] . " doesn't exist."));
		
		$email		= $db->Fetch("SELECT email FROM users WHERE id='" . $_GET["uid"] . "'");
		
		$refers		= Array();
		
		$db->Query("SELECT id, rid FROM refs WHERE uid='" . $_GET["uid"] . "'");
		
		while($data = $db->NextRow())
		{
			$db->Query("SELECT id FROM users WHERE id='" . $data["rid"] . "'", 2);
			
			if($db->NumRows(2) == 1)
				array_push($refers, $data["rid"]);
			else
				$db->Query("DELETE FROM refs WHERE id='" . $data["id"] . "'", 2);
		}
		
		$usersInDownline	= count($refers);
		
		$text	 = "<DIV ALIGN=\"center\"><CENTER>\n";
		$text	.= "<SCRIPT SRC=\"" . _SITE_URL . "/inc/js/functions.js\" LANGUAGE=\"javascript\"></SCRIPT>\n";
		$text	.= "<FORM NAME=\"referrals\" ACTION=\"" . _ADMIN_URL . "/referrals.php?sid=" . $session->ID . "&do=a\" METHOD=\"post\">\n";
		$text	.= "<SCRIPT LANGUAGE=\"javascript\">var frm = document.referrals;</SCRIPT>\n";
		$text	.= "<INPUT TYPE=\"hidden\" NAME=\"uid\" VALUE=\"" . $_GET["uid"] . "\">\n";
		$text	.= "<TABLE WIDTH=\"100%\">\n";
		$text	.= "<TR><TD COLSPAN=\"2\" ALIGN=\"center\"><B>Direct downline of \"$email\"</B></TD></TR>\n";
		$text	.= "<TR><TD COLSPAN=\"2\" ALIGN=\"center\">(total of <B>$usersInDownline</B> members)</TD></TR>\n";
		$text	.= "<TR><TD COLSPAN=\"2\"><HR></TD></TR>\n";
		
		if($usersInDownline == 0)
			$text	.= "<TR><TD COLSPAN=\"2\" ALIGN=\"center\">No referrals in downline</TD></TR>\n";
		else
		{
			$length	= strlen($usersInDownline);
			
			$i	= 1;
			$j	= 1;
			
			foreach($refers AS $id => $uid)
			{
				$db->Query("SELECT id FROM users WHERE id='$uid'");
				
				if($db->NumRows() == 0)
					$db->Query("DELETE FROM refs WHERE id='$uid'");
				else
				{
					$userdata	= $db->Fetch("SELECT id, email FROM users WHERE id='$uid'");
					
					if($j == 1)
					{
						$text	.= "<TR><TD>" . $main->LeadingZero($length, $i) . ". <INPUT TYPE=\"checkbox\" NAME=\"refs[" . $userdata["id"] . "]\" ONCLICK=\"CheckItem(this);\"> <A HREF=\"" . _ADMIN_URL . "/referrals.php?sid=" . $session->ID . "&action=viewdownline&uid=" . $userdata["id"]. "\">" . $userdata["email"] . "</A></TD>";
						$j		= 2;
					}
					elseif($j == 2)
					{
						$text	.= "<TD>" . $main->LeadingZero($length, $i) . ". <INPUT TYPE=\"checkbox\" NAME=\"refs[" . $userdata["id"] . "]\" ONCLICK=\"CheckItem(this);\"> <A HREF=\"" . _ADMIN_URL . "/referrals.php?sid=" . $session->ID . "&action=viewdownline&uid=" . $userdata["id"]. "\">" . $userdata["email"] . "</A></TD></TR>\n";
						$j		= 1;
					}
					
					$i++;
				}
			}
		}
		
		$text	.= "<TR><TD COLSPAN=\"2\"><HR></TD></TR>\n";
		$text	.= "<TR><TD><INPUT TYPE=\"submit\" NAME=\"delete\" VALUE=\"Delete\"> <INPUT TYPE=\"submit\" NAME=\"move\" VALUE=\" Move \"> <INPUT TYPE=\"submit\" NAME=\"add\" VALUE=\" Add \">&nbsp;&nbsp;<INPUT TYPE=\"checkbox\" NAME=\"allbox\" ONCLICK=\"CheckAll();\"> (de)select all</TD><TD ALIGN=\"right\">Move to: (only when moving) <INPUT TYPE=\"text\" NAME=\"nuid\" VALUE=\"Member ID\" SIZE=\"10\"></TD></TR>\n";
		
		$text	.= "</TABLE>\n";
		$text	.= "</FORM>\n";
		$text	.= "</CENTER></DIV>\n";
		
		$main->printText($text);
	}
	elseif($_GET["action"] == "addtodownline")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("SELECT id FROM users WHERE email='" . $_POST["email"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Downline Browser", "Member with e-mail address " . $_POST["email"] . " doesn't exists."));
			
			$qInsert	= _ADDON_CT == 1 ? $_POST["ct"] : "0";
			
			$UID		= $db->Fetch("SELECT id FROM users WHERE email='" . $_POST["email"] . "'");
			
			$db->Query("INSERT INTO refs (uid, rid, status, ct) VALUES ('" . $_GET["uid"] . "', '$UID', '" . $_POST["active"] . "', '$qInsert');");
			
			$main->printText("<B>Downline Browser</B><BR><BR>Member added to downline.", 1);
		}
		else
		{
			$email	= $db->Fetch("SELECT email FROM users WHERE id='" . $_GET["uid"] . "'");
			
			$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/referrals.php?sid=" . $session->ID . "&action=addtodownline&uid=" . $_GET["uid"] . "\" METHOD=\"POST\">\n"
					  ."<TABLE WIDTH=\"100%\">\n"
					  ."<TR><TD COLSPAN=\"2\"><B>Add member to ${email}'s downline</B></TD></TR>"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					  ."<TR><TD>E-Mail Address:</TD><TD><INPUT TYPE=\"text\" NAME=\"email\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"0\">No</OPTION><OPTION VALUE=\"1\" SELECTED>Yes</OPTION></SELECT></TD></TR>\n";
			
			if(_ADDON_CT == 1)
				$text	.= "<TR><TD>Contest:</TD><TD><SELECT NAME=\"ct\" SIZE=\"1\"><OPTION VALUE=\"0\" SELECTED>No</OPTION><OPTION VALUE=\"1\">Yes</OPTION></SELECT></TD></TR>\n";
			
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					  ."<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add to Downline\"></TD></TR>\n"
					  ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	else
		$error->Report("Downline Browser", "Action is unknown.");

?>