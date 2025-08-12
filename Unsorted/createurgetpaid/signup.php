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
	
	$tml->RegisterVar("TITLE", _LANG_SIGNUP_TITLE);
	
	if($_GET["action"] == "turing" && _ADDON_TURING == 1)
	{
		$turing->Output();
	}
	elseif($user->IsLoggedIn() && !$user->IsOperator())
	{
		header("Location: " . _SITE_URL . "/members.php?sid=" . $session->ID);
	}
	elseif($_GET["action"] == "activate" && _MEMBER_ACTIVATION == "YES")
	{
		if(!isset($_GET["c"]) || $_GET["c"] == "")
			exit($error->Report(_LANG_SIGNUP_TITLE, _LANG_SIGNUP_WRONGLINK));
		
		$db->Query("SELECT id FROM users WHERE active='" . $_GET["c"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report(_LANG_SIGNUP_TITLE, _LANG_SIGNUP_WRONGLINK));
		
		$userdata	= $db->Fetch("SELECT id, email FROM users WHERE active='" . $_GET["c"] . "'");
		
		$db->Query("UPDATE users SET active='yes' WHERE active='" . $_GET["c"] . "'");
		
		$user->SendSignupEmail($userdata["email"]);
		$user->ClickQueue($userdata["id"]);
		
		$main->printText(_LANG_SIGNUP_COMPLETE);
	}
	else
	{
		$tml->RegisterVar("ERRORS",	"");
		
		$referer	= $db->Fetch("SELECT fname FROM users WHERE id='" . $referrals->GetRefID($_GET["r"]) . "'");
		
		$tml->RegisterVar("REFERRER", $referer != "" ? $referer : 0);
		$tml->RegisterVar("TURING", _ADDON_TURING == 1 && _TURING_ENABLED == "YES" ? 1 : 0);
		
		$i	= 1;
		
		foreach($GLOBALS["countries"] AS $name => $value)
		{
			$loopData			= Array(
								"name"	=> $name,
								"value"	=> $value
								);
			
			$tml->RegisterLoop("Countries", $i, $loopData);
			$i++;
		}
		
		$db->Query("SELECT id, method, fee FROM payment_methods WHERE active='yes' ORDER BY method ASC");
		
		$i	= 1;
		
		while($row = $db->NextRow())
		{
			$tml->RegisterLoop("Payment_methods", $i, $row);
			
			$i++;
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$error	= Array();
			
			if(!$_POST["email"] || !$_POST["fname"] || !$_POST["sname"] || !$_POST["address"] || !$_POST["city"] || !$_POST["state"] || !$_POST["zipcode"] || !$_POST["country"] || !$_POST["password"] || !$_POST["confirm"])
			{
				$errors[]	= _LANG_ERROR_FIELDEMPTY;
			}
			
			if($_POST["password"] != $_POST["confirm"])
			{
				$errors[]	= _LANG_SIGNUP_PASSWORDNOTMATCH;
			}
			
			if($_POST["terms"] != "agree")
			{
				$errors[]	= _LANG_SIGNUP_NOTERMS;
			}
			
			if(_ADDON_TURING == 1 && _TURING_ENABLED == "YES")
			{
				if($_POST["turing"] != $session->Get("turing"))
				{
					$errors[]	= _LANG_SIGNUP_WRONGTURING;
				}
			}
			
			if(!$user->VerifyData($_POST, $_SERVER))
			{
				$errors[]	= _LANG_ERROR_BLOCKED;
			}
			
			if(_SIGNUP_CHECKEMAIL == "YES")
			{
				$db->Query("SELECT id FROM users WHERE email='" . $_POST["email"] . "'");
				
				if($db->NumRows() != 0)
				{
					$errors[]	= _LANG_SIGNUP_DOUBLEMAIL;
				}
			}
			
			if(_SIGNUP_CHECKBANK == "YES")
			{
				$db->Query("SELECT id FROM users WHERE payment_account='" . $_POST["payment_account"] . "' AND payment_account!=''");
				
				if($db->NumRows() != 0)
				{
					$errors[]	= _LANG_SIGNUP_DOUBLEBANK;
				}
			}
			
			if(_SIGNUP_CHECKIP == "YES" || _SIGNUP_MONITORIP == "YES")
			{
				$db->Query("SELECT id FROM users WHERE remote_addr='" . $_SERVER["REMOTE_ADDR"] . "'");
				
				if($db->NumRows() != 0)
				{
					if(_SIGNUP_CHECKIP == "YES")
					{
						$errors[]	= _LANG_SIGNUP_DOUBLEIP;
					}
					else
					{
						$tml->RegisterVar("DATE",	date("l F d Y"));
						$tml->RegisterVar("EMAIL",	$_POST["email"]);
						$tml->RegisterVar("IP",		$_SERVER["REMOTE_ADDR"]);
						
						$tml->loadFromFile("emails/doubleip");
						$tml->Parse(1);
						
						$main->sendMail(_SITE_EMAIL, _SITE_TITLE, $tml->GetParsedContent(), _SITE_EMAIL);
					}
				}
			}
			
			if(is_array($errors))
			{
				$tml->loadFromFile("pages/header");
				$tml->Parse();
				
				$i	= 1;
				
				foreach($errors AS $name => $value)
				{
					$tml->RegisterLoop("Errors", $i, Array("error" => $value));
					
					$i++;
				}
				
				$tml->RegisterVar("ERRORS", $i > 1 ? $i - 1 : 0);
				
				$tml->loadFromFile("pages/signup");
				$tml->Parse();
				
				$tml->loadFromFile("pages/footer");
				$tml->Parse();
				
				exit($tml->Output());
			}
			
			$newUser	= new User();
			
			$newUser->LoadByID($newUser->Add($_POST["email"], $_POST["password"]));
			
			if(_MEMBER_ACTIVATION == "YES")
			{
				$charset	= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
				$hash		= "";
				
				mt_srand((double) microtime() * 1000000);
				
				for($i = 0; $i < _SIGNUP_HASHLENGTH; $i++)
				{
					$hash	.= $charset[mt_rand(0, strlen($charset) - 1)];
				}
				
				$newUser->Set("active",	$hash);
			}
			else
				$newUser->Set("active",	"yes");
			
			$newUser->Set("fname",				$_POST["fname"]);
			$newUser->Set("sname",				$_POST["sname"]);
			$newUser->Set("address",			$_POST["address"]);
			$newUser->Set("city",				$_POST["city"]);
			$newUser->Set("state",				$_POST["state"]);
			$newUser->Set("zipcode",			$_POST["zipcode"]);
			$newUser->Set("country",			$_POST["country"]);
			$newUser->Set("gender",				$_POST["gender"]);
			$newUser->Set("birth_day",			$_POST["birth_day"]);
			$newUser->Set("birth_month",		$_POST["birth_month"]);
			$newUser->Set("birth_year",			$_POST["birth_year"]);
			$newUser->Set("payment_method",		$_POST["payment_method"]);
			$newUser->Set("payment_account",	$_POST["payment_account"]);
			$newUser->Set("interests",			serialize($_POST["interests"]));
			$newUser->Set("additional",			serialize($_POST["additional"]));
			$newUser->Set("bonus",				_MEMBER_SIGNUPBONUS);
			$newUser->Set("remote_addr",		$_SERVER["REMOTE_ADDR"]);
			$newUser->Set("lastactive",			time());
			$newUser->Set("regdate",			time());
			$newUser->Save();
			
			$ref	= $referrals->GetRefID($_GET["r"]);
			
			if(is_numeric($ref) && $ref != 0)
			{
				$ct	= _ADDON_CT == 1 && _MEMBER_CT == "YES" ? 1 : 0;
				
				if(_REFERRAL_TYPE == "PERCENTAGE")
				{
					$db->Query("INSERT INTO refs (uid, rid, status, ct) VALUES ('$ref', '" . $newUser->Get("id") . "', '1', '$ct');");
				}
				elseif(_REFERRAL_TYPE == "CREDITS")
				{
					$status	= _REFERRAL_LOGGEDIN == 0 && _REFERRAL_EARNED == 0 ? 1 : 0;
					
					$db->Query("INSERT INTO refs (uid, rid, status, ct) VALUES ('$ref', '" . $newUser->Get("id") . "', '$status', '$ct');");
					
					if($status == 1)
					{
						$referrals->AddCreditsToUplines($newUser->Get("id"));
					}
				}
				else
					$error->Fatal(_LANG_SIGNUP_TITLE, _LANG_ERROR_ERROROCCURED);
			}
			
			if(_MEMBER_ACTIVATION == "YES")
			{
				$newUser->SendActivationEmail($_POST["email"], $hash);
				
				$main->printText(_LANG_SIGNUP_ACTMAILSENT);
			}
			else
			{
				$user->SendSignupEmail($_POST["email"]);
				$user->ClickQueue($newUser->Get("id"));
				
				$main->printText(_LANG_SIGNUP_COMPLETE);
			}
		}
		else
		{
			$tml->loadFromFile("pages/header");
			$tml->Parse();
			
			$tml->loadFromFile("pages/signup");
			$tml->Parse();
			
			$tml->loadFromFile("pages/footer");
			$tml->Parse();
			
			$tml->Output();
		}
	}
	
?>