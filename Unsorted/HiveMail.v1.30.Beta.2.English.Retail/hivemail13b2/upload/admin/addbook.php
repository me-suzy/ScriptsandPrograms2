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
// | $RCSfile: addbook.php,v $ - $Revision: 1.4 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; Global Address Book', true, true, '<script type="text/javascript" src="../misc/checkall.js"></script>');
cp_nav('userbook');

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');

// ############################################################################
// Remove contacts
if ($_POST['cmd'] == 'add') {
	extract($newcontact);
	if (!is_email($email)) {
		adminlog(0, false);
		cp_error('You must provied a valid email address.');
	} elseif (empty($name)) {
		$name = $email;
	}

	// Create contact
	$DB_site->query('
		INSERT INTO hive_contact
		SET contactid = NULL, email = "'.addslashes($email).'", name = "'.addslashes($name).'", userid = 0, timezone = -13, emailinfo = "a:0:{}", nameinfo = "a:0:{}", addressinfo = "a:0:{}", phoneinfo = "a:0:{}"
	');

	adminlog($DB_site->insert_id(), true);
	cp_redirect('The contact has been added to the address book.', 'addbook.php');
}

// ############################################################################
// Remove contacts
if ($_POST['cmd'] == 'remove') {
	$deleteids = array();
	foreach ($contact_delete as $contactid => $doit) {
		if ($doit == 'yes') {
			$deleteids[] = intval($contactid);
		}
	}

	// Delete contacts
	$DB_site->query('
		DELETE FROM hive_contact
		WHERE contactid IN ('.implode(', ', $deleteids).') AND userid = 0
	');

	adminlog(0, true, 'remove', 'Deleted contacts: '.implode(', ', $deleteids));
	cp_redirect('The selected contacts were removed from the address book.', 'addbook.php');
}

// ############################################################################
// Update or delete contacts
if ($_POST['cmd'] == 'update') {
	foreach ($contact_emails as $contactid => $email) {
		$name = $contact_names[$contactid];

		if (empty($name)) {
			$name = $email;
		}

		// Delete or update
		if (empty($email)) {
			$DB_site->query('
				DELETE FROM hive_contact
				WHERE contactid = '.intval($contactid).' AND userid = 0
			');
		} elseif (is_email($email)) {
			$DB_site->query('
				UPDATE hive_contact
				SET email = "'.addslashes($email).'", name = "'.addslashes($name).'"
				WHERE contactid = '.intval($contactid).' AND userid = 0
			');
		}
	}

	adminlog(0, true);
	cp_redirect('The address book has been saved.', 'addbook.php');
}

// ############################################################################
// Show address book
if ($cmd == 'modify') {
	adminlog();

	startform('addbook.php', 'update');
	starttable('', '500');
	$cells = array(
		'Name',
		'Email',
		'<input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form);" />',
	);
	tablehead($cells);
	$contacts = $DB_site->query('
		SELECT *
		FROM hive_contact
		WHERE userid = 0
	');
	if ($DB_site->num_rows($contacts) < 1) {
		textrow('No contacts', count($cells), 1);
	} else {
		while ($contact = $DB_site->fetch_array($contacts)) {
			$cells = array(
				"<input type=\"text\" class=\"invsibginput_secondalt\" onFocus=\"this.className = 'bginput';\" onBlur=\"this.className = 'invsibginput_secondalt';\" name=\"contact_names[$contact[contactid]]\" value=\"".htmlchars($contact['name'])."\" size=\"35\" />",
				"<input type=\"text\" class=\"invsibginput_firstalt\" onFocus=\"this.className = 'bginput';\" onBlur=\"this.className = 'invsibginput_firstalt';\" name=\"contact_emails[$contact[contactid]]\" value=\"".htmlchars($contact['email'])."\" size=\"35\" />",
				'center1' => "<input type=\"checkbox\" name=\"contact_delete[$contact[contactid]]\" value=\"yes\" onClick=\"checkMain(this.form);\" />",
			);
			tablerow($cells);
		}
		tablehead(array('<div align="center"><input type="submit" value="  Update contacts  " class="button" />&nbsp;&nbsp;<input type="submit" value="  Delete selected  " class="button" onClick="this.form.cmd.value = \'remove\'; if (confirm(\'Are you sure you want to remove all selected contacts?\')) { this.form.cmd.value = \'remove\'; return true; } else { return false; }" /></div>'), 3);
	}
	endtable();
	endform();

	startform('addbook.php', 'add', '', array('newcontact_name' => 'name', 'newcontact_email' => 'email'));
	starttable('Create new address book entry', '500');
	inputfield('Full name:', 'newcontact[name]');
	inputfield('Email addres:', 'newcontact[email]');
	endform('Create contact');
	endtable();

	starttable('Help', '500');
	textrow('The <b>global address book</b> in HiveMail&trade; can be used to fill your users\' address books with predefined email addresses that are worth knowing.<br />For example, the webmaster email address for support or feedback about the website. All contacts that appear on this page will be shown to <i>all</i> users, regardless of what user-group they are part of or when they registered.<br /><br />To create a new contact, enter their full name and email address in the table above and press Create Contact button. To modify an existing contact, click on their name or email address in the top table and change the information and press the Update Contacts button to submit your changes when you are done. To delete a contact, check the box besides their email address and press the Delete Selected button.');
	endtable();
}

cp_footer();
?>