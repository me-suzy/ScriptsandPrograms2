<?
include_once("../../init.php");
#---------------------------------------------------------------------#

include_once('./upgrade_functions.php');

$web_system = &get_web_system();
$db = &$web_system->get_db();

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

$dont_skip = true;
$templates = array();
if ($db->table_exists('xtra_page_template_frontitia_template')) {
	if (count($db->get_table_fields('xtra_page_template_frontitia_template', true)) > 1) {
		# Lets save all the data
		$templates = $db->associative_array("SELECT pageid, categoryid, parameters FROM xtra_page_template_frontitia_template");
		$db->select("DROP TABLE xtra_page_template_frontitia_template");
	} else {
		$dont_skip = false;
	}
}
if ($dont_skip) {
	$db->select("CREATE TABLE xtra_page_template_frontitia_template (pageid MEDIUMINT(9) UNSIGNED NOT NULL, templateid MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT, categoryid MEDIUMINT(9) UNSIGNED NOT NULL, parameters  LONGTEXT, PRIMARY KEY(templateid))");

	if (!empty($templates)) {
		foreach($templates as $values) {
			$db->insert("INSERT INTO xtra_page_template_frontitia_template (pageid, categoryid, parameters) VALUES ('".$values['pageid']."', '".$values['categoryid']."', '".$values['parameters']."')");
		}
	}
}

$exporters = array();
if ($db->table_exists('xtra_page_template_frontitia_exporter')) {
	if (count($db->get_table_fields('xtra_page_template_frontitia_exporter', true)) > 1) {
		# Lets save all the data
		$exporters = $db->associative_array("SELECT pageid, categoryid, parameters FROM xtra_page_template_frontitia_exporter");
		$db->select("DROP TABLE xtra_page_template_frontitia_exporter");
	} else {
		echo '...upgrade complete.';
		report_success($_SERVER['SCRIPT_FILENAME']);
		exit();
	}
}

$db->select("CREATE TABLE xtra_page_template_frontitia_exporter (pageid MEDIUMINT(9) UNSIGNED NOT NULL, exporterid MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT, categoryid MEDIUMINT(9) UNSIGNED NOT NULL, parameters LONGTEXT, PRIMARY KEY(exporterid))");

if (!empty($exporters)) {
	foreach($exporters as $values) {
		$db->insert("INSERT INTO xtra_page_template_frontitia_exporter (pageid, categoryid, parameters) VALUES ('".$values['pageid']."', '".$values['categoryid']."', '".$values['parameters']."')");
	}
}

echo '...upgrade complete.';
report_success($_SERVER['SCRIPT_FILENAME']);
?>