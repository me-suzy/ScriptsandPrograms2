<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.4.0_2.8.0/upgrade_sub_page_to_parameter.php,v $
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
	$SESSION->login_screen("Upgrade Sub Page", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade Sub Page", "You must be a <b>root</b> to upgrade the system.",$SESSION->user->login);
	exit();
}
if (!is_file($SYSTEM_ROOT."/xtras/page/templates/sub_page/sub_page.inc")) {
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
Upgrading MySource Sub-page Listing.<br><br>
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
	$query = "CREATE TABLE xtra_page_template_sub_page_backup (
	  pageid              MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY,
	  title               VARCHAR(128),
	  bodycopy            LONGTEXT,
	  page_copy           LONGTEXT,
	  number_per_row      SMALLINT UNSIGNED DEFAULT '1',
	  number_per_page     SMALLINT UNSIGNED DEFAULT '1',
	  position            CHAR(1)  DEFAULT 'b',
	  vertical_divider    CHAR(1)  DEFAULT '0',
	  horizontal_divider  CHAR(1)  DEFAULT '0',
	  link_colour         CHAR(6)  DEFAULT '',
	  use_anchors         ENUM('0', '1')  DEFAULT '0',
	  popup_window          CHAR(1) DEFAULT '0',
	  popup_window_menu     CHAR(1) DEFAULT '0',
	  popup_window_tool     CHAR(1) DEFAULT '0',
	  popup_window_width    VARCHAR(5),
	  popup_window_height   VARCHAR(5),
	  popup_window_resize   CHAR(1) DEFAULT '0',
	  popup_window_status   CHAR(1) DEFAULT '0',
	  popup_window_scroll   CHAR(1) DEFAULT '0',
	  popup_window_location CHAR(1) DEFAULT '0'
	)";

	if(!$webdb->select($query)) {
		# d'oh! can't go on, so die.
		echo "Error - There was a problem creating a backup sub_page table. The rest of the script can't run until this problem is fixed.<p>";
		report_failure($_SERVER['SCRIPT_FILENAME']);
		exit;
	}

	$number = $webdb->single_element("SELECT count(*) from xtra_page_template_sub_page");

	if($number) { # yes, we have some data we have to back up.
		$query = "INSERT INTO xtra_page_template_sub_page_backup 
			(pageid, title, bodycopy, page_copy, number_per_row, number_per_page, position, vertical_divider, horizontal_divider, link_colour, use_anchors, popup_window, 
			popup_window_menu, popup_window_tool, popup_window_width, popup_window_height, popup_window_resize, popup_window_status, popup_window_scroll, popup_window_location) 
			SELECT sp.pageid, sp.title, sp.bodycopy, sp.page_copy, sp.number_per_row, sp.number_per_page, sp.position, sp.vertical_divider, sp.horizontal_divider, sp.link_colour, 
			sp.use_anchors, sp.popup_window, sp.popup_window_menu, sp.popup_window_tool, sp.popup_window_width, sp.popup_window_height, sp.popup_window_resize, sp.popup_window_status, 
			sp.popup_window_scroll, sp.popup_window_location 
			FROM xtra_page_template_sub_page sp";
		if(!$webdb->insert($query)) {
			#d'oh! no point if we can't copy what's in there, huh?
			echo "Error - There was a problem copying the data from the subpage listing table to the backup table. The rest of the script can't run until this problem is fixed.<p>";
			report_failure($_SERVER['SCRIPT_FILENAME']);
			exit;
		}
	}

	echo 	"<p>Backup tables created and data copied OK. Starting database changes to subpage table.";

	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN title";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN bodycopy";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN page_copy";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN number_per_row";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN number_per_page";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN position";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN vertical_divider";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN horizontal_divider";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN link_colour";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN use_anchors";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN popup_window";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN popup_window_menu";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN popup_window_tool";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN popup_window_width";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN popup_window_height";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN popup_window_resize";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN popup_window_status";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN popup_window_scroll";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP COLUMN popup_window_location";
	$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD  COLUMN parameters LONGTEXT";
	foreach($sql as $run) $webdb->select($run);

	?> 
	<p>The database changes for the subpage upgrade are done.
	<br>Do you have any serious <b>database errors</b> (e.g. Not including trying to drop a column that doesn't exist)?
	<p>
	<form action="upgrade_sub_page_to_parameter.php" name='upgrade' method="POST"> 
		<input type=hidden name=step value="">
		<input type="button" name="no" value="No" onClick="document.upgrade.step.value=2; document.upgrade.submit()">
		<input type="button" name="yes" value="Yes" onClick="javascript:alert('sorry, you can\'t go on any further with this upgrade - please check the database backup table exists, the data has been copied correctly and columns for the sub_page table have been changed (there should be only two - pageid and parameters.)'); return false;">
	</form>
	<?		

} elseif ($_REQUEST['step'] == 2) {
	# clear the past sql statements. 
	$sql = array();

	# now get all the rows in there and convert them to parameter set.
	# THIS MIGHT NOT WORK if the first column in your db is not pageid. But we'll get to that....
	$forms = $webdb->associative_array("SELECT * from xtra_page_template_sub_page_backup");
	$clean_convert = true;
	include_once("$SQUIZLIB_PATH/form/form.inc");
	include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");
	foreach($forms as $pageid => $data) {

		$parameters = array();

		$parameters['title']					= $data['title'];
		$parameters['bodycopy']					= $data['bodycopy'];
		$parameters['page_copy']				= $data['page_copy'];
		$parameters['number_per_row']			= $data['number_per_row'];
		$parameters['number_per_page']			= $data['number_per_page'];
		$parameters['position']					= $data['position'];
		$parameters['vertical_divider']			= $data['vertical_divider'];
		$parameters['horizontal_divider']		= $data['horizontal_divider'];
		$parameters['link_colour']				= $data['link_colour'];
		$parameters['use_anchors']				= $data['use_anchors'];
		$parameters['popup_window']['on']		= $data['popup_window'];
		$parameters['popup_window']['menu']		= $data['popup_window_menu'];
		$parameters['popup_window']['tool']		= $data['popup_window_tool'];
		$parameters['popup_window']['width']	= $data['popup_window_width'];
		$parameters['popup_window']['height']	= $data['popup_window_height'];
		$parameters['popup_window']['resize']	= $data['popup_window_resize'];
		$parameters['popup_window']['status']	= $data['popup_window_status'];
		$parameters['popup_window']['scroll']	= $data['popup_window_scroll'];
		$parameters['popup_window']['location']	= $data['popup_window_location'];

		if(!$webdb->update("UPDATE xtra_page_template_sub_page set parameters='".addslashes(serialize($parameters))."' where pageid='$pageid'")) {
			echo "<b>WARNING: saving page $pageid to parameter set failed. </b><br>";
			$clean_convert = false;
			report_failure($_SERVER['SCRIPT_FILENAME']);
		} else {
			echo "Converted page $pageid successfully.<br>";
		}
	}

	if($clean_convert) {
		echo "All subpage pages have been converted. Dropping backup table...<p>";
		$webdb->select("DROP TABLE xtra_page_template_sub_page_backup");
		report_success($_SERVER['SCRIPT_FILENAME']);
	}
?>
<br>
...upgrade complete.
<?
}

$CACHE->wipe();

?>
