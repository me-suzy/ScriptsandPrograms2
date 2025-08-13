<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: database.php,v $
// | $Date: 2002/11/01 16:09:33 $
// | $Revision: 1.16 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(0);
require_once('./global.php');

// ############################################################################
// Set the default do
if (!isset($do)) {
	$do = 'backup';
}

// ############################################################################
// The wrapping function to dump a database
function dump_database($fp) {
	global $DB_site;

	ob_start();

	$getversion = $DB_site->query_first('SELECT VERSION()');
	$version = $getversion[0];
	$date = date('r');
	echo "## HiveMail database dump\n";
	echo "## Host: ".$DB_site->config['server']."    Database: ".$DB_site->config['database']."\n";
	echo "## -------------------------------------------------------\n";
	echo "## Server version	$version\n";
	echo "## $date\n";

	if (is_resource($fp)) {
		fwrite($fp, ob_get_contents());
		ob_clean();
	} else {
		ob_flush();
	}

	$tables = $DB_site->query('SHOW TABLES');
	while ($table = $DB_site->fetch_array($tables)) {
		dump_table($table[0], $fp);
		if (is_resource($fp)) {
			fwrite($fp, ob_get_contents());
			ob_clean();
		} else {
			ob_flush();
		}
	}
}

// ############################################################################
// Creates a dump of a MySQL table
function dump_table($tableName, $fp) {
	global $DB_site;

	// DROP TABLE
	echo "\n\n##";
	echo "\n## Table structure for table '$tableName'";
	echo "\n##";
	echo "\n\nDROP TABLE IF EXISTS $tableName;\n";

	// CREATE TABLE
	$createTable = $DB_site->query_first("SHOW CREATE TABLE $tableName");
	echo preg_replace("#\r?\n#", '', 'CREATE TABLE IF NOT EXISTS'.substr($createTable[1], strlen('CREATE TABLE'))).";\n";

	// LOCK TABLE
	echo "\n##";
	echo "\n## Dumping data for table '$tableName'";
	echo "\n##\n\n";
	echo "\nLOCK TABLES $tableName WRITE;\n";

	if (is_resource($fp)) {
		fwrite($fp, ob_get_contents());
		ob_clean();
	} else {
		ob_flush();
	}

	// INSERT
	$rows = $DB_site->query("SELECT * FROM $tableName");
	while ($row = $DB_site->fetch_array($rows)) {
		dump_row($tableName, $row, $fp);
		if (is_resource($fp)) {
			fwrite($fp, ob_get_contents());
			ob_clean();
		} else {
			ob_flush();
		}
	}
	
	// UNLOCK TABLE
	echo "UNLOCK TABLES;";

	if (is_resource($fp)) {
		fwrite($fp, ob_get_contents());
		ob_clean();
	} else {
		ob_flush();
	}
}

// ############################################################################
// Creates an INSERT query out of row data
function dump_row($tableName, $row, $fp) {
	global $DB_site;

	$dump = "INSERT INTO $tableName VALUES (";
	foreach ($row as $field => $value) {
		if (!is_numeric($field)) {
			if (is_numeric($value)) {
				$dump .= "$value, ";
			} else {
				$dump .= "'".preg_replace("#\r?\n#", '\n', addslashes($value))."', ";
			}
		}
	}

	echo substr($dump, 0, -2).");\n";
	if (is_resource($fp)) {
		fwrite($fp, ob_get_contents());
		ob_clean();
	} else {
		ob_flush();
	}
}

// ############################################################################
if ($_POST['do'] == 'serve') {
	header('Content-disposition: filename=hivemail_backup_'.date('m_d_Y', time()).'.sql');
	header('Content-type: unknown/unknown');
	dump_database(true);
}

// ############################################################################
if ($_POST['do'] == 'save') {
	$fp = fopen($filename, 'wb');
	if (!is_resource($fp)) {
		cp_header();
		cp_error('PHP does not have access to write in the folder that contains the backup file. Please change the folder\'s attributes and try again.');
		cp_footer();
	}

	dump_database($fp);
	fclose($fp);

	cp_header();
	cp_redirect('Your database was successfully backed up and saved.', 'database.php');
	cp_footer();
}

