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
		exit($error->Report("Advertisers", "You can not access this page."));
	
	if($_GET["action"] == "findaccounts")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(!$_POST["q"])
				$error->Report("Memberlist", "You left a field empty.");
			else
			{
				$qSelect	= "";
				
				foreach($_POST["q"] AS $name => $value)
				{
					if($qSelect)
						$qSelect	.= ", ";
					
					$qSelect	.= "$name";
				}
				
				$db->Query("SELECT password, fname, sname, payment_account, remote_addr, COUNT(*) AS count FROM users GROUP BY $qSelect ORDER BY count DESC");
				$i		= 0;
				
				$text	.= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\"><TR BGCOLOR=\"#D3D3D3\">";
				$text	.= "<TD><B>ID</B></TD><TD><B>Password</B></TD><TD><B>Full Name</B></TD>\n";
				$text	.= "<TD><B>Payment Account</B></TD><TD><b>Signup IP</B></TD><TD><B>Signup Date</B></TD></TR>";
				
				while($getlist = $db->NextRow())
				{
					if($getlist["count"] < 2)
					{
						break;
					}
					
					$qSelect	= "";
					$q			= "SELECT id, password, fname, sname, payment_account, remote_addr, regdate FROM users WHERE ";
					
					foreach($_POST["q"] AS $name => $value)
					{
						if($qSelect)
							$qSelect	.= " AND";
						
						$qSelect	.= " $name='" . $getlist[$name] . "'";
					}
					
					$q	.= $qSelect;
					
					$db->Query($q, 2);
					
					while($row = $db->NextRow(2))
					{
						$text	.= "<TR BGCOLOR=\"#EAEAEA\">";
						$text	.= "<TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["id"] . "\">#" . $row["id"] . "</A></TD>\n";
						$text	.= "<TD>" . $row["password"] . "</TD><TD>" . $row["fname"] . " " . $row["sname"] . "</TD><TD>" . $row["payment_account"] . "</TD><TD>" . $row["remote_addr"] . "</TD><TD>" . date("d-m-Y h:i", $row["regdate"]) . "</TD></TR>";
					}
					
					$i++;
				}
				
				$text	.= "</TABLE>";
				
				$main->PrintText($text);
			}
		}
		else
		{
			$text	= "<FORM ACTION=\"" . _ADMIN_URL . "/cheaters.php?sid=" . $session->ID . "&action=findaccounts\" METHOD=\"POST\">\n"
					 ."<TABLE>\n"
					 ."<TR><TD ALIGN=\"center\"><B>Find accounts that may belong to cheaters</B></TD></TR>\n"
					 ."<TR><TD>&nbsp;</TD></TR>\n"
					 ."<TR><TD>Search for double..</TD></TR>\n"
					 ."<TR><TD><INPUT TYPE=\"checkbox\" NAME=\"q[payment_account]\"> Payment accounts</TD></TR>\n"
					 ."<TR><TD><INPUT TYPE=\"checkbox\" NAME=\"q[remote_addr]\"> IP addresses</TD></TR>\n"
					 ."<TR><TD><INPUT TYPE=\"checkbox\" NAME=\"q[password]\"> Passwords</TD></TR>\n"
					 ."<TR><TD><INPUT TYPE=\"checkbox\" NAME=\"q[fname]\"> First name</TD></TR>\n"
					 ."<TR><TD><INPUT TYPE=\"checkbox\" NAME=\"q[sname]\"> Second name</TD></TR>\n"
					 ."<TR><TD>&nbsp;</TD></TR>\n"
					 ."<TR><TD><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"List Accounts\"></TD></TR>\n"
					 ."</TABLE>\n"
					 ."</FORM>\n";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "computer")
	{
		$db->Query("SELECT remote_addr, browser, COUNT(*) AS count FROM login_logs WHERE remote_addr!='' AND browser!='' GROUP BY remote_addr, browser ORDER BY count DESC");
		
		$text	.= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\"><TR BGCOLOR=\"#D3D3D3\">";
		$text	.= "<TD><B>ID</B></TD><TD><B>IP Address</B></TD><TD><B>Browser / Operating System</B></TD>\n";
		$text	.= "<TD><B>Date and Time</B></TD></TR>";
		
		while($getlist = $db->NextRow())
		{
			if($getlist["count"] < 2)
			{
				break;
			}
			
			$db->Query("SELECT * FROM login_logs WHERE remote_addr='" . $getlist["remote_addr"] . "' AND browser='" . $getlist["browser"] . "' AND browser!=''", 2);
			
			while($row = $db->NextRow(2))
			{
				$text	.= "<TR BGCOLOR=\"#EAEAEA\">";
				$text	.= "<TD><A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["uid"] . "\">#" . $row["uid"] . "</A></TD>\n";
				$text	.= "<TD>" . $row["remote_addr"] . "</TD><TD>" . $row["browser"] . "</TD><TD>" . date("d-m-Y h:i", $row["dateStamp"]) . "</TD></TR>";
			}
			
			$i++;
		}
		
		$text	.= "</TABLE>";
		
		$main->PrintText($text);
	}
	elseif($_GET["action"] == "tracker")
	{
		$db->Query("SELECT uid, email, payment_account, remote_addr, count, dateStamp FROM cheaters ORDER BY dateStamp");
		
		$text	.= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\"><TR BGCOLOR=\"#D3D3D3\">";
		$text	.= "<TD><B>ID</B></TD><TD><B>E-Mail</B></TD><TD><B>Payment Account</B></TD><TD><B>IP Address</B></TD><TD><B>Times</B></TD><TD><B>Date/Time</B></TD></TR>\n";
		
		while($row = $db->NextRow())
		{
			$db->Query("SELECT id FROM users WHERE id='" . $row["uid"] . "'", 2);
			
			$uid	= $db->NumRows(2) == 1 ? "<A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["uid"] . "\">#" . $row["uid"] . "</A>" : $row["uid"];
			
			$text	.= "<TR BGCOLOR=\"#EAEAEA\">";
			$text	.= "<TD>$uid</TD><TD>" . $row["email"] . "</TD><TD>" . $row["payment_account"] . "</TD><TD>" . $row["remote_addr"] . "</TD><TD>" . $row["count"] . "</TD><TD>" . date("d-m-Y h:i", $row["dateStamp"]) . "</TD></TR>";
		}
		
		$text	.= "</TABLE>";
		
		$main->PrintText($text);
	}
	else
		$error->Report("Memberlist", "Action was undefined.");

?>