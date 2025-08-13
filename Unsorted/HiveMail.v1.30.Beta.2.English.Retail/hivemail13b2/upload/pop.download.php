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
// | $RCSfile: pop.download.php,v $ - $Revision: 1.9 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('SKIP_POP', true); // no need to overload
$templatesused = 'pop_download,pop_download_msgbit';
require_once('./global.php');
require_once('./includes/functions_pop.php');
require_once('./includes/functions_mime.php');
require_once('./includes/functions_smtp.php');

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'showmsgs';
}

// ############################################################################
// Get navigation bar
makemailnav(4);

// ############################################################################
// Get account information and establish connection
$pop = getinfo('pop', $popid);
$serverinfo[$popid] = $pop;
$pop_socket = new $POP_Socket_name($pop, true);
if (!$pop_socket->auth()) {
	eval(makeerror('error_poplogin'));
}
$pop_socket->get_list();
$mime_handle = new Mail_mimeDecode('');

// ############################################################################
// Show messages
if ($cmd == 'showmsgs') {
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

	// Get total number of mails
	$totalmails = count($pop_socket->msgNums);

	// Set default page number and per page values
	if (intme($perpage) < 1)	{
		$perpage = 15;
	}
	if (intme($pagenumber) < 1) {
		$pagenumber = 1;
	}

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

	// Get messages headers
	$msgHeaders = array();
	if ($sortorder != 'asc') {
		for ($i = $f = $totalmails - ($pagenumber-1)*$perpage; $i > $f - $perpage and $i > 0; $i--) {
			$pop_socket->msgNums[$i] = $pop_socket->get_size($i);
			$pop_socket->get_top($i, $msgHeaders[$i]);
		}
	} else {
		for ($i = $limitlower; $i < $limitlower + $perpage and $i <= $totalmails; $i++) {
			$pop_socket->msgNums[$i] = $pop_socket->get_size($i);
			$pop_socket->get_top($i, $msgHeaders[$i]);
		}
	}
	$pop_socket->close();

	// Show the messages
	$msgbits = '';
	while (list($i, ) = each ($msgHeaders)) {
		$msgHeaders[$i] = $mime_handle->_decode($msgHeaders[$i], '', 'text/plain', true);
		$mail = $msgHeaders[$i];
		if (trim($mail['subject']) == '') {
			$mail['subject'] = '(no subject)';
		}
		$mail['date'] = hivedate($mail['dateline'] = rfctotime($mail['date']));
		$mail['time'] = hivedate($mail['dateline'], getop('timeformat'));
		$mail['fromemail'] = extract_email($mail['from']);
		$fromemaillastspace = strrpos($mail['from'], ' ');
		$mail['fromname'] = trim(substr($mail['from'], 0, $fromemaillastspace - strlen($mail['from'])), " \r\n\t\0\x0b\"'");
		if (empty($mail['fromname'])) {
			$mail['fromname'] = $mail['fromemail'];
		}
		$mail['to'] = decodelist($mail['to'], false);
		switch ($mail['x-priority']) {
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
		$mail['kbsize'] = ceil($pop_socket->msgNums[$i] / 1024);
		if ($mail['fromname'] != $mail['fromemail']) {
			$mail['link'] = urlencode("$mail[fromname] <$mail[fromemail]>");
		} else {
			$mail['link'] = urlencode($mail['fromemail']);
		}

		eval(makeeval('msgbits', 'pop_download_msgbit', 1));
	}

	$pagenav = getpagenav($totalmails, "pop.download.php?popid=$popid&perpage=$perpage&sortorder=$sortorder");
	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; POP Accounts';
	eval(makeeval('echo', 'pop_download'));
}

// ############################################################################
// Process messages
if ($_POST['cmd'] == 'process') {
	if (!is_array($selections)) {
		invalid('messages');
	}
	$msgsprocessed = 0;
	foreach ($selections as $msgnum => $doit) {
		if ($doit != 'yes') {
			continue;
		}
		$pop_socket->get_email($msgnum, $msgsource);
		process_mail($msgsource, $popid, -1, $pop_socket->msgNums[$msgnum]);
		$pop_socket->delete_email($msgnum);
		$msgsprocessed++;
	}
	$pop_socket->close();

	eval(makeredirect('redirect_pop_msgsprocessed', "pop.download.php?popid=$popid"));
}

// ############################################################################
// Delete messages
if ($_POST['cmd'] == 'delete') {
	if (!is_array($selections)) {
		invalid('messages');
	}
	$msgsdeleted = 0;
	foreach ($selections as $msgnum => $doit) {
		if ($doit != 'yes') {
			continue;
		}
		$pop_socket->delete_email($msgnum);
		$msgsdeleted++;
	}
	$pop_socket->close();

	eval(makeredirect('redirect_pop_msgsdeleted', "pop.download.php?popid=$popid"));
}

?>