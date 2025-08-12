<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.4.0_2.8.0/upgrade_standard_to_parameter.php,v $
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
	$SESSION->login_screen("Upgrade Standard", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade Standard", "You must be a <b>root</b> to upgrade the system.",$SESSION->user->login);
	exit();
}

if (!is_file($SYSTEM_ROOT."/xtras/page/templates/standard/standard.inc")) {
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
Upgrading MySource Standard Listing.<br><br>
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
	$query = "CREATE TABLE xtra_page_template_standard_backup (
              pageid      MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY,
              title       VARCHAR(128),
              bodycopy    LONGTEXT
        );";

	if(!$webdb->select($query)) {
		# d'oh! can't go on, so die.
		echo "Error - There was a problem creating a backup standard table. The rest of the script can't run until this problem is fixed.<p>";
		report_failure($_SERVER['SCRIPT_FILENAME']);
		exit;
	}

	$number = $webdb->single_element("SELECT count(*) from xtra_page_template_standard");

	if($number) { # yes, we have some data we have to back up.
		$query = "INSERT INTO xtra_page_template_standard_backup 
			(pageid, title, bodycopy) SELECT sp.pageid, sp.title, sp.bodycopy FROM xtra_page_template_standard sp";
		if(!$webdb->insert($query)) {
			#d'oh! no point if we can't copy what's in there, huh?
			echo "Error - There was a problem copying the data from the standard table to the backup table. The rest of the script can't run until this problem is fixed.<p>";
			report_failure($_SERVER['SCRIPT_FILENAME']);
			exit;
		}
	}

	echo 	"<p>Backup tables created and data copied OK. Starting database changes to standard table.";

	$sql[] = "ALTER TABLE xtra_page_template_standard DROP COLUMN title";
	$sql[] = "ALTER TABLE xtra_page_template_standard DROP COLUMN bodycopy";
	$sql[] = "ALTER TABLE xtra_page_template_standard ADD  COLUMN parameters LONGTEXT";
	foreach($sql as $run) $webdb->select($run);

	?> 
	<p>The database changes for the standard upgrade are done.
	<br>Do you have any serious <b>database errors</b> (e.g. Not including trying to drop a column that doesn't exist)?
	<p>
	<form action="upgrade_standard_to_parameter.php" name='upgrade' method="POST"> 
		<input type=hidden name=step value="">
		<input type="button" name="no" value="No" onClick="document.upgrade.step.value=2; document.upgrade.submit()">
		<input type="button" name="yes" value="Yes" onClick="javascript:alert('sorry, you can\'t go on any further with this upgrade - please check the database backup table exists, the data has been copied correctly and columns for the standard table have been changed (there should be only two - pageid and parameters.)'); return false;">
	</form>
	<?		

} elseif ($_REQUEST['step'] == 2) {
	# clear the past sql statements. 
	$sql = array();

	# now get all the rows in there and convert them to parameter set.
	# THIS MIGHT NOT WORK if the first column in your db is not pageid. But we'll get to that....
	$forms = $webdb->associative_array("SELECT * from xtra_page_template_standard_backup");
	$clean_convert = true;

	include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");

	foreach($forms as $pageid => $data) {

		$parameters = array();

		$parameters['title']					= $data['title'];
		$parameters['bodycopy']					= $data['bodycopy'];

		if(!$webdb->update("UPDATE xtra_page_template_standard set parameters='".addslashes(serialize($parameters))."' where pageid='$pageid'")) {
			echo "<b>WARNING: saving page $pageid to parameter set failed. </b><br>";
			$clean_convert = false;
		} else {
			echo "Converted page $pageid successfully.<br>";
		}
	}

	if($clean_convert) {
		echo "All standard pages have been converted. Dropping backup table...<p>";
		$webdb->select("DROP TABLE xtra_page_template_standard_backup");
		report_success($_SERVER['SCRIPT_FILENAME']);
	} else {
		report_failure($_SERVER['SCRIPT_FILENAME']);
	}
?>
<br>
...upgrade complete.
<?
}
$CACHE->wipe();

?>
