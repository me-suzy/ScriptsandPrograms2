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
// | $RCSfile: subscription.php,v $ - $Revision: 1.6 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
require_once('../includes/functions_subscription.php');
cp_header(' &raquo; Subscriptions');
cp_nav('subsubs');

// ############################################################################
// Set the default cmd
default_var($cmd, 'search');

// ############################################################################
// Create a subscription
if ($_POST['cmd'] == 'insert') {
	$getuser = $DB_site->query_first('
		SELECT user.userid, user.usergroupid, subscription.subscriptionid
		FROM hive_user AS user
		LEFT JOIN hive_subscription AS subscription ON (user.userid = subscription.userid AND subscription.active = 1)
		WHERE user.username = "'.addslashes($ausername).'"
	');
	$plan = getinfo('plan', $planid);
	$userid = $getuser['userid'];
	if (!$getuser) {
		adminlog(0, false);
		cp_error('You have entered an invalid username.');
	} elseif ($getuser['subscriptionid']) {
		adminlog(0, false);
		cp_error('The user you selected is already subscribed to another plan.');
	} else {
		$result = subscription_process($planid, $userid, $subscription, 'payment', $plan['cost'] * $periods, $processor, true);
		subscription_log_action($subscription['subscriptionid'], $result, true);
		subscription_log_payment($planid, $userid, $subscription['subscriptionid'], 'payment', $plan['cost'] * $periods, subscription_make_unique($processor), array(), $processor, true);
		adminlog($subscription['subscriptionid'], true);
		cp_redirect('The subscription has been created.', "subscription.php?cmd=list&filter[esubscriptionid]=$subscription[subscriptionid]");
	}
}

// ############################################################################
// Create a subscription
if ($cmd == 'add') {
	adminlog();

	startform('subscription.php', 'insert', '', array('ausername' => 'username'));
	starttable('Create new subscription');

	$processors = array();
	foreach ($_processor_info as $proccode => $procinfo) {
		$processors["$proccode"] = $procinfo['name'];
	}
	$listsize = iif(count($processors) < 3, 3, count($processors));

	hiddenfield('planid', $planid);
	tableselect('Choose plan:', 'planid', 'plan', 0, '1 = 1', '', '', 'name');
	inputfield('Username:', 'ausername');
	selectbox('Payment processor:', 'processor', $processors);
	inputfield('Periods paid for:<br /><span class="cp_small">If the plan you choose requires a recurring payment.</span>', 'periods', '1');

	endform('Create subscription');
	endtable();
}

// ############################################################################
// Show all subscriptions
if ($cmd == 'list') {
	// Sort options
	$sortorder = strtolower($sortorder);
	if ($sortorder != 'asc') {
		$sortorder = 'desc';
	}
	switch ($sortby) {
		case 'processor':
		case 'planid':
		case 'active':
		case 'startdate':
		case 'lastpaydate':
			break;
		default:
			$sortby = 'startdate';
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
					if (substr($field, -4) == 'date') {
						$sqlwhere .= " AND subscription.$field < UNIX_TIMESTAMP('".addslashes($value)."')";
					} else {
						$sqlwhere .= " AND subscription.$field < '".intval($value)."'";
					}
					break;
				case 'm':
					if (substr($field, -4) == 'date') {
						$sqlwhere .= " AND subscription.$field > UNIX_TIMESTAMP('".addslashes($value)."')";
					} else {
						$sqlwhere .= " AND subscription.$field > '".intval($value)."'";
					}
					break;
				case 'e':
					$sqlwhere .= " AND subscription.$field = '".addslashes($value)."'";
					break;
				case 'i':
					$sqlwhere .= " AND subscription.$field IN ($value)";
					break;
				case 'c':
					$sqlwhere .= " AND subscription.$field LIKE '%".addslashes($value)."%'";
					break;
			}
		}
	}

	$pagenav = paginate('subscription', "subscription.php?cmd=list$link", "WHERE $sqlwhere");
	$subs = $DB_site->query("
		SELECT subscription.*, user.username, user.domain, user.realname, usergroup.title, usergroup.usergroupid, plan.name
		FROM hive_subscription AS subscription
		LEFT JOIN hive_user AS user USING (userid)
		LEFT JOIN hive_plan AS plan ON (subscription.planid = plan.planid)
		LEFT JOIN hive_usergroup AS usergroup ON (plan.usergroupid = usergroup.usergroupid)
		WHERE $sqlwhere
		ORDER BY $sortby $sortorder
		LIMIT ".($limitlower-1).", $perpage
	");

	if ($DB_site->num_rows($subs) < 1) {
		echo '<br />';
		starttable('', '400');
		textrow('No subscriptions found, please try some different terms.');
		endtable();
	} else {
		echo '<table width="90%"><tr>';
		$i = 0;
		while ($sub = $DB_site->fetch_array($subs)) {
			if ($i % 2 == 0 and $i != 0) {
				echo '</tr>';
				echo '<tr>';
			}
			echo '<td width="50%">';
			starttable('Subscription ID: '.$sub['subscriptionid'], '100%');
			tablerow(array('Subscribed user:<br /><span class="cp_small">(email address)</span>', "$sub[realname]<br />(<a href=\"user.php?cmd=edit&userid=$sub[userid]\">$sub[username]$sub[domain]</a>)"));
			tablerow(array('Plan:<br /><span class="cp_small">(user group)</span>', "<a href=\"plan.php?cmd=edit&planid=$sub[planid]\">$sub[name]</a><br />(<a href=\"usergroup.php?cmd=edit&usergroupid=$sub[usergroupid]\">$sub[title]</a>)"));
			tablerow(array('Subscription active:', iif($sub['active'], '<span class="cp_temp_orig">Yes</span>', '<span class="cp_temp_cust">No</span>')));
			tablerow(array('Payment processor:', $_processor_info["$sub[processor]"]['name']));
			tablerow(array('Number of payments:', $sub['payments'].' ('.iif($sub['payments'] > 0, "<a href=\"payment.php?cmd=list&filter[esubscriptionid]=$sub[subscriptionid]\">list</a> | ")."<a href=\"payment.php?cmd=add&subscriptionid=$sub[subscriptionid]\">add</a>)"));
			tablerow(array('Start date:', hivedate($sub['startdate'], getop('dateformat'))));
			tablerow(array('Last payment:', hivedate($sub['lastpaydate'], getop('dateformat'))));
			tablerow(array('Expires on:', iif($sub['expirydate'] == -1, 'Never', hivedate($sub['expirydate'], getop('dateformat')))));
			endtable();
			echo '</td>';
			$i++;
		}
		if ($i % 2 == 1) {
			echo '<td width="50%">&nbsp;</td>';
		}
		echo '</tr></table>';
	}

	$cmd = 'search';
}

