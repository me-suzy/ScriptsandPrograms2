<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	// This script is copyrighted to CreateYourGetPaid©	   \\
	// Duplication, selling, or transferring of this script   \\
	// is a violation of the copyright and purchase agreement.\\
	// Alteration of this script in any way voids any		 \\
	// responsibility CreateYourGetPaid© has towards the	  \\
	// functioning of the script. Altering the script in an   \\
	// attempt to unlock other functions of the program that  \\
	// have not been purchased is a violation of the		  \\
	// purchase agreement and forbidden by CreateYourGetPaid© \\
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	
	include "lib/.htconfig.php";
	
	$tml->RegisterVar("TITLE", _LANG_MAILLINKS_TITLE);
	
	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_MAILLINKS_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	if($_GET["action"] == "open" && is_numeric($_GET["qid"]))
	{
		$tml->RegisterVar("QID",		$_GET["qid"]);
		
		$tml->LoadFromFile("pages/emaillinks_open");
		$tml->Parse();
	}
	elseif($_GET["action"] == "text" && is_numeric($_GET["qid"]))
	{
		$mid	= $db->Fetch("SELECT mid FROM sent_emails WHERE id='" . $_GET["qid"] . "'");
		$data	= $main->Trim($db->Fetch("SELECT text, credits, c_type FROM paid_emails WHERE id='$mid'"));
		
		$tml->RegisterVar("ID",			$_GET["qid"]);
		$tml->RegisterVar("MID",		$mid);
		$tml->RegisterVar("UID",		$user->Get("id"));
		$tml->RegisterVar("FNAME",		$user->Get("fname"));
		$tml->RegisterVar("SNAME",		$user->Get("sname"));
		$tml->RegisterVar("TEXT",		$data["text"]);
		$tml->RegisterVar("CREDITS",	$data["credits"]);
		$tml->RegisterVar("C_TYPE",		$data["c_type"]);
		$tml->RegisterVar("SID",		$session->ID);
		
		$tml->LoadFromFile("emails/paidmail");
		$tml->Parse(1);
		
		$tml->RegisterVar("BODY",		$tml->GetParsedContent());
		
		$tml->LoadFromFile("pages/emaillinks_text");
		$tml->Parse();
	}
	elseif($_GET["action"] == "close")
	{
		$tml->LoadFromFile("pages/emaillinks_close");
		$tml->Parse();
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$db->Query("SELECT id, mid, status, dateStamp FROM sent_emails WHERE uid='" . $user->Get("id") . "' ORDER BY status DESC, dateStamp DESC LIMIT $start, 25");
		
		$i	= 1;
		
		while($row = $db->NextRow())
		{
			$data	= $db->Fetch("SELECT id, subject, url, description, c_type, credits, active FROM paid_emails WHERE id='" . $row["mid"] . "'", 2);
			
			if($data["active"] == "yes")
			{
				$data["c_type"]			= $data["c_type"] == "points" ? _LANG_STATS_POINTS : _LANG_STATS_CASH;
				$data["status"]			= $row["status"] == "read" ? _LANG_EMAILLINKS_READ : _LANG_EMAILLINKS_UNREAD;
				
				$data["description"]	= $data["description"];
				$data["date"]			= date(_SITE_DATESTAMP, $row["dateStamp"]);
				$data["uid"]			= $user->Get("id");
				$data["mid"]			= $data["id"];
				$data["id"]				= $row["id"];
				
				$tml->RegisterLoop("Emaillinks", $i, $data);
				
				$i++;
			}
		}
		
		$db->Query("SELECT id FROM sent_emails WHERE uid='" . $user->Get("id") . "'");
		
		$tml->RegisterVar("NAV",	$main->GeneratePages(_SITE_URL . "/emaillinks.php?sid=" . $session->ID, $db->NumRows(), 25, $start));
		
		$tml->RegisterVar("COUNT",	$i == 1 ? 0 : $i - 1);
		
		$tml->loadFromFile("pages/emaillinks");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
	}
	
	$tml->Output();

?>