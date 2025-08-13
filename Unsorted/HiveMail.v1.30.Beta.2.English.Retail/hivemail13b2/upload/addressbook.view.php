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
// | $RCSfile: addressbook.view.php,v $ - $Revision: 1.31 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'addbook_main_entry,addbook_main,addbook_main_letterbit,addbook_main_noentries,addbook_mini,addbook_main_groupbit';
require_once('./global.php');
require_once('./includes/functions_addbook.php');

// ############################################################################
// Set default do
if (!isset($cmd)) {
	$cmd = 'view';
}

// ############################################################################
// Get navigation bar
makemailnav(3);

// ############################################################################
if ($cmd == 'view' or $cmd == 'search') {
	// See if a contactgroup is selected
	if (intme($contactgroupid) != 0) {
		$contactgroup = getinfo('contactgroup', $contactgroupid, false, false);
		if (!$contactgroup) {
			$contactgroupid = 0;
		} else {
			$contactgroupid = $contactgroup['contactgroupid'];
			if (empty($contactgroup['contacts'])) {
				$contactgroup['contacts'] = '0';
			}
		}
	} else {
		$contactgroupid = 0;
	}

	// Set default page number and per page values
	if (intme($pagenumber) < 1) {
		$pagenumber = 1;
	}
	if (intme($perpage) < 1)	{
		$perpage = 30;
	}

	// Sort results by
	$sortby = iif($sortby == 'email', 'email', 'name');

	// Sort order
	$sortorder = strtolower($sortorder);
	if ($sortorder != 'desc') {
		$sortorder = 'asc';
		$newsortorder = 'desc';
		$arrow_image = 'arrow_down';
	} else {
		$newsortorder = 'asc';
		$arrow_image = 'arrow_up';
	}
	$sortorder = strtoupper($sortorder);

	// Search query
	$sqlwhere = '';
	if ($cmd == 'search') {
		$conds = array();
		$joiner = iif($cond == 'or', ' OR ', ' AND ');
		if (!empty($name)) {
			$conds[] = 'name LIKE "%'.addslashes($name).'%"';
		}
		if (!empty($email)) {
			$conds[] = 'email LIKE "%'.addslashes($email).'%"';
		}
		if (!empty($conds)) {
			$sqlwhere = 'AND ('.implode($conds, $joiner).')';
		}
	}
	$condselect = array($cond => 'selected="selected"');

	// Are we viewing a particular letter?
	$curletter = urlencode($letter);
	if (!empty($letter)) {
		if ($curletter == '#') {
			$sqlwhere = 'AND name NOT REGEXP "^[a-zA-Z]"';
		} else {
			$sqlwhere = 'AND name LIKE "'.addslashes(chr(intval(ord($letter)))).'%"';
		}
	}

	// Display list of letters
	$i = 35;
	$letters = '';
	do {
		if ($i == 90) {
			$whichCell = 'RightCell';
		} else {
			$whichCell = 'Cell';
		}
		$letter = chr($i);
		$encletter = '%'.dechex($i);
		eval(makeeval('letters', 'addbook_main_letterbit', 1));
		if ($i == 35) {
			$i = 64;
		}
	} while ($i++ < 90);

	// Get total number of addresses
	$totalcontacts = $DB_site->get_field("
		SELECT COUNT(*) AS count
		FROM hive_contact
		WHERE userid IN (0, $hiveuser[userid])
		".iif($contactgroupid != 0, 'AND contactid IN ('.addslashes($contactgroup['contacts']).')')." $sqlwhere
	");

	// Handle pagination stuff
	$limitlower = ($pagenumber-1)*$perpage+1;
	$limitupper = ($pagenumber)*$perpage;
	if ($limitupper > $totalcontacts) {
		$limitupper = $totalcontacts;
		if ($limitlower > $totalcontacts) {
			$limitlower = $totalcontacts-$perpage;
		}
	}
	if ($limitlower <= 0) {
		$limitlower = 1;
	}

	// Show contacts
	$contacts = '';
	$isthereglobal = false;
	$addbooks = $DB_site->query("
		SELECT contactid, userid, email, name
		FROM hive_contact
		WHERE userid IN (0, $hiveuser[userid])
		".iif($contactgroupid != 0, 'AND contactid IN ('.addslashes($contactgroup['contacts']).')')." $sqlwhere
		ORDER BY $sortby $sortorder
		LIMIT ".($limitlower-1).", $perpage
	");
	for ($counter = ($DB_site->num_rows($addbooks) % 2); $addbook = $DB_site->fetch_array($addbooks); $counter++) {
		if ($addbook['name'] != $addbook['email']) {
			$addbook['link'] = urlencode("\"$addbook[name]\" <$addbook[email]>");
		} else {
			$addbook['link'] = urlencode($addbook['email']);
		}
		if ($addbook['userid'] == 0) {
			$isthereglobal = true;
		}
		eval(makeeval('contacts', 'addbook_main_entry', 1));
	}

	// Show contact groups
	$groups = '';
	$groupoptions = $groupoptions_selected = '';
	$contactgroups = $DB_site->query("
		SELECT *
		FROM hive_contactgroup
		WHERE userid = $hiveuser[userid]
		ORDER BY title
	");
	while ($contactgroup = $DB_site->fetch_array($contactgroups)) {
		$class = iif($contactgroupid == $contactgroup['contactgroupid'], 'high', 'normal');
		$contactgroup['total'] = iif(empty($contactgroup['contacts']), 0, count(explode(',', $contactgroup['contacts'])));
		if ($contactgroupid != $contactgroup['contactgroupid']) {
			$groupoptions .= "<option value=\"$contactgroup[contactgroupid]\">$contactgroup[title]</option>\n";
			$groupoptions_selected .= "<option value=\"$contactgroup[contactgroupid]\">$contactgroup[title]</option>\n";
		} else {
			$groupoptions_selected .= "<option value=\"$contactgroup[contactgroupid]\" selected=\"selected\">$contactgroup[title]</option>\n";
		}
		eval(makeeval('groups', 'addbook_main_groupbit', 1));
	}
	$allGroupClass = iif($contactgroupid == 0, 'high', 'normal');

	// Total contacts
	$allcontacts = $DB_site->get_field("
		SELECT COUNT(*) AS count
		FROM hive_contact
		WHERE userid IN (0, $hiveuser[userid])
	");

	// Make page navigation
	if ($totalcontacts == 0) {
		$pagenav = '';
		$limitlower = 0;
	} else {
		$pagenav = getpagenav($totalcontacts, "addressbook.view.php?cmd=$cmd&contactgroupid=$contactgroupid&name=$name&cond=$cond&email=$email&letter=$curletter&perpage=$perpage&sortby=$sortby&sortorder=$sortorder");
	}
	$sortimages = array("$sortby" => '</a>&nbsp;&nbsp;<a href="'."addressbook.view.php?cmd=$cmd&contactgroupid=$contactgroupid&name=$name&cond=$cond&email=$email&letter=$curletter&perpage=$perpage&sortby=$sortby&sortorder=$newsortorder".'"><img src="'.$skin['images'].'/'.$arrow_image.'.gif" align="middle" alt="" border="0" />');

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; Address Book';
	eval(makeeval('echo', 'addbook_main'));
}

// ############################################################################
if ($cmd == 'mini') {
	define('LOAD_MINI_TEMPLATES', true);

	if (isset($pre['list'])) {
		$pre = array('to' => $pre['list']);
		$onelistonly = 1; // not true please!
	} else {
		$onelistonly = 0;
	}

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
				$addbook['email'] = extract_email($contact);
				$$prename .= '<option value="'.$addbook['email'].'">'.$addbook['name'].'</option>';
			} else {
				$addbook['name'] = $addbook['email'] = trim($contact);
				if (!empty($addbook['email'])) {
					$$prename .= '<option value="'.$addbook['email'].'">'.$addbook['name'].'</option>';
				}
			}
		}
	}

	// We only want users who are local to this install, for shared events or other purposes
	if ($local == 1) {
		$appdomains = explode("\n", getop('domainname'));
		if (count($appdomains) > 1) {
			$appdomains = "'".substr(implode("', '", $appdomains), 0, -3);
		} else {
			$appdomains = "'$appdomains[0]'";
		}
		$wheresql = "AND SUBSTRING(email, LOCATE('@', email)) IN ($appdomains)";
	} else {
		$wheresql = '';
	}

	$contacts = '';
	$concache = array();
	$addbooks = $DB_site->query("
		SELECT contactid, email, emailinfo, name
		FROM hive_contact
		WHERE userid IN (0, $hiveuser[userid])
		$wheresql 
		ORDER BY name
	");
	$groupVars = "var group0emails = new Array(".$DB_site->num_rows($addbooks).");\n";
	$groupVars .= "var group0names = new Array(".$DB_site->num_rows($addbooks).");\n";
	$i = 0;
	while ($addbook = $DB_site->fetch_array($addbooks)) {
		$addbook['emailinfo'] = unserialize($addbook['emailinfo']);
		$concache[$addbook['contactid']] = $addbook;
		$groupVars .= "group0emails[$i] = new Array('".addslashes($addbook['email'])."'";
		foreach ($addbook['emailinfo'] as $email) {
			$groupVars .= ", '".addslashes($email)."'";
		}
		$groupVars .= ");\n";
		$groupVars .= "group0names[$i] = '".addslashes($addbook['name'])."';\n";
		$contacts .= '<option value="'.$i.'">'.$addbook['name'].'</option>';
		$i++;
	}

	$groupoptions = '';
	$contactgroups = $DB_site->query("
		SELECT *
		FROM hive_contactgroup
		WHERE userid = $hiveuser[userid]
		ORDER BY title
	");
	while ($contactgroup = $DB_site->fetch_array($contactgroups)) {
		$contactgroup['total'] = iif(empty($contactgroup['contacts']), 0, count($groupcontacts = explode(',', $contactgroup['contacts'])));
		$groupVars .= "var group$contactgroup[contactgroupid]emails = new Array($contactgroup[total]);\n";
		$groupVars .= "var group$contactgroup[contactgroupid]names = new Array($contactgroup[total]);\n";
		if ($contactgroup['total'] > 0) {
			$thisNames = array();
			foreach ($groupcontacts as $contactID) {
				$thisNames[$contactID] = $concache[$contactID]['name'];
			}
			uasort($thisNames, 'strcasecmp');
			$i = 0;
			foreach ($thisNames as $contactID => $thisName) {
				$groupVars .= "group$contactgroup[contactgroupid]emails[$i] = new Array('".addslashes($concache[$contactID]['email'])."'";
				foreach ($concache[$contactID]['emailinfo'] as $email) {
					$groupVars .= ", '".addslashes($email)."'";
				}
				$groupVars .= ");\n";
				$groupVars .= "group$contactgroup[contactgroupid]names[$i] = '".addslashes($thisName)."';\n";
				$i++;
			}
		}
		$groupoptions .= "<option value=\"$contactgroup[contactgroupid]\"".iif($contactgroupid == $contactgroup['contactgroupid'], ' selected="selected"').">$contactgroup[title]</option>\n";
	}

	eval(makeeval('echo', 'addbook_mini'));
}

// ############################################################################
if ($cmd == 'export') {
	define('LOAD_MINI_TEMPLATES', true);

	if (!is_array($contactcheck) or empty($contactcheck)) {
		invalid('contacts');
	}
	$contacts = array();
	foreach ($contactcheck as $contactid => $doit) {
		if ($doit == 'yes') {
			$contacts[] = $contactid;
		}
	}
	$numcontacts = count($contacts);
	$contacts = implode(',', $contacts);

	eval(makeeval('echo', 'addbook_export'));
}

// ############################################################################
if ($_POST['cmd'] == 'doexport') {
	$contacts = explode(',', $contacts);
	$contactids = '';
	foreach ($contacts as $contactid) {
		$contactids .= intval($contactid).', ';
	}
	$contactids = substr($contactids, 0, -2);
	$contacts = $DB_site->query("
		SELECT *
		FROM hive_contact
		WHERE contactid IN ($contactids) AND userid IN (0, $hiveuser[userid])
		ORDER BY contactid
	");

	if ($format == 'vcard') {
		$vCards = array();
		while ($contact = $DB_site->fetch_array($contacts)) {
			$name = preg_replace('#[^a-z0-9]#i', '_', $contact['name']);
			$vCards["$name"] = contact_to_vcard($contact);
		}
		if (count($vCards) == 1) {
			list($name, $vCard) = each($vCards);
			header('Content-disposition: attachment; filename='.$name.'.vcf');
			header('Content-type: unknown/unknown');
			echo $vCard;
		} else {
			$zipdata = $control = array();
			$_offset = 0;

			foreach ($vCards as $name => $vCard) {
				$name .= '.vcf';
				$zdata = gzcompress($vCard);
				$zdata = substr(substr($zdata, 0, strlen($zdata) - 4), 2);

				$zipdata[] = "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00\x00\x00\x00\x00".pack('V', crc32($vCard)).pack('V', strlen($zdata)).pack('V', strlen($vCard)).pack('v', strlen($name)).pack('v', 0).$name.$zdata.pack('V', crc32($vCard)).pack('V', strlen($zdata)).pack('V', strlen($vCard));

				$cdrec = "\x50\x4b\x01\x02\x00\x00\x14\x00\x00\x00\x08\x00\x00\x00\x00\x00".pack('V', crc32($vCard)).pack('V', strlen($zdata)).pack('V', strlen($vCard)).pack('v', strlen($name)).pack('v', 0).pack('v', 0).pack('v', 0).pack('v', 0).pack('V', 32).pack('V', $_offset).$name;
				$_offset = strlen(implode('', $zipdata));
				$control[] = $cdrec;
			}
			
			$zipdata_i = implode('', $zipdata);
			$control_i = implode('', $control);
			$zipfile = $zipdata_i.$control_i."\x50\x4b\x05\x06\x00\x00\x00\x00".pack('v', sizeof($control)).pack('v', sizeof($control)).pack('V', strlen($control_i)).pack('V', strlen($zipdata_i))."\x00\x00";
			header('Content-disposition: attachment; filename=address_book_'.date('m_d_Y').'.zip');
			header('Content-type: application/zip');
			header('Content-Length: '.strlen($zipfile));
			echo $zipfile;
		}
	} else {
		header('Content-disposition: attachment; filename=address_book_'.date('m_d_Y').'.csv');
		header('Content-type: unknown/unknown');
		$exportdata = '"Name","E-mail Address"';

		while ($contact = $DB_site->fetch_array($contacts)) {
			$exportdata .= CRLF.'"'.str_replace('"', '""', unhtmlchars($contact['name'])).'","'.str_replace('"', '""', $contact['email']).'"';
		}
		echo $exportdata;
	}
}

?>