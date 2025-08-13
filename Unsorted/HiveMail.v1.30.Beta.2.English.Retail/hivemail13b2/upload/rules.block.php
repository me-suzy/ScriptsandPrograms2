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
// | $RCSfile: rules.block.php,v $ - $Revision: 1.13 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
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
if (!empty($delete)) {
	require_once('./includes/functions_pop.php');
	require_once('./includes/functions_mime.php');
	require_once('./includes/functions_smtp.php');
}

// ############################################################################
if ($block != 'subject') {
	if (!array_contains($email, extract_email($hiveuser['blocked'], true))) {
		$DB_site->query("
			UPDATE hive_user
			SET blocked = ' ".addslashes($email.' '.$hiveuser['blocked'])." '
			WHERE userid = $hiveuser[userid]
		");
	}

	if (!empty($delete)) {
		delete_messages('email = "'.addslashes($email).'" AND folderid = '.intval($delete).' AND userid = '.$hiveuser['userid']);
	}

	$block = 'email address';
}

// ############################################################################
if ($block == 'subject') {
	$getorder = $DB_site->query_first("
		SELECT MAX(display) AS max
		FROM hive_rule
		WHERE userid = $hiveuser[userid]
	");
	$thisorder = $getorder['max'] + 1;

	$DB_site->query("
		INSERT INTO hive_rule
		(ruleid, userid, cond, action, active, display)
		VALUES
		(NULL, $hiveuser[userid], '".$_rules['conds']['subjecteq']."~".addslashes($info['subject'])."', '".$_rules['actions']['delete']."~', 1, $thisorder)
	");

	if (!empty($delete)) {
		delete_messages('subject = "'.addslashes($info['subject']).'" AND folderid = '.intval($delete).' AND userid = '.$hiveuser['userid']);
	}
}

eval(makeredirect("redirect_blocked", iif(isset($info), INDEX_FILE."?folderid=$info[folderid]", $_SERVER['HTTP_REFERER'])));

?>