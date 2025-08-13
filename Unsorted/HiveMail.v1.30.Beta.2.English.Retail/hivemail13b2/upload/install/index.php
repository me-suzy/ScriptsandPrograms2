<!--<?php
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
// | $RCSfile: index.php,v $ - $Revision: 1.22 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

if (1 == 0) {
    echo '--><span style="font-weight: bold; color: red; font-family: verdana; font-size: 12px;">PHP is not running, please contact your system administrator.</span><div style="font-size: 1px; visibility: hidden;">';
} else {
    ?>--><?php
}

error_reporting(E_ALL & ~E_NOTICE);
define('ALLOW_LOGGED_OUT', true);
define('ALLOW_INSTALL_FOLDER', true);
require_once('../includes/init_vars.php');
require_once('../includes/config.php');
require_once('../includes/db_mysql.php');
require_once('../includes/functions.php');
require_once('../includes/functions_file.php');
require_once('../includes/functions_admin.php');
require_once('../includes/functions_template.php');
require_once('../includes/init.php');
define('CP_IGNORE_COOKIES', true);
cp_header(' &raquo; Installation', true, true, '<script language="JavaScript">
<!--
function confirmReset() {
	if (confirm("You have chosen to DELETE a number of tables from\nyour database, that may include non-HiveMail data.\n\nAre you SURE?")) {
		if (confirm("You are about to outright DELETE tables from your database.\nHiveMail can hold no responsibility for any loss of data incurred\nas a result of performing this action.\n\nDo you agree to these terms?")) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
-->
</script>');

// ############################################################################
// Checks after each query if it was ok
function report_mysql_error($define = true) {
	global $DB_site;

	if ($DB_site->errno() != 0) {
		echo '<span class="cp_temp_edit"><b>ERROR</b></span>: '.$DB_site->error();
		define('GOTO_NEXT', $define);
	} else {
		echo '<span class="cp_temp_orig"><b>OK!</b></span>';
	}
}

// ############################################################################
// Define steps
$steps = array(
	1 => 'Connect to MySQL Server',
	2 => 'Select MySQL Database',
	3 => 'Create Tables',
	4 => 'Populate Tables',
	5 => 'Add Template Information',
	6 => 'Set Up Default Options',
	7 => 'Complete Installation',
);

// ############################################################################
// Header
echo '<div align="left" style="padding: 10px;"><b><div align="center"><span style="font-size: 20px;">HiveMail&trade; Installation</span><br /><!--CyKuH [WTN]-->Nullified by WTN Team `2004<br />'.iif(is_numeric($step), "Step $step of ".count($steps).": $steps[$step]", 'Welcome to HiveMail&trade; version '.HIVEVERSION).'</b></div><br /><br />';

// ############################################################################
// Welcome screen
if (!is_numeric($step)) {
	echo '<p>Welcome to the HiveMail&trade; installation script!<br />
	This script will guide you through the installation of HiveMail&trade;. These are the steps you will be going through in this script:
	<ul type="1">';
	foreach ($steps as $stepname) {
		echo "<li>$stepname</li>\n";
	}
	echo '</ul>
	It is very important that you follow the on-screen instructions precisely and not skip over any steps.<br />
	If at any time you are stuck or feel like you need help, please do not hesitate and contact us at support forum.</p>';

	echo "<p>The script will now make sure your server is capable of running HiveMail&trade;:\n<ul>";
	$phpversion = phpversion();
	$askhostto = '';

	// PHP version
	echo "<li>PHP version ($phpversion): ";
	if ($phpversion >= '4.1.0') {
		echo '<span class="cp_temp_orig"><b>PASSED!</b></span></li>';
	} else {
		echo '<span class="cp_temp_edit"><b>FAILED</b></span></li>';
		$askhostto .= "<li>Upgrade PHP to at least 4.1.0.</li>\n";
	}

	// MySQL support
	echo "<li>MySQL support: ";
	if (function_exists('mysql_connect')) {
		echo '<span class="cp_temp_orig"><b>PASSED!</b></span></li>';
	} else {
		echo '<span class="cp_temp_edit"><b>FAILED</b></span></li>';
		$askhostto .= "<li>Recompile PHP with MySQL support.</li>\n";
	}

	// PCRE support
	echo "<li>PCRE support: ";
	if (function_exists('preg_replace')) {
		echo '<span class="cp_temp_orig"><b>PASSED!</b></span></li>';
	} else {
		echo '<span class="cp_temp_edit"><b>FAILED</b></span></li>';
		$askhostto .= "<li>Recompile PHP with PCRE support.</li>\n";
	}

	echo '</ul>';

	if (empty($askhostto)) {
		define('GOTO_NEXT', true);
	} else {
		$failed = substr_count($askhostto, '<li>');
		echo "$failed out of 3 tests ".iif($failed == 1, 'has', 'have')." failed, and HiveMail&trade; will not be able to function properly on this server.<br />
		Please contact your host and request that they:
		<ul>$askhostto</ul>";
	}
	echo '</p>';
}

// ############################################################################
// Connect to MySQL Server
if ($step == 1) {
	$DB_site = new DB_MySQL($config, false);
	$DB_site->showerror = false;
	$DB_site->connect();

	echo '<p>The script will now attempt to connect to your MySQL server ... ';
	$errno = $DB_site->errno();
	switch ($errno) {
		case 2005:
			echo '<span class="cp_temp_edit"><b>ERROR</b></span><br />Could not connect to MySQL server at <tt>'.$DB_site->config['server'].'</tt>. Please make sure the <tt>server</tt> setting in <tt>config.php</tt> is correct.';
			break;
		case 1045:
			echo '<span class="cp_temp_edit"><b>ERROR</b></span><br />Could not log on to server using the username and password you specified. Please make sure the <tt>username</tt> and <tt>password</tt> settings in <tt>config.php</tt> is correct.';
			break;
		case 1049: // Invalid database, it's ok
		case 0:
			echo '<span class="cp_temp_orig"><b>OK!</b></span>';
			define('GOTO_NEXT', true);
			break;
		default:
			echo '<span class="cp_temp_edit"><b>ERROR</b></span><br />An error occured while trying to connect to the server: <tt>'.$DB_site->error().'</tt>.';
	}
	echo '</p>';
}

// ############################################################################
// Select MySQL Database
if ($step == 2) {
	if (file_exists('./sql_install.php') and is_readable('./sql_install.php')) {
		require_once('./sql_install.php');
	} else {
		die('<span class="cp_temp_edit"><b>ERROR</b></span>: Cannot find or read <tt>sql_install.php</tt>. Please ensure that it is in the <tt>/install</tt> directory and refresh the page.');
	}

	$DB_site = new DB_MySQL($config, false);
	$DB_site->showerror = false;
	$DB_site->connect();

	echo '<p>The script will now attempt to select your MySQL database ... ';
	if ($DB_site->errno() != 0) {
		echo '<span class="cp_temp_edit"><b>ERROR</b></span><br />The database you have specified (<tt>'.$DB_site->config['database'].'</tt>) does not exist.</p>
		<p>The script will now attempt to create the database ... ';
		$DB_site->query('CREATE DATABASE '.$DB_site->config['database']);
		$DB_site->select_db($DB_site->config['database']);

		if ($DB_site->errno() != 0) {
			echo '<span class="cp_temp_edit"><b>ERROR</b></span><br />The script was unable to create the database. Please create it yourself or contact your host for help.';
		} else {
			echo '<span class="cp_temp_orig"><b>OK!</b></span>';
			define('GOTO_NEXT', true);
		}
	} else {
		echo '<span class="cp_temp_orig"><b>OK!</b></span>';

		$collision = false;
		$currentTables = $DB_site->query('SHOW TABLES');
		while ($currentTable = $DB_site->fetch_array($currentTables)) {
			if (array_key_exists($currentTable[0], $tables)) {
				$collision = true;
				break;
			}
		}

		if (!$collision) {
			define('GOTO_NEXT', true);
		} else {
			echo '</p>
			<p><span class="cp_temp_edit"><b>IMPORTANT:</b></span><br />
			The database you have chosen already exists, and it appears that it contains tables that must be used by HiveMail&trade;.<br />
			To prevent conflicts between programs, it is recommended that you let the script remove any tables HiveMail&trade; will use.<br />
			<b>These tables may, <i>and almost certainly do</i>, contain non-HiveMail&trade; data!</b> If you are unsure please <span class="cp_temp_edit"><b>do not proceed</b></span> without consulting with a professional!<br />
			</p>
			<p>
			<b>Please choose which action to take below:</b>
			<ul>
				<li>To continute <span class="cp_temp_edit"><b>WITHOUT</b></span> deleting the necessary tables, <a href="index.php?step=3"><b>click here</b></a>. We do not recommend that you choose this option unless you <span class="cp_temp_edit"><b>know</b></span> what you are doing.</li>
				<li>To continute and <span class="cp_temp_edit"><b>DELETE THE TABLES</b></span>, <a href="index.php?step=3&reset=1" onClick="return confirmReset();"><b>click here</b></a>. This option will <span class="cp_temp_edit"><b>IRREVERSIBLY DELETE</b></span> the contents of some of the tables in your database!</li>
			</ul>
			Whichever option you choose, we <span class="cp_temp_edit"><b>HIGHLY</b></span> recommend that you back your database up before proceeding.';
			
			// If you have previously installed HiveMail and trying to upgrade to a newer version...
		}
	}
	echo '</p>';
}

// ############################################################################
// By step 3 a database connection should be established
if ($step > 2) {
	$DB_site = new DB_MySQL($config);
	$DB_site->showerror = false;
	if ($step > 3) {
		$DB_site->setup_options();
	}
}

// ############################################################################
// Create Tables
if ($step == 3) {
	if (file_exists('./sql_install.php') and is_readable('./sql_install.php')) {
		require_once('./sql_install.php');
	} else {
		die('<span class="cp_temp_edit"><b>ERROR</b></span>: Cannot find or read <tt>sql_install.php</tt>. Please ensure that it is in the <tt>/install</tt> directory and refresh the page.');
	}
	
	if ($reset == 1 and $confirm != 1) {
		echo '<br />';
		startform('index.php');
		hiddenfield('step', '3');
		hiddenfield('reset', '1');
		hiddenfield('confirm', '1');
		starttable('Delete tables?', '450', true, 2, true);
		textrow('You have chosen to <span class="cp_temp_edit"><b>IRREVERSIBLY DELETE THE TABLES</b></span> HiveMail&trade; needs from your database.<br />
		This is the <b>very last</b> warning message you will see before the data is deleted.<br />
		<br />
		<span class="cp_temp_edit"><b>ARE YOU SURE YOU WANT TO PROCEED?</b></span>');
		endform('    YES, delete the tables    ', '', 'NO, do not delete the tables');
		endtable();

		cp_footer();
		exit;
	} elseif ($reset == 1 and $confirm == 1) {
		echo "<p>Deleting tables HiveMail&trade; needs:\n<ul>\n";
		foreach ($tables as $tablename => $tablecreate) {
			echo "<li>Dropping table <tt>$tablename</tt> ... ";
			$DB_site->query("DROP TABLE IF EXISTS $tablename");
			report_mysql_error();
			echo "</li>\n";
		}
		echo "</ul>\n</p>\n";
	}

	echo "<p>The script will now attempt to create the tables in your database:\n<ul>\n";
	foreach ($tables as $tablename => $tablecreate) {
		echo "<li>Creating table <tt>$tablename</tt> ... ";
		$DB_site->query($tablecreate);
		if ($DB_site->errno() != 0) {
			$error = $DB_site->error();
			$missingField = false;
			$getScheme = $DB_site->query("SHOW FIELDS FROM $tablename");
			while ($field = $DB_site->fetch_array($getScheme)) {
				if (!strstr($tablecreate, "`$field[0]`")) {
					$missingField = true;
					break;
				}
			}
			if (!$missingField) {
				echo '<span class="cp_temp_orig"><b>OK!</b></span>';
			} else {
				echo "<span class=\"cp_temp_edit\"><b>ERROR</b></span>: $error";
				define('GOTO_NEXT', true);
			}
		} else {
			echo '<span class="cp_temp_orig"><b>OK!</b></span>';
		}
		echo "</li>\n";
	}
	echo '</ul>';
	if (defined('GOTO_NEXT')) {
		echo 'It seems that at least one error occurred while creating the tables. Only proceed if you are sure these errors are not critical.';
	} else {
		define('GOTO_NEXT', true);
	}
	echo '</p>';
}

// ############################################################################
// Populate Tables
if ($step == 4) {
	if (file_exists('./sql_install.php') and is_readable('./sql_install.php')) {
		require_once('./sql_install.php');
	} else {
		die('<span class="cp_temp_edit"><b>ERROR</b></span>: Cannot find or read <tt>sql_install.php</tt>. Please ensure that it is in the <tt>/install</tt> directory and refresh the page.');
	}

	echo "<p>The script will now attempt to populate the tables in your database with data:\n<ul>\n";
	foreach ($inserts as $insert) {
		$tablename = substr($insert, strlen('INSERT INTO '));
		$tablename = substr($tablename, 0, strpos($tablename, ' '));
		echo "<li>Adding data to table <tt>$tablename</tt> ... ";
		$DB_site->query($insert);
		report_mysql_error();
		echo "</li>\n";
	}
	echo '</ul>';
	if (defined('GOTO_NEXT')) {
		echo 'It seems that at least one error occurred while populating the tables. Only proceed if you are sure these errors are not critical.';
	} else {
		define('GOTO_NEXT', true);
	}
	echo '</p>';
}

// ############################################################################
// Add Template Information
if ($step == 5) {
	if (file_exists('./templates_install.php') and is_readable('./templates_install.php')) {
		require_once('./templates_install.php');
	} else {
		die('<span class="cp_temp_edit"><b>ERROR</b></span>: Cannot find or read <tt>templates_install.php</tt>. Please ensure that it is in the <tt>/install</tt> directory and refresh the page.');
	}

	$DB_site->query('DELETE FROM hive_template');
	echo "<p>The script will now load the database with the default templates:\n<ul>\n";
	foreach ($templates as $name => $tempinfo) {
		echo "<li>Creating template <tt>$name</tt> ... ";
		$DB_site->query('
			INSERT INTO hive_template
			(templateid, templatesetid, templategroupid, title, user_data, parsed_data)
			VALUES
			(NULL, -1, '.$tempinfo['templategroupid'].', "'.addslashes($name).'", "'.addslashes($tempinfo['user_data']).'", "'.addslashes($tempinfo['parsed_data']).'")
		');
		report_mysql_error();
		echo "</li>\n";
	}
	echo '</ul>';
	if (defined('GOTO_NEXT')) {
		echo 'It seems that at least one error occurred while loading the templates. Only proceed if you are sure these errors are not critical.';
	} else {
		define('GOTO_NEXT', true);
	}
	echo '</p>';
}

// ############################################################################
// Set Up Default Options
if ($step == 6) {
	if (trim($aadmin['username']) == '' or trim($aadmin['password']) == '' or trim($aadmin['realname']) == '' or trim($newoptions['appname']) == '' or trim($newoptions['domainname']) == '') {
		if ($_POST['step'] == 6) {
			echo '<p align="center"><span class="cp_temp_edit"><b>ERROR</b></span>: All fields are requireed.</p>';
		}
		echo '<p align="center">Please fill in the fields below for the various options.<br />For a detailed explanation of these fields.</p>';

		startform('index.php', '', '', array('aadmin_username' => 'username', 'aadmin_password' => 'password', 'aadmin_realname' => 'real name', 'newoptions_appname' => 'application name', 'newoptions_domainname' => 'domain name'));
		hiddenfield('step', '6');
		starttable('Administrator Account', '450', true, 2, true);
		inputfield('Username:', 'aadmin[username]');
		inputfield('Password:', 'aadmin[password]');
		inputfield('Real name:', 'aadmin[realname]');
		inputfield('Secret question:', 'aadmin[question]');
		inputfield('Secret answer:', 'aadmin[answer]');
		tablehead(array('Program Options'), 2);
		inputfield('Application name:', 'newoptions[appname]');
		getclass();
		textrow('<span class="cp_small">This is the name of the program that will be running. It will be viewable on every page.</span>');
		inputfield('Domain name:', 'newoptions[domainname]', '@'.substr($_SERVER['HTTP_HOST'], iif(substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.', 4, 0)));
		getclass();
		textrow('<span class="cp_small">The domain name HiveMail&trade; is running on, with a preceding \'@\' character.<br />For example: @yourdomain.com, @example.net, etc.</span>');
		inputfield('Program location:', 'newoptions[appurl]', 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/') - strlen('install') - 1));
		getclass();
		textrow('<span class="cp_small">The complete URL (without any trailing slashes) of the program, e.g: http://www.example.com/webmail.</span>');
		endform('Submit Options');
		endtable();
		$nofooter = true;
	} else {
		// Update options
		echo "<p>Updating options:\n<ul>\n<li>Application name ... ";
		$DB_site->query('
			UPDATE hive_setting
			SET value = "'.addslashes($newoptions['appname']).'"
			WHERE variable = "appname"
		');
		report_mysql_error(false);
		echo "</li>\n<li>Domain name ... ";
		$DB_site->query('
			UPDATE hive_setting
			SET value = "'.addslashes($newoptions['domainname']).'"
			WHERE variable = "domainname"
		');
		report_mysql_error(false);
		echo "</li>\n<li>Program location ... ";
		$DB_site->query('
			UPDATE hive_setting
			SET value = "'.addslashes($newoptions['appurl']).'"
			WHERE variable = "appurl"
		');
		report_mysql_error(false);
		echo "</li>\n<li>Sender name ... ";
		$DB_site->query('
			UPDATE hive_setting
			SET value = "'.addslashes("Mail Delivery System <postmaster$newoptions[domainname]>").'"
			WHERE variable = "smtp_errorfrom"
		');
		report_mysql_error(false);
		echo "</li>\n<li>Server time zone ... ";
		$DB_site->query('
			UPDATE hive_setting
			SET value = "'.(date('Z') / 3600).'"
			WHERE variable = "timeoffset"
		');
		report_mysql_error(false);
		echo "</li>\n</ul>\n</p>";

		// Default user options
		$defuseroptions = unserialize(getop('defuseroptions'));

		// Create admin
		echo "<p>Creating administrator account ... ";
		$DB_site->query("
			INSERT INTO hive_user
			(userid, username, password, usergroupid, skinid, realname, regdate, lastvisit, cols, question, answer, options, options2, replyto, aliases)
			VALUES
			(NULL, '".addslashes($aadmin['username'])."', '".addslashes(md5($aadmin['password']))."', 1, 1, '".addslashes($aadmin['realname'])."', ".TIMENOW.", ".TIMENOW.", '".addslashes('a:6:{i:0;s:8:"priority";i:1;s:6:"attach";i:2;s:4:"from";i:3;s:7:"subject";i:4;s:8:"datetime";i:5;s:4:"size";}')."', '".addslashes($aadmin['question'])."', '".addslashes(md5($aadmin['answer']))."', $defuseroptions[0], $defuseroptions[1], '".addslashes("$aadmin[username]$newoptions[domainname]")."', '".addslashes($aadmin['username'])."')
		");
		$DB_site->query("
			INSERT INTO hive_alias
			SET userid = ".$DB_site->insert_id().", alias = '".addslashes($aadmin['username'])."'
		");
		report_mysql_error(false);
		echo "</p>";

		if (!defined('GOTO_NEXT')) {
			define('GOTO_NEXT', true);
		}
	}
}

// ############################################################################
// Complete Installation
if ($step == 7) {
	echo 'Congratulations, you have successfully installed the Web interface of HiveMail&trade;!<br /><br />
	As an administrator you can log on to the HiveMail&trade; Administrator Control Panel, where you can control each and every aspect of the program. To get there, please remove the <tt>/install</tt> folder from your web site first (for security reasons). Once you are you done, <a href="../admin/">click here</a> to proceed to the control panel.<br /><br />
	Installing the Web interface was the first part of installing HiveMail&trade;. Now it\'s time install the email gateway, that is - the program that collects mail from your server and delivers it to your users.';
}

// ############################################################################
// Footer
if ($nofooter !== true) {
	starttable('', '90%', true, 2, false, 4, false);
	tablehead(array(iif(defined('GOTO_NEXT') and GOTO_NEXT == true, '<a href="index.php?step='.($step + 1).'">&raquo; Continue to next step: '.$steps[($step + 1)].' &raquo;</a>', '&nbsp;')));
	endtable();
}

cp_footer();
?>