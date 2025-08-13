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
// | $RCSfile: ban.php,v $ - $Revision: 1.4 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; Suspended Users', $cmd != 'edit' and $_POST['cmd'] != 'update', $cmd != 'edit' and $_POST['cmd'] != 'update');
cp_nav('userban');

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');

// ############################################################################
// Remove ban
if ($cmd == 'remove') {
	$ban = getinfo('ban', $banid);
	$auser = getuserinfo($ban['userid']);

	$DB_site->query("
		DELETE FROM hive_ban
		WHERE banid = $banid
	");
	if ($auser['isbanned']) {
		$DB_site->query("
			UPDATE hive_user
			SET options = options - ".USER_ISBANNED."
			WHERE userid = $auser[userid]
		");
	}

	adminlog($banid, true);
	cp_redirect('The user has been unbanned.', 'ban.php');
}

// ############################################################################
// Update bans
if ($_POST['cmd'] == 'update' or $_POST['cmd'] == 'insert') {
	if ($ban_duration_nolimit) {
		$ban['duration'] = 0;
	}
	$ban['dateline'] = strtotime($ban['dateline']);
	if ($ban['dateline'] == -1) {
		adminlog($banid, false);
		cp_error('The suspension date you have specified is invalid.', true, true, true, false);
	} elseif ($ban['duration'] < 0) {
		adminlog($banid, false);
		cp_error('The duration you have specified for the suspension is invalid.', true, true, true, false);
	} else {
		if ($_POST['cmd'] == 'insert') {
			$auser = getuserinfo($ban['userid']);
			if ($auser['canadmin']) {
			//	adminlog(0, false);
			//	cp_error('You cannot suspend an administrator.');
			}
			$DB_site->auto_query('ban', $ban);
			if (!$auser['isbanned']) {
				$DB_site->query("
					UPDATE hive_user
					SET options = options + ".USER_ISBANNED."
					WHERE userid = $auser[userid]
				");
			}
			adminlog($DB_site->insert_id(), true);
			cp_redirect('The user has been banned.', 'ban.php?cmd=modify');
		} else {
			unset($ban['adminid']);
			$DB_site->auto_query('ban', $ban, "banid = $banid");
			adminlog($banid, true);
			cp_redirect('The ban has been updated.', 'ban.php?cmd=edit', 1, false);
		}
	}
}

// ############################################################################
// Show ban update form
if ($cmd == 'edit') {
	$ban = $DB_site->query_first('
		SELECT ban.*, user.*, admin.username AS adminusername, admin.domain AS admindomain, admin.realname AS adminrealname
		FROM hive_ban AS ban
		LEFT JOIN hive_user AS user ON (ban.userid = user.userid)
		LEFT JOIN hive_user AS admin ON (ban.adminid = admin.userid)
		WHERE banid = '.intme($banid).'
	');
	if ($ban) {
		adminlog($banid);
		startform('ban.php', 'update');
		hiddenfield('banid', $ban['banid']);
		starttable('Suspended User: '.$ban['username'].' (ID: '.$ban['userid'].')', '100%');
		tablerow(array('User name and email:', "<a href=\"user.php?cmd=edit&auserid=$ban[userid]\" target=\"_top\">$ban[realname]</a> (<a href=\"../compose.email.php?email=$ban[username]$ban[domain]\" target=\"_blank\">$ban[username]$ban[domain]</a>)"), true, true);
		datefield('Date of suspension:', 'ban[dateline]', hivedate($ban['dateline'], 'Y-m-d'), '15');
		limitfield('Duration:<br /><span class="cp_small">(in days)</span>', 'ban[duration]', $ban['duration']);
		tablerow(array('Suspended by:', "<a href=\"user.php?cmd=edit&auserid=$ban[adminid]\" target=\"_top\">$ban[adminrealname]</a> (<a href=\"../compose.email.php?email=$ban[adminusername]$ban[admindomain]\" target=\"_blank\">$ban[adminusername]$ban[admindomain]</a>)"), true, true);
		textarea('Reason:<br /><span class="cp_small">The user will be able to<br />view this information.</span>', 'ban[reason]', $ban['reason'], '5', '36');
		tablehead(array('<input class="button" type="submit" value="  Save Changes  " />&nbsp;&nbsp;<input class="button" type="submit" value="  Remove Ban  " onClick="if (confirm(\'Are you sure you want to unban this user?\')) { this.form.target = \'_top\'; this.form.cmd.value = \'remove\'; } else { return false; }" />'), 2);
		endtable();
	}
}

// ############################################################################
// Add a new ban
if ($cmd == 'add') {
	$auser = getuserinfo($auserid);
	adminlog($auserid);

	startform('ban.php', 'insert');
	hiddenfield('banid', 0);
	hiddenfield('ban[userid]', $auserid);
	hiddenfield('ban[adminid]', $hiveuser['userid']);
	hiddenfield('ban[dateline]', hivedate(TIMENOW, 'Y-m-d'));
	starttable('Suspend User: '.$auser['username'].' (ID: '.$auserid.')', '550');
	tablerow(array('User name and email:', "<a href=\"user.php?cmd=edit&auserid=$auser[userid]\">$auser[realname]</a> (<a href=\"../compose.email.php?email=$auser[username]$auser[domain]\" target=\"_blank\">$auser[username]$auser[domain]</a>)"), true, true);
	tablerow(array('Date of suspension:', hivedate(TIMENOW, 'Y-m-d')), true, true);
	limitfield('Duration:<br /><span class="cp_small">(in days)</span>', 'ban[duration]', 0);
	textarea('Reason:<br /><span class="cp_small">The user will be able to<br />view this information.</span>', 'ban[reason]', '', '5', '36');
	endform('Suspend User');
	endtable();
}

// ############################################################################
// Display bans
if ($cmd == 'modify') {
	adminlog();

	$bans = $DB_site->query('
		SELECT ban.*, user.username, user.domain
		FROM hive_ban AS ban
		LEFT JOIN hive_user AS user ON (ban.userid = user.userid)
		ORDER BY user.username
	');
	if ($DB_site->num_rows($bans) < 1) {
		echo '<br />';
		starttable('', '400');
		textrow('There are no suspended users at the moment. To ban a user, go to his profile and press the Suspend User button.');
		endtable();
	} else {
		if ($DB_site->num_rows($bans) < 4) {
			$numcols = $DB_site->num_rows($bans);
		} else {
			$numcols = 4;
		}
		$rows = ceil($DB_site->num_rows($bans) / $numcols);
		$rowcells = array();
		for ($i = 0; $ban = $DB_site->fetch_array($bans); $i++) {
			$rowcells[$i % $rows][] = "<a href=\"ban.php?cmd=edit&banid=$ban[banid]\" target=\"editwindow\">$ban[username]$ban[domain]</a>";
		}
		starttable('Suspended Users', '550', true, $numcols);
		foreach ($rowcells as $rownum => $cells) {
			while (count($cells) < $numcols) {
				$cells[] = '&nbsp;';
			}
			tablerow($cells, true, false, false, true, floor(100 / $numcols).'%');
		}
		endtable();

		echo '<br /><iframe name="editwindow" src="ban.php?cmd=edit&banid='.intval($banid).'" style="align: center; width: 550px; height: 350px;" frameborder="no" scrolling="no"></iframe>';
	}
}

cp_footer($cmd != 'edit' and $_POST['cmd'] != 'update', $cmd != 'edit' and $_POST['cmd'] != 'update');
?>