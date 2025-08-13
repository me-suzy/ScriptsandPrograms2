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
// | $RCSfile: payment.php,v $ - $Revision: 1.4 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
require_once('../includes/functions_subscription.php');
cp_header(' &raquo; Payments', ($cmd != 'getinfo' and $cmd != 'refund'), ($cmd != 'getinfo' and $cmd != 'refund'));
cp_nav('subpayments');

// ############################################################################
// Set the default cmd
default_var($cmd, 'search');

// ############################################################################
// Make payment as refunded
if ($cmd == 'refund') {
	$payment = $DB_site->query_first('
		SELECT payment.*, user.username, user.domain, user.realname, plan.name
		FROM hive_payment AS payment
		LEFT JOIN hive_user AS user USING (userid)
		LEFT JOIN hive_plan AS plan ON (payment.planid = plan.planid)
		WHERE paymentid = '.intme($paymentid).'
	');
	if (!$payment) {
		adminlog($paymentid, false);
		cp_error('Invalid paymentid specified.');
	}

	$result = subscription_process($payment['planid'], $payment['userid'], $subscription, 'refund', $payment['amount'], $payment['processor'], true);
	subscription_log_action($subscription['subscriptionid'], $result, true);
	subscription_log_payment($payment['planid'], $payment['userid'], $subscription['subscriptionid'], 'refund', $payment['amount'], iif(empty($payment['remoteid']), 'N/A', $payment['remoteid']), array(), $payment['processor'], true);

	adminlog($paymentid, true);
	$cmd = 'getinfo';
}

// ############################################################################
// Show payment information
if ($cmd == 'getinfo') {
	$payment = $DB_site->query_first('
		SELECT payment.*, user.username, user.domain, user.realname, plan.name, usergroup.title
		FROM hive_payment AS payment
		LEFT JOIN hive_user AS user USING (userid)
		LEFT JOIN hive_plan AS plan ON (payment.planid = plan.planid)
		LEFT JOIN hive_usergroup AS usergroup ON (plan.usergroupid = usergroup.usergroupid)
		WHERE paymentid = '.intme($paymentid).'
	');
	if (!$payment) {
		adminlog($paymentid, false);
		cp_error('Invalid paymentid specified.');
	} else {
		adminlog($paymentid);
	}
	$payment['debuginfo'] = unserialize($payment['debuginfo']);

	echo "<div align=\"center\">\n";
	starttable('Payment Information');
	tablerow(array('Subscribed user:<br /><span class="cp_small">(email address)</span>', "$payment[realname]<br />(<a href=\"user.php?cmd=edit&userid=$payment[userid]\" target=\"_blank\">$payment[username]$payment[domain]</a>)"), true);
	tablerow(array('Plan:<br /><span class="cp_small">(user group)</span>', "<a href=\"plan.php?cmd=edit&planid=$payment[planid]\" target=\"_blank\">$payment[name]</a><br />(<a href=\"usergroup.php?cmd=edit&usergroupid=$payment[usergroupid]\" target=\"_blank\">$payment[title]</a>)"), true);
	tablerow(array('Processor:', $_processor_info["$payment[processor]"]['name']), true);
	tablerow(array('Payment ID:', $payment['remoteid']), true);
	tablerow(array('Amount:', '$'.$payment['amount']), true);
	tablerow(array('Payment date and time:', hivedate($payment['dateline'], getop('dateformat').' '.getop('timeformat'))), true);
	if ($payment['refunddate'] > 0) {
		tablerow(array('Refunded:', hivedate($payment['refunddate'], getop('dateformat').' '.getop('timeformat'))), true);
	} else {
		tablerow(array('Refund:', 'click '.makelink('here', "payment.php?cmd=refund&paymentid=$paymentid").' to mark this payment as refunded'), true);
	}
	if ($payment['pseudo']) {
		textrow('Pseudo payment: <span class="cp_small">this payment was manually posted by an admin.</span>', 2);
	}
	if (!empty($payment['debuginfo']) and is_array($payment['debuginfo'])) {
		tablehead(array('Debug information'), 2);
		foreach ($payment['debuginfo'] as $key => $value) {
			tablerow(array($key, $value), true, false, false, false);
		}
	}
	endtable();
	echo '</div>';
}

// ############################################################################
// Create a payment
if ($_POST['cmd'] == 'insert') {
	if ($payment['amount'] <= 0) {
		adminlog($payment['subscriptionid'], false);
		cp_error('The payment amount you entered is invalid.');
	} elseif (!empty($payment['remoteid']) and !subscription_is_unique('payment', $payment['remoteid'], $payment['processor'])) {
		adminlog($payment['subscriptionid'], false);
		cp_error('The payment ID you entered is not unique.');
	} else {
		if (empty($payment['remoteid'])) {
			$payment['remoteid'] = subscription_make_unique($payment['processor']);
		}
		$result = subscription_process($payment['planid'], $payment['userid'], $subscription, 'payment', $payment['amount'], $payment['processor'], true);
		subscription_log_action($subscription['subscriptionid'], $result, true);
		subscription_log_payment($payment['planid'], $payment['userid'], $subscription['subscriptionid'], 'payment', $payment['amount'], iif(empty($payment['remoteid']), 'N/A', $payment['remoteid']), array(), $payment['processor'], true);
		adminlog($subscription['subscriptionid'], true);
		cp_redirect('The payment has been recorded.', "subscription.php?cmd=list&filter[esubscriptionid]=$subscription[subscriptionid]");
	}
}

// ############################################################################
// Create a payment
if ($cmd == 'add') {
	$sub = $DB_site->query_first('
		SELECT subscription.*, user.username, user.domain, user.realname, usergroup.title, usergroup.usergroupid, plan.name, plan.cost
		FROM hive_subscription AS subscription
		LEFT JOIN hive_user AS user USING (userid)
		LEFT JOIN hive_plan AS plan ON (subscription.planid = plan.planid)
		LEFT JOIN hive_usergroup AS usergroup ON (plan.usergroupid = usergroup.usergroupid)
		WHERE subscriptionid = '.intme($subscriptionid).'
	');
	if (!$sub) {
		adminlog($subscriptionid, false);
		cp_error('Invalid subscriptionid specified.');
	} else {
		adminlog($subscriptionid);
	}

	startform('payment.php', 'insert');
	starttable('Create new payment');

	$processors = array();
	foreach ($_processor_info as $proccode => $procinfo) {
		$processors["$proccode"] = $procinfo['name'];
	}

	hiddenfield('payment[planid]', $sub['planid']);
	hiddenfield('payment[userid]', $sub['userid']);
	hiddenfield('payment[subscriptionid]', $sub['subscriptionid']);
	tablerow(array('Subscribed user:<br /><span class="cp_small">(email address)</span>', "$sub[realname]<br />(<a href=\"user.php?cmd=edit&userid=$sub[userid]\">$sub[username]$sub[domain]</a>)"), true);
	tablerow(array('Plan:<br /><span class="cp_small">(user group)</span>', "<a href=\"plan.php?cmd=edit&planid=$sub[planid]\">$sub[name]</a><br />(<a href=\"usergroup.php?cmd=edit&usergroupid=$sub[usergroupid]\">$sub[title]</a>)"), true);
	inputfield('Payment ID:<br /><span class="cp_small">A custom, unique ID will be automatically given if you leave this empty.</span>', 'payment[remoteid]');
	selectbox('Payment processor:', 'payment[processor]', $processors, $sub['processor']);
	inputfield('Payment amount:<br /><span class="cp_small">In U.S. Dollars.</span>', 'payment[amount]', $sub['cost']);

	endform('Create payment');
	endtable();
}

// ############################################################################
// Show all payments
if ($cmd == 'list') {
	// Sort options
	$sortorder = strtolower($sortorder);
	if ($sortorder != 'asc') {
		$sortorder = 'desc';
	}
	switch ($sortby) {
		case 'processor':
		case 'planid':
		case 'dateline':
		case 'refunddate':
		case 'amount':
			break;
		default:
			$sortby = 'dateline';
	}

	$sqlwhere = '1 = 1';
	$link = "&sortby=$sortby&sortorder=$sortorder";
	if (is_array($filter)) {
		foreach ($filter as $subject => $value) {
			$value = trim($value);
			if ((!is_numeric($value) and empty($value)) or $value == -1) {
				continue;
			}

			$field = substr($subject, 1);
			$link .= "&filter[$subject]=".urlencode($value);

			switch (substr($subject, 0, 1)) {
				case 'l':
					if (substr($field, -4) == 'date' or substr($field, 0, 4) == 'date') {
						$sqlwhere .= " AND payment.$field < UNIX_TIMESTAMP('".addslashes($value)."')";
					} else {
						$sqlwhere .= " AND payment.$field < '".intval($value)."'";
					}
					break;
				case 'm':
					if (substr($field, -4) == 'date' or substr($field, 0, 4) == 'date') {
						$sqlwhere .= " AND payment.$field > UNIX_TIMESTAMP('".addslashes($value)."')";
					} else {
						$sqlwhere .= " AND payment.$field > '".intval($value)."'";
					}
					break;
				case 'e':
					$sqlwhere .= " AND payment.$field = '".addslashes($value)."'";
					break;
				case 'i':
					$sqlwhere .= " AND payment.$field IN ($value)";
					break;
				case 'c':
					$sqlwhere .= " AND payment.$field LIKE '%".addslashes($value)."%'";
					break;
				case 'b':
					if ($value == 1) {
						$sqlwhere .= " AND payment.$field > 0";
					} elseif ($value == 0) {
						$sqlwhere .= " AND payment.$field = 0";
					}
					break;
			}
		}
	}

	?><script language="JavaScript">
	<!--
	function moreinfo(paymentid) {
		window.open('payment.php?cmd=getinfo&paymentid='+paymentid, 'moreInfo'+paymentid, 'width=600,height=400,resizable=yes,scrollbars=yes');
	}
	// -->
	</script><?php

	$pagenav = paginate('payment', "payment.php?cmd=list$link", "WHERE $sqlwhere");
	$payments = $DB_site->query("
		SELECT payment.*, user.username, user.domain, user.realname, plan.name
		FROM hive_payment AS payment
		LEFT JOIN hive_user AS user USING (userid)
		LEFT JOIN hive_plan AS plan ON (payment.planid = plan.planid)
		WHERE $sqlwhere
		ORDER BY $sortby $sortorder
		LIMIT ".($limitlower-1).", $perpage
	");
	starttable();
	$cells = array(
		'ID',
		'User (Plan)',
		'Processor',
		'Amount',
		'Date (Refunded)',
		'Details',
	);
	tablehead($cells);
	if ($DB_site->num_rows($payments) < 1) {
		textrow('No payment found, try some different terms.', count($cells), 1);
	} else {
		while ($payment = $DB_site->fetch_array($payments)) {
			$cells = array(
				$payment['paymentid'],
				"<a href=\"user.php?cmd=edit&userid=$payment[userid]\">$payment[username]$payment[domain]</a><br /><a href=\"plan.php?cmd=edit&planid=$payment[planid]\">$payment[name]</a>",
				$_processor_info["$payment[processor]"]['name'],
				'$'.$payment['amount'],
				hivedate($payment['dateline'], getop('dateformat')).'<br />('.iif($payment['refunddate'] > 0, hivedate($payment['refunddate'], getop('dateformat')), 'No').')',
				"[<a href=\"#\" onClick=\"moreinfo($payment[paymentid]); return false;\">details</a>]",
			);
			tablerow($cells);
		}
	}
	tablehead(array("$pagenav&nbsp;"), count($cells));
	endtable();

	$cmd = 'search';
}

// ############################################################################
// Display payments by criteria
if ($cmd == 'search') {
	adminlog();

	$sortoptions = array(
		'processor' => 'Processor',
		'planid' => 'Plan',
		'dateline' => 'Payment date',
		'refunddate' => 'Refund date',
		'amount' => 'Payment amount',
	);
	$refundoptions = array(
		'1' => 'Refunded',
		'0' => 'Not refunded',
	);
	if (!isset($filter['brefunddate'])) {
		$filter['brefunddate'] = -1;
	}
	$processors = array();
	foreach ($_processor_info as $proccode => $procinfo) {
		$processors["$proccode"] = $procinfo['name'];
	}

	startform('payment.php', 'list');
	starttable('Find Payments');
	textrow('Please choose below which payments you\'d like to display.');
	selectbox('Payment processor is:', 'filter[eprocessor]', $processors, $filter['eprocessor'], 'any processor');
	tableselect('Plan is:', 'filter[eplanid]', 'plan', $filter['eplanid'], '1 = 1', 'any plan', '', 'name');
	selectbox('Payment refunded:', 'filter[brefunddate]', $refundoptions, $filter['brefunddate'], 'doesn\'t matter');
	inputfield('Payment ID contains:', 'filter[cremoteid]', $filter['cremoteid']);
	datefield('Paid after:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[mdateline]', $filter['mdateline']);
	datefield('Paid before:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[ldateline]', $filter['ldateline']);
	datefield('Refunded after:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[mrefunddate]', $filter['mrefunddate']);
	datefield('Refunded before:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[lrefunddate]', $filter['lrefunddate']);
	inputfield('Payments to display per page:', 'perpage', $perpage);
	selectbox('Sort payments by:', 'sortby', $sortoptions, $sortby, '', '&nbsp;in&nbsp;<select name="sortorder" id="sortorder">
			<option value="desc"'.iif($sortorder == 'desc', 'selected="selected"').'>descending order</option>
			<option value="asc"'.iif($sortorder == 'asc', 'selected="selected"').'>ascending order</option>
		</select>');
	endform('Display Payments');
	endtable();
}

cp_footer($cmd != 'getinfo' and $cmd != 'refund');
?>
