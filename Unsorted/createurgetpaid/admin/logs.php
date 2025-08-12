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
	
	$tml->RegisterVar("TITLE", "Log Files");
	
	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Log Files", "You can not access this page."));
	
	if($_GET["action"] == "view" && $_GET["fileName"])
	{
		if(strpos($_GET["fileName"], "..") !== false)
			exit($error->Report("Log Files", "You can't view files before the logs folder."));
		
		$text	= "<TABLE WIDTH=\"100%\">\n"
				 ."<TR><TD COLSPAN=\"2\">View Log File \"" . $_GET["fileName"] . "\"</TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				 ."<TR><TD COLSPAN=\"2\"><TEXTAREA NAME=\"log\" COLS=\"65\" ROWS=\"14\" READONLY>" . $main->Trim($main->ReadFromLog($_GET["fileName"])) . "</TEXTAREA></TD></TR>\n"
				 ."</TABLE></CENTER></DIV>\n</FORM>\n";
		
		$main->printText($text, 1);
	}
	else
	{
		$text	= "<TABLE WIDTH=\"100%\">\n"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				 ."<TR><TD><B>Log File</B></TD><TD><B>Action</B></TD></TR>\n";
		
		if(!$handle	= @opendir(_LOGFILES_PATH))
		{
			$error->Warning("Log Files", "Could not open directory to logfiles \"" . _LOGFILES_PATH . "\"");
			
			$text	.= "<TR><TD COLSPAN=\"2\">There are no log files.</TD></TR>\n";
		}
		else
		{
			$i	= 0;
			
			while($logfile = readdir($handle))
			{
				if($logfile != "." && $logfile != "..")
				{
					$logfile	= str_replace(".log", "", $logfile);
					
					$text		.= "<TR><TD>$logfile</TD><TD><A HREF=\"" . _ADMIN_URL . "/logs.php?sid=" . $session->ID . "&action=view&fileName=$logfile\">View</A></TD></TR>\n";
					
					$i++;
				}
			}
			
			if($i == 0)
			{
				$text	.= "<TR><TD COLSPAN=\"2\">There are no log files.</TD></TR>\n";
			}
			
			closedir($handle);
		}
		
		$text	.= "</TABLE>\n";
		
		$main->printText($text);
	}

?>