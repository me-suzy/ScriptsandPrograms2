#!/usr/local/bin/php
<?
echo "\n\n";
require ('../web/init.php');
require ('../include/site_design.inc');

$web_system = get_web_system();

$db = &$web_system->get_db();

$query = "SELECT designid FROM site_design";

$results = &$db->single_column($query);
foreach($results as $designid) {
	echo "Looking @ designid " . $designid . "\n";
	$design =& new Site_Design($designid);
	$sections = &$design->design_areas['menu']->sections;
	if (!is_array($sections)) continue;
	for(reset($sections); (null !== ($key = key($sections))); next($sections)) { 
		if (!is_array($under_sections)) continue;
		$under_sections = &$sections[$key];
		for(reset($under_sections); (null !== ($under_key = key($under_sections))); next($under_sections)) {
			if (get_class($under_sections[$under_key]) == 'site_design_area_menu_section_type_stalks') {
				echo 'Setting ' . $designid . '->stalk_image_type to png ... '."\n";
				$under_sections[$under_key]->sub_section->set_var('stalk_image_type', 'png');
				unset($under_sections[$under_key]->sub_section->_custom_vars['stalk_image_type']);
			} else {
				echo get_class($under_sections[$under_key]) . " is not a stalks type.. Skipping\n";
			}
		}
	}
	$packed_string = $design->pack();
	echo "Saving ... " . $designid . "\n";
	$qry = "UPDATE site_design SET design='".addslashes($packed_string) . "' WHERE designid='".$designid."'";
	$db->update($qry);
	echo "Done.\n";
}

global $CACHE;
$CACHE->wipe();

?>
