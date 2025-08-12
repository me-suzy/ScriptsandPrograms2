<?  ##############################################
   ### MySource ------------------------------###
  ##- Web initialisation -- PHP4 -------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: web/init.php
## Desc: Parses the configuration files and sets up web system
## $Source: /home/cvsroot/mysource/web/init.php,v $
## $Revision: 2.103.2.7 $
## $Author: achadszinow $
## $Date: 2004/05/28 00:52:44 $
#######################################################################
# This file is intended to be included before any other processing
#

  ########################################
 # Set the level of PHP reported errors
# And some other PHP thingies we want
# done OUR way.
error_reporting(E_ERROR | E_PARSE);
set_magic_quotes_runtime(0); # MySource's eternal nemisis


 ################################################
# Set the current version of the MySource core
# We mustn't forget to update this (until someone
# comes up with an automated method)
define('MYSOURCE_VERSION','2.10.2');
define('MYSOURCE_REQUIRED_PHP_VERSION','4.3.0');
define('MYSOURCE_REQUIRED_MYSQL_VERSION','3.23');
define('MYSOURCE_NAME','MySource');
define('MYSOURCE_LONG_NAME', MYSOURCE_NAME.' v'.MYSOURCE_VERSION);
define('MYSOURCE_URL','http://mysource.squiz.net/');

define('MYSOURCE_ERROR_CODE_NONE',    0);
define('MYSOURCE_ERROR_CODE_WARNING', 1);
define('MYSOURCE_ERROR_CODE_ERROR',   2);

/* Set this to 1 if you are running PHP as a CGI.
	We need to do this because PHP as a CGI doesn't support
	header("404") or header("403")
*/
define('PHP_CGI', 0);

 ############################################################
# Let's het our bearings as to where everything is from here.
# These paths may be relative or absolute
global $SYSTEM_ROOT,$INCLUDE_PATH,$SQUIZLIB_PATH,$WEB_PATH,$EDIT_PATH,$CACHE_PATH,$BIN_PATH,$CONF_PATH,$DATA_PATH,$XTRAS_PATH;
$SYSTEM_ROOT    = dirname(dirname(realpath(__FILE__)));
$INCLUDE_PATH   = "$SYSTEM_ROOT/include";
$SQUIZLIB_PATH  = "$SYSTEM_ROOT/squizlib";
$WEB_PATH       = "$SYSTEM_ROOT/web";
$EDIT_PATH      = "$WEB_PATH/edit";
$CACHE_PATH     = "$SYSTEM_ROOT/cache";
$BIN_PATH       = "$SYSTEM_ROOT/bin";
$CONF_PATH      = "$SYSTEM_ROOT/conf";
$DATA_PATH      = "$SYSTEM_ROOT/data";
$XTRAS_PATH     = "$SYSTEM_ROOT/xtras";

global $THIS_PATH;
$THIS_PATH = dirname(realpath($_SERVER['PATH_TRANSLATED']));

global $IN_BACKEND;
# nice little boolean to use for testing whether we happen to be in the backend or not
$IN_BACKEND = ($EDIT_PATH == substr($THIS_PATH, 0, strlen($EDIT_PATH)));


 ######################################################################
# Load general everyday handy functions, including the error_reporter
require_once("$INCLUDE_PATH/general.inc");
require_once("$SQUIZLIB_PATH/general/general.inc");

# A boolean which can be set, telling MySource to create relative or absolute urls
absolute_urls(FALSE);


$ERROR_REPORTER_FUNCTION = "report_error";
// Time in seconds before an error is considered to have finished
define('ERR_CUTOFF_TIME', 300); 
// The max number of emails to group together before emailing
define('ERR_MAX_GROUP_SIZE', 64);



 #########################################
# Load general everyday handy functions for developers
require_once("$SQUIZLIB_PATH/dev/dev.inc");
#speed_check(); mem_check();

 #########################################
# Load the supersuperclass!
require_once("$SQUIZLIB_PATH/object/object.inc");

 ################################################
# Load the cacheing classes and create the global
# cache object.
#require_once("$SQUIZLIB_PATH/cache/db_cache.inc"); # Note this alternative caching method
#$CACHE = new Db_Cache("mysource_web");             # feel free to explore.
require_once("$SQUIZLIB_PATH/cache/file_cache.inc");
global $CACHE;
$CACHE = new File_Cache($CACHE_PATH);

 #############################################
# Now that we've got error reporting lets 
# generate a few errors!
if(version_no_compare(phpversion(),MYSOURCE_REQUIRED_PHP_VERSION) < 0) {
	report_error(__FILE__,__LINE__,MYSOURCE_LONG_NAME." requires PHP Version ".MYSOURCE_REQUIRED_PHP_VERSION.". You may need to upgrade. Your current version is ".phpversion().".");
	exit();
}
if(!function_exists('mysql')) {
	report_error(__FILE__,__LINE__,MYSOURCE_LONG_NAME." requires PHP to be installed with MySQL support. You may have to recompile PHP.");
	exit();
}

 ###############################################
# Load up the SquizLib database class
require_once("$SQUIZLIB_PATH/db/db.inc");

 ####################################################
