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
// | $RCSfile: addressbook.update.php,v $ - $Revision: 1.18 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'addbook_edit,addbook_edit_entry,redirect_addbook_editentries,redirect_addbook_deleteentries,redirect_addbook_copyentries';
require_once('./global.php');
require_once('./includes/functions_addbook.php');

// ############################################################################
// Set default cmd
if (!isset($cmd) and isset($contactid)) {
	$cmd = 'edit';
}

// ############################################################################
// Get navigation bar
makemailnav(3);

// ############################################################################
if ($cmd == 'edit') {
	define('LOAD_MINI_TEMPLATES', true);
	define('NO_JS', true);
	$contact = getinfo('contact', $contactid);

	// Email addresses
	$emailoptions = '<option value="0" class="defaultItem">'.htmlchars($contact['email']).'</option>';
	$contact['emailinfo'] = unserialize($contact['emailinfo']);
	foreach ($contact['emailinfo'] as $email) {
		if ($email != $contact['email']) {
			$emailoptions .= '<option value="0" class="normalItem">'.htmlchars($email).'</option>';
		}
	}

	// Extended name
	$contact['name'] = iif($contact['name'] != $contact['email'], $contact['name']);
	$contact['nameinfo'] = htmlchars(unserialize($contact['nameinfo']));

	// Birthday
	$contact['birthday'] = explode('-', $contact['birthday']);
	$byearsel = makenumbersel(1900, date('Y'), $contact['birthday'][0]);
	$bmonthsel = makemonthsel($contact['birthday'][1]);
	$bdaysel = makenumbersel(1, 31, $contact['birthday'][2]);

	// Timezone
	$noselect = true;
	$tzsel = array(iif($contact['timezone'] >= 0, $contact['timezone'] * 10, 'n'.abs($contact['timezone'] * 10)) => 'selected="selected"');
	for ($time = -120; $time < 125; $time += 5) {
		$tztime[iif($time >= 0, $time, 'n'.abs($time))] = hivedate(TIMENOW, getop('timeformat'), $time / 10);
	}
	$fieldname = 'contact[timezone]';
	eval(makeeval('timezone', 'options_timezone'));

	// Web page and notes
	$contact['webpage'] = htmlchars($contact['webpage']);
	$contact['notes'] = htmlchars($contact['notes']);

	// Addresses
	$addressoptions = $addressarray = '';
	$contact['addressinfo'] = unserialize($contact['addressinfo']);
	$i = 0;
	foreach ($contact['addressinfo'] as $addressinfo) {
		$i++;
		$addresstitle = array();
		if (!empty($addressinfo['name'])) {
			$addresstitle[] = $addressinfo['name'].':';
		}
		if (!empty($addressinfo['street'])) {
			$addresstitle[] = str_replace("\n", ' ', $addressinfo['street']).',';
		}
		if (!empty($addressinfo['city'])) {
			$addresstitle[] = $addressinfo['city'].',';
		}
		if (!empty($addressinfo['state'])) {
			$addresstitle[] = $addressinfo['state'].',';
		}
		$addresstitle = substr(implode(' ', $addresstitle), 0, -1);
		$addressinfo = htmlchars($addressinfo);
		$addressoptions .= '<option value="'.$i.'" class="'.iif((int) $addressinfo['default'], 'default', 'normal').'Item">'.$addresstitle."</option>\n";
		$addressarray .= str_replace("\n", '\n', 'addressInfo['.$i.'] = new Array("'.$addressinfo['name'].'", "'.$addressinfo['street'].'", "'.$addressinfo['city'].'", "'.$addressinfo['state'].'", "'.$addressinfo['zip'].'", "'.$addressinfo['country'].'", "'.((int) $addressinfo['default']).'");')."\n";
	}

	// Phone numbers
	$phoneoptions = '';
	$contact['phoneinfo'] = unserialize($contact['phoneinfo']);
	foreach ($contact['phoneinfo'] as $phoneinfo) {
		$phoneoptions .= '<option value="'.$phoneinfo['type'].'" class="'.iif($phoneinfo['default'], 'default', 'normal').'Item">'.htmlchars($phoneinfo['phone'])."</option>\n";
	}

	eval(makeeval('echo', 'addbook_edit'));
}

