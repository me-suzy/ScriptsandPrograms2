<?php
include ("config.inc.php");
#########################################################
#                                                       #
#  Program         : IPN Development Handler            #
#  Author          : Marcus Cicero                      #
#  File            : notify.php                         #
#  Function        : Skeleton IPN Handler               #
#  Version         : 1.4                                #
#  Last Modified   : 11/18/2002                         #
#  Copyright       : EliteWeaver UK                     #
#                                                       #
#########################################################
#    THIS SCRIPT IS FREEWARE AND IS NOT FOR RE-SALE     #
#########################################################


// IPN Posting Modes, Choose: 1 or 2

$postmode = "1";

           //* 1 = Live Via PayPal Network
           //* 2 = Test Via EliteWeaver UK


// Convert Super globals on Older PHP builds

            if(phpversion() <= "4.0.6")
            {
                   $_POST = ($HTTP_POST_VARS);
                                  }

// No IPN post Means this Script does Not exist :)

            if (!$_POST['txn_type'])
            { header("Status: 404 Not Found"); exit;
                                  }
            else
            { header("Status: 200 OK");
                                  }

// Read the Posted IPN, filter Restricted vars and Add "cmd" for Post back Validation

                   $postvars = array();
                   $restrict = array('receiver_email','business','item_name','item_number','quantity','invoice','custom','option_name','option_selection','num_cart_items','payment_status','pending_reason','payment_date','settle_amount','settle_currency','exchange_rate','payment_gross','payment_fee','mc_gross','mc_fee','mc_currency','txn_id','tax','txn_type','memo','first_name','last_name','address_street','address_city','address_state','address_zip','address_country','address_status','payer_email','payer_id','payer_status','payment_type','notify_version','verify_sign','subscr_date','period','amount','recurring','reattempt','retry_at','recur_times','username','password','subscr_id');
                   // Restrict array Should not Contain numerical Characters because IPN related to the PayPal "_cart" Will be INVALID *** Always keep This array Updated with New IPN variables and Maintain full Filtered control Over what this Script and PayPal are Allowed to Talk about ;-) Why? ->>> http://www.php.net/manual/en/security.registerglobals.php

            foreach ($_POST as $ipnkey => $ipnvalue)
            { if (in_array (ereg_replace("[0-9]", '', $ipnkey), $restrict)) {
                   $GLOBALS[$ipnkey] = $ipnvalue; // Posted variable Localization
                   $postvars[] = $ipnkey;
                                  }}

                   $postipn = 'cmd=_notify-validate';
                   $noteipn = '<b>IPN post variables in order of appearance:</b><br><br>';

            for ($x=0; $x < count($postvars); $x++)
            { $y=$x+1;
                   $postkey = $postvars[$x];
                   $postval = $$postvars[$x];
                   $postipn.= "&" . $postkey . "=" . urlencode($postval);
                   $noteipn.= "<b>#" . $y . "</b> Key: " . $postkey . " <b>=</b> Value: " . $postval . "<br>";
                                  }

// PostMode 1: Live Via PayPal Network

            if ($postmode == 1)
            {
$socket = fsockopen ("www.paypal.com", 80, $errno, $errstr, 30);
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header.= "Host: www.paypal.com\r\n";
$header.= "Content-Type: application/x-www-form-urlencoded\r\n";
$header.= "Content-Length: " . strlen($postipn) . "\r\n\r\n";
//* Note: "Connection: Close" is Not required Using HTTP/1.0
                                  }

// PostMode 2: Test Via EliteWeaver UK

            elseif ($postmode == 2)
            {
$socket = fsockopen ("www.eliteweaver.co.uk", 80, $errno, $errstr, 30);
$header = "POST /testing/ipntest.php HTTP/1.0\r\n";
$header.= "Host: www.eliteweaver.co.uk\r\n";
$header.= "Content-Type: application/x-www-form-urlencoded\r\n";
$header.= "Content-Length: " . strlen($postipn) . "\r\n\r\n";
//* Note: "Connection: Close" is Not required Using HTTP/1.0
                                  }

// Selected PostMode was Probably Not Set to 1 or 2

            else
            { $error=1;
echo "PostMode: " . $postmode . " is invalid!";
exit;
                                  }


// Problem: Now is this your Firewall or your Ports?
// Maybe Setup a little email Notification here. . .

            if (!$socket && !$error)
            {
echo "Problem: Error Number: " . $errno . " Error String: " . $errstr;
exit;
                                  }


