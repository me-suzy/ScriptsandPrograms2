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
$todo_attributeids = $session->get_var('upgrade_notitia_attributeids');
if($_GET['action'] == 'finished') {
	# We are done so lets clean up
	$todo_attributeids = '';
	$session->set_var('upgrade_notitia_attributeids', $todo_attributeids);
	$report = $session->get_var('upgrade_report');
	echo $report;
	echo '...upgrade complete.';
	report_success($_SERVER['SCRIPT_FILENAME']);
} elseif ($todo_attributeids == '') {
	# The first time ever

	# Lets create the tables that store the stuff
	if (!$db->table_exists('xtra_web_extension_notitia_attribute_value_number')) {
		$db->select("CREATE TABLE xtra_web_extension_notitia_attribute_value_number (attributeid INT UNSIGNED NOT NULL , recordid INT UNSIGNED NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(attributeid,recordid), KEY(recordid))");
	}
	if (!$db->table_exists('xtra_web_extension_notitia_attribute_default_number')) {
		$db->select("CREATE TABLE xtra_web_extension_notitia_attribute_default_number ( attributeid  INT UNSIGNED NOT NULL, categoryid INT UNSIGNED NOT NULL, inherit_type VARCHAR(255) NOT NULL, sibling_type VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(attributeid,categoryid), KEY(categoryid))");
	}
	if (!$db->table_exists('xtra_web_extension_notitia_attribute_value2')) {
		$db->select("CREATE TABLE xtra_web_extension_notitia_attribute_value2 (attributeid INT UNSIGNED NOT NULL, recordid INT UNSIGNED NOT NULL, value TEXT NOT NULL, PRIMARY KEY(attributeid,recordid), KEY(recordid))");
	}
	if (!$db->table_exists('xtra_web_extension_notitia_attribute_value3')) {
		$db->select("CREATE TABLE xtra_web_extension_notitia_attribute_value3 (attributeid INT UNSIGNED NOT NULL, recordid INT UNSIGNED NOT NULL, value TEXT NOT NULL, PRIMARY KEY(attributeid,recordid), KEY(recordid))");
	}
	if (!$db->table_exists('xtra_web_extension_notitia_attribute_xtra')) {
		$db->select("CREATE TABLE xtra_web_extension_notitia_attribute_xtra (attributeid INT UNSIGNED NOT NULL, xtraid INT UNSIGNED NOT NULL AUTO_INCREMENT, type VARCHAR(128), parameters  LONGTEXT, order_no INT UNSIGNED NOT NULL DEFAULT 0, PRIMARY KEY(xtraid), KEY(attributeid), KEY(order_no))");
	}
	$todo_attributeids = $db->single_column("SELECT attributeid FROM xtra_web_extension_notitia_attribute WHERE type='number'");

	if (!empty($todo_attributeids)) {
		# Save the $todo_attributeids into the session for processing
		$session->set_var('upgrade_notitia_attributeids', $todo_attributeids);
		
		# Loop of the pages and set whatever settings we should set
		do_upgrade_attribute_values();
	} else {
		$href = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&action=finished';
		?>
		<script language="javascript">
			document.location='<?echo $href?>';
		</script>
		<?
	}
} else {
	# Continuing Processing 
	do_upgrade_attribute_values();
}

function do_upgrade_attribute_values() {
	$web_system = &get_web_system();
	$db = &$web_system->get_db();

	$session = &get_mysource_session();
	$num_to_process     = $_GET['num_to_process'];
	$num                = $_GET['num'];
	$num_processed      = $_GET['num_processed'];
	$started            = $_GET['started'];
	$start_time         = $_GET['start_time'];
	$action             = $_GET['action'];

	set_time_limit(0);

	if (isset($num) && isset($num_to_process)) {
		if (!$started) {
			# First popup ever
			$now = time();
			$process_url = $_SERVER['PHP_SELF']."?num_to_process=$num_to_process&num=$num&num_processed=0&started=1&start_time=$now";
			$report = '';
			$session->set_var('upgrade_report', $report);
			echo status_popup(1,$process_url,false,false,'#330099','Performing Attribute Upgrading - Please wait', "Starting to process Attributes for $num_to_process pages");
			exit();
		}

		$attributeids = $session->get_var('upgrade_notitia_attributeids');
		$i = 0;
		if (!empty($attributeids)) {
			$report = $session->get_var('upgrade_report');
			$done_attributeids = array();
			foreach($attributeids as $attributeid) {
				$db->insert("INSERT INTO xtra_web_extension_notitia_attribute_value_number SELECT * FROM xtra_web_extension_notitia_attribute_value WHERE attributeid = '$attributeid'");
				$db->insert("INSERT INTO xtra_web_extension_notitia_attribute_value_number SELECT * FROM xtra_web_extension_notitia_attribute_value2 WHERE attributeid = '$attributeid'");
				$db->insert("INSERT INTO xtra_web_extension_notitia_attribute_value_number SELECT * FROM xtra_web_extension_notitia_attribute_value3 WHERE attributeid = '$attributeid'");
				$db->insert("INSERT INTO xtra_web_extension_notitia_attribute_default_number SELECT * FROM xtra_web_extension_notitia_attribute_default WHERE attributeid = '$attributeid'");
				$db->delete("DELETE FROM xtra_web_extension_notitia_attribute_value WHERE attributeid = '$attributeid'");
				$db->delete("DELETE FROM xtra_web_extension_notitia_attribute_value2 WHERE attributeid = '$attributeid'");
				$db->delete("DELETE FROM xtra_web_extension_notitia_attribute_value3 WHERE attributeid = '$attributeid'");
				$db->delete("DELETE FROM xtra_web_extension_notitia_attribute_default WHERE attributeid = '$attributeid'");
				$report .= 'Attribute ID '.$attributeid.' has been processed<br />';
				$i++;
				$done_attributeids[] = $attributeid;
				if ($i >= $num) break;
			}
			$session->set_var('upgrade_report', $report);
		}
		$attributeids = array_diff($attributeids, $done_attributeids);
		$session->set_var('upgrade_notitia_attributeids', $attributeids);

		$num_processed += $i;
		$percent = ceil(($num_processed / $num_to_process) * 100);
		$finish = (($num_processed >= $num_to_process) ? true:false);
		$time_diff = time() - $start_time;
		$time_per_lookup = $time_diff / $num_processed;
		$time_left = $time_per_lookup * ($num_to_process - $num_processed);
		if ($time_left <= 0) $time_left = 1;
		$status = '';

		if ($finish) {
			$process_url = '';
			$status = "<script language=\"Javascript\">window.opener.location='".$_SERVER['PHP_SELF']."?action=finished&started=1';self.close();</script>";
			$percent = 100;
		} else {
			$process_url = $_SERVER['PHP_SELF']."?num_to_process=$num_to_process&num=$num&num_processed=$num_processed&started=1&start_time=$start_time";
			$status = "Completed $num_processed attributes - ".($num_to_process - $num_processed).' remaining - est time: '.easy_time_total($time_left);
		}

		echo status_popup($percent,$process_url,false,$finish,'#330099','Performing Attribute Upgrade - Please wait',$status);
		exit();
	}

	$num_to_process = count($session->get_var('upgrade_notitia_attributeids'));
	$num = 5; # attributes to update at a time
	$process_url = $_SERVER['PHP_SELF']."?num_to_process=$num_to_process&num=$num&num_processed=0&started=0";
	echo status_popup(1,$process_url,true,false,'#330099','Performing Attribute Upgrading - Please wait', "Starting to process Attributes for $num_to_process pages");
}
?>
