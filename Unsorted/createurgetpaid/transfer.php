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

	$tml->RegisterVar("TITLE", _LANG_TRANSFER_TITLE);
	
	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_TRANSFER_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	if(_MEMBER_TRANSFER == "NO")
		exit($error->Report(_LANG_TRANSFER_TITLE, _LANG_ERROR_ERROROCCURED));
	
	$db->Query("SELECT id FROM payment_methods WHERE id='" . $user->Get("payment_method") . "' AND active='yes'");
	
	if($db->NumRows() == 0)
		exit($error->Report(_LANG_TRANSFER_TITLE, _LANG_MEMBERS_NOMETHOD));
	
	$fee	= $db->Fetch("SELECT fee FROM payment_methods WHERE id='" . $user->Get("payment_method") . "'");
	
	$data		= unserialize($user->Get("referral_data"));
	
	$referral_earnings	= 0;
	$preferral_earnings	= 0;
	
	for($i = 1; $i - 1 < $referrals->GetLevelData($user->Get("premium")); $i++)
	{
		$referral_earnings	+= $data["level_$i"];
		$preferral_earnings	+= $data["plevel_$i"];
	}
	
	$total_earnings		= $user->Get("clickthrus") + $user->Get("ptc") + $user->Get("paidsignups") + $user->Get("leads_sales") + $user->Get("credits") + $user->Get("games") + $user->Get("bonus") + $referral_earnings - $user->Get("debits") - $fee;
	$total_pearnings	= $preferral_earnings + $user->Get("points") - $user->Get("dpoints");
	
	if(!isset($_POST["transfer_to"]) || ($_POST["transfer_cash"] < 0.01 && $_POST["transfer_points"] < 1))
		exit($error->Report(_LANG_TRANSFER_TITLE, _LANG_ERROR_FIELDEMPTY));
	
	$db->Query("SELECT id FROM users WHERE email='" . $_POST["transfer_to"] . "'");
	
	if($db->NumRows() != 1)
		exit($error->Report(_LANG_TRANSFER_TITLE, _LANG_TRANSFER_WRONGUSER));
	
	if($_POST["transfer_cash"] >= 0.01 && $total_earnings < $_POST["transfer_cash"])
		exit($error->Report(_LANG_TRANSFER_TITLE, _LANG_TRANSFER_LOWCASH));
	
	if($_POST["transfer_points"] >= 1 && $total_pearnings < $_POST["transfer_points"])
		exit($error->Report(_LANG_TRANSFER_TITLE, _LANG_TRANSFER_LOWPOINTS));
	
	if($_POST["transfer_to"] == $user->Get("email"))
		exit($error->Report(_LANG_TRANSFER_TITLE, _LANG_TRANSFER_SAMEACCOUNT));
	
	$receiver	= $db->Fetch("SELECT id FROM users WHERE email='" . $_POST["transfer_to"] . "'");
	$sender		= $user->Get("id");
	
	if($_POST["transfer_cash"] >= 0.01)
	{
		$_POST["transfer_cash"]	= $_POST["transfer_cash"] - ($_POST["transfer_cash"] * _MEMBER_TRANSFERFEE / 100);
		
		$db->Query("UPDATE users SET credits=credits+'" . $_POST["transfer_cash"] . "' WHERE id='$receiver'");
		$db->Query("UPDATE users SET debits=debits+'" . $_POST["transfer_cash"] . "' WHERE id='$sender'");
		
		$user->Add2Actions($sender, $receiver, "transfer_to",	$_POST["transfer_cash"], "cash");
		$user->Add2Actions($receiver, $sender, "transfer_from",	$_POST["transfer_cash"], "cash");
	}
	
	if($_POST["transfer_points"] >= 1)
	{
		$_POST["transfer_points"]	= $_POST["transfer_points"] - ($_POST["transfer_points"] * _MEMBER_TRANSFERFEE / 100);
		
		$db->Query("UPDATE users SET points=points+'" . $_POST["transfer_points"] . "' WHERE id='$receiver'");
		$db->Query("UPDATE users SET dpoints=dpoints+'" . $_POST["transfer_points"] . "' WHERE id='$sender'");
		
		$user->Add2Actions($sender, $receiver, "transfer_to",	$_POST["transfer_points"], "points");
		$user->Add2Actions($receiver, $sender, "transfer_from",	$_POST["transfer_points"], "points");
	}
	
	$main->PrintText(_LANG_TRANSFER_DONE);

?>