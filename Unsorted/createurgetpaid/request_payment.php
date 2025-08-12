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
	
	$tml->RegisterVar("TITLE", _LANG_REQUESTPAYMENT_TITLE);
	
	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_REQUESTPAYMENT_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	$db->Query("SELECT id FROM payment_methods WHERE id='" . $user->Get("payment_method") . "' AND active='yes'");
	
	if($db->NumRows() == 0)
		exit($error->Report(_LANG_REQUESTPAYMENT_TITLE, _LANG_MEMBERS_NOMETHOD));
	
	$method	= $db->Fetch("SELECT method, fee, minimum FROM payment_methods WHERE id='" . $user->Get("payment_method") . "'");
	
	if($user->Get("payment_account") == "")
		exit($error->Report(_LANG_REQUESTPAYMENT_TITLE, _LANG_MEMBERS_NOPAYMENTACCOUNT));
	
	$data	= unserialize($user->Get("referral_data"));
	
	$referral_earnings	= 0;
	
	for($i = 1; $i - 1 < $referrals->GetLevelData($user->Get("premium")); $i++)
	{
		$referral_earnings	+= $data["level_$i"];
	}
	
	$total_earnings	= $user->Get("clickthrus") + $user->Get("ptc") + $user->Get("paidsignups") + $user->Get("leads_sales") + $user->Get("credits") + $user->Get("games") + $user->Get("bonus") + $referral_earnings - $user->Get("debits") - $method["fee"];
	
	if($total_earnings < $method["minimum"])
		exit($error->Report(_LANG_REQUESTPAYMENT_TITLE, _LANG_MEMBERS_NOREQUESTPAYMENT1));
	
	if($_POST["confirm"] == "yes" && $session->Get("payment_in_process") != "true" && $session->Get("visited") == "true")
	{
		if($_POST["amount"] >= _LOGS_APMIN)
		{
			$main->WriteToLog("payments", "Member id \"" . $user->Get("id") . "\" requested payment of \"" . $_POST["amount"] . "\" - full data - session: " . serialize($session->Data) . " post: " . serialize($_POST) . " get: " . serialize($_GET) . " cookie: " . serialize($_COOKIE));
		}
		
		if($total_earnings < $_POST["amount"])
			exit($error->Report(_LANG_REQUESTPAYMENT_TITLE, _LANG_MEMBERS_NOREQUESTPAYMENT2));
		elseif($_POST["amount"] < 0.01)
			exit($error->Report(_LANG_REQUESTPAYMENT_TITLE, _LANG_MEMBERS_NOTENOUGH));
		
		$session->Set("payment_in_process",	"true");
		$session->Set("visited",			"false");
		
		$db->Query("UPDATE users SET debits=debits+'" . $_POST["amount"] . "' WHERE id='" . $user->Get("id") . "'");
		
		$user->Add2Actions($user->Get("id"), 0, "payout", $_POST["amount"]);
		
		$text	= _LANG_MEMBERS_REQUESTPAYMENTOK;
		
		if(_ADDON_AP == 1 && _MEMBER_AP == "YES" && $user->Get("payment_method") == 1)
		{
			$data	= Array(
					"AccountID"				=> _AP_ACCOUNTID,
					"PassPhrase"			=> urlencode(base64_decode(_AP_PASSPHRASE)),
					"Payee_Account"			=> $user->Get("payment_account"),
					"Amount"				=> number_format($_POST["amount"], 2),
					"Memo"					=> urlencode(_MEMBER_PAYOUTMEMO),
					"PAY_IN"				=> 1,
					"WORTH_OF"				=> "Gold",
					"IGNORE_RATE_CHANGE"	=> "Y"
					);
			
			$apayment->Pay($data);
			
			if($apayment->PROCESS_DETAILS["Error"] == "")
			{
				$status	= "OK";
				$paid	= "yes";
			}
			else
			{
				if(_AP_WTDBALANCE != 4 && preg_match("/The account you are trying to pay \(\w+?\) has a Balance Limit imposed that prevents this payment. Spend not allowed./msi", $apayment->PROCESS_DETAILS["Error"], $args))
				{
					$session->Set("payment_in_process",	"false");
					
					if(_AP_WTDBALANCE == 1)
					{
						$db->Query("UPDATE users SET credits=credits+'" . $_POST["amount"] . "' WHERE id='" . $user->Get("id") . "'");
						
						$user->Add2Actions($user->Get("id"), 0, "refund", $_POST["amount"]);
						
						$main->WriteToLog("payments", "Member id \"" . $user->Get("id") . "\"'s e-gold account (" . $user->Get("payment_account") . ") had a balance limit, payment of \"" . _ADMIN_CURRENCY . number_format($_POST["amount"], 2) . "\" refunded.");
						
						exit($main->printText(_LANG_MEMBERS_EGOLDBPAYMENTREFUND));
					}
					elseif(_AP_WTDBALANCE == 2)
					{
						$main->WriteToLog("payments", "Member id \"" . $user->Get("id") . "\"'s e-gold account (" . $user->Get("payment_account") . ") had a balance limit, payment of \"" . _ADMIN_CURRENCY . number_format($_POST["amount"], 2) . "\" deleted.");
						
						exit($main->printText(_LANG_MEMBERS_EGOLDBPAYMENTDELETED));
					}
					elseif(_AP_WTDBALANCE == 3)
					{
						$user->Logoff();
						
						$user->Remove($user->Get("id"));
						
						$main->WriteToLog("payments", "Member id \"" . $user->Get("id") . "\"'s e-gold account (" . $user->Get("payment_account") . ") had a balance limit, payment of \"" . _ADMIN_CURRENCY . number_format($_POST["amount"], 2) . "\" deleted including the member.");
						
						exit($main->printText(_LANG_MEMBERS_EGOLDBPAYMENTANDMEMBERDELETED));
					}
				}
				elseif(_AP_WTDINVALID == 1 && preg_match("/Payee Account Number \(\w+?\) is missing or invalid./i", $apayment->PROCESS_DETAILS["Error"], $args))
				{
					$session->Set("payment_in_process",	"false");
					
					$db->Query("UPDATE users SET credits=credits+'" . $_POST["amount"] . "' WHERE id='" . $user->Get("id") . "'");
					
					$user->Add2Actions($user->Get("id"), 0, "refund", $_POST["amount"]);
					
					$main->WriteToLog("payments", "Member id \"" . $user->Get("id") . "\"'s e-gold account (" . $user->Get("payment_account") . ") was missing or invalid, payment of \"" . _ADMIN_CURRENCY . number_format($_POST["amount"], 2) . "\" refunded.");
					
					exit($main->printText(_LANG_MEMBERS_EGOLDIPAYMENTREFUND));
				}
				
				$status	= $apayment->PROCESS_DETAILS["Error"];
				$paid	= "no";
			}
			
			$qAdd1	= ", paid, batchnr, status";
			$qAdd2	= ", '$paid', '" . $apayment->PROCESS_DETAILS["Batch"] . "', '$status'";
			
			$text	= $status == "OK" ? _LANG_MEMBERS_EGOLDPAYMENTOK : _LANG_MEMBERS_EGOLDPAYMENTNOTOK;
		}
		
		$db->Query("INSERT INTO payments (uid, credits, method, account, dateStamp" . $qAdd1 . ") VALUES ('" . $user->Get("id") . "', '" . $_POST["amount"] ."', '" . addslashes($user->Get("payment_method")) . "', '" . $user->Get("payment_account") . "', '" . time() . "'" . $qAdd2 . ");");
		
		$session->Set("payment_in_process",	"false");
		
		$main->printText($text);
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$tml->RegisterVar("TOTAL_EARNINGS",		number_format($total_earnings, 2));
		$tml->RegisterVar("DATESTAMP",			date(_SITE_DATESTAMP));
		$tml->RegisterVar("PAYMENT_METHOD",		$method["method"]);
		$tml->RegisterVar("PAYMENT_ACCOUNT",	$user->Get("payment_account"));
		
		$session->Set("visited",	"true");
		
		$tml->loadFromFile("pages/requestpayment");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
		
		$tml->Output();
	}

?>