<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: smtp_functions.php,v $
// | $Date: 2002/11/12 14:02:10 $
// | $Revision: 1.20 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// A function to send emails. Takes the same arguments as mail().
function smtp_mail($to, $subject, $message, $headers = array('')) {
	global $smtp_config, $smtp_recipients;

	// Create an array out of the $headers
	if (!is_array($headers)) {
		$send['headers'] = explode("\r\n", $headers);
	} else {
		$send['headers'] = explode("\r\n", implode_headers_array("\r\n", $headers));
	}

	// If the SMTP information wasn't filled, send using the regular function
	if (empty($smtp_config['host'])) {
		return mail($to, $subject, $message, implode("\r\n", $send['headers']));
	}

	// Get the recipients from the headers
	// And also the From: header
	// (foreach() must not be used here!!!)
	$gotsubject = $gotto = false;
	while (list($key, $header) = each($send['headers'])) {
		$start = strtolower(substr($header, 0, 3));
		switch ($start) {
			case 'cc:':
				$cc = trim(substr($header, 3));
				break;
			case 'bcc':
				$bcc = trim(substr($header, 4));
				unset($send['headers']["$key"]);
				break;
			case 'fro':
				$send['from'] = trim(substr($header, 5));
				break;
			case 'sub':
				$gotsubject = true;
				break;
			case 'to:':
				$gotto = true;
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
	
	// Create the main recipients array
	$send['recipients'] = array();
	$recips = array_merge(explode(' ', $to), explode(' ', $cc), explode(' ', $bcc));
	foreach ($recips as $value) {
		if (preg_match('#([-.a-z0-9_]+@[-.a-z0-9_)]+)#', $value, $getemail)) {
			$send['recipients'][] = $getemail[1];
		}
	}

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

	// Send the message with the class
	if (is_object($smtp = smtp::connect($smtp_config)) and $smtp->send($send)) {
		return true;
		// Any recipients that failed (relaying denied for example) will be logged in the errors variable.
		// print_r($smtp->errors);
	} else {
		return false;
		// The reason for failure should be in the errors variable
		// print_r($smtp->errors);
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
	}

	function &connect ($params = array()) {
		if (!isset($this->status)) {
			$obj = new smtp($params);
			if ($obj->connect()) {
				$obj->status = SMTP_STATUS_CONNECTED;
			}

			return $obj;

		} else {
			$this->connection = fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
			if (function_exists('socket_set_timeout')) {
				@socket_set_timeout($this->connection, 5, 0);
			}

			$greeting = $this->get_data();
			if (is_resource($this->connection)) {
				return $this->auth ? $this->ehlo() : $this->helo();
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
		if (is_resource($this->connection) and $this->send_data('QUIT') and substr(trim($error = $this->get_data()), 0, 3) === '221') {
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

		if ($this->is_connected() and $this->send_data('MAIL FROM:<'.$from.'>') and substr(trim($this->get_data()), 0, 2) === '250') {
			return true;
		} else {
			return false;
		}
	}

	function rcpt ($to) {

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