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
// | $RCSfile: report.php,v $ - $Revision: 1.6 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
require_once('../includes/functions_mime.php');
cp_header(' &raquo; Spam Reports', ($cmd != 'getinfo' and $cmd != 'close' and $cmd != 'ban'), ($cmd != 'getinfo' and $cmd != 'close' and $cmd != 'ban'));
cp_nav('emailspam');

// ############################################################################
// Set the default cmd
default_var($cmd, 'intro');

// ############################################################################
// Close report
if ($cmd == 'close') {
	$DB_site->query('
		UPDATE hive_report
		SET closedate = '.iif($close, TIMENOW, 0).'
		WHERE reportid = '.intme($reportid).'
	');

	adminlog($reportid, true);
	$cmd = 'getinfo';
}

// ############################################################################
// Close report
if ($cmd == 'ban') {
	$DB_site->query('
		UPDATE hive_report
		SET closedate = '.TIMENOW.'
		WHERE reportid = '.intme($reportid).'
	');
	$DB_site->query('
		UPDATE hive_setting
		SET value = CONCAT("'.addslashes($banemail)."\n".'", value)
		WHERE variable = "globalblock"
	');

	adminlog($reportid, true);
	$cmd = 'getinfo';
}

// ############################################################################
// Get information
if ($cmd == 'getinfo') {
	$report = getinfo('report', $reportid);
	$report['domain'] = strstr($report['email'], '@');
	$auser = getinfo('user', $report['userid']);
	adminlog($reportid);
	decodemime($report['source'], false);

	echo "<div align=\"center\">\n";
	starttable('Report Details');
	tablerow(array('Reported by:', "$auser[realname] (<a href=\"user.php?cmd=edit&userid=$auser[userid]\">$auser[username]$auser[domain]</a>)"), true);
	tablerow(array('Sender of the message:', iif($report['name'] != $report['email'], "$report[name] (")."<a href=\"../compose.email.php?email=$report[email]\">$report[email]</a>".iif($report['name'] != $report['email'], ')')), true);
	tablerow(array('Message subject:', htmlchars($report['subject'])), true, false, false, false);
	tablerow(array('Report opened on:', hivedate($report['opendate'], getop('dateformat'))), true);
	if ($report['closedate'] != 0) {
		tablerow(array('Report closed on:', hivedate($report['closedate'], getop('dateformat')).' '.makelink('open', "report.php?cmd=close&close=0&reportid=$reportid")), true);
	} else {
		tablerow(array('Possible actions:', makelink('ban email', "report.php?cmd=ban&banemail=$report[email]&reportid=$reportid").' '.makelink('ban domain name', "report.php?cmd=ban&banemail=$report[domain]&reportid=$reportid").' '.makelink('close report', "report.php?cmd=close&close=1&reportid=$reportid")), true);
	}
	tablehead(array('Message Headers'), 2);
	foreach ($headers as $key => $value) {
		$key = ucwords($key).':';
		if (!is_array($value)) {
			tablerow(array($key, htmlchars($value)), true, false, false, false);
		} else {
			foreach ($value as $subvalue) {
				tablerow(array($key, htmlchars($subvalue)), true, false, false, false);
				$key = '';
			}
		}
	}
	tablehead(array('Message Body'), 2);
	textrow('<div style="width: 600px; height: 500px; overflow: auto;"><pre>'.htmlchars($obj->_body).'</pre></div>');
	endtable();
	echo '</div>';
}

// ############################################################################
// List the admin log
if ($cmd == 'list') {
	adminlog();

	// Sort options
	$sortorder = strtolower($sortorder);
	if ($sortorder != 'asc') {
		$sortorder = 'desc';
	}
	switch ($sortby) {
		case 'opendate':
		case 'username':
		case 'email':
		case 'name':
		case 'subject':
			break;
		default:
			$sortby = 'opendate';
	}

	$sqlwhere = '1 = 1';
	$link = "&sortby=$sortby&sortorder=$sortorder";
	if (is_array($filter)) {
		foreach ($filter as $subject => $value) {
			$value = trim($value);
			if (empty($value) or $value == -1) {
				continue;
			}

			$field = substr($subject, 1);
			$link .= "&filter[$subject]=".urlencode($value);

			switch (substr($subject, 0, 1)) {
				case 'l':
					if (substr($field, -4) == 'date') {
						$sqlwhere .= " AND report.$field < UNIX_TIMESTAMP('".addslashes($value)."')";
					} else {
						$sqlwhere .= " AND report.$field < '".intval($value)."'";
					}
					break;
				case 'm':
					if (substr($field, -4) == 'date') {
						$sqlwhere .= " AND report.$field > UNIX_TIMESTAMP('".addslashes($value)."')";
					} else {
						$sqlwhere .= " AND report.$field > '".intval($value)."'";
					}
					break;
				case 'e':
					$sqlwhere .= " AND report.$field = '".addslashes($value)."'";
					break;
				case 'i':
					$sqlwhere .= " AND report.$field IN ($value)";
					break;
				case 'c':
					$sqlwhere .= " AND report.$field LIKE '%".addslashes($value)."%'";
					break;
			}
		}
	}

	?><script language="JavaScript">
	<!--
	function moreinfo(reportid) {
		window.open('report.php?cmd=getinfo&reportid='+reportid, 'moreInfo'+reportid, "width=650,height=450,resizable=yes,scrollbars=yes");
	}
	// -->
	</script><?php

	$pagenav = paginate('report', "report.php?cmd=list$link", "WHERE $sqlwhere");
	$reports = $DB_site->query("
		SELECT report.*, user.realname, user.username, user.domain, IF(closedate = 0, 0, 1) AS closed
		FROM hive_report AS report
		LEFT JOIN hive_user AS user USING (userid)
		WHERE $sqlwhere
		ORDER BY closed ASC, $sortby $sortorder
		LIMIT ".($limitlower-1).", $perpage
	");

	$headcells = array(
		'ID',
		'Reported By',
		'openclose' => '',
		'Sender',
		'More Info',
	);
	$doneopen = $doneclosed = false;
	if ($DB_site->num_rows($reports) < 1) {
		starttable('Spam Reports');
		textrow('No reports found.', count($cells), 1);
	} else {
		while ($report = $DB_site->fetch_array($reports)) {
			if (!$doneopen and $report['closedate'] == 0) {
				starttable('Open Spam Reports');
				$headcells['openclose'] = 'Opened';
				tablehead($headcells);
				$doneopen = true;
			} elseif (!$doneclosed and $report['closedate'] != 0) {
				if ($doneopen) {
					endtable();
				}
				starttable('Closed Spam Reports');
				$headcells['openclose'] = 'Closed';
				tablehead($headcells);
				$doneclosed = true;
			}
			decode_subject($report['subject']);
			$cells = array(
				'center1' => $report['reportid'],
				"$report[realname]<br /><a href=\"user.php?cmd=edit&userid=$report[userid]\">$report[username]$report[domain]</a>",
				'center2' => iif($report['closedate'] == 0, hivedate($report['opendate'], getop('dateformat')), hivedate($report['closedate'], getop('dateformat'))),
				iif($report['name'] != $report['email'], "$report[name]<br />")."<a href=\"../compose.email.php?email=$report[email]\">$report[email]</a>",
				"[<a href=\"#\" onClick=\"moreinfo($report[reportid]); return false;\">more info</a>]"
			);
			$class = getclass();
			tablerow($cells, $class);
			echo "	<tr>\n";
			echo "		<td colspan=\"".count($cells)."\" class=\"$class\"><i>Subject</i>: ".htmlchars(trimtext($report['subject'], 25))."</td>\n";
			echo "	</tr>\n";
		}
	}
	tablehead(array("$pagenav&nbsp;"), count($cells));
	endtable();

	$sortoptions = array(
		'opendate' => 'Date and time of the report',
		'username' => 'User that reported the email',
		'email' => 'Email address of the sender',
		'name' => 'Name of the sender',
		'subject' => 'Subject of the email',
	);
	startform('report.php', 'list');
	starttable('Find Spam Reports');
	textrow('Please choose below which reports you\'d like to display.');
	inputfield('Subject of messages contains:', 'filter[csubject]', $filter['csubject']);
	inputfield('Email of the sender contains:', 'filter[cemail]', $filter['cemail']);
	inputfield('Name of the sender contains:', 'filter[cname]', $filter['cname']);
	datefield('Report opened after:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[mopendate]', $filter['mopendate']);
	datefield('Report opened before:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[lopendate]', $filter['lopendate']);
	datefield('Report closed after:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[mclosedate]', $filter['mclosedate']);
	datefield('Report closed before:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[lclosedate]', $filter['lclosedate']);
	inputfield('Reports to display per page:', 'perpage', $perpage);
	selectbox('Sort reports by:', 'sortby', $sortoptions, $sortby, '', '&nbsp;in&nbsp;<select name="sortorder" id="sortorder">
			<option value="desc"'.iif($sortorder == 'desc', 'selected="selected"').'>descending order</option>
			<option value="asc"'.iif($sortorder == 'asc', 'selected="selected"').'>ascending order</option>
		</select>');
	endform('Display Reports');
	endtable();
}

// ############################################################################
// Prune
if ($_POST['cmd'] == 'doprune') {
	$sqlwhere = '1 = 1';
	if (is_array($filter)) {
		foreach ($filter as $subject => $value) {
			$value = trim($value);
			if (empty($value) or $value == -1) {
				continue;
			}

			$field = substr($subject, 1);
			
			switch (substr($subject, 0, 1)) {
				case 'l':
					if (substr($field, 0, 4) == 'date') {
						$sqlwhere .= " AND $field < UNIX_TIMESTAMP('".addslashes($value)."')";
					} else {
						$sqlwhere .= " AND $field < '".intval($value)."'";
					}
					break;
				case 'm':
					if (substr($field, 0, 4) == 'date') {
						$sqlwhere .= " AND $field > UNIX_TIMESTAMP('".addslashes($value)."')";
					} else {
						$sqlwhere .= " AND $field > '".intval($value)."'";
					}
					break;
				case 'e':
					$sqlwhere .= " AND $field = '".addslashes($value)."'";
					break;
				case 'i':
					$sqlwhere .= " AND $field IN ($value)";
					break;
			}
		}
	}

	$logs = $DB_site->query("DELETE FROM hive_report".iif($sqlwhere != '1 = 1', " WHERE $sqlwhere"));
	cp_redirect('Selected entries have been pruned from the adming log.', 'report.php');
}

// ############################################################################
// Prune
if ($cmd == 'prune') {
	adminlog();

	startform('report.php', 'doprune');
	starttable('Prune Admin Log', '550');
	textrow('Please choose below which entries you\'d like to prune.');
	selectbox('Logs for script:', 'filter[efilename]', $files, -1, 'any file');
	selectbox('Generated by user:', 'filter[euserid]', $users, -1, 'any user');
	selectbox('Status of actions:', 'filter[isuccess]', $successOptions, -2, 'any status');
	datefield('Generated after:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[mdateline]', $find['mdatelastvisit']);
	datefield('Generated before:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[ldateline]', $find['ldatelastvisit']);
	endform('Prune Log');
	endtable();
}

cp_footer(($cmd != 'getinfo' and $cmd != 'close' and $cmd != 'ban'));
?>