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
// | $Date: 2002/10/30 16:32:37 $
// | $Revision: 1.8 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');

// ############################################################################
// PHP Info
if ($do == 'phpinfo') {
	phpinfo();
	define('ALLOW_LOGGED_OUT', true); // don't parse session urls
	exit;
}

// ############################################################################
// CP Home
cp_header(' &raquo; Control Panel Home');
//echo "<table cellpadding=\"6\" cellspacing=\"0\" width=\"500\" align=\"center\" style=\"border: 0px;\">\n";
//echo '<tr><td>';
//echo 'From here you can control all aspects of the program.';
//echo '</td></tr></table><br /><br />';

// ############################################################################
// Stats
$myversion = mysql_get_server_info();
if (doubleval($myversion) >= 3.23) {
	$dbsize = 0;
	while ($tablestat = $DB_site->fetch_array($tablestats, 'SHOW TABLE STATUS')) {
		$dbsize += $tablestat['Data_length'] + $tablestat['Index_length'];
		if ($tablestat['Name'] == 'message') {
			$messagesize = round(($tablestat['Data_length'] + $tablestat['Index_length']) / (1024 * 1024), 2).' MB';
		} elseif ($tablestat['Name'] == 'draft') {
			$draftsize = round(($tablestat['Data_length'] + $tablestat['Index_length']) / (1024 * 1024), 2).' MB';
		}
	}
	$dbsize = round($dbsize / (1024 * 1024), 2).' MB';
} else {
	$dbsize = $messagesize = $draftsize = 'N/A';
}
$users = $DB_site->get_field('SELECT COUNT(*) FROM user');
$awaiting = $DB_site->get_field('SELECT COUNT(*) FROM user WHERE usergroupid = 3');
$newtoday = $DB_site->get_field('SELECT COUNT(*) FROM user WHERE regdate > '.(TIMENOW - 60*60*24));
$visitors = $DB_site->get_field('SELECT COUNT(*) FROM user WHERE lastvisit > '.(TIMENOW - 60*60*24));
starttable('Program Statistics', '500', true, 4);
tablerow(array('<b>HiveMail version:</b>', '1.0', '<b>PHP version:</b>', phpversion()));
tablerow(array('<b>MySQL version:</b>', mysql_get_server_info(), '<b>Database size:</b>', $dbsize));
tablerow(array('<b>Messages size:</b>', $messagesize, '<b>Drafts size:</b>', $draftsize));
tablerow(array('<b>Total users:</b>', $users, '<b>Users awaiting <a href="user.php?do=validate">validation</a>:</b>', $awaiting));
tablerow(array('<b>New users today:</b>', $newtoday, '<b>Visitors today:</b>', $visitors));
endtable();
echo '<br /><br />';

// ############################################################################
// User finder
startform('user.php', 'results', '', array('find_cusername' => 'username'));
starttable('Quick User Finder', '500');
inputfield('Find a user account:', 'find[cusername]', '', 25, '&nbsp;&nbsp;<input type="submit" value="  Find  " class="bginput" style="padding: 0px; height: 17px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
textrow('<div align="center"><input type="button" value="  List All Users  " class="bginput" style="padding: 0px; height: 17px;" onClick="window.location = \'user.php?do=results\';" /></div>');
endform();
endtable();
echo '<br /><br />';

// ############################################################################
// Credits
starttable('HiveMail Credits', '500');
tablerow(array('<b>Developed by:</b>', 'Chen Avinadav'), true);
tablerow(array('<b>Supplied by:</b>', 'CyKuH [WTN]'), true);
tablerow(array('<b>Nullified by:</b>', 'CyKuH [WTN]'), true);
tablerow(array('<b>Designed by:</b>', 'Everaldo Coelho'), true);
tablerow(array('<b>Contributions by:</b>', 'Kevin Schumacher<br />Chris Padfield<br />Richard Heyes'), true, true);
endtable();

cp_footer();
?>