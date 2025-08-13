<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: addressbook.add.php,v $
// | $Date: 2002/10/28 18:17:29 $
// | $Revision: 1.11 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = ',redirect_addbook_addentries,redirect_addbook_addentries,redirect_addbook_quickadd';
require_once('./global.php');

// ############################################################################
if ($do == 'quick') {
	$info = getinfo('message', $messageid);

	// Make sure the contact isn't already in his address book
	if (!$DB_site->query_first("SELECT contactid FROM contact WHERE userid = $hiveuser[userid] AND email = '".addslashes(trim($info['email']))."'")) {
		$DB_site->query("
			INSERT INTO contact (contactid, userid, email, name) VALUES
			(NULL, $hiveuser[userid], '".addslashes(trim(str_replace(';', ' ', $info['email'])))."', '".addslashes(trim(str_replace(';', ' ', $info['name'])))."')
		");
	}

	if (!is_numeric($return)) {
		eval(makeredirect("redirect_addbook_quickadd", "read.email.php?messageid=$messageid"));
	} else {
		eval(makeredirect("redirect_addbook_quickadd", "index.php?folderid=$return"));
	}
}

// ############################################################################
if ($_POST['do'] == 'insert') {
	// Make sure the contact isn't already in his address book
	if (!$DB_site->query_first("SELECT contactid FROM contact WHERE userid = $hiveuser[userid] AND email = '".addslashes(trim($email))."'")) {
		$DB_site->query("
			INSERT INTO contact (contactid, userid, email, name) VALUES
			(NULL, $hiveuser[userid], '".addslashes(htmlspecialchars(str_replace(';', ' ', $email)))."', '".addslashes(htmlspecialchars(str_replace(';', ' ', $name)))."')
		");
	}

	eval(makeredirect("redirect_addbook_addentries", "addressbook.view.php"));
}

// ############################################################################
if ($_POST['do'] == 'upload') {
	$attachment_name = strtolower($attachment_name);
	$extension = getextension($attachment_name);

	if (is_uploaded_file($attachment)) {
		if ($safeupload) {
			$path = $tmppath.'/'.$attachment_name;
			move_uploaded_file($attachment, $path);
			$attachment = $path;
		}
	
		$filesize = filesize($attachment);
		if ($filesize == $attachment_size and strstr($attachment, '..') == '') {
			$filenum = fopen($attachment, 'rb');
			$row = 0;
			while ($data = fgetcsv ($filenum, 1000, ',')) {
				if ($row == 0) {
					$namefield  = array_search('Name', $data);
					$emailfield = array_search('E-mail Address', $data);
				} else {
					$instervalues .= ",(NULL, $hiveuser[userid], '".addslashes(htmlspecialchars(str_replace(';', ' ', $data["$emailfield"])))."', '".addslashes(htmlspecialchars(str_replace(';', ' ', $data["$namefield"])))."')";
				}
				$row++;
			}
			fclose($filenum);
			unlink($attachment);

			$DB_site->query('
				INSERT INTO contact (contactid, userid, email, name) VALUES
				'.substr($instervalues, 1).'
			');
		} else {
			eval(makeerror('error_attacherror'));
		}
	} else {
		eval(makeerror('error_attacherror'));
	}
	
	eval(makeredirect("redirect_addbook_addentries", "addressbook.view.php"));
}

?>