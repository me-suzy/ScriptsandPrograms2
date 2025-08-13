<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: compose.email.php,v $
// | $Date: 2002/11/11 21:51:41 $
// | $Revision: 1.42 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'compose_attachbit,compose_reply,compose';
require_once('./global.php');

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
// Delete all temp mail data older than 15 minutes or empty
$DB_site->query('
	DELETE FROM draft
	WHERE dateline < '.(TIMENOW-(60*15)).'
	AND dateline <> 0'. // But don't delete drafts
	iif(isset($draftid), ' AND draftid <> '.$draftid)
);

// ############################################################################
if (isset($draft)) {
	// Are we using the current POST data or the data from the database?
	if (!$save) {
		$data = unserialize($draft['data']);
	} else {
		$tempdata = unserialize($draft['data']);
		$data['attach'] = $tempdata['attach'];
	}

	// Update the dateline so the data won't expire
	// (But only if it's not a draft)
	if ($draft['dateline']>0) {
		$DB_site->query("
			UPDATE draft
			SET dateline = ".TIMENOW."
			WHERE draftid = $draftid
		");
	}
	$usingold = true;
} else {
	// Create a new record in the database for this email
	$DB_site->query("
		INSERT INTO draft
		(draftid, userid, dateline, data) VALUES
		(NULL, $hiveuser[userid], ".TIMENOW.", '')
	");
	$draftid = $DB_site->insert_id();
	$data = array('html' => $hiveuser['wysiwyg'], 'addedsig' => 0);
	if (isset($email)) {
		$data['to'] = htmlspecialchars(urldecode($email));
	}
	$usingold = false;
}

// ############################################################################
// Some WYSIWYG editor adjustments
if (!$data['html'] and !isset($data['message'])) {
	$data['message'] = $message;
}

// ############################################################################
// Hanlde replies / forwards
if (!$usingold and ($special == 'reply' or $special == 'replyall' or $special == 'forward')) {
	$mail = getinfo('message', $messageid);
	$mail['datetime'] = hivedate($mail['dateline'], 'l, F d, Y h:i A');
	$mail['message'] = str_replace("\n", "\n$hiveuser[replychar] ", $mail['message']);
	while (substr($mail['subject'], -5) == '; Re:' or substr($mail['subject'], -5) == '; Fw:') {
		$mail['subject'] = substr($mail['subject'], 0, -5);
	}
	if ($special == 'forward') {
		$data['special'] = "fw-$messageid";

		if (!$attach) {
			if ($mail['attach'] > 0) {
				require_once('./includes/mime_functions.php');
				decodemime($mail['source']);
				foreach ($parsed_message['attachments'] as $attachnum => $attachinfo) {
					$data['attach'][] = array(
						'filename' => $attachinfo['filename'],
						'type' => $mimetypes[strtolower(substr(strrchr($filename, '.'), 1))],
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
			if ($hiveuser['options'] & USER_INCLUDEORIG) {
				eval(makeeval('data[message]', 'compose_reply', 0));
			}
		} else {
			$data['attach'][] = array(
				'filename' => "$mail[subject].eml",
				'type' => 'message/rfc822',
				'size' => strlen($mail['source']),
				'data' => $mail['source']
			);
		}
	} else {
		if (substr($mail['subject'], 0, 2) != 'Re') {
			$data['subject'] = 'Re: ';
		}
		$data['to'] = $mail['email'];
		$data['special'] = "re-$messageid";

		if ($special == 'replyall') {
			require_once('./includes/mime_functions.php');
			decodemime($mail['source']);
			$recips = array_unique(array_merge(explode(' ', $headers['to']), explode(' ', $headers['cc'])));
			foreach ($recips as $value) {
				if (preg_match('#([-.a-z0-9_]+@[-.a-z0-9_)]+)#', $value, $getemail)) {
					$data['to'] .= ", $getemail[1]";
				}
			}
		}

		$data['subject'] .= $mail['subject'];
		if ($hiveuser['options'] & USER_INCLUDEORIG) {
			eval(makeeval('data[message]', 'compose_reply', 0));
		}
	}

	$DB_site->query("
		UPDATE draft
		SET data = '".addslashes(serialize($data))."'
		WHERE draftid = $draftid
	");			
}

// ############################################################################
// HTMLize some stuff
if ($usingold) {
	$data['to'] = htmlspecialchars($data['to']);
	$data['cc'] = htmlspecialchars($data['cc']);
	$data['bcc'] = htmlspecialchars($data['bcc']);
	$data['subject'] = htmlspecialchars($data['subject']);
}

// ############################################################################
// Create the input markers
if ($usingold) {
	$savecopychecked = iif($data['savecopy'], 'checked="checked"');
	$requestreadchecked = iif($data['requestread'], 'checked="checked"');
	$addtobookchecked = iif($data['addtobook'], 'checked="checked"');
	$prio = array($data['priority'] => 'selected="selected"');
} else {
	$savecopychecked = iif($hiveuser['options'] & USER_SAVECOPY, 'checked="checked"');
	$requestreadchecked = iif($hiveuser['options'] & USER_REQUESTREAD, 'checked="checked"');
	$addtobookchecked = iif($hiveuser['options'] & USER_ADDRECIPS, 'checked="checked"');
	$prio = array(3 => 'selected="selected"');
}

// ############################################################################
// Show the attachment list
if (is_array($data['attach']) and sizeof($data['attach']) > 0) {
	$attachlist = '';
	foreach ($data['attach'] as $attachdata) {
		eval(makeeval('attachlist', 'compose_attachbit', 1));
	}
} else {
	$attachlist = 'No attachments.<br />';
}

// ############################################################################
// Contacts array for IE completion
$contacts = $DB_site->query("
	SELECT contactid, email, name
	FROM contact
	WHERE userid = $hiveuser[userid]
	ORDER BY name
");
$contactArray = '';
while ($contact = $DB_site->fetch_array($contacts)) {
	if ($contact['email'] != $contact['name']) {
		$contactArray .= ", '$contact[name] <$contact[email]>'";
	}
	$contactArray .= ", '$contact[email]'";
}
$contactArray = substr($contactArray, 2);

// ############################################################################
// Show the correct Draft button
if (isset($draft) and $draft['dateline'] == 0) {
	$draftbutton = 'Remove Draft';
	$updatedraft = '<input type="submit" class="bginput" name="updatedraft" value="Update Draft" onClick="this.form.action=\'compose.draft.php\'; return true;" />';
} else {
	$draftbutton = 'Save as Draft';
	$updatedraft = '';
}

// ############################################################################
// Regular editor or HTML editor?
if ($data['html']) {
	$fontbits = explode('|', $hiveuser['font']);
	$switchmode = 'plain text editor';
	if (!$usingold and ($special == 'reply' or $special == 'replyall' or $special == 'forward')) {
		$data['message'] = nl2br($data['message']);
	} elseif (!$usingold) {
		$data['bgcolor'] = iif($fontbits[4] != 'None', $fontbits[4], $skin['formbackground']);
	} elseif ($draft['dateline'] != 0) {
		$data['message'] = $message; //htmlspecialchars($message);
		$data['message'] = str_replace("\n", '<BR>', $data['message']);
	} else {
		$data['message'] = nl2br($data['message']);
	}
	if (empty($data['bgcolor'])) {
		$data['bgcolor'] = $skin['formbackground'];
	}
	$data['message'] = preg_replace(array("#(\r\n)|(\r)|(\n)#", '#"#', '#<#'), array('\n', '\"', '<"+"'), $data['message']);
	if (substr($data['message'], 0, strlen('<div')) != '<div' and !empty($data['message'])) {
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
	if (!$usingold and $hiveuser['autoaddsig'] and !empty($hiveuser['signature'])) {
		if (!(($special == 'reply' or $special == 'replyall' or $special == 'forward') and $hiveuser['dontaddsigonreply'])) {
			$data['message'] .= "<br /><br />".preg_replace("#(\r)?\n#", '<br />', $hiveuser['signature']).'</DIV>';
		}
	}
	$signature = "<br /><br />$hiveuser[signature]";
} else {
	$switchmode = 'HTML editor';
	$data['message'] = str_replace('<BR>', "\n", $data['message']);
	$data['message'] = str_replace('<P>', "\n\n", $data['message']);
	$data['message'] = str_replace('</P>', '', $data['message']);
	if (!$usingold and $hiveuser['autoaddsig'] and !empty($hiveuser['signature'])) {
		if (!(($special == 'reply' or $special == 'replyall' or $special == 'forward') and $hiveuser['dontaddsigonreply'])) {
			$data['message'] .= "\n\n\n$hiveuser[signature]";
		}
	}
	$signature = "\n\n$hiveuser[signature]";
}

$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; Send New Mail';
eval(makeeval('echo', 'compose'));

?>