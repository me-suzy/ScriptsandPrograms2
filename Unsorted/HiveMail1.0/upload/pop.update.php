<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: pop.update.php,v $
// | $Date: 2002/10/28 18:17:31 $
// | $Revision: 1.10 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_popadded,redirect_popsupdated';
require_once('./global.php');
require_once('./includes/pop_functions.php');
require_once('./includes/mime_functions.php');

// ############################################################################
if ($_POST['do'] == 'add') {
	$popid = 0;
	$thisdelete = iif($delete["$popid"] == 'yes', 1, 0);
	$thisactive = iif($active["$popid"] == 'yes', 1, 0);

	$pop_socket = new POP_Socket($serverinfo["$popid"]);
	$pop_socket->connect();
	if (!$pop_socket->auth()) {
		eval(makeerror('error_poplogin'));
	}

	$DB_site->query("
		INSERT INTO pop
		SET userid = $hiveuser[userid], server = '".addslashes($serverinfo["$popid"]['server'])."', port = '".addslashes($serverinfo["$popid"]['port'])."', username = '".addslashes($serverinfo["$popid"]['username'])."', password = '".addslashes(pop_encrypt($serverinfo["$popid"]['password']))."', active = $thisactive, deletemails = $thisdelete
	");

	$DB_site->query("
		UPDATE user
		SET haspop = haspop + 1
		WHERE userid = $hiveuser[userid]
	");

	eval(makeredirect("redirect_popadded", "pop.list.php"));
}

// ############################################################################
if ($_POST['do'] == 'update') {
	$pops = $DB_site->query("
		SELECT *
		FROM pop
		WHERE userid = $hiveuser[userid]
	");
	while ($pop = $DB_site->fetch_array($pops)) {
		$popid = $pop['popid'];
		$thisactive = iif($active["$popid"] == 'yes', 1, 0);
		$thisdelete = iif($delete["$popid"] == 'yes', 1, 0);
		$setpassword = iif(!empty($serverinfo["$popid"]['password']), " password = '".addslashes(pop_encrypt($serverinfo["$popid"]['password']))."',", '');

		if (!empty($serverinfo["$popid"]['password'])) {
			$pop_socket = new POP_Socket($serverinfo["$popid"]);
			if (!$pop_socket->auth()) {
				eval(makeerror('error_poplogin'));
			}
		}

		$DB_site->query("
			UPDATE pop
			SET server = '".addslashes($serverinfo["$popid"]['server'])."', port = '".addslashes($serverinfo["$popid"]['port'])."', username = '".addslashes($serverinfo["$popid"]['username'])."',$setpassword active = $thisactive, deletemails = $thisdelete
			WHERE popid = $popid
		");
	}

	eval(makeredirect("redirect_popsupdated", "pop.list.php"));
}

?>