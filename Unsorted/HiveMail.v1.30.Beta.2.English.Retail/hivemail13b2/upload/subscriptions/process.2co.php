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
// | $RCSfile: process.2co.php,v $ - $Revision: 1.2 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
chdir('../');
define('ALLOW_LOGGED_OUT', true);
define('SKIP_POP', true);
define('PAY_PROCESSOR', '2co');
$templatesused = '';
require_once('./global.php');
require_once('./includes/functions_subscription.php');
require_once('./includes/functions_smtp.php');

// Verify that this request is genuine
$verify_key = strtoupper(md5($_processor_info['2co']['secretkey'].$_processor_info['2co']['sellerid'].$_GET['order_number'].$_GET['total']));

// Verify that this is a HiveMail subscription and a valid one
if ($verify_key != $_GET['key'] or substr($_GET['cart_order_id'], 0, 4) != 'hive') {
	subscription_report_error(PAY_ERROR_BAD_REQUEST, $_GET['order_number']);
} elseif (strtolower($_GET['demo']) == 'y') {
	subscription_report_error(PAY_ERROR_DEMO_MODE, $_GET['order_number']);
} elseif (strtolower($_GET['credit_card_processed']) != 'y') {
	subscription_report_error(PAY_ERROR_CC_NOTPROCESSED, $_GET['order_number']);
}

// Verify that this is a unique request
if (!subscription_is_unique('payment', $_GET['order_number'])) {
	subscription_report_error(PAY_ERROR_BAD_REQUEST, $_GET['order_number']);
}

// Process the transaction
$cartid = substr($_GET['cart_order_id'], 5);
$cart = subscription_get_cart($cartid);
subscription_delete_cart($cartid);
$result = subscription_process($cart['planid'], $cart['userid'], $subscription, $event, $_GET['total'], PAY_PROCESSOR, true);
if ($result > 0) {
	subscription_log_action($subscription['subscriptionid'], $result);
	subscription_log_payment($cart['planid'], $cart['userid'], $subscription['subscriptionid'], $event, $_GET['total'], $_GET['order_number'], $_POST);
	subscription_report_success();
} else {
	subscription_report_error($result, $_GET['order_number']);
}

?>