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
// | $RCSfile: upgrade.php,v $ - $Revision: 1.32 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

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
cp_header(' &raquo; Upgrade Script');

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
// Checks if the values of the $array are all empty
if (!function_exists('array_empty')) {
	function array_empty($array) {
		foreach ($array as $value) {
			if (!empty($value)) {
				return false;
			}
		}
		return true;
	}
}

// ############################################################################
// Connect to database
$DB_site = new DB_MySQL($config);
$DB_site->setup_options();
$DB_site->showerror = false;

// ############################################################################
// Define versions
$versions = array(
	0 => '1.0',
	1 => '1.1',
	2 => '1.1.1',
	3 => '1.2',
	4 => '1.2.2',
	5 => '1.3'
);
//default_var($version, '1.0');	// Always 1.0, do not change

// ############################################################################
// Define steps
$steps = array(
	1 => 'Table Alteration',
	2 => 'Data Manipulation',
	3 => 'Populate Tables',
	4 => 'Update Template Information',
	5 => 'Complete Upgrade',
);

// ############################################################################
// Header
echo '<div align="left" style="padding: 10px;"><b><div align="center"><span style="font-size: 20px;">HiveMail&trade; Version '.HIVEVERSION.'</span><br /><!--CyKuH [WTN]-->Nullified by WTN Team `2004<br />'.iif(is_numeric($step), "Step $step of ".count($steps).": $steps[$step]", 'Welcome to the HiveMail&trade; Upgrade Script').'</b></div><br /><br />';

// ############################################################################
// Welcome screen
if (!is_numeric($step)) {
	echo '<p>Welcome to the HiveMail&trade; upgrade script!<br />
	This script will guide you through the process of upgrading HiveMail&trade;. These are the steps you will be going through in this script:
	<ul type="1">';
	foreach ($steps as $stepname) {
		echo "<li>$stepname</li>\n";
	}
	echo '</ul>
	It is very important that you follow the on-screen instructions precisely and not skip over any steps.<br />
	This script will shut down HiveMail&trade; when you proceed to the first step, and restart the system once you reach the final step.</p>';

	echo "<p>Please choose below the version you are <i>currently</i> using:\n<ul>\n";
	foreach ($versions as $vercode => $vernum) {
		if ($vernum == HIVEVERSION) {
			break;
		}

		echo '<li>'."Version $vernum: <a href=\"upgrade.php?upgradeto=".($vercode+1)."&step=1\">click here</a>."."</li>\n";
	}
	echo "</ul>";
	echo '</p>';
}

