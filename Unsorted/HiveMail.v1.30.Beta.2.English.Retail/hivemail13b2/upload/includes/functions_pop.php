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
// | $RCSfile: functions_pop.php,v $ - $Revision: 1.38 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Complete class for POP socket connection
class POP_Socket {
	var $fp = null;
	var $connected = false;
	var $serverinfo = array();
	var $encrypted = false;
	var $msgNums = array();
	var $msgIDs = array();

	// Sets up the server information array
	function POP_Socket($info = null, $enc = false) {
		global $foldertitles;

		if ($info === null) {
			$info = array(
				'server' => getop('pop3_server'),
				'port' => getop('pop3_port'),
				'username' => getop('pop3_username'),
				'password' => getop('pop3_password'),
				'deletemails' => (int) !getop('pop3_nodelete'),
				'folderid' => -1,
			);
		}

		// Verify some information		
		if (!isset($info['folderid'])) {
			$info['folderid'] = -1;
		} elseif (is_array($foldertitles) and isset($foldertitles["$info[folderid]"])) {
		} elseif ($info['folderid'] < 0 and $info['folderid'] >= -4) {
		} elseif (!getinfo('folder', $info['folderid'], true, false)) {
			$info['folderid'] = -1;
		}
		if (!isset($info['deletemails'])) {
			$info['deletemails'] = false;
		}

		$this->serverinfo = $info;
		$this->encrypted = $enc;
	}

	// Given a list of Message-ID's, delete them from the server
	function delete_by_id($deleteIDs) {
		// Just in case...
		if ($this->serverinfo['deletemails'] != POP3_DELETE_SYNC) {
			return;
		}

		// Connect to server
		if ($this->connect() === false) {
			return false;
		} elseif ($this->auth() === false) {
			$this->close();
			return false;
		} elseif ($this->get_list() === false) {
			$this->close();
			return false;
		} elseif (empty($this->msgNums)) {
			$this->close();
			return false;
		}

		// If there is a message in here with a POP-size of 0
		// we shouldn't check for message sizes at all
		$check_size = (!getop('pop3_useimap') and !in_array(0, $deleteIDs));

		// Delete messages
		foreach ($this->msgNums as $msgnum => $msgsize) {
			// This shouldn't pose any problems, considering
			// message sizes don't change between sessions
			// (or at least should not)
			if ($check_size and !in_array($msgsize, $deleteIDs)) {
				continue;
			}

			// Get headers of message and stop with the Message-ID
			// echo "Checked message <b>$msgnum</b><br />";
			$this->get_top($msgnum, $msgTop, 0, 'message-id:', true);
			if (empty($msgTop)) {
				continue;
			}

			// And delete the message if its ID is in the to-be-deleted array
			$msgID = trim(substr($msgTop, strlen('message-id:')));
			if (array_key_exists($msgID, $deleteIDs)) {
				$this->delete_email($msgnum);
			}
		}

		$this->close();
		return true;
	}

