#!/usr/local/bin/php 
<?php
echo "Starting tests ...\n";
error_reporting(5);
set_magic_quotes_runtime(0);

ini_set('allow_call_time_pass_reference', true);
define('MYSOURCE_VERSION','2.4.2 A');
define('MYSOURCE_REQUIRED_PHP_VERSION','4.2.0');
define('MYSOURCE_REQUIRED_MYSQL_VERSION','3.23');
define('MYSOURCE_NAME','MySource');
define('MYSOURCE_LONG_NAME', MYSOURCE_NAME.' v'.MYSOURCE_VERSION);
define('MYSOURCE_URL','http://mysource.squiz.net/');

define('MYSOURCE_ERROR_CODE_NONE',    0);
define('MYSOURCE_ERROR_CODE_WARNING', 1);
define('MYSOURCE_ERROR_CODE_ERROR',   2);

$SYSTEM_ROOT    = dirname(__FILE__).'/..';

$INCLUDE_PATH   = "$SYSTEM_ROOT/include";
$SQUIZLIB_PATH  = "$SYSTEM_ROOT/squizlib";
$WEB_PATH       = "$SYSTEM_ROOT/web";
$EDIT_PATH      = "$WEB_PATH/edit";
$CACHE_PATH     = "$SYSTEM_ROOT/cache";
$BIN_PATH       = "$SYSTEM_ROOT/bin";
$CONF_PATH      = "$SYSTEM_ROOT/conf";
$DATA_PATH      = "$SYSTEM_ROOT/data";
$XTRAS_PATH     = "$SYSTEM_ROOT/xtras";

echo "Including files ";

require_once("$INCLUDE_PATH/general.inc");
echo '.';
require_once("$SQUIZLIB_PATH/general/general.inc");
echo '.';
$ERROR_REPORTER_FUNCTION = "report_error_plaintext";
require_once("$SQUIZLIB_PATH/dev/dev.inc");
echo '.';
require_once("$SQUIZLIB_PATH/object/object.inc");
echo '.';
require_once("$SQUIZLIB_PATH/cache/file_cache.inc");
echo '.';
$CACHE = new File_Cache($CACHE_PATH);
require_once("$SQUIZLIB_PATH/db/db.inc");
echo '.';
require_once("$INCLUDE_PATH/xtras.inc");
echo '.';
$XTRAS = new XtrasRegistry($XTRAS_PATH);
echo "done\n";

function &get_system_config() {
	global $SYSTEM_CONFIG;
	if(get_class($SYSTEM_CONFIG) != 'config_mysource') {
		global $INCLUDE_PATH;
		require_once("$INCLUDE_PATH/config.inc");
		$SYSTEM_CONFIG = &get_config("MySource");
	}
	return $SYSTEM_CONFIG;
}

$SYSTEM_CONFIG = &get_system_config();

global $DB_CURRENT_NAME;
$DB_CURRENT_NAME = array();

require_once("$INCLUDE_PATH/text.inc");

function &get_web_system() {
	global $WEB_SYSTEM;
	if(get_class($WEB_SYSTEM) != 'web') {
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/web.inc");
		$WEB_SYSTEM = new Web();
	}
	return $WEB_SYSTEM;
}
function &get_users_system() {
	global $USERS_SYSTEM;
	if(get_class($USERS_SYSTEM) != 'users') {
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/users.inc");
		$USERS_SYSTEM = new Users();
	}
	return $USERS_SYSTEM;
}

function &get_mysource_system() {
	global $MYSOURCE_SYSTEM;
	if(get_class($MYSOURCE_SYSTEM) != 'mysource') {
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/mysource.inc");
		$MYSOURCE_SYSTEM = new Mysource();
	}
	return $MYSOURCE_SYSTEM;
}

require_once("$INCLUDE_PATH/security.inc");

function &get_mysource_session() {
	$session = &$_SESSION['SESSION'];
	if (!$session) {
		$_SESSION['SESSION'] = new MySourceSession("SESSION");
	}
	return $_SESSION['SESSION'];
}

function report_error_plaintext($file, $line, $message) {
	echo "ERROR: in file $file on line $line '$message'\n";
}

require_once("$INCLUDE_PATH/session.inc");
$SESSION = &get_mysource_session();
$SESSION->start();

$tests = array();
$d = dir(".");
while (false !== ($entry = $d->read())) {
	$parts = array_reverse(explode('.', $entry));
	# Get the extension of all the files in the current directory, if
	# the extension is phpt, treat it like a test
	if ($parts[0] == 'phpt') $tests[] = $entry;
}
$d->close();
echo "Mem usage file ".__FILE__.", line ".__LINE__." is ".(memory_get_usage() / 1024)."Kbs\n"; 
foreach ($tests as $file) {
	$testname = str_replace('.phpt', '', $file);
	echo "Executing test: $testname\n";
	include_once($file);
	echo "Finished test: $testname\n";
}
echo "Mem usage file ".__FILE__.", line ".__LINE__." is ".(memory_get_usage() / 1024)."Kbs\n"; 

?>