// ############################################################################
// Table Alteration
if ($step == 1) {
	// Shut down system
	$DB_site->query('
		UPDATE hive_setting
		SET value = 1
		WHERE variable = "maintain"
	');

	$tables = array();
	foreach ($versions as $vercode => $vernum) {
		if ($vercode < $upgradeto) {
			continue;
		}

		$filename = "sql_upgrade_$vercode.php";
		if (file_exists("./$filename") and is_readable("./$filename")) {
			require_once("./$filename");
		} else {
			die('<span class="cp_temp_edit"><b>ERROR</b></span>: Cannot find or read <tt>'.$filename.'</tt>. Please ensure that it is in the <tt>/install</tt> directory and refresh the page.');
		}
	}

	if (!array_empty($tables)) {
		echo "<p>The script will now alter the table structure in your database:\n<ul>\n";
		foreach ($tables as $forversion => $realtables) {
			echo '<li>Performing changes from '.$versions[($forversion-1)].' to '.$versions[$forversion].":\n<ul>\n";
			foreach ($realtables as $tablename => $tablecreate) {
				echo "<li>Altering table <tt>$tablename</tt> ... ";
				$DB_site->query($tablecreate);
				report_mysql_error();
				echo "</li>\n";
			}
			echo "\n</ul></li>\n";
		}
		echo '</ul>';
	} else {
		echo 'No changes were made to the database schema. Please proceed to the next step.';
	}

	if (defined('GOTO_NEXT')) {
		echo 'It seems that at least one error occurred while altering the table structure. Only proceed if you are sure these errors are not critical.';
	} else {
		define('GOTO_NEXT', true);
	}
	echo '</p>';
}

// ############################################################################
// PHP stuff
if ($step == 2) {
	if (false and $upgradeto < 6) {
		$start = iif(empty($start), 0, $start);
		$perpage = iif(empty($perpage), 10, $perpage);
		if (empty($total)) {
			$count = $DB_site->query("
				SELECT *
				FROM hive_iplog
				GROUP BY userid
				ORDER BY datefirstseen ASC
			");
			$total = $DB_site->num_rows($count);
		}
		$ips = $DB_site->query("
			SELECT ipaddr, userid
			FROM hive_iplog
			GROUP BY userid
			ORDER BY datefirstseen ASC
			LIMIT $start, $perpage
		");
		$i = 0;
		echo "<p>The script will now update the system data in your database and populate it with new information:\n<ul>\n";
		while ($ip = $DB_site->fetch_array($ips)) {
			$i++;
			echo "<li>Updating user ID: <tt>$ip[userid]</tt> ...";
			$DB_site->query("
				UPDATE user
				SET regipaddr = $ip[ipaddr]
				WHERE userid = $ip[userid]
			");
			report_mysql_error();
		}
		if ($start < $total and !defined('GOTO_NEXT')) {
			$start = $start + $perpage;
			define('GOTO_LOOP', true);
		}
	} else {
		echo 'No changes are required to your system information. Please proceed to the next step.';
	}
	if (defined('GOTO_NEXT')) {
		echo 'It seems that at least one error occurred while updating the data in the database. Only proceed if you are sure these errors are not critical.';
	} elseif (!defined('GOTO_LOOP')) {
		define('GOTO_NEXT', true);
	}
	echo '</p>';
}

// ############################################################################
// Populate Tables
if ($step == 3) {
	$inserts = array();
	foreach ($versions as $vercode => $vernum) {
		if ($vercode < $upgradeto) {
			continue;
		}

		$filename = "sql_upgrade_$vercode.php";
		if (file_exists("./$filename") and is_readable("./$filename")) {
			require_once("./$filename");
		} else {
			die('<span class="cp_temp_edit"><b>ERROR</b></span>: Cannot find or read <tt>'.$filename.'</tt>. Please ensure that it is in the <tt>/install</tt> directory and refresh the page.');
		}
	}

	if (!array_empty($inserts)) {
		echo "<p>The script will now update the system data in your database and populate it with new information:\n<ul>\n";
		foreach ($inserts as $forversion => $realinserts) {
			echo '<li>Performing changes from '.$versions[($forversion-1)].' to '.$versions[$forversion].":\n<ul>\n";
			foreach ($realinserts as $insert) {
				if (substr($insert, 0, strlen('EVAL ')) == 'EVAL ') {
					$insert = substr($insert, strlen('EVAL '));
					eval('$insert = "'.str_replace('"', '\"', $insert).'";');
				}
				$isinsert = (substr($insert, 0, strlen('INSERT INTO ')) == 'INSERT INTO ');
				$tablename = substr($insert, strlen(iif($isinsert, 'INSERT INTO ', 'UPDATE ')));
				$tablename = substr($tablename, 0, strpos($tablename, ' '));
				echo '<li>'.iif($isinsert, 'Adding data to', 'Updating data in')." <tt>$tablename</tt> ... ";
				$DB_site->query($insert);
				report_mysql_error();
				echo "</li>\n";
			}
			echo "\n</ul></li>\n";
		}
		echo '</ul>';
	} else {
		echo 'Your database is already up-to-date. Please proceed to the next step.';
	}

	if (defined('GOTO_NEXT')) {
		echo 'It seems that at least one error occurred while updating the data in the database. Only proceed if you are sure these errors are not critical.';
	} else {
		define('GOTO_NEXT', true);
	}
	echo '</p>';
}

// ############################################################################
// Update Template Information
if ($step == 4) {
	$templates = array();
	foreach ($versions as $vercode => $vernum) {
		if ($vercode < $upgradeto) {
			continue;
		}

		$filename = "templates_upgrade_$vercode.php";
		if (file_exists("./$filename") and is_readable("./$filename")) {
			require_once("./$filename");
		} else {
			die('<span class="cp_temp_edit"><b>ERROR</b></span>: Cannot find or read <tt>'.$filename.'</tt>. Please ensure that it is in the <tt>/install</tt> directory and refresh the page.');
		}
	}

	if (!array_empty($templates)) {
		$upgradetemplates = array();
		echo "<p>The script will now load the database with the new default templates:\n<ul>\n";
		foreach ($templates as $forversion => $realtemplates) {
			echo '<li>Performing changes from '.$versions[($forversion-1)].' to '.$versions[$forversion].":\n<ul>\n";
			foreach ($realtemplates as $name => $tempinfo) {
				$DB_site->query('
					DELETE FROM hive_template
					WHERE title = "'.addslashes($name).'" AND templatesetid = -1
				');
				echo '<li>'.iif(mysql_affected_rows() == 0, 'Creating', 'Updating')." template <tt>$name</tt> ... ";
				$DB_site->query('
					INSERT INTO hive_template
					(templateid, templatesetid, templategroupid, title, user_data, parsed_data)
					VALUES
					(NULL, -1, '.$tempinfo['templategroupid'].', "'.addslashes($name).'", "'.addslashes($tempinfo['user_data']).'", "'.addslashes($tempinfo['parsed_data']).'")
				');
				report_mysql_error();
				echo "</li>\n";
				$upgradetemplates[] = '"'.addslashes($name).'"';
			}
			echo "\n</ul></li>\n";
		}
		echo '</ul>';
		$DB_site->query('
			UPDATE hive_template
			SET upgraded = 1
			WHERE title IN ('.implode(', ', $upgradetemplates).') AND templatesetid <> -1
		');
	} else {
		echo 'No changes were made to the templates. Please proceed to the next step.';
	}

	if (defined('GOTO_NEXT')) {
		echo 'It seems that at least one error occurred while loading the templates. Only proceed if you are sure these errors are not critical.';
	} else {
		define('GOTO_NEXT', true);
	}
	echo '</p>';
}


// ############################################################################
// Complete Upgrade
if ($step == 5) {
	$DB_site->query('
		UPDATE setting
		SET value = "'.addslashes(HIVEVERSION).'"
		WHERE variable = "versionnum"
	');
	// Restart system
	$DB_site->query('
		UPDATE hive_setting
		SET value = 0
		WHERE variable = "maintain"
	');

	echo 'Congratulations, you have successfully upgraded your installation of HiveMail&trade; and are now running version 1.3 Beta 2 (English)!<br /><br />
	Please remove the <tt>/install</tt> folder from your web site (for security reasons), and proceed to the control panel <a href="../admin/">here</a>. We strongly recommend that you revert any templates that were changed in the upgrade process, you can do <a href="../admin/template.php?cmd=upgrade">here</a>.';
}

// ############################################################################
// Footer
if (defined('GOTO_NEXT') and GOTO_NEXT == true) {
	starttable('', '90%', true, 2, false, 4, false);
	tablehead(array('<a href="upgrade.php?upgradeto='.$upgradeto.'&step='.($step + 1).'">&raquo; Continue to next step: '.$steps[($step + 1)].' &raquo;</a>'));
	endtable();
} elseif (defined('GOTO_LOOP') and GOTO_LOOP == true) {
	starttable('', '90%', true, 2, false, 4, false);
	tablehead(array("<script type=\"text/javascript\">\n" . "window.location=\"upgrade.php?upgradeto=$upgradeto&step=$step&total=$total&start=$start&perpage=$perpage\";" . "\n</script>\n" . '<a href="upgrade.php?upgradeto='.$upgradeto.'&step='.$step.'&start='.$start.'">&raquo; Continue processing &raquo;</a>', '&nbsp;'));
	endtable();
}

cp_footer();
?>