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

	$tml->RegisterVar("TITLE", _LANG_PTC_TITLE);

	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_PTC_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	$tml->loadFromFile("pages/header");
	$tml->Parse();
	
	$db->Query("SELECT id, cid FROM sent_clicks WHERE uid='" . $user->Get("id") . "' AND status!='locked'");
	
	$i		= 1;
	
	while($row = $db->NextRow())
	{
		$db->Query("SELECT id FROM paid_clicks WHERE id='" . $row["cid"] . "' AND active='yes'", 2);
		
		if($db->NumRows(2) == 1)
		{
			$data	= $db->Fetch("SELECT id, title, banner, url, text, c_type, credits, type FROM paid_clicks WHERE id='" . $row["cid"] . "' AND active='yes'", 2);
			
			$data["c_type"]	= $data["c_type"] == "points" ? _LANG_STATS_POINTS : _LANG_STATS_CASH;
			
			$data["text"]	= $data["text"];
			$data["banner"]	= $data["banner"];
			$data["type"]	= $data["type"];
			$data["uid"]	= $user->Get("id");
			$data["cid"]	= $data["id"];
			$data["id"]		= $row["id"];
			
			$tml->RegisterLoop("PaidClicks", $i, $data);
			
			$i++;
		}
	}
	
	$tml->RegisterVar("COUNT",		$i == 1 ? 0 : $i - 1);
	
	$tml->loadFromFile("pages/ptc");
	$tml->Parse();
	
	$tml->loadFromFile("pages/footer");
	$tml->Parse();
	
	$tml->Output();

?>