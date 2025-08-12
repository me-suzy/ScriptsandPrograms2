<?
include_once('./upgrade_functions.php');
require_once('../../init.php');
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/html_general.inc"); 

$web_system = &get_web_system();
$db = &$web_system->get_db();

 ######################################
# tell anyone who isn't root .... sorry
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade Notitia", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade Notitia", "You must be <b>root</b> to upgrade the system.",$SESSION->user->login);
	exit();
}

if (!is_file($SYSTEM_ROOT."/xtras/web/extensions/notitia/notitia.inc")) {
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

$session = &get_mysource_session();

echo "<b>Please note MySql LOCK TABLES grants will need to be modified. See INSTALL file and read MySQL Requirements for more information on how to grant your MySql user LOCK TABLES grants</b><br /><br >";

# Lets create the table that store the stuff
if (!$db->table_exists('xtra_web_extension_notitia_attribute_role_auto_increment')) {
	$db->select("CREATE TABLE xtra_web_extension_notitia_attribute_role_auto_increment (attributeid INT UNSIGNED NOT NULL, value DOUBLE NOT NULL, PRIMARY KEY(attributeid));");
} // end if - new table exists

// finished
echo '...upgrade complete.';
report_success($_SERVER['SCRIPT_FILENAME']);