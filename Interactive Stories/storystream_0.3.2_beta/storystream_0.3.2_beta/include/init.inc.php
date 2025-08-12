<?php
/** @file init.inc.php

	This file contains critical constants and global variables
	that MUST be included in any executed script ONE TIME.
	
	@version 0.1
	@date October, 2003	
*/

// ** This must be the first call made. **
session_start ();

///////////////////////////////////////////////////
// GET THE CONFIGURATION PARAMETERS
//	Change the value of CONFIG_FILE if you
//	wish to move the config file to a different 
//	location on the server (for security 
//	reasons)
///////////////////////////////////////////////////

/* This is the absolute path to the frontpage folder of the storystream website - ends with a slash */

// automatically define the absolute path to the root of the site (as defined locally)
$baseDir = dirname(__FILE__);
$baseDir = dirname ($baseDir).'/';

// automatically define the base url
$baseUrl = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$baseUrl .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
$pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : getenv('PATH_INFO');
if (@$pathInfo) {
    //$baseUrl .= dirname($pathInfo);
} else {
    //$baseUrl .= isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));
}

/** This is the file that the user edits to various configuration parameters */
define ('CONFIG_FILE', $GLOBALS['SCRIPT_ROOT'].'config/config.php');
define ('SS_DEBUG', 1);

// Has the installer been run?
if (!file_exists ($baseDir.'config/dbconfig.php'))
{
	header ("Location: ./install/");
	exit ();
}

require_once (CONFIG_FILE);	

///////////////////////////////////////////////////
// INCLUDE FILES
///////////////////////////////////////////////////
require_once ('files.inc.php');

///////////////////////////////////////////////////
// GLOBAL OBJECTS
///////////////////////////////////////////////////
require_once ('globals.inc.php');

///////////////////////////////////////////////////
// SETUP THE LOGGED IN USER (if any)
///////////////////////////////////////////////////
$GLOBALS['APP']->setupUserEnvironment ();
$GLOBALS['APP']->setupDiscussionEnvironment ();

set_error_handler (array ('SSError', 'onError'));

?>
