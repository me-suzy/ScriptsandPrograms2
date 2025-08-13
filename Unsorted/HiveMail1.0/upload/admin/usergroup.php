<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: usergroup.php,v $
// | $Date: 2002/11/01 16:09:33 $
// | $Revision: 1.22 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; User Groups');

// ############################################################################
// Set the default do
if (!isset($do)) {
	$do = 'modify';
}

// ############################################################################
// Remove user group
if ($_POST['do'] == 'kill') {
	$usergroup = getinfo('usergroup', $usergroupid);
	if ($usergroupid <= 3) {
		cp_error('You cannot remove original user groups.');
	} elseif (($usergroup['perms'] & GROUP_CANADMIN) and !$DB_site->query_first("SELECT usergroupid FROM usergroup WHERE usergroupid <> $usergroupid AND (perms & ".GROUP_CANADMIN.")")) {
		cp_error('You cannot remove administrative privileges from the only group that has them.');
	}

	$DB_site->query("
		DELETE FROM usergroup
		WHERE usergroupid = $usergroupid
	");
	$DB_site->query("
		UPDATE user
		SET usergroupid = $moveto
		WHERE usergroupid = $usergroupid
	");

	cp_redirect('The user group has been removed.', 'usergroup.php');
}

// ############################################################################
// Remove user group
if ($do == 'remove') {
	$usergroup = getinfo('usergroup', $usergroupid);
	if ($usergroupid <= 3) {
		cp_error('You cannot remove original user groups.');
	} elseif (($usergroup['perms'] & GROUP_CANADMIN) and !$DB_site->query_first("SELECT usergroupid FROM usergroup WHERE usergroupid <> $usergroupid AND (perms & ".GROUP_CANADMIN.")")) {
		cp_error('You cannot remove administrative privileges from the only group that has them.');
	}

	startform('usergroup.php', 'kill', 'Are you sure you want to remove this user group?');
	starttable('Remove user group "'.$usergroup['title'].'" (ID: '.$usergroupid.')');
	textrow('Are you <b>sure</b> you want to remove this group? This procedure <b>cannot</b> be reveresed!');
	tableselect('Move users from this group to:', 'moveto', 'usergroup', -1, "usergroupid <> $usergroupid");
	hiddenfield('usergroupid', $usergroupid);
	endform('Remove User Group', '', 'Go Back');
	endtable();
}

// ############################################################################
// Create a new usergroup or update an existing one
if ($_POST['do'] == 'update') {
	if (empty($usergroup['title'])) {
		cp_error('The user group must have a title.');
	} elseif (!is_array($usergroup['allowedskins'])) {
		cp_error('You must select at least one skin for users to choose from.');
	} elseif (!$usergroup['GROUP_CANADMIN'] and !$DB_site->query_first("SELECT usergroupid FROM usergroup WHERE usergroupid <> $usergroupid AND (perms & ".GROUP_CANADMIN.")")) {
		cp_error('You cannot take away administrative privileges from the only group that has them.');
	} else {
		// Skins
		$usergroup['allowedskins'] = implode(',', $usergroup['allowedskins']);

		// Perms
		$usergroup['perms'] = 0;
		foreach ($_groupbits as $conname => $devnul) {
			if ($usergroup["$conname"]) {
				$usergroup['perms'] += constant($conname);
			}
			unset($usergroup["$conname"]);
		}
		
		if ($usergroupid == 0) {
			$DB_site->auto_query('usergroup', $usergroup);
			cp_redirect('The user group has been created.', 'usergroup.php');
		} else {
			$DB_site->auto_query('usergroup', $usergroup, "usergroupid = $usergroupid");
			cp_redirect('The user group has been updated.', 'usergroup.php');
		}
	}
}

// ############################################################################
// Create a new usergroup or update an existing one
if ($do == 'edit') {
	startform('usergroup.php', 'update', '', array('usergroup_title' => 'title'));

	$usergroup = getinfo('usergroup', $usergroupid, false, false);
	if ($usergroup === false) {
		$usergroup = array(
			'title' => '',
			'maxmb' => '5',
			'groupsig' => '',
			'perms' => '31'
		);
		$usergroupid = 0;
		starttable('Create new user group');
	} else {
		$usergroup['allowedskins'] = explode(',', $usergroup['allowedskins']);
		starttable('Update user group "'.$usergroup['title'].'" (ID: '.$usergroupid.')');
	}

	hiddenfield('usergroupid', $usergroupid);
	inputfield('Title:', 'usergroup[title]', $usergroup['title']);
	inputfield('Maximum storage (in megabytes):<br /><span class="cp_small">Set this to 0 for no limitation.</span>', 'usergroup[maxmb]', $usergroup['maxmb']);
	textarea('Email signature:<br /><span class="cp_small">This signature will be attached to all outgoing messages of users in this group.</span>', 'usergroup[groupsig]', $usergroup['groupsig'], 10, 80);

	foreach ($_groupbits as $conname => $text) {
		yesno($text, 'usergroup['.$conname.']', $usergroup['perms'] & constant($conname));
	}

	$checkboxes = '';
	$theclass = getclass();
	$skins = $DB_site->query('SELECT * FROM skin');
	while ($skin = $DB_site->fetch_array($skins)) {
		$checkboxes .= "<input type=\"checkbox\" name=\"usergroup[allowedskins][]\" class=\"radio_$theclass\" value=\"$skin[skinid]\"".iif(!is_array($usergroup['allowedskins']) or in_array($skin['skinid'], $usergroup['allowedskins']), ' checked="checked"')." /> $skin[title]<br />\n";
	}
	echo "	<tr class=\"$theclass\">\n";
	echo "		<td valign=\"top\">Avilable skins:<br /><span class=\"cp_small\">Users from this user group will only be able to<br />choose from the skins that are selected tothe right.</span></td>\n";
	echo "		<td valign=\"top\">$checkboxes</td>\n";
	echo "	</tr>\n";

	if ($usergroupid == 0) {
		endform('Create user group');
	} else {
		endform('Update user group', 'Reset Fields');
	}
	endtable();
}

// ############################################################################
// List the usergroups
if ($do == 'modify') {
	starttable('', '450');
	tablehead(array('ID', 'Title', 'Users', 'Options'));
	$groups = $DB_site->query('
		SELECT usergroup.*, COUNT(user.userid) AS users
		FROM usergroup
		LEFT JOIN user USING (usergroupid)
		GROUP BY usergroup.usergroupid
	');
	if ($DB_site->num_rows($groups) < 1) {
		textrow('No groups', 4, 1);
	} else {
		while ($group = $DB_site->fetch_array($groups)) {
			tablerow(array($group['usergroupid'], $group['title'], iif($group['users'] > 0, "<a href=\"user.php?do=results&find[eusergroupid]=$group[usergroupid]\">$group[users] user".iif($group['users'] != 1, 's').'</a>', "$group[users] users"), makelink('edit', "usergroup.php?do=edit&usergroupid=$group[usergroupid]") . iif($group['usergroupid'] > 3, '-' . makelink('remove', "usergroup.php?do=remove&usergroupid=$group[usergroupid]"))));
		}
	}
	emptyrow(4);
	endtable();

	startform('usergroup.php', 'edit');
	starttable('', '450');
	endform('Create new user group');
	endtable();
	
	echo '<br /><br />';
	starttable('', '450');
	textrow('A <b>user group</b> is a group of users that has special options and attributes. You can set different options and permissions for each group, independently, giving you complete control over your users.<br /><br />
	Every user can belong to one user group. The group users are originally put in depends on whether or not you have turned on the validation queue for users. If you have, new users will be placed in the "Awaiting Validation" group, which should have very restricted permissions or the validation process will be pointless. If the option is turned off, new registrants will be placed directly in the "Regular Users" group which should have normal permissions. The "Administrators" user group is meants for administrators only, and you should not lower its permissions.<br /><br />
	(The original user groups (IDs: 1 - 3) cannot be removed.)');
	endtable();
}

cp_footer();
?>