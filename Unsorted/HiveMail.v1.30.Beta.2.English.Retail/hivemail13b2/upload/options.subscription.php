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
// | $RCSfile: options.subscription.php,v $ - $Revision: 1.12 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'subscriptions_already_subscribed,subscriptions,subscriptions_planbit,subscriptions_processorbit,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');
require_once('./includes/functions_subscription.php');

// ############################################################################
// Do we even have plans to offer?
if ($DB_site->get_field('SELECT COUNT(*) AS count FROM hive_plan') == 0) {
	access_denied();
}

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'choose';
}

// ############################################################################
// Delete old search carts
$DB_site->query('
	DELETE FROM hive_cart
	WHERE dateline < '.(TIMENOW - (60*15))	// 15 minutes eh?
);

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('subscription');
$units = array('d' => 'day(s)', 'w' => 'week(s)', 'm' => 'month(s)', 'y' => 'year(s)');
$codeunits = array('d' => 'days', 'w' => 'weeks', 'm' => 'months', 'y' => 'years'); // DO NOT MODIFY
$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Subscription Options';

// ############################################################################
// See if the user is already subscribed
$cursub = $DB_site->query_first("
	SELECT plan.*, subscription.*
	FROM hive_subscription AS subscription
	LEFT JOIN hive_plan AS plan USING (planid)
	WHERE userid = $hiveuser[userid] AND active = 1
");

// ############################################################################
// Cancel the user's current subscription
if ($_POST['cmd'] == 'cancel') {
	if ($verify) {
		subscription_cancel($hiveuser, $cursub);
		subscription_log_action($cursub['subscriptionid'], PAY_STATUS_CANCELLED);
		eval(makeredirect('redirect_subscription_cancelled', "options.subscription.php"));
	} else {
		$_POST['cmd'] = '';
	}
}

// ############################################################################
// Show the screen to renew or cancel the current subscription
if ($_POST['cmd'] != 'cancel' and $_POST['cmd'] != 'renew' and $cursub) {
	$cursub['expires'] = hivedate($cursub['expirydate']);
	if ($cursub['length'] > 0 and $cursub['canpayadvance']) {
		$canrenew = true;
	} elseif ($cursub['length'] == 0) {
		$canrenew = false;
	} else {
		$timeoffset = $cursub['expirydate'] - strtotime(date('Y-m-d', $cursub['expirydate']).' 00:00:00');
		$lastperiod = strtotime(date('Y-m-d', $cursub['expirydate']).' -'.$cursub['length'].' '.$codeunits[$cursub['unit']]) + $timeoffset;
		$canrenew = ($lastperiod < TIMENOW);
	}
	$cursub['unit'] = $units["$cursub[unit]"];

	$payform = subscription_build_html($_processor_info["$cursub[processor]"], 'hive_'.subscription_create_cart($cursub['planid'], $hiveuser['userid']), $cursub['cost'], $cursub['name']);
	eval(makeeval('echo', 'subscriptions_already_subscribed'));
}

// ############################################################################
// Show the list of subscriptions for the user to choose from
if ($cmd == 'choose') {
	// Get all plans
	$getplans = $DB_site->query("
		SELECT *
		FROM hive_plan
	");
	$plans = '';
	while ($plan = $DB_site->fetch_array($getplans)) {
		$plan['unit'] = $units["$plan[unit]"];
		$plan['processors'] = explode(',', $plan['processors']);
		$plan = htmlchars($plan);
		$procselect = '';
		foreach ($_processor_info as $proccode => $procinfo) {
			if (array_contains($proccode, $plan['processors'])) {
				$procselect .= '<option value="'.$proccode.'">'.$procinfo['name'].'</option>';
			}
		}
		eval(makeeval('plans', 'subscriptions_planbit', true));
	}

	eval(makeeval('echo', 'subscriptions'));
}

// ############################################################################
// Show payment form
if ($cmd == 'payform') {
	$plan = getinfo('plan', $planid);
	if (!array_contains($processors[$planid], explode(',', $plan['processors']))) {
		invalid('processor');
	}
	$plan['unit'] = $units["$plan[unit]"];

	$payform = subscription_build_html($_processor_info["{$processors[$planid]}"], 'hive_'.subscription_create_cart($planid, $hiveuser['userid']), $plan['cost'], $plan['name']);
	eval(makeeval('echo', 'subscriptions_payment_form'));
}

// ############################################################################
// Show payment form
if ($cmd == 'thankyou') {
	eval(makeredirect('subscriptions_thankyou', "options.subscription.php"));
}

?>