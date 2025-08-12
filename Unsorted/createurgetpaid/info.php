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

	$tml->RegisterVar("TITLE", _LANG_INFO_TITLE);

	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_INFO_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(!$_POST["email"] || !$_POST["fname"] || !$_POST["sname"] || !$_POST["address"] || !$_POST["city"] || !$_POST["state"] || !$_POST["zipcode"] || !$_POST["country"] || !$_POST["password"])
			exit($error->Report(_LANG_INFO_TITLE, _LANG_ERROR_FIELDEMPTY));
		
		if($_POST["password"] != $user->Get("password"))
			exit($error->Report(_LANG_INFO_TITLE, _LANG_MEMBERS_BADPASSWORD));
		
		if(!$user->VerifyData($_POST, $_SERVER))
			exit($error->Report(_LANG_INFO_TITLE, _LANG_ERROR_BLOCKED));
		
		$db->Query("SELECT id FROM users WHERE email='" . $_POST["email"] . "' AND id!='" . $user->Get("id") . "'");
		
		if($db->NumRows() >= 1)
			exit($error->Report(_LANG_INFO_TITLE, _LANG_MEMBERS_USEDEMAIL));
		
		if(_SIGNUP_CHECKBANK == "YES")
		{
			$db->Query("SELECT id FROM users WHERE payment_account='" . $_POST["payment_account"] . "' AND id!='" . $user->Get("id") . "'");
			
			if($db->NumRows() >= 1)
				exit($error->Report(_LANG_INFO_TITLE, _LANG_MEMBERS_USEDBANK));
		}
		
		if($_POST["new_password"])
			$user->Set("password",			$_POST["new_password"]);
		
		$user->Set("email",				$_POST["email"]);
		$user->Set("fname",				$_POST["fname"]);
		$user->Set("sname",				$_POST["sname"]);
		$user->Set("address",			$_POST["address"]);
		$user->Set("city",				$_POST["city"]);
		$user->Set("state",				$_POST["state"]);
		$user->Set("zipcode",			$_POST["zipcode"]);
		$user->Set("country",			$_POST["country"]);
		$user->Set("gender",			$_POST["gender"]);
		$user->Set("birth_day",			$_POST["birth_day"]);
		$user->Set("birth_month",		$_POST["birth_month"]);
		$user->Set("birth_year",		$_POST["birth_year"]);
		$user->Set("payment_method",	$_POST["payment_method"]);
		$user->Set("payment_account",	$_POST["payment_account"]);
		$user->Set("interests",			serialize($_POST["interests"]));
		$user->Set("additional",		serialize($_POST["additional"]));
		
		if($_POST["vacation"] == "on")
		{
			$user->Set("vacation", $user->Get("vacation") == 0 ? time() : $user->Get("vacation"));
		}
		else
			$user->Set("vacation", 0);
		
		$user->Save();
		
		$main->printText(_LANG_INFO_UPDATED);
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$i	= 1;
		
		foreach($GLOBALS["countries"] AS $name => $value)
		{
			$selected	= $value == $user->Get("country") ? "selected" : "";
			
			$tml->RegisterLoop("Countries", $i, Array("name" => $name, "value" => $value, "selected" => $selected));
			
			$i++;
		}
		
		$db->Query("SELECT id, method, fee FROM payment_methods WHERE active='yes' ORDER BY method ASC");
		
		$i	= 1;
		
		while($row = $db->NextRow())
		{
			$row["status"]	= $row["id"] == $user->Get("payment_method") ? "selected" : "";
			
			$tml->RegisterLoop("Payment_methods", $i, $row);
			
			$i++;
		}
		
		$proginterests	= explode("|", _MEMBER_INTERESTS);
		$userinterests	= unserialize($user->Get("interests"));
		
		for($i = 0; $i < count($proginterests); $i++)
		{
			$tml->RegisterVar($proginterests[$i], eregi("on", $userinterests[$proginterests[$i]]) ? "checked" : "");
		}
		
		$progadditional	= explode("|", _MEMBER_ADDITIONAL);
		$useradditional	= unserialize($user->Get("additional"));
		
		for($i = 0; $i < count($progadditional); $i++)
		{
			$tml->RegisterVar($progadditional[$i], $useradditional[$progadditional[$i]]);
		}
		
		$upline_id	= $db->Fetch("SELECT uid FROM refs WHERE rid='" . $user->Get("id") . "'");
		$upline		= $db->Fetch("SELECT fname, sname FROM users WHERE id='" . $upline_id["uid"] . "'");
		
		$tml->RegisterVar("UPLINE",				$upline_id >= 1 ? $upline["fname"] . " " . $upline["sname"] : "");
		
		$tml->RegisterVar("CHECKED",			$user->Get("vacation") >= 1 ? "checked" : 0);
		$tml->RegisterVar("TIME",				$user->Get("vacation") >= 1 ? date(_SITE_DATESTAMP, $user->Get("vacation") + _MEMBER_VACLENGTH) : "-");
		
		$tml->RegisterVar("MALE",				$user->Get("gender") == "male" ? "SELECTED" : "");
		$tml->RegisterVar("FEMALE",				$user->Get("gender") == "female" ? "SELECTED" : "");
		
		$tml->RegisterVar("EMAIL",				$user->Get("email"));
		$tml->RegisterVar("FNAME",				$user->Get("fname"));
		$tml->RegisterVar("SNAME",				$user->Get("sname"));
		$tml->RegisterVar("ADDRESS",			$user->Get("address"));
		$tml->RegisterVar("CITY",				$user->Get("city"));
		$tml->RegisterVar("STATE",				$user->Get("state"));
		$tml->RegisterVar("ZIPCODE",			$user->Get("zipcode"));
		$tml->RegisterVar("COUNTRY",			$user->Get("country"));
		$tml->RegisterVar("PAYMENT_ACCOUNT",	$user->Get("payment_account"));
		
		$tml->RegisterVar("DAY"					. $user->Get("birth_day"),		"selected");
		$tml->RegisterVar("MONTH"				. $user->Get("birth_month"),	"selected");
		$tml->RegisterVar("YEAR"				. $user->Get("birth_year"),		"selected");
		
		$tml->loadFromFile("pages/info");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
		
		$tml->Output();
	}

?>