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
// | $RCSfile: user.php,v $ - $Revision: 1.60 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
@set_time_limit(0);
require_once('./global.php');
require_once('../includes/functions_smtp.php');
require_once('../includes/functions_mime.php');
require_once('../includes/functions_field.php');
require_once('../includes/data_country.php');
cp_header(' &raquo; Users');

// ############################################################################
// Set the default cmd
default_var($cmd, 'search');
if ($cmd == 'add') {
	$cmd = 'edit';
}

// ############################################################################
// Update the default options of new users
if ($_POST['cmd'] == 'defupdate') {
	cp_nav('userop');
	$defoptions = $defoptions2 = 0;
	foreach ($_userbits as $_group) {
		foreach ($_group as $conname => $devnul) {
			if ($auser["$conname"]) {
				if (!array_contains($conname, $_userextrabits)) {
					$defoptions += constant($conname);
				} else {
					$defoptions2 += constant($conname);
				}
			}
			unset($auser["$conname"]);
		}
	}
	if ($autoaddsig > 0) {
		$defoptions += USER_AUTOADDSIG;
	}
	if ($autoaddsig == 1) {
		$defoptions += USER_DONTADDSIGONREPLY;
	}

	$defoptions = array($defoptions, $defoptions2);
	$DB_site->query("
		UPDATE hive_setting
		SET value = '".addslashes(serialize($defoptions))."'
		WHERE variable = 'defuseroptions'
	");

	adminlog(0, true); // We don't consider field errors to be real errors
	cp_redirect('The default user options have been saved.', 'user.php');
}

// ############################################################################
// Change the default options of new users
if ($cmd == 'defoptions') {
	cp_nav('userop');
	adminlog();
	$defuseroptions = unserialize(getop('defuseroptions'));
	$auser = decode_user_options(array('options' => $defuseroptions[0], 'options2' => $defuseroptions[1]));
	startform('user.php', 'defupdate');
	starttable('Default user options');
	textrow('These are the default options that users will be assigned when they register. (This will not effect current users, in order to change the options of a certain user you must edit his profie separately.)');

	tablehead(array('Reading Options'), 2);
	foreach ($_userbits['read'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', (bool) $auser[strtolower(substr($conname, 5))]);
	}
	tablehead(array('Compose Options'), 2);
	foreach ($_userbits['compose'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', (bool) $auser[strtolower(substr($conname, 5))]);
	}
	$class = getclass();
	tablerow(array('Automatically add signature:', '<input type="radio" class="radio_'.$class.'" name="autoaddsig" value="2" id="autoaddsigon"'.iif($auser['autoaddsig'] and !$auser['dontaddsigonreply'], ' checked="checked"').' /> <label for="autoaddsigon">Yes</label><br /><input type="radio" class="radio_'.$class.'" name="autoaddsig" value="1" id="autoaddsigonly"'.iif($auser['autoaddsig'] and $auser['dontaddsigonreply'], ' checked="checked"').' /> <label for="autoaddsigonly">Only when not replying</label><br /><input type="radio" class="radio_'.$class.'" name="autoaddsig" value="0" id="autoaddsigoff"'.iif(!$auser['autoaddsig'], ' checked="checked"').' /> <label for="autoaddsigoff">No</label>'), $class);
	tablehead(array('Folder View Options'), 2);
	foreach ($_userbits['folder'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', (bool) $auser[strtolower(substr($conname, 5))]);
	}
	tablehead(array('Calendar Options'), 2);
	foreach ($_userbits['calendar'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', (bool) $auser[strtolower(substr($conname, 5))]);
	}
	tablehead(array('General Options'), 2);
	foreach ($_userbits['general'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', (bool) $auser[strtolower(substr($conname, 5))]);
	}
	
	endform('Update user', 'Reset Fields');
	endtable();
}

// ############################################################################
// Remove user
if ($_POST['cmd'] == 'kill') {
	cp_nav('usermanage');
	$auser = getinfo('user', $auserid);
	if (!$DB_site->query_first("SELECT userid FROM hive_user AS user LEFT JOIN hive_usergroup AS usergroup USING (usergroupid) WHERE userid <> $auserid AND (perms & ".GROUP_CANADMIN.")")) {
		adminlog($auserid, false);
		cp_error('You cannot remove the last administrator from the system.');
	} elseif ($auserid == $hiveuser['userid']) {
		adminlog($auserid, false);
		cp_error('You cannot remove your own account.');
	}

	kill_user($auserid);

	adminlog($auserid, true);
	cp_redirect('The user has been deleted from the system.', 'user.php');
}

// ############################################################################
// Remove user
if ($cmd == 'remove') {
	cp_nav('usermanage');
	$auser = getinfo('user', $auserid);
	if (!$DB_site->query_first("SELECT userid FROM hive_user AS user LEFT JOIN hive_usergroup AS usergroup USING (usergroupid) WHERE userid <> $auserid AND (perms & ".GROUP_CANADMIN.")")) {
		adminlog($auserid, false);
		cp_error('You cannot remove the last administrator from the system.');
	} elseif ($auserid == $hiveuser['userid']) {
		adminlog($auserid, false);
		cp_error('You cannot remove your own account.');
	}

	adminlog($auserid);
	startform('user.php', 'kill', 'Are you sure you want to remove this user account?');
	starttable('Delete user "'.$auser['username'].$auser['domain'].'" (ID: '.$auserid.')');
	textrow('Are you <b>sure</b> you want to delete this account?<br />All data associated with this user will be <b>IRREVERSIBLY</b> deleted! This procedure <b>cannot</b> be reveresed!');
	hiddenfield('auserid', $auserid);
	endform('Delete User', '', 'Go Back');
	endtable();
}

// ############################################################################
// Create a new user or update an existing one
if ($_POST['cmd'] == 'update') {
	$newuser = ($auserid == 0);

	if ($newuser) {
		cp_nav('useradd');
	} else {
		cp_nav('usermanage');
	}

	// Taken username
	if ($newuser and user_exists($auser['username'])) {
		adminlog($auserid, false);
		cp_error('There is already a user with this username ("'.$auser['username'].'").');
	}

	// Last admin
	$newusergroup = getinfo('usergroup', $auser['usergroupid']);
	if (!($newusergroup['perms'] & GROUP_CANADMIN) and !$DB_site->query_first("SELECT userid FROM hive_user AS user LEFT JOIN hive_usergroup AS usergroup USING (usergroupid) WHERE userid <> $auserid AND (perms & ".GROUP_CANADMIN.")")) {
		adminlog($auserid, false);
		cp_error('You cannot place the last administrator in a group without administrative privileges.');
	}

	// Valid skin
	if (!in_array($auser['skinid'], explode(',', $newusergroup['allowedskins']))) {
		adminlog($auserid, false);
		cp_error('The skin you have chosen for this user is not one that is available to users of the "'.$newusergroup['title'].'" group. Please go back and choose another skin.');
	}

	// Required fields
	$missinfield = '';
	if (empty($auser['username'])) {
		$missinfield = 'username';
	} elseif (empty($auser['realname'])) {
		$missinfield = 'real name';
	} elseif (empty($auser['question'])) {
		$missinfield = 'secret question';
	} elseif ($newuser and empty($auser['apassword'])) {
		$missinfield = 'password';
	} elseif ($newuser and empty($auser['answer'])) {
		$missinfield = 'secret answer';
	}
	if (!empty($missinfield)) {
		adminlog($auserid, false);
		cp_error("The $missinfield field is required. Please go back, fill it in and submit the form again.");
	}

	// Profile fields
	$allfields = $DB_site->query('
		SELECT *
		FROM hive_field
		WHERE display > 0 AND module = "user"
		ORDER BY display
	');
	$queries = array();
	$fielderrors = '';
	while ($field = $DB_site->fetch_array($allfields)) {
		$field['value'] = process_field_value($field, $fields[$field['fieldid']], $fields_custom[$field['fieldid']], $errcode, $current_number);

		if ($field['value'] === false) {
			eval(makeeval('fielderrors', "error_field_$errcode", 1));
		} elseif ($field['value'] != $hiveuser["field$field[fieldid]"]) {
			if (is_array($field['value'])) {
				if (!empty($field['value'])) {
					$set = 'value = "", choices = ",'.addslashes(implode(',', $field['value'])).',"';
				} else {
					$set = 'value = "", choices = ""';
				}
			} else {
				$set = 'value = "'.addslashes($field['value']).'", choices = ""';
			}
			$queries[] = "REPLACE INTO hive_fieldinfo SET fieldid = $field[fieldid], dateline = ".TIMENOW.", $set";
		}
	}

	// Registration date
	$auser['regdate'] = strtotime($auser['regdate']);

	// Options
	$auser['options'] = $auser['options2'] = 0;
	foreach ($_userbits as $_group) {
		foreach ($_group as $conname => $devnul) {
			if ($auser["$conname"]) {
				if (!array_contains($conname, $_userextrabits)) {
					$auser['options'] += constant($conname);
				} else {
					$auser['options2'] += constant($conname);
				}
			}
			unset($auser["$conname"]);
		}
	}

	// Other... stuff
	intme($auser['sendread']);
	intme($auser['markread']);
	if ($autoaddsig > 0) {
		$auser['options'] += USER_AUTOADDSIG;
	}
	if ($autoaddsig == 1) {
		$auser['options'] += USER_DONTADDSIGONREPLY;
	}
	if ($newuser) {
		$auser['lastvisit'] = TIMENOW;
		$auser['font'] = 'Verdana|10|Regular|Black|None';
		$auser['cols'] = USER_DEFAULTCOLS;
		$auser['aliases'] = $auser['username'];
	} else {
		$aliasarray = array('"'.addslashes($auser['username']).'"');
		$aliasvalues = array("(NULL, $auserid, '".addslashes($auser['username'])."')");
		$auser['aliases'] = array_unique(array_filter(explode(' ', trim($auser['aliases'])), 'strlen'));
		foreach ($auser['aliases'] as $alias) {
			if ($alias == $auser['username']) {
				continue;
			}
			$aliasarray[] = '"'.addslashes($alias).'"';
			$aliasvalues[] = "(NULL, $auserid, '".addslashes($alias)."')";
		}
		$takenaliases = $DB_site->query("SELECT * FROM hive_alias WHERE userid <> $auserid AND alias IN (".implode(', ', $aliasarray).")");
		if ($DB_site->num_rows($takenaliases) > 0) {
			$taken = '';
			while ($gettaken = $DB_site->fetch_array($takenaliases)) {
				$taken .= "<li><a href=\"user.php?cmd=edit&auserid=$gettaken[userid]\" target=\"_blank\">$gettaken[alias]$auser[domain]</a></li>\n";
			}
			adminlog($auserid, false);
			cp_error("The following aliases are already taken and therefore cannot be assigned to this user:\n<ul>\n$taken</ul>Click an alias to be taken to the the account of the user that is currently using it.", true, true, false);
		}
		$DB_site->query("DELETE FROM hive_alias WHERE userid = $auserid");
		$DB_site->query('INSERT INTO hive_alias (aliasid, userid, alias) VALUES '.implode(', ', $aliasvalues));
		$auser['aliases'] = $auser['username'].' '.implode(' ', $auser['aliases']);
	}
	unset($auser['alias_select']);
	if (empty($auser['replyto'])) {
		$auser['replyto'] = $auser['username'].getop('domainname');
	}

	// Password and answer
	if (!$newuser) {
		if (empty($auser['answer'])) {
			unset($auser['answer']);
		} else {
			$auser['answer'] = md5($auser['answer']);
		}
		if (empty($auser['apassword'])) {
			unset($auser['apassword']);
		} else {
			$auser['password'] = md5($auser['apassword']);
			unset($auser['apassword']);
		}
	} else {
		$auser['answer'] = md5($auser['answer']);
		$auser['password'] = md5($auser['apassword']);
		unset($auser['apassword']);
	}

	// Auto empty bin
	intme($auser['emptybin']);
	$auser['lastbinempty'] = 0;
	if ($auser['emptybin'] > 0) {
		if ($binevery > 0) {
			$auser['emptybin'] = intval($binevery);
			$auser['lastbinempty'] = 0; // This way the bin will be emptied when the user logs in
		} else {
			$auser['emptybin'] = USER_EMPTYBINNO;
		}
	}

	if ($newuser) {
		$DB_site->auto_query('user', $auser);
		$auserid = $DB_site->insert_id();
		$DB_site->auto_query('alias', array('userid' => $auserid, 'alias' => $auser['username']));

		// Welcome email
		if ($auser['usergroupid'] != 3 and getop('sendgreeting')) {
			send_welcome($auserid, $auser['username'], $auser['domain'], $auser['realname']);
		}
	} else {
		$DB_site->auto_query('user', $auser, "userid = $auserid");
	}
	
	// Add fields information
	foreach ($queries as $query) {
		$DB_site->query($query.", userid = $auserid");
	}
	rebuild_field_cache($auserid);

	adminlog($auserid, true); // We don't consider field errors to be real errors

	if (!empty($fielderrors)) {
		cp_error("The following errors occurred while updating the user's profile:\n<ul>\n$fielderrors\n</ul>\nThese erroneous values or choices were not updated in the database, only the valid information was kept. Please click <a href=\"user.php?cmd=edit&auserid=$auserid\">here</a> to go back and update this information.", true, false, false);
	} else {
		if ($newuser) {
			cp_redirect('The user has been created.', 'user.php');
		} else {
			cp_redirect('The user has been updated.', 'user.php');
		}
	}
}

// ############################################################################
// Create a new user or update an existing one
if ($cmd == 'edit') {
	$auser = getuserinfo($auserid);
	adminlog($auserid);
	if ($auser === false) {
		cp_nav('useradd');

		$defuseroptions = unserialize(getop('defuseroptions'));
		$auser = decode_user_options(array(
			'usergroupid' => iif(getop('moderate'), 3, 2),
			'skinid' => getop('defaultskin'),
			'regdate' => TIMENOW,
			'options' => $defuseroptions[0],
			'options2' => $defuseroptions[1],
			'perpage' => 15,
			'replychat' => '> ',
		));
		$auserid = 0;
		startform('user.php', 'update', '', array('auser_username' => 'username', 'auser_realname' => 'real name', 'auser_apassword' => 'password', 'auser_question' => 'secret question', 'auser_answer' => 'secret answer'));
		starttable('Create new user');
	} else {
		cp_nav('usermanage');

		$messages = intval($DB_site->get_field("SELECT COUNT(*) FROM hive_message WHERE userid = $auserid"));
		$messagesize = $DB_site->get_field("SELECT SUM(size) FROM hive_message WHERE userid = $auserid");
		$drafts = intval($DB_site->get_field("SELECT COUNT(*) FROM hive_draft WHERE userid = $auserid"));
		$draftsize = $DB_site->get_field("SELECT SUM(LENGTH(data)) FROM hive_draft WHERE userid = $auserid");
		$lastip = $DB_site->get_field("SELECT ipaddr FROM hive_iplog WHERE userid = $auserid ORDER BY datelastseen DESC");
		$daysago = floor((TIMENOW - $auser['lastvisit']) / (60 * 60 * 24));
		switch ($daysago) {
			case 0:
				$daysago = 'today';
				break;
			case 1:
				$daysago = 'yesterday';
				break;
			default:
				$daysago .= ' days ago';
				break;
		}
		$sent_today = intval($DB_site->get_field("
			SELECT COUNT(*) AS sentmsgs
			FROM hive_eventlog
			WHERE event = 501 AND userid = $auser[userid] AND dateline > ".(TIMENOW - (24 * 60 * 60))."
			GROUP BY userid
		"));
		$sent_week = intval($DB_site->get_field("
			SELECT COUNT(*) AS sentmsgs
			FROM hive_eventlog
			WHERE event = 501 AND userid = $auser[userid] AND dateline > ".(TIMENOW - (7 * 24 * 60 * 60))."
			GROUP BY userid
		"));
		startform('user.php', 'remove');
		hiddenfield('auserid', $auserid);
		starttable('User Statistics: '.$auser['username'].$auser['domain'], '90%', true, 4);
		tablerow(array('<b>Member since:</b>', hivedate($auser['regdate']), '<b>Last visit: ('.$daysago.')</b>', hivedate($auser['lastvisit'])));
		tablerow(array('<b>Number of messages:</b>', $messages, '<b>Messages size:</b>', round($messagesize / (1024 * 1024), 2).' MB'));
		tablerow(array('<b>Number of drafts:</b>', $drafts, '<b>Drafts size:</b>', round($draftsize / (1024 * 1024), 2).' MB'));
		tablerow(array('<b>Sent messages today:</b>', $sent_today, '<b>Last IP address (<a href="iplog.php?cmd=list&filter[euserid]='.$auserid.'&sortby=ipaddr&sortorder=desc">all</a>):</b>', $lastip));
		tablerow(array('<b>Sent messages this week:</b>', $sent_week, '<b>Last password change:</b>', iif($auser['lasspassword'], hivedate($auser['lasspassword']), 'N/A')));
		tablehead(array('<input class="button" type="submit" value="  Remove User  " />'.iif(!$auser['isbanned'], '&nbsp;&nbsp;<input class="button" type="submit" value="  Suspend User  " onClick="this.form.action = \'ban.php\'; this.form.cmd.value = \'add\';" />')), 4);
		endform();
		endtable();
		echo '<br />';

		if ($auser['isbanned']) {
			$ban = $DB_site->query_first("
				SELECT ban.*, admin.username AS adminusername, admin.domain AS admindomain, admin.realname AS adminrealname
				FROM hive_ban AS ban
				LEFT JOIN hive_user AS admin ON (ban.adminid = admin.userid)
				WHERE ban.userid = $auser[userid]
			");
			startform('ban.php', 'modify');
			hiddenfield('banid', $ban['banid']);
			starttable('User Suspended');
			textrow('This user has been suspended since <b>'.hivedate($ban['dateline'], 'Y-m-d').'</b> for '.iif($ban['duration'] == 0, 'an <b>unlimited</b> amount of time', iif($ban['duration'] == 1, '<b>1</b> day', "<b>$ban[duration]</b> days")).'. '.iif(empty($ban['reason']), "No reason was given by the administrator <b>$ban[adminusername]$ban[admindomain]</b> ($ban[adminrealname]).", "The reason that was given by the administrator <b>$ban[adminusername]$ban[admindomain]</b> ($ban[adminrealname]) was:<br /><i>".htmlchars($ban['reason']).'</i>'));
			endform('More Information');
			endtable();
			echo '<br />';
		}

		startform('user.php', 'update', '', array('auser_username' => 'username', 'auser_realname' => 'real name', 'auser_question' => 'secret question'), false, 'for (var i = 0; i < this.user_aliases.options.length; i++) this.auser_aliases.value += this.user_aliases.options[i].value + \' \';');
		starttable('Update user "'.$auser['username'].'" (ID: '.$auserid.')');
	}

	hiddenfield('auserid', $auserid);
	hiddenfield('oldusername', $auser['username']);
	inputfield('Username: (*)', 'auser[username]', $auser['username']);
	tableselect('User group:', 'auser[usergroupid]', 'usergroup', $auser['usergroupid']);
	selectbox('Domain name:', 'auser[domain]', getop('domainnames'), $auser['domain'], false, '', 1, '', true);
	if ($auserid != 0) {
		$aliasops = '';
		foreach ($auser['aliases'] as $aliasKey => $alias) {
			if ($alias == $auser['username']) {
				unset($auser['aliases'][$aliasKey]);
				continue;
			}
			$aliasops .= "<option value=\"$alias\">$alias$auser[domain]</option>\n";
		}
		$aliassize = iif(count($auser['aliases']) > 4, count($auser['aliases']), 4);
		hiddenfield('auser[aliases]', '');
		tablerow(array(
			'Aliases:<br /><span class="cp_small">(Besides the username which is the default alias.)</span><br /><br /><input type="button" class="button" name="new" value="New Alias" onClick="name = prompt(\'Enter the new alias:\', \'\'); if (name == null || name == \'\') return false; this.form.user_aliases.options[this.form.user_aliases.options.length] = new Option(name + \''.$auser['domain'].'\', name);" style="width: 150px;" /> <input type="button" class="button" name="remove" value="Remove Alias" disabled="disabled" onClick="if (confirm(\'Are you sure you want to remove this alias?\')) { this.form.user_aliases.options[this.form.user_aliases.selectedIndex] = null; this.disabled = true; }" style="width: 150px;" />',
			'<select name="auser[alias_select]" id="user_aliases" style="width: 225px;" size="'.$aliassize.'" onChange="this.form.remove.disabled = (this.selectedIndex == -1);">'.$aliasops.'</select>'
		), true, true);
		if (!empty($auser['aliaslog'])) {
			$logs = '';
			foreach ($auser['aliaslog'] as $logentry) {
				list($action, $time, $alias) = explode('|', $logentry);
				if (intme($time) > 0) {
					$logs .= "- <b>".iif($action == 'add', '&nbsp;Created&nbsp;', 'Removed')."</b> alias <b>".htmlchars($alias)."</b> at <b>".hivedate($time, 'Y-m-d')."</b><br />\n";
				}
			}
			if (!empty($logs)) {
				textrow("Actions regarding aliases: &nbsp;<span class=\"cp_small\">(only actions performed by the user are logged)</span><br />\n$logs");
			}
		}
	}
	tableselect('Skin:<br /><span class="cp_small">Note: The skin you chose here must be available to the users of the group this user belongs to.</span>', 'auser[skinid]', 'skin', $auser['skinid']);
	inputfield('Secondary email address', 'auser[altemail]', $auser['altemail']);
	datefield('Registration date:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'auser[regdate]', hivedate($auser['regdate'], 'Y-m-d'));
	textrow('Notes about this user: &nbsp;<span class="cp_small">(only visible to administrators)</span><br /><textarea name="auser[adminnotes]" rows="5" cols="85">'.htmlchars($auser['adminnotes']).'</textarea>');
	/***************/
	if ($auserid != 0) {
		tablehead(array('<input class="button" type="submit" value="  Update user  " />'), 2);
		endtable();
		starttable('Personal Information');
	} else {
		tablehead(array('Personal Information'), 2);
	}
	inputfield('Real name: (*)', 'auser[realname]', $auser['realname']);
	datefield('Birthday:<br /><span class="cp_small">Format: yyyy-mm-dd.<br />Use 0000 as the year to ignore it.</span>', 'auser[birthday]', $auser['birthday']);
	selectbox('Country:', 'auser[country]', $_countries, $auser['country']);
	selectbox('State:', 'auser[state]', $_states, $auser['state']);
	inputfield('Zip code:', 'auser[zip]', $auser['zip'], 7);
	/***************/
	tablehead(array('Password and Security'), 2);
	inputfield('Password:'.iif($auserid != 0, '<br /><span class="cp_small">Leave empty unless you want to change it.</span>', ' (*)'), 'auser[apassword]');
	inputfield('Secret question: (*)', 'auser[question]', $auser['question']);
	inputfield('Secret answer:'.iif($auserid != 0, '<br /><span class="cp_small">Leave empty unless you want to change it.</span>', ' (*)'), 'auser[answer]');
	/***************/
	tablehead(array('Reading Options'), 2);
	foreach ($_userbits['read'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', (bool) $auser[strtolower(substr($conname, 5))]);
	}
	$class = getclass();
	tablerow(array('Returning read receipts:', '<input type="radio" class="radio_'.$class.'" name="auser[sendread]" value="0" id="sendreadno"'.iif($auser['sendread'] == USER_SENDREADNO, ' checked="checked"').' /> <label for="sendreadno">Never send a read receipt</label><br />
	<input type="radio" class="radio_'.$class.'" name="auser[sendread]" value="1" id="sendreadask"'.iif($auser['sendread'] == USER_SENDREADASK, ' checked="checked"').' /> <label for="sendreadask">Notify me for each read receipt request</label><br />
	<input type="radio" class="radio_'.$class.'" name="auser[sendread]" value="2" id="sendreadalways"'.iif($auser['sendread'] == USER_SENDREADALWAYS, ' checked="checked"').' /> <label for="sendreadalways">Always send a read receipt</label>'), $class);
	/***************/
	tablehead(array('Compose Options'), 2);
	foreach ($_userbits['compose'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', (bool) $auser[strtolower(substr($conname, 5))]);
	}
	inputfield('Original message prefix:', 'auser[replychar]', $auser['replychar'], 7, '', false);
	inputfield('Default reply-to address:', 'auser[replyto]', $auser['replyto']);
	$class = getclass();
	tablerow(array('Automatically add signature:', '<input type="radio" class="radio_'.$class.'" name="autoaddsig" value="2" id="autoaddsigon"'.iif($auser['autoaddsig'] and !$auser['dontaddsigonreply'], ' checked="checked"').' /> <label for="autoaddsigon">Yes</label><br /><input type="radio" class="radio_'.$class.'" name="autoaddsig" value="1" id="autoaddsigonly"'.iif($auser['autoaddsig'] and $auser['dontaddsigonreply'], ' checked="checked"').' /> <label for="autoaddsigonly">Only when not replying</label><br /><input type="radio" class="radio_'.$class.'" name="autoaddsig" value="0" id="autoaddsigoff"'.iif(!$auser['autoaddsig'], ' checked="checked"').' /> <label for="autoaddsigoff">No</label>'), $class);
	/***************/
	tablehead(array('Folder View Options'), 2);
	foreach ($_userbits['folder'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', (bool) $auser[strtolower(substr($conname, 5))]);
	}
	inputfield('Page refresh rate (in seconds):', 'auser[autorefresh]', $auser['autorefresh'], 4);
	inputfield('Messages per page:', 'auser[perpage]', $auser['perpage'], 4);
	inputfield('Automatically mark messages read (in seconds):', 'auser[markread]', $auser['markread'], 4);
	/***************/
	tablehead(array('Calendar Options'), 2);
	foreach ($_userbits['calendar'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', (bool) $auser[strtolower(substr($conname, 5))]);
	}
	/***************/
	tablehead(array('General Options'), 2);
	foreach ($_userbits['general'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', (bool) $auser[strtolower(substr($conname, 5))]);
	}
	inputfield('Auto forward address:', 'auser[forward]', $auser['forward']);

	$class = getclass();
	tablerow(array('Empty the trash can automatically:', '<input type="radio" class="radio_'.$class.'" name="auser[emptybin]" value="-2" id="emptybinonexit"'.iif($auser['emptybin'] == USER_EMPTYBINONEXIT, ' checked="checked"').' /> <label for="emptybinonexit">Empty folder on exit</label><br /><input type="radio" class="radio_'.$class.'" name="auser[emptybin]" value="1" id="emptybinevery"'.iif($auser['emptybin'] != USER_EMPTYBINNO and $auser['emptybin'] != USER_EMPTYBINONEXIT, ' checked="checked"').' /> <label for="emptybinevery">Empty folder every &nbsp;<input type="text" name="binevery" value="'.iif($auser['emptybin'] != USER_EMPTYBINNO and $auser['emptybin'] != USER_EMPTYBINONEXIT, $auser['emptybin']).'" size="3" class="bginput" maxlength="3" onClick="emptybinevery.checked = true;" />&nbsp; days</label><br /><input type="radio" class="radio_'.$class.'" name="auser[emptybin]" value="-1" id="emptybinno"'.iif($auser['emptybin'] == USER_EMPTYBINNO, ' checked="checked"').' /> <label for="emptybinno">Never empty folder</label>'), $class);
	/***************/
	$allfields = $DB_site->query('
		SELECT *
		FROM hive_field
		WHERE display > 0 AND module = "user"
		ORDER BY display
	');
	if ($DB_site->num_rows($allfields)) {
		tablehead(array('Custom Profile Fields'), 2);
		while ($field = $DB_site->fetch_array($allfields)) {
			tablerow(array($field['title'].'<br /><span class="cp_small">'.$field['description'].'</span>', make_field_html($field, $auser["field$field[fieldid]"])), true, true, false, false);
		}
	}
	/***************/

	if ($auserid == 0) {
		endform('Create user');
	} else {
		endform('Update user', 'Reset Fields');
	}
	endtable();
	echo 'Fields with an asterisk (*) next to them are required.<br /><br /><br />';
}

// ############################################################################
// Search results
if ($cmd == 'results') {
	if (count($_GET) == 1) {
		cp_nav('userall');
	} else {
		cp_nav('usermanage');
	}
	adminlog();
	$sqlwhere = '1 = 1';
	$link = '';
	if (is_array($find)) {
		foreach ($find as $subject => $value) {
			$value = trim($value);
			if (empty($value) or $value == -1) {
				continue;
			}

			$field = substr($subject, 1);
			$link .= "&find[$subject]=".urlencode($value);
			if ($field == 'password') {
				$value = md5($value);
			}

			switch (substr($subject, 0, 1)) {
				case 'l':
					if (substr($field, 0, 4) == 'date') {
						$field = substr($field, 4);
						$sqlwhere .= " AND $field < UNIX_TIMESTAMP('".addslashes($value)."')";
					} else {
						$sqlwhere .= " AND $field < '".intval($value)."'";
					}
					break;
				case 'm':
					if (substr($field, 0, 4) == 'date') {
						$field = substr($field, 4);
						$sqlwhere .= " AND $field > UNIX_TIMESTAMP('".addslashes($value)."')";
					} else {
						$sqlwhere .= " AND $field > '".intval($value)."'";
					}
					break;
				case 'e':
					$sqlwhere .= " AND LCASE($field) = '".addslashes(strtolower($value))."'";
					break;
				case 'c':
				default:
					$sqlwhere .= " AND INSTR(LCASE($field), '".addslashes(strtolower($value))."') > 0";
					break;
			}
		}
	}

	// Profile fields
	$allfields = $DB_site->query('
		SELECT *
		FROM hive_field
		WHERE display > 0 AND module = "user"
		ORDER BY display
	');
	$usersets = array();
	$fieldvalues = array();
	$fielderrors = '';
	while ($field = $DB_site->fetch_array($allfields)) {
		$field['value'] = process_field_value($field, $fields[$field['fieldid']], $fields_custom[$field['fieldid']], $errcode, $current_number);

		if ($field['value'] === false or ($field['value'] == 0 and ($field['type'] == 'select' or $field['type'] == 'radio'))) {
			$fieldvalues["field$field[fieldid]"] = false;
		} else {
			$fieldvalues["field$field[fieldid]"] = $field['value'];
			if (is_array($field['value'])) {
				if (!empty($field['value'])) {
					$where = 'value = "" AND choices = ",'.addslashes(implode(',', $field['value'])).',"';
				} else {
					$where = 'value = "" AND choices = ""';
				}
			} else {
				$where = 'value = "'.addslashes($field['value']).'" AND choices = ""';
			}
			$usersets[] = $DB_site->query("
				SELECT userid
				FROM hive_fieldinfo
				WHERE fieldid = $field[fieldid] AND $where
			");
		}
	}
	$fielduserids = null;
	foreach ($usersets as $userset) {
		$tempuserids = array();
		while ($auser = $DB_site->fetch_array($userset)) {
			$tempuserids[] = $auser['userid'];
		}
		if ($fielduserids !== null) {
			$fielduserids = array_intersect($fielduserids, $tempuserids);
		} elseif (!empty($tempuserids)) {
			$fielduserids = $tempuserids;
		}
	}

	// By sent messages
	$sentuserids = null;
	if (is_array($sent) and $sent['msgs'] > 0 and $sent['timenum'] > 0) {
		$sentuserids = array();
		$sentusers = $DB_site->query('
			SELECT COUNT(*) AS sentmsgs, userid
			FROM hive_eventlog
			WHERE event = 501 AND dateline > '.(TIMENOW - ($sent['timenum'] * $sent['timeperiod'] * 60)).'
			GROUP BY userid
			HAVING sentmsgs >= '.intval($sent['msgs']).'
		');
		while ($sentuser = $DB_site->fetch_array($sentusers)) {
			$sentuserids[] = $sentuser['userid'];
		}
	}

	// Get all user ID's
	$ausers = $DB_site->query("
		SELECT userid
		FROM hive_user
		WHERE $sqlwhere
	");
	$userids = array();
	while ($auser = $DB_site->fetch_array($ausers)) {
		$userids[] = $auser['userid'];
	}

	// Intersect the ID's with the other requirements
	if (is_array($fielduserids)) {
		$userids = array_intersect($userids, $fielduserids);
	}
	if (is_array($sentuserids)) {
		$userids = array_intersect($userids, $sentuserids);
	}

	// No matches?
	if (empty($userids)) {
		$userids = '0';
	} else {
		$userids = implode(',', $userids);
	}

	// Pagination
	$link = "user.php?cmd=results$link";
	$pagenav = paginate('user', $link, "WHERE userid IN ($userids)");

	// Only one user found?
	if ($totalitems == 1) {
		cp_redirect('Only one user found...', 'user.php?cmd=edit&auserid='.$userids);
	}

	// Get storage
	/* $getsizes = $DB_site->query("
		SELECT userid, SUM(size) AS totalsize, COUNT(*) AS totalcount
		FROM hive_message
		WHERE userid IN ($userids)
		GROUP BY userid
	");
	$sizes = $counts = array();
	while ($size = $DB_site->fetch_array($getsizes)) {
		$sizes[$size['userid']] = $size['totalsize'];
		$counts[$size['userid']] = $size['totalcount'];
	} */

	// Sort order
	$sortorder = strtolower($sortorder);
	if ($sortorder != 'desc') {
		$sortorder = 'asc';
	} else {
		$newsortorder = 'desc';
	}
	$sortorder = strtoupper($sortorder);

	// Sort field
	switch ($sortby) {
		case 'userid':
		case 'username':
		case 'realname':
		case 'usergroupid':
		case 'lastvisit':
		case 'regdate':
		case 'messages':
		case 'sizes':
			break;
		default:
			$sortby = 'userid';
	}

	$ausers = $DB_site->query("
		SELECT user.*, usergroup.title, COUNT(messageid) AS messages, SUM(size) AS sizes
		FROM hive_user AS user
		LEFT JOIN hive_usergroup AS usergroup ON (user.usergroupid = usergroup.usergroupid)
		LEFT JOIN hive_message AS message ON (message.userid = user.userid)
		WHERE user.userid IN ($userids)
		GROUP BY user.userid
		ORDER BY $sortby $sortorder
		LIMIT ".($limitlower-1).", $perpage
	");
	starttable();
	$link = "$link&perpage=$perpage&pagenumber=$pagenumber&";
	$cells = array(
		'<a href="'.$link.get_sort_string('userid').'">ID</a>',
		'<a href="'.$link.get_sort_string('username').'">Email Address</a>',
		'<a href="'.$link.get_sort_string('realname').'">Real Name</a><br />(<a href="'.$link.get_sort_string('usergroupid').'">User Group</a>)',
		'<a href="'.$link.get_sort_string('lastvisit').'">Last Login</a><br />(<a href="'.$link.get_sort_string('regdate').'">Joined On</a>)',
		'<a href="'.$link.get_sort_string('messages').'">Total Messages</a><br />(<a href="'.$link.get_sort_string('sizes').'">Total Size</a>)',
	);
	tablehead($cells);
	if ($DB_site->num_rows($ausers) < 1) {
		textrow('No users found, try some different terms.', count($cells), 1);
	} else {
		while ($auser = $DB_site->fetch_array($ausers)) {
			$auser['domain'] = verify_domain($auser['domain']);
			if (strlen($auser['realname']) > 16) {
				$auser['realname'] = substr($auser['realname'], 0, 16).'...';
			}
			if (round($auser['sizes'] / (1024 * 1024), 2) > 0) {
				$size = '<br />('.round($auser['sizes'] / (1024 * 1024), 2).' MB)';
			} elseif (round($auser['sizes'] / 1024, 2) > 0) {
				$size = '<br />('.round($auser['sizes'] / 1024, 2).' kb)';
			} else {
				$size = '';
			}
			$cells = array(
				$auser['userid'],
				"<a href=\"user.php?cmd=edit&auserid=$auser[userid]\">$auser[username]$auser[domain]</a>",
				"$auser[realname]<br />(<a href=\"usergroup.php?cmd=edit&usergroupid=$auser[usergroupid]\">$auser[title]</a>)",
				'&nbsp;'.hivedate($auser['lastvisit']).'<br />('.hivedate($auser['regdate']).')',
				'center' => '&nbsp;'.number_format($auser['messages']).' messages'.$size,
			);
			tablerow($cells, true);
		}
	}
	tablehead(array("$pagenav&nbsp;"), count($cells));
	endtable();

	if ($DB_site->num_rows($ausers) > 0) {
		startform('user.php', 'stuff');
		starttable('Found users', '450');
		hiddenfield('userids', $userids);
		selectbox('Select an action to perform:', 'dowhat', array('prune' => 'Mass-prune users', 'empty' => 'Empty user accounts', 'move' => 'Move users to a new group', 'email' => 'Mass-email users'));
		endform('Perform Action');
		endtable();
	}
	echo '<br /><br />';

	$cmd = 'search';
}

// ############################################################################
// Find users
if ($cmd == 'search') {
	cp_nav('usermanage');
	if ($_POST['cmd'] != 'results') {
		$sent = array('msgs' => '0', 'timenum' => '10', 'timeperiod' => '60');
	}

	adminlog();
	startform('user.php', 'results');
	starttable('Find users...');
	inputfield('Username contains:', 'find[cusername]', $find['cusername']);
	inputfield('Password is:', 'find[cpassword]', $find['cpassword']);
	inputfield('Real name contains:', 'find[crealname]', $find['crealname']);
	tableselect('User group is:', 'find[eusergroupid]', 'usergroup', $find['eusergroupid'], '1 = 1', 'any group');
	inputfield('Currently using the alias:', 'find[caliases]', $find['caliases']);
	inputfield('Previously used the alias:', 'find[caliaslog]', $find['caliaslog']);
	datefield('Registered after:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'find[mdateregdate]', $find['mdateregdate']);
	datefield('Registered before:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'find[ldateregdate]', $find['ldateregdate']);
	datefield('Last visited the system after:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'find[mdatelastvisit]', $find['mdatelastvisit']);
	datefield('Last visited the system before:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'find[ldatelastvisit]', $find['ldatelastvisit']);
	selectbox('Live in country:', 'find[ecountry]', $_countries, $find['ecountry'], 'any country');
	selectbox('Live in state:', 'find[estate]', $_states, $find['estate'], 'any state');
	inputfield('Zip code contains:', 'find[czip]', $find['czip'], 7);
	tablerow(array('Sent at least:',
		'<input type="text" class="bginput" name="sent[msgs]" value="'.$sent['msgs'].'" size="3" />
		&nbsp;messages in the last&nbsp;
		<input type="text" class="bginput" name="sent[timenum]" value="'.$sent['timenum'].'" size="3" />
		<select name="sent[timeperiod]">
			<option value="1"'.iif($sent['timeperiod'] == 1, ' selected="selected"').'>seconds</option>
			<option value="60"'.iif($sent['timeperiod'] == 60, ' selected="selected"').'>minutes</option>
			<option value="3600"'.iif($sent['timeperiod'] == 3600, ' selected="selected"').'>hours</option>
			<option value="86400"'.iif($sent['timeperiod'] == 86400, ' selected="selected"').'>days</option>
		</select>'), true, true);
	$allfields = $DB_site->query('
		SELECT *
		FROM hive_field
		WHERE display > 0 AND module = "user"
		ORDER BY display
	');
	if ($DB_site->num_rows($allfields)) {
		tablehead(array('Custom Profile Fields'), 2);
		while ($field = $DB_site->fetch_array($allfields)) {
			tablerow(array($field['title'].'<br /><span class="cp_small">'.$field['description'].'</span>', make_field_html($field, iif(!is_array($fieldvalues), false, $fieldvalues["field$field[fieldid]"]))), true, true, false, false);
		}
	}
	endform('Find users');
	endtable();
}

// ############################################################################
// Move users
if ($_POST['cmd'] == 'move') {
	cp_nav('usermanage');
	$newgroup = getinfo('usergroup', $newgroupid);

	$updateids = '0';
	foreach ($approves as $auserid => $doit) {
		if ($doit == 1) {
			if (!($newgroup['perms'] & GROUP_CANADMIN) and !$DB_site->query_first("SELECT userid FROM hive_user AS user LEFT JOIN hive_usergroup AS usergroup USING (usergroupid) WHERE userid <> $auserid AND (perms & ".GROUP_CANADMIN.")")) {
				adminlog(0, false);
				cp_error('You cannot place the last administrator in a group without administrative privileges.');
			}
			$updateids .= ",$auserid";
		}
	}

	$DB_site->query("
		UPDATE hive_user
		SET usergroupid = $newgroup[usergroupid]
		WHERE userid IN ($updateids)
	");
	adminlog(0, true, 'move', 'Moved users: '.substr($updateids, 2));
	cp_redirect('The users have been moved to the new user group.', 'user.php');
}

// ############################################################################
// Prune users
if ($_POST['cmd'] == 'empty') {
	cp_nav('usermanage');
	$emptyids = '0';
	foreach ($approves as $auserid => $doit) {
		if ($doit == 1) {
			$emptyids .= ",$auserid";
		}
	}

	delete_messages("userid IN ($emptyids)", true, false);
	adminlog(0, true, 'empty', 'Emptied accounts: '.substr($deleteids, 2));
	cp_redirect('All selected accounts were emptied.', 'user.php');
}

// ############################################################################
// Prune users
if ($_POST['cmd'] == 'prune') {
	cp_nav('usermanage');
	$deleteids = '0';
	foreach ($approves as $auserid => $doit) {
		if ($doit == 1) {
			if (!$DB_site->query_first("SELECT userid FROM hive_user AS user LEFT JOIN hive_usergroup AS usergroup USING (usergroupid) WHERE userid <> $auserid AND (perms & ".GROUP_CANADMIN.")")) {
				adminlog(0, false);
				cp_error('You cannot remove the last administrator from the system.');
			} elseif ($auserid == $hiveuser['userid']) {
				adminlog(0, false);
				cp_error('You cannot remove your own account.');
			} else {
				$deleteids .= ",$auserid";
			}
		}
	}

	delete_messages("userid IN ($deleteids)", true, false);
	$DB_site->query("
		DELETE FROM hive_contact
		WHERE userid IN ($deleteids)
	");
	$DB_site->query("
		DELETE FROM hive_draft
		WHERE userid IN ($deleteids)
	");
	$DB_site->query("
		DELETE FROM hive_folder
		WHERE userid IN ($deleteids)
	");
	$DB_site->query("
		DELETE FROM hive_pop
		WHERE userid IN ($deleteids)
	");
	$DB_site->query("
		DELETE FROM hive_rule
		WHERE userid IN ($deleteids)
	");
	$DB_site->query("
		DELETE FROM hive_search
		WHERE userid IN ($deleteids)
	");
	$DB_site->query(" 
        DELETE FROM hive_alias 
        WHERE userid IN ($deleteids) 
    "); 
	$DB_site->query("
		DELETE FROM hive_user
		WHERE userid IN ($deleteids)
	");
	$DB_site->query("
		DELETE FROM hive_iplog
		WHERE userid IN ($deleteids)
	");
	adminlog(0, true, 'prune', 'Pruned users: '.substr($deleteids, 2));
	cp_redirect('The users have been removed from the system.', 'user.php');
}

// ############################################################################
// Email users
if ($cmd == 'email') {
	cp_nav('usermanage');
	if (is_array($approves)) {
		foreach ($approves as $auserid => $doit) {
			if ($doit == 1) {
				$userids .= ",$auserid";
			}
		}
		$userids = substr($userids, 1);
	}

	$search = array('$username', '$email', '$realname');
	$userids_array = explode(',', $userids);
	$send_ids = '0';
	$ausers = $DB_site->query("
		SELECT *
		FROM hive_user
		WHERE userid IN ($userids)
		LIMIT $perpage
	");
	echo "<p>Emailing users $nextstart to ".($nextstart + $DB_site->num_rows($ausers) - 1).":\n<ul>\n";
	if (!empty($copymail)) {
		echo "<li>Sending copy to $copymail ... ";
		$sent = smtp_mail($copymail, $subject, $body, 'From: '.getop('smtp_errorfrom'), false);
		if ($sent) {
			echo '<span class="cp_temp_orig"><b>OK!</b></span>';
		} else {
			echo '<span class="cp_temp_edit"><b>ERROR</b></span>';
		}
		echo "</li>\n";
	}
	flush();
	while ($auser = $DB_site->fetch_array($ausers)) {
		echo "<li>'$auser[username]' (ID: $auser[userid]) ... ";
		$auser['domain'] = verify_domain($auser['domain']);

		if ($secondaddress) {
			if (empty($auser['altemail'])) {
				echo '<span class="cp_temp_orig"><b>IGNORED</b></span>';
				if (($userpos = array_search($auser['userid'], $userids_array)) !== false) {
					unset($userids_array[$userpos]);
				}
				continue;
			}
			$auser['email'] = $auser['altemail'];
		} else {
			$auser['email'] = $auser['username'].$auser['domain'];
		}
		$replace = array($auser['username'], $auser['email'], $auser['realname']);

		$sent = smtp_mail($auser['email'], str_replace($search, $replace, $subject), $thisbody = str_replace($search, $replace, $body), array('From: '.getop('smtp_errorfrom'), 'X-AuthCode: '.md5(trim($thisbody).'CyKuH')), false);
		unset($thisbody);

		if (($userpos = array_search($auser['userid'], $userids_array)) !== false) {
			unset($userids_array[$userpos]);
		}
		$send_ids .= ",$auser[userid]";

		if ($sent) {
			echo '<span class="cp_temp_orig"><b>OK!</b></span>';
		} else {
			echo '<span class="cp_temp_edit"><b>ERROR</b></span>';
		}
		echo "</li>\n";
		flush();
	}
	echo "</ul>\n";

	if (is_object($_smtp_connection)) {
		$_smtp_connection->quit();
	}

	adminlog(0, true, 'email', 'Emailed users: '.substr($send_ids, 2));
	if (count($userids_array) < 1) {
		echo "All done!\n";
	} elseif ($autodirect and 0) {
		//cp_redirect('.', 'user.php?cmd=email&userids='.implode(',', $userids_array).'&autodirect=1&');
	} else {
		startform('user.php', 'email');
		starttable('Send Email', '550');
		inputfield('Message subject:', 'subject', $subject);
		textarea('Message body:<br /><br />
		<span class="cp_small">You can use the following variables in this field:<br />
		$username = user\'s username<br />
		$email = user\'s full email<br />
		$realname = user\'s real name</span>', 'body', $body);
		inputfield('Emails to send per page:<br /><span class="cp_small">Please do not set this to a high number, for performance reasons.</span>', 'perpage', $perpage);
		//yesno('Automatically redirect between pages:<br /><span class="cp_small">If this is enabled the system will automatically redirect itself to the next page when it\'s done processing the current batch of users.</span>', 'autodirect');
		hiddenfield('secondaddress', $secondaddress);
		hiddenfield('autodirect', '0');
		hiddenfield('userids', implode(',', $userids_array));
		hiddenfield('nextstart', $nextstart + $perpage);
		endform('Next Page...');
		endtable();
	}
}

// ############################################################################
// Do stuff to users
if ($cmd == 'stuff') {
	cp_nav('usermanage');
	if ($emailall == 1) {
		$dowhat = 'email';
		// Get all user ID's
		$ausers = $DB_site->query('
			SELECT userid
			FROM hive_user
		');
		$userids = '';
		while ($auser = $DB_site->fetch_array($ausers)) {
			$userids .= ','.$auser['userid'];
		}
		$userids = substr($userids, 1);
	}
	if ($dowhat == 'move') {
		echo '<p>Use this form to move users from one group to another. Choosing <b>Yes</b><br />will update the user\'s account, while choosing <b>No</b> will leave it untouched.</p>';
	} elseif ($dowhat == 'email') {
		echo '<p>Use this form to mass-email your users.<br />Choose <b>Yes</b> to send the email to the user.</p>';
	} elseif ($dowhat == 'empty') {
		echo '<p>Use this form to empty user accounts (i.e delete all messages from the system). Choosing <b>Yes</b><br />will empty the user\'s account, while choosing <b>No</b> will leave it untouched.<br /><b><span class="cp_temp_cust">THIS PROCEDURE IS NOT REVERSIBLE!</span></b></p>';
	} else {
		echo '<p>Use this form to prune users and their messages from system. Choosing <b>Yes</b><br />will remove the user\'s account, while choosing <b>No</b> will leave it untouched.<br /><b><span class="cp_temp_cust">THIS PROCEDURE IS NOT REVERSIBLE!</span></b></p>';		
	}

	adminlog(0, -1, $dowhat, "Intro to $dowhat action");
	startform('user.php', $dowhat, iif($dowhat != 'email', 'Are you sure? This cannot be undone!'));
	starttable('', '550');
	$cells = array(
		'ID',
		'Email',
		'Real Name',
		iif($dowhat == 'move', 'Current Group', 'Last Visited'),
		iif($dowhat == 'move', 'Move?', iif($dowhat == 'email', 'Send?', iif($dowhat == 'empty', 'Empty?', 'Prune?'))),
	);
	tablehead($cells);
	$ausers = $DB_site->query("
		SELECT user.*, usergroup.title AS grouptitle
		FROM hive_user AS user
		LEFT JOIN hive_usergroup AS usergroup USING (usergroupid)
		WHERE userid IN ($userids)
	");
	if ($DB_site->num_rows($ausers) < 1) {
		textrow('No users found', count($cells), 1);
		emptyrow(count($cells));
	} else {
		while ($auser = $DB_site->fetch_array($ausers)) {
			$thisclass = getclass(1);
			$cells = array(
				$auser['userid'],
				$auser['username'].$auser['domain'],
				$auser['realname'],
				iif($dowhat == 'move', $auser['grouptitle'], hivedate($auser['lastvisit'])),
				"<input type=\"radio\" name=\"approves[$auser[userid]]\" id=\"appyes$auser[userid]\" value=\"1\"".iif($dowhat != 'prune' and $dowhat != 'empty', ' checked="checked"')." /> <label for=\"appyes$auser[userid]\">Yes</label> &nbsp;&nbsp;<input type=\"radio\" name=\"approves[$auser[userid]]\" id=\"appno$auser[userid]\" value=\"0\"".iif($dowhat == 'prune' or $dowhat == 'empty', ' checked="checked"')." /> <label for=\"appno$auser[userid]\">No</label>",
			);
			tablerow($cells, false, false);
		}
		if ($dowhat == 'empty') {
			endform('Delete Messages', '', '', '', count($cells));
		} elseif ($dowhat == 'prune') {
			endform('Prune Users', '', '', '', count($cells));
		} else {
			emptyrow(count($cells));
		}
	}
	endtable();

	if ($dowhat == 'move') {
		echo '<br /><br />';
		starttable('New User Group', '550');
		tableselect('Move users to group:', 'newgroupid', 'usergroup');
		endform('Move Users');
		endtable();
	} elseif ($dowhat == 'email') {
		echo '<br /><br />';
		starttable('Send Email', '550');
		inputfield('Message subject:', 'subject');
		textarea('Message body:<br /><br />
		<span class="cp_small">You can use the following variables in this field:<br />
		$username = user\'s username<br />
		$email = user\'s full email<br />
		$realname = user\'s real name</span>', 'body');
		inputfield('Send a copy of the message to:<br /><span class="cp_small">One copy of this message will be sent to this address.<br />Please note that no variables will be parsed on for this special copy.</span>', 'copymail');
		inputfield('Emails to send per cycle:<br /><span class="cp_small">Please do not set this to a high number, for performance reasons.</span>', 'perpage', 50);
		yesno('Send to users\' secondary address:<br /><span class="cp_small">Users without a secondary email address will be ignored.</span>', 'secondaddress', false);
		//yesno('Automatically redirect between pages:<br /><span class="cp_small">If this is enabled the system will automatically redirect itself to the next page when it\'s done processing the current batch of users.</span>', 'autodirect');
		hiddenfield('autodirect', '0');
		hiddenfield('nextstart', '1');
		endform('Email Users');
		endtable();
	}
}

// ############################################################################
// Validate users
if ($_POST['cmd'] == 'dovalidate') {
	cp_nav('uservalidate');
	$ausers = $DB_site->query('
		SELECT *
		FROM hive_user
		WHERE usergroupid = 3
	');

	$updateids = array();
	$deleteids = '0';
	while ($auser = $DB_site->fetch_array($ausers)) {
		$auserid = $auser['userid'];
		$realname = $auser['realname'];
		$usertarget = iif($target[$auserid] == 3 or !getinfo('usergroup', $target[$auserid], true, false), 2, $target[$auserid]);

		if ($approves[$auserid] == -1) {
			continue;
		} elseif ($approves[$auserid] == 1) {
			if (!empty($auser['altemail'])) {
				eval(makeevalsystem('body', 'signup_activate_message'));
				eval(makeevalsystem('subject', 'signup_activate_subject'));
				smtp_mail($auser['altemail'], $subject, $body, 'From: '.getop('smtp_errorfrom'), false);
			}
			if (getop('sendgreeting') and is_array($ausernames)) {
				send_welcome($auser['userid'], $auser['username'], $auser['domain'], $auser['realname']);
				$ausernames[$auser['userid']] = $auser['username'].$auser['domain'];
			}
			$updateids[$usertarget] .= ",$auserid";
		} else {
			$deleteids .= ",$auserid";
		}
	}

	$DB_site->query(" 
        DELETE FROM hive_alias 
        WHERE userid IN ($deleteids) 
    "); 
	$DB_site->query("
		DELETE FROM hive_user
		WHERE userid IN ($deleteids)
	");
	foreach ($updateids as $groupid => $ids) {
		$DB_site->query("
			UPDATE hive_user
			SET usergroupid = $groupid
			WHERE userid IN (0$ids)
		");
		adminlog(0, true, 'validate', 'Approved users (group ID '.$groupid.'): '.substr($ids, 1));
	}

	// Email users
	if (getop('sendgreeting') and is_array($ausernames)) {
		foreach ($ausernames as $auserid => $auseremail) {
			smtp_mail($auseremail, $subjects[$auserid], $bodys[$auserid], 'From: '.getop('smtp_errorfrom'), false);
		}
	}
	if (is_object($_smtp_connection)) {
		$_smtp_connection->quit();
	}

	adminlog(0, true, 'validate', 'Deleted users: '.substr($deleteids, 2));
	cp_redirect('The users have been updated.', 'user.php?cmd=validate');
}

// ############################################################################
// Validate users
if ($cmd == 'validate') {
	cp_nav('uservalidate');
	adminlog(0, -1, 'validate', "Intro to user validation");

	$groupselect = '';
	$groups = table_to_array('usergroup', 'usergroupid', 'usergroupid <> 3', 'title');
	foreach ($groups as $groupid => $title) {
		$groupselect .= "\n<option value=\"$groupid\"".iif($groupid, 'selected="selected"').">$title</option>";
	}
	$groupselect .= "\n</select>";

	echo '<p>Use this form to validate users that have been put into moderation.<br />Choosing <b>Yes</b> will approve the user\'s account, while choosing <b>No</b> will delete<br />the account. Choose <b>Undecided</b> to leave the user in the validation queue.</p>';
	startform('user.php', 'dovalidate');
	starttable('', '550');
	$cells = array(
		'ID',
		'Email',
		'Real Name',
		'Joined On',
		'Approve?',
		'Target group',
	);
	tablehead($cells);
	$ausers = $DB_site->query('
		SELECT *
		FROM hive_user
		WHERE usergroupid = 3
	');
	if ($DB_site->num_rows($ausers) < 1) {
		textrow('No users in queue', count($cells), 1);
		emptyrow(count($cells));
	} else {
		while ($auser = $DB_site->fetch_array($ausers)) {
			$thisclass = getclass(1);
			$cells = array(
				$auser['userid'],
				$auser['username'].$auser['domain'],
				$auser['realname'],
				hivedate($auser['regdate']),
				"<input type=\"radio\" name=\"approves[$auser[userid]]\" id=\"appyes$auser[userid]\" value=\"1\" checked=\"checked\" /> <label for=\"appyes$auser[userid]\">Yes</label> &nbsp;&nbsp;<input type=\"radio\" name=\"approves[$auser[userid]]\" id=\"appno$auser[userid]\" value=\"0\" /> <label for=\"appno$auser[userid]\">No</label> &nbsp;&nbsp;<input type=\"radio\" name=\"approves[$auser[userid]]\" id=\"appun$auser[userid]\" value=\"-1\" /> <label for=\"appun$auser[userid]\">Undecided</label>",
				'<select name="target['.$auser['userid'].']">'.$groupselect,
			);
			tablerow($cells, true);
		}
		endform('Process Users', '', '', '', count($cells));
	}
	endtable();
}

cp_footer();
?>
