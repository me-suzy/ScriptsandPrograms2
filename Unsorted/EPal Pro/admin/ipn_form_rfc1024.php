<?
/* SET IPN TO HTTP://WWW.SITE_NAME.COM/ADMIN/IPN_FORM_RFC1024.PHP */
/* http://test.baseheadgames.com/admin/receive_ipn.php?payer_email=test@baseheadgames.com&payment_status=Completed&payment_gross=108.89&payment_fee=5&txn_id=testing123 */
require("include.inc.php");
if ($payment_status == 'Completed') {
 $check = mysql_fetch_array(mysql_query("select * from transactions where s_email='$payer_email' and base_total = '$payment_gross'")); 
 if ($check[id] > 0) {
  mysql_query("update transactions set pp_status='$payment_status',pp_amount='$payment_gross', pp_fee='$payment_fee', pp_trans_id='$txn_id' where id=$check[id]")or die(mysql_error());
  $subject = "Payment Confirmation";
  $message = "Process Payment for transaction ID #$txn_id\nPayment Amount: $payment_gross\nSend By: $check[ship_method]\nSend to:\n$check[r_name]\n$check[r_address]\n$check[r_city]\n$check[r_state]\n$check[r_zip]\n$check[r_country]\n\nFrom:\n$check[s_name] ($check[s_email])\n$check[s_address]\n$check[s_city]\n$check[s_state]\n$check[s_zip]\n$check[s_country]\n$check[s_phone]\n\nExtras:\nAuction Info: $check[o_auction_site]\nAuction Item Info: $check[o_item_num]\nSender Auction ID: $check[o_id]\nAuction Description: $check[o_description]\n\nVERIFY ALL PAYMENTS FROM YOUR PAYPAL ACCOUNT PRIOR TO SUBMITTING MONEY ORDERS";
  $extra = "From: system@site-name.com";
  mail('webmaster@baseheadgames.com', $subject, $message, $extra);
  $subject = "Payment Confirmation";
  $message = "Your payment has been successfully processed for your money order transaction.\n\nTo check your order, simply use your PayPal tracking number: #$txn_id\n\nThis is a system message, please do not respond.";
  $extra = "From: system@site-name.com";
  mail($check[s_email], $subject, $message, $extra);
 }
}
?>