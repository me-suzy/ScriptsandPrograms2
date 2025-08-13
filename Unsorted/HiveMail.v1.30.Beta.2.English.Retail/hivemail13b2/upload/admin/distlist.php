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
// | $RCSfile: distlist.php,v $ - $Revision: 1.2 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; Distribution Lists');
cp_nav('emaildist');

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');

// ############################################################################
// Remove list
if ($_POST['cmd'] == 'kill') {
	$distlist = getinfo('distlist', $distlistid);

	$DB_site->query("
		DELETE FROM hive_distlist
		WHERE distlistid = $distlistid
	");

	adminlog($distlistid, true);
	cp_redirect('The list has been removed.', 'distlist.php');
}

// ############################################################################
// Remove list
if ($cmd == 'remove') {
	$distlist = getinfo('distlist', $distlistid);

	adminlog($distlistid);
	startform('distlist.php', 'kill', 'Are you sure you want to remove this list?');
	starttable('Remove distribution list "'.$distlist['toalias'].getop('domainname').'" (ID: '.$distlistid.')');
	textrow('Are you <b>sure</b> you want to remove this list? This procedure <b>cannot</b> be reveresed!');
	hiddenfield('distlistid', $distlistid);
	endform('Remove list', '', 'Go Back');
	endtable();
}

// ############################################################################
// Create a new list or update an existing one
if ($_POST['cmd'] == 'update') {
	if (!preg_match('#^[a-z0-9][a-z0-9_.]+$#i', $distlist['toalias']) or user_exists($distlist['toalias'])) {
		adminlog($distlistid, false);
		cp_error('The email address you have entered is either invalid or taken already.');
	} else {
		$recips = preg_split('#\s#', $distlist['recipients']);
		$sqlrecips = array();
		foreach ($recips as $key => $recip) {
			$recips[$key] = $recip = trim($recip);
			if (!preg_match('#^[a-z0-9][a-z0-9_.]+$#i', $recip)) {
				unset($recips[$key]);
			} else {
				$sqlrecips[] = '"'.addslashes($recip).'"';
			}
		}
		$users = $DB_site->query('
			SELECT userid
			FROM hive_user
			WHERE username IN ('.implode(', ', $sqlrecips).')
		');
		if (empty($recips)) {
			cp_error('Please enter at least one target recipient.');
		} elseif (count($sqlrecips) > $DB_site->num_rows($users)) {
			cp_error('Some of the recipients were not found in the database.');
		} else {
			$distlist['recipients'] = serialize($recips);
			if ($distlistid == 0) {
				$DB_site->auto_query('distlist', $distlist);
				adminlog($distlistid, true);
				cp_redirect('The list has been created.', 'distlist.php');
			} else {
				$DB_site->auto_query('distlist', $distlist, "distlistid = $distlistid");
				adminlog($distlistid, true);
				cp_redirect('The list has been updated.', 'distlist.php');
			}
		}
	}
}

// ############################################################################
// Create a new list or update an existing one
if ($cmd == 'edit') {
	startform('distlist.php', 'update', '', array('distlist_toalias' => 'email address'), false, 'for (var i = 0; i < this.distlist_recip_select.options.length; i++) this.distlist_recipients.value += this.distlist_recip_select.options[i].value + \' \';');

	$distlist = getinfo('distlist', $distlistid, false, false, false);
	adminlog($distlistid);
	if ($distlist === false) {
		$distlist = array('recipients' => array());
		$distlistid = 0;
		starttable('Create new distribution list');
	} else {
		$distlist['recipients'] = unserialize($distlist['recipients']);
		starttable('Update distribution list "'.$distlist['toalias'].getop('domainname').'" (ID: '.$distlistid.')');
	}

	hiddenfield('distlistid', $distlistid);
	hiddenfield('distlist[recipients]', '');
	inputfield('Email address to expand:', 'distlist[toalias]', $distlist['toalias'], 25, getop('domainname'));
	$recipops = '';
	foreach ($distlist['recipients'] as $recipient) {
		$recipops .= "<option value=\"$recipient\">$recipient".getop('domainname')."</option>\n";
	}
	$recipsize = iif(count($distlist['recipients']) > 5, count($distlist['recipients']), 5);
	tablerow(array(
		'Target recipients:<br /><span class="cp_small">(These are the users that will receive<br />messages sent to the address above.)</span><br /><br /><input type="button" class="button" name="new" value="New Recipient" onClick="name = prompt(\'Enter the new recipient (username only):\', \'\'); if (name == null || name == \'\') return false; this.form.distlist_recip_select.options[this.form.distlist_recip_select.options.length] = new Option(name + \''.getop('domainname').'\', name);" style="width: 150px;" /> <input type="button" class="button" name="remove" value="Remove Recipent" disabled="disabled" onClick="if (confirm(\'Are you sure you want to remove this recipient?\')) { this.form.distlist_recip_select.options[this.form.distlist_recip_select.selectedIndex] = null; this.disabled = true; }" style="width: 150px;" />',
		'<select name="distlist[recip_select]" id="distlist_recip_select" style="width: 225px;" size="'.$recipsize.'" onChange="this.form.remove.disabled = (this.selectedIndex == -1);">'.$recipops.'</select>'
	), true, true);

	if ($distlistid == 0) {
		endform('Create distribution list');
	} else {
		endform('Update distribution list');
	}
	endtable();
}

// ############################################################################
// List the lists
if ($cmd == 'modify') {
	adminlog();

	startform('user.php', 'edit');
	hiddenfield('auserid', 0);
	starttable('', '90%');
	$cells = array(
		'ID',
		'Email Address <span class="cp_small">(click to edit)</span>',
		'Recipients <span class="cp_small">(press Go to view user)</span>',
		'Options',
	);
	tablehead($cells);
	$lists = $DB_site->query('
		SELECT *
		FROM hive_distlist
	');
	if ($DB_site->num_rows($lists) < 1) {
		textrow('No lists', count($cells), 1);
	} else {
		$recips = array();
		while ($list = $DB_site->fetch_array($lists)) {
			$thisrecips = unserialize($list['recipients']);
			foreach ($thisrecips as $key => $recip) {
				$thisrecips[$key] = addslashes($recip);
			}
			$recips = array_merge($recips, $thisrecips);
		}
		$getusers = $DB_site->query('
			SELECT userid, username, domain
			FROM hive_user
			WHERE username IN ("'.implode('", "', $recips).'")
		');
		$users = array();
		while ($user = $DB_site->fetch_array($getusers)) {
			$users["$user[username]"] = array('userid' => $user['userid'], 'email' => $user['username'].$user['domain']);
		}
		$DB_site->reset($lists);
		while ($list = $DB_site->fetch_array($lists)) {
			$thisrecips = unserialize($list['recipients']);
			$recipoptions = '';
			foreach ($thisrecips as $recip) {
				if (isset($users["$recip"])) {
					$recipoptions .= "<option value=\"{$users[$recip][userid]}\">{$users[$recip][email]}</option>\n";
				} else {
					$recipoptions .= "<option value=\"0\">$recip".getop('domainname')."</option>\n";
				}
			}
			$cells = array(
				$list['distlistid'],
				'<a href="distlist.php?cmd=edit&distlistid='.$list['distlistid'].'">'.$list['toalias'].getop('domainname').'</a>',
				'<select name="recips'.$list['distlistid'].'" onChange="this.form.gobut.disabled = (this.options[this.selectedIndex].value == 0);">'.$recipoptions.'</select> <input type="submit" name="gobut" value="Go" class="button" onClick="var selUserID = this.form.recips'.$list['distlistid'].'.options[this.form.recips'.$list['distlistid'].'.selectedIndex].value; if (selUserID == 0) { alert(\'No user was found with this email address.\'); return false; } else { this.form.auserid.value = selUserID; return true; }" />',
				makelink('remove', "distlist.php?cmd=remove&distlistid=$list[distlistid]"),
			);
			tablerow($cells);
		}
	}
	emptyrow(count($cells));
	endform();
	endtable();

	startform('distlist.php', 'edit');
	echo '<div aling="center"><input class="button" type="submit" value="  Create new list  " /></div><br />';
	endform();

	starttable('', '450');
	textrow('A <b>distribution list</b> allows you to define certain addresses that are not bound to any users in HiveMail&trade; and make them forward any messages to other users. For example, if you ');
	endtable();
}

cp_footer();
?>