// ############################################################################
if ($_POST['cmd'] == 'update') {
	$contactid = getinfo('contact', $contactid, true);

	// Email addresses
	$emails = preg_split("#\r?\n#", trim($emailData));
	$contact['email'] = '';
	$contact['emailinfo'] = array();
	foreach ($emails as $emailbit) {
		list(, $email, $isDef) = explode('|', $emailbit);
		if ($isDef) {
			$contact['email'] = $email;
		} else {
			$contact['emailinfo'][] = $email;
		}
	}
	if (empty($contact['email'])) {
		$contact['email'] = array_shift($contact['emailinfo']);
	}
	$contact['emailinfo'] = serialize($contact['emailinfo']);

	// Extended name
	$contact['nameinfo'] = serialize($contact['nameinfo']);

	// Birthday
	$day = intme($contact['birthday']['day']);
	$month = intme($contact['birthday']['month']);
	$year = intme($contact['birthday']['year']);
	if ($day < 1 or $day > 31 or $month < 1 or $month > 12) {
		$contact['birthday'] = '0000-00-00';
	} else {
		if ($year < 1901 or $year > date('Y')) {
			$year = '0000';
		}
		$contact['birthday'] = "$year-$month-$day";
	}

	// Timezone
	floatme($contact['timezone']);

	// Addresses
	$addresses = preg_split("#\r?\n#", trim($addressData));
	$totaladdys = count($addresses) / 7;
	$contact['addressinfo'] = array();
	for ($i = 0; $i < $totaladdys; $i++) {
		$contact['addressinfo'][$i] = array();
		$contact['addressinfo'][$i]['name'] = $addresses[$i * 7 + 0];
		$contact['addressinfo'][$i]['street'] = str_replace('~', "\n", $addresses[$i * 7 + 1]);
		$contact['addressinfo'][$i]['city'] = $addresses[$i * 7 + 2];
		$contact['addressinfo'][$i]['state'] = $addresses[$i * 7 + 3];
		$contact['addressinfo'][$i]['zip'] = $addresses[$i * 7 + 4];
		$contact['addressinfo'][$i]['country'] = $addresses[$i * 7 + 5];
		$contact['addressinfo'][$i]['default'] = (bool) intme($addresses[$i * 7 + 6]);
	}
	$contact['addressinfo'] = serialize($contact['addressinfo']);

	// Phone numbers
	$phones = preg_split("#\r?\n#", trim($phoneData));
	$contact['phoneinfo'] = array();
	foreach ($phones as $i => $phonebit) {
		list($contact['phoneinfo'][$i]['type'], $contact['phoneinfo'][$i]['phone'], $contact['phoneinfo'][$i]['default']) = explode('|', $phonebit);
	}
	$contact['phoneinfo'] = serialize($contact['phoneinfo']);

	// Update contact
	$DB_site->auto_query('contact', $contact, "contactid = $contactid");

	// Close window and refresh parent
	?><script language="JavaScript" type="text/javascript">
	<!--
	window.opener.location.reload();
	window.close()
	//-->
	</script><?php
	exit;
}

// ############################################################################
if ($_POST['cmd'] == 'delete') {
	if (!is_array($contactcheck)) {
		invalid('contacts');
	}
	foreach ($contactcheck as $bookid => $doit) {
		if ($doit == 'yes') {
			$DB_site->query("
				DELETE FROM hive_contact
				WHERE contactid = $bookid
				AND userid = $hiveuser[userid]
			");
		}
	}

	eval(makeredirect("redirect_addbook_deleteentries", "addressbook.view.php"));
}

// ############################################################################
if ($_POST['cmd'] == 'copy') {
	$group = getinfo('contactgroup', $copyto);
	$contacts = explode(',', $group['contacts']);

	foreach ($contactcheck as $bookid => $doit) {
		if ($doit == 'yes') {
			$contacts[] = intval($bookid);
		}
	}
	$contacts = array_filter(array_unique($contacts), 'strlen');

	$DB_site->query("
		UPDATE hive_contactgroup
		SET contacts = '".addslashes(implode(',', $contacts))."'
		WHERE contactgroupid = $group[contactgroupid]
	");

	eval(makeredirect("redirect_addbook_copyentries", "addressbook.view.php?contactgroupid=$group[contactgroupid]"));
}

// ############################################################################
if ($_POST['cmd'] == 'email') {
	if (!is_array($contactcheck)) {
		invalid('contacts');
	}

	$contactids = array();
	foreach ($contactcheck as $bookid => $doit) {
		if ($doit == 'yes') {
			$contactids[] = intval($bookid);
		}
	}

	$contacts = $DB_site->query("
		SELECT email, name
		FROM hive_contact
		WHERE contactid IN (".implode(', ', $contactids).") AND userid IN (0, $hiveuser[userid])
		ORDER BY name
		".iif($hivemail['maxrecips'] > 0, "LIMIT $hivemail[maxrecips]")."
	");
	$data = array('html' => $hiveuser['wysiwyg'], 'addedsig' => 0, 'priority' => 3);
	while ($contact = $DB_site->fetch_array($contacts)) {
		if ($contact['email'] != $contact['name']) {
			$data['to'] .= "$contact[name] <$contact[email]>; ";
		} else {
			$data['to'] .= "$contact[email]; ";
		}
	}
	$data['to'] = substr(unhtmlchars($data['to']), 0, -2);

	$DB_site->query("
		INSERT INTO hive_draft
		SET draftid = NULL, userid = $hiveuser[userid], dateline = ".TIMENOW.", data = '".addslashes(base64_encode(serialize($data)))."'
	");
	$draftid = $DB_site->insert_id();
	header("Location: compose.email.php?draftid=$draftid");
	exit;
}

// ############################################################################
if ($_POST['cmd'] == 'addgroup') {
	$title = trim($grouptitle);
	if (empty($title)) {
		eval(makeerror('error_grouptitleempty'));
	}

	$DB_site->query("
		INSERT INTO hive_contactgroup
		SET contactgroupid = NULL, userid = $hiveuser[userid], title = '".addslashes($title)."', contacts = ''
	");

	eval(makeredirect("redirect_addbook_groupadded", "addressbook.view.php?contactgroupid=".$DB_site->insert_id()));
}

// ############################################################################
if ($_POST['cmd'] == 'delgroup') {
	if (!is_array($groupcheck)) {
		invalid('groups');
	}
	foreach ($groupcheck as $groupid => $doit) {
		if ($doit != 'yes') {
			continue;
		}
		$DB_site->query("
			DELETE FROM hive_contactgroup
			WHERE contactgroupid = ".intval($groupid)."
			AND userid = $hiveuser[userid]
		");
	}

	eval(makeredirect("redirect_addbook_groupdeleted", "addressbook.view.php"));
}

?>