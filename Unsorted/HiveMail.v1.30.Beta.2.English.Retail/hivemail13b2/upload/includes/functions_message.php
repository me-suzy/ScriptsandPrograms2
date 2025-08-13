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
// | $RCSfile: functions_message.php,v $ - $Revision: 1.32 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Encode subject (move Re: and Fw: to the end and stuff)
function encode_subject(&$subject) {
	$subject = trim($subject);
	if (substr(strtolower($subject), 0, 3) == 're:') {
		$subject = substr($subject, 3).'; Re:';
	}
	if (substr(strtolower($subject), 0, 3) == 'fw:') {
		$subject = substr($subject, 3).'; Fw:';
	}
	while (substr(strtolower($subject), 0, 3) == 're:' or substr(strtolower($subject), 0, 3) == 'fw:') {
		$subject = substr($subject, 3);
	}
	if (empty($subject)) {
		$subject = '(no subject)';
	}
}

// ############################################################################
// Decode subject to user-readable format
function decode_subject(&$subject, $justtrim = false, $htmlchars = true) {
	$subject = trim($subject);
	while (substr($subject, -5) == '; Re:') {
		$subject = iif(!$justtrim, 'Re: ').substr($subject, 0, -5);
	}
	while (substr($subject, -5) == '; Fw:') {
		$subject = iif(!$justtrim, 'Fw: ').substr($subject, 0, -5);
	}
	if ($justtrim) {
		return;
	}
	if (trim($subject) == '') {
		$subject = '(no subject)';
	}
	if ($htmlchars) {
		$subject = htmlchars($subject);
	}
}

// ############################################################################
// Adds a header line to $advheaders, for reading emails
function show_header($headername, $headerinfo) {
	global $advheaders, $afterattach, $count;

	switch (substr($headername, 0, 3)) {
		case 'fro':
		case 'sub':
		case 'to':
		case 'cc':
			return;
	}

	if (is_array($headerinfo)) {
		foreach ($headerinfo as $headerinfo2) {
			show_header($headername, $headerinfo2);
		}
	} else {
		if ($count++ % 2 == 0) {
			$headerbgcolor = $afterattach['first'];
		} else {
			$headerbgcolor = $afterattach['second'];
		}
		$headername = ucwords($headername);
		$headerinfo = nl2br(htmlchars($headerinfo));
		eval(makeeval('advheaders', 'read_header', 1));
	}
}

// ############################################################################
// Adds <a> tags around URLs and email addresses
function addlinks($text, $msgid = 0) {
	$find = array(
		"/([^]_a-z0-9-=\"'\/])((https?|ftp|gopher|news|telnet):\/\/|www\.)([^ \r\n\(\)\^\$!`\"'\|\[\]\{\}<>]*)/sie",
		"/^((https?|ftp|gopher|news|telnet):\/\/|www\.)([^ \r\n\(\)\^\$!`\"'\|\[\]\{\}<>]*)/sie",
		"/([ \n\r\t])([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4}))/si",
		"/^([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4}))/si"
	);

	if ($msgid == 0 or !getop('framelinks')) {
		$replace = array(
			'"$1<a href=\"".iif(substr(\'$2$4\', 0, 4) != \'http\', \'http://\')."$2$4\" target=\"_blank\">$2$4</a>"',
			'"<a href=\"".iif(substr(\'$1$3\', 0, 4) != \'http\', \'http://\')."$1$3\" target=\"_blank\">$1$3</a>"',
		);
	} else {
		$replace = array(
			'"$1<a href=\"read.link.php?messageid='.$msgid.'&url=".urlencode("$2$4")."\" target=\"_blank\">$2$4</a>"',
			'"<a href=\"read.link.php?messageid='.$msgid.'&url=".urlencode("$1$3")."\" target=\"_blank\">$1$3</a>"',
		);
	}
	array_push($replace, '$1<a href="compose.email.php?email=$2" target="_blank">$2</a>', '<a href="compose.email.php?email=$0" target="_blank">$0</a>');

	return preg_replace($find, $replace, $text);
}

