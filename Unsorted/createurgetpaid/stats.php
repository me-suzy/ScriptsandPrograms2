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

	$tml->RegisterVar("TITLE", _LANG_EARNINGS_TITLE);

	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_EARNINGS_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	$db->Query("SELECT id FROM payment_methods WHERE id='" . $user->Get("payment_method") . "' AND active='yes'");
	
	if($db->NumRows() == 0)
		exit($error->Report(_LANG_EARNINGS_TITLE, _LANG_MEMBERS_NOMETHOD));
	
	if($_GET["action"] == "detailed" && $_GET["level"] >= 1)
	{
		if(!$_GET["sort"])
			$_GET["sort"]	= "id";
		
		$refs	= $referrals->GetNumReferrals($user->Get("id"), $_GET["level"], 1);
		
		foreach($refs AS $RID)
		{
			if($clause)
				$clause	.= " OR ";
			
			$clause	.= "id='$RID'";
		}
		
		if(!$clause)
			$clause	= "id='-1'";
		
		$clause	= "WHERE " . $clause;
		
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$db->Query("SELECT id, fname, sname, clickthrus, ptc, paidsignups, leads_sales, games, credits, bonus, debits, points, dpoints, referral_data, sessions, lastactive FROM users $clause ORDER BY " . $_GET["sort"] . " ASC LIMIT $start, 20");
		
		$i		= 1;
		$k		= 1;
		
		while($userdata = $db->NextRow())
		{
			$referral_data	= unserialize($userdata["referral_data"]);
			
			$referral_earnings	= 0;
			$preferral_earnings	= 0;
			
			for($j = 1; $j - 1 < $referrals->GetLevelData($user->Get("premium")); $j++)
			{
				$referral_earnings	+= $data["level_$i"];
				$preferral_earnings	+= $data["plevel_$i"];
			}
			
			$cash_earnings	= $userdata["clickthrus"] + $userdata["ptc"] + $userdata["paidsignups"] + $userdata["leads_sales"] + $userdata["games"] + $userdata["credits"] + $userdata["bonus"] + $referral_earnings - $userdata["debits"];
			$point_earnings	= $userdata["points"] + $preferral_earnings - $userdata["dpoints"];
			
			$row["lastactive"]	= date(_SITE_DATESTAMP, $userdata["lastactive"]);
			$row["sessions"]	= $userdata["sessions"];
			$row["rid"]			= $userdata["id"];
			$row["fname"]		= $userdata["fname"];
			$row["sname"]		= $userdata["sname"];
			$row["cash"]		= number_format($cash_earnings, 2);
			$row["points"]		= number_format($point_earnings, 2);
			$row["style"]		= $k;
			
			$tml->RegisterLoop("Stats", $i, $row);
			
			$k	= $k == 1 ? 2 : 1;
			
			$i++;
		}
		
		$db->Query("SELECT id FROM users $clause");
		
		$tml->RegisterVar("NAV",	$main->GeneratePages(_SITE_URL . "/stats.php?sid=" . $session->ID . "&action=detailed&level=" . $_GET["level"] . "&sort=" . $_GET["sort"], $db->NumRows(), 20, $start));
		$tml->RegisterVar("LEVEL",	$_GET["level"]);
		$tml->RegisterVar("COUNT",	$i == 1 ? 0 : $i - 1);
		
		$tml->loadFromFile("pages/stats_detailed");
		$tml->Parse();
		
		$tml->Output();
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$method	= $db->Fetch("SELECT method, fee, minimum FROM payment_methods WHERE id='" . $user->Get("payment_method") . "'");
		
		$tml->RegisterVar("CLICKTHRUS",		number_format($user->Get("clickthrus"),		4, ".", ""));
		$tml->RegisterVar("PTC",			number_format($user->Get("ptc"),			4, ".", ""));
		$tml->RegisterVar("PAIDSIGNUPS",	number_format($user->Get("paidsignups"),	4, ".", ""));
		$tml->RegisterVar("LEADS_SALES",	number_format($user->Get("leads_sales"),	4, ".", ""));
		$tml->RegisterVar("GAMES",			number_format($user->Get("games"),			4, ".", ""));
		$tml->RegisterVar("CREDITS",		number_format($user->Get("credits"),		4, ".", ""));
		$tml->RegisterVar("BONUS",			number_format($user->Get("bonus"),			4, ".", ""));
		$tml->RegisterVar("DEBITS",			number_format($user->Get("debits"),			4, ".", ""));
		
		$tml->RegisterVar("POINTS",			_MEMBER_POINTS == "YES" ? number_format($user->Get("points"),	2, ".", "") : "");
		$tml->RegisterVar("DPOINTS",		_MEMBER_POINTS == "YES" ? number_format($user->Get("dpoints"),	2, ".", "") : "");
		
		$tml->RegisterVar("SHOWINACTIVE", (_REFERRAL_LOGGEDIN != 0 || _REFERRAL_EARNED != 0) && _REFERRAL_WITHIN != 0 && _REFERRAL_TYPE == "CREDITS" ? 1 : 0);
		
		$data		= unserialize($user->Get("referral_data"));
		$loopData	= Array();
		
		$referral_earnings	= 0;
		$preferral_earnings	= 0;
		
		for($i = 1; $i - 1 < $referrals->GetLevelData($user->Get("premium")); $i++)
		{
			if((_REFERRAL_LOGGEDIN != 0 || _REFERRAL_EARNED != 0) && _REFERRAL_WITHIN != 0 && _REFERRAL_TYPE == "CREDITS")
			{
				$numI		= count($referrals->GetNumReferrals($user->Get("id"), $i, 0));
				
				$loopData	= Array(
							"NumIReferrals"		=> $numI,
							"NumReferrals"		=> count($referrals->GetNumReferrals($user->Get("id"), $i, 1)),
							"LevelIEarnings"	=> number_format(($referrals->GetLevelData($user->Get("premium"), $i) * $numI), 4, ".", ""),
							"PLevelEarnings"	=> _MEMBER_POINTS == "YES" ? number_format($data["plevel_$i"], 2, ".", "") : -1,
							"LevelEarnings"		=> number_format($data["level_$i"], 4, ".", ""),
							);
			}
			else
			{
				$loopData	= Array(
							"NumReferrals"		=> count($referrals->GetNumReferrals($user->Get("id"), $i, 1)),
							"PLevelEarnings"	=> _MEMBER_POINTS == "YES" ? number_format($data["plevel_$i"], 2, ".", "") : -1,
							"LevelEarnings"		=> number_format($data["level_$i"], 4, ".", ""),
							);
			}
			
			$referral_earnings	+= $data["level_$i"];
			$preferral_earnings	+= $data["plevel_$i"];
			
			$tml->RegisterLoop("levelData", ($i - 1), $loopData);
		}
		
		$tml->RegisterVar("METHOD",	$method["method"]);
		$tml->RegisterVar("FEE",	$method["fee"]);
		
		$total_earnings		= $user->Get("clickthrus") + $user->Get("ptc") + $user->Get("paidsignups") + $user->Get("leads_sales") + $user->Get("credits") + $user->Get("games") + $user->Get("bonus") + $referral_earnings - $user->Get("debits") - $method["fee"];
		$total_pearnings	= $preferral_earnings + $user->Get("points") - $user->Get("dpoints");
		
		$tml->RegisterVar("TOTAL_PEARNINGS",	_MEMBER_POINTS == "YES" ? number_format($total_pearnings, 4, ".", "") : -1);
		$tml->RegisterVar("TOTAL_EARNINGS",		number_format($total_earnings, 4, ".", ""));
		$tml->RegisterVar("PAYMENT_BUTTON",		$total_earnings >= $method["minimum"] && $total_earnings >= 0.01);
		$tml->RegisterVar("TRANSFER",			_MEMBER_TRANSFER);
		$tml->RegisterVar("TRANSFERFEE",		_MEMBER_TRANSFERFEE);
		
		$tml->loadFromFile("pages/stats");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
		
		$tml->Output();
	}

?>