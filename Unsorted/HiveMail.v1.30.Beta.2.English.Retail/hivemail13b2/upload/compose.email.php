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
// | $RCSfile: compose.email.php,v $ - $Revision: 1.51 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'compose_attachbit,compose_reply,compose';
require_once('./global.php');
require_once('./includes/functions_pop.php');
require_once('./includes/functions_mime.php');

// ############################################################################
// Generate the navigation bar
makemailnav(2);

// ############################################################################
// If we're using temp mail data get it
if (isset($draftid)) {
	$draft = getinfo('draft', $draftid, false, false);
	if (!$draft) {
		unset($draft);
	}
} else {
	unset($draft);
}

// ############################################################################
// Delete all temp mail data older than 60 minutes
$DB_site->query('
	DELETE FROM hive_draft
	WHERE dateline < '.(TIMENOW - (60 * 60)).'
	AND dateline > 0'. // But don't delete drafts
	iif(isset($draftid), ' AND draftid <> '.$draftid)
);

// ############################################################################
if (isset($draft)) {
	// Are we using the current POST data or the data from the database?
	if (!$save) {
		$data = unserialize_base64($draft['data']);
	} else {
		$tempdata = unserialize_base64($draft['data']);
		$data['attach'] = $tempdata['attach'];
		unset($tempdata);
	}
	unset($draft['data']);

	// Update the dateline so the data won't expire
	// (But only if it's not a draft)
	if ($draft['dateline']>0) {
		$DB_site->query("
			UPDATE hive_draft
			SET dateline = ".TIMENOW."
			WHERE draftid = $draftid
		");
	}

	// Just in case the user switches browsers in between
	$data['html'] = ($data['html'] and $hiveuser['cansendhtml']);
	$usingold = true;
} else {
	// Create a new record in the database for this email
	$DB_site->query("
		INSERT INTO hive_draft
		(draftid, userid, dateline, data) VALUES
		(NULL, $hiveuser[userid], ".TIMENOW.", '')
	");
	$draftid = $DB_site->insert_id();
	if (!is_array($data)) {
		$data = array();
	}
	$data += array('sendby' => 0, 'html' => $hiveuser['wysiwyg'], 'addedsig' => 0);
	if (isset($email)) {
		$data['to'] = htmlchars(urldecode($email));
	}
	$usingold = false;
}

// ############################################################################
// Some WYSIWYG editor adjustments
if (!isset($data['message']) and isset($message)) {
	$data['message'] = $message;
}

// ############################################################################
// Hanlde replies / forwards
if (!$usingold and ($special == 'reply' or $special == 'replyall' or $special == 'forward')) {
	if (isset($popid)) {
		$mail = pop_decodemail($popid, $msgid);
		$frompop = true;
		$data['sendby'] = $mail['popid'];
	} else {
		$mail = getinfo('message', $messageid);
		get_source($mail);
		decodemime($mail['source'], $special == 'forward');
		$frompop = false;
		$data['sendby'] = $mail['popid'];
	}
	if (trim($parsed_message['text'][0]) != '') {
		$mail['message'] = trim($parsed_message['text'][0]);
	} elseif ($msg_nohtml = trim(strip_tags($parsed_message['html'][0]))) {
		$mail['message'] = $msg_nohtml;
	} else {
		$mail['message'] = '[no message]';
	}
	$mail['datetime'] = hivedate($mail['dateline'], 'l, F d, Y h:i A');
	$hiveuser['replychar'] = unhtmlchars($hiveuser['replychar']);
	$mail['message'] = str_replace("\n", "\n$hiveuser[replychar] ", $mail['message']);
	$mail['to'] = $parsed_message['headers']['to'];
	$mail['cc'] = $parsed_message['headers']['cc'];
	decode_subject($mail['subject'], true);
	if ($special == 'forward') {
		if (!$frompop) {
			$data['special'] = "fw-$messageid";
		}

		if (!$attach) {
			if ($mail['attach'] > 0 and is_array($parsed_message['attachments'])) {
				foreach ($parsed_message['attachments'] as $attachnum => $attachinfo) {
					if (($endpos = strpos($attachinfo['headers']['content-type'], ';')) !== false) {
						$attachinfo['headers']['content-type'] = substr($attachinfo['headers']['content-type'], 0, $endpos);
					}
					$data['attach'][] = array(
						'filename' => $attachinfo['filename'],
						'type' => $attachinfo['headers']['content-type'],
						'size' => strlen($attachinfo['data']),
						'data' => $attachinfo['data']
					);
					unset($attachinfo, $parsed_message['attachments'][$attachnum]['data']);
				}
			}

			if (substr($mail['subject'], 0, 2) != 'Fw') {
				$data['subject'] = 'Fw: ';
			}
			$data['subject'] .= $mail['subject'];
			if ($hiveuser['includeorig']) {
				eval(makeeval('data[message]', 'compose_reply', 0));
				$data['message'] = htmlchars($data['message']);
			}
		} else {
			$data['attach'][] = array(
				'filename' => "$mail[subject].eml",
				'type' => 'message/rfc822',
				'size' => strlen($mail['source']),
				'data' => $mail['source']
			);
			if (substr($mail['subject'], 0, 2) != 'Fw') {
				$data['subject'] = 'Fw: ';
			}
			$data['subject'] .= $mail['subject'];
		}
	} else {
		if (substr($mail['subject'], 0, 2) != 'Re') {
			$data['subject'] = 'Re: ';
		}
		if (isset($parsed_message['headers']['reply-to']) and ($data['to'] = extract_email($parsed_message['headers']['reply-to']))) {
		} else {
			$data['to'] = $mail['email'];
		}
		if (!$frompop) {
			$data['special'] = "re-$messageid";
		}

		if ($special == 'replyall') {
			// Create an array with all possible emails this user can have
			$selfemails = array();
			foreach ($hiveuser['aliases'] as $alias) {
				foreach ($_options['domainnames'] as $a_domainname) {
					$selfemails[] = "$alias$a_domainname";
				}
			}
			$to_emails = extract_email($headers['to'].' '.$data['to'], true);
			$cc_emails = extract_email($headers['cc'], true);
			$data['to'] = $data['cc'] = array();
			foreach ($to_emails as $to_email) {
				if (!array_contains($to_email, $data['to']) and !array_contains($to_email, $selfemails)) {
					$data['to'][] = $to_email;
				}
			}
			if (is_array($cc_emails)) {
				foreach ($cc_emails as $cc_email) {
					if (!array_contains($cc_email, $data['cc']) and !array_contains($cc_email, $data['to']) and !array_contains($cc_email, $selfemails)) {
						$data['cc'][] = $cc_email;
					}
				}
			}
			$data['to'] = implode(', ', $data['to']);
			$data['cc'] = implode(', ', $data['cc']);
		}

		$data['subject'] .= $mail['subject'];
		if ($hiveuser['includeorig']) {
			eval(makeeval('data[message]', 'compose_reply', 0));
			$data['message'] = htmlchars($data['message']);
		}
	}

	$DB_site->query("
		UPDATE hive_draft
		SET data = '".addslashes(base64_encode(serialize($data)))."'
		WHERE draftid = $draftid
	");
}

// ############################################################################
// Build some select options
$aliasoptions = $popoptions = '';
$toalias = $hiveuser['username'];
$hiveuser['newaliases'] = array_merge(array($hiveuser['username']), $hiveuser['aliases']);

// See if there is already an alias or POP3 account specified
if (!empty($data['sendby']) and $pop = getinfo('pop', $data['sendby'], false, false)) {
	$data['sendby'] = $data['sendby'];

// Try to find an alias in the original message
} else {
	if (isset($mail['recipients'])) {
		$emails_found = preg_match_all('/(('.REGEX_EMAIL_USER.')(@'.REGEX_EMAIL_DOMAIN.'))/i', $mail['recipients'], $allemails);
		if (is_array($allemails)) {
			for ($i = 0; $i < $emails_found; $i++) {
				if (array_contains($allemails[3][$i], getop('domainnames')) and array_contains($allemails[2][$i], $hiveuser['newaliases'])) {
					$data['sendby'] = $allemails[2][$i];
					break;
				}
			}
		}
	}
	// Use default user option
	if (empty($data['sendby'])) {
		$defcompose = explode('-', $hiveuser['defcompose']);
		if ($defcompose[0] == 'username') {
			$data['sendby'] = $hiveuser['username'];
		} else {
			$data['sendby'] = $defcompose[1];
		}
	}
}
$defselected = iif($data['sendby'] == $hiveuser['username'], 'selected="selected"');
foreach ($hiveuser['aliases'] as $alias) {
	$aliasoptions .= '<option value="'.$alias.'"'.iif($data['sendby'] == $alias, ' selected="selected"').'>'.$hiveuser['realname'].' &lt;'.$alias.$hiveuser['domain'].'&gt;</option>';
}
if ($hiveuser['canpop']) {
	$pops = $DB_site->query("
		SELECT *
		FROM hive_pop
		WHERE smtp_server <> ''
		AND smtp_port <> ''
		AND userid = $hiveuser[userid]
		ORDER BY accountname
	");
	while ($thispop = $DB_site->fetch_array($pops)) {
		$popoptions .= "<option value=\"$thispop[popid]\"".iif($data['sendby'] == $thispop['popid'], ' selected="selected"').">$thispop[displayname] &lt;$thispop[displayemail]&gt; ($thispop[accountname])</option>\n";
	}
}
if (!$usingold) {
	if ($data['sendby'] != 0) {
		$data['replyto'] = $pop['replyto'];
	} else {
		$data['replyto'] = $hiveuser['replyto'];
	}
}

// ############################################################################
// HTMLize some stuff
if ($usingold) {
	$data['to'] = htmlchars($data['to']);
	$data['cc'] = htmlchars($data['cc']);
	$data['bcc'] = htmlchars($data['bcc']);
	$data['subject'] = htmlchars($data['subject']);
}

// ############################################################################
// Create the input markers
if ($usingold) {
	$savecopychecked = iif($data['savecopy'], 'checked="checked"');
	$requestreadchecked = iif($data['requestread'], 'checked="checked"');
	$deleteorigchecked = iif($data['deleteorig'], 'checked="checked"');
	$addtobookchecked = iif($data['addtobook'], 'checked="checked"');
	$prio = array($data['priority'] => 'selected="selected"');
} else {
	$savecopychecked = iif($hiveuser['savecopy'], 'checked="checked"');
	$requestreadchecked = iif($hiveuser['requestread'], 'checked="checked"');
	$deleteorigchecked = '';
	$addtobookchecked = iif($hiveuser['addrecips'], 'checked="checked"');
	$prio = array(3 => 'selected="selected"');
}

// ############################################################################
// Show the attachment list
$attachlist = '';
if (is_array($data['attach'])) {
	reset($data['attach']);
	while (list($number, ) = each($data['attach'])) {
		$attachdata = &$data['attach'][$number];
		eval(makeeval('attachlist', 'compose_attachbit', 1));
	}
}

// ############################################################################
// Contacts array for IE completion
$contacts = $DB_site->query("
	SELECT name, email, emailinfo
	FROM hive_contact
	WHERE userid IN (0, $hiveuser[userid])
	ORDER BY name
");
$contactArray = '';
while ($contact = $DB_site->fetch_array($contacts)) {
	$contact['email'] = addslashes($contact['email']);
	$contact['name'] = addslashes($contact['name']);
	if ($contact['email'] != $contact['name']) {
		$contactArray .= ", '".addslashes("$contact[name] <$contact[email]>")."'";
	}
	$contactArray .= ", '".addslashes($contact['email'])."'";
	$contact['emailinfo'] = unserialize($contact['emailinfo']);
	foreach ($contact['emailinfo'] as $contact['email']) {
		$contactArray .= ", '".addslashes($contact['email'])."'";
		if ($contact['email'] != $contact['name']) {
			$contactArray .= ", '".addslashes("$contact[name] <$contact[email]>")."'";
		}
	}
}
$contactArray = substr($contactArray, 2);

// ############################################################################
// Too many contacts... no options
if ($hiveuser['maxcontacts'] > 0) {
	$toomanycontacts = ($DB_site->num_rows($contacts) >= $hiveuser['maxcontacts']);
} else {
	$toomanycontacts = false;
}

// ############################################################################
// Get signatures
$getsigs = $DB_site->query("
	SELECT *
	FROM hive_sig
	WHERE userid = $hiveuser[userid]
	ORDER BY isdefault DESC
");
$sigs = '';
$randsig = (($DB_site->num_rows($getsigs) == 0) ? 0 : rand(1, $DB_site->num_rows($getsigs)));
$i = 1;
while ($sig = $DB_site->fetch_array($getsigs)) {
	if (($hiveuser['userandomsig'] and $i++ == $randsig) or (!$hiveuser['userandomsig'] and $sig['isdefault']) or $DB_site->num_rows($getsigs) == 1) {
		$signature = $sig['signature'];
	}

	$sigs .= iif($sig['isdefault'], '<b>')."<a href=\"#\" onClick=\"insertSig('sig$sig[sigid]'); return false;\">$sig[name]</a><br />\n".iif($sig['isdefault'], '</b>');

	if ($data['html']) {
		$sigs .= "<input type=\"hidden\" name=\"sig$sig[sigid]\" value=\"".htmlchars("<br /><br />".preg_replace("#\r?\n#", '<br />', $sig['signature']))."\" />\n";
	} else {
		$sigs .= "<input type=\"hidden\" name=\"sig$sig[sigid]\" value=\"".htmlchars("\n\n$sig[signature]")."\" />\n";
	}
}

// ############################################################################
// Regular editor or HTML editor?
if ($data['html']) {
	$fontbits = explode('|', $hiveuser['font']);
	$switchmode = 'plain text mode';
	if (!$usingold and ($special == 'reply' or $special == 'replyall' or $special == 'forward')) {
		$data['message'] = nl2br($data['message']);
	} elseif (!$usingold) {
		$data['bgcolor'] = iif($fontbits[4] != 'None', $fontbits[4], $skin['formbackground']);
	} elseif ($draft['dateline'] != 0) {
		$data['message'] = str_replace("\n", '<BR>', $data['message']);
	}
	if (empty($data['bgcolor'])) {
		$data['bgcolor'] = $skin['formbackground'];
	}
	$data['message'] = preg_replace(array("#(\r\n)|(\r)|(\n)#", '#"#', '#<#'), array('\n', '\"', '<"+"'), $data['message']);
	if (substr($data['message'], 0, strlen('<"+"DIV')) != '<"+"DIV' and !empty($data['message'])) {
		$data['message'] = '<DIV style=\"font-family: '.$fontbits[0].'; color: '.$fontbits[3].'; font-size: '.$fontbits[1].'pt;\">'.$data['message'].'</DIV>';
	} elseif (empty($data['message'])) {
		$data['message'] = '<DIV style=\"font-family: '.$fontbits[0].'; color: '.$fontbits[3].'; font-size: '.$fontbits[1].'pt;';
		switch ($fontbits[2]) {
			case 'Italic':
				$data['message'] .= ' font-style: italic;';
				break;
			case 'Bold':
				$data['message'] .= ' font-weight: bold;';
				break;
			case 'Bold Italic':
				$data['message'] .= ' font-weight: bold; font-style: italic;';
				break;
		}
		$data['message'] .= '\">';
	}
	$esc_sig = preg_replace(array("#(\r)?\n#", '#"#', '#<#'), array('<br />', '\"', '<"+"'), $signature);
	if (!$usingold and $hiveuser['autoaddsig'] and !empty($signature)) {
		if ($special == 'reply' or $special == 'replyall' or $special == 'forward') {
			if (!$hiveuser['dontaddsigonreply']) {
				$data['message'] = $esc_sig.'</DIV><br />'.$data['message'];
			}
		} else {
			$data['message'] .= "<br /><br />$esc_sig</DIV>";
		}
	}
	$signature = "<br /><br />$signature";
} else {
	$switchmode = 'rich text mode';
	if (!empty($data['plainmessage'])) {
		$data['message'] = $data['plainmessage'];
	} else {
		$data['message'] = str_replace('<BR>', "\n", $data['message']);
		$data['message'] = str_replace('<P>', "\n\n", $data['message']);
		$data['message'] = str_replace('</P>', '', $data['message']);
	}
	if (!$usingold and $hiveuser['autoaddsig'] and !empty($signature)) {
		if ($special == 'reply' or $special == 'replyall' or $special == 'forward') {
			if (!$hiveuser['dontaddsigonreply']) {
				$data['message'] = "$signature\n\n$data[message]";
			}
		} else {
			$data['message'] .= "\n\n$signature";
		}
	}
	$signature = "\n\n$signature";
}

// ############################################################################
// Field that gets focus
if (empty($data['to'])) {
	$startfield = 'to';
} elseif (empty($data['subject'])) {
	$startfield = 'subject';
} else {
	if ($data['html']) {
		$startfield = ''; // Can't focus here unfortunately
	} else {
		$startfield = 'tmessage';
	}
}
if (!empty($startfield)) {
	$focusfield = 'document.forms.composeform.'.$startfield.'.focus();';
} else {
	$focusfield = '';
}

$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; Send New Mail';
eval(makeeval('echo', 'compose'));

?>