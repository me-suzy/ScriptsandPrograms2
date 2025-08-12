<?
ini_set('memory_limit', '128M');
include_once("../../init.php");
#---------------------------------------------------------------------#

include_once('./upgrade_functions.php');

$web_system = &get_web_system();
$db = &$web_system->get_db();

# http://beta.squiz.net/isc/_edit/2.8.0_2.10.0/upgrade_frontitia_1.10.8_1.11.0.php?siteid=9

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

if (is_file($_SERVER['SCRIPT_FILENAME'] . '.failure')) {
	unlink($_SERVER['SCRIPT_FILENAME'] . '.failure');
}

$upgrade_array = $SESSION->get_var('frontitia_upgrade_script_1_11_0');
if (empty($upgrade_array)) {
	# First time running
	$upgrade_array = array();
	$upgrade_array['running'] = 1;
	$siteid = $_GET['siteid'];
	if (!$siteid) {
		$siteid = '';
	}

	# Get all frontitia pages cause we need to update em all
	$pages = &$web_system->get_template_pages('frontitia', $siteid);
	$msgs = array();
	
	$msgs[] = "<p><b>This version of Frontitia has many new permissions features and this script will attempt to guess the settings that must be set to keep your Frontitia performing the way you would expect it. Everytime a guess is needed to be made a description of the new feature it's setting will coming in the messages and will tell you what it set it to. It will also ask you to verify the guess. Please keep this list by copying and pasting it into some sort of text document for future reference. If you find you require assistance it will help the person assisting you.</b></p>";

} else {
	$pages = $upgrade_array['pages'];
	$msgs = $upgrade_array['msgs'];
}

