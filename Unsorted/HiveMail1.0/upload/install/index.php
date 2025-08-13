<!--<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: index.php,v $
// | $Date: 2002/11/12 16:21:53 $
// | $Revision: 1.12 $
// +-------------------------------------------------------------+

if (1 == 0) {
    echo '--><span style="font-weight: bold; color: red; font-family: verdana; font-size: 12px;">You are not running PHP - Please contact your system administrator.</span><div style="font-size: 1px; visibility: hidden;">';
} else {
    ?>--><?php
}

error_reporting(E_ALL & ~E_NOTICE);
define('ALLOW_LOGGED_OUT', true);
define('ALLOW_INSTALL_FOLDER', true);
require_once('../includes/config.php');
require_once('../includes/db_mysql.php');
require_once('../includes/functions.php');
require_once('../includes/admin_functions.php');
require_once('../includes/template_functions.php');
require_once('../includes/init.php');
cp_header(' &raquo; Installation', false, false, '<script language="JavaScript">
<!--
function confirmReset() {
	if (confirm("You have chosen to DELETE a number of tables from\nyour database, that may include non-HiveMail data.\n\nAre you SURE?")) {
		if (confirm("You are about to outright DELETE tables from your database.\nHiveMail can hold no responsibility for any loss of data incurred\nas a result of performing this action.\n\nDo you agree to these terms?")) {
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
	if (mysql_errno() != 0) {
			echo '<span class="cp_temp_edit"><b>ERROR</b></span>: '.mysql_error();
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
starttable('<table border="0" cellpadding="0" cellspacing="0" style="border-width: 0px;" align="center"><tr class="thead" style="background: none;"><td><img src="../misc/cp_logo.gif" valign="middle" /></td><td style="padding-left: 12px;"><span style="font-size: 20px;">HiveMail Installation</span><br /><!-- CyKuH [WTN] --><span style="font-size: 15px;" class="theadlink">Nullified By CyKuH from WTN Team</span><br />'.iif(is_numeric($step), "Step $step of ".count($steps).": $steps[$step]", 'Welcome to HiveMail version 1.0').'</td></tr></table>', '100%');
echo "	<tr class=\"".getclass()."\" height=\"275\" valign=\"top\">\n";
echo "		<td style=\"padding: 10px;\">";

// ############################################################################
// Welcome screen
if (!is_numeric($step)) {
	echo '<p>Welcome to HiveMail installation script!<br />
	This script will guide you through the installation of HiveMail. These are the steps you will be going through in this script:
	<ul type="1">';
	foreach ($steps as $stepname) {
		echo "<li>$stepname</li>\n";
	}
	echo '</ul>
	It is very important that you follow the on-screen instructions precisely and not skip over any steps. <br />
	';

	echo "<p>The script will now make sure your server is capable of running HiveMail:\n<ul>";
	$phpversion = phpversion();
	$askhostto = '';

	// PHP version
	echo "<li>PHP version ($phpversion): ";
	if ($phpversion >= '4.0.6') {
		echo '<span class="cp_temp_orig"><b>PASSED!</b></span></li>';
	} else {
		echo '<span class="cp_temp_edit"><b>FAILED</b></span></li>';
		$askhostto .= "<li>Upgrade PHP to at least 4.0.6.</li>\n";
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
		echo "$failed out of 3 tests ".iif($failed == 1, 'has', 'have')." failed, and HiveMail will not be able to function properly on this server.<br />
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
	$errno = mysql_errno();
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
			echo '<span class="cp_temp_edit"><b>ERROR</b></span><br />An error occured while trying to connect to the server: <tt>'.mysql_error().'</tt>.';
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
	if (mysql_errno() != 0) {
		echo '<span class="cp_temp_edit"><b>ERROR</b></span><br />The database you have specified (<tt>'.$DB_site->config['database'].'</tt>) does not exist.</p>
		<p>The script will now attempt to create the database ... ';
		$DB_site->query('CREATE DATABASE '.$DB_site->config['database']);
		$DB_site->select_db($DB_site->config['database']);

		if (mysql_errno() != 0) {
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
			The database you have chosen already exists, and it appears that it contains tables that must be used by HiveMail.<br />
			To prevent conflicts between programs, it is recommended that the script will remove any tables HiveMail will use.<br />
			<b>These tables may, <i>and probably do</i>, contain non-HiveMail data!</b> If you are unsure please <span class="cp_temp_edit"><b>do not proceed</b></span> without consulting with a professional!<br />
			</p>
			<p>
			<b>Please choose which action to take below:</b>
			<ul>
				<li>To continute <span class="cp_temp_edit"><b>WITHOUT</b></span> deleting the necessary tables, <a href="index.php?step=3">click here</a>. We do not recommend that you choose this option unless you <span class="cp_temp_edit"><b>know</b></span> what you are doing.</li>
				<li>To continute and <span class="cp_temp_edit"><b>DELETE THE TABLES</b></span>, <a href="index.php?step=3&reset=1" onClick="return confirmReset();">click here</a>. This option will <span class="cp_temp_edit"><b>IRREVERSIBLY DELETE</b></span> the contents of some of the tables in your database!</li>
			</ul>
			Whichever option you choose, we <span class="cp_temp_edit"><b>HIGHLY</b></span> recommend that you back your database up before proceeding.';
			
			// If you have previously installed HiveMail and trying to upgrade to a newer version...
		}
	}
	echo '</p>';
}

// ############################################################################
// By step 3 a database connection should be established
if ($step > 2) {
	$DB_site = new DB_MySQL($config);
	$DB_site->showerror = false;
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
		textrow('You have chosen to <span class="cp_temp_edit"><b>IRREVERSIBLY DELETE THE TABLES</b></span> HiveMail needs from your database.<br />
		This is the <b>very last</b> warning message you will see before the data is deleted.<br />
		<br />
		<span class="cp_temp_edit"><b>ARE YOU SURE YOU WANT TO PROCEED?</b></span>');
		endform('    YES, delete the tables    ', '', 'NO, do not delete the tables');
		endtable();

		echo "</td>\n";
		echo "	</tr>\n";
		tablehead(array('&nbsp;'));
		endtable();
		exit;
	} elseif ($reset == 1 and $confirm == 1) {
		echo "<p>Deleting tables HiveMail needs:\n<ul>\n";
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
		if (mysql_errno() != 0) {
			$error = mysql_error();
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
		echo 'It seems that an error occurred while creating the tables. Only proceed if you are sure these errors are not critical.';
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
		echo "<li>Adding data to table <tt>$tablename</tt> ... </li>\n";
		$DB_site->query($insert);
		report_mysql_error();
	}
	echo '</ul>';
	if (defined('GOTO_NEXT')) {
		echo 'It seems that an error occurred while populating the tables. Only proceed if you are sure these errors are not critical.';
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

	$DB_site->query('DELETE FROM template');
	echo "<p>The script will now load the database with the default templates:\n<ul>\n";
	foreach ($templates as $name => $tempinfo) {
		echo "<li>Creating template <tt>$name</tt> ... ";
		$DB_site->query('
			INSERT INTO template
			(templateid, templatesetid, templategroupid, title, user_data, parsed_data)
			VALUES
			(NULL, -1, '.$tempinfo['templategroupid'].', "'.addslashes($name).'", "'.addslashes($tempinfo['user_data']).'", "'.addslashes($tempinfo['parsed_data']).'")
		');
		report_mysql_error();
		echo "</li>\n";
	}
	echo '</ul>';
	if (defined('GOTO_NEXT')) {
		echo 'It seems that an error occurred while loading the templates. Only proceed if you are sure these errors are not critical.';
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
		echo '<p align="center">Please fill in the fields below for the various options.<br /></p>';
// CyKuH [WTN]
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
		textrow('<span class="cp_small">The domain name HiveMail is running on, with a preceding \'@\' character.<br />For example: @youdomain.com, @example.net, etc.</span>');
		inputfield('Program location:', 'newoptions[appurl]', 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/') - strlen('install') - 1));
		getclass();
		textrow('<span class="cp_small">The complete URL (without any trailing slashes) of the program, e.g: http://www.example.com/webmail.</span>');
		endtable();

		echo "</td>\n";
		echo "	</tr>\n";
		endform('Submit Options');
		endtable();
		$nofooter = true;
	} else {
		// Update options
		echo "<p>Updating options:\n<ul>\n<li>Application name ... ";
		$DB_site->query('
			UPDATE setting
			SET value = "'.addslashes($newoptions['appname']).'"
			WHERE variable = "appname"
		');
		report_mysql_error(false);
		echo "</li>\n<li>Domain name ... ";
		$DB_site->query('
			UPDATE setting
			SET value = "'.addslashes($newoptions['domainname']).'"
			WHERE variable = "domainname"
		');
		report_mysql_error(false);
		echo "</li>\n<li>Program location ... ";
		$DB_site->query('
			UPDATE setting
			SET value = "'.addslashes($newoptions['appurl']).'"
			WHERE variable = "appurl"
		');
		report_mysql_error(false);
		echo "</li>\n<li>Sender name ... ";
		$DB_site->query('
			UPDATE setting
			SET value = "'.addslashes("Mail Delivery System <mail$newoptions[domainname]>").'"
			WHERE variable = "smtp_errorfrom"
		');
		report_mysql_error(false);
		echo "</li>\n</ul>\n</p>";

		// Create admin
		echo "<p>Creating administrator account ... ";
		$DB_site->query("
			INSERT INTO user
			(userid, username, password, usergroupid, skinid, realname, regdate, lastvisit, cols, question, answer, options, replyto)
			VALUES
			(NULL, '".addslashes($aadmin['username'])."', '".addslashes(md5($aadmin['password']))."', 1, 1, '".addslashes($aadmin['realname'])."', ".TIMENOW.", ".TIMENOW.", '".addslashes('a:6:{i:0;s:8:"priority";i:1;s:6:"attach";i:2;s:4:"from";i:3;s:7:"subject";i:4;s:8:"datetime";i:5;s:4:"size";}')."', '".addslashes($aadmin['question'])."', '".addslashes(md5($aadmin['answer']))."', ".USER_DEFAULTBITS.", '".addslashes("$aadmin[username]$newoptions[domainname]")."')
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
	echo 'Congratulations, you have successfully installed the Web interface of HiveMail nullified by CyKuH [WTN]!<br /><br />
	As an administrator you can log on to the HiveMail Administrator Control Panel, where you can control each and every aspect of the program. To get there, please remove the <tt>/install</tt> folder from your web site first (for security reasons). Once you are you done, <a href="../admin/">click here</a> to proceed to the control panel.<br /><br />
	Installing the Web interface was the first part of installing HiveMail. Now it\'s time install the email gateway, that is - the program that collects mail from your server and delivers it to your users. ';
}

// ############################################################################
// Footer
if ($nofooter !== true) {
	echo "</td>\n";
	echo "	</tr>\n";
	tablehead(array(iif(defined('GOTO_NEXT') and GOTO_NEXT == true, '<a href="index.php?step='.($step + 1).'" class="navlink"><span class="theadlink">&raquo; Continue to next step: '.$steps[($step + 1)].' &raquo;</span></a>', '&nbsp;')));
	endtable();
}

cp_footer(false);
?>