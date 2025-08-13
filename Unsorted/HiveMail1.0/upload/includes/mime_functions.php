<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: mime_functions.php,v $
// | $Date: 2002/11/12 14:02:10 $
// | $Revision: 1.14 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// This one takes the message source ($message), and, well, processes it
function process_mail($message, $calledfromweb = false) {
	global $parsed_message, $headers, $DB_site, $hiveuser, $_rules;

	// Decode message
	decodemime($message, false);
	$messageBits = preg_split("#\r?\n#", $message);
	foreach ($messageBits as $i => $messageBit) {
		if ($messageBit == '' and $i > 0) {
			break;
		}
		unset($messageBits[$i]);
	}
	$messageOnly = implode(CRLF, $messageBits);

	// $good will hold all the correct infomation from now on
	$good = array();
	$toalias = array();
	$recips = array();
	$headers = $parsed_message['headers'];

	// trim() some stuff now instead of later
	$headers['from'] = trim($headers['from']);
	$headers['to'] = trim($headers['to']);
	$headers['cc'] = trim($headers['cc']);
	$headers['subject'] = trim($headers['subject']);
	$good['emailid'] = trim($headers['message-id']);

	// Get the sender's email
	preg_match("/([-.a-z0-9_]+@[-.a-z0-9_)]*)/i", $headers['from'], $getfromemail);
	$good['fromemail'] = $getfromemail[1];
	$good['fromdomain'] = substr($good['fromemail'], strpos($good['fromemail'], '@') + 1);

	// Get the sender's name
	$fromemaillastspace = strrpos($headers['from'], ' ');
	if ($headers['from'][($fromemaillastspace - 1)] == '"' and $headers['from'][0] == '"') {
		$good['fromname'] = substr($headers['from'], 1, $fromemaillastspace-1-strlen($headers['from']));
	} else {
		$good['fromname'] = substr($headers['from'], 0, $fromemaillastspace-strlen($headers['from']));
		if (trim($good['fromname']) == '') {
			$good['fromname'] = $good['fromemail'];
		}
	}

	// Get the list of To and CC recipient and put in an array
	$tolist = explode(', ', preg_replace("/\"[^\"]*\"/U", '', $headers['to'].' '.$headers['cc']));
	foreach ($tolist as $tokey => $value) {
		if (preg_match("/([-.a-z0-9_]+@[-.a-z0-9_)]*)/i", $value, $gettoemail)) {
			if (strstr($gettoemail[1], '@') == getop('domainname')) {
				$toalias[] = substr($gettoemail[1], 0, -strlen(getop('domainname')));
			}
			$recips[] = $gettoemail[1];
		}
	}
	$recips = implode(' ', $recips);

	// Try to get extra recipients
	if (is_array($headers['received'])) {
		foreach ($headers['received'] as $recvalue) {
			if (preg_match("/([-.a-z0-9_]+@[-.a-z0-9_)]*)/i", $recvalue, $gettoemail)) {
				if (strstr($gettoemail[1], '@') == getop('domainname')) {
					$toalias[] = substr($gettoemail[1], 0, -strlen(getop('domainname')));
				}
			}
		}
	} else {
		if (preg_match("/([-.a-z0-9_]+@[-.a-z0-9_)]*)/i", $headers['received'], $gettoemail)) {
			if (strstr($gettoemail[1], '@') == getop('domainname')) {
				$toalias[] = substr($gettoemail[1], 0, -strlen(getop('domainname')));
			}
		}
	}

	// If this is a POP email we only have one recipient
	if ($calledfromweb) {
		$toalias = array($hiveuser['username']);
	}

	// Handle the subject
	if (!empty($headers['subject'])) {
		if (substr(strtolower($headers['subject']), 0, 3) == 're:') {
			$headers['subject'] = substr($headers['subject'], 3).'; Re:';
		}
		if (substr(strtolower($headers['subject']), 0, 3) == 'fw:') {
			$headers['subject'] = substr($headers['subject'], 3).'; Fw:';
		}
		while (substr(strtolower($headers['subject']), 0, 3) == 're:' or substr(strtolower($headers['subject']), 0, 3) == 'fw:') {
			$headers['subject'] = substr($headers['subject'], 3);
		}
		$good['subject'] = trim($headers['subject']);
	} else {
		$good['subject'] = '[no subject]';
	}

	// Priorities rock
	$good['priority'] = intval($headers['x-priority']);
	switch ($good['priority']) {
		case 1: case 5: break;
		default:
			$good['priority'] = 3;
	}

	// If we have attachments it's about time we tell the user about it
	if (is_array($parsed_message['attachments'])) {
		$good['attach'] = count($parsed_message['attachments']);
	} else {
		$good['attach'] = 0;
	}

	// The message
	if (trim($parsed_message['text'][0]) != '') {
		$good['message'] = trim($parsed_message['text'][0]);
	} else {
		$good['message'] = '[no message]';
	}

	// Insert the email to the database for every alias we found
	$valuelist = '';
	$usernamelist = '';
	$userslist = '';
	foreach ($toalias as $aliasnum => $alias) {
		$usernamelist .= ',"'.addslashes($alias).'"';
	}

	// Make sure we haven't processed this POP message
	if ($calledfromweb and !empty($good['emailid']) and $DB_site->query_first("SELECT messageid FROM message WHERE userid = $hiveuser[userid] AND emailid = '".addslashes($good['emailid'])."'")) {
		// Hello
	} else {
		// Get all users with the aliases we found
		$result = $DB_site->query('
			SELECT userid, username, realname, blocked, safe, forward, emptybin, usergroup.maxmb
			FROM user
			LEFT JOIN usergroup USING (usergroupid)
			WHERE username IN (""'.$usernamelist.') AND (usergroup.perms & '.GROUP_CANUSE.')
		');

		// Was our search successful?
		if ($DB_site->num_rows($result) > 0) {
			while ($user = $DB_site->fetch_array($result)) {
				// Forward the email
				if (!empty($user['forward'])) {
					smtp_mail($user['forward'], $good['subject'], $messageOnly, $parsed_message['headers']);
				}

				// Make sure there is enough space in the user's account
				$maildata = $DB_site->fetch_array($DB_site->query("
					SELECT SUM(size) AS bytes
					FROM message
					WHERE userid = $user[userid]".iif($user['emptybin'] != 0, ' AND folderid <> -3')."
				"));
				$mailmb = round($maildata['bytes'] / 1048576, 2);
				if ($mailmb > (float) $user['maxmb'] and $user['maxmb'] > 0) {
					// Bounce!
					eval(makeeval('bounce_subject', 'error_processerror_subject'));
					eval(makeeval('bounce_message', 'error_processerror_nospace'));
					smtp_mail($good['fromemail'], $bounce_subject, $bounce_message, 'From: '.getop('smtp_errorfrom'));
					continue;
				}

				// Default vars
				$folderid = -1;
				$status = 0;

				// Protect safe senders
				$safelist = explode(' ', $user['safe']);
				if (!in_array(trim($good['fromemail']), $safelist) and !in_array(trim($good['fromdomain']), $safelist)) {

					// Get rules
					$rules = $DB_site->query("
						SELECT *
						FROM rule
						WHERE userid = $user[userid] AND active = 1 ORDER BY display
					");

					// Go through each one
					while ($rule = $DB_site->fetch_array($rules)) {
						// Extract the data
						$cond = intval($rule['cond']);
						$condsubject = substr($cond, 0, 1);
						$condhow = substr($cond, 1);
						$action = intval($rule['action']);
						if (($condpos = strpos($rule['cond'], '~')) !== false) {
							$condextra = substr($rule['cond'], $condpos + 1);
						} else {
							$condextra = '';
						}
						if (($actionpos = strpos($rule['action'], '~')) !== false) {
							$actionextra = substr($rule['action'], $actionpos + 1);
						} else {
							$actionextra = '';
						}

						// Check if the email matches the condition
						switch ($condsubject) {
							case substr($_rules['conds']['emaileq'], 0, 1):
								$regex['subject'] = $good['fromemail'];
								break;

							case substr($_rules['conds']['msgeq'], 0, 1):
								$regex['subject'] = $good['message'];
								break;

							case substr($_rules['conds']['recipseq'], 0, 1):
								$regex['subject'] = $recips;
								break;

							case substr($_rules['conds']['subjecteq'], 0, 1):
								$regex['subject'] = $good['subject'];
								break;
						}

						$condextra = str_replace('\*', '(.*)', preg_quote($condextra));
						switch ($condhow) {
							case substr($_rules['conds']['emaileq'], 1, 1):
								$regex['pattern'] = '#^'.$condextra.'$#';
								break;

							case substr($_rules['conds']['emailcon'], 1, 1):
								$regex['pattern'] = '#^'.$condextra.'$#';
								break;

							case substr($_rules['conds']['emailnotcon'], 1, 1):
								$regex['pattern'] = '#^'.$condextra.'$#';
								break;

							case substr($_rules['conds']['emailstars'], 1, 1):
								$regex['pattern'] = '#^'.$condextra.'$#';
								break;

							case substr($_rules['conds']['emailends'], 1, 1):
								$regex['pattern'] = '#^'.$condextra.'$#';
								break;
						}

						if (!empty($regex['pattern']) and preg_match($regex['pattern'], $regex['subject'])) {
							// Delete it
							if ($action & $_rules['actions']['delete']) {
								$folderid = -3;
							}
							// Mark as read
							if ($action & $_rules['actions']['read']) {
								$status += MAIL_READ;
							}
							// Flag it
							if ($action & $_rules['actions']['flag']) {
								$status += MAIL_FLAGGED;
							}
							// Move to folder
							if ($action & $_rules['actions']['move']) {
								$folderid = $actionextra;
							}
							// Copy to folder
							if ($action & $_rules['actions']['copy']) {
								$valuelist .= "(NULL, $user[userid], $actionextra, ".TIMENOW.", '".addslashes($good['fromemail'])."', '".addslashes($good['fromname'])."', '".addslashes($good['subject'])."', '".addslashes($good['message'])."', '".addslashes($recips)."', $good[attach], $status, '".addslashes($good['emailid'])."', '".addslashes($message)."', $good[priority], ".strlen($message).")";
							}
						}
					}

					// Make sure this isn't a blocked email
					$blocked = explode(' ', $user['blocked']);
					if (in_array(trim($good['fromemail']), $blocked) or in_array(trim($good['fromdomain']), $blocked)) {
						$folderid = -3;
					}
				}

				if (!empty($valuelist)) {
					$valuelist .= ',';
				}
				$valuelist .= "(NULL, $user[userid], $folderid, ".TIMENOW.", '".addslashes($good['fromemail'])."', '".addslashes($good['fromname'])."', '".addslashes($good['subject'])."', '".addslashes($good['message'])."', '".addslashes($recips)."', $good[attach], $status, '".addslashes($good['emailid'])."', '".addslashes($message)."', $good[priority], ".strlen($message).")";
			}

			// Insert all the emails!
			if (!empty($valuelist)) {
				$DB_site->query("
					INSERT INTO message
					(messageid, userid, folderid, dateline, email, name, subject, message, recipients, attach, status, emailid, source, priority, size)
					VALUES
					$valuelist
				");
			}
		} else {
			// Bounce!
			eval(makeeval('bounce_subject', 'error_processerror_subject'));
			eval(makeeval('bounce_message', 'error_processerror_unknown'));
			smtp_mail($good['fromemail'], $bounce_subject, $bounce_message, 'From: '.getop('smtp_errorfrom'));
		}
	}
}

// ############################################################################
// Function that decodes the MIME message and creates the $headers and $parsed_message data arrays
function decodemime($message, $incAttachData = true) {
	global $parsed_message, $headers;

	$message = preg_replace("/\r?\n/", "\r\n", $message);
	$params = array(
		'input'          => $message,
		'crlf'           => "\r\n",
		'include_bodies' => true,
		'decode_headers' => true,
		'decode_bodies'  => true
	);
	$output = Mail_mimeDecode::decode($params);
	$parseparsed_message = array();
	parse_output($output, $parsed_message, $incAttachData);
	$headers = $parsed_message['headers'];
}

// ############################################################################
// The MIME decoding class written by Richard Heyes
// Permission granted by Richard Heyes for inclusion in HiveMail
class Mail_mimeDecode {
	var $_input;
	var $_header;
	var $_body;
	var $_error;
	var $_include_bodies;
	var $_decode_bodies;
	var $_decode_headers;
	var $mailMimeDecode;

	function Mail_mimeDecode($input) {
		list($header, $body)   = $this->_splitBodyHeader($input);

		$this->_input          = $input;
		$this->_header         = $header;
		$this->_body           = $body;
		$this->_decode_bodies  = false;
		$this->_include_bodies = true;
		
		$this->mailMimeDecode  = true;
	}

	function decode($params = null) {

		if (!isset($this->mailMimeDecode) AND isset($params['input'])) {

			$obj = new Mail_mimeDecode($params['input']);
			$structure = $obj->decode($params);

		} elseif (!isset($this->mailMimeDecode)) {
			return $this->raiseError('Called statically and no input given');

		} else {
			$this->_include_bodies = isset($params['include_bodies'])  ? $params['include_bodies']  : false;
			$this->_decode_bodies  = isset($params['decode_bodies'])   ? $params['decode_bodies']   : false;
			$this->_decode_headers = isset($params['decode_headers'])  ? $params['decode_headers']  : false;

			$structure = $this->_decode($this->_header, $this->_body);
			if ($structure === false) {
				$structure = $this->raiseError($this->_error);
			}
		}

		return $structure;
	}

	function _decode($headers, $body, $default_ctype = 'text/plain') {
		$return = new stdClass;
		$headers = $this->_parseHeaders($headers);

		foreach ($headers as $value) {
			if (isset($return->headers[strtolower($value['name'])]) AND !is_array($return->headers[strtolower($value['name'])])) {
				$return->headers[strtolower($value['name'])]   = array($return->headers[strtolower($value['name'])]);
				$return->headers[strtolower($value['name'])][] = $value['value'];

			} elseif (isset($return->headers[strtolower($value['name'])])) {
				$return->headers[strtolower($value['name'])][] = $value['value'];

			} else {
				$return->headers[strtolower($value['name'])] = $value['value'];
			}
		}

		reset($headers);
		while (list($key, $value) = each($headers)) {
			$headers["$key"]['name'] = strtolower($headers["$key"]['name']);
			switch ($headers["$key"]['name']) {

				case 'content-type':
					$content_type = $this->_parseHeaderValue($headers["$key"]['value']);

					if (preg_match('/([0-9a-z+.-]+)\/([0-9a-z+.-]+)/i', $content_type['value'], $regs)) {
						$return->ctype_primary   = $regs[1];
						$return->ctype_secondary = $regs[2];
					}

					if (isset($content_type['other'])) {
						while (list($p_name, $p_value) = each($content_type['other'])) {
							$return->ctype_parameters[$p_name] = $p_value;
						}
					}
					break;

				case 'content-disposition';
					$content_disposition = $this->_parseHeaderValue($headers["$key"]['value']);
					$return->disposition   = $content_disposition['value'];
					if (isset($content_disposition['other'])) {
						while (list($p_name, $p_value) = each($content_disposition['other'])) {
							$return->d_parameters[$p_name] = $p_value;
						}
					}
					break;

				case 'content-transfer-encoding':
					$content_transfer_encoding = $this->_parseHeaderValue($headers["$key"]['value']);
					break;
			}
		}

		if (isset($content_type)) {
			switch (strtolower($content_type['value'])) {
				case 'text/plain':
					$encoding = isset($content_transfer_encoding) ? $content_transfer_encoding['value'] : '7bit';
					$this->_include_bodies ? $return->body = ($this->_decode_bodies ? $this->_decodeBody($body, $encoding) : $body) : null;
					break;

				case 'text/html':
					$encoding = isset($content_transfer_encoding) ? $content_transfer_encoding['value'] : '7bit';
					$this->_include_bodies ? $return->body = ($this->_decode_bodies ? $this->_decodeBody($body, $encoding) : $body) : null;
					break;

				case 'multipart/parallel':
				case 'multipart/report':
				case 'multipart/signed':
				case 'multipart/digest':
				case 'multipart/alternative':
				case 'multipart/related':
				case 'multipart/mixed':
					if(!isset($content_type['other']['boundary'])){
						$this->_error = 'No boundary found for ' . $content_type['value'] . ' part';
						return false;
					}

					$default_ctype = (strtolower($content_type['value']) === 'multipart/digest') ? 'message/rfc822' : 'text/plain';

					$parts = $this->_boundarySplit($body, $content_type['other']['boundary']);
					for ($i = 0; $i < count($parts); $i++) {
						list($part_header, $part_body) = $this->_splitBodyHeader($parts["$i"]);
						$part = $this->_decode($part_header, $part_body, $default_ctype);
						if($part === false)
							$part = $this->raiseError($this->_error);
						$return->parts[] = $part;
					}
					break;

				case 'message/rfc822':
					$obj = &new Mail_mimeDecode($body);
					$return->parts[] = $obj->decode(array('include_bodies' => $this->_include_bodies));
					unset($obj);
					break;

				default:
					if(!isset($content_transfer_encoding['value']))
						$content_transfer_encoding['value'] = '7bit';
					$this->_include_bodies ? $return->body = ($this->_decode_bodies ? $this->_decodeBody($body, $content_transfer_encoding['value']) : $body) : null;
					break;
			}

		} else {
			$ctype = explode('/', $default_ctype);
			$return->ctype_primary   = $ctype[0];
			$return->ctype_secondary = $ctype[1];
			$this->_include_bodies ? $return->body = ($this->_decode_bodies ? $this->_decodeBody($body) : $body) : null;
		}

		return $return;
	}

	function &getMimeNumbers(&$structure, $no_refs = false, $mime_number = '', $prepend = '') {
		$return = array();
		if (!empty($structure->parts)) {
			if ($mime_number != '') {
				$structure->mime_id = $prepend . $mime_number;
				$return[$prepend . $mime_number] = &$structure;
			}
			for ($i = 0; $i < count($structure->parts); $i++) {

			
				if (!empty($structure->headers['content-type']) AND substr(strtolower($structure->headers['content-type']), 0, 8) == 'message/') {
					$prepend      = $prepend . $mime_number . '.';
					$_mime_number = '';
				} else {
					$_mime_number = ($mime_number == '' ? $i + 1 : sprintf('%s.%s', $mime_number, $i + 1));
				}

				$arr = &Mail_mimeDecode::getMimeNumbers($structure->parts["$i"], $no_refs, $_mime_number, $prepend);
				foreach ($arr as $key => $val) {
					$no_refs ? $return["$key"] = '' : $return["$key"] = &$arr["$key"];
				}
			}
		} else {
			if ($mime_number == '') {
				$mime_number = '1';
			}
			$structure->mime_id = $prepend . $mime_number;
			$no_refs ? $return[$prepend . $mime_number] = '' : $return[$prepend . $mime_number] = &$structure;
		}
		
		return $return;
	}

	function _splitBodyHeader($input) {
		if (preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $input, $match)) {
			return array($match[1], $match[2]);
		}
		$this->_error = 'Could not split header and body';
		return false;
	}

	function _parseHeaders($input) {

		if ($input !== '') {
			$input   = preg_replace("/\r\n/", "\n", $input);
			$input   = preg_replace("/\n(\t| )+/", ' ', $input);
			$headers = explode("\n", trim($input));

			foreach ($headers as $value) {
				$hdr_name = substr($value, 0, $pos = strpos($value, ':'));
				$hdr_value = substr($value, $pos+1);
				if($hdr_value[0] == ' ')
					$hdr_value = substr($hdr_value, 1);

				$return[] = array(
								  'name'  => $hdr_name,
								  'value' => $this->_decode_headers ? $this->_decodeHeader($hdr_value) : $hdr_value
								 );
			}
		} else {
			$return = array();
		}

		return $return;
	}

	function _parseHeaderValue($input) {

		if (($pos = strpos($input, ';')) !== false) {

			$return['value'] = trim(substr($input, 0, $pos));
			$input = trim(substr($input, $pos+1));

			if (strlen($input) > 0) {

				$parameters = preg_split('/\s*(?<!\\\\);\s*/i', $input);

				for ($i = 0; $i < count($parameters); $i++) {
					$param_name  = substr($parameters["$i"], 0, $pos = strpos($parameters["$i"], '='));
					$param_value = substr($parameters["$i"], $pos + 1);
					if ($param_value[0] == '"') {
						$param_value = substr($param_value, 1, -1);
					}
					$return['other'][$param_name] = $param_value;
					$return['other'][strtolower($param_name)] = $param_value;
				}
			}
		} else {
			$return['value'] = trim($input);
		}

		return $return;
	}

	function _boundarySplit($input, $boundary) {
		$tmp = explode('--'.$boundary, $input);

		for ($i=1; $i<count($tmp)-1; $i++) {
			$parts[] = $tmp["$i"];
		}

		return $parts;
	}

	function _decodeHeader($input) {
		$input = preg_replace('/(=\?[^?]+\?(Q|B)\?[^?]*\?=)( |' . "\t|\r?\n" . ')+=\?/', '\1=?', $input);

		while (preg_match('/(=\?([^?]+)\?(Q|B)\?([^?]*)\?=)/', $input, $matches)) {

			$encoded  = $matches[1];
			$charset  = $matches[2];
			$encoding = $matches[3];
			$text     = $matches[4];

			switch ($encoding) {
				case 'B':
					$text = base64_decode($text);
					break;

				case 'Q':
					$text = str_replace('_', ' ', $text);
					preg_match_all('/=([a-f0-9]{2})/i', $text, $matches);
					foreach($matches[1] as $value)
						$text = str_replace('='.$value, chr(hexdec($value)), $text);
					break;
			}

			$input = str_replace($encoded, $text, $input);
		}

		return $input;
	}

	function _decodeBody($input, $encoding = '7bit') {
		switch ($encoding) {
			case '7bit':
				return $input;
				break;

			case 'quoted-printable':
				return $this->_quotedPrintableDecode($input);
				break;

			case 'base64':
				return base64_decode($input);
				break;

			default:
				return $input;
		}
	}

	function _quotedPrintableDecode($input) {
		$input = preg_replace("/=\r?\n/", '', $input);

		if (preg_match_all('/=[a-f0-9]{2}/i', $input, $matches)) {
			$matches = array_unique($matches[0]);
			foreach ($matches as $value) {
				$input = str_replace($value, chr(hexdec(substr($value,1))), $input);
			}
		}

		return $input;
	}

	function &uudecode($input) {
		preg_match_all("/begin ([0-7]{3}) (.+)\r?\n(.+)\r?\nend/Us", $input, $matches);

		for ($j = 0; $j < count($matches[3]); $j++) {

			$str      = $matches[3][$j];
			$filename = $matches[2][$j];
			$fileperm = $matches[1][$j];

			$file = '';
			$str = preg_split("/\r?\n/", trim($str));
			$strlen = count($str);

			for ($i = 0; $i < $strlen; $i++) {
				$pos = 1;
				$d = 0;
				$len=(int)(((ord(substr($str["$i"],0,1)) -32) - ' ') & 077);

				while (($d + 3 <= $len) AND ($pos + 4 <= strlen($str["$i"]))) {
					$c0 = (ord(substr($str["$i"],$pos,1)) ^ 0x20);
					$c1 = (ord(substr($str["$i"],$pos+1,1)) ^ 0x20);
					$c2 = (ord(substr($str["$i"],$pos+2,1)) ^ 0x20);
					$c3 = (ord(substr($str["$i"],$pos+3,1)) ^ 0x20);
					$file .= chr(((($c0 - ' ') & 077) << 2) | ((($c1 - ' ') & 077) >> 4));

					$file .= chr(((($c1 - ' ') & 077) << 4) | ((($c2 - ' ') & 077) >> 2));

					$file .= chr(((($c2 - ' ') & 077) << 6) |  (($c3 - ' ') & 077));

					$pos += 4;
					$d += 3;
				}

				if (($d + 2 <= $len) && ($pos + 3 <= strlen($str["$i"]))) {
					$c0 = (ord(substr($str["$i"],$pos,1)) ^ 0x20);
					$c1 = (ord(substr($str["$i"],$pos+1,1)) ^ 0x20);
					$c2 = (ord(substr($str["$i"],$pos+2,1)) ^ 0x20);
					$file .= chr(((($c0 - ' ') & 077) << 2) | ((($c1 - ' ') & 077) >> 4));

					$file .= chr(((($c1 - ' ') & 077) << 4) | ((($c2 - ' ') & 077) >> 2));

					$pos += 3;
					$d += 2;
				}

				if (($d + 1 <= $len) && ($pos + 2 <= strlen($str["$i"]))) {
					$c0 = (ord(substr($str["$i"],$pos,1)) ^ 0x20);
					$c1 = (ord(substr($str["$i"],$pos+1,1)) ^ 0x20);
					$file .= chr(((($c0 - ' ') & 077) << 2) | ((($c1 - ' ') & 077) >> 4));

				}
			}
			$files[] = array('filename' => $filename, 'fileperm' => $fileperm, 'filedata' => $file);
		}

		return $files;
	}

	function getSendArray() {
		$this->_decode_headers = FALSE;
		$headerlist =$this->_parseHeaders($this->_header);
		$to = "";
		if (!$headerlist) {
			return $this->raiseError("Message did not contain headers");
		}
		foreach($headerlist as $item) {
			$header[$item['name']] = $item['value'];
			switch (strtolower($item['name'])) {
				case "to":
				case "cc":
				case "bcc":
					$to = ",".$item['value'];
				default:
				   break;
			}
		}
		if ($to == "") {
			return $this->raiseError("Message did not contain any recipents");
		}
		$to = substr($to,1);
		return array($to,$header,$this->_body);
	}
}

function parse_output (&$obj, &$parts, $incAttachData = true) {
	if (!empty($obj->parts)) {
		for($i=0; $i<count($obj->parts); $i++)
		parse_output($obj->parts["$i"], $parts);
	} else {
		$ctype = $obj->ctype_primary.'/'.$obj->ctype_secondary;
		$ctype = strtolower($ctype);
		switch ($ctype){
			case 'text/plain':
				if (!empty($obj->disposition) AND $obj->disposition == 'attachment') {
					$parts['attachments'][] = array(
													'data' => iif($incAttachData, $obj->body, ''),
													'filename' => $obj->d_parameters['filename'],
													'filename2' => $obj->ctype_parameters['name'],
													'type' => $obj->ctype_primary,
													'encoding' => $obj->headers['content-transfer-encoding']
													);
				} else {
					$parts['text'][] = $obj->body;
				}
				break;

			case 'text/html':
				if (!empty($obj->disposition) AND $obj->disposition == 'attachment') {
					$parts['attachments'][] = array(
													'data' => iif($incAttachData, $obj->body, ''),
													'filename' => $obj->d_parameters['filename'],
													'filename2' => $obj->ctype_parameters['name'],
													'type' => $obj->ctype_primary,
													'encoding' => $obj->headers['content-transfer-encoding']
													);
				} else {
					$parts['html'][] = $obj->body;
				}
				break;

			default:
				if (!strstr($obj->headers['content-type'], 'signature')) {
					$parts['attachments'][] = array(
													'data' => iif($incAttachData, $obj->body, ''),
													'filename' => $obj->d_parameters['filename'],
													'filename2' => $obj->ctype_parameters['name'],
													'type' => $obj->ctype_primary,
													'headers' => $obj->headers
													);
				}

		}
	}
	$parts['headers'] = $obj->headers;
}

?>