// ############################################################################
// Display subscriptions by criteria
if ($cmd == 'search') {
	adminlog();

	$sortoptions = array(
		'processor' => 'Processor',
		'planid' => 'Plan',
		'active' => 'Status',
		'startdate' => 'Start date',
		'lastpaydate' => 'Last payment',
	);
	$activeoptions = array(
		'1' => 'Active',
		'0' => 'Not active',
	);
	if (!isset($filter['eactive'])) {
		$filter['eactive'] = -1;
	}
	$processors = array();
	foreach ($_processor_info as $proccode => $procinfo) {
		$processors["$proccode"] = $procinfo['name'];
	}

	startform('subscription.php', 'list');
	starttable('Find Subscriptions');
	textrow('Please choose below which subscriptions you\'d like to display.');
	selectbox('Payment processor is:', 'filter[eprocessor]', $processors, $filter['eprocessor'], 'any processor');
	tableselect('Plan is:', 'filter[eplanid]', 'plan', $filter['eplanid'], '1 = 1', 'any plan', '', 'name');
	selectbox('Status of subscription:', 'filter[eactive]', $activeoptions, $filter['eactive'], 'any status');
	datefield('Started after:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[mstartdate]', $filter['mstartdate']);
	datefield('Started before:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[lstartdate]', $filter['lstartdate']);
	datefield('Last payment after:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[mlastpaydate]', $filter['mlastpaydate']);
	datefield('Last payment before:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[llastpaydate]', $filter['llastpaydate']);
	inputfield('Subscriptions to display per page:', 'perpage', $perpage);
	selectbox('Sort subscriptions by:', 'sortby', $sortoptions, $sortby, '', '&nbsp;in&nbsp;<select name="sortorder" id="sortorder">
			<option value="desc"'.iif($sortorder == 'desc', 'selected="selected"').'>descending order</option>
			<option value="asc"'.iif($sortorder == 'asc', 'selected="selected"').'>ascending order</option>
		</select>');
	endform('Display Subscriptions');
	endtable();

	startform('subscription.php', 'add');
	starttable('', '450');
	endform('Post Manual Subscription');
	endtable();
}

cp_footer();
?>
