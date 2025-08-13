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
// | $RCSfile: pop.php,v $ - $Revision: 1.8 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('LEAVE_SESSION_OPEN', true);
require_once('./global.php');
require_once('../includes/functions_pop.php');
require_once('../includes/functions_mime.php');
cp_header(' &raquo; Email Browser', true, true, '<script language="JavaScript" src="../misc/checkall.js"></script>');
cp_nav('emailbrowse');

// ############################################################################
// Set the default cmd
default_var($cmd, 'getinfo');
adminlog();

// ############################################################################
// Store account information as session
if (is_array($_REQUEST['pop'])) {
	$_SESSION['pop_browse'] = $_REQUEST['pop'];
}
if (!is_array($_SESSION['forms'])) {
	$_SESSION['forms'] = array();
}
$popinfo = $_SESSION['pop_browse'];
$perpage = $popinfo['perpage'];
$sortorder = $popinfo['sortorder'];

// ############################################################################
// Remove messages
if ($_POST['cmd'] == 'delete' and in_array($formtime, $_SESSION['forms'])) {
	$cmd = 'browse';
} elseif ($_POST['cmd'] == 'delete') {
	// Establish connection
	$pop_socket = new $POP_Socket_name($popinfo);
	if (!$pop_socket->auth()) {
		cp_error('The account information you have entered could not be used to log into the mail account. Please go back and try again.');
	}
	$pop_socket->get_list();

	$total = 0;
	if (is_array($deletemails)) {
		foreach ($deletemails as $msgnum => $doit) {
			if ($doit == '1') {
				$pop_socket->delete_email($msgnum);
				$total++;
			}
		}
	}
	$pop_socket->close();
	echo "<p>$total message(s) were successfully deleted.</p>";
	$cmd = 'browse';
	$_SESSION['forms'][] = $formtime;
}

// ############################################################################
// Show message list
if ($cmd == 'browse') {
	// Establish connection
	$pop_socket = new $POP_Socket_name($popinfo);
	if (!$pop_socket->auth()) {
		cp_error('The account information you have entered could not be used to log into the mail account. Please go back and try again.');
	}
	$pop_socket->get_list();
	$mime_handle = new Mail_mimeDecode('');

	$sortorder = strtolower($sortorder);
	if ($sortorder != 'asc') {
		$sortorder = 'desc';
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
	startform('pop.php', 'delete', 'Are you sure you want to remove the selected messages?');
	hiddenfield('formtime', TIMENOW);
	starttable();
	tablehead(array('ID', 'From', 'Subject', 'Date', 'Size', '<input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form);" />'));
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
		$mail['kbsize'] = ceil($pop_socket->msgNums[$i] / 1024);
		if ($mail['fromname'] != $mail['fromemail']) {
			$mail['link'] = urlencode("$mail[fromname] <$mail[fromemail]>");
		} else {
			$mail['link'] = urlencode($mail['fromemail']);
		}

		$cells = array(
			$i,
			'<a href="../compose.email.php?email='.$mail['link'].'">'.$mail['fromname'].'</a>',
			'wrap' => $mail['subject'],
			"$mail[date]&nbsp;$mail[time]",
			"$mail[kbsize]KB",
			'<input type="checkbox" value="1" name="deletemails['.$i.']" onClick="checkMain(this.form);" />',
		);
		tablerow($cells, true, false, false, true);
		flush();
	}
	$pagenav = getpagenav($totalmails, "pop.php?do=browse&perpage=$perpage&sortorder=$sortorder", false);
	if ($totalmails == 0) {
		$cells = array('', '', '', '', '', '');
		textrow('No messages in mailbox', count($cells), 1);
		emptyrow(count($cells));
	} else {
		endform('Delete Selected Messages', '', '', '', count($cells));
	}
	endtable();
	echo $pagenav;
}

// ############################################################################
// Get account information
if ($cmd == 'getinfo') {
	if (!is_array($popinfo)) {
		$popinfo = array(
			'server' => getop('pop3_server'),
			'port' => getop('pop3_port'),
			'username' => getop('pop3_username'),
			'password' => getop('pop3_password'),
			'perpage' => 15,
			'sortorder' => 'asc',
		);
	}

	startform('pop.php', 'browse');
	starttable('Enter account information', '450');
	inputfield('Server name:', 'pop[server]', $popinfo['server']);
	inputfield('Server port:', 'pop[port]', $popinfo['port']);
	inputfield('Username:', 'pop[username]', $popinfo['username']);
	inputfield('Password:', 'pop[password]', $popinfo['password']);
	inputfield('Messages per page:', 'pop[perpage]', $popinfo['perpage']);
	selectbox('Sort messages in:', 'pop[sortorder]', array('asc' => 'Ascending order', 'desc' => 'Descending order'), $popinfo['sortorder']);
	endform('Browse messages');
	endtable();
}

cp_footer();
?>