	// Whole procedure to get emails and add them to the database
	function fetch_and_add($popid = 0, $returnUsers = false) {
		global $DB_site, $hiveuser;

		if ($this->connect() === false) {
			return 'socket';
		} elseif ($this->auth() === false) {
			$this->close();
			return 'login';
		} elseif ($this->get_list() === false) {
			$this->close();
			return 'list';
		} elseif (empty($this->msgNums)) {
			$this->close();
			return 'success';
		}

		// From a user's POP3 server?
		$userpop = (intme($popid) > 0);

		// I seriously don't remember what this code does... umm
		// I think it removes messages that were already processed
		// from the list, but not sure why it's here since this is
		// already done in mime_functions.php. Oh well...
		if ($userpop) {
			foreach ($this->msgNums as $msgnum => $msginfo) {
				$this->get_top($msgnum, $msgTop, 0, 'message-id:', true);
				if ($msgTop === false) {
					continue;
				}
				$this->msgIDs[$msgnum] = trim(substr($msgTop, strlen('message-id:')));
			}

			if (!empty($this->msgIDs)) {
				$oldMsgs = $DB_site->query("
					SELECT *
					FROM hive_emailid
					WHERE userid = $hiveuser[userid]
					AND emailid IN ('".implode('\', \'', $this->msgIDs)."')
				");
				while ($oldMsg = $DB_site->fetch_array($oldMsgs)) {
					$oldMsgNum = array_search($oldMsg['emailid'], $this->msgIDs);
					unset($this->msgNums[$oldMsgNum]);
				}
			}
		}

		// Figure out if all messages are huge
		$processBig = true;
		if (!getop('pop3_useimap')) {
			$pop3_maxsize = getop('pop3_maxsize') * (1024 * 1024);
			foreach ($this->msgNums as $msgnum => $msgsize) {
				if ($msgsize <= $pop3_maxsize and $pop3_maxsize > 0) {
					$processBig = false;
					break;
				}
			}
		}

		$count = 0;
		$totalsize = 0;
		$userIDs = array();
		foreach ($this->msgNums as $msgnum => $msgsize) {
			// Too many messages?
			if ($count++ > getop('pop3_maxmsgs') and getop('pop3_maxmsgs') > 0) {
				break;
			}

			// Message too big?
			if (!$processBig and $msgsize > $pop3_maxsize and $pop3_maxsize > 0) {
				continue;
			}

			// Processed enough for today?
			if ($totalsize > $pop3_maxsize and $pop3_maxsize > 0) {
				break;
			}
			$msgsize = $this->get_size($msgnum);
			$totalsize += $msgsize;

			$this->get_email($msgnum, $msgsource);
			$thisIDs = process_mail($msgsource, $popid, $this->serverinfo['folderid'], $msgsize);
			if (is_array($thisIDs)) {
				$userIDs = array_merge($userIDs, $thisIDs);
			}
			if ($this->serverinfo['deletemails'] == POP3_DELETE_RIGHTAWAY) {
				$this->delete_email($msgnum);
			}
		}
		$this->close();

		if ($returnUsers) {
			return $userIDs;
		} else {
			return 'success';
		}
	}

	// Check if we can connect and log into the mailbox
	function test_mailbox() {
		if ($this->connect() === false) {
			return 'socket';
		} elseif ($this->auth() === false) {
			$this->close();
			return 'login';
		} elseif ($this->get_list() === false) {
			$this->close();
			return 'list';
		} elseif (empty($this->msgNums)) {
			$this->close();
			return 'empty'; // Still 'good'
		} else {
			$this->close();
			return 'success';
		}
	}

	// Logs failed logins to our server
	function error($error, $errorarray) {
		if ($this->serverinfo['server'] == getop('pop3_server')) {
			log_event(EVENT_CRITICAL, $error, $errorarray);
		}
	}
}

// ############################################################################
// Completes the POP3 class by directly connecting through a socket
class POP_Socket_socket extends POP_Socket {
	// Sets up the server information array
	function POP_Socket_socket($info = null, $enc = false) {
		$this->POP_Socket($info, $enc);
	}

	// Connect to the server
	function connect() {
		// Apparently fsockopen() doesn't return false in these cases:
		if (empty($this->serverinfo['server'])) {
			return false;
		}
		if ($this->connected == true) {
			return true;
		}

		$this->fp = @fsockopen($this->serverinfo['server'], intval($this->serverinfo['port']), $error_no, $error_str);
		if (!is_resource($this->fp)) {
			$this->error(101, $this->serverinfo);
			return false;
		}
		$this->connected = true;

		$buffer = fgets($this->fp, 4096);
		if (substr($buffer, 0, 3) != '+OK') {
			$this->error(101, $this->serverinfo);
			$this->close();
			return false;
		} else {
			return true;
		}
	}

	// Login to the server
	function auth($justchecking = false) {
		if (!is_resource($this->fp) and $this->connect() === false) {
			return false;
		}

		fputs($this->fp, 'USER '.$this->serverinfo['username']."\r\n");
		$buffer = fgets($this->fp, 4096);
		if (substr($buffer, 0, 3) != '+OK') {
			$this->close();
			return false;
		}

		if ($this->encrypted) {
			fputs($this->fp, 'PASS '.pop_decrypt($this->serverinfo['password'])."\r\n");
		} else {
			fputs($this->fp, 'PASS '.$this->serverinfo['password']."\r\n");
		}
		$buffer = fgets($this->fp, 4096);

		if (substr($buffer, 0, 3) != '+OK') {
			$this->error(102, $this->serverinfo);
			$this->close();
			return false;
		} else {
			if ($justchecking) {
				$this->close();
			}
			return true;
		}
	}

	// Get the list of messages and their ID's
	function get_list() {
		fputs($this->fp, "LIST\r\n");
		if (substr(fgets($this->fp, 4096), 0, 3) != '+OK') {
			$this->close();
			return false;
		}

		// Store the message numbers and sizes
		$buffer = fgets($this->fp, 4096);
		while ($buffer != ".\r\n") {
			$msginfo = explode(' ', $buffer);
			$this->msgNums[(trim($msginfo[0]))] = trim($msginfo[1]);
			$buffer = fgets($this->fp, 4096);
		}
		return true;
	}

