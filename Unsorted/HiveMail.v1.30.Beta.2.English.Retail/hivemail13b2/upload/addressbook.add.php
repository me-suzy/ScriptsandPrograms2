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
// | $RCSfile: addressbook.add.php,v $ - $Revision: 1.37 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'addbook_add,addbook_add_contactbit';
require_once('./global.php');
require_once('./includes/functions_pop.php');
require_once('./includes/functions_mime.php');
require_once('./includes/functions_addbook.php');
require_once('./includes/functions_zip.php');

// ############################################################################
$current = array();
$_contacts = $DB_site->query("
	SELECT email, emailinfo
	FROM hive_contact
	WHERE userid IN (0, $hiveuser[userid])
");
$numcontacts = 0;
while ($_contact = $DB_site->fetch_array($_contacts)) {
	$current[] = $_contact['email'];
	$current = array_merge($current, unserialize($_contact['emailinfo']));
	if ($_contact['userid'] != 0) {
		$numcontacts++;
	}
}
$current = array_unique($current);

// ############################################################################
if ($hiveuser['maxcontacts'] > 0 and $numcontacts > $hiveuser['maxcontacts']) {
	eval(makeerror('error_contacts_toomany'));
}

// ############################################################################
/*
if ($cmd == 'massadd') {
	if (isset($msgids)) {
		$msgids = explode(',', $msgids);
		foreach ($msgids as $key => $msgid) {
			intme($msgids[$key]);
		}
		$msgids = implode(',', $msgids);
		$messages = $DB_site->query("
			SELECT email, name
			FROM hive_message
			WHERE userid = $hiveuser[userid] AND messageid IN ($msgids)
		");
		$contacts = array();
		while ($message = $DB_site->fetch_array($messages)) {
			$contacts["$message[name]"] = $message['email'];
		}
	} else {
		$contacts = unserialize($contacts);
	}
	if (!is_array($contacts) or empty($contacts)) {
		// Redirect to index.php (or selected)
	}

	$groupoptions = '';
	$contactgroups = $DB_site->query("
		SELECT *
		FROM hive_contactgroup
		WHERE userid = $hiveuser[userid]
		ORDER BY title
	");
	while ($contactgroup = $DB_site->fetch_array($contactgroups)) {
		$groupoptions .= "<option value=\"$contactgroup[contactgroupid]\">$contactgroup[title]</option>\n";
	}

	$addcontacts = '';
	$i = 0;
	foreach ($contacts as $name => $email) {
		if (array_contains($email, $current)) {
			continue;
		}
		if (!is_string($name)) {
			$name = '';
		}
		eval(makeeval('addcontacts', 'addbook_add_contactbit', true));
		$i++;
	}
	if (empty($addcontacts)) {
		// Redirect to index.php (or selected)
	}

	// Show those that couldn't be added
	eval(makeeval('echo', 'addbook_add'));
}

// ############################################################################
if ($_POST['cmd'] == 'massinsert') {
	$toomany = false;
	$insertvalues = array();
	foreach ($emails as $i => $email) {
		$name = $names[$i];
		$contactgroupid = $contactgroupids[$i];
		$name = iif(empty($name), $email, $name);
		if ($hiveuser['maxcontacts'] > 0 and $numcontacts > $hiveuser['maxcontacts']) {
			$toomany = true;
			break;
		}
		if (array_contains($email, $current)) {
			continue;
		}
		$insertvalues[] = "(NULL, $hiveuser[userid], '".addslashes($email)."', '".addslashes(htmlchars(str_replace(';', ' ', $name)))."', -13, 'a:0:{}', 'a:0:{}', 'a:0:{}', 'a:0:{}')";
		$numcontacts++;
	}

	if (!empty($insertvalues)) {
		$DB_site->query('
			INSERT INTO hive_contact (contactid, userid, email, name, timezone, emailinfo, nameinfo, addressinfo, phoneinfo) VALUES
			'.implode(', ', $insertvalues).'
		');
		if ($toomany) {
			eval(makeerror('error_contacts_toomany'));
		}
	}
}
*/

// ############################################################################
if ($cmd == 'quick') {
	if (isset($popid)) {
		$info = pop_decodemail($popid, $msgid);
	} else {
		$info = getinfo('message', $messageid);
	}
	$info['email'] = extract_email($info['email']);

	// Make sure the contact isn't already in his address book
	if (!array_contains($info['email'], $current)) {
		$DB_site->query("
			INSERT INTO hive_contact
			(contactid, userid, email, name, timezone, emailinfo, nameinfo, addressinfo, phoneinfo)
			VALUES
			(NULL, $hiveuser[userid], '".addslashes($info['email'])."', '".addslashes(htmlchars(str_replace(';', ' ', $info['name'])))."', -13, 'a:0:{}', 'a:0:{}', 'a:0:{}', 'a:0:{}')
		");
	}

	if (!is_numeric($return)) {
		eval(makeredirect("redirect_addbook_quickadd", "read.email.php?messageid=$messageid"));
	} else {
		eval(makeredirect("redirect_addbook_quickadd", INDEX_FILE."?folderid=$return"));
	}
}

// ############################################################################
if ($_POST['cmd'] == 'insert') {
	$email = extract_email($email);
	if (empty($name) or !is_email($email)) {
		eval(makeerror('error_invalidcontact'));
	}

	// Make sure the contact isn't already in his address book
	$contactid = 0;
	if (!array_contains($email, $current)) {
		$DB_site->query("
			INSERT INTO hive_contact (contactid, userid, email, name, timezone, emailinfo, nameinfo, addressinfo, phoneinfo) VALUES
			(NULL, $hiveuser[userid], '".addslashes($email)."', '".addslashes(htmlchars(str_replace(';', ' ', $name)))."', -13, 'a:0:{}', 'a:0:{}', 'a:0:{}', 'a:0:{}')
		");
		$contactid = $DB_site->insert_id();
		$contactgroup = getinfo('contactgroup', $contactgroupid, false, false);
		if ($contactgroup) {
			$grouplink = "?contactgroupid=$contactgroup[contactgroupid]";
			$contacts = explode(',', $contactgroup['contacts']);
			$contacts[] = $DB_site->insert_id();
			$contacts = array_filter(array_unique($contacts), 'strlen');
			$DB_site->query("
				UPDATE hive_contactgroup
				SET contacts = '".addslashes(implode(',', $contacts))."'
				WHERE contactgroupid = $contactgroup[contactgroupid]
			");
		} else {
			$grouplink = '';
		}
	}

	if ($gotoedit) {
		if ($contactid != 0) {
			?><script language="JavaScript" type="text/javascript">
			<!--
			window.opener.location.reload();
			window.location = 'addressbook.update.php?cmd=edit&contactid=<?php echo $contactid; ?>';
			//-->
			</script><?php
		} else {
			?><script language="JavaScript" type="text/javascript">
			<!--
			window.close();
			//-->
			</script><?php
		}			
		exit;
	} else {
		eval(makeredirect("redirect_addbook_addentries", "addressbook.view.php$grouplink"));
	}
}

// ############################################################################
if ($_POST['cmd'] == 'upload') {
	$filename = strtolower($_FILES['attachment']['name']);
	$extension = getextension($filename);
	$insertvalues = $vcards = array();
	$toomany = false;

	if (is_uploaded_file($_FILES['attachment']['tmp_name'])) {
		if (getop('safeupload')) {
			$path = getop('tmppath', true).'/'.$filename;
			move_uploaded_file($_FILES['attachment']['tmp_name'], $path);
			$_FILES['attachment']['tmp_name'] = $path;
		}
	
		$filesize = filesize($_FILES['attachment']['tmp_name']);
		if ($filesize == $_FILES['attachment']['size'] and strstr($_FILES['attachment']['tmp_name'], '..') == '') {
			// Read vCard file
			if ($format == 'vcard') {
				$vcards = array($_FILES['attachment']['name'] => readfromfile($_FILES['attachment']['tmp_name']));
			}

			// Read ZIP file
			if ($format == 'vzip') {
				$vcards = zip_getfiles($_FILES['attachment']['tmp_name'], 'vcf');
				$format = 'vcard';
			}

			// Process vCard file(s)
			if ($format == 'vcard') {
				// Failed?
				if (!is_array($vcards) or empty($vcards)) {
					eval(makeerror('error_cvsfail'));
				}

				// Too many new contacts?
				if ($hiveuser['maxcontacts'] > 0 and $numcontacts + count($vcards) > $hiveuser['maxcontacts']) {
					$toomany = true;
					$vcards = array_slice($vcards, 0, $hiveuser['maxcontacts'] - $numcontacts);
				}

				// Parse vCards
				foreach ($vcards as $vcard) {
					$contact = vcard_to_contact($vcard);
					$insertvalues[] = "(NULL, $hiveuser[userid], '".addslashes($contact['email'])."', '".addslashes(serialize($contact['emailinfo']))."', '".addslashes($contact['name'])."', '".addslashes(serialize($contact['nameinfo']))."', '".addslashes($contact['birthday'])."', ".floatme($contact['timezone']).", '".addslashes($contact['webpage'])."', '".addslashes($contact['notes'])."', '".addslashes(serialize($contact['addressinfo']))."', '".addslashes(serialize($contact['phoneinfo']))."')";
				}
			}

			// Process CSV file
			if ($format == 'csv') {
				$fp = fopen($_FILES['attachment']['tmp_name'], 'r');
				$row = 0;
				unset($namefield, $namefield1, $namefield2, $emailfield);
				$firstline = trim(fread($fp, 512));
				$delimiter = ',';
				if (preg_match('#([,;])#', $firstline, $delimiters)) {
					$delimiter = $delimiters[1];
				}
				rewind($fp);
				while ($data = fgetcsv($fp, 1028, $delimiter)) {
					if ($hiveuser['maxcontacts'] > 0 and $numcontacts > $hiveuser['maxcontacts']) {
						$toomany = true;
						break;
					}

					if ($row < 1) {
						foreach ($data as $key => $value) {
							if (!isset($namefield) and preg_match('/name/i', $value)) {
								$namefield = $key;
							}
							if (!isset($namefield1) and preg_match('/first/i', $value)) {
								$namefield1 = $key;
							}
							if (!isset($namefield2) and preg_match('/last/i', $value)) {
								$namefield2 = $key;
							}
							if (!isset($emailfield) and preg_match('/e[-]?mail/i', $value)) {
								$emailfield = $key;
							}
						}
					}
					if (!isset($emailfield) and $email = extract_email(implode(' ',$data))) {
						if (array_contains($email, $current)) {
							continue;
						}
						$name = addslashes(htmlchars(str_replace(';', ' ', $email)));
						$insertvalues[] = "(NULL, $hiveuser[userid], '".addslashes($email)."', 'a:0:{}', '$name', 'a:0:{}', '0000-00-00', -13, '', '', 'a:0:{}', 'a:0:{}')";
					} elseif ($row > 0 and $email = extract_email($data[$emailfield])) {
						if (array_contains($email, $current)) {
							continue;
						}
						if (isset($namefield1) or isset($namefield2)) {
							$name = trim($data[$namefield1].' '.$data[$namefield2]);
						} else {
							$name = trim($data[$namefield]);
						}
						$name = iif(empty($name), $email, $name);
						$insertvalues[] = "(NULL, $hiveuser[userid], '".addslashes($email)."', 'a:0:{}', '".addslashes(htmlchars(str_replace(';', ' ', $name)))."', 'a:0:{}', '0000-00-00', -13, '', '', 'a:0:{}', 'a:0:{}')";
					}
					$row++;
					$numcontacts++;
				}
				fclose($fp);
			}

			// Delete attached file and insert values into database
			unlink($_FILES['attachment']['tmp_name']);
			if (!empty($insertvalues)) {
				$DB_site->query('
					INSERT INTO hive_contact (contactid, userid, email, emailinfo, name, nameinfo, birthday, timezone, webpage, notes, addressinfo, phoneinfo) VALUES
					'.implode(', ', $insertvalues).'
				');
				if ($toomany) {
					eval(makeerror('error_contacts_toomany'));
				}
			} else {
				eval(makeerror('error_cvsfail'));
			}
		} else {
			eval(makeerror('error_cvsfail'));
		}
	} else {
		eval(makeerror('error_cvsfail'));
	}
	
	eval(makeredirect("redirect_addbook_addentries", "addressbook.view.php"));
}

?>