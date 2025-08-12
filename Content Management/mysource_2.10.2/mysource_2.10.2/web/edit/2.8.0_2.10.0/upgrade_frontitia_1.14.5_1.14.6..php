<?
include_once("../../init.php");
include_once('./upgrade_functions.php');
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/html_general.inc"); 
#---------------------------------------------------------------------#
#
# This script upgrades frontitia to use Expiry on a per category basis.

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

# num pages to process at one time
define(NUM_PAGES, 5);

# frontitia storages to update
$get_functions = array(	'record'=>'get_customised_category_record_storage', 
						'record_list'=>'get_customised_category_record_list_storage',
						'editing'=>'get_customised_category_editing_storage', 
						'search'=>'get_customised_category_search_storage',
						'posting'=>'get_customised_category_posting_storage'
						);

$session = &get_mysource_session();
$pages = &$session->get_var('upgrade_frontitia_pageids');
$report = &$session->get_var('upgrade_report');
if (!is_string($report)) {
	$report = '';
}

if($_GET['action'] == 'finished') {
	# We are done so lets clean up
	$session->set_var('upgrade_frontitia_pageids', $null=null);
	echo $report;
	echo '...upgrade complete.';
	$report = '';
	report_success($_SERVER['SCRIPT_FILENAME']);
} elseif (!isset($pages)) {
	# first time running
	$web_system = &get_web_system();
	$pages = $web_system->get_template_pages('frontitia');
	$report .= "<p>Finding frontitia pages ...</p>";
	if (count($pages) == 0) {
		echo "No frontitia pages where found";
		$session->set_var('upgrade_frontitia_pageids', $null=null);
		echo '...upgrade complete.';
		$report = '';
		report_success($_SERVER['SCRIPT_FILENAME']);
	}
	do_upgrade_limited_pages($pages, $report);
} else {
	do_upgrade_limited_pages($pages, $report);
}

function do_upgrade_limited_pages(&$pages, &$report) {
	$num_to_process     = $_GET['num_to_process'];
	$num                = $_GET['num'];
	$num_processed      = $_GET['num_processed'];
	$started            = $_GET['started'];
	$start_time         = $_GET['start_time'];
	$action             = $_GET['action'];

	if (!$started) {
		# First popup ever
		$now = time();
		$num_to_process = count($pages);
		$process_url = $_SERVER['PHP_SELF']."?num_processed=0&started=1&start_time=$now&num_to_process=$num_to_process";
		echo status_popup(1,$process_url,true,false,'#330099','Performing Frontitia Upgrading - Please wait', "Starting to process $num_to_process Frontitia pages");
		return;
	}

	# upgrade num pages 
	if (!empty($pages)) {
		$count = upgrade_pages($pages, $report, NUM_PAGES);
	}

	$num_processed += $count;
	$percent = ceil(($num_processed / $num_to_process) * 100);
	$time_diff = time() - $start_time;
	$time_per_lookup = $time_diff / $num_processed;
	$time_left = $time_per_lookup * ($num_to_process - $num_processed);
	if ($time_left <= 0) $time_left = 1;
	$status = '';

	if (empty($pages)) {
		$process_url = '';
		$status = "<script language=\"Javascript\">window.opener.location='".$_SERVER['PHP_SELF']."?action=finished&started=1';self.close();</script>";
		$percent = 100;
	} else {
		$process_url = $_SERVER['PHP_SELF']."?num_to_process=$num_to_process&num_processed=$num_processed&started=1&start_time=$start_time";
		$status = "Completed $num_processed Frontitia Pages - ".($num_to_process - $num_processed).' remaining - est time: '.easy_time_total($time_left);
	}

	echo status_popup($percent,$process_url,false,$finish,'#330099','Performing Frontitia Upgrade - Please wait',$status);
}

# upgrades $num_to_process pages and also removes $num_to_process elements from the pages array
function upgrade_pages(&$pages, &$report, $num_to_process) {
	global $get_functions;
	$web_system = &get_web_system();
	$keys = array_keys($pages);
	$report .= '<table border=1>';
	$i = 0;
	foreach ($keys as $pageid) {
		if ($i == $num_to_process) {
			break;
		}
		$i++;

		# process page
		$pagename = $pages[$pageid];
		$report .= "<td>Upgrading $pagename</td>";
		$frontitia_page = &$web_system->get_page($pageid);
		$frontitia_page_template = &$frontitia_page->get_template();
		
		$expiry_attributeid = $frontitia_page_template->get_value('expiredate_attributeid');
		$use_expiry_initial_status = $frontitia_page_template->get_value('use_expiry_initial_status');
		$expiry_array = $frontitia_page_template->get_value('expiry_array');

		$attribute = &$frontitia_page_template->get_attribute($expiry_attributeid);
		$ignore_expiry = false;
		if ($attribute->id && $attribute->type == 'datetime') {
			$sec = 0;
			if ($use_expiry_initial_status) {
				$sec = (int) $expiry_array['mins']*60 + $expiry_array['hours']*3600 + $expiry_array['days']*86400;
			}
		} else {
				$ignore_expiry = true;
		}

		foreach($get_functions as $tab_name => $get_function) {
			eval("\$storage = &\$frontitia_page_template->$get_function();");
			$storage_keys = array_keys($storage);
			foreach($storage_keys as $cid) {
				$submit_time = &$storage[$cid]['submitted_time_attributes'];
				if (($tab_name == 'posting' || $tab_name == 'editing') && is_array($submit_time)) {
					for (reset($submit_time); null !== ($key = key($submit_time)); next($submit_time)) {
						$submit_time[$key] = 0;
					}
				}
				$storage[$cid]['expiredate_attributeid'] = $expiry_attributeid;
				if (!$ignore_expiry && $use_expiry_initial_status && ($tab_name == 'posting' || $tab_name == 'editing')) {
					$storage[$cid]['submitted_time_attributes'][$expiry_attributeid.'_raw'] = $sec;
				}
			}
			$frontitia_page_template->save_tab_parameters($tab_name);
			$report .= "<td>Saved $tab_name storage</td>";
		}
		$report .= '</tr>';

		unset($frontitia_page_template->parameters['expiredate_attributeid']);
		unset($frontitia_page_template->parameters['use_expiry_initial_status']);
		unset($frontitia_page_template->parameters['expiry_array']);
		$frontitia_page_template->save_parameters();

		$frontitia_page->clear_cache();
		unset($frontitia_page_template);
		$web_system->forget_page($pageid);

		# remove page from list so it is not processed again
		unset($pages[$pageid]);
	}
	$report .= '</table>';
	return $i;
}

?>

