<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: pop_functions.php,v $
// | $Date: 2002/11/11 15:33:16 $
// | $Revision: 1.15 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// These are the secret keys that are used to encrypt the passwords
// As the name suggests, these keys should be kept private
srand((double) microtime() * 1000000);
$_secret_keys[0] = rand(1, 100);
$_secret_keys[1] = 'S3AsLIqap2us';
$_secret_keys[2] = 'Nu2oSpislut9';
$_secret_keys[3] = 'cr7sleCHagiP';
$_secret_keys[4] = 'fe9ASwaw4Ota';
$_secret_keys[5] = 'suFEr2T39Fob';

// ############################################################################
// 3 functions, taken from PHP.net's Manual, for encrypting and decrypting
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
// Does the same, only the other way around
function pop_decrypt($enc) {
	global $_secret_keys;
	return keyed(decrypt(keyed(decrypt(keyed(base64_decode($enc), $_secret_keys[4]), $_secret_keys[5]), $_secret_keys[3]), $_secret_keys[2]), $_secret_keys[1]);
}

// ############################################################################
// Complete class for POP socket connection
class POP_Socket {
	var $fp = null;
	var $serverinfo = array();
	var $encrypted = false;
	var $msgnums = array();

	// Sets up the server information array
	function POP_Socket($info, $enc = false) {
		$this->serverinfo = $info;
		$this->encrypted = $enc;
	}

	// Whole procedure to get emails and add them to the database
	function fetch_and_add() {
		if ($this->connect() === false) {
			return 'socket';
		}
		if ($this->auth() === false) {
			return 'login';
		}
		if ($this->get_list() === false) {
			return 'list';
		}

		$sources = array();
		foreach ($this->msgnums as $msgnum) {
			$sources[] = $this->get_email($msgnum);

			if ($this->serverinfo['deletemails']) {
				$this->delete_email($msgnum);
			}
		}
		$this->close();

		foreach ($sources as $source) {
			process_mail($source, true);
		}

		return 'success';
	}

	// Connect to the server
	function connect() {
		$this->fp = fsockopen($this->serverinfo['server'], $this->serverinfo['port']);
		if (!$this->fp) {
			return false;
		}

		$buffer = fgets($this->fp, 4096);
		if (substr($buffer, 0, 3) != '+OK') {
			$this->close();
			return false;
		} else {
			return true;
		}
	}

	// Login to the server
	function auth() {
		if ($this->fp === null and $this->connect() === false) {
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
			$this->close();
			return false;
		} else {
			return true;
		}
	}

	// Get the list of messages
	function get_list() {
		fputs($this->fp, "LIST\r\n");

		if (substr(fgets($this->fp, 4096), 0, 3) != '+OK') {
			$this->close();
			return false;
		} else {
			$buffer = fgets($this->fp, 4096);
			while ($buffer != ".\r\n") {
				$this->msgnums[] = intval($buffer);
				$buffer = fgets($this->fp, 4096);
			}
			return true;
		}
	}

	// Gets email number $msgnum from the server
	function get_email($msgnum) {
		fputs($this->fp, "RETR $msgnum\r\n");

		if (substr(fgets($this->fp, 4096), 0, 3) != '+OK') {
			return false;
		}

		$source = '';
		$buffer = fgets($this->fp, 4096);
		while ($buffer != ".\r\n") {
			$source .= $buffer;
			$buffer = fgets($this->fp, 4096);
		}

		return $source;
	}

	// Delete message number $msgnum from the server
	function delete_email($msgnum) {
		fputs($this->fp, "DELE $msgnum\r\n");
		if (substr(fgets($this->fp, 4096), 0, 3) != '+OK') {
			return false;
		} else {
			return true;
		}
	}

	// Close connection to the server
	function close() {
		fputs($this->fp, "QUIT\r\n");
		fclose($this->fp);
	}
}

?>