# Load register the xtras plugged into this system
global $XTRAS;
require_once("$INCLUDE_PATH/xtras.inc");
$XTRAS = new XtrasRegistry($XTRAS_PATH);


 ####################################################
# Load configuration variables into the global scope
function &get_system_config() {
	global $SYSTEM_CONFIG;
	if(get_class($SYSTEM_CONFIG) != 'config_mysource') {
		global $INCLUDE_PATH;
		require_once("$INCLUDE_PATH/config.inc");
		$SYSTEM_CONFIG = &get_config("MySource");
	}
	return $SYSTEM_CONFIG;
}

global $SYSTEM_CONFIG;
$SYSTEM_CONFIG = &get_system_config();


 #########################################################################
# If the system config is not cached, it needs to access
# the database a couple of times. This sets the global DB_CURRENT_NAME
# variable that tells the db it is already using the correct database.
# We need to reset this _after_ the system config is loaded to stop
# a bunch of db errors occuring.
global $DB_CURRENT_NAME;
$DB_CURRENT_NAME = array();

# Configuration variables should now be set.


 ###################################################################################
# OK, now that we have the backend_suffix from the config, let's set up the 
# These paths are relative, for use in HREFs
# MySource developer challenge no. 1: What the hell is going on here!?
# ..later.. trying to phase out $BASE_DIR altogether, since we're moving edit
# out of the web directory, there will be no relative path the base_dir.
# and if you're on the frontend then you're in the base_dir all the time anyway
global $BASE_DIR,$EDIT_DIR;
$BASE_DIR = substr('./'.str_repeat('../',substr_count(substr($THIS_PATH,strlen(dirname(realpath(__FILE__)))),'/')),0,-1); 
$EDIT_DIR = $BASE_DIR.'/'.$SYSTEM_CONFIG->backend_suffix;

 ###################################################
# Load text, color and image manipulation functions
# which may be of some use.
require_once("$INCLUDE_PATH/text.inc");

 ###########################################################
# MySource is divided into two (at the moment) systems -
# * Web management
# * User management
# An object "runs" each ones, these functions create the objects
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

function &get_statistics_reporter($type='') {
	global $STATISTICS_REPORTER;
	$system_config = &get_system_config();
	if(get_class($STATISTICS_REPORTER) != strtolower($system_config->statistics_reporter)) {
		global $XTRAS_PATH;
		include_once($XTRAS_PATH.'/statistics/'.$system_config->statistics_reporter.'/'.$system_config->statistics_reporter.'.inc');
		$STATISTICS_REPORTER = new $system_config->statistics_reporter();
	}
	$STATISTICS_REPORTER->set_statistic_type($type);
	return $STATISTICS_REPORTER;
}


 ###############################################
# Load securiy-related functions
require_once("$INCLUDE_PATH/security.inc");

 ####################################################
# Load configuration variables into the global scope

function &get_mysource_session() {
	$session = &$_SESSION['SESSION'];
	if (!$session) {
		$_SESSION['SESSION'] = new MySourceSession("SESSION");
	}
	return $_SESSION['SESSION'];
}

global $SESSION;
require_once("$INCLUDE_PATH/session.inc");
$SESSION = &get_mysource_session();
$SESSION->start();


 ###############################################
# Start XSS Security...
start_xss_security($IN_BACKEND);


 ###########################################################
# This function may be called by the main script to process
# some standard mysource actions
function process_mysource_action($mysource_action='') {
	if(!$mysource_action) $mysource_action = $_REQUEST['mysource_action'];
	switch($mysource_action) {
		case 'display_image': # Prints an image (anywhere) nicely in the cornder on a black background
			$title = $_REQUEST['title'];
			$image = $_REQUEST['image'];
			$bgcolor = $_REQUEST['bgcolor'];
			# takes a parameter for the bgcolor, in hex, format XXXXXX of the generated page, 
			# if not set reverts to 000000 
			if ($bgcolor) {
				$bgcolor = '#'.$bgcolor;
			} else {
				$bgcolor='#000000';
			}
			?><html><head><title><?=gpc_stripslashes((($title)?$title:$image))?></title><body marginheight=0 marginwidth=0 topmargin=0 leftmargin=0 bgcolor=<?echo $bgcolor;?>><table width=100% height=100% border=0 cellspacing=0 cellpadding=0><tr><td align=center><img src="<?=gpc_stripslashes(htmlspecialchars($image))?>"></tr></td></table></body></html><?
			exit();
		case 'send_file': # Sends a file from somewhere in the filesystem, perhaps after some security checking
			$web = &get_web_system();
			$frontend = &$web->get_frontend();
			$frontend->send_file($_REQUEST['type'],$_REQUEST['file']);
			exit();
		case 'login_prompt': # Displays a login prompt _ PUT INTO THE DESIGN
			$web  = &get_web_system();
			if(!$web->current_siteid) {
					$web->extract_url_info_to_environment();
					$web->determine_current_objects();
			}
 			$site = $web->get_site();
			$_SESSION['SESSION']->login_prompt($site->name);
			exit();
		default: # The hard stuff
			break;
	}
}
?>