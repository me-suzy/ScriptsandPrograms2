<?php
	// WARNING *** WARNING *** WARNING
	//
	// Do not edit this script unless you know exactly
	// what your are doing!!!
	// If you really want to edit it, please make a backup
	// first. 
	//
	// Editing this script can impact your ability
	// to automatically credit your customer's account!!!

	include_once("settings.php");
	
	// read post from PayPal system and add 'cmd'
	$postvars = array();
	while (list ($key, $value) = each ($HTTP_POST_VARS)) 
	{
		$postvars[] = $key;
	}
	
	$req = 'cmd=_notify-validate';
	for($var = 0; $var < count ($postvars); $var++) 
	{
		$postvar_key = $postvars[$var];
		$postvar_value = $$postvars[$var];
		$req .= "&" . $postvar_key . "=" . urlencode ($postvar_value);
	}

	// post back to PayPal system to validate
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen ($req) . "\r\n\r\n";
	$fp = fsockopen("www.paypal.com", 80, $errno, $errstr, 30);

	// assign posted variables to local variables
	// note: additional IPN variables also available -- see IPN documentation
	$item_name = $HTTP_POST_VARS['item_name'];
	$receiver_email = $HTTP_POST_VARS['receiver_email'];
	$item_number = $HTTP_POST_VARS['item_number'];
	$invoice = $HTTP_POST_VARS['invoice'];
	$payment_status = $HTTP_POST_VARS['payment_status'];
	$payment_gross = $HTTP_POST_VARS['payment_gross'];
	$txn_id = $HTTP_POST_VARS['txn_id'];
	$payer_email = $HTTP_POST_VARS['payer_email'];

	if (!$fp) 
	{
		// HTTP ERROR
		echo "$errstr ($errno)";
	} 
	else 
	{
		fputs ($fp, $header . $req);
		while (!feof($fp)) 
		{
			$res = fgets ($fp, 1024);
			if (strcmp ($res, "VERIFIED") == 0) 
			{
				// check the payment_status=Completed
				// check that txn_id has not been previously processed
				// check that receiver_email is an email address in your PayPal account
				// process payment
				$out = "\n---------------------------\n";

				// Make a transaction in the customer's account
				if($HTTP_POST_VARS['custom'] == $user['sid'] && $HTTP_POST_VARS['payment_type'] == "instant" && $HTTP_POST_VARS['payment_status'] == "Completed")
				{
					// Get the customer for this post
					$cust = lib_getsql("SELECT id FROM clients WHERE sid='$HTTP_POST_VARS[custom]'");
					
					// Post the transaction	
					if($cust[0][id])
					{
						$i = array();
						$i[date] = time();
						$i[adid] = "Payment";
						$i[clientid] = $cust[0][id];
						$i[amount] = $HTTP_POST_VARS['payment_gross'];
						$i[ip] = $REMOTE_ADDR;
						lib_insert("account", $i);
					}
					else
					{
						$msg = "Error while searching for customer with SID-> $HTTP_POST_VARS[custom]. Please contact w3matter.com";
						mail($S[paypal_email], "ADREVENUE -- Error with Paypal Instant Payment Notification", $msg);
					}
				}				
			}
			else if (strcmp ($res, "INVALID") == 0) 
			{
					// log for manual investigation
					$log .= "*** THIS IS AN AUTOMATED MESSAGE. PLEASE DO NOT REPLY ***\n\n";
					$log .= "Please Check This Transaction\n";
					$log .= "PAYPAL AUTOMATIC NOTIFICATION\n";
					$log .= "-----------------------------\n\n";
					while(list($key,$val) = each($HTTP_POST_VARS))
					{
						$log .= "$key = $val\n";
					}
					
					$log .= "-- End of message --";
					mail($S[paypal_email], "ADREVENUE -- Paypal Transaction Inspection", $log);
			}
		}
		fclose ($fp);
	}
?>
