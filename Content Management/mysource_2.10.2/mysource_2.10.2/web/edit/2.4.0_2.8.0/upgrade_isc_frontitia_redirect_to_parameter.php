<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.4.0_2.8.0/upgrade_isc_frontitia_redirect_to_parameter.php,v $
## $Revision: 1.2.2.1 $
## $Author: brobertson $
## $Date: 2004/05/11 11:29:27 $
#######################################################################
# Initialise
include_once("../../init.php");
#---------------------------------------------------------------------#

include_once('./upgrade_functions.php');

 ######################################
# tell anyone who isn't root .... sorry
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade ISC Frontitia", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade ISC Frontitia", "You must be a <b>root</b> to upgrade the system.",$SESSION->user->login);
	exit();
}

if (!is_file($SYSTEM_ROOT."/xtras/page/templates/ISC_frontitia_redirect/ISC_frontitia_redirect.inc")) {
	report_ignore($_SERVER['SCRIPT_FILENAME']);
	exit();
}

if (is_file($_SERVER['SCRIPT_FILENAME'] . '.success')) {
	echo 'This upgrade has already run. Aborting.<br />';
	exit();
}

if (is_file($_SERVER['SCRIPT_FILENAME'] . '.failure')) {
	unlink($_SERVER['SCRIPT_FILENAME'] . '.failure');
}

?>
Upgrading MySource ISC frontitia redirect.<br><br>
<?

error_reporting(5);
$web = &get_web_system();
$webdb = &$web->get_db();
$users = &get_users_system();
$usersdb = &$users->get_db();
global $CACHE;

$CACHE->wipe();

if(!$_REQUEST['step']) {
	# First step!
	 #########################################################
	# Web DB Changes
	$sql = array();
	$query = "CREATE TABLE xtra_page_template_ISC_frontitia_redirect_backup (
        pageid          MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY,
        to_pageid       MEDIUMINT(9) UNSIGNED,
        to_url          VARCHAR(255),
        extra_url       VARCHAR(255),
        new_window      SET('Y','N'),
        window_options TEXT
        );";

	if(!$webdb->select($query)) {
		# d'oh! can't go on, so die.
		echo "Error - There was a problem creating a backup ISC frontitia redirect table. The rest of the script can't run until this problem is fixed.<p>";
		report_failure($_SERVER['SCRIPT_FILENAME']);
		exit;
	}

	$number = $webdb->single_element("SELECT count(*) from xtra_page_template_ISC_frontitia_redirect");

	if($number) { # yes, we have some data we have to back up.
		$query = "INSERT INTO xtra_page_template_ISC_frontitia_redirect_backup 
			(pageid, to_pageid, to_url, extra_url, new_window, window_options) SELECT sp.pageid, sp.to_pageid, sp.to_url, sp.extra_url, sp.new_window, sp.window_options FROM xtra_page_template_ISC_frontitia_redirect sp";
		if(!$webdb->insert($query)) {
			#d'oh! no point if we can't copy what's in there, huh?
			echo "Error - There was a problem copying the data from the ISC frontitia redirect table to the backup table. The rest of the script can't run until this problem is fixed.<p>";
			report_failure($_SERVER['SCRIPT_FILENAME']);
			exit;
		}
	}

	echo 	"<p>Backup tables created and data copied OK. Starting database changes to ISc frontitia redirect table.";

	$sql[] = "ALTER TABLE xtra_page_template_ISC_frontitia_redirect DROP COLUMN to_pageid";
	$sql[] = "ALTER TABLE xtra_page_template_ISC_frontitia_redirect DROP COLUMN to_url";
	$sql[] = "ALTER TABLE xtra_page_template_ISC_frontitia_redirect DROP COLUMN extra_url";
	$sql[] = "ALTER TABLE xtra_page_template_ISC_frontitia_redirect DROP COLUMN new_window";
	$sql[] = "ALTER TABLE xtra_page_template_ISC_frontitia_redirect DROP COLUMN window_options";
	$sql[] = "ALTER TABLE xtra_page_template_ISC_frontitia_redirect ADD  COLUMN parameters LONGTEXT";
	foreach($sql as $run) {
		if (!$webdb->select($run)) report_failure($_SERVER['SCRIPT_FILENAME']);
	}

	?> 
	<p>The database changes for the ISC frontitia redirect upgrade are done.
	<br>Do you have any serious <b>database errors</b> (e.g. Not including trying to drop a column that doesn't exist)?
	<p>
	<form action="upgrade_isc_frontitia_redirect_to_parameter.php" name='upgrade' method="POST"> 
		<input type=hidden name=step value="">
		<input type="button" name="no" value="No" onClick="document.upgrade.step.value=2; document.upgrade.submit()">
		<input type="button" name="yes" value="Yes" onClick="javascript:alert('sorry, you can\'t go on any further with this upgrade - please check the database backup table exists, the data has been copied correctly and columns for the ISC frontitia redirct table have been changed (there should be only two - pageid and parameters.)'); return false;">
	</form>
	<?		

} elseif ($_REQUEST['step'] == 2) {
	# clear the past sql statements. 
	$sql = array();

	# now get all the rows in there and convert them to parameter set.
	# THIS MIGHT NOT WORK if the first column in your db is not pageid. But we'll get to that....
	$forms = $webdb->associative_array("SELECT * from xtra_page_template_ISC_frontitia_redirect_backup");
	$clean_convert = true;
	foreach($forms as $pageid => $data) {

		$parameters = array();

		$parameters['to_pageid']				= $data['to_pageid'];
		if ($data['to_pageid']) {
			$to_pageid = $data['to_pageid'];
			$siteid = $webdb->single_element("SELECT siteid FROM page WHERE pageid='$to_pageid'");
			$parameters['to_siteid'] = $siteid;
		} else {
			$parameters['to_siteid'] = 0;
		}
		$parameters['to_url']					= $data['to_url'];
		$parameters['extra_url']				= $data['extra_url'];
		$parameters['new_window']				= $data['new_window'];
		$window_array = unserialize($data['window_options']);
		$window_options_array = array();
		if (!empty($window_array)) {
			foreach($window_array as $name => $data) {
				if ($data && $name != 'height' && $name != 'width') {
					array_push($window_options_array, $name);
				}
				if ($name == 'height') {
					$parameters['height'] = $data;
				}
				if ($name == 'width') {
					$parameters['width'] = $data;
				}
			}
			$parameters['window_options'] = $window_options_array;
		}

		if(!$webdb->update("UPDATE xtra_page_template_ISC_frontitia_redirect set parameters='".addslashes(serialize($parameters))."' where pageid='$pageid'")) {
			echo "<b>WARNING: saving page $pageid to parameter set failed. </b><br>";
			$clean_convert = false;
			report_failure($_SERVER['SCRIPT_FILENAME']);
		} else {
			echo "Converted page $pageid successfully.<br>";
		}
	}

	if($clean_convert) {
		echo "All ISC frontitia redirect pages have been converted. Dropping backup table...<p>";
		$webdb->select("DROP TABLE xtra_page_template_ISC_frontitia_redirect_backup");
		report_success($_SERVER['SCRIPT_FILENAME']);
	}
?>
<br>
...upgrade complete.
<?
}

$CACHE->wipe();

?>
