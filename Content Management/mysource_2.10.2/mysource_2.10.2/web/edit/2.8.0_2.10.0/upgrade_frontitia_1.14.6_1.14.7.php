<?

include_once('upgrade_template.php');

#---------------------------------------------------------------------#
#
# This script upgrades frontitia to use link2posting keywords on a per category basis

/*
Frontitia version 1.14.6
cvs up -r 1.232 Xtra.info
cvs up -r 1.310 frontitia.inc
cvs up -r 1.90 frontitia.pset
*/

class upgrade_frontitia_template extends upgrade_template {
	function upgrade_header() {
		$this->report .= '<table border="3" bordercolor="black" cellspacing="0">';
	}

	function upgrade_page_template(&$page, &$page_template) {
		$get_functions = array(	'record_list'=>'get_customised_category_record_list_storage',
								'adv_fk' => 'get_foreign_key_element_storage'
						);

		$this->report .= "<tr><td>$page->id</td><td>$page->name</td>";
		foreach($get_functions as $tab_name => $get_function) {
			eval("\$storage = &\$page_template->$get_function();");
			$storage_keys = array_keys($storage);
			foreach($storage_keys as $key) {
				$sub_storage = &$storage[$key];
				$storage_area = &$sub_storage['link2record_attributes'];
				$keys = array_keys($storage_area);
				foreach ($keys as $format) {
					$linking_array = &$storage_area[$format];
					if (empty($linking_array) || $format == 0) continue;
					$pageid = null;
					if ($tab_name == 'record_list') {
						# record list
						$pageid = $linking_array['page'];
					} else {
						# adv FK tab
						$pageid = $sub_storage['pageid'];
						if ($sub_storage['popup']) {
							$linking_array['popup'] = $sub_storage['popup'];
							$linking_array['popup_width'] = $sub_storage['popup_width'];
							$linking_array['popup_height'] = $sub_storage['popup_height'];
							$linking_array['popup_toolbar'] = $sub_storage['popup_toolbar'];
							$linking_array['popup_menubar'] = $sub_storage['popup_menubar'];
							$linking_array['popup_location'] = $sub_storage['popup_location'];
							$linking_array['popup_status'] = $sub_storage['popup_status'];
							$linking_array['popup_scrollbars'] = $sub_storage['popup_scrollbars'];
							$linking_array['popup_resizable'] = $sub_storage['popup_resizable'];
						}

						unset($sub_storage['popup']);
						unset($sub_storage['popup_width']);
						unset($sub_storage['popup_height']);
						unset($sub_storage['popup_toolbar']);
						unset($sub_storage['popup_menubar']);
						unset($sub_storage['popup_location']);
						unset($sub_storage['popup_status']);
						unset($sub_storage['popup_scrollbars']);
						unset($sub_storage['popup_resizable']);
						unset($sub_storage['pageid']);
					}
					if (!$pageid) {
						$pageid = '';
					}
					if (!is_array($linking_array['page'])) {
						$linking_array['page'] = array('page'=>array($pageid),'catid'=>array(''));
					}
					if ($tab_name != 'adv_fk') {
						$page_template->save_tab_parameters($tab_name);
					}
				}
			}
			$this->report .= "<td>$tab_name upgraded</td>";
		}
		$this->report .= '</tr>';
	}

	function upgrade_footer() {
		$this->report .= '</table>';
	}
}

$upgrade_template = new upgrade_frontitia_template('frontitia');
$upgrade_template->run();

?>