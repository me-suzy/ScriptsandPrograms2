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
// | $RCSfile: plan.php,v $ - $Revision: 1.4 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
require_once('../includes/functions_subscription.php');
cp_header(' &raquo; Plans Manager');
cp_nav('subplans');

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');

// ############################################################################
// Remove plan
if ($_POST['cmd'] == 'kill') {
	$plan = getinfo('plan', $planid);

	$subscriptions = $DB_site->query("
		SELECT *
		FROM hive_subscription
		WHERE planid = $planid
	");
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

	$DB_site->query("
		DELETE FROM hive_plan
		WHERE planid = $planid
	");
	$DB_site->query("
		DELETE FROM hive_subscription
		WHERE planid = $planid
	");
	$DB_site->query("
		DELETE FROM hive_payment
		WHERE planid = $planid
	");

	adminlog($planid, true);
	cp_redirect('The plan was successfully removed.', 'plan.php');
}

// ############################################################################
// Remove plan
if ($cmd == 'remove') {
	$plan = getinfo('plan', $planid);

	adminlog($planid);
	startform('plan.php', 'kill', 'Are you sure you want to remove this plan?');
	starttable('Remove plan "'.$plan['name'].'" (ID: '.$planid.')', '450');
	textrow('Are you <b>sure</b> you want to remove this plan? This procedure <b>cannot</b> be reveresed! Users that are currently subscribed to this plan will be returned to their original user group.');
	hiddenfield('planid', $planid);
	endform('Remove Plan', '', 'Go Back');
	endtable();
}

// ############################################################################
// Create a new plan or update an existing one
if ($_POST['cmd'] == 'update') {
	if (empty($plan['name'])) {
		adminlog($planid, false);
		cp_error('The plan must have a name.');
	} elseif (empty($plan['description'])) {
		adminlog($planid, false);
		cp_error('The plan must have a description.');
	} elseif (empty($plan['processors'])) {
		adminlog($planid, false);
		cp_error('You must choose at least one processor.');
	} else {
		$plan['processors'] = implode(',', $plan['processors']);
		if ($plan_length_nolimit) {
			$plan['length'] = 0;
		}
		if ($planid == 0) {
			$DB_site->auto_query('plan', $plan);
			adminlog($planid, true);
			cp_redirect('The plan has been created.', 'plan.php');
		} else {
			$DB_site->auto_query('plan', $plan, "planid = $planid");
			adminlog($planid, true);
			cp_redirect('The plan has been updated.', 'plan.php');
		}
	}
}

// ############################################################################
// Create a new plan or update an existing one
if ($cmd == 'edit') {
	startform('plan.php', 'update', '', array('plan_name' => 'name'));

	$plan = getinfo('plan', $planid, false, false);
	adminlog($planid);
	if ($plan === false) {
		$plan = array(
			'usergroupid' => 2,
			'processors' => array(),
			'cost' => 0,
			'length' => 1,
			'unit' => 'm',
			'canpayadvance' => 1,
			'reminder' => 1,
		);
		$planid = 0;
		starttable('Create new plan');
	} else {
		$plan['processors'] = explode(',', $plan['processors']);
		starttable('Update plan "'.$plan['name'].'" (ID: '.$planid.')');
	}

	$lengthunits = array('d' => 'Day(s)', 'w' => 'Week(s)', 'm' => 'Month(s)', 'y' => 'Year(s)');
	$unit = '';
	foreach ($lengthunits as $unitvalue => $unitname) {
		$unit .= '<option value="'.$unitvalue.'"';
		if ($plan['unit'] == $unitvalue) {
			$unit .= ' selected="selected"';
		}
		$unit .= '>'.$unitname.'</option>';
	}

	$processors = array();
	foreach ($_processor_info as $proccode => $procinfo) {
		$processors["$proccode"] = $procinfo['name'];
	}
	$listsize = iif(count($processors) < 3, 3, count($processors));

	hiddenfield('planid', $planid);
	inputfield('Name of plan:', 'plan[name]', $plan['name']);
	tableselect('User group:<br /><span class="cp_small">Choose the user group that subscribers will be moved to after signing up to this plan.</span>', 'plan[usergroupid]', 'usergroup', $plan['usergroupid']);
	textarea('Description of plan:<br /><span class="cp_small">This is the description users will see when deciding which plan to sign up for, so you should include information about what users get when they sign up. Payment details do not have to be described here as they are automatically shown.</span>', 'plan[description]', $plan['description']);
	selectbox('Payment processors that can be used:', 'plan[processors][]', $processors, $plan['processors'], false, '', $listsize, ' multiple="multiple" style="width: 150px"');
	inputfield('Remind users to pay:<br /><span class="cp_small">Only applicable if the payment is recurring.</span>', 'plan[reminder]', $plan['reminder'], '5', ' days before expiry');
	tablehead(array('Payment settings'), 2);
	inputfield('Payment amount:<br /><span class="cp_small">The cost of the subscription, in U.S. Dollars.</span>', 'plan[cost]', sprintf('%.2f', $plan['cost']));
	limitfield('Recur payment every:<br /><span class="cp_small">Choose whether or not the payment for this plan should recur, and if so when.</span>', 'plan[length]', $plan['length'], '15', 0, true, 'One-time payment', " <select name=\"plan[unit]\">$unit</select>");
	yesno('Can pay in advance:<br /><span class="cp_small">Allow users to pay for more than one period in advance? Only applicable if the payment is recurring.</span>', 'plan[canpayadvance]', $plan['canpayadvance']);

	if ($planid == 0) {
		endform('Create plan');
	} else {
		endform('Update plan');
	}
	endtable();
}

// ############################################################################
// List the plans
if ($cmd == 'modify') {
	adminlog();

	$plans = $DB_site->query('
		SELECT plan.*, usergroup.title, COUNT(subscription.subscriptionid) AS subscriptions
		FROM hive_plan AS plan
		LEFT JOIN hive_usergroup AS usergroup USING (usergroupid)
		LEFT JOIN hive_subscription AS subscription ON (plan.planid = subscription.planid AND subscription.active = 1)
		GROUP BY plan.planid
	');
	starttable('Plans Manager');
	$cells = array(
		'ID',
		'Name',
		'Subscribers',
		'User Group',
		'Options',
	);
	tablehead($cells);
	if ($DB_site->num_rows($plans) < 1) {
		textrow('No plans', count($cells), 1);
	} else {
		while ($plan = $DB_site->fetch_array($plans)) {
			$cells = array(
				$plan['planid'],
				$plan['name'],
				iif($plan['subscriptions'] > 0, "<a href=\"subscription.php?cmd=list&planid=$plan[planid]\">$plan[subscriptions] subscriber".iif($plan['subscriptions'] != 1, 's').'</a>', "$plan[subscriptions] users"),
				"<a href=\"usergroup.php?cmd=edit&usergroupid=$plan[usergroupid]\">$plan[title]</a>",
				makelink('edit', "plan.php?cmd=edit&planid=$plan[planid]") . '-' . makelink('remove', "plan.php?cmd=remove&planid=$plan[planid]"),
			);
			tablerow($cells);
		}
	}
	emptyrow(count($cells));
	endtable();

	startform('plan.php', 'edit');
	starttable('', '450');
	endform('Create plan');
	endtable();
}

cp_footer();
?>