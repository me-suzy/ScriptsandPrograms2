<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: process.paypal.php,v $ - $Revision: 1.4 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
chdir('../');
define('ALLOW_LOGGED_OUT', true);
define('SKIP_POP', true);
define('SKIP_SKIN', true);
define('PAY_PROCESSOR', 'paypal');
$templatesused = '';
require_once('./global.php');
require_once('./includes/functions_subscription.php');
require_once('./includes/functions_smtp.php');

// Build the query to send back to PayPal
$query = 'cmd=_notify-validate';
foreach ($_POST as $key => $val) {
	$query .= "&$key=".urlencode($val);
}

// Verify that this request is genuine
$header = "POST /cgi-bin/webscr HTTP/1.0\r\nHost: www.paypal.com\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: ".strlen($query)."\r\n\r\n";
$fp = fsockopen('www.paypal.com', 80, $errno, $errstr, 30);
fwrite($fp, $header.$query);
$result = '';
while (!feof($fp)) {
	$result = strtoupper(fgets($fp, 1024));
	if ($result == 'VERIFIED') {
		break;
	}
}
fclose($fp);

// Verify that this is a HiveMail subscription and a valid one
if ($result != 'VERIFIED' or substr($_POST['item_number'], 0, 4) != 'hive' or ($_POST['txn_type'] != 'web_accept' and $_POST['txn_type'] != 'reversal')) {
	subscription_report_error(PAY_ERROR_BAD_REQUEST, $_POST['txn_id'], $_POST['payer_email']);
}

// Figure out what we want to do
if ($_POST['payment_status'] == 'Completed' and $_POST['txn_type'] == 'web_accept') {
	$event = 'payment';
} elseif ($_POST['payment_status'] == 'Canceled' and $_POST['txn_type'] == 'reversal') {
	$event = 'reversal';
} elseif ($_POST['payment_status'] == 'Refunded') {
	$event = 'refund';
} else {
	subscription_report_error(PAY_ERROR_BAD_REQUEST, $_POST['txn_id'], $_POST['payer_email']);
}

// Verify that this is a unique request
if (!subscription_is_unique($event, $_POST['txn_id'])) {
	subscription_report_error(PAY_ERROR_BAD_REQUEST, $_POST['txn_id'], $_POST['payer_email']);
}

// Process the transaction
$cartid = substr($_POST['item_number'], 5);
$cart = subscription_get_cart($cartid);
subscription_delete_cart($cartid);
$result = subscription_process($cart['planid'], $cart['userid'], $subscription, $event, $_POST['payment_gross'], PAY_PROCESSOR, $_POST['txn_type'] != 'reversal');
if ($result > 0) {
	subscription_log_action($subscription['subscriptionid'], $result);
	subscription_log_payment($cart['planid'], $cart['userid'], $subscription['subscriptionid'], $event, $_POST['payment_gross'], $_POST['txn_id'], $_POST);
	subscription_report_success($_POST['payer_email']);
} elseif ($event == 'payment') {
	subscription_report_error($result, $_POST['txn_id'], $_POST['payer_email']);
}

?>