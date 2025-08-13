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
// | $RCSfile: eventlog.php,v $ - $Revision: 1.24 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
require_once('../includes/events.php');
cp_header(' &raquo; Event Log', ($cmd != 'getinfo'), ($cmd != 'getinfo'));
cp_nav('logevent');

// ############################################################################
// Set the default cmd
default_var($cmd, 'intro');

// ############################################################################
function geteventmsg($eventid, $thearray, $getlong = false) {
	global $events;
	$index = iif(!$getlong, 0, 1);
	if (is_array($thearray)) {
		extract($thearray);
	}
	eval('$message = "'.$events[$eventid][$index].'";');
	return $message;
}

// ############################################################################
// List the admin log
if ($cmd == 'list') {
	//adminlog();

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
					if (substr($field, 0, 4) == 'date') {
						$sqlwhere .= " AND eventlog.$field < UNIX_TIMESTAMP('".addslashes($value)."')";
					} else {
						$sqlwhere .= " AND eventlog.$field < '".intval($value)."'";
					}
					break;
				case 'm':
					if (substr($field, 0, 4) == 'date') {
						$sqlwhere .= " AND eventlog.$field > UNIX_TIMESTAMP('".addslashes($value)."')";
					} else {
						$sqlwhere .= " AND eventlog.$field > '".intval($value)."'";
					}
					break;
				case 'b':
					list($minvalue,$maxvalue) = split('-',$value);
					$sqlwhere .= " AND (eventlog.$field > $minvalue AND eventlog.$field < $maxvalue)";
					break;
				case 'e':
					$sqlwhere .= " AND eventlog.$field = '".addslashes($value)."'";
					break;
				case 'i':
					$sqlwhere .= " AND eventlog.$field IN ($value)";
					break;
			}
		}
	}?>
	<script language="JavaScript">
	<!--
	function moreinfo(logid) {
		window.open('eventlog.php?cmd=getinfo&eventlogid='+logid, 'moreInfo'+logid, "width=520,height=290,resizable=yes,scrollbars=yes");
	}
	// -->
	</script>
	<?PHP
	$pagenav = paginate('eventlog', "eventlog.php?cmd=list$link", "WHERE $sqlwhere");
	$logs = $DB_site->query("
		SELECT *
		FROM hive_eventlog AS eventlog
		WHERE $sqlwhere
		ORDER BY $sortby $sortorder
		LIMIT ".($limitlower-1).", $perpage
	");

	starttable('');
	$cells = array(
		'&nbsp',
		'Log ID',
		'Date/Time',
		'Event ID',
		'Module',
		'Message',
		'More Info'
	);
	tablehead($cells);
	if ($DB_site->num_rows($logs) < 1) {
		textrow('No log entries, try some different terms.', count($cells), 1);
	} else {
		while ($log = $DB_site->fetch_array($logs)) {
			$module = floor($log['event']/100);
			$cells = array(
				'<img src="../misc/logging/level'.$log[level].'.gif" valign="absmiddle" alt="'.$_events['levels'][$log['level']].'">',
				$log['eventlogid'],
				hivedate($log['dateline'], getop('dateformat').' '.getop('timeformat')),
				$log['event'],
				$_events['modules'][$module],
				geteventmsg($log['event'], unserialize($log['debuginfo'])),
				"[<a href=\"#\" onClick=\"moreinfo($log[eventlogid]); return false;\">more info</a>]"
			);
			tablerow($cells, true, false, true);
		}
	}
	tablehead(array("$pagenav&nbsp;"), count($cells));
	endtable();
}

// ############################################################################
// Display some options
if ($cmd == 'intro') {
	$sortBy = array(
		'dateline' => 'Date and time',
		'level' => 'Level',
		'module' => 'Module'
	);
	foreach ($_events['modules'] as $key=>$value) {
		$mod = $key*100;
		$newkey = $mod.'-'.($mod+100);
		$modules[$newkey] = $value;
	}
	startform('eventlog.php', 'list');
	starttable('Event Log Browser', '550');
	textrow('Please choose below which events you\'d like to display.');
	selectbox('Logs for module:', 'filter[bevent]', $modules, -1, 'any module');
	selectbox('Event level:', 'filter[ilevel]', $_events['levels'], -1, 'any level');
	datefield('Generated after:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[mdateline]', $find['mdatelastvisit']);
	datefield('Generated before:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[ldateline]', $find['ldatelastvisit']);
	inputfield('Entries to display per page:', 'perpage', '50');
	selectbox('Sort entries by:', 'sortby', $sortBy, 'dateline', '', '&nbsp;in&nbsp;<select name="sortorder" id="sortorder">
			<option value="desc" selected="selected">descending order</option>
			<option value="asc">ascending order</option>
		</select>');
	endform('Display Log');
	endtable();

	echo '<br />';
	foreach ($_events['modules'] as $key=>$value) {
		$mod = $key*100;
		$newkey = $mod.'-'.($mod+100);
		$modules[$newkey] = $value;
	}
	startform('eventlog.php', 'doprune');
	starttable('Prune Event Log', '550');
	textrow('Please choose below which events you\'d like to prune.');
	selectbox('Logs for module:', 'filter[bevent]', $modules, -1, 'any module');
	selectbox('Event level:', 'filter[ilevel]', $_events['levels'], -1, 'any level');
	datefield('Generated after:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[mdateline]', $find['mdatelastvisit']);
	datefield('Generated before:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'filter[ldateline]', $find['ldatelastvisit']);
	endform('Prune Log');
	endtable();
}

// ############################################################################
// Prune events
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
				case 'b':
					list($minvalue,$maxvalue) = split('-',$value);
					$sqlwhere .= " AND ($field > $minvalue AND $field < $maxvalue)";
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

	$logs = $DB_site->query("DELETE FROM hive_eventlog".iif($sqlwhere != '1 = 1', " WHERE $sqlwhere"));
	cp_redirect('Selected events have been pruned from the error log', 'eventlog.php');
}

// ############################################################################
// Display information about an event
if ($cmd == 'getinfo') {
	$evententry = getinfo('eventlog', $eventlogid);
	adminlog($eventlogid);

	$module = floor($evententry['event']/100);
	echo "<div align=\"center\">\n";
	starttable('Event Information');
	tablerow(array('Date and time:', hivedate($evententry['dateline'], getop('dateformat').' '.getop('timeformat'))), true);
	tablerow(array('Event ID:', $evententry['event']), true);
	tablerow(array('Module:', $_events['modules'][$module]), true);
	tablerow(array('Event:', geteventmsg($evententry['event'], unserialize($log['debuginfo']))), true);
	endtable();
	echo '</div>';
	echo "<br><div align=\"center\">\n";
	starttable('Event Variables');
	$eventvars = unserialize($evententry['debuginfo']);
	foreach ($eventvars as $key => $value) {
		$nicekey = iif(isset($translated_events["$key"]), $translated_events["$key"], $key);
		tablerow(array($nicekey.':', $value), true, true);
	}
	endtable();
	echo '</div>';
}

cp_footer(($cmd != 'getinfo'));

?>