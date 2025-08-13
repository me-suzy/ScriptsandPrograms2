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
// | $RCSfile: functions_smtp.php,v $ - $Revision: 1.31 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Makes sure that we can use the given SMTP server
function smtp_validate($smtp_config = null) {
	global $_smtp_connection;

	// Configuration
	if ($smtp_config === null) {
		$smtp_config = array(
			'host' => getop('smtp_host'),
			'port' => getop('smtp_port'),
			'helo' => getop('smtp_helo'),
			'auth' => getop('smtp_auth'),
			'user' => getop('smtp_user'),
			'pass' => getop('smtp_pass'),
		);
	}

	// Test info
	if (!is_object($_smtp_connection = smtp::connect($smtp_config)) or $_smtp_connection->status != SMTP_STATUS_CONNECTED) {
		return false;
	} else {
		$_smtp_connection->quit();
		return true;
	}
}

// ############################################################################
// A function to send emails. Takes the same arguments as mail().
function smtp_mail($to, $subject, $message, $headers = array(''), $close = true, $smtp_config = null) {
	global $smtp_recipients, $_send, $_smtp_connection;
	$_send = false;

	// Keep-alive?
	if (!getop('smtp_persistent')) {
		$close = true;
	}

	// Create an array out of the $headers
	if (!is_array($headers)) {
		$send['headers'] = preg_split("#\r?\n#", $headers);
	} else {
		$send['headers'] = flatten_headers_array($headers);
	}

	// Configuration
	if ($smtp_config === null) {
		$smtp_config = array(
			'host' => getop('smtp_host'),
			'port' => getop('smtp_port'),
			'helo' => getop('smtp_helo'),
			'auth' => getop('smtp_auth'),
			'user' => getop('smtp_user'),
			'pass' => getop('smtp_pass'),
		);
	}

	// Remove non-header From line from the top
	foreach ($send['headers'] as $key => $header) {
		if (preg_match('#from[^:]#i', $header)) {
			unset($send['headers'][$key]);
			break;
		}
	}

	// If the SMTP information wasn't filled, send using the regular function
	if (empty($smtp_config['host'])) {
		$i = 0;
		$return_path = null;
		// Remove previous To line to avoid loops and get the Return-Path
		foreach ($send['headers'] as $key => $header) {
			if ($i == 2) {
				break;
			}
			if (strtolower(substr($header, 0, strpos($header, ':'))) == 'to') {
				unset($send['headers'][$key]);
				$i++;
			} elseif (strtolower(substr($header, 0, strpos($header, ':'))) == 'return-path') {
				$return_path = trim(substr($header, strpos($header, ':') + 1));
				$i++;
			}
		}
		$to = implode(', ', extract_email($to, true));
		if ($return_path !== null) {
			return mail($to, $subject, $message, implode(CRLF, $send['headers']), "-f$return_path");
		} else {
			return mail($to, $subject, $message, implode(CRLF, $send['headers']));
		}
	}

	// Get the recipients from the headers
	// And also the From: header
	// (foreach() must not be used here!!!)
	$gotsubject = $gotto = false;
	reset($send['headers']);
	while (list($key, $header) = each($send['headers'])) {
		$start = strtolower(substr($header, 0, strpos($header, ':')));
		switch ($start) {
			case 'cc':
				$cc = trim(substr($header, 3));
				break;
			case 'bcc':
				$bcc = trim(substr($header, 4));
				unset($send['headers']["$key"]);
				break;
			case 'from':
				$send['from'] = extract_email($header);
				break;
			case 'subject':
				$gotsubject = true;
				break;
			case 'to':
				$gotto = true;
				break;
			case 'date':
				$gotdate = true;
				break;
			case 'message-id':
				$gotid = true;
				break;
			case 'x-mailer':
				$gotmailer = true;
				break;
		}
	}

	// Add a Subject header if needed
	if (!$gotsubject) {
		$send['headers'][] = "Subject: $subject";
	}

	// Same with the To
	if (!$gotto) {
		$send['headers'][] = "To: $to";
	}

	if (!$gotdate) {
		$send['headers'][] = 'Date: '.date('r');
	}

	if (!$gotid) {
		$send['headers'][] = 'Message-ID: <'.md5(microtime().IPADDRESS).'@'.$smtp_config['host'].'>';
	}

	if (!$gotmailer) {
		$send['headers'][] = 'X-Mailer: Hivemail '.HIVEVERSION;
	}
	
	// Create the main recipients array
	$send['recipients'] = array();
	// I don't think this line is needed but I'm not removing it (yet)
	// $recips = array_merge(explode(' ', $to), explode(' ', $cc), explode(' ', $bcc));
	$send['recipients'] = extract_email("$to $cc $bcc", true);

	/* foreach ($recips as $value) {
		if (($getemail = extract_email($value)) != false) {
			$send['recipients'][] = $getemail;
		}
	} */

	// Remove duplicates from it
	$send['recipients'] = array_unique($send['recipients']);

	// Make it available outside the function
	$smtp_recipients = implode(' ', $send['recipients']);

	// The message
	$send['body'] = $message;

	// Add a bogus From address in case there is none
	if (empty($send['from'])) {
		$send['from'] = ini_get('sendmail_from');
	}

	$_send = $send;
	// Send the message with the class
	if (!is_object($_smtp_connection)) {
		if (!is_object($_smtp_connection = smtp::connect($smtp_config))) {
			return false;
		}
	} elseif (!$_smtp_connection->is_connected()) {
		if (!$_smtp_connection->connect($smtp_config)) {
			return false;
		}
	}
	if ($_smtp_connection->send($send)) {
		if ($close) {
			$_smtp_connection->quit();
		}
		return true;
	} else {
		//echo '<pre>', print_r($_smtp_connection->errors), '</pre>';
		if ($close) {
			$_smtp_connection->quit();
		}
		return false;
	}
}