// ############################################################################
// Create a mail bit for $mail
function makemailbit($mail, $templatename = 'mailbit') {
	global $hiveuser, $folderid, $rowjsbits, $skin, $sortby, $markallbg, $current, $_folders, $foldertitles;

	// Highlight the right column
	$bgcolors = array(
		'flagged' => '',
		'attach' => '',
		'subject' => '',
		'name' => '',
		'dateline' => '',
		'priority' => '',
		'size' => '',
		'folderid' => '',
	);
	switch ($sortby) {
		case 'flagged':
		case 'attach':
		case 'subject':
		case 'name':
		case 'dateline':
		case 'priority':
		case 'size':
		case 'folderid':
			$bgcolors["$sortby"] = 'high';
			break;
	}

	// Row color for POP3 accounts
	if ($mail['color'] == 'none') {
		$mail['color'] = '';
	}
	if (empty($mail['color'])) {
		switch ($mail['bgcolor']) {
			case 'gray':
			case 'teal':
			case 'purple':
			case 'navy':
			case 'olive':
			case 'green':
			case 'maroon':
			case 'black':
				$mail['color'] = 'white';
		}
	}

	// Icon
	$mail['image'] = 'mail';

	// Fix the date...
	$mail['date'] = hivedate($mail['dateline']);
	$mail['time'] = hivedate($mail['dateline'], getop('timeformat'));

	// The sender's informationn...
	$mail['fromname'] = $mail['name'];
	$mail['fromemail'] = $mail['email'];
	if (empty($mail['fromname'])) {
		if (!empty($mail['fromemail'])) {
			$mail['fromname'] = $mail['fromemail'];
		} else {
			$mail['fromname'] = '&nbsp;';
		}
	}

	// If we are in sent items, display recipients instead of sender
	if ($folderid == -2) {
		if (!empty($mail['recipients'])) {
			$mail['recipients'] = decodelist($mail['recipients'], false);
		} else {
			$mail['recipients'] = '&nbsp;';
		}
	}

	// Fix the subject
	decode_subject($mail['subject']);

	// Replied or forwarded?
	if ($mail['status'] & MAIL_REPLIED) {
		$mail['image'] .= '_replied';
	} elseif ($mail['status'] & MAIL_FORWARDED) {
		$mail['image'] .= '_forwarded';
	}

	// Old or new?
	if ($mail['status'] & MAIL_READ) {
		$mail['unreadstyle'] = '';
	} else {
		$mail['image'] .= '_new';
		$mail['unreadstyle'] = 'style="font-weight: bold;" ';
	}

	// A system mail?
	if ($mail['status'] & MAIL_SYSMAIL) {
		$mail['sysimage'] = '<img src="'.$skin['images'].'/sysmsg.gif" alt="This message is from the system administrator" />&nbsp; ';
	} else {
		$mail['sysimage'] = '';
	}

	// Attachments Clip...
	if ($mail['attach'] > 0) {
		$mail['attach'] = '<img src="'.$skin['images'].'/paperclip.gif" alt="This message has '.$mail['attach'].' attachments" />';
	} else {
		$mail['attach'] = '&nbsp;';
	}

	// Flag image...
	$mail['isflagged'] = $mail['flagged'];
	if ($mail['flagged']) {
		$mail['flagimg'] = '<img src="'.$skin['images'].'/flag.gif" alt="This message is flagged" />';
	} else {
		$mail['flagimg'] = '&nbsp;';
	}

	// Priority...
	switch ($mail['priority']) {
		case 1:
			$mail['priority'] = '<img src="'.$skin['images'].'/prio_high.gif" alt="This message is high priority" />';
			break;
		case 5:
			$mail['priority'] = '<img src="'.$skin['images'].'/prio_low.gif" alt="This message is low priority" />';
			break;
		default:
			$mail['priority'] = '&nbsp;';
			break;
	}

	// Add to row array
	$rowjsbits .= 'rows['.($current-1)."] = $mail[messageid];\n";

	// Size
	$mail['kbsize'] = ceil($mail['size'] / 1024);

	// This is for the check all box
	$markallbg .= "getElement('row$mail[messageid]').className = ";

	// Link
	if ($mail['fromname'] != $mail['fromemail']) {
		$mail['link'] = urlencode("$mail[fromname] <$mail[fromemail]>");
	} else {
		$mail['link'] = urlencode($mail['fromemail']);
	}

	// Folder name
	if ($mail['folderid'] < 0){
		$mail['folder'] = $_folders["$mail[folderid]"]['title'];
	} else {
		$mail['folder'] = $foldertitles["$mail[folderid]"];
	}

	// Shorten long subjects and emails
	$mail['shortfromname'] = trimtext($mail['fromname'], 20);
	$mail['shortsubject'] = trimtext($mail['subject'], 80);

	// Custom columns
	$columns = '';
	foreach ($hiveuser['cols'] as $column) {
		if ($column == 'from' and $folderid == -2) {
			eval(makeeval('columns', "mailbit_to", 1));
		} else {
			eval(makeeval('columns', "mailbit_$column", 1));
		}
	}

	// Parse the template and return it
	eval(makeeval('mailbit', $templatename));
	return $mailbit;
}

