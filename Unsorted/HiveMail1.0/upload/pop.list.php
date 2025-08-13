<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: pop.list.php,v $
// | $Date: 2002/11/05 16:02:34 $
// | $Revision: 1.12 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'pop,pop_popbit,pop_nopops';
require_once('./global.php');
require_once('./includes/pop_functions.php');
require_once('./includes/mime_functions.php');

// ############################################################################
// Set default do
if (!isset($do)) {
	$do = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(4);

// ############################################################################
// Get the accounts
$pops = $DB_site->query("
	SELECT *
	FROM pop
	WHERE userid = $hiveuser[userid]
");

// Show the accounts
$popbits = '';
$counter = 1;
while ($pop = $DB_site->fetch_array($pops)) {
	// BG
	if (($counter++ % 2) == 0) {
		$class['name'] = 'normal';
	} else {
		$class['name'] = 'high';
	}
	// Active or not
	if ($pop['active']) {
		$activechecked = 'checked="checked"';
	} else {
		$activechecked = '';
	}

	// Delete or not
	if ($pop['deletemails']) {
		$deletechecked = 'checked="checked"';
	} else {
		$deletechecked = '';
	}

	eval(makeeval('popbits', 'pop_accountbit', 1));
}

// In case there are no accounts
if (empty($popbits)) {
	eval(makeeval('popbits', 'pop_nopops'));
}

$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; POP Accounts';
eval(makeeval('echo', 'pop'));

?>