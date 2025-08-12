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

$get_functions = array('record'=>'get_customised_category_record_storage', 'record_list'=>'get_customised_category_record_list_storage', 'editing'=>'get_customised_category_editing_storage', 'search'=>'get_customised_category_search_storage');
$pages = $web_system->get_template_pages('frontitia');
echo "<p>Finding frontitia pages ...</p>";
foreach($pages as $pageid => $pagename) {
	echo "<p>Found $pagename, UPGRADING</p>";
	$frontitia_page = &$web_system->get_page($pageid);
	$frontitia_page_template = $frontitia_page->get_template();
	foreach($get_functions as $tab_name => $get_function) {
		echo "<p>	Searching $tab_name</p>";
		eval("\$storage = &\$frontitia_page_template->$get_function();");
		$storage_keys = array_keys($storage);
		foreach($storage_keys as $cid) {
			$element = $storage[$cid];
			$owners = $element['owners'];
			$formats = array_keys($owners);
			$owner_attribute_format = reset($formats);
			if(!empty($element['ownership_rules_list'])) {
				foreach($element['ownership_rules_list'] as $ruleid => $rule) {
					$storage[$cid]['ownership_rules_list'][$ruleid]['source_user_input'] = '';
					$storage[$cid]['ownership_rules_list'][$ruleid]['attribute_format'] = $owner_attribute_format;
				}
			}
			unset($storage[$cid]['owners']);
		}
		$frontitia_page_template->save_tab_parameters($tab_name);
		echo "<p>	Saving $tab_name</p>";
	}
	echo "<p>	Searching Foreign Key Elements</p>";
	$fk_storage = &$frontitia_page_template->get_value('foreign_key_element_storage');
	if (!empty($fk_storage)) {
		$fk_keys = array_keys($fk_storage);
		foreach($fk_keys as $elementid) {
			$element = $fk_storage[$elementid];
			$owners = $element['owners'];
			$formats = array_keys($owners);
			$owner_attribute_format = reset($formats);
			if(!empty($element['ownership_rules_list'])) {
				foreach($element['ownership_rules_list'] as $ruleid => $rule) {
					$fk_storage[$elementid]['ownership_rules_list'][$ruleid]['source_user_input'] = '';
					$fk_storage[$elementid]['ownership_rules_list'][$ruleid]['attribute_format'] = $owner_attribute_format;
				}
			}
			unset($fk_storage[$elementid]['owners']);
		}
	}
	$frontitia_page_template->save_parameters();
	echo "<p>	Saving foreign key changes</p>";
}
echo '...upgrade complete.';
report_success($_SERVER['SCRIPT_FILENAME']);
?>

