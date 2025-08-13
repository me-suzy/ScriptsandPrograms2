<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: addressbook.update.php,v $
// | $Date: 2002/11/05 16:02:33 $
// | $Revision: 1.17 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'addbook_edit,addbook_edit_entry,redirect_addbook_editentries,redirect_addbook_deleteentries';
require_once('./global.php');

// ############################################################################
// Set default do
if ((!isset($do) and isset($contactid)) or ($_POST['do'] == 'update' and !empty($_POST['edit']))) {
	$do = 'edit';
}

// ############################################################################
// Get navigation bar
makemailnav(3);

// ############################################################################
if ($do == 'edit') {
	// Are we only editing one contact?
	// Or maybe only some of them?
	if (isset($contactid)) {
		$contactid = getinfo('contact', $contactid, true);
		$whereid = "AND contactid = $contactid";
	} elseif (is_array($deletelist)) {
		$whereid = 'AND contactid IN (' . implode(',', array_keys($deletelist, 'yes')) . ')';
	} else {
		$whereid = '';
	}

	$contacts = '';
	$addbooks = $DB_site->query("
		SELECT contactid, email, name
		FROM contact
		WHERE userid = $hiveuser[userid] $whereid
		ORDER BY name
	");
	for ($counter = ($DB_site->num_rows($addbooks) % 2); $addbook = $DB_site->fetch_array($addbooks); $counter++) {
		if (($counter % 2) == 0) {
			$classname = 'normalRow';
		} else {
			$classname = 'highRow';
		}
		eval(makeeval('contacts', 'addbook_edit_entry', 1));
	}
	if (empty($contacts)) {
		eval(makeeval('contacts', 'addbook_main_noentries'));
	}

	$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; <a href="addressbook.view.php">Address Book</a> &raquo; Edit Contacts';
	eval(makeeval('echo', 'addbook_edit'));
}

// ############################################################################
if ($_POST['do'] == 'update') {
	if (!empty($delete)) {
		if (!is_array($deletelist)) {
			invalid('contacts');
		}
		foreach ($deletelist as $bookid => $doit) {
			if ($doit == 'yes') {
				$DB_site->query("
					DELETE FROM contact
					WHERE contactid = $bookid
					AND userid = $hiveuser[userid]
				");
			}
		}

		eval(makeredirect("redirect_addbook_deleteentries", "addressbook.view.php"));
	} else {
		if (!is_array($email) or !is_array($name)) {
			invalid('contacts');
		}
		foreach ($name as $keyname => $valname) {
			list($keyemail, $valemail) = each($email);
			if (trim($valname) != '' and trim($valemail) != '') {
				$DB_site->query("
					UPDATE contact SET
					name = '".addslashes(htmlspecialchars(str_replace(';', ' ', $valname)))."', email = '".addslashes(htmlspecialchars(str_replace(';', ' ', $valemail)))."'
					WHERE contactid = $keyname AND userid = $hiveuser[userid]
				");
			} else {
				$DB_site->query("
					DELETE FROM contact
					WHERE contactid = $keyname
					AND userid = $hiveuser[userid]
				");
			}
		}

		eval(makeredirect("redirect_addbook_editentries", "addressbook.view.php"));
	}
}

?>