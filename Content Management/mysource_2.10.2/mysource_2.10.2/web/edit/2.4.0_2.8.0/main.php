<?
require_once('../../init.php');

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

if (is_file($_SERVER['PHP_SELF'] . '.success')) {
	echo 'This upgrade has already run. Aborting.<br />';
	exit();
}

if (is_file($_SERVER['PHP_SELF'] . '.failure')) {
	unlink($_SERVER['PHP_SELF'] . '.failure');
}

$web = &get_web_system();
$webdb = &$web->get_db();

$users = &get_users_system();
$usersdb = &$users->get_db();

global $CACHE;

$CACHE->wipe();

 ########################################################################
# Set the system logging on, so logging doesn't stop after the upgrade
$SYSTEM_CONFIG->set_log_visitors(1);
$SYSTEM_CONFIG->conf_updated();
?>
<span style="color: red">To enable logging, you will need to add<br /><i>SetEnv MySource_LogVisitors on</i><br />to the apache configuration and restart the web server.<br /><br /></span>
<?

if (!is_dir($SYSTEM_ROOT."/data/ftp")) {
	create_directory($SYSTEM_ROOT."/data/ftp");
}

 #########################################################
# Web DB Changes
$sql = array();
$sql[] = "CREATE TABLE meta_data (siteid MEDIUMINT UNSIGNED NOT NULL, pageid MEDIUMINT UNSIGNED NOT NULL, group_name VARCHAR(100) DEFAULT '' NOT NULL, tag_name VARCHAR(100) DEFAULT '' NOT NULL, tag_scheme VARCHAR(100) DEFAULT '' NOT NULL, tag_lang VARCHAR(100) DEFAULT '' NOT NULL, value_name VARCHAR(100) DEFAULT '' NOT NULL, content LONGTEXT DEFAULT '' NOT NULL, PRIMARY KEY (siteid, pageid, group_name, tag_name, value_name));";
$sql[] = "ALTER TABLE xtra_page_template_redirect ADD column extra_url VARCHAR(255);";
$sql[] = "ALTER TABLE page CHANGE `ssl` usessl TINYINT DEFAULT 0;";
$sql[] = "ALTER TABLE page ADD COLUMN level SMALLINT NOT NULL";

foreach($sql as $run) $webdb->select($run);

# User database
$sql = array();

foreach($sql as $run) $usersdb->select($run);

# Set all the levels for each page
$queries = array();
# Get all the page records
$query = "SELECT pageid, parentid FROM page";
$result = $webdb->associative_array($query);
foreach($result as $pageid => $parentid) {
	# Find out how many parents and level
	$level = get_level($result, $parentid, 0);
	$queries[] = "UPDATE page SET level = '$level' WHERE pageid = '$pageid'";
}

# Run all the queries
foreach($queries as $query) {
	$webdb->update($query);
}

report_success($_SERVER['SCRIPT_FILENAME']);
$CACHE->wipe();

function get_level($result, $parentid, $level) {
	if ($parentid == 0) {
		return $level;
	} else {
		$level = $level + 1;
		return get_level($result, $result[$parentid], $level);
	}
}

?>