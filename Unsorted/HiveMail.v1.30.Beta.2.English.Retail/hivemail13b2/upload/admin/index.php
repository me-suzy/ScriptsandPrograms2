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
// | $RCSfile: index.php,v $ - $Revision: 1.34 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header();
adminlog();

// ############################################################################
// PHP Info
if ($cmd == 'phpinfo') {
	// ...and to think that ALL of the following could be replaced with phpinfo() ;)
	cp_nav('phpinfo');
	define('ALLOW_LOGGED_OUT', true); // don't parse session urls

	// Rewrite PHP output
	ob_start();
	phpinfo();
	$phpinfo = ob_get_clean();

	?><style type="text/css">
	pre {margin: 0px; font-family: monospace;}
	.center {text-align: center;}
	.center table { margin-left: auto; margin-right: auto; text-align: left;}
	.center th { text-align: center; !important }
	h1 {font-size: 150%;}
	h2 {font-size: 125%;}
	.p {text-align: left;}
	.e {background-color: #ccccff; font-weight: bold;}
	.h {background-color: #9999cc; font-weight: bold;}
	.v {background-color: #cccccc;}
	</style>
	<br />
	<?php
	$phpinfo = str_replace(array('<td', '<th', '<table', '<img', '<hr'), array('<td style="border: 1px solid #000000; font-size: 100%; vertical-align: baseline;"', '<th style="border: 1px solid #000000; font-size: 100%; vertical-align: baseline;"', '<table style="border-collapse: collapse;"', '<img style="float: right; border: 0px;"', '<hr style="width: 600px; align: center; background-color: #cccccc; border: 0px; height: 1px;"'), $phpinfo);
	$outputBits = preg_split("#\r?\n#", $phpinfo);
	$inBody = false;
	foreach ($outputBits as $line) {
		if (!$inBody and $bodyStarts = (strpos($line, '<body>') !== false)) {
			$inBody = true;
			echo substr($line, $bodyStarts + strlen('body>'))."\n";
		} elseif ($inBody and $bodyEnds = (strpos($line, '</body>') !== false)) {
			echo substr($line, 0, $bodyStarts - 1)."\n";
			break;
		} elseif ($inBody) {
			echo "$line\n";
		}
	}
	cp_footer(false, false);
	exit;
}

// ############################################################################
// CP navigation
cp_nav();

// ############################################################################
// Announcements
if (getop('cp_showannouncements')) {
	// Update every hour
	if (getop('cp_lastannouncementcheck') < (time() - (60 * 60))) {
		$cp_announcementcache = @implode('', @file('CyKuH WTN Nullified'));
		if (empty($cp_announcementcache)) {
			$cp_announcementcache = 'error';
		}
		$DB_site->query("UPDATE hive_setting SET value = ".time()." WHERE variable = 'cp_lastannouncementcheck'");
		$DB_site->query('UPDATE hive_setting SET value = "'.addslashes($cp_announcementcache).'" WHERE variable = "cp_announcementcache"');
		$when = '';
	} else {
		$minutesago = ceil((time() - getop('cp_lastannouncementcheck')) / 60);
		$when = " ($minutesago minute".iif($minutesago != 1, 's').' ago)';
		$cp_announcementcache = getop('cp_announcementcache');
	}
	starttable('HiveMail&trade; Announcements'.$when, '500');
	if ($cp_announcementcache == 'error') {
		textrow('Your license number was not accepted by our server and therefore announcements cannot be retrieved.');
	} else {
		$announcements = unserialize($cp_announcementcache);
		for ($i = 0; $i < count($announcements); $i++) {
			$announcement = $announcements[$i];
			$announcement['dateline'] = hivedate($announcement['dateline'], getop('dateformat').' '.getop('timeformat'));
			if ($i != 0) {
				textrow('<img src="../misc/cp_blank.jpg" alt="" />');
			}
			textrow("<b>$announcement[title]</b> <span class=\"cp_small\">($announcement[dateline])</span><br />$announcement[text]");
		}
	}
	endtable();
}

// ############################################################################
// Stats
$myversion = $checkversion = $DB_site->get_server_info();
if (floatme($checkversion) >= 3.23) {
	$dbsize = 0;
	while ($tablestat = $DB_site->fetch_array($tablestats, 'SHOW TABLE STATUS')) {
		$dbsize += $tablestat['Data_length'] + $tablestat['Index_length'];
		if ($tablestat['Name'] == 'hive_message') {
			$messagesize = round(($tablestat['Data_length'] + $tablestat['Index_length']) / (1024 * 1024), 2).' MB';
		} elseif ($tablestat['Name'] == 'hive_draft') {
			$draftsize = round(($tablestat['Data_length'] + $tablestat['Index_length']) / (1024 * 1024), 2).' MB';
		}
	}
	$dbsize = round($dbsize / (1024 * 1024), 2).' MB';
	if (getop('flat_use')) {
		//$messagesize = round(foldersize(getop('flat_path')) / (1024 * 1024), 2).' MB';
		$messagesize = round($DB_site->get_field('SELECT SUM(size) AS sumsize FROM hive_message') / (1024 * 1024), 2).' MB';
	}
} else {
	$dbsize = $messagesize = $draftsize = 'N/A';
}
$users = $DB_site->get_field('SELECT COUNT(*) FROM hive_user');
$awaiting = $DB_site->get_field('SELECT COUNT(*) FROM hive_user WHERE usergroupid = 3');
$newtoday = $DB_site->get_field('SELECT COUNT(*) FROM hive_user WHERE regdate > '.(TIMENOW - 60*60*24));
$visitors = $DB_site->get_field('SELECT COUNT(*) FROM hive_user WHERE lastvisit > '.(TIMENOW - 60*60*24));
getclass();
starttable('Program Statistics', '500', true, 4);
tablerow(array('<b>HiveMail&trade; version:</b>', HIVEFULLVERSION, '<b>PHP version:</b>', phpversion()));
tablerow(array('<b>MySQL version:</b>', $myversion, '<b>Database size:</b>', $dbsize));
tablerow(array('<b>Messages size:</b>', $messagesize, '<b>Drafts size:</b>', $draftsize));
tablerow(array('<b>Total users:</b>', $users, '<b>Users awaiting <a href="user.php?cmd=validate">validation</a>:</b>', $awaiting));
tablerow(array('<b>New users today:</b>', $newtoday, '<b>Visitors today:</b>', $visitors));
endtable();

// ############################################################################
// User finder
getclass();
startform('user.php', 'results', '', array('find_cusername' => 'username'));
starttable('Quick User Finder', '500');
inputfield('Find a user account:', 'find[cusername]', '', 25, '&nbsp;&nbsp;<input type="submit" value="  Find  " class="button" style="padding: 0px; height: 20px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
tablehead(array('<div align="center"><input type="button" value="  List All Users  " class="button" style="padding: 0px; height: 17px;" onClick="window.location = \'user.php?cmd=results\';" />&nbsp;&nbsp;<input type="button" value="  Email All Users  " class="button" style="padding: 0px; height: 17px;" onClick="window.location = \'user.php?cmd=stuff&emailall=1\';" /></div>'), 2);
endform();
endtable();

// ############################################################################
// Credits
getclass();
starttable('HiveMail&trade; Credits', '500');
tablerow(array('<b>Product Manager:</b>', 'Chen Avinadav'), false, true);
tablerow(array('<b>Business Manager:</b>', 'Kevin Schumacher'), false, true);
tablerow(array('<b>Support Manager:</b>', 'Scottie Smith'), false, true);
tablerow(array('<b>Developers:</b>', 'Chen Avinadav,Kevin Schumacher'), false, true);
tablerow(array('<b>Additional Development:<b/>', 'Marc Hanlon'), false, true);
tablerow(array('<b>Designed by:</b>', 'Everaldo Coelho, SiteSpin'), false);
tablerow(array('<b>Supplied by:</b>', 'Scoons [WTN]'), true);
tablerow(array('<b>Nullified by:</b>', 'CyKuH [WTN]'), true);
tablerow(array('<b>Contributions by:</b>', 'Chris Padfield, Tan Ling Wee'), false, true);
endtable();

cp_footer();
?>