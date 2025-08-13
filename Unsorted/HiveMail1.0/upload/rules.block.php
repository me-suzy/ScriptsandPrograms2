<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: rules.block.php,v $
// | $Date: 2002/10/28 18:17:31 $
// | $Revision: 1.11 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_blocked';
require_once('./global.php');

// ############################################################################
if (!empty($messageid) or $block == 'subject') {
	$info = getinfo('message', $messageid);
	$email = $info['email'];
} else {
	$email = urldecode($email);
}

// ############################################################################
if ($block != 'subject') {
	if (!strstr($hiveuser['blocked'], $email)) {
		$DB_site->query("
			UPDATE user
			SET blocked = ' ".addslashes($email.' '.$hiveuser['blocked'])." '
			WHERE userid = $hiveuser[userid]
		");
	}

	if (!empty($delete)) {
		$DB_site->query('
			DELETE FROM message
			WHERE email = "'.addslashes($email).'"
			AND folderid = '.intval($delete).'
			AND userid = '.$hiveuser['userid'].'
		');
	}

	$block = 'email address';
}

// ############################################################################
if ($block == 'subject') {
	$getorder = $DB_site->query_first("
		SELECT MAX(display) AS max
		FROM rule
		WHERE userid = $hiveuser[userid]
	");
	$thisorder = $getorder['max'] + 1;

	$DB_site->query("
		INSERT INTO rule
		(ruleid, userid, cond, action, active, display)
		VALUES
		(NULL, $hiveuser[userid], '".$_rules['conds']['subjecteq']."~".addslashes($info['subject'])."', '".$_rules['actions']['delete']."~', 1, $thisorder)
	");

	if (!empty($delete)) {
		$DB_site->query('
			DELETE FROM message
			WHERE subject = "'.addslashes($info['subject']).'"
			AND folderid = '.intval($delete).'
			AND userid = '.$hiveuser['userid'].'
		');
	}
}

eval(makeredirect("redirect_blocked", iif(isset($info), "index.php?folderid=$info[folderid]", $_SERVER['HTTP_REFERER'])));

?>