// ############################################################################
if ($do == 'backup') {
	cp_header(' &raquo; Database Backup');

	starttable('WARNING');
	textrow('HiveMail <b><span class="cp_temp_cust">cannot guarantee</span></b> under any circumstances the integrity of database backups that are done using this script.<br /><br />
	If possible, you are <b><span class="cp_temp_cust">strongly recommended</span></b> to use MySQL\'s internal shell script, mysqldump, in the following manner:
	<ul>
		<li>Log into <tt>telnet</tt> / <tt>SSH</tt> for your website. (usually you will use the same login information you use for your site\'s FTP)</li>
		<li>Type the following from the command line:<br />
			<tt>mysqldump --opt -u{username} -p {database} > /path/to/dump.sql</tt><br /><br />
			Replacing:
			<ul>
				<li><tt>{username}</tt> with your MySQL username.</li>
				<li><tt>{database}</tt> with your MySQL database name.</li>
				<li><tt>/path/to/dump.sql</tt> with the complete path to your backup file. (for example, <tt>'.$_SERVER['DOCUMENT_ROOT'].'/hivemail_backup_'.date('m_d_Y', time()).'.sql</tt>)</li>
			</ul>
		</li>
		<li>You will then be asked to enter your MySQL password.</li>
		<li>The server will now create a backup of your database. The larger your database is, the longer it will take to back it up.</li>
		<li>When the backup is complete you will be brought back to the command prompt.</li>
		<li>Verify through FTP that the backup file is in place, and type <tt>exit</tt> to close the connection.</li>
	</ul>
	(All of the MySQL information above can be found in HiveMail\'s <tt>/includes/config.php</tt> file.)');
	tablehead(array('&nbsp;'), 2); 
	endtable();

	getclass();
	echo '<br /><br />';
	
	startform('database.php', 'save', 'Please note that we cannot guarantee the integrity of the\ndatabase backup you are about to create. Your browser\nmay time out while the script is still running, resulting in an\nincomplete backup.\n\nWe strongly recommend you to use MySQL\\\'s internal shell\nscript as described above if possible.');
	starttable('Save backup to server');
	textrow('This will save the database backup to a file on the server, so you will be able to easily restore it later.');
	getclass();
	inputfield('Path to file:<br /><span class="cp_small"><b>PHP must have access to write in this directory</b> (usually CHMOD 0777).<br /><b>Warning:</b> <i>Do not</i> place your backup in a folder that can be accessed from the web.<br />Place it above your web-root if possible!</span>', 'filename', "$_SERVER[DOCUMENT_ROOT]/hivemail_backup_".date('m_d_Y', time()).'.sql');
	endform('Save Backup to File');
	endtable();

	getclass();
	echo '<br /><br />';

	startform('database.php', 'serve', 'Please note that we cannot guarantee the integrity of the\ndatabase backup you are about to create. Your browser\nmay time out while the script is still running, resulting in an\nincomplete backup.\n\nWe strongly recommend you to use MySQL\\\'s internal shell\nscript as described above if possible.');
	starttable('Download database backup');
	textrow('Use this to download the backup of your database and save it to your computer.<br />If your database is large, you are strongly recommended to use the option above instead of this.');
	endform('Download Backup');
	endtable();

	cp_footer();
}

// ############################################################################
if ($_POST['do'] == 'upload') {
/*
	$attachment_name = strtolower($file_name);
	$extension = getextension($file_name);

	$filestuff = '';
	if (is_uploaded_file($file)) {
		if ($safeupload) {
			$path = $tmppath.'/'.$file_name;
			move_uploaded_file($file, $path);
			$file = $path;
		}
	
		$filesize = filesize($file);
		if ($filesize == $file_size and strstr($file, '..') == '') {
			$filenum = fopen($file, 'rb');
			$filestuff = fread($filenum, $filesize);
			fclose($filenum);
			unlink($file);
		}
	}

	if (empty($filestuff)) {
		cp_header();
		cp_error('There has been error while trying to upload the file.<br />Please go back and try again.');
	}
*/
}

// ############################################################################
if ($do == 'restore') {
	cp_header(' &raquo; Restoring Database Backup');

	starttable('Restoring Backup');
	textrow('To restore a database backup you once made, follow these simple steps:
	<ul>
		<li>First, make sure the backup file is on your site. If it\'s on your computer, you will need to upload it first using FTP.</li>
		<li>Log into <tt>telnet</tt> / <tt>SSH</tt> for your website. (usually you will use the same login information you use for your site\'s FTP)</li>
		<li>Type the following from the command line:<br />
			<tt>mysql -u{username} -p {database} < /path/to/backup.sql</tt><br /><br />
			Replacing:
			<ul>
				<li><tt>{username}</tt> with your MySQL username.</li>
				<li><tt>{database}</tt> with your MySQL database name.</li>
				<li><tt>/path/to/dump.sql</tt> with the complete path to your backup file. (for example, <tt>'.$_SERVER['DOCUMENT_ROOT'].'/my_backup.sql</tt>)</li>
			</ul>
		</li>
		<li>You will then be asked to enter your MySQL password.</li>
		<li>The server will now restore the backup of your database. The larger your backup is, the longer it will take to restore it.</li>
		<li>When the process is complete you will be brought back to the command prompt.</li>
		<li>Verify the database has been restored, and type <tt>exit</tt> to close the connection.</li>
	</ul>
	(All of the MySQL information above can be found in HiveMail\'s <tt>/includes/config.php</tt> file.)<br /><br />
	<span class="cp_temp_cust"><b>IMPORTANT NOTE:</b></span><br />
	Restoring a database backup in this manner will <span class="cp_temp_edit"><b>IRREVERSIBLY DELETE</b></span> all current data in your database, and replace it with data from the backup you have made. In other words, if you have made the database backup on March 13th, and you are restoring it on March 16th, any and all new data that was created between these dates will be <span class="cp_temp_edit"><b>DELETED</b></span>.');
	tablehead(array('&nbsp;'), 2); 
	endtable();

/*
	echo '<form action="database.php" method="post" name="form" enctype="multipart/form-data"';
	//echo ' onSubmit="for (value = 0; value < this.overwrite.length; value++) { if (this.overwrite[value].checked == true) { dooverwrite = this.overwrite[value].value; } } if (this.filename.value == \'\') { alert(\'The file upload field is required. Please fill it in.\'); return false; } else if (dooverwrite == 1) { if (confirm(\'You have chosen to DELETE ALL\nHiveMail data fromyour database.\n\nAre you SURE?\')) { return confirm(\'You are about to outright DELETE tables from your database.\nHiveMail can hold no responsibility for any loss of data incurred\nas a result of performing this action.\n\nDo you agree to these terms?\'); } else { return false; } } else { return true; }"';
	echo ' onSubmit="if (confirm(\'You are about to DELETE ALL\nHiveMail data fromyour database.\n\nAre you SURE?\')) { return confirm(\'You are about to outright DELETE tables from your database.\nHiveMail can hold no responsibility for any loss of data incurred\nas a result of performing this action.\n\nDo you agree to these terms?\'); } else { return false; }"';
	echo ">\n";
	hiddenfield('do', 'load');
	hiddenfield('MAX_FILE_SIZE', get_max_upload());
	starttable('Restore backup from server');
	textrow('This will load the backup of the database from a file on the server and import all data.');
	getclass();
	inputfield('Path to file:', 'filename', $_SERVER['DOCUMENT_ROOT'].'/');
	getclass();
	//yesno('Would you like to overwrite current data in your database?<br /><span class="cp_small">If this is set to yes, all HiveMail data that is currently in your database will be <b>COMPLETELY</b> deleted.<br />This procedure is dangerously fatal, and you will <b>NOT</b> be able to recover <b>ANY</b> data that is lost.<br /><b>HiveMail can hold no responsibility for any loss of data incurred as a result of performing this action.</b></span>', 'overwrite', 0);
	textrow('All HiveMail data that is currently in your database will be <b>COMPLETELY</b> deleted.<br />This procedure is dangerously fatal, and you will <b>NOT</b> be able to recover <b>ANY</b> data that is lost.<br /><b>HiveMail can hold no responsibility for any loss of data incurred as a result of performing this action.</b>');
	endform('Load File and Restore');
	endtable();

	getclass();
	echo '<br /><br />';

	echo '<form action="database.php" method="post" name="form" enctype="multipart/form-data"';
	//echo ' onSubmit="for (value = 0; value < this.overwrite.length; value++) { if (this.overwrite[value].checked == true) { dooverwrite = this.overwrite[value].value; } } if (this.file.value == \'\') { alert(\'The file upload field is required. Please fill it in.\'); return false; } else if (dooverwrite == 1) { if (confirm(\'You have chosen to DELETE ALL\nHiveMail data fromyour database.\n\nAre you SURE?\')) { return confirm(\'You are about to outright DELETE tables from your database.\nHiveMail can hold no responsibility for any loss of data incurred\nas a result of performing this action.\n\nDo you agree to these terms?\'); } else { return false; } } else { return true; }"';
	echo ' onSubmit="if (confirm(\'You are about to DELETE ALL\nHiveMail data fromyour database.\n\nAre you SURE?\')) { return confirm(\'You are about to outright DELETE tables from your database.\nHiveMail can hold no responsibility for any loss of data incurred\nas a result of performing this action.\n\nDo you agree to these terms?\'); } else { return false; }"';
	echo ">\n";
	hiddenfield('do', 'upload');
	hiddenfield('MAX_FILE_SIZE', get_max_upload());
	starttable('Upload database backup');
	textrow('Use this to upload a backup of your database and use it to restore information.<br />If the backup file is large, you are strongly recommended to use the option above instead of this.<br />Note that this function is limited to PHP\'s internal limit of file uploads. On this system, uploaded files are limited to '.intval(ini_get('upload_max_filesize')).' MB. If your backup file is larger, you will not be able to use this form.');
	getclass();
	filefield('Backup file:<br /><span class="cp_small">Click Browse and select the backup file from your computer. When you are done, click Open<br />and submit the form to load the file.', 'file');
	getclass();
	//yesno('Would you like to overwrite current data in your database?<br /><span class="cp_small">If this is set to yes, all HiveMail data that is currently in your database will be <b>COMPLETELY</b> deleted.<br />This procedure is dangerously fatal, and you will <b>NOT</b> be able to recover <b>ANY</b> data that is lost.<br /><b>HiveMail can hold no responsibility for any loss of data incurred as a result of performing this action.</b></span>', 'overwrite', 0);
	textrow('All HiveMail data that is currently in your database will be <b>COMPLETELY</b> deleted.<br />This procedure is dangerously fatal, and you will <b>NOT</b> be able to recover <b>ANY</b> data that is lost.<br /><b>HiveMail can hold no responsibility for any loss of data incurred as a result of performing this action.</b>');
	endform('Upload and Restore');
	endtable();
*/

	cp_footer();
}

// ############################################################################
if ($_POST['do'] == 'dooptimize') {
	cp_header(' &raquo; Optimize and Repair Database');

	starttable('Repairing and optimizing tables...');
	echo "	<tr class=\"".getclass()."\" height=\"275\" valign=\"top\">\n";
	echo "		<td style=\"padding: 10px;\">";

	foreach ($tables as $table => $do) {
		if ($do != 1) {
			continue;
		}
		echo "<p>Examining table <b>$table</b>:<br />\n";

		echo '&raquo; Checking ... ';
		$check = $DB_site->query_first("CHECK TABLE $table");
		if ($check['Msg_type'] == 'error') {
			echo '<b><span class="cp_temp_cust">ERROR</span></b>: '.$check['Msg_text'];
		} else {
			echo '<b><span class="cp_temp_orig">OK</span></b>';
		}
		echo "<br />\n";

		if ($check['Msg_type'] == 'error') {
			echo '&raquo; Repairing ... ';
			$repair = $DB_site->query_first("REPAIR TABLE $table");
			if ($repair['Msg_type'] == 'error') {
				echo '<b><span class="cp_temp_cust">ERROR</span></b>: '.$check['Msg_text'];
			} else {
				echo '<b><span class="cp_temp_orig">OK</span></b>';
			}
			echo "<br />\n";
		}

		if ($repair['Msg_type'] != 'error') {
			echo '&raquo; Optimizing ... ';
			$optimize = $DB_site->query_first("OPTIMIZE TABLE $table");
			if ($optimize['Msg_type'] == 'error') {
				echo '<b><span class="cp_temp_cust">ERROR</span></b>: '.$optimize['Msg_text'];
			} else {
				echo '<b><span class="cp_temp_orig">OK</span></b>';
			}
			echo "<br />\n";
		}

		echo "<br />\n";
	}

	echo "All done!</td>\n";
	echo "	</tr>\n";
	tablehead(array('&nbsp;'));
	endtable();

	cp_footer();
}

// ############################################################################
if ($do == 'optimize') {
	cp_header(' &raquo; Optimize and Repair Database');

	startform('database.php', 'dooptimize');
	starttable('Optimize and Repair Database', '450');
	textrow('Please choose below which tables you would like to repair and optimize.');
	while ($table = $DB_site->fetch_array($tables, 'SHOW TABLES')) {
		yesno($table[0], 'tables['.$table[0].']');
	}
	endform('Optimize tables');
	endtable();

	cp_footer();
}

?>