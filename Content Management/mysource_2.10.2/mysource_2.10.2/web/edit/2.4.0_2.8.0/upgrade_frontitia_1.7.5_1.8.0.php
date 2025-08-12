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

# create backup
$sql = array();
$query = "CREATE TABLE xtra_page_template_frontitia_backup (pageid MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY, parameters  LONGTEXT);";

if(!$db->select($query)) {
	# d'oh! can't go on, so die.
	echo "Error - There was a problem creating a backup frontitia table. The rest of the script can't run until this problem is fixed.<p>";
	report_failure($_SERVER['SCRIPT_FILENAME']);
	exit;
}

$number = $db->single_element("SELECT count(*) from xtra_page_template_frontitia");
if($number) { # yes, we have some data we have to back up.
	$query = "INSERT INTO xtra_page_template_frontitia_backup (pageid, parameters) SELECT sp.pageid, sp.parameters FROM xtra_page_template_frontitia sp";
	if(!$db->insert($query)) {
		#d'oh! no point if we can't copy what's in there, huh?
		echo "Error - There was a problem copying the data from the frontitia listing table to the backup table. The rest of the script can't run until this problem is fixed.<p>";
		report_failure($_SERVER['SCRIPT_FILENAME']);
		exit;
	}
}

echo "<p>Backup tables created and data copied OK. Creating additional frontitia tables frontitia tables";
$sql[] = "CREATE TABLE xtra_page_template_frontitia_browsing (pageid MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY, parameters LONGTEXT)";
$sql[] = "CREATE TABLE xtra_page_template_frontitia_record (pageid MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY, parameters LONGTEXT)";
$sql[] = "CREATE TABLE xtra_page_template_frontitia_record_list (pageid MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY, parameters LONGTEXT)";
$sql[] = "CREATE TABLE xtra_page_template_frontitia_posting (pageid MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY, parameters LONGTEXT)";
$sql[] = "CREATE TABLE xtra_page_template_frontitia_editing (pageid MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY, parameters LONGTEXT)";
$sql[] = "CREATE TABLE xtra_page_template_frontitia_search (pageid MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY, parameters LONGTEXT)";
foreach($sql as $run) {
	if (!$db->select($run)) report_failure($_SERVER['SCRIPT_FILENAME']);
}
echo "<p>New tables created. Spliting frontitia storages into new tables</p>";

$storage_array = array('xtra_page_template_frontitia_browsing' => 'customised_category_browsing_storage', 'xtra_page_template_frontitia_record' => 'customised_category_record_storage', 'xtra_page_template_frontitia_record_list' => 'customised_category_record_list_storage', 'xtra_page_template_frontitia_posting' => 'customised_category_posting_storage', 'xtra_page_template_frontitia_editing' => 'customised_category_editing_storage', 'xtra_page_template_frontitia_search' => 'customised_category_search_storage');

$pages = $web_system->get_template_pages('frontitia');
echo "<p>Finding frontitia pages ...</p>";
foreach($pages as $pageid => $pagename) {
	echo "<p>Found $pagename</p>";
	list($this->id, $tmp) = $db->single_row("SELECT pageid, parameters FROM xtra_page_template_frontitia WHERE pageid='$pageid'");
	$this->parameters = unserialize($tmp);
	foreach($storage_array as $table_name => $storage_name) {
		$storage = $this->parameters[$storage_name];
		echo "<p>Parameters of page $pagename found for storage $storage_name</p>";
		if ($ret_val = $db->insert("INSERT INTO $table_name (pageid, parameters) VALUES ('$pageid', '".addslashes(serialize($storage))."')")) {
			unset($this->parameters[$storage_name]);
		} else {
			echo "<p>A problem arose trying to move parameters into $table_name for this page. Terminating upgrade  & restoring old settings</p>";
			$sql2 = array();
			$sql2[] = "DROP TABLE xtra_page_template_frontitia_browsing";
			$sql2[] = "DROP TABLE xtra_page_template_frontitia_record";
			$sql2[] = "DROP TABLE xtra_page_template_frontitia_record_list";
			$sql2[] = "DROP TABLE xtra_page_template_frontitia_posting";
			$sql2[] = "DROP TABLE xtra_page_template_frontitia_editing";
			$sql2[] = "DROP TABLE xtra_page_template_frontitia_search";
			$sql2[] = "DROP TABLE xtra_page_template_frontitia";
			foreach($sql2 as $run) $db->select($run);
			echo "<p>New tables dropped as well as standard table ... Restoring frontitia from backup</p>";
			$query = "CREATE TABLE xtra_page_template_frontitia (pageid MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY, parameters LONGTEXT);";
			$db->select($query);
			$number = $db->single_element("SELECT count(*) from xtra_page_template_frontitia_backup");
			if($number) { # yes, we have some data we have to back up.
				$query = "INSERT INTO xtra_page_template_frontitia (pageid, parameters) SELECT sp.pageid, sp.parameters FROM xtra_page_template_frontitia_backup sp";
				if(!$db->insert($query)) {
					#d'oh! no point if we can't copy what's in there, huh?
					echo "<p>There was a problem restoring backup table. Aborting.</p>";
				}
			}
			echo "<p>Original frontitia table restored. Cleaning up backup table</p>";
			$query = "DROP TABLE xtra_page_template_frontitia_backup";
			$db->select($query);
			echo "<p>Roll back complete. Sorry the upgrade didn't work</p>";
			report_failure($_SERVER['SCRIPT_FILENAME']);
			exit;
		}
	}
	$temp = addslashes(serialize($this->parameters));
	$db->update("UPDATE xtra_page_template_frontitia SET parameters='".$temp."' WHERE pageid='$pageid'");
	echo "<br />Upgraded Frontitia Page $page_name<br>";
	unset($temp);
	unset($this->parameters);
}
echo '...upgrade complete.';
report_success($_SERVER['SCRIPT_FILENAME']);
?>