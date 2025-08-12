<?php

/***************************************************************************

 inlinepoll.php
 ---------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

global $_SERVER;
if ( (substr($_SERVER["PHP_SELF"],-11) == 'control.php') ||
	 (substr($_SERVER["PHP_SELF"],-10) == 'module.php') ||
	 (substr($_SERVER["PHP_SELF"],-16) == 'showcontents.php') ) {
	 require_once('../moduleSec.php');
} else {
	require_once('../moduleSec.php');
}

global $EZ_SESSION_VARS;


$GLOBALS["ModuleName"] = 'poll';

$linkref = $nLink;
$chainlink = explode('/',$linkref);
$modfilename = array_pop($chainlink);
$GLOBALS["modfiledir"] = implode('/',$chainlink);
include($GLOBALS["modfiledir"]."/moduleref.php");

include_once ($GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_admin.php");
include_once ($GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_main.php");
include_languagefile ($GLOBALS["modules_home"].$GLOBALS["ModuleRef"]."/",$GLOBALS["gsLanguage"],"lang_poll.php");

include_once($GLOBALS["modules_home"]."modfunctions.php");
//	Note that inline poll doesn't support voting (only the module), but we need to include the form header
//	and footer functions because they're referenced in poll_summary for use by the module.
//  They may already have been defined in module.php (if the inline poll is called in a content article that
//	appears on all pages, and that includes module pages), so we test for their existence before including
//	the file
if (!(function_exists('SubFormHeader'))) {
	include_once($GLOBALS["modfiledir"]."/pollformfunctions.php");
}

GetModuleData($GLOBALS["ModuleRef"]);

$GLOBALS["ScreenWidthMultiplier"] = $GLOBALS["poll"]["InlineScreenWidthMultiplier"];


$GLOBALS["inline"] = True;
include($GLOBALS["modfiledir"]."/poll_summary.php");

?>