// ############################################################################
// The SMTP class written by Richard Heyes
// Permission granted by Richard Heyes for inclusion in HiveMail
class smtp {
	var $authenticated;
	var $connection;
	var $recipients;
	var $headers;
	var $timeout;
	var $errors;
	var $status;
	var $body;
	var $from;
	var $host;
	var $port;
	var $helo;
	var $auth;
	var $user;
	var $pass;

	function smtp ($params = array()) {
		if (!defined('CRLF')) {
			define('CRLF', "\r\n", true);
		}

		$this->authenticated	= false;			
		$this->timeout			= 5;
		$this->status			= SMTP_STATUS_NOT_CONNECTED;
		$this->host				= 'localhost';
		$this->port				= 25;
		$this->helo				= 'localhost';
		$this->auth				= false;
		$this->user				= '';
		$this->pass				= '';
		$this->errors   		= array();

		foreach ($params as $key => $value) {
			$this->$key = $value;
		}

		if (empty($this->helo)) {
			$this->helo = $_SERVER['SERVER_NAME'];
		}
	}

	function &connect ($params = array()) {
		global $i;
		if (!isset($this->status)) {
			$obj = new smtp($params);
			if ($obj->connect()) {
				$obj->status = SMTP_STATUS_CONNECTED;
			}

			return $obj;

		} else {
			$this->connection = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
			if (function_exists('socket_set_timeout')) {
				@socket_set_timeout($this->connection, 5, 0);
			}

			$greeting = $this->get_data();
			if (is_resource($this->connection)) {
				if ($this->auth ? $this->ehlo() : $this->helo()) {
					$this->status = SMTP_STATUS_CONNECTED;
				}
				return ($this->status == SMTP_STATUS_CONNECTED);
			} else {
				$this->errors[] = 'Failed to connect to server: '.$errstr;
				return false;
			}
		}
	}

	function send ($params = array()) {
		foreach ($params as $key => $value) {
			$this->set($key, $value);
		}

		if ($this->is_connected()) {
			// Do we auth or not? Note the distinction between the auth variable and auth() function
			if ($this->auth and !$this->authenticated) {
				if (!$this->auth()) {
					return false;
				}
			}

			$this->mail($this->from);
			if (is_array($this->recipients)) {
				foreach ($this->recipients as $value) {
					$this->rcpt($value);
				}
			} else {
				$this->rcpt($this->recipients);
			}

			if (!$this->data()) {
				return false;
			}

			// Transparency
			$headers = str_replace(CRLF.'.', CRLF.'..', trim(implode(CRLF, $this->headers)));
			$body    = str_replace(CRLF.'.', CRLF.'..', $this->body);
			$body    = $body[0] == '.' ? '.'.$body : $body;

			$this->send_data($headers);
			$this->send_data('');
			$this->send_data($body);
			$this->send_data('.');

			$result = (substr(trim($this->get_data()), 0, 3) === '250');
			return $result;
		} else {
			$this->errors[] = 'Not connected!';
			return false;
		}
	}