// ############################################################################
// Replaces and adds target attribute for all links in $text
function fix_links_target($text) {
	do {
		$text = preg_replace('#(<a[^>]+)target=("|\')?[^"\']*\2([^>]?'.'>)#i', '\1\3', $text);
	} while (preg_match('#(<a[^>]+)target=("|\')?[^"\']*\2([^>]?'.'>)#i', $text));
	return str_replace('<a', '<a target="_blank"', $text);
}

// ############################################################################
// Gets the source of a message *ONLY FROM THE FILESYSTEM*
// If it's in the database you still need to get it yourself
// Can either take an array with a 'source' index, or just a string
// in which the source will be stored. The current 'source' must
// point to the unique name of the data file
function get_source(&$message) {
	if (!getop('flat_use')) {
		return;
	}

	$source = ((is_array($message)) ? ($message['source']) : ($message));
	if (strpos($source, '/') === false) {
		$filename = getop('flat_prefix').$source.'.dat';
	} else {
		$filename = str_replace('/', '/'.getop('flat_prefix'), $source).'.dat';
	}

	$filepath = getop('flat_path', true).'/'.$filename;
	$fp = fopen($filepath, 'rb');
	if (!is_resource($fp)) { 
		return;
	}

	if (is_array($message)) {
		for ($message['source'] = ''; !feof($fp); $message['source'] .= fgets($fp, 4096));
	} else {
		for ($message = ''; !feof($fp); $message .= fgets($fp, 4096));
	}
	fclose($fp);
}

// ############################################################################
// Returns the References header of a message
function get_references(&$message) {
	preg_match("/^(.*?)\r?\n\r?\n.*/s", $message, $matches);
	$headerbits = preg_split("#\r?\n#", $matches[1]);

	$find = 'References:';
	$finds = array();
	foreach ($headerbits as $header) {
		if (strtolower(substr($header, 0, strlen($find))) == strtolower($find)) {
			$finds[] = trim(substr($header, strlen($find)));
		} elseif (!empty($finds) and ltrim($header) != $header) {
			$finds[] = ltrim($header);
		} elseif (!empty($finds)) {
			break;
		}
	}
	return implode(' ', $finds);
}

// ############################################################################
// "Flattens" the multi-demensinal headers array into a single dimension one
function flatten_headers_array($input, $add = '') {
	$output = array();

	foreach ($input as $key => $value) {
		if (!empty($add)) {
			$newkey = ucfirst(strtolower($add));
		} elseif (is_numeric($key)) {
			$newkey = '';
		} else {
			$newkey = ucfirst(strtolower($key));
		}
		if (is_array($value)) {
			$output = array_merge($output, flatten_headers_array($value, $newkey));
		} else {
			$output[] = iif(!empty($newkey), $newkey.': ').$value;
		}
	}

	return $output;
}

