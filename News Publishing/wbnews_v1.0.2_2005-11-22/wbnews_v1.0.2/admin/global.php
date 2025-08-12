<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 1st August 2005                         #||
||#     Filename: global.php                             #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package AdminCP
*/

if (!defined('wbnews'))
	die ("Hacking Attempt");

// we are currently debugging so we must set this to true
define("DEBUG", true);

include "../config.php";
include $config['installdir']."/includes/constants.php"; //holds constants for tables etc
include $config['installdir']."/includes/admin-functions.php"; // get administration functions
include $config['installdir']."/includes/common.php"; // get the common file

// get database and template classes
include $config['installdir']."/includes/lib/db_mysql.php";
include $config['installdir']."/includes/lib/template.php";

/* initiate database properties and initiate a connection to the database */
$dbclass = new DB($config['dbhost'], base64_decode($config['dbname']), base64_decode($config['dbuser']), base64_decode($config['dbpass']));
$dbclass->db_connect();

//########################## NEWS CONFIGURATION ##########################//
if (!($newsConfig = $dbclass->db_fetchall("SELECT var, value FROM ".TBL_NEWSCONFIG, "var", "value")))
    die ("Fatal Error: Couldnt retrieve News Configuration Settings");
    
/* get theme, and configuration info */
$theme = $dbclass->db_fetcharray($dbclass->db_query("SELECT themepath as THEME_DIRECTORY FROM " . TBL_THEMES . " WHERE themeid = '" . $newsConfig['themeid'] . "'"));
/* */
 
// initiate user session
session_start();

$GLOBAL['LOGGED_USERNAME'] = (isset($_SESSION['wbnews-admin_login']) ? $_SESSION['wbnews-admin_login']['username'] : false);
$GLOBAL['NEWS_VERSION'] = $config['version'];
$GLOBAL = array_merge($GLOBAL, $theme);

/* initiate template object */
$tpl = new template($config['installdir']."/templates", "admin", $theme['THEME_DIRECTORY']);
include $config['installdir']."/templates/".$theme['THEME_DIRECTORY']."/admin/theme_info.php";

?>
