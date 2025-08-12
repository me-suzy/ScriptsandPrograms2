<?
include_once("../../init.php");
#---------------------------------------------------------------------#

 ####################################################################
# tell anyone who isn't root to go away
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade to $UPGRADE_TO", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade to $UPGRADE_TO", "You must be a <b>root</b> to upgrade the system.",$SESSION->user->login);
	exit();
}

include_once('./upgrade_functions.php');

 ######################################
# tell anyone who isn't root .... sorry
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade Frontitia", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade Frontitia", "You must be <b>root</b> to upgrade the system.",$SESSION->user->login);
	exit();
}

if (!is_file($SYSTEM_ROOT."/xtras/page/templates/frontitia/frontitia.inc")) {
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


$web_system = &get_web_system();
$db = &$web_system->get_db();
$pages = $web_system->get_template_pages('frontitia');

echo "Upgrading ...";

foreach($pages as $pageid => $pagename) {
	list($this->id, $tmp) = $db->single_row("SELECT pageid, parameters FROM xtra_page_template_frontitia WHERE pageid='$pageid'");
	$this->parameters = unserialize($tmp);
	list($this->id, $tmp) = $db->single_row("SELECT pageid, parameters FROM xtra_page_template_frontitia WHERE pageid='$pageid'");
	$this->parameters2 = unserialize($tmp);
	$storage = &$this->parameters['foreign_key_element_storage'];
	$storage2 = $this->parameters2['foreign_key_element_storage'];
	foreach($storage2 as $elementid => $element) {
		if (!isset($element['record_element_storage_type']) || ($element['record_element_storage_type'] != '0' && $element['record_element_storage_type'] != '2')) {
			$storage[$elementid]['record_element_storage_type'] = '0';
		}
	}
	echo "<br />Upgraded Frontitia Page";
	if (!$db->update("UPDATE xtra_page_template_frontitia SET parameters='".addslashes(serialize($this->parameters))."' WHERE pageid='$pageid'")) {
		echo 'Upgrade failed.<br />';
		report_failure($_SERVER['SCRIPT_FILENAME']);
		exit();
	}
}
report_success($_SERVER['SCRIPT_FILENAME']);
echo "Upgrade Complete";
?>