	// Gets the size of a message
	function get_size($msgnum) {
		return $this->msgNums[$msgnum];
	}

	// Gets email number $msgnum from the server
	function get_email($msgnum, &$source) {
		return $this->get_data("RETR $msgnum\r\n", $source);
	}

	// Gets the top $lines from the message
	function get_top($msgnum, &$source, $lines = 0, $stopat = '', $onelineonly = false) {
		return $this->get_data("TOP $msgnum $lines\r\n", $source, $stopat, $onelineonly);
	}

	// Issues $command and returns the output
	function get_data($command, &$source, $stopat = '', $onelineonly = false) {
		fputs($this->fp, $command);

		if (substr(fgets($this->fp, 4096), 0, 3) != '+OK') {
			return false;
		}

		$source = '';
		$buffer = fgets($this->fp, 4096);
		while ($buffer != ".\r\n") {
			if (!$onelineonly) {
				$source .= $buffer;
			}
			if (!empty($stopat)) {
				if (strtolower(substr(trim($buffer), 0, strlen($stopat))) == strtolower($stopat)) {
					if ($onelineonly) {
						$source = $buffer;
					} else {
						$onelineonly = true;
					}
					$stopat = '';
				}
			}
			$buffer = fgets($this->fp, 4096);
		}

		return true;
	}

	// Sends the given command to the server and returns true or false on success
	function send_command($command) {
		fputs($this->fp, $command."\r\n");
		$buffer = trim(fgets($this->fp, 4096));
		if (substr($buffer, 0, 3) != '+OK') {
			$this->error(103, array('cmd' => $command, 'resp' => $buffer));
			return false;
		} else {
			return true;
		}
	}

	// Delete message number $msgnum from the server
	function delete_email($msgnum) {
		return $this->send_command("DELE $msgnum");
	}

	// Close connection to the server
	function close() {
		if ($this->connected == true) { // and is_resource($this->fp)) {
			$this->connected = false;
			@fputs($this->fp, "QUIT\r\n");
			@fclose($this->fp);
		}
	}
}

// ############################################################################
// Completes the POP3 class using IMAP functions
class POP_Socket_IMAP extends POP_Socket {
	// Sets up the server information array
	function POP_Socket_IMAP($info = null, $enc = false) {
		$this->POP_Socket($info, $enc);
	}

	// Connect to the server
	function connect() {
		if (empty($this->serverinfo['server'])) {
			return false;
		}
		if ($this->connected == true) {
			return true;
		}

		$this->fp = @imap_open('{'.$this->serverinfo['server'].':'.intval($this->serverinfo['port']).'/pop3'.iif($this->serverinfo['usessl'], '/ssl').'}INBOX', $this->serverinfo['username'], ($this->encrypted ? pop_decrypt($this->serverinfo['password']) : $this->serverinfo['password']));
		if (!$this->fp) {
			$this->error(101, $this->serverinfo);
			return false;
		}
		$this->connected = true;

		return true;
	}

	// Login to the server
	function auth($justchecking = false) {
		if (!$this->fp and $this->connect() === false) {
			return false;
		}

		return true;
	}

	// Get the list of messages and their ID's
	function get_list() {
		$totalMsgs = imap_num_msg($this->fp);

		// Store the message numbers and sizes
		for ($i = 1; $i <= $totalMsgs; $i++) {
			$this->msgNums[$i] = 0;
		}
		return true;
	}

	// Gets the size of a message
	function get_size($msgnum) {
		if ($this->msgNums[$msgnum] == 0) {
			$structure = imap_fetch_overview($this->fp, $msgnum);
			$this->msgNums[$msgnum] = $structure[0]->size;
		}
		return $this->msgNums[$msgnum];
	}

	// Gets email number $msgnum from the server
	function get_email($msgnum, &$source) {
		$source = imap_fetchheader($this->fp, $msgnum).imap_body($this->fp, $msgnum);
	}

	// Gets the top $lines from the message
	function get_top($msgnum, &$source, $lines = 0, $stopat = '', $onelineonly = false) {
		$headers = preg_split("#(\r?\n)#", imap_fetchheader($this->fp, $msgnum), PREG_SPLIT_DELIM_CAPTURE);

		$source = '';
		foreach ($headers as $buffer) {
			if (!$onelineonly) {
				$source .= $buffer;
			}
			if (!empty($stopat)) {
				if (strtolower(substr(trim($buffer), 0, strlen($stopat))) == strtolower($stopat)) {
					if ($onelineonly) {
						$source = $buffer;
					} else {
						$onelineonly = true;
					}
					$stopat = '';
				}
			}
		}

		return true;
	}