$left_to_do = $pages;
$do_count = 0;
foreach($pages as $pageid => $pagename) {
	$frontitia_page = &$web_system->get_page($pageid);
	$frontitia_page_template = &$frontitia_page->get_template();
	echo "<p>Found $pagename from Site ID $frontitia_page->siteid UPGRADING</p>";
	$msgs[] = "<p>Found $pagename from Site ID $frontitia_page->siteid UPGRADING</p>";

	# UPGRADE AND SET PERMISSIONS ON THE CONFIG TAB
	# Check if we need to set delete checks
	$delete_grants = $frontitia_page_template->parameters['permissions']['delete_grants'];
	if (!empty($delete_grants['approved']) || !empty($delete_grants['pending'])) {
		$delete_checks = $frontitia_page_template->parameters['permissions']['delete_checks'];
		if (!is_array($delete_checks)) {
			$delete_checks = array();
		}
		# Make status check a delete check
		$delete_checks[] = 's';
# ISC SHOULDN"T USE ANY APPROVAL
		$msgs[] = "<p>For $pagename it was determined you were using delete approval checking. This script has decided in the Configuration tab to set that this Frontitia should check delete approvals.</p>";
		$frontitia_page_template->parameters['permissions']['delete_checks'] = $delete_checks;
	}
	# Unset a bunch of things so we don't blow the memory limit
	unset($delete_grants);
	unset($delete_checks);

	# Check if we need to set edit checks (also includes view check and view permission since viewing checking is different and new)
	$editor_grants = $frontitia_page_template->parameters['permissions']['editor_grants'];
	if (!empty($editor_grants['approved']) || !empty($editor_grants['pending'])) {
		$edit_checks = $frontitia_page_template->parameters['permissions']['edit_checks'];
		if (!is_array($edit_checks)) {
			$edit_checks = array();
		}
		# Make status check an edit check
		$edit_checks[] = 's';
# ISC SHOULDN"T USE ANY APPROVAL
		$msgs[] = "<p>For $pagename it was determined you were using edit approval checking. This script has decided in the Configuration tab to set that this Frontitia should check edit approvals.</p>";
		# At this point we know these guys are using approval so for viewing checks so probably the same editing rules apply to viewing in terms of who can see pending records
		$view_grants = $frontitia_page_template->parameters['permissions']['view_grants'];
		if (!is_array($view_grants)) {
			$view_grants = array();
		}
		$view_grants['pending'] = $editor_grants['pending'];
		$view_checks = $frontitia_page_template->parameters['permissions']['view_checks'];
		if (!is_array($view_checks)) {
			$view_checks = array();
		}
		$view_checks[] = 's';
# ISC SHOULDN"T USE ANY APPROVAL
		$msgs[] = "<p>For $pagename it was determined you will require new settings for viewing access. Viewing access is a new type of permission. Rather than having viewing tied in with editing it has split into it's own type of permission. It is most likely that you wish to have all people that could edit pending records able to view pending records as well so this has been set. Status checking has also been set in the view checks checks.</p>";
		# Who is allowed to view approved records? Well I'm pretty sure everyone no matter what could see approved records so lets set to everyone
		$view_grants['approved'] = array('P');
		$msgs[] = "<p>For $pagename since prior to this Frontitia version everyone no matter what was allowed to see approved records this script has set for view access of this page to be everyone can see approved records.</p>";
		$frontitia_page_template->parameters['permissions']['edit_checks'] = $edit_checks;
		$frontitia_page_template->parameters['permissions']['view_checks'] = $view_checks;
		$frontitia_page_template->parameters['permissions']['view_grants'] = $view_grants;
	}

	# Unset a bunch of things so we don't blow the memory limit
	unset($editor_grants);
	unset($edit_checks);
	unset($view_grants);
	unset($view_checks);

	# Now for edit expiry check (also includes view check and view permission since viewing checking is different and new)
	$editor_grants = $frontitia_page_template->parameters['permissions']['editor_grants'];
	if(!empty($editor_grants['expired'])) {
		# We should be checking expiry then for edit and view
		$edit_checks = $frontitia_page_template->parameters['permissions']['edit_checks'];
		if (!is_array($edit_checks)) {
			$edit_checks = array();
		}
		# Make status check an edit check
		$edit_checks[] = 'e';
		$msgs[] = "<p>For $pagename it was determined you were allowing certain groups to edit expired records. This script has decided in the Configuration tab to set that this Frontitia should check expiry grants as part of the edit grant process.</p>";

		# At this point we know these guys are using expiry so for viewing checks we probably want the same editing rules apply to viewing in terms of who can see expired records
		$view_grants = $frontitia_page_template->parameters['permissions']['view_grants'];
		if (!is_array($view_grants)) {
			$view_grants = array();
		}
		$view_grants['expired'] = $editor_grants['expired'];
		$view_checks = $frontitia_page_template->parameters['permissions']['view_checks'];
		if (!is_array($view_checks)) {
			$view_checks = array();
		}
		$view_checks[] = 'e';
		$msgs[] = "<p>For $pagename it was determined you will require new settings for viewing access. It is most likely that you wish to have all people that could edit expired records able to view expired records as well so this has been set in this frontitia. Checking for expiry has also been set in the view checks checks.</p>";

		$frontitia_page_template->parameters['permissions']['edit_checks'] = $edit_checks;
		$frontitia_page_template->parameters['permissions']['view_checks'] = $view_checks;
		$frontitia_page_template->parameters['permissions']['view_grants'] = $view_grants;
	}

	# Unset a bunch of things so we don't blow the memory limit
	unset($editor_grants);
	unset($edit_checks);
	unset($view_checks);
	unset($view_grants);

	# Up to the posting copy now. This should be easy enought cause it's it's not empty well we should be checking then I guess
	$submitter_grants = $frontitia_page_template->parameters['permissions']['submitter_grants'];
	if (!empty($submitter_grants['approved']) || !empty($submitter_grants['pending'])) {
		# We should be checking expiry then for edit and view
		$post_checks = $frontitia_page_template->parameters['permissions']['post_checks'];
		if (!is_array($post_checks)) {
			$post_checks = array();
		}
		# Make status check an edit check
		$post_checks[] = 's';
		$msgs[] = "<p>For $pagename it was determined you were allowing certain groups to post approved or pending records. This script has decided in the Configuration tab to set that this Frontitia should check post grants as part of the post grant process.</p>";
		$frontitia_page_template->parameters['permissions']['post_checks'] = $post_checks;
	}
	$frontitia_page_template->save_parameters();

	# Unset a bunch of things so we don't blow the memory limit
	unset($submitter_grants);
	unset($post_checks);

	# FOR THIS NEXT SECTION WE WILL NEED TO GO THRU ALL THE AREAS IN THE POSTING COPY AND UPGRADE WHAT IS NEEDED
	$storage = &$frontitia_page_template->get_customised_category_posting_storage();
	$storage_keys = array_keys($storage);
	$msgs[] = "<p>For $pagename upgrading the post copy tab.</p>";
	foreach($storage_keys as $cid) {
		$element = &$storage[$cid];
		# Post copy had changed where it stores it's
		$pending_key_value = $element['record_approval']['initial_value'];
		$element['record_approval']['pending_value'] = $pending_key_value;
		unset($element['record_approval']['initial_value']);
		# Post copy now stores an approved value which we have to guess (only if there was a pending setting to begin with and we have a valid approval attribute)
		$approval_attributeid = $element['record_approval']['approval_attributeid'];
		$approval_attribute = &$frontitia_page_template->get_attribute($approval_attributeid);
		if ($element['record_approval']['pending_value'] && $approval_attribute->id) {
			# Time to guess what it should be
			$options = $approval_attribute->parameters['options'];
			foreach($options as $key => $label) {
				if ($key != $pending_key_value) {
					$approved_value_guess = $key;
					$msgs[] = "<p>For $pagename an educated guess was made that the approved value for when someone submits a new record is $label and has been set on the post copy tab categoryid $cid as the approved value</p>";
					$element['record_approval']['approved_value'] = $approved_value_guess;
					# Unset a bunch of things so we don't blow the memory limit
					unset($approved_value_guess);
					unset($pending_key_value);
					break;
				}
			}
		}

		# Upgrade the posting rule (eg. used to just have source now it's a whole type & settings)
		$owner_sources = $element['owner_sources'];
		foreach($owner_sources as $format => $source) {
			$storage[$cid]['owner_settings'][$format]['type'] = 'ls';
			$storage[$cid]['owner_settings'][$format]['owner_sources'] = $source;
			$msgs[] = "<p>For $pagename - Posting Copy - Category ID $cid - Ownership has been updated.</p>";
		}
		unset($storage[$cid]['owner_sources']);
	}
	$frontitia_page_template->save_tab_parameters('posting');
	# Unset a bunch of things so we don't blow the memory limit
	unset($storage);
	unset($storage_keys);
	unset($element);
	unset($cid);
	unset($pending_key_value);
	unset($approval_attributeid);
	unset($approval_attribute);
	unset($options);
	unset($key);
	unset($label);

	# We now need to go thru record, record list, search, editing copies
	$get_functions = array('record'=>'get_customised_category_record_storage', 'record_list'=>'get_customised_category_record_list_storage', 'editing'=>'get_customised_category_editing_storage', 'search'=>'get_customised_category_search_storage');
	foreach($get_functions as $tab_name => $get_function) {
		$msgs[] = "<p>Searching $tab_name</p>";
		eval("\$storage = &\$frontitia_page_template->$get_function();");
		$storage_keys = array_keys($storage);
		$add_ownership_viewing = false;
		$add_ownership_editing = false;
		foreach($storage_keys as $cid) {
			$element = &$storage[$cid];
			# Upgrade approval by setting hopefully the opposite status
			$approved_key_value = $element['record_approval']['approved_value'];
			# Post copy now stores an approved value which we have to guess (only if there was a pending setting to begin with and we have a valid approval attribute)
			$approval_attributeid = $element['record_approval']['approval_attributeid'];
			$approval_attribute = &$frontitia_page_template->get_attribute($approval_attributeid);
			if ($approved_key_value && $approval_attribute->id) {
				# Time to guess what it should be (This assumes they used a selection attribute. What fool would do otherwise I ask?
				$options = $approval_attribute->parameters['options'];
				foreach($options as $key => $label) {
					if ($key != $approved_key_value) {
						$pending_value_guess = $key;
						$msgs[] = "<p>Page $pagename - $tab_name - $cid an educated guess was made that the pending value for when someone wants to view/edit a record is $label and has been set on the before mentioned tab</p>";
						$element['record_approval']['pending_value'] = $pending_value_guess;
						break;
					}
				}
			}

			# Upgrade each ownership to have a level of 1 trimming all tabs + fks
			$owner_rules = &$element['ownership_rules_list'];
			$owner_rules_keys = array_keys($owner_rules);

			$ownership_updated = false;
			foreach($owner_rules_keys as $key) {
				$owner_element = &$owner_rules[$key];
				$owner_element['level'] = 1;
				$ownership_updated = true;
			}
			if ($ownership_updated) {
				switch($tab_name) {
					case 'record': case 'record_list': case 'search':
						$add_ownership_viewing = true;
					break;
					case 'editing':
						$add_ownership_editing = true;
						break;
				}
			}

			# Unset a bunch of things so we don't blow the memory limit
			unset($approval_attribute);
			unset($approved_key_value);
			unset($approval_attributeid);
			unset($options);
			unset($label);
			unset($key);
			unset($pending_value_guess);
			unset($owner_rules_keys);
			unset($save_search_format);
			unset($name);
		}
		$frontitia_page_template->save_tab_parameters($tab_name);
		$save = false;
		if ($add_ownership_viewing) {
			$view_checks = $frontitia_page_template->parameters['permissions']['view_checks'];
			if (!is_array($view_checks)) {
				$view_checks = array();
			}
			if (!in_array('o', $view_checks)) {
				$view_checks[] = 'o';
				$frontitia_page_template->parameters['permissions']['view_checks'] = $view_checks;
				$save = true;
			}
			if (!in_array('L', $frontitia_page_template->parameters['permissions']['view_grants']['own'])) {
				$frontitia_page_template->parameters['permissions']['view_grants']['own'] = array('L');
				$save = true;
			}
		}
		if ($add_ownership_editing) {
			$edit_checks = $frontitia_page_template->parameters['permissions']['edit_checks'];
			if (!is_array($edit_checks)) {
				$edit_checks = array();
			}
			if (!in_array('o', $edit_checks)) {
				$edit_checks[] = 'o';
				$frontitia_page_template->parameters['permissions']['edit_checks'] = $edit_checks;
				$save = true;
			}
			if (!in_array('L', $frontitia_page_template->parameters['permissions']['editor_grants']['own'])) {
				if (!empty($frontitia_page_template->parameters['permissions']['editor_grants']['approved'])) {
					$frontitia_page_template->parameters['permissions']['editor_grants']['own'] = $frontitia_page_template->parameters['permissions']['editor_grants']['approved'];
					$save = true;
				} elseif (!empty($frontitia_page_template->parameters['permissions']['editor_grants']['pending'])) {
					$frontitia_page_template->parameters['permissions']['editor_grants']['own'] = $frontitia_page_template->parameters['permissions']['editor_grants']['pending'];
					$save = true;
				} else {
					$frontitia_page_template->parameters['permissions']['editor_grants']['own'] = array('L');
					$save = true;
				}
			}
		}
		if ($save) {
			$frontitia_page_template->save_parameters();
		}

		# Unset a bunch of things so we don't blow the memory limit
		unset($storage_keys);
		unset($storage);
		unset($element);
		unset($cid);
		unset($owner_rules);
		unset($owner_element);
	}

	# Do exactly the same only on the foreign key storage
	$msgs[] = "<p>Searching Foreign Key tab</p>";
	$storage = &$frontitia_page_template->parameters['foreign_key_element_storage'];
	$storage_keys = array_keys($storage);
	foreach($storage_keys as $cid) {
		$element = &$storage[$cid];
		# Upgrade approval by setting hopefully the opposite status
		$approved_key_value = $element['record_approval']['approved_value'];
		# Post copy now stores an approved value which we have to guess (only if there was a pending setting to begin with and we have a valid approval attribute)
		$approval_attributeid = $element['record_approval']['approval_attributeid'];
		$approval_attribute = &$frontitia_page_template->get_attribute($approval_attributeid);
		if ($approved_key_value && $approval_attribute->id) {
			# Time to guess what it should be (This assumes they used a selection attribute. What fool would do otherwise I ask?
			$options = $approval_attribute->parameters['options'];
			foreach($options as $key => $label) {
				if ($key != $approved_key_value) {
					$pending_value_guess = $key;
					$msgs[] = "<p>Page $pagename - Foreign Key Tab - $cid an educated guess was made that the pending value for when someone wants to view/edit a record is $label and has been set on the before mentioned tab</p>";
					$element['record_approval']['pending_value'] = $pending_value_guess;
					break;
				}
			}
		}

		# Upgrade each ownership to have a level of 1 trimming all tabs + fks
		$owner_rules = &$element['ownership_rules_list'];
		$owner_rules_keys = array_keys($owner_rules);
		$owners = &$element['owners'];
		# Grab the first searching attribute in owners
		foreach($owners as $search_format => $name) {
			break;
		}
		foreach($owner_rules_keys as $key) {
			$owner_element = &$owner_rules[$key];
			$owner_element['level'] = 1;
			$owner_element['attribute_format'] = $search_format;
		}
		# We can trash this now
		unset($owners);
		# Unset a bunch of things so we don't blow the memory limit
		unset($approval_attribute);
		unset($approved_key_value);
		unset($approval_attributeid);
		unset($options);
		unset($label);
		unset($key);
		unset($pending_value_guess);
		unset($owner_rules_keys);
		unset($search_format);
		unset($name);
	}

	$frontitia_page_template->save_parameters();
	$frontitia_page->clear_cache();
	# Unset a bunch of things so we don't blow the memory limit
	$frontitia_page_template = null;
	$frontitia_page = null;
	unset($frontitia_page_template);
	unset($frontitia_page);
	unset($storage_keys);
	unset($storage);
	unset($element);
	unset($cid);
	unset($owner_rules);
	unset($owner_element);
	# Process 5 pages per refresh
	$do_count++;
	unset($left_to_do[$pageid]);
	$left_to_do_count = count($left_to_do);
	echo "<p><b>Page $pagename done</b> <i>$left_to_do_count pages left to do</i></p>";
	if ($do_count > 3) {
		# Save and refresh to this upgrade script
		$upgrade_array['pages'] = $left_to_do;
		$upgrade_array['msgs'] = $msgs;
		$SESSION->set_var('frontitia_upgrade_script_1_11_0', $upgrade_array);
		$href = $_SERVER['PHP_SELF'];
		?>
		<script language="javascript">
			document.location='<?echo $href?>';
		</script>
		<?
		exit();
	}
}

# We are done so lets print out the big msg list
foreach($msgs as $msg) {
	echo $msg;
}

# Wipe the upgrade array
$upgrade_array = array();
$SESSION->set_var('frontitia_upgrade_script_1_11_0', $upgrade_array);
echo '...upgrade complete.';
report_success($_SERVER['SCRIPT_FILENAME']);
?>

