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
	
	$tml->RegisterVar("TITLE", _LANG_START_TITLE);
	
	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_START_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	$db->Query("SELECT id FROM payment_methods WHERE id='" . $user->Get("payment_method") . "' AND active='yes'");
	
	if($db->NumRows() == 0)
		exit($error->Report(_LANG_START_TITLE, _LANG_MEMBERS_NOMETHOD));
	
	$tml->loadFromFile("pages/header");
	$tml->Parse();
	
	$db->Query("SELECT id, cid FROM sent_clicks WHERE uid='" . $user->Get("id") . "' AND status!='locked' LIMIT " . _STARTPAGE_MAXCLICKS);
	
	$i	= 1;
	
	while($row = $db->NextRow())
	{
		$db->Query("SELECT id FROM paid_clicks WHERE id='" . $row["cid"] . "' AND active='yes'", 2);
		
		if($db->NumRows(2) == 1)
		{
			$data			= $db->Fetch("SELECT id, title, c_type, credits, timer FROM paid_clicks WHERE id='" . $row["cid"] . "' AND active='yes'", 2);
			
			$data["c_type"]	= $data["c_type"] == "points" ? _LANG_STATS_POINTS : _LANG_STATS_CASH;
			
			$data["uid"]	= $user->Get("id");
			$data["cid"]	= $data["id"];
			$data["id"]		= $row["id"];
			
			$tml->RegisterLoop("PaidClicks", $i, $data);
			
			$i++;
		}
	}
	
	$tml->RegisterVar("COUNT2",		$i == 1 ? 0 : $i - 1);
	
	$db->Query("SELECT id, mid, status FROM sent_emails WHERE uid='" . $user->Get("id") . "' ORDER BY status DESC, dateStamp DESC LIMIT " . _STARTPAGE_MAXEMAILS);
	
	$i	= 1;
	
	while($row = $db->NextRow())
	{
		$data	= $db->Fetch("SELECT id, subject, c_type, credits, timer, active FROM paid_emails WHERE id='" . $row["mid"] . "' AND active='yes'", 2);
		
		if($data["active"] == "yes")
		{
			$data["c_type"]	= $data["c_type"] == "points" ? _LANG_STATS_POINTS : _LANG_STATS_CASH;
			$data["status"]	= $row["status"] == "read" ? _LANG_EMAILLINKS_READ : _LANG_EMAILLINKS_UNREAD;
			
			$data["uid"]	= $user->Get("id");
			$data["mid"]	= $data["id"];
			$data["id"]		= $row["id"];
			
			$tml->RegisterLoop("Emaillinks", $i, $data);
			
			$i++;
		}
	}
	
	$tml->RegisterVar("COUNT1",	$i == 1 ? 0 : $i - 1);
	
	$method	= $db->Fetch("SELECT method, fee, minimum FROM payment_methods WHERE id='" . $user->Get("payment_method") . "'");
	
	$data	= unserialize($user->Get("referral_data"));
	
	$referral_earnings	= 0;
	$preferral_earnings	= 0;
	
	for($i = 1; $i - 1 < $referrals->GetLevelData($user->Get("premium")); $i++)
	{
		$referral_earnings	+= $data["level_$i"];
		$preferral_earnings	+= $data["plevel_$i"];
	}
	
	$tml->RegisterVar("METHOD",	$method["method"]);
	$tml->RegisterVar("FEE",	$method["fee"]);
	
	$total_earnings		= $user->Get("clickthrus") + $user->Get("ptc") + $user->Get("paidsignups") + $user->Get("leads_sales") + $user->Get("credits") + $user->Get("games") + $user->Get("bonus") + $referral_earnings - $user->Get("debits") - $method["fee"];
	$total_pearnings	= $preferral_earnings + $user->Get("points") - $user->Get("dpoints");
	
	$tml->RegisterVar("TOTAL_PEARNINGS",	_MEMBER_POINTS == "YES" ? number_format($total_pearnings, 2) : -1);
	$tml->RegisterVar("TOTAL_EARNINGS",		number_format($total_earnings, 2));
	$tml->RegisterVar("HELP",				$_GET["action"] == "help" ? 1 : 0);
	$tml->RegisterVar("MAXCLICKS",			_STARTPAGE_MAXCLICKS);
	$tml->RegisterVar("MAXEMAILS",			_STARTPAGE_MAXEMAILS);
	
	$tml->loadFromFile("pages/start");
	$tml->Parse();
	
	$tml->loadFromFile("pages/footer");
	$tml->Parse();
	
	$tml->Output();

?>