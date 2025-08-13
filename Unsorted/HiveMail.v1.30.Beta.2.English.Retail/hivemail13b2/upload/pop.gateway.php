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
// | $RCSfile: pop.gateway.php,v $ - $Revision: 1.8 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
ignore_user_abort(true);
@set_time_limit(0);
require_once('./global.php');

// ############################################################################
// Run POP3 gateway
$gotNewMsgs = 0;
// The /* protects javascript from errors due to PHP
echo 'var gotNewMsgs = 0; /*';
if (getop('gatewaytype') == 'pop3' and !getop('pop3_cron')) {
	if (getop('pop3_runevery') == 0) {
		$runpop = true;
	} elseif (getop('pop3_lastrun') + (60 * getop('pop3_runevery')) <= TIMENOW) {
		$DB_site->query('UPDATE hive_setting SET value = '.TIMENOW.' WHERE variable = "pop3_lastrun"');
		$runpop = true;
	} else {
		$runpop = false;
	}

	if ($runpop) {
		require_once('./includes/functions_pop.php');
		require_once('./includes/functions_mime.php');
		require_once('./includes/functions_smtp.php');
		$pop3_gateway = new $POP_Socket_name();
		$userIDs = $pop3_gateway->fetch_and_add(0, true);

		if (@in_array($hiveuser['userid'], $userIDs)) {
			$numMsgs = array_count_values($userIDs);
			$gotNewMsgs = $numMsgs[$hiveuser['userid']];
		}
	}
}

// ############################################################################
// User's POP3 accounts
if ($foruser and $hiveuser['haspop'] > 0) {
	require_once('./includes/functions_pop.php');
	require_once('./includes/functions_mime.php');
	require_once('./includes/functions_smtp.php');

	$popids = explode(',', $pops);
	$sqlids = array();
	foreach ($popids as $popid) {
		$sqlids[] = intval($popid);
	}
	$pops = $DB_site->query("
		SELECT *
		FROM hive_pop
		WHERE popid IN (".implode(', ', $sqlids).")
	");
	$userIDs = array();
	while ($pop = $DB_site->fetch_array($pops)) {
		if (connection_aborted()) {
			break; // No point in continuing
		}
		if ($pop['userid'] != $hiveuser['userid'] or !$pop['autopoll']) {
			continue;
		}
		$pop_socket = new $POP_Socket_name($pop, true);
		$thisIDs = $pop_socket->fetch_and_add($pop['popid'], true);
		if (is_array($thisIDs)) {
			$userIDs = array_merge($userIDs, $thisIDs);
		}
	}

	if (in_array($hiveuser['userid'], $userIDs)) {
		$numMsgs = array_count_values($userIDs);
		$gotNewMsgs += $numMsgs[$hiveuser['userid']];
	}
}

// JS to notify parent document we have new messages for this user
echo '*/ gotNewMsgs = '.$gotNewMsgs.';';

?>