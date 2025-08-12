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
	
	$tml->RegisterVar("TITLE", "Template Manager");
	
	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Template Manager", "You can not access this page."));
	
	if($_GET["action"] == "edit" && $_GET["path"])
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$TEVARS	= $main->Trim($_POST, 0);
			
			if(strpos($_GET["path"], "..") !== false)
				exit($error->Report("Template Manager", "You can't edit files before the templates folder."));
			
			if(!$fp = @fopen(_TEMPLATE_PATH . substr($_GET["path"], 1, strlen($_GET["path"])), "w"))
				exit($error->Report("Template Manager", "The template you are trying to change is not CHMOD 777. Pleas change the attributes of this file and then try it again."));
			
			fwrite($fp, $TEVARS["content"]);
			fclose($fp);
			
			$main->WriteToLog("template_change", "Template \"" . $_GET["path"] . "\" changed");
			
			$main->printText("<B>Template Manager</B><BR><BR>The template has been changed.", 1);
		}
		else
		{
			if(strpos($_GET["path"], "..") !== false)
				exit($error->Report("Template Manager", "You can't edit files before the templates folder."));
			
			$fp		= fopen(_TEMPLATE_PATH . $_GET["path"], "r");
			
			while(!feof($fp))
			{
				$content	.= fread($fp, 4096);
			}
			
			fclose($fp);
			
			$text		= "<FORM ACTION=\""._ADMIN_URL."/templates.php?sid=" . $session->ID . "&action=edit&path=" . $_GET["path"] . "\" METHOD=\"post\">\n"
						 ."<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD>Change template \"" . $_GET["path"] . "\"</TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>\n"
						 ."<TR><TD ALIGN=\"center\"><TEXTAREA NAME=\"content\" COLS=\"66\" ROWS=\"16\">" . htmlentities(stripslashes($content)) . "</TEXTAREA></TD></TR>\n"
						 ."<TR><TD>&nbsp;</TD></TR>\n"
						 ."<TR><TD><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Change Template\"> <INPUT TYPE=\"button\" VALUE=\"Back to Filelist\" ONCLICK=\"history.go(-1)\"></TD></TR>\n"
						 ."</TABLE>\n"
						 ."</FORM>\n";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "browse")
	{
		if(strpos($_GET["path"], "..") !== false)
			exit($error->Report("Template Manager", "You can't edit files before the templates folder."));
		
		$text	= "<TABLE WIDTH=\"100%\">\n"
				 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
				 ."<TR><TD><B>Name</B></TD><TD><B>Type</B></TD><TD><B>Action</B></TD></TR>\n";
		
		$handle	= opendir(_TEMPLATE_PATH . $_GET["path"]);
		
		while($item = readdir($handle))
		{
			$path	= _TEMPLATE_PATH . $_GET["path"] . "/" . $item;
			
			if(is_dir($path) && $item != "." && $item != "..")
				$text	.= "<TR><TD>$item</TD><TD>Directory</TD><TD><A HREF=\"" . _ADMIN_URL . "/templates.php?sid=" . $session->ID . "&action=browse&path=" . $_GET["path"] . "/$item\">Open</A></TD></TR>\n";
			elseif($item != "." && $item != "..")
				$text	.= "<TR><TD>$item</TD><TD>Template</TD><TD><A HREF=\"" . _ADMIN_URL . "/templates.php?sid=" . $session->ID . "&action=edit&path=" . $_GET["path"] . "/$item\">Edit</A></TD></TR>\n";
		}
		
		closedir($handle);
		
		$text	.= "<TR><TD COLSPAN=\"3\">&nbsp;</TD></TR>\n";
		
		if($_GET["path"])
		{
			$text	.= "<TR><TD COLSPAN=\"3\" ALIGN=\"center\"><INPUT TYPE=\"button\" VALUE=\"Back to Filelist\" ONCLICK=\"history.go(-1)\"></TD></TR>\n";
			$text	.= "<TR><TD COLSPAN=\"3\">&nbsp;</TD></TR>\n";
		}
		
		$text	.= "</TABLE>\n";
			
		$main->printText($text);
	}
	else
	{
		header("Location: " . _ADMIN_URL . "/templates.php?sid=" . $session->ID . "&action=browse");
	}

?>