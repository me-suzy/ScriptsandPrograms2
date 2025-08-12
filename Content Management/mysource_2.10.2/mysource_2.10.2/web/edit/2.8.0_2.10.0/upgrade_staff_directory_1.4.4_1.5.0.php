<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.8.0_2.10.0/upgrade_staff_directory_1.4.4_1.5.0.php,v $
## $Revision: 1.1 $
## $Author: brobertson $
## $Date: 2004/01/06 15:52:07 $
#######################################################################
# Initialise
include_once("../../init.php");
#---------------------------------------------------------------------#

include_once('./upgrade_functions.php');

$session = &get_mysource_session();

 ######################################
# tell anyone who isn't root .... sorry
if (!$session->logged_in()) {
	$session->login_screen("Upgrade Staff Directory", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$session->login_screen("Upgrade Staff Directory", "You must be <b>root</b> to upgrade the system.",$session->user->login);
	exit();
}

if (!is_file($SYSTEM_ROOT."/xtras/page/templates/staff_directory/staff_directory.inc")) {
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
Upgrading MySource Staff Directory.<br><br>
<?
error_reporting(5);
$web = &get_web_system();
$webdb = &$web->get_db();
$users = &get_users_system();
$usersdb = &$users->get_db();
global $CACHE;

require_once $SYSTEM_ROOT."/xtras/page/templates/staff_directory/staff_directory.inc";

$sql = 'SELECT pageid
		FROM xtra_page_template_staff_directory';

$pageids = $webdb->single_column($sql);

if (is_array($pageids)) {
	foreach($pageids as $pageid) {

		echo "Updating Staff Directory Page #$pageid<br>";

		$template = new Page_Template_Staff_Directory($pageid);
		$template->set_value('forbid_empty_searches', '0');
		$template->set_value('text_prev_page', '&lt;&lt; Prev %prev_count%');
		$template->set_value('text_next_page', 'Next %next_count% &gt;&gt;');
		$template->set_value('text_new_search', 'New Search');
		$template->set_value('text_results_summary', 'Displaying Results %display_start% - %display_end% / %total_count%');


	}// end if
}// end if

echo "<p>All Staff Directory pages have been updated.";
report_success($_SERVER['SCRIPT_FILENAME']);

?>
