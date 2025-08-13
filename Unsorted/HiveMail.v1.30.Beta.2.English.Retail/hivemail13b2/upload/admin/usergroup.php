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
// | $RCSfile: usergroup.php,v $ - $Revision: 1.28 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; User Groups');
cp_nav('usergroup');

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');
if ($cmd == 'add') {
	$cmd = 'edit';
}

// ############################################################################
// Remove user group
if ($_POST['cmd'] == 'kill') {
	$usergroup = getinfo('usergroup', $usergroupid);
	if ($usergroupid <= 3) {
		adminlog($usergroupid, false);
		cp_error('You cannot remove original user groups.');
	} elseif (($usergroup['perms'] & GROUP_CANADMIN) and !$DB_site->query_first("SELECT usergroupid FROM hive_usergroup WHERE usergroupid <> $usergroupid AND (perms & ".GROUP_CANADMIN.")")) {
		adminlog($usergroupid, false);
		cp_error('You cannot remove administrative privileges from the only group that has them.');
	}

	$DB_site->query("
		DELETE FROM hive_usergroup
		WHERE usergroupid = $usergroupid
	");
	$DB_site->query("
		UPDATE hive_user
		SET usergroupid = $moveto
		WHERE usergroupid = $usergroupid
	");

	adminlog($usergroupid, true, 'kill', "Users will now be in user group: $moveto");
	cp_redirect('The user group has been removed.', 'usergroup.php');
}

// ############################################################################
// Remove user group
if ($cmd == 'remove') {
	$usergroup = getinfo('usergroup', $usergroupid);
	if ($usergroupid <= 3) {
		adminlog($usergroupid, false);
		cp_error('You cannot remove original user groups.');
	} elseif (($usergroup['perms'] & GROUP_CANADMIN) and !$DB_site->query_first("SELECT usergroupid FROM hive_usergroup WHERE usergroupid <> $usergroupid AND (perms & ".GROUP_CANADMIN.")")) {
		adminlog($usergroupid, false);
		cp_error('You cannot remove administrative privileges from the only group that has them.');
	}

	adminlog($usergroupid);
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
if ($_POST['cmd'] == 'update') {
	if (empty($usergroup['title'])) {
		adminlog($usergroupid, false);
		cp_error('The user group must have a title.');
	} elseif (!is_array($usergroup['allowedskins'])) {
		adminlog($usergroupid, false);
		cp_error('You must select at least one skin for users to choose from.');
	} elseif (!$usergroup['GROUP_CANADMIN'] and !$DB_site->query_first("SELECT usergroupid FROM hive_usergroup WHERE usergroupid <> $usergroupid AND (perms & ".GROUP_CANADMIN.")")) {
		adminlog($usergroupid, false);
		cp_error('You cannot take away administrative privileges from the only group that has them.');
	} elseif ($usergroup['removetime'] > 0 and $usergroup['emptytime'] > 0 and $usergroup['removetime'] < $usergroup['emptytime']) {
		adminlog($usergroupid, false);
		cp_error('You cannot set accounts to be deleted before the messages are deleted. Please either disable one option, or set them both in a logical order.');
	} else {
		// Skins
		$usergroup['allowedskins'] = implode(',', $usergroup['allowedskins']);

		// Limits
		$limitFields = array('maxmb', 'maxsigs', 'maxresponses', 'maxrecips', 'maxcontacts', 'maxattach', 'maxaliases');
		foreach ($limitFields as $limitField) {
			if (${"usergroup_$limitField"."_nolimit"}) {
				$usergroup["$limitField"] = 0;
			}
		}
		if (!$maxmsgs_every_nolimit) {
			$usergroup['msgpersec'] = serialize($maxmsgs);
		} else {
			$usergroup['msgpersec'] = serialize(array('every' => 0, 'unit' => 60));
		}

		// Permissions
		$usergroup['perms'] = 0;
		foreach ($usergroup as $conname => $value) {
			if (substr($conname, 0, strlen('GROUP_')) != 'GROUP_') {
				continue;
			}
			if ($value) {
				$usergroup['perms'] += constant($conname);
			}
			unset($usergroup["$conname"]);
		}
		
		adminlog($usergroupid, true);
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
if ($cmd == 'edit') {
	startform('usergroup.php', 'update', '', array('usergroup_title' => 'title'));

	$usergroup = getinfo('usergroup', $usergroupid, false, false);
	adminlog($usergroupid);
	if ($usergroup === false) {
		$usergroup = array(
			'title' => '',
			'maxmb' => '5',
			'maxattach' => '1',
			'maxsigs' => '0',
			'maxrecips' => '0',
			'maxresponses' => '0',
			'maxcontacts' => '0',
			'maxaliases' => '1',
			'msgpersec' => array('every' => 0, 'unit' => 1),
			'perms' => GROUP_DEFAULTBITS,
			'changepass' => '0',
		);
		$usergroupid = 0;
		starttable('Create new user group');
	} else {
		$usergroup['allowedskins'] = explode(',', $usergroup['allowedskins']);
		$usergroup['msgpersec'] = unserialize($usergroup['msgpersec']);
		starttable('Update user group "'.$usergroup['title'].'" (ID: '.$usergroupid.')');
	}

	// Skin selection
	$checkboxes = '';
	$theclass = getclass();
	$skins = $DB_site->query('SELECT * FROM hive_skin');
	while ($skin = $DB_site->fetch_array($skins)) {
		$checkboxes .= "<input type=\"checkbox\" name=\"usergroup[allowedskins][]\" class=\"radio_$theclass\" id=\"skin_$skin[skinid]\" value=\"$skin[skinid]\"".iif(!is_array($usergroup['allowedskins']) or in_array($skin['skinid'], $usergroup['allowedskins']), ' checked="checked"')." /> <label for=\"skin_$skin[skinid]\">$skin[title]</label><br />\n";
	}

	hiddenfield('usergroupid', $usergroupid);
	inputfield('Title:', 'usergroup[title]', $usergroup['title']);
	echo "	<tr class=\"$theclass\">\n";
	echo "		<td valign=\"top\">Avilable skins:<br /><span class=\"cp_small\">Users from this user group will only be able to<br />choose from the skins that are selected to the right.</span></td>\n";
	echo "		<td valign=\"top\">$checkboxes</td>\n";
	echo "	</tr>\n";
	getclass();
	/* --- */
	tablehead(array('General Permissions'), 2);
	foreach ($_groupbits as $conname => $text) {
		yesno($text, 'usergroup['.$conname.']', $usergroup['perms'] & constant($conname));
	}
	/* --- */
	tablehead(array('User Limitations'), 2);
	tablerow(array('Users can send emails once every:<br /><span class="cp_small">Set either fields to 0 to impose no limit at all.</span>',
		'<input type="radio" name="'.reform_field_name('maxmsgs[every]').'_nolimit" id="'.reform_field_name('maxmsgs[every]').'_nolimit_false" value="0"'.iif($usergroup['msgpersec']['every'] != 0, ' checked="checked"').' />
		<input type="text" class="bginput" name="maxmsgs[every]" onClick="this.form.'.reform_field_name('maxmsgs[every]').'_nolimit_false.checked = true;" value="'.$usergroup['msgpersec']['every'].'" size="3" />
		<select name="maxmsgs[unit]" onChange="this.form.'.reform_field_name('maxmsgs[every]').'_nolimit_false.checked = true;">
			<option value="1"'.iif($usergroup['msgpersec']['unit'] == 1, ' selected="selected"').'>seconds</option>
			<option value="60"'.iif($usergroup['msgpersec']['unit'] == 60, ' selected="selected"').'>minutes</option>
			<option value="3600"'.iif($usergroup['msgpersec']['unit'] == 3600, ' selected="selected"').'>hours</option>
			<option value="86400"'.iif($usergroup['msgpersec']['unit'] == 86400, ' selected="selected"').'>days</option>
		</select><br />
		<input type="radio" name="'.reform_field_name('maxmsgs[every]').'_nolimit" id="'.reform_field_name('maxmsgs[every]').'_nolimit_true" value="1"'.iif($usergroup['msgpersec']['every'] == 0, ' checked="checked"').' />
		<label for="'.reform_field_name('maxmsgs[every]').'_nolimit_true">Unlimited</label>'), true, true);
	limitfield('Maximum storage (in megabytes):', 'usergroup[maxmb]', $usergroup['maxmb'], '31', 0, false);
	limitfield('Maximum signatures:<br /><span class="cp_small">The maximum number of signatures every user can have.<br />Set this to 1 to only allow one signature per user.</span>', 'usergroup[maxsigs]', $usergroup['maxsigs']);
	limitfield('Maximum auto-responders:<br /><span class="cp_small">The maximum number of auto-responders every user can have.<br />Set this to 1 to only allow one automatic response per user.</span>', 'usergroup[maxresponses]', $usergroup['maxresponses']);
	limitfield('Maximum recipients per message:<br /><span class="cp_small">The maximum number of recipients for every message users send. Useful for preventing mass-distribution of spam emails.</span>', 'usergroup[maxrecips]', $usergroup['maxrecips']);
	limitfield('Maximum address book contacts:<br /><span class="cp_small">The maximum number of contacts in the address book every user can have.</span>', 'usergroup[maxcontacts]', $usergroup['maxcontacts']);
	limitfield('Maximum outgoing attachments size (in megabytes):<br /><span class="cp_small">This is the maximum size of attachments in outgoing messages for users.</span>', 'usergroup[maxattach]', $usergroup['maxattach'], '31', 0, false);
	limitfield('Maximum number of aliases:<br /><span class="cp_small">This is the maximum number of aliases users can have.</span>', 'usergroup[maxaliases]', $usergroup['maxaliases']);
	limitfield('Remind users to change password after:<br /><span class="cp_small">Users will be reminded to change their password after this amount of days have passed since they last changed it.</span>', 'usergroup[changepass]', $usergroup['changepass'], 26, 0, true, "Don't remind at all", ' days');
	/* --- */
	tablehead(array('Group Signature'), 2);
	textarea('Email signature (text version):<br /><span class="cp_small">This signature will be attached to all outgoing messages of users in this group. This version will be used for normal text messages.</span>', 'usergroup[groupsig_text]', $usergroup['groupsig_text'], 6, 40);
	textarea('Email signature (HTML version):<br /><span class="cp_small">Same as above, but this version will be added to HTML messages.<br />Please remember to use &lt;br /&gt; for new lines! This signature will be used as-is and new lines will not be added automatically.</span>', 'usergroup[groupsig_html]', $usergroup['groupsig_html'], 6, 40);
	/* --- */
	tablehead(array('Account Expiration'), 2);
	inputfield('Days to wait before deleting messages:<br /><span class="cp_small">If the user has not logged in for this period of time, the messages in the account will automatically be deleted. Set this to 0 to disable this feature.</span>', 'usergroup[emptytime]', $usergroup['emptytime']);
	yesno('Notify users before messages are deleted?<br /><span class="cp_small">This will allow the user to log-in in time before the messages are deleted.<br />Notifications will only be sent to users who have entered a secondary email address.<br />The contents of this notification is taken from the "Warning" <a href="template.php">templates</a>.</span>', 'usergroup[GROUP_NOTIFY_EMPTY]', $usergroup['perms'] & GROUP_NOTIFY_EMPTY);
	inputfield('Days to wait before removing account:<br /><span class="cp_small">If the user has not logged in for this period of time, the account will automatically be removed from the system. Set this to 0 to disable this feature.</span>', 'usergroup[removetime]', $usergroup['removetime']);
	yesno('Notify users before account is removed?<br /><span class="cp_small">This will allow the user to log-in in time before the account is removed.<br />Notifications will only be sent to users who have entered a secondary email address.<br />The contents of this notification is taken from the "Warning" <a href="template.php">templates</a>.</span>', 'usergroup[GROUP_NOTIFY_REMOVE]', $usergroup['perms'] & GROUP_NOTIFY_REMOVE);
	inputfield('Notification times:<br /><span class="cp_small">Here you can specify exactly when users will be notified by email if any of the options above are enabled. For example, to send an email to the users 3 days before their account or messages are deleted, enter 3 in this box. Multiple notifications can also be sent, simply separate intervals with a space or comma. For example, if you enter 2,5,10 in this box, an email will be sent to users 10, 5, and finally 2 days before any action is taken. The numbers can appear in any order. <b>Note</b>: This must be set correctly or no notifications will be sent at all.</span>', 'usergroup[notifytime]', $usergroup['notifytime']);

	if ($usergroupid == 0) {
		endform('Create user group');
	} else {
		endform('Update user group', 'Reset Fields');
	}
	endtable();
}

// ############################################################################
// List the usergroups
if ($cmd == 'modify') {
	adminlog();
	starttable('', '450');
	tablehead(array('ID', 'Title', 'Users', 'Options'));
	$groups = $DB_site->query('
		SELECT usergroup.*, COUNT(user.userid) AS users
		FROM hive_usergroup AS usergroup
		LEFT JOIN hive_user AS user USING (usergroupid)
		GROUP BY usergroup.usergroupid
	');
	if ($DB_site->num_rows($groups) < 1) {
		textrow('No groups', 4, 1);
	} else {
		while ($group = $DB_site->fetch_array($groups)) {
			tablerow(array(
				$group['usergroupid'],
				"<a href=\"usergroup.php?cmd=edit&usergroupid=$group[usergroupid]\">$group[title]</a>",
				iif($group['users'] > 0, "<a href=\"user.php?cmd=results&find[eusergroupid]=$group[usergroupid]\">$group[users] user".iif($group['users'] != 1, 's').'</a>', "$group[users] users"),
				makelink('edit', "usergroup.php?cmd=edit&usergroupid=$group[usergroupid]").iif($group['usergroupid'] > 3, '-'.makelink('remove', "usergroup.php?cmd=remove&usergroupid=$group[usergroupid]"))
			), false, false, false);
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