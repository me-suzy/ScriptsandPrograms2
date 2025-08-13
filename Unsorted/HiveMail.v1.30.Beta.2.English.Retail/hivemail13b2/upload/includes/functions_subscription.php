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
// | $RCSfile: functions_subscription.php,v $ - $Revision: 1.8 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Process a payment for a subscription
function subscription_process($planid, $userid, &$subscription, $event, $paid, $processor = PAY_PROCESSOR, $allowskip = true) {
	global $DB_site;

	// Get information about the plan, user and possibly subscription
	$plan = getinfo('plan', $planid);
	$user = getinfo('user', $userid);
	$subscription = $DB_site->query_first("
		SELECT *
		FROM hive_subscription
		WHERE planid = $planid AND userid = $userid
	");

	// See how many periods were paid for
	$periods = floor($paid / $plan['cost']);
	if ($periods > 1 and !$plan['canpayadvance']) {
		$periods = 1;
	}

	// Payment has been completed or reversed back to the merchant
	if ($event == 'payment' or $event == 'reversal') {
		// Make sure the user isn't already subscribed to some plan
		if ($DB_site->query_first("SELECT subscriptionid FROM hive_subscription WHERE userid = $userid AND planid <> $planid AND active = 1")) {
			return PAY_ERROR_ANOTHER_SUB;
		}

		// Didn't pay enough for even one period, contact the admin
		if ($periods < 1) {
			return PAY_ERROR_NOT_ENOUGH;
		}

		// Renewing an old subscription or creating a new one?
		if ($subscription) {
			$newdate = subscription_extend($plan, $user, $subscription, $periods, $allowskip);
			return PAY_STATUS_EXTENDED;
		} else {
			$newdate = subscription_create($plan, $user, $subscription, $periods, $processor, $allowskip);
			return PAY_STATUS_CREATED;
		}
	}

	// Payment was refunded by the merchant
	if ($event == 'refund') {
		// Subscription doesn't exist anyway
		if (!$subscription) {
			return PAY_ERROR_NEVER_MIND;
		}

		// Payment never gave any periods
		if ($periods < 1) {
			return PAY_ERROR_NEVER_MIND;
		}

		// Shorten the subscription and return the correct code
		$newdate = subscription_shorten($plan, $user, $subscription, $periods);
		if ($newdate >= $subscription['startdate']) {
			return PAY_STATUS_SHORTENED;
		} else {
			return PAY_STATUS_CANCELLED;
		}
	}
}

// ############################################################################
// Create a new cart
function subscription_create_cart($planid, $userid) {
	global $DB_site;

	$DB_site->query("
		INSERT INTO hive_cart
		(cartid, planid, userid, dateline)
		VALUES (NULL, $planid, $userid, ".TIMENOW.")
	");
	return $DB_site->insert_id();
}

// ############################################################################
// Get information about a payment from the cart
function subscription_get_cart(&$cartid) {
	global $DB_site;

	$cart = $DB_site->query_first('
		SELECT *
		FROM hive_cart
		WHERE cartid = '.intme($cartid).'
	');
	if (!$cart) {
		return PAY_ERROR_INVALID_CART;
	} else {
		return $cart;
	}
}

// ############################################################################
// Delete cart data
function subscription_delete_cart($cartid) {
	global $DB_site;

	$DB_site->query("
		DELETE FROM hive_cart
		WHERE cartid = $cartid
	");
}

// ############################################################################
// Log an action on a subscription
function subscription_log_action($subscriptionid, $action, $pseudo = false) {
	global $DB_site;

	$DB_site->query("
		INSERT INTO hive_subscriptionlog
		(subscriptionid, action, dateline, pseudo)
		VALUES ($subscriptionid, $action, ".TIMENOW.", ".((int) $pseudo).")
	");
}

// ############################################################################
// Finds a unique payment ID for a pseudo payment
function subscription_make_unique($processor = PAY_PROCESSOR, $length = 16) {
	do {
		$remoteid = substr(md5(microtime()), 0, $length);
	} while (!subscription_is_unique('payment', $remoteid, $processor));

	return $remoteid;
}

// ############################################################################
// Log an action on a subscription
function subscription_is_unique($event, $remoteid, $processor = PAY_PROCESSOR) {
	global $DB_site;

	// Check if payment already exists
	$payment = $DB_site->query_first("
		SELECT *
		FROM hive_payment
		WHERE remoteid = '".addslashes($remoteid)."' AND processor = '".addslashes($processor)."'
	");

	// Now see if it's normal to expect another transaction with the same ID
	switch ($event) {
		case 'payment':
			return (!isset($payment['paymentid']));
			break;
		case 'reversal':
			return ($payment and $payment['refunddate'] != 0);
			break;
		case 'refund':
			return ($payment and $payment['refunddate'] == 0);
			break;
	}
}

// ############################################################################
// Log an action on a subscription
function subscription_log_payment($planid, $userid, $subscriptionid, $event, $amount, $remoteid, $debuginfo, $processor = PAY_PROCESSOR, $pseudo = false) {
	global $DB_site;

	// Either create a new payment or update an existing record
	switch ($event) {
		case 'payment':
			$DB_site->query("
				INSERT INTO hive_payment
				(paymentid, planid, userid, subscriptionid, processor, dateline, remoteid, amount, debuginfo, pseudo)
				VALUES
				(NULL, $planid, $userid, $subscriptionid, '".addslashes($processor)."', ".TIMENOW.", '".addslashes($remoteid)."', ".floatme($amount).", '".addslashes(serialize($debuginfo))."', ".((int) $pseudo).")
			");
			$newpay = '+ 1';
			$lastpaydate = TIMENOW;
			break;

		default:
			if ($event == 'reversal') {
				$refunddate = 0;
				$newpay = '+ 1';
			} elseif ($event == 'refund') {
				$refunddate = TIMENOW;
				$newpay = '- 1';
			}
			$lastpaydate = 'lastpaydate';
			$DB_site->query("
				UPDATE hive_payment
				SET refunddate = $refunddate
				WHERE remoteid = '".addslashes($remoteid)."' AND processor = '".addslashes($processor)."'
			");
	}

	// Update the payments count for the subscription
	$DB_site->query("
		UPDATE hive_subscription
		SET payments = payments $newpay, lastpaydate = $lastpaydate
		WHERE subscriptionid = $subscriptionid
	");
}

// ############################################################################
// Create a new subscription
function subscription_create($plan, $user, &$subscription, $periods, $processor = PAY_PROCESSOR, $allowskip = true) {
	global $DB_site;

	// Create the new subscription
	$DB_site->query("
		INSERT INTO hive_subscription
		(subscriptionid, planid, userid, oldusergroup, processor, active, startdate)
		VALUES
		(NULL, $plan[planid], $user[userid], $user[usergroupid], '".addslashes($processor)."', 0, ".TIMENOW.")
	");

	// Extend the subscription for the first time
	$subscription = array(
		'subscriptionid' => $DB_site->insert_id(),
		'expirydate' => -1,
		'active' => 0,
	);
	return subscription_extend($plan, $user, $subscription, $periods, $allowskip);
}

// ############################################################################
// Extend a subscription
function subscription_extend($plan, $user, &$subscription, $periods, $allowskip = true) {
	global $DB_site;

	// Extend the expiry date
	if ($plan['length'] == 0) {
		$subscription['expirydate'] = -1;
	} else {
		$units = array('d' => 'days', 'w' => 'weeks', 'm' => 'months', 'y' => 'years');
		if ($allowskip and $subscription['expirydate'] < TIMENOW) {
			$subscription['expirydate'] = TIMENOW;
		}
		$timeoffset = $subscription['expirydate'] - strtotime(date('Y-m-d', $subscription['expirydate']).' 00:00:00');
		$subscription['expirydate'] = strtotime(date('Y-m-d', $subscription['expirydate']).' +'.($plan['length'] * $periods).' '.$units[$plan['unit']]) + $timeoffset;
	}
	$subscription['active'] = 1;

	// Update subscription information
	$DB_site->query("
		UPDATE hive_subscription
		SET expirydate = $subscription[expirydate],
			active = $subscription[active]
		WHERE subscriptionid = $subscription[subscriptionid]
	");

	// Update the user's account... maybe the admin changed the subscription information or something
	$DB_site->query("
		UPDATE hive_user
		SET usergroupid = $plan[usergroupid], subexpiry = $subscription[expirydate]
		WHERE userid = $user[userid]
	");

	// Return the new expiry date
	return $subscription['expirydate'];
}

// ############################################################################
// Shorten a subscription
function subscription_shorten($plan, $user, &$subscription, $periods) {
	global $DB_site;

	// Shorten the expiry date
	if ($plan['length'] == 0) {
		$subscription['expirydate'] = -1;
	} else {
		$units = array('d' => 'days', 'w' => 'weeks', 'm' => 'months', 'y' => 'years');
		$timeoffset = $subscription['expirydate'] - strtotime(date('Y-m-d', $subscription['expirydate']).' 00:00:00');
		$subscription['expirydate'] = strtotime(date('Y-m-d', $subscription['expirydate']).' -'.($plan['length'] * $periods).' '.$units[$plan['unit']]) + $timeoffset;
	}
	$active = ($subscription['expirydate'] > TIMENOW);

	// Update subscription information
	$DB_site->query("
		UPDATE hive_subscription
		SET expirydate = $subscription[expirydate],
			active = ".((int) $active)."
		WHERE subscriptionid = $subscription[subscriptionid]
	");

	// If the subscription is now disabled reset the user's account
	if (!$active) {
		subscription_reset_usergroup($user, $subscription);
	} else {
		$DB_site->query("
			UPDATE hive_user
			SET subexpiry = $subscription[expirydate]
			WHERE userid = $user[userid]
		");
	}
	$subscription['active'] = $active;

	// Return the new expiry date
	return $subscription['expirydate'];
}

// ############################################################################
// Cancel a subscription
function subscription_cancel($user, &$subscription) {
	global $DB_site;

	// Reset the user's account
	subscription_reset_usergroup($user, $subscription);

	// Disable the subscription
	$subscription['active'] = false;
	$DB_site->query("
		UPDATE hive_subscription
		SET expirydate = ".TIMENOW.",
			active = ".((int) $subscription['active'])."
		WHERE subscriptionid = $subscription[subscriptionid]
	");
}

// ############################################################################
// Reset a user's membership
function subscription_reset_usergroup($user, &$subscription) {
	global $DB_site;

	// Reset the user's account
	$DB_site->query("
		UPDATE hive_user
		SET usergroupid = ".subscription_getnew_usergroup($user, $subscription).", subexpiry = 0
		WHERE userid = $user[userid]
	");
}

// ############################################################################
// Get the new usergroup for a user
function subscription_getnew_usergroup($user, &$subscription) {
	global $DB_site;

	// See if the user is already subscribed to some other plan
	$latestsubscription = $DB_site->query_first("
		SELECT plan.usergroupid
		FROM hive_subscription AS subscription
		LEFT JOIN hive_plan AS plan USING (planid)
		WHERE userid = $user[userid] AND subscriptionid <> $subscription[subscriptionid] AND active = 1
		ORDER BY expirydate DESC
		LIMIT 1
	");

	// Try to reset his usergroup to the one specified in the latest subscription
	if ($latestsubscription) {
		$newusergroupid = $latestsubscription['usergroupid'];
	} else {
		$newusergroupid = $subscription['oldusergroup'];
	}

	return $newusergroupid;
}

// ############################################################################
// Check all subscriptions and expire them
function subscription_check_expiration() {
	global $DB_site;

	$subscriptions = $DB_site->query('
		SELECT *
		FROM hive_subscription
		WHERE expirydate < '.TIMENOW.' AND active = 1
	');
	$newusergroups = array();
	while ($subscription = $DB_site->fetch_array($subscriptions)) {
		$newusergroups[subscription_getnew_usergroup(array('userid' => $subscription['userid']), $subscription)][] = $subscription['userid'];
	}
	foreach ($newusergroups as $usergroupid => $userids) {
		$DB_site->query("
			UPDATE hive_user
			SET usergroupid = $usergroupid, subexpiry = 0
			WHERE userid IN (".implode(', ', $userids).")
		");
	}
	$DB_site->query('
		UPDATE hive_subscription
		SET active = 0
		WHERE expirydate < '.TIMENOW.'
	');

	// Update last run time
	$DB_site->query('UPDATE hive_setting SET value = '.TIMENOW.' WHERE variable = "subs_lastcheck"');
}

// ############################################################################
// Creates the HTML form for the given processor
function subscription_build_html($processor, $cartid, $cost, $planname) {
	$appurl = getop('appurl');

	$html = '<form action="'.$processor['form']['action'].'" method="'.$processor['form']['method'].'" style="margin: 0px;">';
	foreach ($processor['form']['fields'] as $name => $value) {
		$iscost = ($value == '$cost');
		eval('$value = "'.$value.'";');
		$html .= '<input type="hidden" name="'.$name.'" value="'.$value.'"'.iif($iscost, ' id="payform_cost_field"').' />';
	}

	return $html;
}

// ############################################################################
// Sends an email with the confirmation to the user (or show it on the page he sees)
function subscription_report_success($email = '') {
	$appname = getop('appname');
	if (empty($email)) {
		eval(makeredirect('redirect_subscription_payment', '../options.subscription.php'));
	} else {
		eval(makeevalsystem('subject', 'error_subs_procerror_subject'));
		eval(makeevalsystem('body', 'redirect_subscription_payment'));
		smtp_mail($email, $subject, $body, 'From: '.getop('smtp_errorfrom'));
	}
	exit;
}

// ############################################################################
// Sends an email with the error description to the user (or show it on the page he sees)
function subscription_report_error($errornum, $remoteid, $email = '', $processor = PAY_PROCESSOR) {
	global $_processor_info;

	$makeeval = 'makeeval'.iif(!empty($email), 'system');
	$processorname = $_processor_info["$processor"]['name'];
	$appname = getop('appname');
	$datetime = date('F j, Y, g:i a');
	eval($makeeval('errortext', 'error_subs_procerror_'.subscription_translate_error($errornum)));

	if (empty($email)) {
		eval(makeerror('error_subs_procerror'));
	} else {
		eval(makeevalsystem('subject', 'error_subs_procerror_subject'));
		eval(makeevalsystem('body', 'error_subs_procerror'));
		smtp_mail($email, $subject, $body, 'From: '.getop('smtp_errorfrom'));
	}
	exit;
}

// ############################################################################
// Returns the error code that corresponds to the given error number
function subscription_translate_error($errornum) {
	switch ($errornum) {
		case PAY_ERROR_DEMO_MODE:
			return 'demo_mode';
		case PAY_ERROR_BAD_REQUEST:
			return 'bad_request';
		case PAY_ERROR_CC_NOTPROCESSED:
			return 'cc_notprocessed';
		case PAY_ERROR_INVALID_CART:
			return 'invalid_cart';
		case PAY_ERROR_NEVER_MIND:
			return 'never_mind';
		case PAY_ERROR_NOT_ENOUGH:
			return 'not_enough';
		case PAY_ERROR_ANOTHER_SUB:
			return 'another_sub';
	}
}

?>