<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: addressbook.view.php,v $
// | $Date: 2002/11/05 16:02:33 $
// | $Revision: 1.15 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'addbook_main_entry,addbook_main,addbook_main_noentries,addbook_mini_entry,addbook_mini';
require_once('./global.php');

// ############################################################################
// Set default do
if (!isset($do)) {
	$do = 'view';
}

// ############################################################################
// Get navigation bar
makemailnav(3);

// ############################################################################
if ($do == 'view') {
	$contacts = '';
	$addbooks = $DB_site->query("
		SELECT contactid, email, name
		FROM contact
		WHERE userid = $hiveuser[userid]
		ORDER BY name
	");
	for ($counter = ($DB_site->num_rows($addbooks) % 2); $addbook = $DB_site->fetch_array($addbooks); $counter++) {
		if (($counter % 2) == 0) {
			$classname = 'normalRow';
		} else {
			$classname = 'highRow';
		}
		if ($addbook['name'] != $addbook['email']) {
			$addbook['link'] = urlencode("\"$addbook[name]\" <$addbook[email]>");
		} else {
			$addbook['link'] = urlencode($addbook['email']);
		}
		eval(makeeval('contacts', 'addbook_main_entry', 1));
	}
	if (empty($contacts)) {
		eval(makeeval('contacts', 'addbook_main_noentries'));
	}

	$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; Address Book';
	eval(makeeval('echo', 'addbook_main'));
}

// ############################################################################
if ($do == 'mini') {
	define('LOAD_MINI_TEMPLATES', true);

	$pres = $pre;
	foreach ($pre as $prename => $prevalue) {
		$pre["$prename"] = preg_split('#(,|;)#', trim($prevalue));
	}
	foreach ($pre as $prename => $prevalue) {
		$$prename = '';
		foreach ($prevalue as $contact) {
			if (strstr($contact, '<')) {
				$addbook['name'] = trim(substr($contact, 0, strpos($contact, '<')));
				if ($addbook['name']{0} == '"') {
					$addbook['name'] = substr($addbook['name'], 1, -1);
				}
				preg_match("/([-.a-z0-9_]+@[-.a-z0-9_)]*)/i", $contact, $getemail);
				$addbook['email'] = $getemail[1];
				eval(makeeval($prename, 'addbook_mini_entry', 1));
			} else {
				$addbook['name'] = $addbook['email'] = trim($contact);
				if (!empty($addbook['email'])) {
					eval(makeeval($prename, 'addbook_mini_entry', 1));
				}
			}
		}
	}

	$contacts = '';
	$addbooks = $DB_site->query("
		SELECT contactid, email, name
		FROM contact
		WHERE userid = $hiveuser[userid]
		ORDER BY name
	");
	while( $addbook = $DB_site->fetch_array($addbooks)) {
		eval(makeeval('contacts', 'addbook_mini_entry', 1));
	}
	eval(makeeval('echo', 'addbook_mini'));
}

?>