	// Delete message number $msgnum from the server
	function delete_email($msgnum) {
		return imap_delete($this->fp, $msgnum);
	}

	// Close connection to the server
	function close() {
		if ($this->connected == true) {
			$this->connected = false;
			imap_expunge($this->fp);
			imap_close($this->fp);
		}
	}
}

// ############################################################################
// This functions takes a POP account ID and a message number and returns an
// array which is what the database would hold if the message was processed
function pop_decodemail($popid, $msgid, $folderid = -1) {
	global $pop_socket, $parsed_message, $headers, $popmailinfo, $POP_Socket_name;

	if (!($popmailinfo = getinfo('pop', $popid, false, false))) {
		return false;
	}

	$pop_socket = new $POP_Socket_name($popmailinfo, true);
	if (!$pop_socket->auth()) {
		eval(makeerror('error_poplogin'));
	}
	if (!$pop_socket->get_email(intme($msgid), $msgsource)) {
		getinfo('message', $messageid = 0);
	}
	$mail = process_mail($msgsource, $popid, $folderid, 0, true);
	$mail['folderid'] = $folderid;
	$mail['source'] = &$msgsource;

	return $mail;
}

// ############################################################################
// A function to synchronize POP3 accounts with local inbox
function pop_sync($emailids) {
	global $hiveuser, $DB_site, $POP_Socket_name;

	if ($hiveuser['haspop'] > 0) {
		// Get Message-ID's
		if (is_resource($emailids)) {
			$allemailids = array();
			while ($emailid = $DB_site->fetch_array($emailids)) {
				$allemailids["$emailid[emailid]"] = $emailid['popsize'];
			}
		} else {
			$allemailids = $emailids;
		}

		// Connect to each account and delete messages
		$pops = $DB_site->query("
			SELECT *
			FROM hive_pop
			WHERE userid = $hiveuser[userid]
			AND deletemails = ".POP3_DELETE_SYNC."
		");
		while ($pop = $DB_site->fetch_array($pops)) {
			$pop_socket = new $POP_Socket_name($pop, true);
			$pop_socket->delete_by_id($allemailids);
		}
	}
}

// ############################################################################
// 3 functions, taken from PHP's Manual, for encrypting and decrypting
function keyed($txt, $encrypt_key) {
	$encrypt_key = md5($encrypt_key);
	$ctr = 0;
	$tmp = '';
	for ($i = 0; $i < strlen($txt); $i++) {
		if ($ctr == strlen($encrypt_key)) {
			$ctr = 0;
		}
		$tmp .= substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1);
		$ctr++;
	}
	return $tmp;
}

function encrypt($txt,$key) {
	global $_secret_keys;
	srand((double) microtime() * 1000000);
	$encrypt_key = md5($_secret_keys[0]);
	$ctr = 0;
	$tmp = '';
	for ($i = 0; $i < strlen($txt); $i++) {
		if ($ctr == strlen($encrypt_key)) {
			$ctr = 0;
		}
		$tmp .= substr($encrypt_key, $ctr, 1) . (substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1));
		$ctr++;
	}
	return keyed($tmp, $key);
}

function decrypt($txt, $key) {
	$txt = keyed($txt, $key);
	$tmp = '';
	for ($i = 0; $i < strlen($txt); $i++) {
		$md5 = substr($txt, $i, 1);
		$i++;
		$tmp .= (substr($txt, $i, 1) ^ $md5);
	}
	return $tmp;
}

// ############################################################################
// This function utilizes the functions above to encrypt a string
function pop_encrypt($text) {
	global $_secret_keys;
	return base64_encode(keyed(encrypt(keyed(encrypt(keyed($text, $_secret_keys[1]), $_secret_keys[2]), $_secret_keys[3]), $_secret_keys[4]), $_secret_keys[5]));
}

// ############################################################################
// Does the same but with less keys (less intensive)
function pop_encrypt_simple($text) {
	global $_secret_keys;
	return base64_encode(encrypt($text, $_secret_keys[1]));
}

// ############################################################################
// Decrypts text
function pop_decrypt($enc) {
	global $_secret_keys;
	return keyed(decrypt(keyed(decrypt(keyed(base64_decode($enc), $_secret_keys[4]), $_secret_keys[5]), $_secret_keys[3]), $_secret_keys[2]), $_secret_keys[1]);
}

// ############################################################################
// Does the same but with less keys (less intensive)
function pop_decrypt_simple($enc) {
	global $_secret_keys;
	return decrypt(base64_decode($enc), $_secret_keys[1]);
}

?>