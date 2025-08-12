<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.8.0_2.10.0/main_site_2.90_2.91.php,v $
## $Revision: 1.1.2.1 $
## $Author: achadszinow $
## $Date: 2004/04/20 00:18:26 $
#######################################################################
# Initialise
include_once("../../init.php");
#---------------------------------------------------------------------#

include_once('./upgrade_functions.php');

$session = &get_mysource_session();

 ######################################
# tell anyone who isn't root .... sorry
if (!$session->logged_in()) {
	$session->login_screen("Upgrade Site for Sub Page Auto Order", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$session->login_screen("Upgrade Site for Sub Page Auto Order", "You must be <b>root</b> to upgrade the system.",$session->user->login);
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
Upgrading Site for Sub Page Auto Order.<br><br>
<?
error_reporting(5);
global $CACHE;

$web = &get_web_system();
$webdb = &$web->get_db();

$sql = "ALTER TABLE site ADD COLUMN subpage_auto_order VARCHAR(2) NULL";
$webdb->select($sql);
$sql = "CREATE TABLE wizard_server_job (jobid MEDIUMINT(8) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, status ENUM('N','R','F','A') NOT NULL DEFAULT 'N', userid MEDIUMINT(8) UNSIGNED NOT NULL, last_updated DATETIME NOT NULL, job_type VARCHAR(255) NOT NULL, parameters LONGTEXT NOT NULL, caller_type VARCHAR(255) NOT NULL, callerid MEDIUMINT(8) NOT NULL, message LONGTEXT NOT NULL, taskid VARCHAR(255) NOT NULL)";
$webdb->select($sql);

report_success($_SERVER['SCRIPT_FILENAME']);

$CACHE->wipe();

echo "Done<p>";

?>