// ############################################################################
// Takes a To or CC list and returns a formatted version of it
function decodelist($list, $addbr = false) {
	$array = explode(' ', str_replace(array('"', "'"), '', $list));

	$total = count($array);
	$last = 0;
	$return = array();
	for ($i = 0; $i < $total; $i++) {
		if (($getemail = extract_email($array["$i"])) !== false) {
			if ($lastemail == $getemail) {
				array_pop($return);
			}
			$thisreturn = '';
			if (!empty($output)) {
				$thisreturn .= "<br />\n";
			}
			for ($j = $last; $j < $i; $j++) {
				$thisreturn .= "$array[$j] ";
			}
			$return[] = $thisreturn.iif($last != $i, '(')."<a href=\"compose.email.php?email=".urlencode($getemail)."\">$getemail</a>".iif($last != $i, ')').iif($addbr, "<br />\n", '; ');
			$last = $i + 1;
			$lastemail = $getemail;
		}
	}

	return substr(implode('', $return), 0, -2);
}

// ############################################################################
// Kind of the other way around
function encodelist($list) {
	$array = explode(' ', str_replace(array('"', "'"), '', $list));

	$total = count($array);
	$last = 0;
	$return = '';
	for ($i = 0; $i < $total; $i++) {
		if (($getemail = extract_email($array["$i"])) != false) {
			if ($j != $last - 1 and $last < $i) {
				$return .= '"';
				for ($j = $last; $j < $i; $j++) {
					$return .= addslashes("$array[$j] ");
				}
				$return = trim($return).'"';
				$return .= " <$getemail>, ";
			} else {
				$return .= "$getemail, ";
			}
			$last = $i + 1;
		}
	}

	return substr($return, 0, -2);
}

