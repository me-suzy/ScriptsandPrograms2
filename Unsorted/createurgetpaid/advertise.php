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
	
	$tml->RegisterVar("TITLE", _LANG_ADVERTISE_TITLE);
	
	if($_GET["action"] == "done" && _ADDON_AP == 1 && _AP_ADS == 1)
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$tml->RegisterVar("STATUS", $_GET["status"]);
		
		$tml->loadFromFile("pages/advertise_complete");
	}
	elseif($_GET["action"] == "pay" && _ADDON_AP == 1 && _AP_ADS == 1)
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$db->Query("SELECT id FROM ad_orders WHERE id='" . $_GET["oid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report(_LANG_ADVERTISE_TITLE, _LANG_ADVERTISE_WRONGLINK));
		
		$order_data	= $db->Fetch("SELECT package, method, endtotal FROM ad_orders WHERE id='" . $_GET["oid"] . "'");
		
		$tml->RegisterVar("APAY",	1);
		$tml->RegisterVar("PLACED",	0);
		
		$tml->RegisterVar("FORM", $apayment->PaymentForm($order_data["endtotal"], $_GET["oid"], $order_data["method"]));
		
		$tml->RegisterVar("PAYMENT_METHOD", $apayment->PaymentMethod($order_data["method"]));
		
		$tml->loadFromFile("pages/advertise_pay");
	}
	elseif($_GET["step"] == 2)
	{
		$_POST	= $main->Trim($_POST);
		
		if(!$_POST["fullname"] || !$_POST["address"] || !$_POST["zipcode"] || !$_POST["city"] || !$_POST["country"] || !$_POST["email"] || !$_POST["package"] || !$_POST["ad_url"] || !$_POST["ad_title"] || !$_POST["ad_text"])
			exit($error->Report(_LANG_ADVERTISE_TITLE, _LANG_ERROR_FIELDEMPTY));
		
		if(_ADDON_AP == 1 && _AP_ADS == 1 && !$_POST["payment_method"])
			exit($error->Report(_LANG_ADVERTISE_TITLE, _LANG_ERROR_FIELDEMPTY));
		
		if(($_POST["payment_method"] == "egold" && _AP_ACCTEGOLD == "") || ($_POST["payment_method"] == "moneybookers" && _AP_ACCTMONEYBOOKERS == "") || ($_POST["payment_method"] == "paypal" && _AP_ACCTPAYPAL == "") || ($_POST["payment_method"] == "cc" && _AP_ACCTPAYPAL == ""))
			exit($error->Report(_LANG_ADVERTISE_TITLE, _LANG_ERROR_ERROROCCURED));
		
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$tml->RegisterVar("APAY", _ADDON_AP == 1 && _AP_ADS == 1 ? 1 : 0);
		
		foreach($_POST AS $name => $value)
		{
			$session->Set($name, $value);
			
			if($name == "payment_method")
				$value	= $apayment->PaymentMethod($value);
			elseif($name == "package")
				$value	= $db->Fetch("SELECT title FROM ad_packages WHERE id='$value'");
			
			$tml->RegisterVar(strtoupper($name), $value);
		}
		
		$endtotal	= $db->Fetch("SELECT price FROM ad_packages WHERE id='" . $session->Get("package") . "'");
		
		$session->Set("endtotal",	$endtotal);
		
		$tml->RegisterVar("ENDTOTAL", $endtotal);
		
		$tml->loadFromFile("pages/advertise_overview");
	}
	elseif($_GET["step"] == 3)
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$tml->RegisterVar("APAY", _ADDON_AP == 1 && _AP_ADS == 1 ? 1 : 0);
		
		$db->Query("INSERT INTO ad_orders (package, method, endtotal, fullname, address, zipcode, city, country, email, ad_url, ad_title, ad_text, comments, referer, billdate) VALUES ('" . $session->Get("package") . "', '" . $session->Get("payment_method") . "', '" . $session->Get("endtotal") . "', '" . $session->Get("fullname") . "', '" . $session->Get("address") . "', '" . $session->Get("zipcode") . "', '" . $session->Get("city") . "', '" . $session->Get("country") . "', '" . $session->Get("email") . "', '" . $session->Get("ad_url") . "', '" . $session->Get("ad_title") . "', '" . $session->Get("ad_text") . "', '" . $session->Get("comments") . "', '" . $_GET["r"] . "', '" . time() . "');");
		
		foreach($session->Data AS $name => $value)
		{
			if($name == "ad_text")
				$value	= nl2br(htmlentities($value));
			elseif($name == "package")
				$value	= $db->Fetch("SELECT title FROM ad_packages WHERE id='$value'");
			
			$tml->RegisterVar(strtoupper($name), $value);
		}
		
		$tml->RegisterVar("ORDERID", $db->LastInsertID());
		
		$tml->loadFromFile("emails/invoice");
		$tml->Parse(1);
		
		$main->sendMail($session->Get("email"), _LANG_ADVERTISE_INVOICE, $tml->GetParsedContent());
		$main->sendMail(_EMAIL_ADVERTISE, _LANG_ADVERTISE_INVOICE . " Copy", $tml->GetParsedContent());
		
		$tml->RegisterVar("PLACED", 1);
		
		if(_ADDON_AP == 1 && _AP_ADS == 1)
		{
			$tml->RegisterVar("FORM", $apayment->PaymentForm($session->Get("endtotal"), $db->LastInsertID(), $session->Get("payment_method")));
			
			$tml->RegisterVar("PAYMENT_METHOD", $apayment->PaymentMethod($session->Get("payment_method")));
		}
		
		$tml->loadFromFile("pages/advertise_pay");
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		if($_GET["pageID"] == "order")
		{
			$db->Query("SELECT id, title, price FROM ad_packages ORDER BY id ASC");
			
			$i	= 1;
			
			while($row = $db->NextRow())
			{
				$row["price"]	= number_format($row["price"], 2);
				
				$tml->RegisterLoop("Packages", $i, $row);
				
				$i++;
			}
			
			$i	= 1;
			
			foreach($GLOBALS["countries"] AS $name => $value)
			{
				$loopData	= Array("name"	=> $name);
				
				$tml->RegisterLoop("Countries", $i, $loopData);
				
				$i++;
			}
			
			$tml->RegisterVar("APAY",			_AP_ADS == 1 && _ADDON_AP == 1 && _AP_ACCTEGOLD != "" && _AP_ACCTMONEYBOOKERS != "" && _AP_ACCTPAYPAL != "" ? 1 : 0);
			$tml->RegisterVar("EGOLD",			_AP_ADS == 1 && _AP_ACCTEGOLD >= 1 ? 1 : 0);
			$tml->RegisterVar("MONEYBOOKERS",	_AP_ADS == 1 && _AP_ACCTMONEYBOOKERS != "" ? 1 : 0);
			$tml->RegisterVar("PAYPAL",			_AP_ADS == 1 && _AP_ACCTPAYPAL != "" ? 1 : 0);
		}
		
		$_GET["pageID"]			= !$_GET["pageID"] || !file_exists(_TEMPLATE_PATH . $session->Get("language") . "/" . "pages/advertise_" . $_GET["pageID"] . ".tml") ? "index" : $_GET["pageID"];
			
		$tml->loadFromFile("pages/advertise_" . $_GET["pageID"]);
	}
	
	$tml->Parse();
	
	$tml->loadFromFile("pages/footer");
	$tml->Parse();
	
	$tml->Output();

?>