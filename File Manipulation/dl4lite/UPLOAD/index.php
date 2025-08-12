<?php
/*********************************************************
 * Name: index.php
 * Author: Dave Conley
 * Contact: realworld@blazefans.com
 * Description: Main program entry point
 * Version: 4.0
 * Last edited: 1st March, 2004
 *********************************************************/

// Set warning level
error_reporting  ( E_ERROR | E_WARNING | E_PARSE );
set_magic_quotes_runtime(0);

define( 'ROOT_PATH', "./" );

// Create our superglobal wotsit so we can save doing the same things over and over
class wotsit
{
	var $path = "";
	var $url = "";
	var $skinurl = "";
	var $cat_cache  = array();
	var $cats_saved = 0;
	var $image_cache = array();
    var $imgs_saved = 0;
	var $user_cache = array();
	var $error_log  = "";
	var $nav = "EMPTY";
	var $userbar = "";
	var $pagetitle = "";
	var $links = "";
	var $lang = array();
	var $loaded_templates = array();
	var $skin_global;
	var $skin_wrapper;
}

$rwdInfo = new wotsit();

// Load config
$CONFIG = array();
require_once (ROOT_PATH."/globalvars.php");

// Create helper globals
$rwdInfo->path = $CONFIG["sitepath"];
$rwdInfo->url = $CONFIG["siteurl"];
$rwdInfo->skinurl = ROOT_PATH."/skins/skin".($CONFIG["defaultSkin"]?$CONFIG["defaultSkin"]:1);
$rwdInfo->pagetitle = $CONFIG['isoffline']?$CONFIG['sitename']." [OFFLINE]":$CONFIG['sitename'];

// Load required libraries
require_once (ROOT_PATH."/functions/lang.php");
require_once (ROOT_PATH."/functions/global_functions.php");
require_once (ROOT_PATH."/functions/mysql.php");
require_once (ROOT_PATH."/functions/output.php");

// Our skin handler
$OUTPUT = new CDisplay();
// Global functions
$std    = new func();
// Get data from global arrays
$IN 	= $std->saveGlobals();

// Load the database
$dbinfo = array("sqlhost" => $CONFIG["sqlhost"],
				"sqlusername" => $CONFIG["sqlusername"],
				"sqlpassword" => $CONFIG["sqlpassword"],
				"sqldatabase" => $CONFIG["sqldatabase"],
				"sql_tbl_prefix" => $CONFIG["sqlprefix"]);

$DB = new mysql($dbinfo);

// Map Actions into files
$action = array(    "idx" => "browse",
					"useradddl" => "files",
					"dl" => "download",
					"offline" => "browse" );
					
if ($CONFIG['isoffline']) 
	$IN["ACT"] = "offline";				
if ( empty($IN["ACT"]) )
	$IN["ACT"] = "idx";
$langpref = $CONFIG['defaultLang']?$CONFIG['defaultLang']:1;
	
require_once (ROOT_PATH."/lang/".$langpref."/lang_".$action[$IN["ACT"]].".php");
$lang_1 = $lang;
require_once (ROOT_PATH."/lang/".$langpref."/lang_global.php");
$lang_2 = $lang;
require_once (ROOT_PATH."/lang/".$langpref."/lang_warn.php");
$lang_3 = $lang;
require_once (ROOT_PATH."/lang/".$langpref."/lang_error.php");
$lang_4 = $lang;
$rwdInfo->lang = array_merge($lang_1, $lang_2, $lang_3, $lang_4);

// Enable GZip?
if ( $CONFIG['usegzip'] )
{
	if(extension_loaded("zlib")) 
	{
		ob_start("ob_gzhandler");
	}
}

ob_start();
require (ROOT_PATH."/functions/".$action[$IN["ACT"]].".php");
$main_content = ob_get_contents();
ob_end_clean();

$std->userOptions();
$OUTPUT->print_output();

?>
