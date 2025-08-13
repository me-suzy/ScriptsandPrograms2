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
// | $RCSfile: sound.php,v $ - $Revision: 1.11 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; Sound Files');

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');
if ($cmd == 'add') {
	$cmd = 'edit';
	cp_nav('soundadd');
} else {
	cp_nav('soundmodify');
}

// ############################################################################
// Remove sound file
if ($_POST['cmd'] == 'kill') {
	$sound = getinfo('sound', $soundid, false, true, false);

	$DB_site->query("
		DELETE FROM hive_sound
		WHERE soundid = $soundid
	");
	$DB_site->query("
		UPDATE hive_user
		SET soundid = $newsoundid
		WHERE soundid = $soundid
	");

	adminlog($soundid, true, 'kill', "Users will now be using sound $newsoundid");
	cp_redirect('The file has been removed.', 'sound.php');
}

// ############################################################################
// Remove sound file
if ($cmd == 'remove') {
	$sound = getinfo('sound', $soundid, false, true, false);
	if ($sound['userid'] == -1) {
		adminlog($soundid, false);
		cp_error('You cannot remove the default file.');
	}

	adminlog($soundid);
	startform('sound.php', 'kill', 'Are you sure you want to remove this file?');
	starttable('Remove sound file "'.$sound['title'].'" (ID: '.$soundid.')');
	textrow('Are you <b>sure</b> you want to remove this file? This procedure <b>cannot</b> be reveresed!');
	tableselect('New sound for users that are using this file:', 'newsoundid', 'sound', -1, "userid <= 0 AND soundid <> $soundid");
	hiddenfield('soundid', $soundid);
	endform('Remove file', '', 'Go Back');
	endtable();
}

// ############################################################################
// Create a new sound or update an existing one
if ($_POST['cmd'] == 'update') {
	if (empty($sound['title'])) {
		adminlog($soundid, false);
		cp_error('The file must have a title.');
	} else {
		if (upload_file($filename, $filedata)) {
			$sound['data'] = $filedata;
			$sound['filename'] = $filename;
		} elseif ($soundid == 0) {
			adminlog($soundid, false);
			cp_error('You must upload a file.');
		} else {
			unset($sound['data']);
			$sound['filename'] = $oldfilename;
		}

		if ($deffile) {
			$sound['userid'] = -1;
			$DB_site->query('
				UPDATE hive_sound
				SET userid = 0
				WHERE userid = -1
			');
		} else {
			$olduserid = $sound['userid'];
			$sound['userid'] = 0;

			// We must have at least one default file
			if ($olduserid == -1 and !$DB_site->query_first("SELECT soundid FROM hive_sound WHERE userid = -1 AND soundid <> $soundid")) {
				$DB_site->query("
					UPDATE hive_sound
					SET userid = -1
					WHERE userid = 0 AND soundid <> $soundid
					LIMIT 1
				");
				// In case we found no other file, too bad
				if (!$DB_site->query_first("SELECT soundid FROM hive_sound WHERE userid = -1 AND soundid <> $soundid")) {
					$sound['userid'] = -1;
				}
			}
		}

		if ($soundid == 0) {
			$DB_site->auto_query('sound', $sound);
			adminlog($soundid, true);
			cp_redirect('The file has been created.', 'sound.php');
		} else {
			$DB_site->auto_query('sound', $sound, "soundid = $soundid");
			adminlog($soundid, true);
			cp_redirect('The file has been updated.', 'sound.php');
		}
	}
}

// ############################################################################
// Create a new sound or update an existing one
if ($cmd == 'edit') {
	startform('sound.php', 'update', '', array('sound_title' => 'title'), true);

	$sound = getinfo('sound', $soundid, false, false, false);
	adminlog($soundid);
	if ($sound === false) {
		$sound = array(
			'userid' => 0,
		);
		$soundid = 0;
		starttable('Create new sound file');
	} else {
		starttable('Update sound file "'.$sound['title'].'" (ID: '.$soundid.')');
	}

	hiddenfield('soundid', $soundid);
	hiddenfield('sound[userid]', $sound['userid']);
	hiddenfield('oldfilename', $sound['filename']);
	inputfield('Title:', 'sound[title]', $sound['title']);
	filefield('Upload file:<br /><span class="cp_small">Leave empty to keep current file.<br />Click Browse and select the sound file from your computer. When you are done, click Open.</span>', 'file');
	if ($sound['userid'] != -1) {
		yesno('Make default file:<br /><span class="cp_small">Would you like this file to be the default one users hear unless they change it?</span>', 'deffile', false);
	} else {
		hiddenfield('deffile', '1');
	}

	if ($soundid == 0) {
		endform('Create sound file');
	} else {
		endform('Update sound file');
	}
	endtable();
}

// ############################################################################
// List the sounds
if ($cmd == 'modify') {
	adminlog();

	starttable('', '450');
	$cells = array(
		'ID',
		'Title',
		'File Name',
		'Used By',
		'Options'
	);
	tablehead($cells);
	$sounds = $DB_site->query('
		SELECT sound.*, COUNT(user.userid) AS users
		FROM hive_sound AS sound
		LEFT JOIN hive_user AS user USING (soundid)
		WHERE sound.userid <= 0
		GROUP BY sound.soundid
	');
	if ($DB_site->num_rows($sounds) < 1) {
		textrow('No files', count($cells), 1);
	} else {
		while ($sound = $DB_site->fetch_array($sounds)) {
			$cells = array(
				$sound['soundid'],
				"<a href=\"sound.php?cmd=edit&soundid=$sound[soundid]\">".iif($sound['userid'] == -1, "<b>$sound[title]</b>", $sound['title']).'</a>',
				"<a href=\"../user.sound.php?soundid=$sound[soundid]\" target=\"_blank\">$sound[filename]</a>",
				$sound['users'].' user'.iif($sound['users'] != 1, 's'),
				makelink('edit', "sound.php?cmd=edit&soundid=$sound[soundid]") . '-' . makelink('remove', "sound.php?cmd=remove&soundid=$sound[soundid]"),
			);
			tablerow($cells);
		}
	}
	emptyrow(count($cells));
	endtable();

	startform('sound.php', 'update', '', array('sound_title' => 'title'), true);
	starttable('Create new sound file', '450');
	hiddenfield('soundid', '0');
	inputfield('Title:', 'sound[title]', '');
	filefield('Upload file:<br /><span class="cp_small">Click Browse and select the sound file from your computer. When you are done, click Open.</span>', 'file');
	endform('Create sound file');
	endtable();
}

cp_footer();
?>