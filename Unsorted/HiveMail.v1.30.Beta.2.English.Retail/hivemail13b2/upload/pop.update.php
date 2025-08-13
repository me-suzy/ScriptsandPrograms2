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
// | $RCSfile: pop.update.php,v $ - $Revision: 1.23 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_popadded,redirect_popsupdated';
require_once('./global.php');
require_once('./includes/functions_pop.php');
require_once('./includes/functions_smtp.php');
require_once('./includes/functions_mime.php');

// ############################################################################
if ($_POST['cmd'] == 'updateall') {
	$pops = $DB_site->query("
		SELECT *
		FROM hive_pop
		WHERE userid = $hiveuser[userid]
	");
	while ($oldpop = $DB_site->fetch_array($pops)) {
		$popid = $oldpop['popid'];
		$newpop = $serverinfo["$popid"];

		// Email address
		if (!($newpop['displayemail'] = extract_email($newpop['displayemail']))) {
			$newpop['displayemail'] = $hiveuser['username'].getop('domainname');
		}

		// Changed password?
		if ($newpop['password'] != substr($origpass, 0, strlen($newpop['password']))) {
			$newpassword = '\''.addslashes(pop_encrypt($newpop['password'])).'\'';

			// Check new login
			$pop_socket = new $POP_Socket_name($newpop);
			if (!$pop_socket->auth(true)) {
				$pop_socket->close();
				eval(makeerror('error_poplogin'));
			} else {
				$pop_socket->close();
			}
		} else {
			$newpassword = 'password';
		}

		// Check SMTP server
		if (strpos($newpop['smtp_server'], '.') === false) {
			$newpop['smtp_server'] = '';
		}
		if (!empty($newpop['smtp_server']) and ($newpop['smtp_server'] != $oldpop['smtp_server'] or $newpop['smtp_port'] != $oldpop['smtp_port'])) {
			if (!smtp_validate(array('host' => $newpop['smtp_server'], 'port' => $newpop['smtp_port'], 'helo' => $_SERVER['SERVER_NAME'], 'auth' => (!empty($oldpop['smtp_username']) and !empty($oldpop['smtp_password'])), 'user' => $oldpop['smtp_username'], 'pass' => $oldpop['smtp_password']))) {
				$newpop['smtp_server'] = $oldpop['smtp_server'];
				$newpop['smtp_port'] = $oldpop['smtp_port'];
			}
		}

		$DB_site->query("
			UPDATE hive_pop
			SET server = '".addslashes($newpop['server'])."', port = '".addslashes($newpop['port'])."', username = '".addslashes($newpop['username'])."', password = $newpassword, smtp_server = '".addslashes($newpop['smtp_server'])."', smtp_port = '".addslashes($newpop['smtp_port'])."', displayemail = '".addslashes($newpop['displayemail'])."'
			WHERE popid = $popid
		");
	}

	// Update HivePOP3 options
	update_options($synchivepop, 'USER_SYNCHIVEPOP');
	intme($savetopop);

	$DB_site->query("
		UPDATE hive_user
		SET savetopop = $savetopop, options = $hiveuser[options], options2 = $hiveuser[options2]
		WHERE userid = $hiveuser[userid]
	");

	eval(makeredirect("redirect_popsupdated", "pop.list.php"));
}

// ############################################################################
if ($_POST['cmd'] == 'singleupdate') {
	$oldpop = getinfo('pop', $popid);
	$serverinfo = array("$popid" => $newpop);

	// Email address and name
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

	// Changed password?
	if ($newpop['password'] != substr($origpass, 0, strlen($newpop['password']))) {
		$newpassword = pop_encrypt($newpop['password']);
	} else {
		$newpassword = $oldpop['password'];
	}
	if (empty($newpop['smtp_password']) or $newpop['smtp_password'] != substr($origpass, 0, strlen($newpop['smtp_password']))) {
		$newsmtp_password = pop_encrypt($newpop['smtp_password']);
	} else {
		$newsmtp_password = $oldpop['smtp_password'];
	}

	// Modify login values for SMTP
	switch ($smtpauth) {
		case 'same':
			$newpop['smtp_username'] = $newpop['username'];
			$newsmtp_password = $newpassword;
			break;
		case 'diff':
			break;
		case 'none':
		default:
			$newpop['smtp_username'] = '';
			$newsmtp_password = '';
			break;
	}

	// Check POP3 server
	if ($newpop['username'] != $oldpop['username'] or $newpassword != $oldpop['password']) {
		$newpop['password'] = pop_decrypt($newpassword);
		$pop_socket = new $POP_Socket_name($newpop);
		if (!$pop_socket->auth(true)) {
			$pop_socket->close();
			eval(makeerror('error_poplogin'));
		} else {
			$pop_socket->close();
		}
	}

	// Check SMTP server
	if ($newpop['smtp_server'] != $oldpop['smtp_server'] or $newpop['smtp_port'] != $oldpop['smtp_port'] or $newpop['smtp_username'] != $oldpop['smtp_username'] or $newsmtp_password != $oldpop['smtp_password']) {
		$newpop['smtp_password'] = pop_decrypt($newsmtp_password);
		if (!smtp_validate(array('host' => $newpop['smtp_server'], 'port' => $newpop['smtp_port'], 'helo' => $_SERVER['SERVER_NAME'], 'auth' => ($smtpauth != 'none'), 'user' => $newpop['smtp_username'], 'pass' => $newpop['smtp_password']))) {
			$newpop['smtp_server'] = $oldpop['smtp_server'];
			$newpop['smtp_port'] = $oldpop['smtp_port'];
			$newpop['smtp_username'] = $oldpop['smtp_username'];
			$newsmtp_password = $oldpop['smtp_password'];
		}
	}

	// Verify folder ID
	if ($newpop['folderid'] > 0) {
		getinfo('folder', $newpop['folderid'], true);
	} else {
		intme($newpop['folderid']);
	}

	// Reply to address
	if (!($newpop['replyto'] = extract_email($newpop['replyto']))) {
		$newpop['replyto'] = $newpop['displayemail'];
	}

	// Update database
	$popupdate = array(
		'accountname' => htmlchars($newpop['accountname']),
		'displayname' => htmlchars($newpop['displayname']),
		'displayemail' => htmlchars($newpop['displayemail']),
		'server' => $newpop['server'],
		'port' => intval($newpop['port']),
		'username' => $newpop['username'],
		'password' => $newpassword,
		'smtp_server' => $newpop['smtp_server'],
		'smtp_port' => intval($newpop['smtp_port']),
		'smtp_username' => $newpop['smtp_username'],
		'smtp_password' => $newsmtp_password,
		'autopoll' => intval((bool) $newpop['autopoll']),
		'folderid' => $newpop['folderid'],
		'deletemails' => iif($newpop['delete'] >= 0 and $newpop['delete'] <= 2, $newpop['delete'], POP3_DELETE_SYNC),
		'color' => $newpop['color'],
		'replyto' => $newpop['replyto'],
		'usessl' => intval((bool) ($newpop['usessl'] and getop('pop3_useimap'))),
	);
	$DB_site->auto_query('pop', $popupdate, "popid = $popid");

	// Close window and refresh parent
	?><script language="JavaScript" type="text/javascript">
	<!--
	window.opener.location.reload();
	window.close()
	//-->
	</script><?php
	exit;
}

?>