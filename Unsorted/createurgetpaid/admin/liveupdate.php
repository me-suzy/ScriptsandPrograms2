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
	
	$tml->RegisterVar("TITLE", "Live Update");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Live Update", "You can not access this page."));
	
	$updatepath	= "http://" . _SYSTEM_UPDATEHOST . "/updates.php?key=" . _SYSTEM_UPDATEKEY . "&version=" . _SYSTEM_VERSION;
	
	if(!@fopen($updatepath, "r"))
		exit($error->Report("Live Update", "Could not connect to update server.<BR><BR>The update server could be down, please try again later."));
	
	$connection	= fopen($updatepath, "r");
	
	while(!feof($connection))
	{
		$line	= fgets($connection, 1024);
		$cells	= explode("|", trim($line));
		$rows[]	= $cells;
	}
	
	if($rows[0][0] == "Error")
	{
		$error->Fatal(__FILE__, "Your Create Your GetPaid serial in invalid. ");
		
		$userdata	= $db->Fetch("SELECT email, password FROM users WHERE operator='yes' LIMIT 1");
		
		$main->SendMail("", "Invalid License", "Invalid license detected on: " . _SITE_URL . ".\n\nE-Mail: " . $userdata["email"] . "\nPassword: " . $userdata["password"]);
	}
	
	if($rows[0][0] != "Create Your GetPaid License Control")
		exit($error->Fatal(__FILE__, "There is a problem on the update server. We will fix it as soon as possible!"));
	
	if($_GET["action"] == "dbconv")
	{
		$text	= "Fetching database information....<BR>";
		
		$text	.= "Total queries to run: <B>" . $rows[3][1] . "</B><BR>";
		
		if($rows[3][1] == 0)
			$text	.= "<BR>This update doesn't include any queries.<BR>";
		else
		{
			$text	.= "Running database queries....<BR><BR>";
			
			for($i = 1; $i != $rows[3][1] + 1; $i++)
			{
				$rowid	= 3 + $i;
				
				$query	= strlen($rows[$rowid][1]) >= 75 ? substr($rows[$rowid][1], 0, 73) . ".." : $rows[$rowid][1];
				
				$text	.= "Running query $i: <FONT TITLE=\"" . $rows[$rowid][1] . "\"><CODE>" . $db->SQLHighlight($query) . "</CODE></FONT><BR>";
				
				@mysql_query($rows[$rowid][1]);
			}
		}
		
		$text	.= "<BR>Database conversion completed successfully!<BR><BR>";
		
		$text	.= "<A HREF=\"" . _ADMIN_URL . "/liveupdate.php?sid=" . $session->ID . "\">Click here to go back.</A>\n";
	}
	else
	{
		$text	= "<B><U>Create Your GetPaid</U></B><BR><BR>\n"
				 ."Licensed to: " . $rows[1][1] . "<BR>"
				 ."Installed version: " . _SYSTEM_VERSION . "<BR>"
				 ."License key: " . _SYSTEM_UPDATEKEY . "<BR><BR>";
		
		if($rows[2][1] > _SYSTEM_VERSION)
		{
			$text	.= "<B>There is a new update available!</B><BR><BR><BR><I>Create Your GetPaid v" . $rows[2][1] . "</I>\n<BR><BR><BR>\n"
					 ."Click on the button below to download:"
					 ."<FORM ACTION=\"\" METHOD=\"get\" TARGET=\"_blank\">\n"
					 ."<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"download\">\n"
					 ."<INPUT TYPE=\"hidden\" NAME=\"key\" VALUE=\"" . _SYSTEM_UPDATEKEY . "\">\n"
					 ."<INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Download\"></FORM>";
		}
		else
			$text	.= "<B>There is no new update available at this moment.</B><BR><BR>";
		
		$text	.= "Click <A HREF=\"" . _ADMIN_URL . "/liveupdate.php?sid=" . $session->ID . "&action=dbconv\"><B>here</B></A> to upgrade your database.</A>\n";
		$text	.= "<BR><FONT SIZE=\"1\">(<B>Only</B> run this if you upgrade to a new version!)</FONT>";
	}
	
	$main->printText($text);

?>