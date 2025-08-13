<?
#########################################################
#                                                       #
#         ModernBill .:. Client Billing System          #
#  Copyright © 2001 ModernBill   All Rights Reserved.   #
#                                                       #
#########################################################
if ($rawAuthCode=="C" || $transId=="")  { exit(); }
if ($REMOTE_ADDR!="195.35.90.61" && $REMOTE_ADDR!="195.35.90.62") { exit(); }

$DIR = "../../";
include($DIR."include/functions.inc.php");

## Connect to the DB
GLOBAL $dbh;
if (!$dbh) dbconnect();

if ($wp_return_pw == $callbackPW) {

        $invoice_amount_paid = ($amount) ? $amount : 0 ;
        $invoice_id = $cartId;
        $trans_id = $transId;

        ## update the invoice
        $pay_update_sql = "UPDATE client_invoice
                           SET invoice_amount_paid=invoice_amount_paid+$invoice_amount_paid,
                           invoice_date_paid='".mktime()."',
                           invoice_payment_method='$worldpay_id',
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
        $reg_desc = WORLDPAYPAYMENT.": $trans_id";
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

## Send back to login page.
header("location: $user_login_url");
?>