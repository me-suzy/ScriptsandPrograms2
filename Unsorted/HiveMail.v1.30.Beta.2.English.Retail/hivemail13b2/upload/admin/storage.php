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
// | $RCSfile: storage.php,v $ - $Revision: 1.18 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; Message Storage');
cp_nav('emailstorage');
@set_time_limit(0);

// ############################################################################
// Function to get all files, recursively, in a directory
function scandir_recursive($base, $directory = '') {
	if (!($dh = opendir("$base/$directory"))) {
		return false;
	}

	$files = array();
	while (false !== ($filename = readdir($dh))) {
		if ($filename != '.' and $filename != '..') {
			if (is_dir("$base/$directory/$filename")) {
				$files = array_merge($files, scandir_recursive($base, "$directory/$filename"));
			} else {
				$files[] = trim(iif(!empty($directory), "$directory/").$filename, '/\\');
			}
		}
	}
	closedir($dh);

	return $files;
}

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');

// ############################################################################
// Update options
if ($cmd == 'switch') {
	// Add temp field if we're beginning and shut down system
	if ($beginning) {
		$DB_site->showerror = false;
		$DB_site->query('
			ALTER TABLE hive_message
			ADD source2 LONGTEXT NOT NULL DEFAULT ""
		');
		$DB_site->showerror = true;
		$DB_site->query('
			UPDATE hive_setting
			SET value = 1
			WHERE variable = "maintain"
		');
	}

	$options = unserialize(urldecode($options));

	// Pagination stuff
    if (empty($perpage)) {
        $perpage = 100;
    } else {
        intme($perpage);
    }
    if (empty($startat)) {
        $startat = intval($DB_site->get_field('SELECT MIN(messageid) AS min FROM hive_message')) - 1;
    } else {
        intme($startat);
    }
    $finishat = $startat + $perpage;
	$lastid = 0;

	// Move messages
	$messages = $DB_site->query("
		SELECT *
		FROM hive_message
		WHERE messageid > $startat
		ORDER BY messageid ASC LIMIT $perpage
	");
	$filenames = array();
	echo '<p align="left" style="padding-left: 20px;">';
	while ($message = $DB_site->fetch_array($messages)) {
		echo "Processing message ID: $message[messageid] ... ";
		flush();
		if ($options['flat_use']) {
			$dirname = get_dirname(true);
			$filename = make_filename($dirname, true);
			$filenames[] = $dirname.'/'.$filename;
			$filepath = $options['flat_path'].'/'.$dirname.'/'.getop('flat_prefix').$filename.'.dat';
			if ($dirname != getop('flat_curfolder') or !is_dir($options['flat_path'].'/'.$dirname)) {
				mkdir($options['flat_path'].'/'.$dirname, 0777);
				chmod($options['flat_path'].'/'.$dirname, 0777);
				clearstatcache();
				$_options['flat_curcount'] = 0;
			}
			if (!writetofile($filepath, $message['source'])) {
				cp_error('Could not write to file '.$filepath.' (message ID: '.$message['messageid'].')');
			}
			chmod($filepath, 0777);
			$_options['flat_curfolder'] = $dirname;
			$_options['flat_curcount']++;
			$DB_site->query("
				UPDATE hive_message
				SET source2 = '".addslashes($dirname.'/'.$filename)."'
				WHERE messageid = $message[messageid]
			");
		} else {
			if (strpos($message['source'], '/') === false) {
				$filepath = getop('flat_path', true).'/'.getop('flat_prefix').$message['source'].'.dat';
			} else {
				$filepath = getop('flat_path', true).'/'.str_replace('/', '/'.getop('flat_prefix'), $message['source']).'.dat';
			}
			if (($filedata = readfromfile($filepath)) === false) {
				cp_error('Could not read file '.$filepath.' (message ID: '.$message['messageid'].')');
			}
			$DB_site->query("
				UPDATE hive_message
				SET source2 = '".$DB_site->escape($filedata)."'
				WHERE messageid = $message[messageid]
			");
		}
		$lastid = $message['messageid'];
		echo '<b><span class="cp_temp_orig">OK</span></b><br />'."\n";
		flush();
	}
	echo '</p>';

	if ($options['flat_use']) {
		$DB_site->query('
			UPDATE hive_setting
			SET value = "'.addslashes($_options['flat_curfolder']).'"
			WHERE variable = "flat_curfolder"
		');
		$DB_site->query("
			UPDATE hive_setting
			SET value = $_options[flat_curcount]
			WHERE variable = 'flat_curcount'
		");
	}

	// Add records to messagefile
	if ($options['flat_use'] and !empty($filenames)) {
		$DB_site->query('
			INSERT INTO hive_messagefile (filename, messages)
			VALUES ("'.implode('", 1), ("', $filenames).'", 1)
		');
	}

	// Check if there are more messages to be processed
	if ($DB_site->query_first("SELECT messageid FROM hive_message WHERE messageid > $lastid LIMIT 1")) {
		$options = urlencode(serialize($options));
		cp_redirect('Processing next batch of messages...', "storage.php?cmd=switch&startat=$lastid&perpage=$perpage&options=$options");
	} else {
		// All done!
		$DB_site->query('
			UPDATE hive_setting
			SET value = 0
			WHERE variable = "maintain"
		');

		// Switch fields
		$DB_site->query('
			ALTER TABLE hive_message
			DROP source
		');
		$DB_site->query('
			ALTER TABLE hive_message
			CHANGE source2 source LONGTEXT NOT NULL DEFAULT ""
		');
		$DB_site->query('
			OPTIMIZE TABLE hive_message
		');

		// Delete obsolete files
		if (!$options['flat_use']) {
			$filenames = $DB_site->query('
				SELECT filename
				FROM hive_messagefile
			');
			while ($filename = $DB_site->fetch_array($filenames)) {
				if (strpos($filename['filename'], '/') === false) {
					$filepath = getop('flat_path', true).'/'.getop('flat_prefix').$filename['filename'].'.dat';
				} else {
					$filepath = getop('flat_path', true).'/'.str_replace('/', '/'.getop('flat_prefix'), $filename['filename']).'.dat';
				}
				if (file_exists($filepath)) {
					unlink($filepath);
				}
			}

			// Remove folders
			$DB_site->reset($filenames);
			while ($filename = $DB_site->fetch_array($filenames)) {
				if (strpos($filename['filename'], '/') === false) {
					continue;
				} else {
					$dirname = substr($filename['filename'], 0, 32);
					if (is_dir(getop('flat_path', true).'/'.$dirname)) {
						rmdir(getop('flat_path', true).'/'.$dirname);
						clearstatcache();
					}
				}
			}

			// Update database
			$DB_site->query('
				DELETE FROM hive_messagefile
			');
			$DB_site->query('
				UPDATE hive_setting
				SET value = ""
				WHERE variable = "flat_curfolder"
			');
			$DB_site->query('
				UPDATE hive_setting
				SET value = 0
				WHERE variable = "flat_curcount"
			');
		}

		$options = urlencode(serialize($options));
		$_POST['cmd'] = 'final';
	}
}

// ############################################################################
// Update options
if ($_POST['cmd'] == 'confirm') {
	// Fix path
	while (in_array(substr($options['flat_path'], -1), array('/', '\\'))) {
		$options['flat_path'] = substr($options['flat_path'], 0, -1);
	}

	if ($options['flat_use'] == getop('flat_use')) {
		$options = urlencode(serialize($options));
		$_POST['cmd'] = 'final';
	} else {
		$options = urlencode(serialize($options));
		startform('storage.php', 'switch', 'Are you sure you want to change the storage method?');
		if ($options['flat_use']) {
			starttable('Store messages in <b>file-system</b>');
			inputfield('Messages to process per cycle:', 'perpage', 100);
			textrow('We will now move all messages out of the database and store them in files. It is possible that you do not have enough free disk space to do this, but unfortunately we cannot tell if you do beforehand.
			<br />No data will be deleted from the database until this procedure is completed, however we still <b><span class="cp_temp_cust">STRONGLY RECOMMEND</span></b> that you backup the database before proceeding. If anything goes wrong we will <b><span class="cp_temp_cust">NOT</span></b> be able to provide support if you have not backed up the database before proceeding.<br />
			During this process the system will automatically close itself for maintenance, making it unavailable to your users.');
		} else {
			starttable('Store messages in <b>the database</b>');
			inputfield('Messages to process per cycle:', 'perpage', 100);
			textrow('We will now move all messages out of the file-system and store them in the database. It is possible that you do not have enough disk space to do this, but unfortunately we cannot tell if you do beforehand.
			<br />No data will be deleted from the file-system until this procedure is completed, however we still <b><span class="cp_temp_cust">STRONGLY RECOMMEND</span></b> that you backup the database before proceeding. If anything goes wrong we will <b><span class="cp_temp_cust">NOT</span></b> be able to provide support if you have not backed up the database before proceeding.<br />
			During this process the system will automatically close itself for maintenance, making it unavailable to your users.');
		}
		hiddenfield('options', $options);
		hiddenfield('beginning', '1');
		endform('Change Storage Method', '', 'Go Back');
		endtable();
	}
}

// ############################################################################
// Update options
if ($_POST['cmd'] == 'final') {
	$options = unserialize(urldecode($options));
	foreach ($options as $varname => $value) {
		$DB_site->query("
			UPDATE hive_setting
			SET value = '".addslashes($value)."'
			WHERE variable = '".addslashes($varname)."'
		");
		if ($value != getop($varname)) {
			$updated_options .= ",$varname";
		}
	}

	adminlog(0, true, 'update', 'Updated storage settings: '.substr($updated_options, 1));
	cp_redirect('The settings were successfully updated.', 'storage.php');
}

// ############################################################################
// Update options
// Too stressful on the server right now
/*
if ($cmd == 'clean') {
	$flat_path = getop('flat_path', true);
	$allfiles = scandir_recursive('../../');
	$messages = $DB_site->query("
		SELECT *
		FROM hive_message
	");
	$allmsgs = array();
	while ($message = $DB_site->fetch_array($messages)) {
		if (strpos($message['source'], '/') === false) {
			$filepath = getop('flat_prefix').$message['source'].'.dat';
		} else {
			$filepath = str_replace('/', '/'.getop('flat_prefix'), $message['source']).'.dat';
		}
		$allmsgs[$message['messageid']] = $filepath;
	}

	// Do some array manipulation to find orphaned files and messages
	$existing = array_intersect($allfiles, $allmsgs);
	$bad_files = array_diff($allfiles, $existing);
	$bad_msgs  = array_diff($allmsgs, $existing);

	// Change format of bad messages
	foreach ($bad_msgs as $filekey => $filename) {
		if (strpos($filename, '/') !== false) {
			$parts = explode('/', $filename);
		} else {
			$parts = array($filename);
		}
		$bad_msgs[$filekey] = trim($parts[0].'/'.substr($parts[1], strlen(getop('flat_prefix')), -4), '/\\');
	}

	print_R($bad_files);
	print_R($bad_msgs);
	exit;

	// Delete bad messages
	//$DB_site->query('
	//	DELETE FROM hive_message
	//	WHERE messageid IN ('.implode(', ', array_keys($bad_msgs)).')
	//');
	//$DB_site->query('
	//	DELETE FROM hive_messagefile
	//	WHERE filename IN ("'.implode('", "', $bad_msgs).'")
	//');

	// All done!
	$DB_site->query('
		UPDATE hive_setting
		SET value = 0
		WHERE variable = "maintain"
	');

	// Switch fields
	$DB_site->query('
		ALTER TABLE hive_message
		DROP source
	');
	$DB_site->query('
		ALTER TABLE hive_message
		CHANGE source2 source LONGTEXT NOT NULL DEFAULT ""
	');
	$DB_site->query('
		OPTIMIZE TABLE hive_message
	');

	// Delete obsolete files
	if (!$options['flat_use']) {
		$filenames = $DB_site->query('
			SELECT filename
			FROM hive_messagefile
		');
		while ($filename = $DB_site->fetch_array($filenames)) {
			if (strpos($filename['filename'], '/') === false) {
				$filepath = getop('flat_path', true).'/'.getop('flat_prefix').$filename['filename'].'.dat';
			} else {
				$filepath = getop('flat_path', true).'/'.str_replace('/', '/'.getop('flat_prefix'), $filename['filename']).'.dat';
			}
			if (file_exists($filepath)) {
				unlink($filepath);
			}
		}

		// Remove folders
		$DB_site->reset($filenames);
		while ($filename = $DB_site->fetch_array($filenames)) {
			if (strpos($filename['filename'], '/') === false) {
				continue;
			} else {
				$dirname = substr($filename['filename'], 0, 32);
				if (is_dir(getop('flat_path', true).'/'.$dirname)) {
					rmdir(getop('flat_path', true).'/'.$dirname);
					clearstatcache();
				}
			}
		}

		// Update database
		$DB_site->query('
			DELETE FROM hive_messagefile
		');
		$DB_site->query('
			UPDATE hive_setting
			SET value = ""
			WHERE variable = "flat_curfolder"
		');
		$DB_site->query('
			UPDATE hive_setting
			SET value = 0
			WHERE variable = "flat_curcount"
		');
	}

	$options = urlencode(serialize($options));
	$_POST['cmd'] = 'final';
}
*/

// ############################################################################
// Display settings
if ($cmd == 'modify') {
	adminlog();

	$donetitle = false;
	$settings = $DB_site->query('
		SELECT setting.*, settinggroup.title AS grouptitle
		FROM hive_setting AS setting
		LEFT JOIN hive_settinggroup AS settinggroup USING (settinggroupid)
		WHERE settinggroup.display = 0 AND settinggroup.description = "storage"
		ORDER BY settinggroup.display, setting.display
	');

	startform('storage.php', 'confirm', 'Are you SURE you want to change the message storage settings?');
	while ($setting = $DB_site->fetch_array($settings)) {
		if (!$donetitle) {
			starttable($setting['grouptitle']);
			$donetitle = true;
		}
		settingfield($setting);
	}
	endform('Save Changes');
	endtable();
}

cp_footer();
?>