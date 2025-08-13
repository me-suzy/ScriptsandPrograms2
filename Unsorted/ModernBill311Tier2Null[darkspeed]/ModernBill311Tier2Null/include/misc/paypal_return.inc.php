<?
#########################################################
#                                                       #
#         ModernBill .:. Client Billing System          #
#  Copyright © 2001 ModernBill   All Rights Reserved.   #
#                                                       #
#########################################################
###############################################################################
#                                                                             #
#    PayPal IPN Handler for Modernbill v0.02 (Alpha)                          #
#        Coded by: RunWithUs! Internet Services                               #
#        http://www.runwithus.com / admin@runwithus.com                       #
#                                                                             #
#    1) Place this file in your /include/misc directory                       #
#    2) Edit your PayPal IPN URL at PayPal.com to                             #
#       point to this file, ie:                                               #
#       http://www.yoursite.com/modernbill/include/misc/paypal_return.inc.php #
#    3) Create a paypal_transactions table:                                   #
#                                                                             #
#       CREATE TABLE paypal_transactions (                                    #
#   createstamp date DEFAULT '0000-00-00' NOT NULL,                           #
#   sweeped tinyint(1) DEFAULT '0' NOT NULL,                                  #
#   receiver_email varchar(150) DEFAULT '0' NOT NULL,                         #
#   item_name varchar(150) DEFAULT '0' NOT NULL,                              #
#   item_number varchar(150) DEFAULT '0' NOT NULL,                            #
#   quantity varchar(150) DEFAULT '0' NOT NULL,                               #
#   invoice varchar(150) DEFAULT '0' NOT NULL,                                #
#   custom varchar(150) DEFAULT '0' NOT NULL,                                 #
#   payment_status varchar(150) DEFAULT '0' NOT NULL,                         #
#   pending_reason varchar(150) DEFAULT '0' NOT NULL,                         #
#   payment_date varchar(150) DEFAULT '0' NOT NULL,                           #
#   payment_gross varchar(150) DEFAULT '0' NOT NULL,                          #
#   payment_fee varchar(150) DEFAULT '0' NOT NULL,                            #
#   txn_id varchar(150) DEFAULT '0' NOT NULL,                                 #
#   txn_type varchar(150) DEFAULT '0' NOT NULL,                               #
#   first_name varchar(150) DEFAULT '0' NOT NULL,                             #
#   last_name varchar(150) DEFAULT '0' NOT NULL,                              #
#   address_street varchar(150) DEFAULT '0' NOT NULL,                         #
#   address_city varchar(150) DEFAULT '0' NOT NULL,                           #
#   address_state varchar(150) DEFAULT '0' NOT NULL,                          #
#   address_zip varchar(150) DEFAULT '0' NOT NULL,                            #
#   address_country varchar(150) DEFAULT '0' NOT NULL,                        #
#   address_status varchar(150) DEFAULT '0' NOT NULL,                         #
#   payer_email varchar(150) DEFAULT '0' NOT NULL,                            #
#   payer_status varchar(150) DEFAULT '0' NOT NULL,                           #
#   payment_type varchar(150) DEFAULT '0' NOT NULL,                           #
#   notify_version varchar(150) DEFAULT '0' NOT NULL,                         #
#   verify_sign varchar(150) DEFAULT '0' NOT NULL,                            #
#   response varchar(20) DEFAULT 'NONE' NOT NULL,                             #
#   UNIQUE txn_id (txn_id));                                                  #
#                                                                             #
#     Use at your own risk. We take no responsibility                         #
#     for this code - just something we threw together                        #
#     on two hours of sleep and a couple gallons of                           #
#     iced tea (caffeine intake of preference)                                #
#                                                                             #
#     Please email comments/suggestions to us and we'll                       #
#     try to incorporate them in.  thanks                                     #
#                                                                             #
#                                                                             #
#     Version History:                                                        #
#     0.02    Added paypal_transactions table to log all transactions,        #
#             including those which don't have an invoice associated          #
#             with them.                                                      #
#     0.01    Initial release - Credits invoice when customer uses MBill      #
#                  generated PayPal URL.                                      #
#                                                                             #
#     To do:                                                                  #
#     1) Create a PayPal ledger-view from within MB admin                     #
#     2) Apply credit to customer account (match by email address)            #
#     3) Use of custom PayPal variables to better track payments.             #
#     4) URL redirection so clients see http://www.host.com/payme.php?i=65    #
#        or something similar (easier to see from within email programs)      #
#     5) Handling of invalid transactions (maybe flag it for investigation    #
#         on the 'To Do' list                                                 #
###############################################################################

