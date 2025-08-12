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
	
	$tml->RegisterVar("TITLE", _LANG_REDEMPTIONS_TITLE);
	
	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_REDEMPTIONS_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	$db->Query("SELECT id FROM payment_methods WHERE id='" . $user->Get("payment_method") . "' AND active='yes'");
	
	if($db->NumRows() == 0)
		exit($error->Report(_LANG_REDEMPTIONS_TITLE, _LANG_MEMBERS_NOMETHOD));
	
	$fee	= $db->Fetch("SELECT fee FROM payment_methods WHERE id='" . $user->Get("payment_method") . "'");
	
	$data	= unserialize($user->Get("referral_data"));
	
	$referral_earnings	= 0;
	$referral_pearnings	= 0;
	
	for($i = 1; $i - 1 < $referrals->GetLevelData($user->Get("premium")); $i++)
	{
		$referral_earnings	+= $data["level_$i"];
		$referral_pearnings	+= $data["plevel_$i"];
	}
	
	$cash_earnings	= $user->Get("clickthrus") + $user->Get("ptc") + $user->Get("paidsignups") + $user->Get("leads_sales") + $user->Get("credits") + $user->Get("games") + $user->Get("bonus") + $referral_earnings - $user->Get("debits") - $fee;
	$point_earnings	= ($user->Get("points") + $referral_pearnings) - $user->Get("dpoints");
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$data	= $db->Fetch("SELECT item, c_type, credits, description, weights FROM redempts WHERE id='" . $_POST["rid"] . "'");
		
		if(!in_array($user->Get("premium"), unserialize($data["weights"])))
			exit($error->Report(_LANG_REDEMPTIONS_TITLE, _LANG_ERROR_ERROROCCURED));
		
		if($data["c_type"] == "points")
		{
			$credits	= $point_earnings;
			$table		= "dpoints";
		}
		else
		{
			$credits	= $cash_earnings;
			$table		= "debits";
		}
		
		if($credits < $data["credits"])
			exit($error->Report(_LANG_REDEMPTIONS_TITLE, _LANG_ERROR_ERROROCCURED));
		
		if($_POST["confirm"])
		{
			if(!$_POST["ad_url"] || !$_POST["ad_title"] || !$_POST["ad_text"])
				exit($error->Report(_LANG_REDEMPTIONS_TITLE, _LANG_ERROR_FIELDEMPTY));
			
			$db->Query("UPDATE users SET " . $table . "=" . $table . "+'" . $data["credits"] . "' WHERE id='" . $user->Get("id") . "'");
			
			$user->Add2Actions($user->Get("id"), 0, $table, $data["credits"]);
			
			$db->Query("INSERT INTO ad_orders (package, method, endtotal, fullname, address, zipcode, city, country, email, ad_url, ad_title, ad_text, comments, referer, billdate, payment_acct, payment_date, payment_id) VALUES ('" . $_POST["rid"] . "', 'account', '" . $data["credits"] . "', '" . $user->Get("fname") . " " . $user->Get("sname") . "', '" . $user->Get("address") . "', '" . $user->Get("zipcode") . "', '" . $user->Get("city") . "', '" . $user->Get("country") . "', '" . $user->Get("email") . "', '" . $_POST["ad_url"] . "', '" . $_POST["ad_title"] . "', '" . $_POST["ad_text"] . "', '" . $_POST["comments"] . "', '0', '" . time() . "', '" . $user->Get("email") . "', '" . time() . "', '" . md5($user->Get("id") . time()) . "');");
			
			$tml->RegisterVar("NAME",			$user->Get("fname") . " " . $user->Get("sname"));
			$tml->RegisterVar("EMAIL",			$user->Get("email"));
			$tml->RegisterVar("ITEM",			$data["item"]);
			$tml->RegisterVar("DESCRIPTION",	$data["description"]);
			
			$tml->loadFromFile("emails/redempts");
			$tml->Parse(1);
			
			$main->sendMail(_EMAIL_ADVERTISE, _LANG_REDEMPTS_FORM, $tml->GetParsedContent(), $user->Get("email"));
			
			$main->printText(_LANG_REDEMPTS_ORDERSENT);
		}
		else
		{
			$tml->RegisterVar("DESCRIPTION",	$data["description"]);
			$tml->RegisterVar("ITEM",			$data["item"]);
			$tml->RegisterVar("RID",			$_POST["rid"]);
			
			$tml->loadFromFile("pages/header");
			$tml->Parse();
			
			$tml->loadFromFile("pages/redempts_confirm");
			$tml->Parse();
			
			$tml->loadFromFile("pages/footer");
			$tml->Parse();
			
			$tml->Output();
		}
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$tml->RegisterVar("CASH_EARNINGS",	number_format($cash_earnings, 4, ".", ""));
		$tml->RegisterVar("POINT_EARNINGS",	_MEMBER_POINTS == "YES" ? number_format($point_earnings, 4, ".", "") : -1);
		
		$db->Query("SELECT id, item, c_type, credits, description, weights FROM redempts ORDER BY credits");
		
		$i	= 0;
		$j	= 0;
		
		while($row = $db->NextRow())
		{
			if(_MEMBER_POINTS == "NO" && $row["c_type"] == "points" || !in_array($user->Get("premium"), unserialize($row["weights"])))
				continue;
			
			$credits			= $row["c_type"] == "points" ? $point_earnings : $cash_earnings;
			$row["disabled"]	= $row["credits"] > $credits ? "disabled" : "";
			$row["c_type"]		= $row["c_type"] == "points" ? _LANG_STATS_POINTS : _LANG_STATS_CASH;
			
			$row["credits"]		= number_format($row["credits"], 2);
			$row["pos"]			= $j;
			
			$tml->RegisterLoop("Redempts", $i, $row);
			
			$j	= $j == 1 ? 0 : $j+1;
			
			$i++;
		}
		
		$tml->loadFromFile("pages/redempts");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
		
		$tml->Output();
	}

?>