// ############################################################################
// Updates message counts for custom folders
function updatefolders($userid = null) {
	global $DB_site, $hiveuser;

	if ($userid === null) {
		$userid = $hiveuser['userid'];
	}
	$updfolders = $allfolders = array();

	$all = $DB_site->query("
		SELECT folderid
		FROM hive_folder
		WHERE userid = $userid
	");
	while ($folder = $DB_site->fetch_array($all)) {
		$allfolders[] = $folder['folderid'];
	}

	$folders = $DB_site->query("
		SELECT COUNT(*) AS realcount, folderid
		FROM hive_message
		WHERE userid = $userid
		GROUP BY folderid
	");
	while ($folder = $DB_site->fetch_array($folders)) {
		$DB_site->query("
			UPDATE hive_folder
			SET msgcount = $folder[realcount]
			WHERE folderid = $folder[folderid]
			AND userid = $userid
		");
		$updfolders[] = $folder['folderid'];
	}

	$unupd = array_diff($allfolders, $updfolders);

	if (!empty($unupd)) {
		$unupd = implode(',', $unupd);
		$DB_site->query("
			UPDATE hive_folder
			SET msgcount = 0
			WHERE folderid IN ($unupd)
			AND userid = $userid
		");
	}
	rebuild_folder_cache($userid);
}

// ############################################################################
// Empties a folder either by deleting its messages or moving them to the Trash Can
// (Based on $fulldelete value)
function emptyfolder($folderid, $fulldelete = false) {
	global $hiveuser, $DB_site;

	// Delete messages
	delete_messages("folderid = $folderid AND userid = $hiveuser[userid]", $fulldelete);

	// Update the folder's message count
	if (intme($folderid) > 0) {
		$DB_site->query("
			UPDATE hive_folder
			SET msgcount = 0
			WHERE folderid = $folderid AND userid = $hiveuser[userid]
		");
		rebuild_folder_cache();
	}
}

// ############################################################################
// Deletes messages from the user's mailbox
function delete_messages($where, $fulldelete = false, $syncpop = true) {
	global $DB_site, $hiveuser;

	// Synchronize with POP3 accounts and delete message files
	if ($fulldelete) {
		if (getop('flat_use')) {
			$emails = $DB_site->query("
				SELECT source, emailid, popsize, filename, messages, uniquestr
				FROM hive_message AS message
				LEFT JOIN hive_messagefile AS messagefile ON (message.source = messagefile.filename)
				WHERE $where
			");
			$messages = $filenames = array();
			while ($email = $DB_site->fetch_array($emails)) {
				$messages["$email[source]"]++;
				$filenames["$email[source]"] = $email['messages'];
			}

			foreach ($messages as $filename => $count) {
				if ($count == $filenames["$filename"]) {
					// All instances of this message are being deleted
					if (strpos($filename, '/') === false) {
						$filepath = getop('flat_path', true).'/'.getop('flat_prefix').$filename.'.dat';
					} else {
						$filepath = getop('flat_path', true).'/'.str_replace('/', '/'.getop('flat_prefix'), $filename).'.dat';
					}
					if (!unlink($filepath)) {
						log_event(EVENT_CRITICAL, 702, array('file' => getop('flat_prefix').$filename, 'path' => getop('flat_path', true)));
					}
					$DB_site->query('
						DELETE FROM hive_messagefile
						WHERE filename = "'.addslashes($filename).'"
					');
				} else {
					// Only some are deleted, need to update the count
					$DB_site->query("
						UPDATE hive_messagefile
						SET messages = messages - $count
						WHERE filename = '".addslashes($filename)."'
					");
				}
			}
			$DB_site->reset($emails);
		} else {
			$emails = $DB_site->query("
				SELECT emailid, popsize, uniquestr
				FROM hive_message AS message
				WHERE $where
			");
		}

		if ($syncpop) {
			require_once(iif(INADMIN, '.').'./includes/functions_pop.php');
			require_once(iif(INADMIN, '.').'./includes/functions_mime.php');
			require_once(iif(INADMIN, '.').'./includes/functions_smtp.php');
			pop_sync($emails);

			if ($hiveuser['canhivepop'] and $hiveuser['synchivepop'] and $hiveuser['savetopop'] == USER_HIVEPOP_SAVEBOTH) {
				$popmsgs = hivepop_listmsgs();
				$uniquestrs = array(); // array of uniquestr => fileid
				foreach ($popmsgs as $msgfile) {
					if (getextension($msgfile) == 'data') {
						$uniquestrs[readfromfile(hivepop_folder().'/'.$msgfile)] = intval($msgfile);
					}
				}
				$DB_site->reset($emails);
				while ($email = $DB_site->fetch_array($emails)) {
					if (isset($uniquestrs["$email[uniquestr]"])) {
						hivepop_deletemsg($uniquestrs["$email[uniquestr]"]);
					}
				}
			}
		}
	}

	// Either delete all messages or move them to the Trash Can
	if ($fulldelete) {
		$DB_site->delete('message', $where);
	} else {
		$DB_site->query("UPDATE hive_message SET folderid = -3 WHERE $where");
	}
}

// ############################################################################
// Returns a formatted version of $message
function messageparse($message, $html = true, $script = true, $cid = true, $iframe = true) {
	global $cids, $messageid;

	// Consider: ("javascript:([^"]+)"|'javascript:([^']+)')
	if ($script) {
		$message = preg_replace("/<script[^>]*>[^<]+<\/script[^>]*>/is", '', $message);
	}
	if ($iframe) {
		$message = preg_replace("/<iframe[^>]*>[^<]*<\/iframe[^>]*>/is", '', $message);
	}
	if ($html) {
		$message = nl2br(addlinks(htmlchars($message), $messageid));
	}
	if ($cid and getop('allowcid') and is_array($cids)) {
		for ($i = 0; $i < count($cids); $i++) {
			$searchcids .= '|'.preg_quote($cids[$i], '#');
		}
		$message = preg_replace_callback('#cid:('.substr($searchcids, 1).')#i', 'cidattachments', $message);
	}

	return fix_links_target($message);
}

// ############################################################################
// Call-back for messageparse
function cidattachments ($match) {
	global $messageid, $parsed_message, $hiveuser;
	if (!$hiveuser['showinline'] and is_array($parsed_message['attachments'])) {
		foreach ($parsed_message['attachments'] as $key => $value) {
			if ($value['headers']['content-id'] == '<'.$match[1].'>') {
				unset($parsed_message['attachments'][$key]);
			}
		}
		if (count($parsed_message['attachments']) < 1) {
			unset($parsed_message['attachments']);
		}
	}
	// The link has to be absolute because a BASE could be in effect :(
	return getop('appurl').'/read.attachment.php?messageid='.$messageid.'&cid='.urlencode($match[1]);
}

?>