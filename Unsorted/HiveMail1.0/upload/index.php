<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: index.php,v $
// | $Date: 2002/11/11 21:51:41 $
// | $Revision: 1.67 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'index_drafts_bit,index_drafts,mailbit,index_nomails,folders_jumpbit,index,index_unreads,spacegauge,index_minifolderbit,index_minifolderbit_current,index_topbox,index_folder_select,mailbit_priority,mailbit_attach,mailbit_from,mailbit_subject,mailbit_datetime,mailbit_size,index_header_priority,index_header_attach,index_header_from,index_header_subject,index_header_datetime,index_header_size';
require_once('./global.php');

// ############################################################################
// Set the default do
if (!isset($do)) {
	$do = 'show';
}
$dobox = false;

// ############################################################################
// Get the POP accounts
if (!getop('pop3_use')) {
	require_once('./includes/pop_functions.php');
	require_once('./includes/mime_functions.php');
	require_once('./includes/smtp_functions.php');
}
if ($hiveuser['haspop'] > 0) {
	$poperrors = array();
	$pops = $DB_site->query("
		SELECT *
		FROM pop
		WHERE userid = $hiveuser[userid] AND active = 1
	");
	while ($pop = $DB_site->fetch_array($pops)) {
		$pop_socket = new POP_Socket($pop, true);
		$status = $pop_socket->fetch_and_add();
		$poperrors["$pop[popid]"] = array(
			'server' => $pop['server'],
			'error' => ''
		);
		switch ($status) {
			case 'success':
				unset($poperrors["$pop[popid]"]);
				break;
			case 'socket':
				$poperrors["$pop[popid]"]['error'] = 'Couldn\'t connect to server.';
				break;
			case 'login':
				$poperrors["$pop[popid]"]['error'] = 'The login information was not accepted.';
				break;
			default:
				$poperrors["$pop[popid]"]['error'] = 'An unexpected error encountered.';
				break;
		}
	}
	$poperror = '';
	foreach ($poperrors as $popid => $intel) {
		$poperror .= "$intel[server]: $intel[error]<br />\n";
	}
	if (!empty($poperror)) {
		$dobox = true;
		eval(makeeval('poperror', 'poperrors'));
	}
}

// ############################################################################
// Unread messages
if ($folderid == -1) {
	$unreads = '';
	if ($hiveuser['showtopbox']) {
		$DB_site->reset($getunreads);
		$unreadnums = $DB_site->num_rows($getunreads);
		for ($i = 0; $i < $unreadnums; $i++) {
			$unread = $DB_site->fetch_array($getunreads);
			if ($unread['folderid'] < 0) {
				$unread['title'] = $_folders["$unread[folderid]"]['title'];
			}
			if ($unread['messages'] == 1) {
				$s = '';
			} else {
				$s = 's';
			}
			if ($unreadnums == 1 or $unreadnums != $i + 1) {
				$unreads .= ',';
			} else {
				$unreads .= ' and';
			}
			if ($unread['folderid'] < 0) {
				$unreads .= " $unread[messages] unread message$s in your $unread[title]";
			} else {
				$unreads .= " $unread[messages] unread message$s in $unread[title]";
			}
		}
		if (!empty($unreads)) {
			$unreads = 'You have '.substr($unreads, 1).'.';
			eval(makeeval('unreads', 'index_unreads'));
			$dobox = true;
		}
	}

	// Space gauge
	if ($_mailmb != 0 and $hiveuser['maxmb'] != 0 and empty($space)) {
		$spacepercent = intval(($_mailmb / $hiveuser['maxmb']) * 100);

		if ($hiveuser['showtopbox'] or $spacepercent >= getop('minpercentforgauge')) {
			eval(makeeval('space', 'index_spacegauge'));
			$dobox = true;
		}
	}

	if ($dobox) {
		eval(makeeval('topbox', 'index_topbox'));
	} else {
		$topbox = '';
	}
}

// ############################################################################
// Get the navigation bar
makemailnav(1);

// ############################################################################
if ($do == 'show') {
	// Auto refresh the page?
	if ($hiveuser['autorefresh'] > 0) {
		$metarefresh = '<meta http-equiv="refresh" content="'.$hiveuser['autorefresh'].'; url='.$REQUEST_URI.'" />';
	} else {
		$metarefresh = '';
	}

	// Do we have unsent drafts?
	$drafts = $DB_site->query("
		SELECT *
		FROM draft
		WHERE dateline = 0 AND userid = $hiveuser[userid]
	");
	$draftbits = '';
	if ($draft = $DB_site->fetch_array($drafts)) {
		do {
			$data = unserialize($draft['data']);
			$mail['subject'] = iif(!empty($data['subject']), $data['subject'], '[no title]');
			eval(makeeval('draftbits', 'index_drafts_bit', 1));
		} while ($draft = $DB_site->fetch_array($drafts));
	}

	// Set default page number and per page values
	if (intme($pagenumber) < 1) {
		$pagenumber = 1;
	}
	if (intme($perpage) < 1)	{
		$perpage = $hiveuser['perpage'];
	}

	// Get total number of emails in this folder
	$totalmails = $DB_site->get_field("
		SELECT COUNT(*) AS count
		FROM message
		WHERE ".iif(!isset($findemail), "folderid = $folderid AND ")."userid = $hiveuser[userid]
	");

	// Handle pagination stuff
	$limitlower = ($pagenumber-1)*$perpage+1;
	$limitupper = ($pagenumber)*$perpage;
	if ($limitupper > $totalmails) {
		$limitupper = $totalmails;
		if ($limitlower > $totalmails) {
			$limitlower = $totalmails-$perpage;
		}
	}
	if ($limitlower <= 0) {
		$limitlower = 1;
	}

	// Sort order
	$sortorder = strtolower($sortorder);
	if ($sortorder != 'asc') {
		$sortorder = 'desc';
		$newsortorder = 'asc';
		$arrow_image = 'arrow_up';
	} else {
		$newsortorder = 'desc';
		$arrow_image = 'arrow_down';
	}
	$sortorder = strtoupper($sortorder);

	// Sort field
	$sortimages = array();
	switch ($sortby) {
		case 'attach':
		case 'subject':
		case 'name':
		case 'dateline':
		case 'priority':
		case 'size':
			$sortimages["$sortby"] = '</a>&nbsp;&nbsp;<a href="index.php?folderid='.$folderid.'&perpage='.$perpage.'&sortorder='.$newsortorder.'&sortby='.$sortby.'"><img src="'.$skin['images'].'/'.$arrow_image.'.gif" align="middle" alt="" border="0" />';
			break;
		default:
			$sortimages['dateline'] = '</a>&nbsp;&nbsp;<a href="index.php?folderid='.$folderid.'&perpage='.$perpage.'&sortorder='.$newsortorder.'&sortby=dateline"><img src="'.$skin['images'].'/'.$arrow_image.'.gif" align="middle" alt="" border="0" />';
			$sortby = 'dateline';
	}

	// Get all the emails
	$mails = $DB_site->query("
		SELECT *
		FROM message
		WHERE folderid = $folderid AND userid = $hiveuser[userid]
		ORDER BY $sortby $sortorder LIMIT ".($limitlower-1).", $perpage
	");

	$rowjsbits = '';
	$markallbg = '';
	$current = 1;
	if ($DB_site->num_rows($mails) > 0) {
		$pagenav = getpagenav($totalmails, "index.php?folderid=$folderid&perpage=$perpage&sortorder=$sortorder&sortby=$sortby");
		$mailbits = '';
		while ($mail = $DB_site->fetch_array($mails)) {
			$mailbits .= makemailbit($mail);
			$current++;
		}
		$checkallfirst .= "'$skin[firstalt]';\n";
		$checkallsecond .= "'$skin[secondalt]';\n";
	} else {
		$pagenav = '';
		$limitlower = 0;
		eval(makeeval('mailbits', 'index_nomails'));
	}

	// Show the delete note
	if ($folderid != -3) {
		$deletenote = '<b>Note:</b> deleted messages will be moved to the Trash Can.<br />Hold down Shift key when clicking to completely delete the messages.';
	} else {
		$deletenote = '&nbsp;';
	}

	// Custom columns
	$colheaders = '';
	foreach ($hiveuser['cols'] as $column) {
		if ($column == 'datetime' and $folderid == -2) {
			eval(makeeval('colheaders', "index_header_$column"."_sentitems", 1));
		} else {
			eval(makeeval('colheaders', "index_header_$column", 1));
		}
	}

	$youarehere = getop('appname').' &raquo; '.$thisfoldertitle;
	eval(makeeval('echo', 'index'));
}

// ############################################################################
if ($do == 'selfolder') {
	define('LOAD_MINI_TEMPLATES', true);

	// Make the folder jump
	$movefolderjump = '';
	$selected = '';
	$folders = $DB_site->query("
		SELECT *
		FROM folder
		WHERE userid = $hiveuser[userid]
	");
	while ($folder = $DB_site->fetch_array($folders)) {
		eval(makeeval('movefolderjump', 'folders_jumpbit', 1));
	}

	// Bye bye
	eval(makeeval('echo', 'index_folder_select'));
}

// ############################################################################
if ($_POST['do'] == 'dostuff') {
	if ($folderid > 0) {
		$folder = getinfo('folder', $folderid);
	}
	if ($movetofolderid > 0 and !empty($move)) {
		$movetofolder = getinfo('folder', $movetofolderid);
	}

	$msgids = '0';
	if (is_array($mails)) {
		// Get the list of message ID's
		// [I could use implode() but feel that this is more secure]
		foreach ($mails as $key => $val) {
			$msgids .= ','.intval($key);
		}

		// Do whatever it is we need
		if (!empty($delete)) {
			if ($folderid != -3) {
				$DB_site->query("
					UPDATE message
					SET folderid = -3
					WHERE messageid IN ($msgids)
					AND userid = $hiveuser[userid]
				");
			} else {
				$DB_site->query("
					DELETE FROM message
					WHERE messageid IN ($msgids)
					AND userid = $hiveuser[userid]
				");
			}
		} elseif (!empty($move)) {
			$DB_site->query("
				UPDATE message
				SET folderid = $movetofolderid
				WHERE messageid IN ($msgids) AND userid = $hiveuser[userid]
			");
		} elseif (!empty($mark)) {
			switch ($markas) {
				case 'read':
					$DB_site->query("
						UPDATE message
						SET status = status + ".MAIL_READ."
						WHERE messageid IN ($msgids) AND userid = $hiveuser[userid] AND NOT(status & ".MAIL_READ.")
					");
					break;
				case 'unread':
					$DB_site->query("
						UPDATE message
						SET status = status - ".MAIL_READ."
						WHERE messageid IN ($msgids) AND userid = $hiveuser[userid] AND status & ".MAIL_READ."
					");
					break;
				case 'flagged':
					$DB_site->query("
						UPDATE message
						SET status = status + ".MAIL_FLAGGED."
						WHERE messageid IN ($msgids) AND userid = $hiveuser[userid] AND NOT(status & ".MAIL_FLAGGED.")
					");
					break;
				case 'unflagged':
					$DB_site->query("
						UPDATE message
						SET status = status - ".MAIL_FLAGGED."
						WHERE messageid IN ($msgids) AND userid = $hiveuser[userid] AND status & ".MAIL_FLAGGED."
					");
					break;
				case 'replied':
					$DB_site->query("
						UPDATE message
						SET status = status + ".MAIL_REPLIED."
						WHERE messageid IN ($msgids) AND userid = $hiveuser[userid] AND NOT(status & ".MAIL_REPLIED.")
					");
					break;
				case 'unreplied':
					$DB_site->query("
						UPDATE message
						SET status = status - ".MAIL_REPLIED."
						WHERE messageid IN ($msgids) AND userid = $hiveuser[userid] AND status & ".MAIL_REPLIED."
					");
					break;
				case 'forwarded':
					$DB_site->query("
						UPDATE message
						SET status = status + ".MAIL_FORWARDED."
						WHERE messageid IN ($msgids) AND userid = $hiveuser[userid] AND NOT(status & ".MAIL_FORWARDED.")
					");
					break;
				case 'unforwarded':
					$DB_site->query("
						UPDATE message
						SET status = status - ".MAIL_FORWARDED."
						WHERE messageid IN ($msgids) AND userid = $hiveuser[userid] AND status & ".MAIL_FORWARDED."
					");
					break;
			}
		} elseif (!empty($forward)) {
			// If only one message is selected...
			if (substr_count($msgids, ',') == 1 and !$asattach) {
				header('Location: compose.email.php?special=forward&messageid='.substr($msgids, 2));
				exit;
			}

			$msgs = $DB_site->query("
				SELECT subject, source
				FROM message
				WHERE messageid IN ($msgids) AND userid = $hiveuser[userid]
			");
			$data = array();
			while ($msg = $DB_site->fetch_array($msgs)) {
				$data['attach'][] = array(
					'filename' => "$msg[subject].eml",
					'type' => 'message/rfc822',
					'size' => strlen($msg['source']),
					'data' => $msg['source']
				);
			}

			$DB_site->query("
				INSERT INTO draft
				SET draftid = NULL, userid = $hiveuser[userid], dateline = ".TIMENOW.", data = '".addslashes(serialize($data))."'
			");
			$draftid = $DB_site->insert_id();
			header("Location: compose.email.php?draftid=$draftid");
			exit;
		}

		// Update the folders count
		if (empty($mark)) {
			$folders = $DB_site->query("
				SELECT *
				FROM folder
				WHERE userid = $hiveuser[userid]
			");
			while ($folder = $DB_site->fetch_array($folders)) {
				$msgcount = $DB_site->get_field("
					SELECT COUNT(*) AS count
					FROM message
					WHERE folderid = $folder[folderid]
				");
				$DB_site->query("
					UPDATE folder
					SET msgcount = $msgcount
					WHERE folderid = $folder[folderid]
				");
			}
		}

		// Redirect
		if (!empty($move)) {
			if ($movetofolderid > 0) {
				$newfolder = $DB_site->query_first("
					SELECT title
					FROM folder
					WHERE folderid = $movetofolderid
				");
			} else {
				if (array_key_exists($movetofolderid, $_folders)) {
					$newfolder['title'] = $_folders["$movetofolderid"]['title'];
				}
			}
			eval(makeredirect("redirect_msgmoved", "index.php?folderid=$movetofolderid"));
		} elseif (!empty($mark)) {
			$es = 's have';
			if (!empty($searchid)) {
				eval(makeredirect("redirect_markedas", 'search.results.php?searchid='.intval($searchid)));
			} else {
				eval(makeredirect("redirect_markedas", "index.php?folderid=$folderid"));
			}
		} else {
			if ($folderid == -3) {
				$folderid = -1;
			}
			eval(makeredirect("redirect_msgdeleted", "index.php?folderid=$folderid"));
		}
	} else {
		invalid('messages');
	}
}

?>