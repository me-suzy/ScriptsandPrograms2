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

	include "lib/.htconfig.php";

	$tml->RegisterVar("TITLE", _LANG_PAIDSIGNUPS_TITLE);

	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_PAIDSIGNUPS_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	$tml->loadFromFile("pages/header");
	$tml->Parse();
	
	$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
	
	$db->Query("SELECT id, title, text, url, c_type, credits, max FROM paid_signups WHERE active='yes' ORDER BY title ASC LIMIT $start, " . _MEMBER_SIGNUPSPP);
	
	$i		= 1;
	
	while($data = $db->NextRow())
	{
		$db->Query("SELECT id FROM received_signups WHERE sid='" . $data["id"] . "' AND uid='" . $user->Get("id") . "'", 2);
		
		if($db->NumRows(2) == 0)
		{
			$data	= $main->Trim($data);
			
			$db->Query("SELECT id FROM received_signups WHERE sid='" . $data["id"] . "' AND NOT (checked='1' AND credited='no')", 2);
			
			$data["left"]		= $data["max"] - $db->NumRows(2);
			$data["credits"]	= $data["credits"];
			
			$data["c_type"]		= $data["c_type"] == "points" ? _LANG_STATS_POINTS : _LANG_STATS_CASH;
			
			$tml->RegisterLoop("PaidSignups", $i, $data);
			
			$i++;
		}
	}
	
	$db->Query("SELECT id FROM paid_signups WHERE active='yes'");
	
	$tml->RegisterVar("NAV",	$main->GeneratePages(_SITE_URL . "/paidsignups.php?sid=" . $session->ID, $db->NumRows(), _MEMBER_SIGNUPSPP, $start));
	
	$tml->RegisterVar("COUNT",	$i == 1 ? 0 : $i - 1);
	
	$tml->loadFromFile("pages/paidsignups");
	$tml->Parse();
	
	$tml->loadFromFile("pages/footer");
	$tml->Parse();
	
	$tml->Output();

?>