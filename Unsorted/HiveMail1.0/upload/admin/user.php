<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: user.php,v $
// | $Date: 2002/11/12 15:07:42 $
// | $Revision: 1.21 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('LOAD_COUNTRIES', true);
require_once('./global.php');
require_once('../includes/smtp_functions.php');
cp_header(' &raquo; Users');

// ############################################################################
// Set the default do
if (!isset($do)) {
	$do = 'search';
}

// ############################################################################
// Remove user
if ($_POST['do'] == 'kill') {
	$auser = getinfo('user', $auserid);
	if (!$DB_site->query_first("SELECT userid FROM user LEFT JOIN usergroup USING (usergroupid) WHERE userid <> $auserid AND (perms & ".GROUP_CANADMIN.")")) {
		cp_error('You cannot remove the last administrator from the system.');
	}

	$DB_site->query("
		DELETE FROM contact
		WHERE userid = $auserid
	");
	$DB_site->query("
		DELETE FROM draft
		WHERE userid = $auserid
	");
	$DB_site->query("
		DELETE FROM folder
		WHERE userid = $auserid
	");
	$DB_site->query("
		DELETE FROM message
		WHERE userid = $auserid
	");
	$DB_site->query("
		DELETE FROM pop
		WHERE userid = $auserid
	");
	$DB_site->query("
		DELETE FROM rule
		WHERE userid = $auserid
	");
	$DB_site->query("
		DELETE FROM search
		WHERE userid = $auserid
	");
	$DB_site->query("
		DELETE FROM user
		WHERE userid = $auserid
	");

	cp_redirect('The user has been deleted from the system.', 'user.php');
}

// ############################################################################
// Remove user
if ($do == 'remove') {
	$auser = getinfo('user', $auserid);
	if (!$DB_site->query_first("SELECT userid FROM user LEFT JOIN usergroup USING (usergroupid) WHERE userid <> $auserid AND (perms & ".GROUP_CANADMIN.")")) {
		cp_error('You cannot remove the last administrator from the system.');
	}

	startform('user.php', 'kill', 'Are you sure you want to remove this user account?');
	starttable('Delete user "'.$auser['username'].getop('domainname').'" (ID: '.$auserid.')');
	textrow('Are you <b>sure</b> you want to delete this account?<br />All data associated with this user will be <b>IRREVERSIBLY</b> deleted! This procedure <b>cannot</b> be reveresed!');
	hiddenfield('auserid', $auserid);
	endform('Delete User', '', 'Go Back');
	endtable();
}

// ############################################################################
// Create a new user or update an existing one
if ($_POST['do'] == 'update') {
	// Last admin
	$newusergroup = getinfo('usergroup', $auser['usergroupid']);
	if (!($newusergroup['perms'] & GROUP_CANADMIN) and !$DB_site->query_first("SELECT userid FROM user LEFT JOIN usergroup USING (usergroupid) WHERE userid <> $auserid AND (perms & ".GROUP_CANADMIN.")")) {
		cp_error('You cannot place the last administrator in a group without administrative privileges.');
	}

	// Valid skin
	if (!in_array($auser['skinid'], explode(',', $newusergroup['allowedskins']))) {
		cp_error('The skin you have chosen for this user is not one that is available to users of the "'.$newusergroup['title'].'" group. Please go back and choose another skin.');
	}

	// Required fields
	if (empty($auser['username'])) {
		cp_error('The username field is required. Please go back, fill it in and submit the form again.');
	} elseif (empty($auser['realname'])) {
		cp_error('The real name field is required. Please go back, fill it in and submit the form again.');
	} elseif (empty($auser['question'])) {
		cp_error('The secret question field is required. Please go back, fill it in and submit the form again.');
	} elseif ($auserid == 0) {
		if (empty($auser['apassword'])) {
			cp_error('The password field is required. Please go back, fill it in and submit the form again.');
		} elseif (empty($auser['answer'])) {
			cp_error('The secret answer field is required. Please go back, fill it in and submit the form again.');
		}
	}

	// Registration date
	$auser['regdate'] = strtotime($auser['regdate']);

	// Options
	$auser['options'] = 0;
	foreach ($_userbits as $_group) {
		foreach ($_group as $conname => $devnul) {
			if ($auser["$conname"]) {
				$auser['options'] += constant($conname);
			}
			unset($auser["$conname"]);
		}
	}

	// Other... stuff
	$auser['lastvisit'] = TIMENOW;
	intme($auser['sendread']);
	if ($autoaddsig > 0) {
		$auser['options'] += USER_AUTOADDSIG;
	}
	if ($autoaddsig == 1) {
		$auser['options'] += USER_DONTADDSIGONREPLY;
	}
	if ($auserid == 0) {
		$auser['replyto'] = $auser['username'].getop('domainname');
		$auser['font'] = 'Verdana|10|Regular|Black|None';
		$auser['cols'] = 'a:6:{i:0;s:8:"priority";i:1;s:6:"attach";i:2;s:4:"from";i:3;s:7:"subject";i:4;s:8:"datetime";i:5;s:4:"size";}';
	}

	// Password and answer
	if ($auserid != 0) {
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
	
	if ($auserid == 0) {
		$DB_site->auto_query('user', $auser);
		cp_redirect('The user has been created.', 'user.php');
	} else {
		$DB_site->auto_query('user', $auser, "userid = $auserid");
		cp_redirect('The user has been updated.', 'user.php');
	}
}

// ############################################################################
// Create a new user or update an existing one
if ($do == 'edit') {
	$auser = getuserinfo($auserid);
	if ($auser === false) {
		$auser = decode_user_options(array(
			'usergroupid' => iif(getop('moderate'), 3, 2),
			'skinid' => getop('defaultskin'),
			'regdate' => TIMENOW,
			'options' => USER_DEFAULTBITS,
			'perpage' => 15,
		));
		$auserid = 0;
		echo '<p>Fields with an asterisk (*) next to them are required.</p>';
		startform('user.php', 'update', '', array('auser_username' => 'username', 'auser_realname' => 'real name', 'auser_apassword' => 'password', 'auser_question' => 'secret question', 'auser_answer' => 'secret answer'));
		starttable('Create new user');
	} else {
		$messages = $DB_site->get_field("SELECT COUNT(*) FROM message WHERE userid = $auserid");
		$messagesize = $DB_site->get_field("SELECT SUM(size) FROM message WHERE userid = $auserid");
		$drafts = $DB_site->get_field("SELECT COUNT(*) FROM draft WHERE userid = $auserid");
		$draftsize = $DB_site->get_field("SELECT SUM(LENGTH(data)) FROM draft WHERE userid = $auserid");
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
		startform('user.php', 'remove');
		hiddenfield('auserid', $auserid);
		starttable('User Statistics and Options', '500', true, 4);
		tablerow(array('<b>Member since:</b>', hivedate($auser['regdate']), '<b>Last visit:</b>', hivedate($auser['lastvisit']).' ('.$daysago.')'));
		tablerow(array('<b>Number of messages:</b>', $messages, '<b>Messages size:</b>', round($messagesize / (1024 * 1024), 2).' MB'));
		tablerow(array('<b>Number of drafts:</b>', $drafts, '<b>Drafts size:</b>', round($draftsize / (1024 * 1024), 2).' MB'));
		endform('Remove User', '', '', '', 4);
		endtable();
		echo '<br /><br />';

		echo '<p>Fields with an asterisk (*) next to them are required.</p>';
		startform('user.php', 'update', '', array('auser_username' => 'username', 'auser_realname' => 'real name', 'auser_question' => 'secret question'));
		starttable('Update user "'.$auser['username'].getop('domainname').'" (ID: '.$auserid.')');
	}

	hiddenfield('auserid', $auserid);
	inputfield('Username: (*)', 'auser[username]', $auser['username']);
	tableselect('User group:', 'auser[usergroupid]', 'usergroup', $auser['usergroupid']);
	tableselect('Skin:<br /><span class="cp_small">Note: The skin you chose here must be available to the users of the group<br />this user belongs to.</span>', 'auser[skinid]', 'skin', $auser['skinid']);
	inputfield('Secondary email address', 'auser[altemail]', $auser['altemail']);
	inputfield('Registration date:<br /><span class="cp_small">Format: yyyy-mm-dd.</span>', 'auser[regdate]', hivedate($auser['regdate'], 'Y-m-d'));
	/***************/
	tablehead(array('Personal Information'), 2);
	inputfield('Real name: (*)', 'auser[realname]', $auser['realname']);
	inputfield('Birthday:<br /><span class="cp_small">Format: yyyy-mm-dd.<br />Use 0000 as the year to ignore it.</span>', 'auser[birthday]', $auser['birthday']);
	selectbox('Country:', 'auser[country]', $_countries, $auser['country']);
	selectbox('State:', 'auser[state]', $_states, $auser['state']);
	inputfield('Zip code:', 'auser[zip]', $auser['zip'], 7);
	/***************/
	tablehead(array('Password and Security'), 2);
	inputfield('Password:'.iif($auserid != 0, '<br /><span class="cp_small">Leave empty unless you want to change it.</span>', ' (*)'), 'auser[apassword]');
	inputfield('Secrew question: (*)', 'auser[question]', $auser['question']);
	inputfield('Secret answer:'.iif($auserid != 0, '<br /><span class="cp_small">Leave empty unless you want to change it.</span>', ' (*)'), 'auser[answer]');
	/***************/
	tablehead(array('Reading Options'), 2);
	foreach ($_userbits['read'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', $auser['options'] & constant($conname));
	}
	$class = getclass();
	tablerow(array('Returning read receipts:', '<input type="radio" class="radio_'.$class.'" name="auser[sendread]" value="0" id="sendreadno"'.iif($auser['sendread'] == USER_SENDREADNO, ' checked="checked"').' /> <label for="sendreadno">Never send a read receipt</label><br />
	<input type="radio" class="radio_'.$class.'" name="auser[sendread]" value="1" id="sendreadask"'.iif($auser['sendread'] == USER_SENDREADASK, ' checked="checked"').' /> <label for="sendreadask">Notify me for each read receipt request</label><br />
	<input type="radio" class="radio_'.$class.'" name="auser[sendread]" value="2" id="sendreadalways"'.iif($auser['sendread'] == USER_SENDREADALWAYS, ' checked="checked"').' /> <label for="sendreadalways">Always send a read receipt</label>'), $class);
	/***************/
	tablehead(array('Compose Options'), 2);
	foreach ($_userbits['compose'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', $auser['options'] & constant($conname));
	}
	inputfield('Original message prefix:', 'auser[replychar]', $auser['replychar'], 7, '', false);
	inputfield('Default reply-to address:', 'auser[replyto]', $auser['replyto']);
	textarea('Signature:', 'auser[signature]', $auser['signature'], 10, 80);
	$class = getclass();
	tablerow(array('Automatically add signature:', '<input type="radio" class="radio_'.$class.'" name="autoaddsig" value="2" id="autoaddsigon"'.iif($auser['autoaddsig'] and !$auser['dontaddsigonreply'], ' checked="checked"').' /> <label for="autoaddsigon">Yes</label><br /><input type="radio" class="radio_'.$class.'" name="autoaddsig" value="1" id="autoaddsigonly"'.iif($auser['autoaddsig'] and $auser['dontaddsigonreply'], ' checked="checked"').' /> <label for="autoaddsigonly">Only when not replying</label><br /><input type="radio" class="radio_'.$class.'" name="autoaddsig" value="0" id="autoaddsigoff"'.iif(!$auser['autoaddsig'], ' checked="checked"').' /> <label for="autoaddsigoff">No</label>'), $class);
	/***************/
	tablehead(array('Folder View Options'), 2);
	foreach ($_userbits['folder'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', $auser['options'] & constant($conname));
	}
	inputfield('Page refresh rate (in seconds):', 'auser[autorefresh]', $auser['autorefresh'], 4);
	inputfield('Messages per page:', 'auser[perpage]', $auser['perpage'], 4);
	/***************/
	tablehead(array('General Options'), 2);
	foreach ($_userbits['general'] as $conname => $text) {
		yesno($text, 'auser['.$conname.']', $auser['options'] & constant($conname));
	}
	inputfield('Auto forward address:', 'auser[forward]', $auser['forward']);

	$class = getclass();
	tablerow(array('Automatically add signature:', '<input type="radio" class="radio_'.$class.'" name="auser[emptybin]" value="-2" id="emptybinonexit"'.iif($auser['emptybin'] == USER_EMPTYBINONEXIT, ' checked="checked"').' /> <label for="emptybinonexit">Empty folder on exit</label><br /><input type="radio" class="radio_'.$class.'" name="auser[emptybin]" value="1" id="emptybinevery"'.iif($auser['emptybin'] != USER_EMPTYBINNO and $auser['emptybin'] != USER_EMPTYBINONEXIT, ' checked="checked"').' /> <label for="emptybinevery">Empty folder every &nbsp;<input type="text" name="binevery" value="'.iif($auser['emptybin'] != USER_EMPTYBINNO and $auser['emptybin'] != USER_EMPTYBINONEXIT, $auser['emptybin']).'" size="3" class="bginput" maxlength="3" onClick="emptybinevery.checked = true;" />&nbsp; days</label><br /><input type="radio" class="radio_'.$class.'" name="auser[emptybin]" value="-1" id="emptybinno"'.iif($auser['emptybin'] == USER_EMPTYBINNO, ' checked="checked"').' /> <label for="emptybinno">Never empty folder</label>'), $class);
	/***************/

	if ($auserid == 0) {
		endform('Create user');
	} else {
		endform('Update user', 'Reset Fields');
	}
	endtable();
}

// ############################################################################
// Search results
if ($do == 'results') {
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

			if (substr($subject, 0, 1) == 'c') {
				$sqlwhere .= " AND INSTR(LCASE($field), '".addslashes(strtolower($value))."') > 0";
			} else {
				$sqlwhere .= " AND LCASE($field) = '".addslashes(strtolower($value))."'";
			}
		}
	}

	$pagenav = paginate('user', "user.php?do=results$link", "WHERE $sqlwhere");
	$ausers = $DB_site->query("
		SELECT *
		FROM user
		WHERE $sqlwhere
		LIMIT ".($limitlower-1).", $perpage
	");

	// Only one user found?
	if ($totalitems == 1) {
		$auser = $DB_site->fetch_array($ausers);
		cp_redirect('Only one user found...', "user.php?do=edit&auserid=$auser[userid]");
	}

	starttable('', '550');
	$cells = array(
		'ID',
		'Email',
		'Real Name',
		'Joined On',
		'Options'
	);
	tablehead($cells);
	if ($DB_site->num_rows($ausers) < 1) {
		textrow('No users found, try some different terms.', count($cells), 1);
	} else {
		while ($auser = $DB_site->fetch_array($ausers)) {
			$cells = array(
				$auser['userid'],
				$auser['username'].getop('domainname'),
				$auser['realname'],
				hivedate($auser['regdate']),
				makelink('edit', "user.php?do=edit&auserid=$auser[userid]") . '-' . makelink('remove', "user.php?do=remove&auserid=$auser[userid]")
			);
			tablerow($cells);
		}
	}
	tablehead(array("$pagenav&nbsp;"), count($cells));
	endtable();

	$do = 'search';
}

// ############################################################################
// Find users
if ($do == 'search') {
	startform('user.php', 'results');
	starttable('Find users...', '550');
	inputfield('Whose username contains:', 'find[cusername]', $find['cusername']);
	inputfield('Whose password is:', 'find[cpassword]', $find['cpassword']);
	inputfield('Whose real name contains:', 'find[crealname]', $find['crealname']);
	tableselect('Whose user group is:', 'find[eusergroupid]', 'usergroup', $find['eusergroupid'], '1 = 1', true);
	textarea('Whose signature contains:', 'find[csignature]', $find['csignature']);
	selectbox('Who live in country:', 'find[ecountry]', $_countries, $find['ecountry'], true);
	selectbox('Who live in state:', 'find[estate]', $_states, $find['estate'], true);
	inputfield('Whose zip code contains:', 'find[czip]', $find['czip'], 7);
	endform('Find users');
	endtable();
}

// ############################################################################
// Validate users
if ($_POST['do'] == 'dovalidate') {
	$ausers = $DB_site->query('
		SELECT *
		FROM user
		WHERE usergroupid = 3
	');

	$updateids = $deleteids = '0';
	while ($auser = $DB_site->fetch_array($ausers)) {
		$auserid = $auser['userid'];

		if ($approves[$auserid] == -1) {
			continue;
		} elseif ($approves[$auserid] == 1) {
			eval(makeeval('body', 'signup_email'));
			eval(makeeval('subject', 'signup_email_subject'));
			smtp_mail($auser['altemail'], $subject, $body, 'From: '.getop('smtp_errorfrom'));
			$updateids .= ",$auserid";
		} else {
			$deleteids .= ",$auserid";
		}
	}

	$DB_site->query("
		UPDATE user
		SET usergroupid = 2
		WHERE userid IN ($updateids)
	");
	$DB_site->query("
		DELETE FROM user
		WHERE userid IN ($deleteids)
	");
	cp_redirect('The users have been updated.', 'user.php?do=validate');
}

// ############################################################################
// Validate users
if ($do == 'validate') {
	echo '<p>Use this form to validate users that have been put into moderation.<br />Choosing <b>Yes</b> will approve the user\'s account, while choosing <b>No</b> will delete<br />the account. Choose <b>Undecided</b> to leave the user in the validation queue.</p>';
	startform('user.php', 'dovalidate');
	starttable('', '550');
	$cells = array(
		'ID',
		'Email',
		'Real Name',
		'Joined On',
		'Approve?'
	);
	tablehead($cells);
	$ausers = $DB_site->query('
		SELECT *
		FROM user
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
				$auser['username'].getop('domainname'),
				$auser['realname'],
				hivedate($auser['regdate']),
				"Yes <input class=\"$thisclass\" type=\"radio\" name=\"approves[$auser[userid]]\" value=\"1\" checked=\"checked\" />&nbsp;&nbsp;No <input class=\"$thisclass\" type=\"radio\" name=\"approves[$auser[userid]]\" value=\"0\" />&nbsp;&nbsp;Undecided <input class=\"$thisclass\" type=\"radio\" name=\"approves[$auser[userid]]\" value=\"-1\" />",
			);
			tablerow($cells, false, false);
		}
		endform('Validate Users', '', '', '', count($cells));
	}
	endtable();
}

cp_footer();
?>