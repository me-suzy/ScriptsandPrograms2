<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.8.0_2.10.0/main.php,v $
## $Revision: 1.6 $
## $Author: brobertson $
## $Date: 2004/03/16 10:43:24 $
#######################################################################
require_once('../../init.php');

 ####################################################################
# tell anyone who isn't root to go away
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade Main", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade Main", "You must be a <b>root</b> to upgrade the system.",$SESSION->user->login);
	exit();
}

include_once('./upgrade_functions.php');

if (is_file($_SERVER['PHP_SELF'] . '.success')) {
	echo 'This upgrade has already run. Aborting.<br />';
	exit();
}

if (is_file($_SERVER['PHP_SELF'] . '.failure')) {
	unlink($_SERVER['PHP_SELF'] . '.failure');
}

$web = &get_web_system();
$webdb = &$web->get_db();

$users = &get_users_system();
$usersdb = &$users->get_db();

global $CACHE;

 #########################################################
# Web DB Changes
$sql = array();
$sql[] = "ALTER TABLE file ADD COLUMN replaceid INT DEFAULT 0";
$sql[] = "CREATE TABLE mysource_help (helpid mediumint(9) NOT NULL auto_increment, feature_name varchar(127) NOT NULL default '', feature_help_text longtext NOT NULL, PRIMARY KEY  (helpid), UNIQUE KEY feature_name (feature_name)) TYPE=MyISAM;";
$sql[] = "INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('Name', 'Defines the name of the page.');";
$sql[] = "INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('Currently', 'Shows you the current status of this page.');";
$sql[] = "INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('Site Name', 'Name of this site. This name can appear in the frontend, but it\'s used in the backend mainly.\r\n<hr>\r\nName dieser Site. Dieser Name kann im Frontend angezeigt werden, hauptsächlich wird er aber im Backend benützt.');";
$sql[] = "INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('Web', 'The web tab.');";
$sql[] = "INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('Find:(login/email/name)', 'Type a users name, login name or email address and click the magnifying glass to find the user.\r\n\r\n<hr>\r\n\r\nUm einen User zu finden, dessen Namen, Login-Namen oder Email-Adresse eingeben. Ein Klick auf die Lupe started die Suche.');";
$sql[] = "INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('Create New Pages?', 'To add new subpages to the current page (or site, if you want to create main pages), type the names of the new pages in to the field. One page per line. \r\nYou can select the desired template of the new pages here. But you can also change the template of every single page later.\r\n\r\n<hr>\r\n\r\nErstellt neue Subpages in der aktuellen Page. Tippen die Namen der neuen Pages in das grosse Eingabefeld (eine Page pro Linie!).\r\nIm Auswahlfeld können Sie das Template der neuen Pages bestimmen. Dieses kann aber nachher bei jeder Page einzeln noch geändert werden.');";
$sql[] = "INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('News from mysource.squiz.net', 'What about an english course?');";

foreach($sql as $run) $webdb->select($run);

# User database
$sql = array();

foreach($sql as $run) $usersdb->select($run);

report_success($_SERVER['SCRIPT_FILENAME']);

$CACHE->wipe();

?>