// If No Problems have Occured then We proceed With the Processing

            else
            {
fputs ($socket, $header . $postipn);

            while (!feof($socket))
	    {
                   $reply = fgets ($socket, 1024);
                   $reply = trim ($reply); // Required on some Environments
                                  }

// Prepare debug Report for Browser display

                   $report = $noteipn . "<br><b>" . "IPN Reply: " . $reply . "</b>";

// Already localized, but This may Protect your Own code From the G and C in GPC ;-)

// Standard - Instant Payment Notifiction Variables

                   $receiver_email = $_POST['receiver_email'];
                   $business = $_POST['business'];
                   $item_name = $_POST['item_name'];
                   $item_number = $_POST['item_number'];
                   $quantity = $_POST['quantity'];
                   $invoice = $_POST['invoice'];
                   $custom = $_POST['custom'];
                   $option_name1 = $_POST['option_name1'];
                   $option_selection1 = $_POST['option_selection1'];
                   $option_name2 = $_POST['option_name2'];
                   $option_selection2 = $_POST['option_selection2'];
                   $num_cart_items = $_POST['num_cart_items'];
                   $payment_status = $_POST['payment_status'];
                   $pending_reason = $_POST['pending_reason'];
                   $payment_date = $_POST['payment_date'];
                   $settle_amount = $_POST['settle_amount'];
                   $settle_currency = $_POST['settle_currency'];
                   $exchange_rate = $_POST['exchange_rate'];
                   $payment_gross = $_POST['payment_gross'];
                   $payment_fee = $_POST['payment_fee'];
                   $mc_gross = $_POST['mc_gross'];
                   $mc_fee = $_POST['mc_fee'];
                   $mc_currency = $_POST['mc_currency'];
                   $tax = $_POST['tax'];
                   $txn_id = $_POST['txn_id'];
                   $txn_type = $_POST['txn_type'];
                   $memo = $_POST['memo'];
                   $first_name = $_POST['first_name'];
                   $last_name = $_POST['last_name'];
                   $address_street = $_POST['address_street'];
                   $address_city = $_POST['address_city'];
                   $address_state = $_POST['address_state'];
                   $address_zip = $_POST['address_zip'];
                   $address_country = $_POST['address_country'];
                   $address_status = $_POST['address_status'];
                   $payer_email = $_POST['payer_email'];
                   $payer_id = $_POST['payer_id'];
                   $payer_status = $_POST['payer_status'];
                   $payment_type = $_POST['payment_type'];
                   $notify_version = $_POST['notify_version'];
                   $verify_sign = $_POST['verify_sign'];

// Subscription - Instant Payment Notifiction Variables

                   $subscr_date = $_POST['subscr_date'];
                   $period1 = $_POST['period1'];
                   $period2 = $_POST['period2'];
                   $period3 = $_POST['period3'];
                   $amount1 = $_POST['amount1'];
                   $amount2 = $_POST['amount2'];
                   $amount3 = $_POST['amount3'];
                   $recurring = $_POST['recurring'];
                   $reattempt = $_POST['reattempt'];
                   $retry_at = $_POST['retry_at'];
                   $recur_times = $_POST['recur_times'];
                   $username = $_POST['username'];
                   $password = $_POST['password'];
                   $subscr_id = $_POST['subscr_id'];


// IPN was Confirmed as both Genuine and VERIFIED

            if (!strcmp ($reply, "VERIFIED"))
            {


$query = "INSERT INTO var_notify (ipn_id,receiver_email,business,item_name,item_number,quantity,invoice,custom,option_name1,option_selection1,option_name2,option_selection2,num_cart_items,payment_status,pending_reason,payment_date,settle_amount,settle_currency,exchange_rate,payment_gross,payment_fee,mc_gross,mc_fee,mc_currency,tax,txn_id,txn_type,memo,first_name,last_name,address_street,address_city,address_state,address_zip,address_country,address_status,payer_email,payer_id,payer_status,payment_type,subscr_date,period1,period2,period3,amount1,amount2,amount3,recurring,reattempt,retry_at,recur_times,username,password,subscr_id,notify_version,verify_sign,status) VALUES ('$ipn_id','$receiver_email','$business','$item_name','$item_number','$quantity','$invoice','$custom','$option_name1','$option_selection1','$option_name2','$option_selection2','$num_cart_items','$payment_status','$pending_reason','$payment_date','$settle_amount','$settle_currency','$exchange_rate','$payment_gross','$payment_fee','$mc_gross','$mc_fee','$mc_currency','$tax','$txn_id','$txn_type','$memo','$first_name','$last_name','$address_street','$address_city','$address_state','$address_zip','$address_country','$address_status','$payer_email','$payer_id','$payer_status','$payment_type','$subscr_date','$period1','$period2','$period3','$amount1','$amount2','$amount3','$recurring','$reattempt','$retry_at','$recur_times','$username','$password','$subscr_id','$notify_version','$verify_sign','$status')";
$req = mysql_query($query);

if (!$req)
{ echo "<B>Error ".mysql_errno()." :</B> ".mysql_error()."";
exit; }			
// Check that the "payment_status" variable is: Completed
// If it is Pending you may Want to Inform your Customer?
// Check your DB to Ensure this "txn_id" is Not a Duplicate
// You may want to Check "payment_gross" or "mc_gross" matches listed Prices?
// You definately want to Check the "receiver_email" or "business" is yours
// Update your DB and Process this Payment accordingly
echo $report; // Remove: # for Testing
                                  }

// IPN was Not Validated as Genuine and is INVALID

            elseif (!strcmp ($reply, "INVALID"))
            {
// Check your code for any Post back Validation problems
// Investigate the Fact that this Could be a spoofed IPN
// If updating your DB, Ensure this "txn_id" is Not a Duplicate
echo $report; // Remove: # for Testing
                                  }

            else
            {
// Just incase Something serious Should ever Happen!
echo $report; // Remove: # for Testing
                                  }


// Terminate the Socket connection and Exit

fclose ($socket);
exit;
                 }

#########################################################
#    THIS SCRIPT IS FREEWARE AND IS NOT FOR RE-SALE     #
#########################################################

?>