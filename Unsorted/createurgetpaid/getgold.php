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

	$tml->RegisterVar("TITLE", _LANG_GETGOLD_TITLE);

	if(!$user->IsLoggedIn())
		$error->Report(_LANG_GETGOLD_TITLE, _LANG_ERROR_NOTLOGGEDIN);
	
	$db->Query("SELECT id FROM payment_methods WHERE id='" . $user->Get("payment_method") . "' AND active='yes'");
	
	if($db->NumRows() == 0)
		exit($error->Report(_LANG_GETGOLD_TITLE, _LANG_MEMBERS_NOMETHOD));
	
	$fee	= $db->Fetch("SELECT fee FROM payment_methods WHERE id='" . $user->Get("payment_method") . "'");
	
	$data	= unserialize($user->Get("referral_data"));
	
	$referral_earnings	= 0;
	
	for($i = 1; $i - 1 < $referrals->GetLevelData($user->Get("premium")); $i++)
	{
		$referral_earnings	+= $data["level_$i"];
	}
	
	$total_earnings	= $user->Get("clickthrus") + $user->Get("ptc") + $user->Get("paidsignups") + $user->Get("leads_sales") + $user->Get("credits") + $user->Get("games") + $user->Get("bonus") + $referral_earnings - $user->Get("debits") - $fee;
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(!$_POST["info"] || !$_POST["mid"])
			exit($error->Report(_LANG_GETGOLD_TITLE, _LANG_ERROR_FIELDEMPTY));
		
		$db->Query("SELECT id FROM memberships WHERE id='" . $_POST["mid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report(_LANG_GETGOLD_TITLE, _LANG_ERROR_ERROROCCURED));
		
		$membership	= $db->Fetch("SELECT title, price FROM memberships WHERE id='" . $_POST["mid"] . "'");
		
		if($total_earnings < $membership["price"])
			exit($error->Report(_LANG_GETGOLD_TITLE, _LANG_GETGOLD_NOTENOUGH));
		
		$db->Query("UPDATE users SET debits=debits+'" . $membership["price"] . "', premium='" . $_POST["mid"] . "' WHERE id='" . $user->Get("id") . "'");
		
		$user->Add2Actions($user->Get("id"), 0, "debits", $membership["price"]);
		
		$tml->RegisterVar("NAME",		$user->Get("fname") . " " . $user->Get("sname"));
		$tml->RegisterVar("EMAIL",		$user->Get("email"));
		$tml->RegisterVar("TYPE",		$membership["title"]);
		$tml->RegisterVar("INFO",		nl2br($_POST["info"]));
		$tml->RegisterVar("COMMENTS",	nl2br($_POST["comments"]));
		
		$tml->loadFromFile("emails/getgold");
		$tml->Parse(1);
		
		$main->sendMail(_EMAIL_GETGOLD, _LANG_GETGOLD_FORM, $tml->GetParsedContent(), $user->Get("email"));
		
		$main->printText(_LANG_GETGOLD_COMPLETE);
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$db->Query("SELECT id, title, weight, ref_levels, advantages, price FROM memberships ORDER BY weight DESC");
		
		$i	= 1;
		
		while($row = $db->NextRow())
		{
			if($user->Get("premium") < $row["weight"])
			{
				$row["price"]	= number_format($row["price"], 2);
				
				$tml->RegisterLoop("Memberships", $i, $row);
				
				$i++;
			}
		}
		
		$tml->RegisterVar("COUNT", $i == 1 ? 0 : $i - 1);
		
		$tml->loadFromFile("pages/getgold");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
		
		$tml->Output();
	}

?>