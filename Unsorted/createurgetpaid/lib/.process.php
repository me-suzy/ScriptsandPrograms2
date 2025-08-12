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
	
	include ".htconfig.php";
	
	if(eregi("d_", $_POST["PAYMENT_METHOD"]) || eregi("d_", $_POST["custom"]))
	{
		$egoldacct	= _AP_DACCTEGOLD;
		$mbacct		= _AP_DACCTMONEYBOOKERS;
		$payacct	= _AP_DACCTPAYPAL;
		
		$egoldhash	= _AP_DHASHEGOLD;
		$mbhash		= _AP_DHASHMONEYBOOKERS;
	}
	else
	{
		$egoldacct	= _AP_ACCTEGOLD;
		$mbacct		= _AP_ACCTMONEYBOOKERS;
		$payacct	= _AP_ACCTPAYPAL;
		
		$egoldhash	= _AP_HASHEGOLD;
		$mbhash		= _AP_HASHMONEYBOOKERS;
	}
	
	if($_POST["PAYMENT_METHOD"])
	{
		$payment_method	= eregi_replace("d_", "", $_POST["PAYMENT_METHOD"]);
	}
	elseif($_POST["custom"])
	{
		$payment_method	= eregi_replace("d_", "", $_POST["custom"]);
	}
	else
		exit();
	
	if($payment_method == "egold")
	{
		if(!$apayment->CheckEgold($_POST["PAYMENT_ID"], $_POST["PAYEE_ACCOUNT"], $_POST["PAYMENT_AMOUNT"], $_POST["PAYMENT_UNITS"], $_POST["PAYMENT_METAL_ID"], $_POST["PAYMENT_BATCH_NUM"], $_POST["PAYER_ACCOUNT"], $egoldhash, $_POST["ACTUAL_PAYMENT_OUNCES"], $_POST["USD_PER_OUNCE"], $_POST["FEEWEIGHT"], $_POST["TIMESTAMPGMT"], trim($_POST["V2_HASH"])))
			exit();
		
		$payment_id		= $_POST["PAYMENT_BATCH_NUM"];
		$order_id		= $_POST["PAYMENT_ID"];
		$payment_gross	= $_POST["PAYMENT_AMOUNT"];
		$receiver		= $_POST["PAYEE_ACCOUNT"];
		$payment_acct	= $_POST["PAYER_ACCOUNT"];
		$account		= $egoldacct;
	}
	elseif($payment_method == "moneybookers")
	{
		if(!$apayment->CheckMoneybookers($_POST["merchant_id"], $_POST["transaction_id"], $mbhash, $_POST["mb_amount"], $_POST["mb_currency"], $_POST["status"], trim($_POST["md5sig"]), $_POST["currency"]))
			exit();
		
		$payment_id		= $_POST["transaction_id"];
		$order_id		= $_POST["transaction_id"];
		$payment_gross	= $_POST["amount"];
		$receiver		= $_POST["pay_to_email"];
		$payment_acct	= $_POST["pay_from_email"];
		$account		= $mbacct;
	}
	elseif($payment_method == "paypal")
	{
		$vars = "cmd=_notify-validate";
		
		foreach($_POST AS $key => $value)
			$vars			.= "&$key=" . urlencode(stripslashes($value));
		
		if(!$apayment->CheckPaypal($vars, $_POST["mc_currency"]))
			exit();
		
		$payment_id		= $_POST["txn_id"];
		$order_id		= $_POST["invoice"];
		$payment_gross	= $_POST["mc_gross"];
		$receiver		= $_POST["receiver_email"];
		$payment_acct	= $_POST["payer_email"];
		$account		= $payacct;
	}
	else
	{
		exit();
	}
	
	if(eregi("d_", $order_id))
	{
		$order_id	= eregi_replace("d_", "", $order_id);
		
		$db->Query("SELECT id FROM deposits WHERE id='$order_id' AND payment_id!='$payment_id'");
		
		if($db->NumRows() == 0)
			exit();
		
		$deposit_data	= $db->Fetch("SELECT uid, amount, payment_date FROM deposits WHERE id='$order_id' AND payment_id!='$payment_id'");
		
		$db_check		= $deposit_data["amount"] >= 0.01 && $deposit_data["payment_date"] == 0 ? "ok" : "false";
		
		if($receiver != $account || $db_check != "ok" || $payment_gross < $deposit_data["amount"])
			exit();
		
		$status		= $payment_method == "paypal" ? $_POST["payment_status"] : "Completed";
		
		if($status == "Completed")
		{
			$db->Query("UPDATE deposits SET payment_acct='$payment_acct', payment_date='" . time() . "', payment_id='$payment_id' WHERE id='$order_id'");
			
			$amount	= $deposit_data["amount"] - (_AP_DFEE / 100 * $deposit_data["amount"]);
			
			$user->Add2Actions($deposit_data["uid"], 0, "deposit", $amount, "cash");
			
			$db->Query("UPDATE users SET credits=credits+'$amount' WHERE id='" . $deposit_data["uid"] . "'");
		}
	}
	else
	{
		$db->Query("SELECT id FROM ad_orders WHERE id='$order_id' AND payment_id!='$payment_id'");
		
		if($db->NumRows() == 0)
			exit();
		
		$order_data	= $db->Fetch("SELECT package, endtotal, fullname, address, zipcode, city, country, email, payment_date FROM ad_orders WHERE id='$order_id' AND payment_id!='$payment_id'");
		
		$db_check	= $order_data["endtotal"] >= 0.01 && $order_data["payment_date"] == 0 ? "ok" : "false";
		
		if($receiver != $account || $db_check != "ok" || $payment_gross < $order_data["endtotal"])
			exit();
		
		$status		= $payment_method == "paypal" ? $_POST["payment_status"] : "Completed";
		
		if($status == "Completed")
		{
			$db->Query("UPDATE ad_orders SET payment_acct='$payment_acct', payment_date='" . time() . "', payment_id='$payment_id' WHERE id='$order_id'");
			
			$db->Query("SELECT id FROM users WHERE email='" . $order_data["email"] . "'");
			
			if($db->NumRows() != 1)
			{
				$country	= "us";
				
				foreach($GLOBALS["countries"] AS $name => $value)
				{
					if($value == $order_data["country"])
					{
						$country	= $name;
						
						break;
					}
				}
				
				$db->Query("INSERT INTO users (email, password, fname, sname, address, city, state, zipcode, country, vacation, advertiser, active, regdate) VALUES ('" . $order_data["email"] . "', '" . $order_data["fullname"] . "', '" . $order_data["fullname"] . "', '', '" . $order_data["address"] . "', '" . $order_data["city"] . "', 'none', '" . $order_data["zipcode"] . "', '$country', '" . (time() + 31536000) . "', 'yes', 'yes', '" . time() . "');");
			}			
			else
				$db->Query("UPDATE users SET advertiser='yes' WHERE email='" . $order_data["email"] . "'");
		}
		
		$tml->RegisterVar("PAYMENT_METHOD",	$apayment->PaymentMethod($payment_method));
		$tml->RegisterVar("PAYMENT_STATUS",	$status);
		$tml->RegisterVar("PAYMENT_GROSS",	$payment_gross);
		$tml->RegisterVar("PAYMENT_ACCT",	$payment_acct);
		$tml->RegisterVar("ORDERID",		$order_id);
		
		$tml->loadFromFile("emails/payment");
		$tml->Parse(1);
		
		$main->SendMail(_EMAIL_ADVERTISE, _SITE_TITLE, $tml->GetParsedContent());
	}

?>