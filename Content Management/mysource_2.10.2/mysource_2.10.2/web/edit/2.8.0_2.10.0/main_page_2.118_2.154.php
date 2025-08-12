<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.8.0_2.10.0/main_page_2.118_2.154.php,v $
## $Revision: 1.2 $
## $Author: brobertson $
## $Date: 2004/03/10 16:26:04 $
#######################################################################
# Initialise
include_once("../../init.php");
#---------------------------------------------------------------------#

include_once('./upgrade_functions.php');

$session = &get_mysource_session();

 ######################################
# tell anyone who isn't root .... sorry
if (!$session->logged_in()) {
	$session->login_screen("Upgrade Page for Page Notes", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$session->login_screen("Upgrade Page for Page Notes", "You must be <b>root</b> to upgrade the system.",$session->user->login);
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
Upgrading Page for Page Notes.<br><br>
<?
error_reporting(5);
global $CACHE;

$web = &get_web_system();
$webdb = &$web->get_db();

$sql = "ALTER TABLE page ADD COLUMN page_notes TEXT NOT NULL";
$webdb->select($sql);

report_success($_SERVER['SCRIPT_FILENAME']);

$CACHE->wipe();

echo "Done<p>";

?>