	function helo () {
		if (is_resource($this->connection) and $this->send_data('HELO '.$this->helo) and substr(trim($error = $this->get_data()), 0, 3) === '250') {
			return true;
		} else {
			$this->errors[] = 'HELO command failed, output: ' . trim(substr(trim($error),3));
			return false;
		}
	}

	function ehlo () {
		if (is_resource($this->connection) and $this->send_data('EHLO '.$this->helo) and substr(trim($error = $this->get_data()), 0, 3) === '250') {
			return true;
		} else {
			$this->errors[] = 'EHLO command failed, output: ' . trim(substr(trim($error),3));
			return false;
		}
	}

	function rset () {
		if (is_resource($this->connection) and $this->send_data('RSET') and substr(trim($error = $this->get_data()), 0, 3) === '250') {
			return true;
		} else {
			$this->errors[] = 'RSET command failed, output: ' . trim(substr(trim($error),3));
			return false;
		}
	}

	function quit () {
		if ($this->status != SMTP_STATUS_NOT_CONNECTED and is_resource($this->connection) and $this->send_data('QUIT') and substr(trim($error = $this->get_data()), 0, 3) === '221') {
			fclose($this->connection);
			$this->status = SMTP_STATUS_NOT_CONNECTED;
			return true;
		} else {
			$this->errors[] = 'QUIT command failed, output: ' . trim(substr(trim($error),3));
			return false;
		}
	}

	function auth () {
		if (is_resource($this->connection) and $this->send_data('AUTH LOGIN') and substr(trim($error = $this->get_data()), 0, 3) === '334' and $this->send_data(base64_encode($this->user)) and substr(trim($error = $this->get_data()),0,3) === '334' and $this->send_data(base64_encode($this->pass)) and substr(trim($error = $this->get_data()),0,3) === '235') {
			$this->authenticated = true;
			return true;
		} else {
			$this->errors[] = 'AUTH command failed: ' . trim(substr(trim($error),3));
			return false;
		}
	}

	function mail ($from) {
		$from = extract_email($from);

		if ($this->is_connected() and $this->send_data('MAIL FROM:<'.$from.'>') and substr(trim($this->get_data()), 0, 3) === '250') {
			return true;
		} else {
			return false;
		}
	}

	function rcpt ($to) {
		$to = extract_email($to);

		if ($this->is_connected() and $this->send_data('RCPT TO:<'.$to.'>') and substr(trim($error = $this->get_data()), 0, 2) === '25') {
			return true;
		} else {
			$this->errors[] = trim(substr(trim($error), 3));
			return false;
		}
	}

	function data () {

		if ($this->is_connected() and $this->send_data('DATA') and substr(trim($error = $this->get_data()), 0, 3) === '354') {
			return true;
		} else {
			$this->errors[] = trim(substr(trim($error), 3));
			return false;
		}
	}

	function is_connected () {
		return (is_resource($this->connection) and ($this->status === SMTP_STATUS_CONNECTED));
	}

	function send_data ($data) {
		if (is_resource($this->connection)) {
			return fwrite($this->connection, $data.CRLF, strlen($data)+2);
		} else {
			return false;
		}
	}

	function &get_data () {
		$return = '';
		$line   = '';
		$loops  = 0;

		if (is_resource($this->connection)) {
			while ((strpos($return, CRLF) === false OR substr($line,3,1) !== ' ') and $loops < 100) {
				$line    = fgets($this->connection, 512);
				$return .= $line;
				$loops++;
			}
			return $return;
		} else {
			return false;
		}
	}

	function set ($var, $value) {
		$this->$var = $value;
		return true;
	}
}

?>