$DIR = "../../";
include($DIR."include/functions.inc.php");

## Connect to the DB
GLOBAL $dbh;
if (!$dbh) dbconnect();



// read post from PayPal system and add 'cmd'
$postvars = array();
while (list ($key, $value) = each ($HTTP_POST_VARS)) {
$postvars[] = $key;
}
$req = 'cmd=_notify-validate';
for ($var = 0; $var < count ($postvars); $var++) {
$postvar_key = $postvars[$var];
$postvar_value = $$postvars[$var];
$req .= "&" . $postvar_key . "=" . urlencode ($postvar_value);
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen ($req) . "\r\n\r\n";
$fp = fsockopen ("www.paypal.com", 80, $errno, $errstr, 30);

if (!$fp) {
// HTTP ERROR - this am bad mmmkay
echo "$errstr ($errno)";
} else {
// This am good
fputs ($fp, $header . $req);
while (!feof($fp)) {
$res = fgets ($fp, 1024);

// if good, then process the payment/invoice
if (strcmp ($res, "VERIFIED") == 0) {

        $response = "VERIFIED";
        $invoice_amount_paid = ($payment_gross) ? $payment_gross : 0 ;
        $invoice_id = $item_number;
        $trans_id = $txn_id;

        ## update the invoice
        $pay_update_sql = "UPDATE client_invoice
                           SET invoice_amount_paid=invoice_amount_paid+$invoice_amount_paid,
                           invoice_date_paid='".mktime()."',
                           invoice_payment_method='$paypal_id',
                           auth_return='1',
                           auth_code='',
                           avs_code='',
                           trans_id='$trans_id',
                           batch_stamp='".mktime()."',
                           invoice_stamp='".mktime()."'
                           WHERE invoice_id='$invoice_id'";

        if($debug)echo MFB."<pre>".$pay_update_sql."</pre>".EF."<br>";
        if (!mysql_query($pay_update_sql,$dbh)) { echo mysql_errno(). ": ".mysql_error(). "<br>"; return; }

        $this_invoice = mysql_fetch_array(mysql_query("SELECT * FROM client_invoice WHERE invoice_id='$invoice_id'"));

        ## Clear batch
        $total_paid_amount = $this_invoice[invoice_amount_paid] + $invoice_amount_paid;
        if ($this_invoice[invoice_amount]==$total_paid_amount) {
            if (!mysql_query("DELETE FROM authnet_batch WHERE x_Invoice_Num = $invoice_id",$dbh)) { echo mysql_errno(). ": ".mysql_error(). "<br>"; return; }
        } elseif ($this_invoice[invoice_amount]>$total_paid_amount) {
            $x_Amount = $this_invoice[invoice_amount] - $total_paid_amount;
            if (!mysql_query("UPDATE authnet_batch SET x_Amount = '$x_Amount' WHERE x_Invoice_Num = $invoice_id",$dbh)) { echo mysql_errno(). ": ".mysql_error(). "<br>"; return; }
        }

        ## client_register entry
        $reg_desc = PAYPALPAYMENT.": $trans_id";
        $reg_payment = $invoice_amount_paid;
        $this_invoice = mysql_one_array("SELECT * FROM client_invoice WHERE invoice_id = $invoice_id");
        register_insert($this_invoice[client_id],$reg_desc,$invoice_id,NULL,$reg_payment);

        ## send manual email
        if ( ( $override_email ) ||
             ( $send_client_email && $manual_email_id ) ) {
            $email_id       = $manual_email_id;
            $email_type     = "invoice";
            $where          = "i.invoice_id = $invoice_id";
            $email_to[0]    = mysql_one_data("SELECT client_id FROM client_invoice WHERE invoice_id = $invoice_id");
            $email_cc       = $inv_email_cc;
            $email_priority = $inv_email_priority;
            $email_subject  = $inv_email_subject;
            $email_from     = $inv_email_from;
            $email_body     = "%%LEAVE_FOR_ORIGINAL_INVOICE_HERE%%";
            @send_email($email_to,$email_cc,$email_priority,$email_subject,$email_body,$email_from);
        }

}
else if (strcmp ($res, "INVALID") == 0) {
     $response = "Invalid";
     // log for manual investigation, this was an invalid post (need to do some logging here, maybe a 'invalid paypal transaction' email to admin?)
}
}
fclose ($fp);
}

/*

--> I am not sure this will be required. Commenting out for testing.

// assign posted variables to local variables, even though we're not using all of them at the moment.
$receiver_email = $HTTP_POST_VARS['receiver_email'];
$item_name = $HTTP_POST_VARS['item_name'];
$item_number = $HTTP_POST_VARS['item_number'];
$quantity = $HTTP_POST_VARS['quantity'];
$invoice = $HTTP_POST_VARS['invoice'];
$custom = $HTTP_POST_VARS['custom'];
$payment_status = $HTTP_POST_VARS['payment_status'];
$pending_reason = $HTTP_POST_VARS['pending_reason'];
$payment_date = $HTTP_POST_VARS['payment_date'];
$payment_gross = $HTTP_POST_VARS['payment_gross'];
$payment_fee = $HTTP_POST_VARS['payment_fee'];
$txn_id = $HTTP_POST_VARS['txn_id'];
$txn_type = $HTTP_POST_VARS['txn_type'];
$first_name = $HTTP_POST_VARS['first_name'];
$last_name = $HTTP_POST_VARS['last_name'];
$address_street = $HTTP_POST_VARS['address_street'];
$address_state = $HTTP_POST_VARS['address_state'];
$address_zip = $HTTP_POST_VARS['address_zip'];
$address_country = $HTTP_POST_VARS['address_country'];
$address_status = $HTTP_POST_VARS['address_status'];
$payer_email = $HTTP_POST_VARS['payer_email'];
$payer_status  = $HTTP_POST_VARS['payer_status'];
$payment_type = $HTTP_POST_VARS['payment_type'];
$notify_version = $HTTP_POST_VARS['notify_version'];
$verify_sign = $HTTP_POST_VARS['verify_sign'];

// Insert into paypal_transactions table
if ($txn_id != "") {
   $paypal_update_sql = "INSERT INTO paypal_transactions
                                VALUES(
                                '".mktime()."',
                                '$receiver_email',
                                '$item_name',
                                '$item_number',
                                '$quantity',
                                '$invoice',
                                '$custom',
                                '$payment_status',
                                '$pending_reason',
                                '$payment_date',
                                '$payment_gross',
                                '$payment_fee',
                                '$txn_id',
                                '$txn_type',
                                '$first_name',
                                '$last_name',
                                '$address_street',
                                '$address_city',
                                '$address_state',
                                '$address_zip',
                                '$address_country',
                                '$address_status',
                                '$payer_email',
                                '$payer_status',
                                '$payment_type',
                                '$notify_version',
                                '$verify_sign',
                                '$response'
                                )";
   @mysql_query($paypal_update_sql,$dbh);
}
*/

## Send back to login page.
header("location: $user_login_url");
?>