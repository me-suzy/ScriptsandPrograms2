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
// | $RCSfile: pop.add.php,v $ - $Revision: 1.6 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_popadded,redirect_popsupdated';
require_once('./global.php');
require_once('./includes/functions_pop.php');
require_once('./includes/functions_smtp.php');
require_once('./includes/functions_mime.php');
define('LOAD_MINI_TEMPLATES', true);

// ############################################################################
// Set default cmd
$badinfo = false;
if (!isset($cmd)) {
	$cmd = 'step1';
}

// ############################################################################
if ($_POST['cmd'] == 'finish') {
	$newpop += unserialize($newpopinfo);

	if (empty($newpop['accountname'])) {
		$badinfo = true;
		$_POST['cmd'] = 'step4';
	} else {
		$popinfo = array(
			'userid' => $hiveuser['userid'],
			'accountname' => htmlchars($newpop['accountname']),
			'displayname' => htmlchars($newpop['displayname']),
			'displayemail' => htmlchars($newpop['displayemail']),
			'server' => $newpop['server'],
			'port' => intval($newpop['port']),
			'username' => $newpop['username'],
			'password' => $newpop['password'],
			'smtp_server' => $newpop['smtp_server'],
			'smtp_port' => intval($newpop['smtp_port']),
			'smtp_username' => $newpop['smtp_username'],
			'smtp_password' => $newpop['smtp_password'],
		);
		$DB_site->auto_query('pop', $popinfo);
		$DB_site->query("
			UPDATE hive_user
			SET haspop = haspop + 1
			WHERE userid = $hiveuser[userid]
		");

		// Close window and refresh parent
		?><script language="JavaScript" type="text/javascript">
		<!--
		window.opener.location.reload();
		window.close()
		//-->
		</script><?php
		exit;
	}
}

// ############################################################################
if ($_POST['cmd'] == 'step4') {
	if (!$badinfo) {
		$newpop += unserialize($newpopinfo);
	}

	if (!$badinfo and !empty($newpop['smtp_server']) and !smtp_validate(array('host' => $newpop['smtp_server'], 'port' => $newpop['smtp_port'], 'helo' => $_SERVER['SERVER_NAME'], 'auth' => ($smtpauth != 'none'), 'user' => $newpop['smtp_username'], 'pass' => $newpop['smtp_password']))) {
		$badinfo = true;
		$_POST['cmd'] = 'step3';
	} else {
		$newpop['password'] = pop_encrypt($newpop['password']);
		$newpop['smtp_password'] = pop_encrypt($newpop['smtp_password']);
		$newpopinfo = htmlchars(serialize($newpop));
		eval(makeeval('echo', 'pop_addaccount_step4'));
	}
}

// ############################################################################
if ($_POST['cmd'] == 'step3') {
	if (!$badinfo) {
		$newpop += unserialize($newpopinfo);
	}

	$pop_socket = new $POP_Socket_name($newpop);
	if (!$badinfo and !$pop_socket->auth(true)) {
		$badinfo = true;
		$_POST['cmd'] = 'step2';
		$pop_socket->close();
	} else {
		$pop_socket->close();
		if (empty($newpop['smtp_port']) or !is_numeric($newpop['smtp_port'])) {
			$newpop['smtp_port'] = 25;
		}
		$authsel = array(
			'none' => iif(empty($newpop['smtp_username']) or empty($newpop['smtp_password']), 'selected="selected"'),
			'same' => iif($newpop['username'] == $newpop['smtp_username'] and $newpop['password'] == $newpop['smtp_password'], 'selected="selected"'),
		);
		$authsel['diff'] = iif(empty($authsel['none']) and ($newpop['username'] != $newpop['smtp_username'] or $newpop['password'] != $newpop['smtp_password']), 'selected="selected"');
		$authdisabled = iif(empty($newpop['smtp_server']), 'disabled="disabled"');
		$smtplogindisabled = iif(empty($authsel['diff']), 'disabled="disabled"');
		$newpopinfo = htmlchars(serialize($newpop));
		eval(makeeval('echo', 'pop_addaccount_step3'));
	}
}

// ############################################################################
if ($_POST['cmd'] == 'step2') {
	if (!($newpop['displayemail'] = extract_email($newpop['displayemail']))) {
		$newpop['displayemail'] = $hiveuser['username'].getop('domainname');
	}
	if (empty($newpop['displayname'])) {
		if (empty($hiveuser['realname'])) {
			$newpop['displayname'] = $newpop['displayemail'];
		} else {
			$newpop['displayname'] = $hiveuser['realname'];
		}
	}

	if (empty($newpop['port']) or !is_numeric($newpop['port'])) {
		$newpop['port'] = 110;
	}

	$newpopinfo = htmlchars(serialize($newpop));
	eval(makeeval('echo', 'pop_addaccount_step2'));
}

// ############################################################################
if ($cmd == 'step1') {
	eval(makeeval('echo', 'pop_addaccount_step1'));
}

?>